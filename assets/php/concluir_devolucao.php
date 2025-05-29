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
        SET status = 'devolvido', data_devolucao = NOW() 
        WHERE id = ?
    ");
    $stmt->execute([$idRequisicao]);

    // Buscar ISBN e quantidade
    $stmt = $pdo->prepare("
        SELECT cod_isbn, quantidade 
        FROM requisicoes 
        WHERE id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $requisicao = $stmt->fetch();

    // Atualizar estoque
    if ($requisicao) {
        $updateStmt = $pdo->prepare("
            UPDATE livros 
            SET disponivel = disponivel + ? 
            WHERE cod_isbn = ?
        ");
        $updateStmt->execute([
            $requisicao['quantidade'],
            $requisicao['cod_isbn']
        ]);
    }

    $pdo->commit();
    header("Location: /ModuloProjeto/gerir-requisicoes.php?success=3");
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}