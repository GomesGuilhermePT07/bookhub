<?php
session_start();
require_once 'check_login.php';
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = $_POST['isbn'];
    $quantity = (int)$_POST['quantity'];
    $userId = $_SESSION['id'];

    if ($quantity < 1) {
        // Remove o item se a quantidade for 0
        $stmt = $conn->prepare("DELETE FROM carrinho WHERE id_utilizador = ? AND cod_isbn = ?");
        $stmt->bind_param("is", $userId, $isbn);
    } else {
        $stmt = $conn->prepare("UPDATE carrinho SET quantidade = ? WHERE id_utilizador = ? AND cod_isbn = ?");
        $stmt->bind_param("iis", $quantity, $userId, $isbn);
    }

    if ($stmt->execute()) {
        $_SESSION['success'] = "Carrinho atualizado!";
    } else {
        $_SESSION['error'] = "Erro ao atualizar: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: ../../cart.php");
    exit;
}
?>