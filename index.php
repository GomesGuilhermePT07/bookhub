<?php

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
            <a href="index.php">
                <img class="img-logo" src="assets/img/Logotipo_Bookhub.png" alt="Logo BOOKhub">
            </a>
        </div>
        <nav>
            <a href="#" class="nav-links">Livros</a>
            <a href="#" class="nav-links">Lista de desejos</a>
            <a href="#" class="nav-links">Workshops</a>
            <a href="#" class="nav-links">Carrinho</a>
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
            <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Função para carregar os livros
        function loadBooks() {
            fetch('listar_livros.php')
                .then(response => response.json())
                .then(data => {
                    const bookListContainer = document.getElementById("book-list");
                    if (bookListContainer) {
                        bookListContainer.innerHTML = ""; // Limpa o conteúdo atual

                        if (data.error) {
                            alert(data.error);
                            return;
                        }

                        data.forEach(book => {
                            const bookHtml = `
                                <div class="book-item">
                                    <h5>${book.titulo}</h5>
                                    <p>Autor: ${book.autor}</p>
                                    <p>Edição: ${book.edicao}</p>
                                    <p>Páginas: ${book.numero_paginas}</p>
                                    <p>Quantidade: ${book.quantidade}</p>
                                    <p>Resumo: ${book.resumo}</p>
                                    <button class="remove-book" data-isbn="${book.cod_isbn}">Remover</button>
                                </div>`;
                            bookListContainer.innerHTML += bookHtml;
                        });
                    }
                })
                .catch(error => console.error('Erro ao carregar livros:', error));
        }

        // Carregar os livros quando a página é carregada
        loadBooks();

        // Adicionar evento de clique para remover livros
        document.addEventListener("click", function (event) {
            if (event.target.classList.contains("remove-book")) {
                const isbn = event.target.getAttribute("data-isbn");
                if (confirm("Tem certeza que deseja remover este livro?")) {
                    fetch(`remover_livro.php?isbn=${isbn}`, {
                        method: "DELETE"
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            loadBooks(); // Recarregar a lista de livros após remover
                        } else {
                            alert(data.error);
                        }
                    })
                    .catch(error => console.error('Erro ao remover livro:', error));
                }
            }
        });
    });
</script>
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