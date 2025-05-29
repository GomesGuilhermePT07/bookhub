<?php
session_start();
require_once 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Usuário não autenticado']);
    exit;
}

$isbn = isset($_POST['isbn']) ? $_POST['isbn'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (!$isbn) {
    echo json_encode(['status' => 'error', 'message' => 'ISBN inválido']);
    exit;
}

$userId = $_SESSION['id'];

try {
    // Verificar se livro existe
    $checkStmt = $pdo->prepare("SELECT cod_isbn FROM livros WHERE cod_isbn = ?");
    $checkStmt->execute([$isbn]);
    
    if (!$checkStmt->fetch()) {
        echo json_encode(['status' => 'error', 'message' => 'Livro não encontrado']);
        exit;
    }

    // Verificar se já está no carrinho
    $checkStmt = $pdo->prepare("
        SELECT quantidade 
        FROM carrinho 
        WHERE id_utilizador = ? AND cod_isbn = ?
    ");
    $checkStmt->execute([$userId, $isbn]);
    $existing = $checkStmt->fetch();

    if ($existing) {
        // Atualizar quantidade
        $newQuantity = $existing['quantidade'] + $quantity;
        $stmt = $pdo->prepare("
            UPDATE carrinho 
            SET quantidade = ? 
            WHERE id_utilizador = ? AND cod_isbn = ?
        ");
        $stmt->execute([$newQuantity, $userId, $isbn]);
    } else {
        // Inserir novo item
        $stmt = $pdo->prepare("
            INSERT INTO carrinho (id_utilizador, cod_isbn, quantidade) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$userId, $isbn, $quantity]);
    }

    // Obter contagem atualizada do carrinho
    $countStmt = $pdo->prepare("SELECT SUM(quantidade) AS total FROM carrinho WHERE id_utilizador = ?");
    $countStmt->execute([$userId]);
    $countData = $countStmt->fetch();
    $cartCount = isset($countData['total']) ? $countData['total'] : 0;

    echo json_encode([
        'status' => 'success', 
        'cartCount' => $cartCount,
        'message' => 'Livro adicionado ao carrinho'
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Erro ao adicionar ao carrinho']);
}