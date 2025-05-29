<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id'])) {
    header("Location: " . SITE_URL . "../../logins/login.php");
    exit;
}

$userId = $_SESSION['id'];

try {
    $pdo->beginTransaction();

    // Buscar itens do carrinho
    $stmt = $pdo->prepare("
        SELECT c.cod_isbn, c.quantidade, l.titulo 
        FROM carrinho c 
        JOIN livros l ON c.cod_isbn = l.cod_isbn 
        WHERE c.id_utilizador = ?
    ");
    $stmt->execute([$userId]);
    $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($cartItems)) {
        throw new Exception("Carrinho vazio");
    }

    // Criar requisições
    foreach ($cartItems as $item) {
        $stmt = $pdo->prepare("
            INSERT INTO requisicoes (id_utilizador, cod_isbn, quantidade, data_requisicao, status)
            VALUES (?, ?, ?, NOW(), 'pendente')
        ");
        $stmt->execute([
            $userId,
            $item['cod_isbn'],
            $item['quantidade']
        ]);
    }

    // Limpar carrinho
    $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id_utilizador = ?");
    $stmt->execute([$userId]);

    $pdo->commit();
    
    header("Location: " . SITE_URL . "../../cart.php?success=1");
} catch (Exception $e) {
    $pdo->rollBack();
    die("Erro: " . $e->getMessage());
}