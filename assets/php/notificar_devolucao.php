<?php
session_start();
require_once 'config.php';
require_once '../../vendor/autoload.php';

// Verificar admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado.");
}

$idRequisicao = $_GET['id'];

try {
    // Buscar dados do utilizador
    $stmt = $pdo->prepare("
        SELECT u.email, u.nome_completo, l.titulo 
        FROM requisicoes r 
        JOIN utilizadores u ON u.id = r.id_utilizador 
        JOIN livros l ON l.cod_isbn = r.cod_isbn 
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $dados = $stmt->fetch();

    // Enviar email para o utilizador
    if ($dados) {
        $to = $dados['email'];
        $subject = "Solicitação de Devolução de Livro";
        $message = "Olá {$dados['nome_completo']},\n\n";
        $message .= "O prazo para o livro '{$dados['titulo']}' está a acabar.\n";
        $message .= "Por favor, devolva o livro na biblioteca o mais brevemente possível.\n\n";
        $message .= "Atenciosamente,\nEquipe BOOKhub";
        
        $headers = "From: bookhub.adm1@gmail.com" . "\r\n" .
                   "Reply-To: bookhub.adm1@gmail.com";
        
        mail($to, $subject, $message, $headers);
    }

    // Marcar como devolução solicitada
    $updateStmt = $pdo->prepare("
        UPDATE requisicoes
        SET data_devolucao = '1970-01-01 00:00:01'
        WHERE id = ?
    ");
    $updateStmt->execute([$idRequisicao]);

    header("Location: ../../gerir-requisicoes.php?success=2");
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}