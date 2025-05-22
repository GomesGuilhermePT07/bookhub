<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION['id'])) {
    header("Location: /ModuloProjeto/logins/login.php");
    exit;
}

try {
    $userId = $_SESSION['id'];
    
    // Buscar informações do usuário
    $stmtUser = $pdo->prepare("SELECT nome_completo, email FROM utilizadores WHERE id = ?");
    $stmtUser->execute([$userId]);
    $user = $stmtUser->fetch();

    // Buscar itens do carrinho
    $stmtCarrinho = $pdo->prepare("
        SELECT l.titulo, l.autor, l.cod_isbn 
        FROM carrinho c 
        JOIN livros l ON c.cod_isbn = l.cod_isbn 
        WHERE c.id_utilizador = ?
    ");
    $stmtCarrinho->execute([$userId]);
    $itens = $stmtCarrinho->fetchAll();

    if (empty($itens)) {
        $_SESSION['error'] = "Carrinho vazio!";
        header("Location: ../../cart.php");
        exit;
    }

    // Construir corpo do email
    $emailBody = "<h3>Nova Requisição de Livros</h3>";
    $emailBody .= "<p><strong>Utilizador:</strong> {$user['nome_completo']} ({$user['email']})</p>";
    $emailBody .= "<p><strong>Livros solicitados:</strong></p><ul>";
    
    foreach ($itens as $item) {
        $emailBody .= "<li>{$item['titulo']} (Autor: {$item['autor']}, ISBN: {$item['cod_isbn']})</li>";
    }
    
    $emailBody .= "</ul>";

    // Enviar email para admin
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    // Configuração SMTP (igual aos outros scripts)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bookhub.adm1@gmail.com'; // Teu email
    $mail->Password = 'bookhubAdministrador1!'; // Tua senha
    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->setFrom('bookhub.adm1@gmail.com', 'BOOKhub');
    $mail->addAddress('bookhub.adm1@gmail.com'); // Email do admin
    $mail->Subject = 'Nova Requisição de Livros';
    $mail->isHTML(true);
    $mail->Body = $emailBody;

    if ($mail->send()) {
        // Limpar carrinho após envio bem sucedido
        $pdo->prepare("DELETE FROM carrinho WHERE id_utilizador = ?")->execute([$userId]);
        $_SESSION['success'] = "Requisição enviada com sucesso! O administrador será notificado.";
    } else {
        $_SESSION['error'] = "Erro ao enviar requisição. Tente novamente.";
    }

    header("Location: ../cart.php");
    exit;

} catch (Exception $e) {
    $_SESSION['error'] = "Erro: " . $e->getMessage();
    header("Location: ../cart.php");
    exit;
}