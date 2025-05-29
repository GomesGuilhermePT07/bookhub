<?php
session_start();
require_once 'config.php';

// Verificar admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado.");
}

$idRequisicao = $_GET['id'];

try {
    $pdo->beginTransaction();

    // Atualizar status
    $stmt = $pdo->prepare("
        UPDATE requisicoes 
        SET status = 'pronto_para_levantar', data_conclusao = NOW() 
        WHERE id = ?
    ");
    $stmt->execute([$idRequisicao]);

    // Buscar dados para email
    $stmt = $pdo->prepare("
        SELECT u.email, u.nome, l.titulo 
        FROM requisicoes r 
        JOIN utilizadores u ON u.id = r.id_utilizador 
        JOIN livros l ON l.cod_isbn = r.cod_isbn 
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $dados = $stmt->fetch();

    require 'vendor/autoload.php';
    
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
    $mail->isHTML(true);
    $mail->Subject = 'Livro Pronto para Levantamento';
    $mail->Body = "
        <h3>Olá {$dados['nome']},</h3>
        <p>O livro <strong>{$dados['titulo']}</strong> está disponível para levantamento na biblioteca.</p>
    ";

    $mail->send();
    $pdo->commit();

    $pdo->commit();
    header("Location: /ModuloProjeto/gerir-requisicoes.php?success=1");
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}