<?php
require_once 'config.php';

// Verificar se é admin via sessão (adicione isso)
session_start();
if ($_SESSION['admin'] != 1) die("Acesso negado.");

$idRequisicao = $_GET['id'];

try {
    $pdo->beginTransaction();

    // 1. Atualizar status da requisição
    $stmt = $pdo->prepare("UPDATE requisicoes SET status = 'pronto_para_levantar', data_conclusao = NOW() WHERE id = ?");
    $stmt->execute([$idRequisicao]);

    // 2. Enviar email para o utilizador
    $stmt = $pdo->prepare("
        SELECT u.email, u.nome, l.titulo 
        FROM requisicoes r 
        JOIN utilizadores u ON u.id = r.id_utilizador 
        JOIN livros l ON l.cod_isbn = r.cod_isbn 
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $dados = $stmt->fetch();

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    // ... (configuração SMTP igual ao add_to_cart.php)
    $mail->setFrom('bookhub.adm1@gmail.com', 'BOOKhub');
    $mail->addAddress($dados['email']);
    $mail->Subject = 'Livro Pronto para Levantamento';
    $mail->Body = "
        <h3>Olá {$dados['nome']},</h3>
        <p>O livro <strong>{$dados['titulo']}</strong> está disponível para levantamento na biblioteca.</p>
    ";
    $mail->send();

    $pdo->commit();
    header("Location: gerir-requisicoes.php?success=1");
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}