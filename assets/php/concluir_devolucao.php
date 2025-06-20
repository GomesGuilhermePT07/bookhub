<?php
session_start();
require_once 'config.php';

// Ativar exibição de erros para debug (remover após corrigir)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Verificar admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado.");
}

$idRequisicao = $_GET['id'];

try {
    $pdo->beginTransaction();

    // Verificar se a devolução foi solicitada
    $checkStmt = $pdo->prepare("
        SELECT cod_isbn
        FROM requisicoes
        WHERE id = ?
        AND status = 'com_o_aluno'
        AND data_devolucao = '1970-01-01 00:00:01'
    ");
    $checkStmt->execute([$idRequisicao]);
    $requisicao = $checkStmt->fetch();

    if (!$requisicao) {
        die("Não é possível concluir: devolução não solicitada ou já processada.");
    }

    // Atualizar status
    $stmt = $pdo->prepare("
        UPDATE requisicoes 
        SET status = 'devolvido', 
            data_devolucao = NOW() 
        WHERE id = ?
    ");
    $stmt->execute([$idRequisicao]);

    // // Buscar ISBN (como não temos quantidade na requisição, assumimos 1 unidade)
    // $stmt = $pdo->prepare("
    //     SELECT cod_isbn 
    //     FROM requisicoes 
    //     WHERE id = ?
    // ");
    // $stmt->execute([$idRequisicao]);
    // $requisicao = $stmt->fetch();

    // Atualizar estoque (assumindo 1 unidade)
    // if ($requisicao) {
    //     $updateStmt = $pdo->prepare("
    //         UPDATE livros 
    //         SET disponivel = disponivel + 1 
    //         WHERE cod_isbn = ?
    //     ");
    //     $updateStmt->execute([$requisicao['cod_isbn']]);
    // }
    $updateStmt = $pdo->prepare("
        UPDATE livros
        SET disponivel = disponivel + 1 
        WHERE cod_isbn = ?   
    ");
    $updateStmt->execute([$idRequisicao['cod_isbn']]);

    $pdo->commit();
    header("Location: /ModuloProjeto/gerir-requisicoes.php?success=3");
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}