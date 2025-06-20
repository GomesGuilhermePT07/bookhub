<?php
session_start();
require_once 'config.php';
require_once '../../vendor/autoload.php'; // Adicione esta linha

// Verificar admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado.");
}

$idRequisicao = $_GET['id'];

try {
    // Buscar dados do usuário e do livro
    $stmt = $pdo->prepare("
        SELECT u.email, u.nome_completo, l.titulo 
        FROM requisicoes r 
        JOIN utilizadores u ON u.id = r.id_utilizador 
        JOIN livros l ON l.cod_isbn = r.cod_isbn 
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $dados = $stmt->fetch();

    if ($dados) {
        // Configurar PHPMailer
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom(SMTP_USER, 'BOOKhub');
        $mail->addAddress($dados['email'], $dados['nome_completo']);
        $mail->Subject = "Solicitação de Devolução de Livro";
        
        // Corpo do email em formato texto
        $mail->isHTML(false); // Usar formato texto simples
        $mail->Body = "Olá {$dados['nome_completo']},\n\n";
        $mail->Body .= "Solicitamos a devolução do livro '{$dados['titulo']}'.\n";
        $mail->Body .= "Por favor, dirija-se à biblioteca para efetuar a devolução.\n\n";
        $mail->Body .= "Atenciosamente,\nEquipe BOOKhub";
        
        // Configurações adicionais para evitar problemas com SSL
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        $mail->send();
    }

    // Marcar como devolução solicitada
    $updateStmt = $pdo->prepare("
        UPDATE requisicoes 
        SET data_devolucao = '1970-01-01 00:00:01'
        WHERE id = ?
    ");
    $updateStmt->execute([$idRequisicao]);

    header("Location: ../../gerir-requisicoes.php?success=2");
    exit;
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}