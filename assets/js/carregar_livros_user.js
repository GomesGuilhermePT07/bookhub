document.addEventListener("DOMContentLoaded", function () {
    // Função para buscar a capa do livro usando a API do Google Books
    async function fetchBookCover(isbn) {
        try {
            const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
            const data = await response.json();

            if (data.items && data.items[0].volumeInfo.imageLinks) {
                return data.items[0].volumeInfo.imageLinks.thumbnail; // Retorna a URL da capa
            }
        } catch (error) {
            console.error("Erro ao buscar capa do livro:", error);
        }
        return "https://via.placeholder.com/128x186"; // Retorna uma imagem padrão se a capa não for encontrada
    }

    // Função para carregar os livros
    async function loadBooks() {
        try {
            const response = await fetch('assets/php/listar_livros.php');
            if (!response.ok) {
                throw new Error("Erro na requisição: " + response.statusText);
            }
            const data = await response.json();

            const bookListContainer = document.getElementById("book-list");
            if (bookListContainer) {
                bookListContainer.innerHTML = ""; // Limpa o conteúdo atual

                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Itera sobre os livros e os adiciona ao container
                for (const book of data) {
                    const coverUrl = await fetchBookCover(book.cod_isbn); // Busca a capa do livro

                    const bookHtml = `
                        <div class="book-item" data-isbn="${book.cod_isbn}">
                            <img src="${coverUrl}" alt="Capa do livro ${book.titulo}" class="book-cover">
                            <h5>${book.titulo}</h5>
                            <p><b>Autor: </b>${book.autor}</p>
                            <form action="../ModuloProjeto/assets/php/add_to_cart.php" method="POST">
                                <input type="hidden" name="isbn" value="${book.cod_isbn}">
                                <input type="hidden" name="title" value="${book.titulo}">
                                <button class="add-to-cart">Adicionar ao carrinho</button>
                            </form>
                        </div>`;
                    bookListContainer.innerHTML += bookHtml;
                }
            }
        } catch (error) {
            console.error('Erro ao carregar livros:', error);
            alert("Erro ao carregar livros. Verifique o console para mais detalhes.");
        }
    }

    // Carregar os livros quando a página é carregada
    loadBooks();
});