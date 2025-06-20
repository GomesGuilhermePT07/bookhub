<?php
session_start();
require_once 'config.php';

// Verificar admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado. Somente administradores podem executar esta ação.");
}

// Verificar se o ID foi fornecido
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID da requisição não especificado.");
}

$idRequisicao = $_GET['id'];

try {
    // Iniciar transação
    $pdo->beginTransaction();

    // 1. Verificar se a devolução foi solicitada
    $checkStmt = $pdo->prepare("
        SELECT cod_isbn 
        FROM requisicoes 
        WHERE id = ? 
        AND status = 'com_o_aluno'
        AND data_devolucao = '1970-01-01 00:00:01'
    ");
    
    if (!$checkStmt->execute([$idRequisicao])) {
        throw new Exception("Erro ao verificar requisição: " . implode(" ", $checkStmt->errorInfo()));
    }
    
    $requisicao = $checkStmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$requisicao) {
        throw new Exception("Não é possível concluir: devolução não solicitada, status incorreto ou já processada.");
    }

    // 2. Atualizar status e data de devolução
    $updateStmt = $pdo->prepare("
        UPDATE requisicoes 
        SET status = 'devolvido', 
            data_devolucao = NOW() 
        WHERE id = ?
    ");
    
    if (!$updateStmt->execute([$idRequisicao])) {
        throw new Exception("Erro ao atualizar requisição: " . implode(" ", $updateStmt->errorInfo()));
    }

    // 3. Atualizar estoque
    $stockStmt = $pdo->prepare("
        UPDATE livros 
        SET disponivel = disponivel + 1 
        WHERE cod_isbn = ?
    ");
    
    if (!$stockStmt->execute([$requisicao['cod_isbn']])) {
        throw new Exception("Erro ao atualizar estoque: " . implode(" ", $stockStmt->errorInfo()));
    }

    // Commit da transação
    $pdo->commit();
    
    header("Location: /ModuloProjeto/gerir-requisicoes.php?success=3");
    exit();
    
} catch (Exception $e) {
    // Rollback em caso de erro
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    // Mensagem de erro detalhada
    die("Erro: " . $e->getMessage());
}