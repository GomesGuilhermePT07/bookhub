<?php
session_start();
require_once 'config.php';
require_once '../../vendor/autoload.php';

if (!isset($_SESSION['id'])) {
    header("Location: /ModuloProjeto/logins/login.php");
    exit;
}

$userId = $_SESSION['id'];

try {
    $pdo->beginTransaction();

    // Buscar itens do carrinho
    $stmt = $pdo->prepare("
        SELECT c.cod_isbn, c.quantidade, l.titulo, l.autor 
        FROM carrinho c 
        JOIN livros l ON c.cod_isbn = l.cod_isbn 
        WHERE c.id_utilizador = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        $_SESSION['cart_error'] = "Seu carrinho está vazio.";
        header("Location: ../../cart.php");
        exit;
    }

    // Verificar disponibilidade
    foreach ($cartItems as $item) {
        $checkStmt = $pdo->prepare("SELECT disponivel FROM livros WHERE cod_isbn = ?");
        $checkStmt->execute([$item['cod_isbn']]);
        $livro = $checkStmt->fetch();
        
        if (!$livro || $livro['disponivel'] < $item['quantidade']) {
            $_SESSION['cart_error'] = "O livro '{$item['titulo']}' não tem unidades suficientes disponíveis.";
            header("Location: ../../cart.php");
            exit;
        }
    }

    // Criar requisições
    $requisicoes = [];
    foreach ($cartItems as $item) {
        for ($i = 0; $i < $item['quantidade']; $i++) {
            $stmtReq = $pdo->prepare("
                INSERT INTO requisicoes (id_utilizador, cod_isbn, data_requisicao, status)
                VALUES (?, ?, NOW(), 'pendente')
            ");
            $stmtReq->execute([$userId, $item['cod_isbn']]);
            $requisicoes[] = $pdo->lastInsertId();
        }

        // Atualizar estoque
        $updateStmt = $pdo->prepare("
            UPDATE livros 
            SET disponivel = disponivel - ? 
            WHERE cod_isbn = ?
        ");
        $updateStmt->execute([$item['quantidade'], $item['cod_isbn']]);
    }

    // Limpar carrinho
    $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id_utilizador = ?");
    $stmt->execute([$userId]);

    // Buscar dados do usuário - CORREÇÃO: usando nome_completo
    $userStmt = $pdo->prepare("SELECT nome_completo, email FROM utilizadores WHERE id = ?");
    $userStmt->execute([$userId]);
    $user = $userStmt->fetch();
    
    // Preparar lista de livros
    $livrosLista = array_map(function($item) {
        return "• {$item['titulo']} - {$item['autor']} (ISBN: {$item['cod_isbn']}) - {$item['quantidade']} unidade(s)";
    }, $cartItems);
    
    $livrosTexto = implode("\n", $livrosLista);
    
    // Configurar PHPMailer
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    // Configurações do servidor SMTP
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port = SMTP_PORT;
    
    // Configurações adicionais necessárias para o Gmail
    $mail->SMTPOptions = [
        'ssl' => [
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        ]
    ];
    
    // Remetente
    $mail->setFrom(SMTP_USER, 'BOOKhub - Suporte');
    
    // Destinatário (admin)
    $mail->addAddress('bookhub.adm1@gmail.com', 'Administrador BOOKhub');
    
    // Assunto
    $mail->Subject = 'Nova Requisição de Livros';
    
    // Corpo do email
    $mail->Body = "
        Nova requisição realizada por:
        Nome: {$user['nome_completo']}
        Email: {$user['email']}
        
        Livros requisitados:
        {$livrosTexto}
        
        Total de itens: " . count($requisicoes) . "
        IDs das Requisições: " . implode(", ", $requisicoes) . "
    ";

    // Tentar enviar email
    if(!$mail->send()) {
        throw new Exception('Erro ao enviar email: ' . $mail->ErrorInfo);
    }

    $pdo->commit();
    $_SESSION['cart_success'] = "Requisição realizada com sucesso! Um email foi enviado para o administrador.";
    header("Location: ../../cart.php");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['cart_error'] = "Erro ao processar requisição: " . $e->getMessage();
    header("Location: ../../cart.php");
    exit;
}