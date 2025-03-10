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
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/index_slider.css">
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

    <div class="slider">
        <div class="slides">
            <div class="slide">
                <img src="./assets/img/imagem_teste_slider1.jpg" alt="Slide 1">
                <div class="caption">Legenda do Slide 1</div>
            </div>
            <div class="slide">
                <img src="./assets/img/imagem_teste_slider2.jpg" alt="Slide 2">
                <div class="caption">Legenda do Slide 2</div>
            </div>
            <div class="slide">
                <img src="./assets/img/imagem_teste_slider3.jpg" alt="Slide 3">
                <div class="caption">Legenda do Slide 3</div>
            </div>
        </div>
        <button class="prev">&#10094;</button>
        <button class="next">&#10095;</button>
    </div> <!-- slider -->

    <main>
        <!-- <h2>ISTO É O MAIN</h2> -->
        <section class="first-section">
            <form action="./assets/php/captar_livro.php" method="POST" id="bookForm">
            </form>
            <script src="../ModuloProjeto/assets/js/modal_livros.js"></script>
            <script src="../ModuloProjeto/assets/js/carregar_livros_user.js"></script>
            <script src="../ModuloProjeto/assets/js/remover_livros.js"></script>
            <script src="../ModuloProjeto/assets/js/slider.js"></script>
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