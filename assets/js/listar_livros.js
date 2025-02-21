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