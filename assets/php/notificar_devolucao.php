<?php
session_start();
require_once 'config.php';

// Verificar admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado.");
}

$idRequisicao = $_GET['id'];

try {
    // Buscar dados do usuário
    $stmt = $pdo->prepare("
        SELECT u.email, u.nome, l.titulo 
        FROM requisicoes r 
        JOIN utilizadores u ON u.id = r.id_utilizador 
        JOIN livros l ON l.cod_isbn = r.cod_isbn 
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $dados = $stmt->fetch();

    // Enviar email para o usuário
    if ($dados) {
        $to = $dados['email'];
        $subject = "Solicitação de Devolução de Livro";
        $message = "Olá {$dados['nome']},\n\n";
        $message .= "O prazo para o livro '{$dados['titulo']}' está terminando.\n";
        $message .= "Por favor, devolva o livro na biblioteca o mais breve possível.\n\n";
        $message .= "Atenciosamente,\nEquipe BOOKhub";
        
        $headers = "From: bookhub.adm1@gmail.com" . "\r\n" .
                   "Reply-To: bookhub.adm1@gmail.com";
        
        mail($to, $subject, $message, $headers);
    }

    header("Location: ../../gerir-requisicoes.php?success=2");
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}