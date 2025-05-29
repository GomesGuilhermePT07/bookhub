<?php
session_start();
require_once 'config.php';

// Verificar admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado.");
}

$idRequisicao = $_GET['id'];

try {
    // Buscar dados do utilizador
    $stmt = $pdo->prepare("
        SELECT u.email, u.nome, l.titulo, l.cod_isbn 
        FROM requisicoes r 
        JOIN utilizadores u ON u.id = r.id_utilizador 
        JOIN livros l ON l.cod_isbn = r.cod_isbn 
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $dados = $stmt->fetch();

    require '../../vendor/autoload.php';
    
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = 'tls';
    $mail->Port = SMTP_PORT;
    
    $mail->setFrom(SMTP_USER, 'BOOKhub');
    $mail->addAddress($dados['email']);
    
    $linkDevolucao = SITE_URL . "/iniciar_devolucao.php?id=" . $idRequisicao;
    
    $mail->isHTML(true);
    $mail->Subject = 'Devolução do Livro: ' . $dados['titulo'];
    $mail->Body = "
        <h3>Olá {$dados['nome']},</h3>
        <p>Por favor, devolva o livro <strong>{$dados['titulo']}</strong> (ISBN: {$dados['cod_isbn']}).</p>
        <a href='$linkDevolucao'>Confirmar Devolução</a>
    ";

    $mail->send();

    header("Location: /ModuloProjeto/gerir-requisicoes.php?success=2");
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}