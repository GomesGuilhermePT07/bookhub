<?php
session_start();
require_once 'config.php';

// Verificar admin
if ($_SESSION['admin'] != 1) die("Acesso negado.");

$idRequisicao = $_GET['id'];

try {
    $pdo->beginTransaction();

    // 1. Atualizar status da requisição
    $stmt = $pdo->prepare("UPDATE requisicoes SET status = 'devolvido', data_devolucao = NOW() WHERE id = ?");
    $stmt->execute([$idRequisicao]);

    // 2. Liberar livro
    $stmt = $pdo->prepare("UPDATE livros SET disponivel = 1 WHERE cod_isbn = (SELECT cod_isbn FROM requisicoes WHERE id = ?)");
    $stmt->execute([$idRequisicao]);

    $pdo->commit();
    header("Location: ../gerir-requisicoes.php?success=3");
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}
?>