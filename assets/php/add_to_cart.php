<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = $_POST['isbn'];
    $userId = $_SESSION['id'];

    // Verificar se o livro existe
    $checkStmt = $conn->prepare("SELECT cod_isbn FROM livros WHERE cod_isbn = ?");
    $checkStmt->bind_param("s", $isbn);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows === 0) {
        $_SESSION['error'] = "Livro não encontrado.";
        $checkStmt->close();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
    $checkStmt->close();

    $cartCheckStmt = $conn->prepare("SELECT cod_isbn FROM carrinho WHERE id_utilizador = ? AND cod_isbn = ?");
    $cartCheckStmt->bind_param("is", $userId, $isbn);
    $cartCheckStmt->execute();
    $cartCheckStmt->store_result();

    if ($cartCheckStmt->num_rows > 0) {
        $_SESSION['error'] = "Este livro já está no seu carrinho.";
        $cartCheckStmt->close();
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
    $cartCheckStmt->close();

    // --- Alterado: INSERT simples sem ON DUPLICATE ---
    $stmt = $conn->prepare("INSERT INTO carrinho (id_utilizador, cod_isbn, quantidade) VALUES (?, ?, 1)");
    $stmt->bind_param("is", $userId, $isbn);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Livro adicionado ao carrinho!";
    } else {
        $_SESSION['error'] = "Erro ao adicionar ao carrinho: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>