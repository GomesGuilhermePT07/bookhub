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
    <link rel="stylesheet" href="../Módulo Projeto/assets/css/index_style.css">
    <link rel="stylesheet" href="../Módulo Projeto/assets/css/modal.css">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather&display=swap" rel="stylesheet">
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
            <a href="#" class="nav-links">Carrinho</a>
        </nav>
        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>
            <a href="logins/registo_com_validacao.php" class="btn-action-ref">Registar</a>
            <a href="logins/login.php" class="btn-action-ref1">Entrar</a>
        <?php else: ?>
            <a href="detalhes_conta.html" class="btn-action-ref">Ver Conta</a>
            <a href="logins/logout.php" class="btn-action-ref1">Terminar Sessão</a>
        <?php endif; ?>
    </header>

    <main>
        <h2>ISTO É O MAIN</h2>
        <section class="first-section">
            <button id="openModal">Adicionar Livro</button>
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
                        <form id="bookForm">
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
                                <input type="text" id="genre" name="genre" class="modal-input" required>
                                <label for="genre" class="labelInput">Nº de páginas</label>
                            </div>
        
                            <div class="modal-buttons-container">
                                <button type="button" id="saveBook" class="modal-buttons">Guardar livro</button>
                                <button type="button" id="closeModal" class="modal-buttons1">Fechar</button>
                            </div>
                        </form>
                    </div>
                </div>                
            </dialog>
            <script src="../Módulo Projeto/assets/js/modal.js"></script>
            <p>esta é a parte dos livros</p>
        </section>

        <section class="second-section">
            <p>esta é a parte </p>
        </section>

        <section class="third-section">
            <p>esta é a parte </p>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 BOOKhub. Todos os direitos reservados.</p>
    </footer>
</body>
</html>