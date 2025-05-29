<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id'])) {
    echo "not_logged_in";
    exit;
}

$isbn = isset($_POST['isbn']) ? $_POST['isbn'] : null;
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;

if (!$isbn) {
    echo "invalid_isbn";
    exit;
}

$userId = $_SESSION['id'];

try {
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

    echo "success";
} catch (PDOException $e) {
    echo "error: " . $e->getMessage();
}