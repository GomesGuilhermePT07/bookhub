<?php
session_start();
require_once '../config.php';

// Verificar admin
if ($_SESSION['admin'] != 1) die("Acesso negado.");

$idRequisicao = $_GET['id'];

try {
    // Buscar dados da requisição
    $stmt = $pdo->prepare("
        SELECT u.email, u.nome, l.titulo, l.autor, l.cod_isbn 
        FROM requisicoes r 
        JOIN utilizadores u ON u.id = r.id_utilizador 
        JOIN livros l ON l.cod_isbn = r.cod_isbn 
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $dados = $stmt->fetch();

    // Enviar email com botão de devolução
    $linkDevolucao = "http://localhost:8080/ModuloProjeto/assets/php/iniciar_devolucao.php?id=" . $idRequisicao;
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    // ... (configuração SMTP igual ao add_to_cart.php)
    $mail->setFrom('bookhub.adm1@gmail.com', 'BOOKhub');
    $mail->addAddress($dados['email']);
    $mail->Subject = 'Devolução do Livro: ' . $dados['titulo'];
    $mail->Body = "
        <h3>Olá {$dados['nome']},</h3>
        <p>Por favor, devolva o livro <strong>{$dados['titulo']}</strong> (ISBN: {$dados['cod_isbn']}).</p>
        <a href='$linkDevolucao' style='background: #4CAF50; color: white; padding: 10px; text-decoration: none;'>Confirmar Devolução</a>
    ";
    $mail->send();

    header("Location: ../gerir-requisicoes.php?success=2");
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>