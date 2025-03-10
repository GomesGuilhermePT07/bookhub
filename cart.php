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
            <a href="#" class="nav-links">Livros</a>
            <a href="#" class="nav-links">Lista de desejos</a>
            <a href="#" class="nav-links">Workshops</a>
            <a href="./cart.php" class="nav-links">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </svg>(<span id="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>)
            </a>
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