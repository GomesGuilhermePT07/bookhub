<?php

session_start();

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/index_style.css">
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/modal.css">
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/apresentar_livro.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">
    <title>BOOKhub</title>
</head>
<body>
    <header>
        <div class="box-img-header">
            <a href="index_user.php">
                <img class="img-logo" src="assets/img/Logotipo_Bookhub.png" alt="Logo BOOKhub">
            </a>
        </div>
        <nav>
            <a href="#" class="nav-links">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                </svg>
            </a>
            <a href="#" class="nav-links">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-suit-heart" viewBox="0 0 16 16">
                    <path d="m8 6.236-.894-1.789c-.222-.443-.607-1.08-1.152-1.595C5.418 2.345 4.776 2 4 2 2.324 2 1 3.326 1 4.92c0 1.211.554 2.066 1.868 3.37.337.334.721.695 1.146 1.093C5.122 10.423 6.5 11.717 8 13.447c1.5-1.73 2.878-3.024 3.986-4.064.425-.398.81-.76 1.146-1.093C14.446 6.986 15 6.131 15 4.92 15 3.326 13.676 2 12 2c-.777 0-1.418.345-1.954.852-.545.515-.93 1.152-1.152 1.595zm.392 8.292a.513.513 0 0 1-.784 0c-1.601-1.902-3.05-3.262-4.243-4.381C1.3 8.208 0 6.989 0 4.92 0 2.755 1.79 1 4 1c1.6 0 2.719 1.05 3.404 2.008.26.365.458.716.596.992a7.6 7.6 0 0 1 .596-.992C9.281 2.049 10.4 1 12 1c2.21 0 4 1.755 4 3.92 0 2.069-1.3 3.288-3.365 5.227-1.193 1.12-2.642 2.48-4.243 4.38z"/>
                </svg>
            </a>
            <a href="#" class="nav-links">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-mortarboard" viewBox="0 0 16 16">
                    <path d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917zM8 8.46 1.758 5.965 8 3.052l6.242 2.913z"/>
                    <path d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46z"/>
                </svg>
            </a>
            <a href="./cart.php" class="nav-links">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                </svg>(<span id="cart-count"><?php echo isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?></span>)
            </a>
        </nav>
        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>
            <a href="./logins/registo_com_validacao.php" class="btn-action-ref">Registar</a>
            <a href="./logins/login.php" class="btn-action-ref1">Entrar</a>
        <?php else: ?>
            <a href="./detalhes_conta.html" class="btn-action-ref">Ver Conta</a>
            <a href="./logins/logout.php" class="btn-action-logout">Sair</a>
        <?php endif; ?>
    </header>

    <main>
        <!-- <h2>ISTO É O MAIN</h2> -->
        <section class="first-section">
            <button id="openModal">Adicionar Livro</button>
            <form action="./assets/php/captar_livro.php" method="POST" id="bookForm">
            <dialog class="modal">
                <h2>Adicionar Livro</h2>
                <div class="modal-content">
                    <div class="modal-left"> <!--Coluna da esquerda-->
                        <div class="book-image-container">
                            <img id="bookImage" src="https://via.placeholder.com/128x186" alt="Imagem do Livro">
                        </div>
                        <div class="book-summary">
                            <label for="summary" class="summary-label">Resumo</label>
                            <textarea id="summary" name="summary" rows="4" placeholder="Resumo..." required></textarea>
                            <button id="viewFullText" class="view-icon-btn" title="Ver resumo completo">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <dialog id="textModal" class="text-modal">
                            <h2>Resumo completo do livro</h2>
                            <p id="fullTextContent"></p>
                            <button id="closeTextModal" class="modal-close-btn">Fechar</button>
                        </dialog>
                    </div>
                    <div class="modal-right">
                        <div class="inputUser">
                            <input type="text" id="isbn" name="isbn" class="modal-input" required>
                            <label for="isbn" class="labelInput">Código ISBN</label>
                        </div>
                    
                        <div class="inputUser">
                            <input type="text" id="title" name="title" class="modal-input" required>
                            <label for="title" class="labelInput">Título do Livro</label>
                        </div>
                    
                        <div class="inputUser">
                            <input type="text" id="edition" name="edition" class="modal-input" required>
                            <label for="edition" class="labelInput">Edição</label>
                        </div>
                    
                        <div class="inputUser">
                            <input type="text" id="author" name="author" class="modal-input" required>
                            <label for="author" class="labelInput">Autor</label>
                        </div>
                    
                        <div class="inputUser">
                            <input type="text" id="numero_paginas" name="numero_paginas" class="modal-input" required>
                            <label for="numero_paginas" class="labelInput">Nº de páginas</label>
                        </div>

                        <div class="inputUser">
                            <input type="number" id="quantity" name="quantity" class="modal-input" min="1" value="1" required>
                            <label for="quantity" class="labelInput">Quantidade</label>
                        </div>
    
                        <div class="modal-buttons-container">
                            <button type="submit" name="submit" id="saveBook" class="modal-buttons">Guardar livro</button>
                            <button type="button" name="submit" id="closeModal" class="modal-buttons1">Fechar</button>
                        </div>
                    </div>
                </div>                
            </dialog>
            </form>
            <script src="../ModuloProjeto/assets/js/modal_livros.js"></script>
            <script src="../ModuloProjeto/assets/js/carregar_livros.js"></script>
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
        <!-- <p>&copy; 2025 BOOKhub. Todos os direitos reservados.</p> -->
    </footer>
</body>
</html>