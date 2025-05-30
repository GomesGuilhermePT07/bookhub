<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_GET['isbn'])) {
    echo json_encode(['status' => 'error', 'message' => 'ISBN não especificado']);
    exit;
}

$userId = $_SESSION['id'];
$isbn = $_GET['isbn'];

try {
    // Verificar se existe no carrinho
    $checkStmt = $pdo->prepare("
        SELECT quantidade 
        FROM carrinho 
        WHERE id_utilizador = ? AND cod_isbn = ?
    ");
    $checkStmt->execute([$userId, $isbn]);
    
    if (!$checkStmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Item não encontrado no carrinho']);
        exit;
    }

    // Remover do carrinho
    $stmt = $pdo->prepare("
        DELETE FROM carrinho 
        WHERE id_utilizador = ? AND cod_isbn = ?
    ");
    $stmt->execute([$userId, $isbn]);
    
    // Obter contagem atualizada do carrinho
    $countStmt = $pdo->prepare("SELECT SUM(quantidade) AS total FROM carrinho WHERE id_utilizador = ?");
    $countStmt->execute([$userId]);
    $countData = $countStmt->fetch();
    $cartCount = isset($countData['total']) ? $countData['total'] : 0;

    echo json_encode([
        'status' => 'success', 
        'cartCount' => $cartCount,
        'message' => 'Item removido do carrinho'
    ]);

    // Atualizar disponibilidade do livro
    $atualizarDisponibilidade = $conn->prepare("UPDATE livros SET disponivel = 1 WHERE cod_isbn = ?");
    $atualizarDisponibilidade->bind_param("s", $cod_isbn);
    $atualizarDisponibilidade->execute();
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao remover item']);
}