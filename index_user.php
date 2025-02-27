<?php

session_start();

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/index_style.css">
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/apresentar_livro.css">
    <title>BOOKhub</title>
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
            <a href="./web-pages/cart.php" class="nav-links">Carrinho (<span id="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>)</a>
        </nav>
        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>
            <a href="logins/registo_com_validacao.php" class="btn-action-ref">Registar</a>
            <a href="logins/login.php" class="btn-action-ref1">Entrar</a>
        <?php else: ?>
            <a href="detalhes_conta.html" class="btn-action-ref">Ver Conta</a>
            <a href="logins/logout.php" class="btn-action-logout">Sair</a>
        <?php endif; ?>
    </header>

    <main>
        <!-- <h2>ISTO É O MAIN</h2> -->
        <section class="first-section">
            <form action="./assets/php/captar_livro.php" method="POST" id="bookForm">
            </form>
            <script src="../ModuloProjeto/assets/js/modal_livros.js"></script>
            <script src="../ModuloProjeto/assets/js/carregar_livros_user.js"></script>
            <script src="../ModuloProjeto/assets/js/remover_livros.js"></script>
            <!-- <p>esta é a parte dos livros</p> -->
        </section>

        <section class="second-section">
            <!-- <p>esta é a parte </p> -->
        </section>

        <section class="third-section">
            <!-- <p>esta é a parte </p> -->
        </section>
    </main>

    <footer>
        <!-- <p>&copy; 2025 BOOKhub. Todos os direitos reservados.</p>  -->
    </footer>

    <!-- <p>esta linha é um teste</p> -->
</body>
</html>