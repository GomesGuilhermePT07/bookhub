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

        // Buscar itens do carrinho do usuário
        $stmt = $pdo->prepare("
            SELECT cod_isbn, quantidade 
            FROM carrinho 
            WHERE id_utilizador = ?
        ");
        $stmt->execute([$userId]);
        $cartItems = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($cartItems)) {
            throw new Exception("Carrinho vazio");
        }

        // Verificar disponibilidade geral
        foreach ($cartItems as $item) {
            $checkStmt = $pdo->prepare("SELECT disponivel FROM livros WHERE cod_isbn = ?");
            $checkStmt->execute([$item['cod_isbn']]);
            $livro = $checkStmt->fetch();
            
            if (!$livro || $livro['disponivel'] < $item['quantidade']) {
                throw new Exception("Livro indisponível: " . $item['cod_isbn']);
            }
        }

        // Processar cada item
        foreach ($cartItems as $item) {
            // Criar requisição
            $stmtReq = $pdo->prepare("
                INSERT INTO requisicoes (id_utilizador, cod_isbn, quantidade, data_requisicao, status)
                VALUES (?, ?, ?, NOW(), 'pendente')
            ");
            $stmtReq->execute([
                $userId,
                $item['cod_isbn'],
                $item['quantidade']
            ]);

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
        header("Location: ../../cart.php?success=1");
    } catch (Exception $e) {
        $pdo->rollBack();
        die("Erro: " . $e->getMessage());
}