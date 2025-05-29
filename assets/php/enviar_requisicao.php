<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id'])) {
    header("Location: /ModuloProjeto/logins/login.php");
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
        $_SESSION['cart_error'] = "Seu carrinho está vazio.";
        header("Location: ../../cart.php");
        exit;
    }

    // Verificar disponibilidade
    foreach ($cartItems as $item) {
        $checkStmt = $pdo->prepare("SELECT disponivel FROM livros WHERE cod_isbn = ?");
        $checkStmt->execute([$item['cod_isbn']]);
        $livro = $checkStmt->fetch();
        
        if (!$livro || $livro['disponivel'] < $item['quantidade']) {
            $_SESSION['cart_error'] = "O livro '{$item['titulo']}' não tem unidades suficientes disponíveis.";
            header("Location: ../cart.php");
            exit;
        }
    }

    // Criar requisições (1 requisição por unidade)
    foreach ($cartItems as $item) {
        for ($i = 0; $i < $item['quantidade']; $i++) {
            $stmtReq = $pdo->prepare("
                INSERT INTO requisicoes (id_utilizador, cod_isbn, data_requisicao, status)
                VALUES (?, ?, NOW(), 'pendente')
            ");
            $stmtReq->execute([$userId, $item['cod_isbn']]);
        }

        // Atualizar estoque
        $updateStmt = $pdo->prepare("
            UPDATE livros 
            SET disponivel = disponivel - ? 
            WHERE cod_isbn = ?
        ");
        $updateStmt->execute([
            $item['quantidade'],
            $item['cod_isbn']
        ]);
    }

    // Limpar carrinho
    $stmt = $pdo->prepare("DELETE FROM carrinho WHERE id_utilizador = ?");
    $stmt->execute([$userId]);

    $pdo->commit();
    $_SESSION['cart_success'] = "Requisição realizada com sucesso!";
    header("Location: ../../cart.php");
    exit;
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['cart_error'] = "Erro ao processar requisição: " . $e->getMessage();
    header("Location: ../cart.php");
    exit;
}