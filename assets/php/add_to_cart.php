<?php
session_start();
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = $_POST['isbn'];
    $userId = $_SESSION['id'];

    try{
        $pdo->beginTransaction(); // Inicia a transação

        // Passo 1: Verificar disponibilidade
        $checkLivro = $pdo->prepare("SELECT disponivel FROM livros WHERE cod_isbn = ? FOR UPDATE;");
        $checkLivro->execute([$isbn]);
        $livro = $checkLivro->fetch();

        if (!$livro || !$livro['disponivel']) {
            $_SESSION['error'] = "Livro indisponível";
            $pdo->rollBack();
            header("Location: " . $_SESSION['HTTP_REFERER']);
            exit;
        }

        // Passo 2: Marcar como indisponivel
        $updateStmt = $pdo->prepare("UPDATE livros SET disponivel = FALSE WHERE cod_isbn = ?");
        $updateStmt->execute([$isbn]);

        // Passo 3: Adicionar ao carrinho
        $insertStmt = $pdo->prepare("INSERT INTO carrinho (id_utilizador, cod_isbn, quantidade) VALUES (?, ?, 1)");
        $insertStmt->execute([$userId, $isbn]);

        $pdo->commit();
        $_SESSION['success'] = "Livro adicionado ao carrinho!";
    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Erro: " . $e->getMessage();
    }
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}
?>