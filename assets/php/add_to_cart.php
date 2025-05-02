<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = $_POST['isbn'];
    $userId = $_SESSION['id'];

    try{
         // Verificar se o livro existe
        $checkStmt = $pdo->prepare("SELECT cod_isbn FROM livros WHERE cod_isbn = ?");
        $checkStmt->execute([$isbn]);
        
        if ($checkStmt->rowCount() === 0) {
            $_SESSION['error'] = "Livro não encontrado.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Verificar se já está no carrinho
        $cartCheckStmt = $pdo->prepare("SELECT cod_isbn FROM carrinho WHERE id_utilizador = ? AND cod_isbn = ?");
        $cartCheckStmt->execute([$userId, $isbn]);

        if ($cartCheckStmt->rowCount() > 0) {
            $_SESSION['error'] = "Este livro já está no seu carrinho.";
            header("Location: " . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // Inserir no carrinho
        $stmt = $pdo->prepare("INSERT INTO carrinho (id_utilizador, cod_isbn, quantidade) VALUES (?, ?, 1)");    
        if ($stmt->execute([$userId, $isbn])) {
            $_SESSION['success'] = "Livro adicionado ao carrinho!";
        } else {
            $_SESSION['error'] = "Erro ao adicionar ao carrinho: ";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Erro: " . $e->getMessage();
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>