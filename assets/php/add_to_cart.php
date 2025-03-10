<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
// require_once 'check_login.php';
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

    // Adicionar ou atualizar o carrinho
    $stmt = $conn->prepare("
        INSERT INTO carrinho (id_utilizador, cod_isbn, quantidade) 
        VALUES (?, ?, 1) 
        ON DUPLICATE KEY UPDATE quantidade = quantidade + 1
    ");
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