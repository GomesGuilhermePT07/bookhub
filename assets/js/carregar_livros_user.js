document.addEventListener("DOMContentLoaded", function () {
    // Função para buscar a capa do livro
    async function fetchBookCover(isbn) {
        try {
            const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
            const data = await response.json();
            return data.items?.[0]?.volumeInfo?.imageLinks?.thumbnail || "https://via.placeholder.com/128x186";
        } catch (error) {
            return "https://via.placeholder.com/128x186";
        }
    }

    // Função para atualizar o badge do carrinho
    function updateCartBadge(count) {
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.textContent = count;
            cartBadge.style.display = count > 0 ? 'block' : 'none';
        }
    }

    // Função para adicionar ao carrinho via AJAX
    async function addToCart(isbn) {
        try {
            const formData = new FormData();
            formData.append('isbn', isbn);
            
            const response = await fetch('assets/php/add_to_cart.php', {
                method: 'POST',
                body: formData
            });
            
            const data = await response.json();
            
            if (data.status === 'success') {
                updateCartBadge(data.cartCount);
                alert('Livro adicionado ao carrinho!');
            } else {
                alert(data.message || 'Erro ao adicionar ao carrinho');
            }
        } catch (error) {
            alert('Erro na conexão');
        }
    }

    // Função para carregar os livros
    async function loadBooks() {
        try {
            const response = await fetch('assets/php/listar_livros.php');
            const data = await response.json();
            const bookListContainer = document.getElementById("book-list");
            
            if (!bookListContainer) return;
            bookListContainer.innerHTML = "";
            
            for (const book of data) {
                const coverUrl = await fetchBookCover(book.cod_isbn);
                const bookHtml = `
                    <div class="book-item ${book.disponivel ? '' : 'unavailable'}" data-isbn="${book.cod_isbn}">
                        <a href="livro_detalhes.php?isbn=${book.cod_isbn}" class="book-link">
                            <img src="${coverUrl}" alt="Capa do livro ${book.titulo}" class="book-cover">
                            <h5>${book.titulo}</h5>
                            <p><b>Autor: </b>${book.autor}</p>
                        </a>
                        ${book.disponivel ? 
                            `<button class="add-to-cart" data-isbn="${book.cod_isbn}">Adicionar ao carrinho</button>` : 
                            `<button class="add-to-cart disabled" disabled>Indisponível</button>`
                        }
                    </div>`;
                bookListContainer.innerHTML += bookHtml;
            }

            // Adiciona event listeners aos botões
            document.querySelectorAll('.add-to-cart:not(.disabled)').forEach(button => {
                button.addEventListener('click', function() {
                    addToCart(this.dataset.isbn);
                });
            });
        } catch (error) {
            console.error('Erro ao carregar livros:', error);
        }
    }

    // Carrega os livros e o estado inicial do carrinho
    loadBooks();
});