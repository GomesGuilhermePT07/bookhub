<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado']);
    exit;
}

$isbn = isset($_GET['isbn']) ? $_GET['isbn'] : null;

if (!$isbn) {
    echo json_encode(['status' => 'error', 'message' => 'ISBN inválido']);
    exit;
}

$userId = $_SESSION['id'];

try {
    // Obter quantidade antes de remover
    $getQtyStmt = $pdo->prepare("SELECT quantidade FROM carrinho WHERE id_utilizador = ? AND cod_isbn = ?");
    $getQtyStmt->execute([$userId, $isbn]);
    $item = $getQtyStmt->fetch();
    
    if (!$item) {
        echo json_encode(['status' => 'error', 'message' => 'Item não encontrado no carrinho']);
        exit;
    }

    $quantity = $item['quantidade'];
    
    // Remover item do carrinho
    $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id_utilizador = ? AND cod_isbn = ?");
    $stmt->execute([$userId, $isbn]);
    
    // Obter nova contagem do carrinho
    $countStmt = $pdo->prepare("SELECT SUM(quantidade) AS total FROM carrinho WHERE id_utilizador = ?");
    $countStmt->execute([$userId]);
    $countData = $countStmt->fetch();
    $cartCount = isset($countData['total']) ? $countData['total'] : 0;

    echo json_encode([
        'status' => 'success',
        'cartCount' => $cartCount,
        'message' => 'Item removido do carrinho'
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao remover item: ' . $e->getMessage()]);
}