<?php
session_start();
require_once '../config.php';

// Verificação de admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado.");
}

$idRequisicao = $_GET['id'];

try {
    $pdo->beginTransaction();

    // 1. Atualizar status
    $stmt = $pdo->prepare("UPDATE requisicoes SET status = 'devolvido', data_devolucao = NOW() WHERE id = ?");
    $stmt->execute([$idRequisicao]);

    // 2. Liberar livro (correção crítica)
    $stmt = $pdo->prepare("
        UPDATE livros l
        JOIN requisicoes r ON l.cod_isbn = r.cod_isbn
        SET l.disponivel = l.disponivel + r.quantidade
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);

    $pdo->commit();
    header("Location: " . SITE_URL . "../../gerir-requisicoes.php?success=3");
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}