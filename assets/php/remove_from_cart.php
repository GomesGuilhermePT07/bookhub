<?php
session_start();
require_once 'check_login.php';
require_once 'config.php';

if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
    $userId = $_SESSION['id'];

    try {
        $pdo->beginTransaction();

        // Passo 1: Remover do carrinho
        $deleteStmt = $pdo->prepare("DELETE FROM carrinho WHERE id_utilizador = ? AND cod_isbn = ?");
        $deleteStmt->execute([$userId, $isbn]);

        // Passo 2: Liberar livro
        $updateStmt = $pdo->prepare("UPDATE livros SET disponivel = TRUE WHERE cod_isbn = ?");
        $updateStmt->execute([$isbn]);

        $pdo->commit();
        $_SESSION['success'] = "Livro removido do carrinho!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erro: " . $e->getMessage();
    }
}
header("Location: ../../cart.php");
exit;
?>