<?php
    session_start();
    // require_once 'assets/php/check_login.php';
    require_once 'assets/php/config.php';

    $userId = $_SESSION['id'];
    $cartItems = [];
    $cartCount = 0;

    // Buscar itens do carrinho
    $stmt = $conn->prepare("
        SELECT l.titulo, c.cod_isbn, c.quantidade 
        FROM carrinho c 
        JOIN livros l ON c.cod_isbn = l.cod_isbn 
        WHERE c.id_utilizador = ?
    ");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $cartItems = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    // Calcular total de itens (versão compatível com PHP < 7.0)
    $countStmt = $conn->prepare("SELECT SUM(quantidade) AS total FROM carrinho WHERE id_utilizador = ?");
    $countStmt->bind_param("i", $userId);
    $countStmt->execute();
    $countResult = $countStmt->get_result();

    // Correção aplicada aqui
    $countData = $countResult->fetch_assoc();
    $cartCount = isset($countData['total']) ? $countData['total'] : 0;

    $countStmt->close();
    $conn->close();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/cart_style.css">
    <link rel="stylesheet" href="./assets/css/index_style.css">
    <title>BOOKhub | Carrinho</title>
</head>
<body>
    <header>
        <div class="box-img-header">
            <a href="index.php">
                <img class="img-logo" src="assets/img/Logotipo_Bookhub.png" alt="Logo BOOKhub">
            </a>
        </div>
        <nav>
            <a href="./index_user.php" class="nav-links">Livros</a>
            <a href="#" class="nav-links">Lista de desejos</a>
            <a href="#" class="nav-links">Workshops</a>
            <a href="./cart.php" class="nav-links">Carrinho (<span id="cart-count"><? $cartCount ?></span>)</a>
        </nav>
        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>
            <a href="logins/registo_com_validacao.php" class="btn-action-ref">Registar</a>
            <a href="logins/login.php" class="btn-action-ref1">Entrar</a>
        <?php else: ?>
            <a href="detalhes_conta.html" class="btn-action-ref">Ver Conta</a>
            <a href="logins/logout.php" class="btn-action-logout">Sair</a>
        <?php endif; ?>
    </header>

    <main class ="cart-container">
    <h1>O teu carrinho</h1>

        <?php if (!empty($cartItems)): ?>
        <div class="cart-items">
            <table>
                <thead>
                    <tr>
                        <th>Livro</th>
                        <th>Quantidade</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['titulo']) ?></td>
                            <td>
                                <form action="./assets/php/update_cart.php" method="POST">
                                    <input type="number" name="quantity" value="<?= $item['quantidade'] ?>" min="1">
                                    <input type="hidden" name="isbn" value="<?= $item['cod_isbn'] ?>">
                                    <button type="submit">Atualizar</button>
                                </form>
                            </td>
                            <td>
                                <a href="./assets/php/remove_from_cart.php?isbn=<?= $item['cod_isbn'] ?>" class="btn-remove">Remover</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p class="empty-cart">Seu carrinho está vazio.</p>
        <?php endif; ?>
    </main>
</body>
</html>