document.addEventListener("DOMContentLoaded", function () {
    const button = document.getElementById("openModal");
    const modal = document.querySelector("dialog");
    const closeModal = document.getElementById("closeModal");
    const saveBook = document.getElementById("saveBook");
    const viewFullTextBtn = document.getElementById("viewFullText");
    const textModal = document.getElementById("textModal");
    const closeTextModalBtn = document.getElementById("closeTextModal");
    const fullTextContent = document.getElementById("fullTextContent");
    const textarea = document.getElementById("summary");
    const isbnInput = document.getElementById("isbn");
    const bookTitleInput = document.getElementById("title");
    const bookAuthorInput = document.getElementById("author");
    const bookEditionInput = document.getElementById("edition");
    const bookPagesInput = document.getElementById("numero_paginas");
    const bookImage = document.getElementById("bookImage");
    const quantity = document.getElementById("quantity");
    const bookListContainer = document.createElement("div");
    const bookForm = document.getElementById("bookForm");

    bookListContainer.id = "book-list";
    document.body.appendChild(bookListContainer);

    const saveTextModalBtn = document.getElementById("saveTextModal"); 
    if (saveTextModalBtn) {
        saveTextModalBtn.addEventListener("click", () => {
            const editableTextarea = document.getElementById("editableTextarea");
            if (editableTextarea && textarea) {
                textarea.value = editableTextarea.value;
                textModal.close();
            }
        });
    }

    if (closeTextModalBtn) {
        closeTextModalBtn.addEventListener("click", (e) => {
            e.preventDefault();
            textModal.close();
        });
    }

    if (bookForm) {
        bookForm.addEventListener("submit", async function (e) {
            e.preventDefault();
            saveBookHandler(); 
        });
    }

    if (button && modal) {
        button.onclick = function () {
            resetForm();
            modal.showModal();
        };
    }

    if (closeModal && modal) {
        closeModal.onclick = function () {
            modal.close();
            if (quantity) quantity.value = 1;
        };
    }

    // Função para resetar o formulário
    function resetForm() {
        if (isbnInput) isbnInput.value = "";
        if (bookTitleInput) bookTitleInput.value = "";
        if (bookAuthorInput) bookAuthorInput.value = "";
        if (bookEditionInput) bookEditionInput.value = "";
        if (bookPagesInput) bookPagesInput.value = "";
        if (textarea) textarea.value = "";
        if (bookImage) bookImage.src= "https://via.placeholder.com/128x186";
        if (quantity) quantity.value = 1;
    }

    // Buscar detalhes do livro
    async function fetchBookDetails(isbn) {
        try {
            const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
            const data = await response.json();

            if (data.totalItems > 0) {
                const book = data.items[0].volumeInfo;

                if (bookTitleInput) bookTitleInput.value = book.title || "Não disponível";
                if (bookAuthorInput) bookAuthorInput.value = book.authors ? book.authors.join(", ") : "Não disponível";
                if (bookEditionInput) bookEditionInput.value = book.publishedDate || "Não disponível";
                if (bookPagesInput) bookPagesInput.value = book.pageCount || "Não disponível";
                if (textarea) textarea.value = book.description || "Resumo não disponível";

                if (book.imageLinks && book.imageLinks.thumbnail && bookImage) {
                    bookImage.src = book.imageLinks.thumbnail;
                } else if (bookImage) {
                    bookImage.src = "https://via.placeholder.com/128x186";
                }
            } else {
                // alert("Nenhum livro encontrado com este ISBN.");
            }
        } catch (error) {
            console.error("Erro ao buscar os detalhes do livro:", error);
            // alert("Erro ao buscar os detalhes do livro. Tente novamente.");
        }
    }

    // Buscar dados ao digitar o ISBN
    if (isbnInput) {
        isbnInput.addEventListener("input", () => {
            const isbn = isbnInput.value.trim();
            if (isbn.length === 10 || isbn.length === 13) {
                fetchBookDetails(isbn);
            } else {
                if (bookTitleInput) bookTitleInput.value = "";
                if (bookAuthorInput) bookAuthorInput.value = "";
                if (bookEditionInput) bookEditionInput.value = "";
                if (bookPagesInput) bookPagesInput.value = "";
                if (textarea) textarea.value = "";
                if (bookImage) bookImage.src = "https://via.placeholder.com/128x186";
                if (quantity) quantity.value = 1;
            }
        });
    }

    // Abrir modal de resumo
    if (viewFullTextBtn && textModal && fullTextContent) {
        viewFullTextBtn.addEventListener("click", () => {
            if (textarea) {
                const text = textarea.value.trim();
                if (text) {
                    fullTextContent.innerHTML = "";
                    const editableTextarea = document.createElement("textarea");
                    editableTextarea.value = text;
                    editableTextarea.id = "editableTextarea";
                    editableTextarea.style.width = "100%";
                    editableTextarea.style.height = "200px";
                    editableTextarea.style.resize = "none";
                    fullTextContent.appendChild(editableTextarea);
                    textModal.showModal();
                } else {
                    // alert("O campo de resumo está vazio!");
                }
            }
        });
    }

    // Função para salvar o livro
    async function saveBookHandler() {
        if (!bookTitleInput || !bookAuthorInput || !bookEditionInput || !bookPagesInput || !textarea || !bookImage || !quantity) return;

        const title = bookTitleInput.value;
        const author = bookAuthorInput.value;
        const edition = bookEditionInput.value;
        const pages = bookPagesInput.value;
        const summary = textarea.value;
        const thumbnail = bookImage.src;
        const quantityValue = quantity.value;

        if (title && author && edition && pages && summary) {
            const isbn = document.querySelector("#isbn").value;
            const formData = new FormData();
            formData.append("isbn", isbn);
            formData.append("title", title);
            formData.append("edition", edition);
            formData.append("author", author);
            formData.append("numero_paginas", pages);
            formData.append("quantity", quantityValue);
            formData.append("summary", summary);

            try {
                const response = await fetch("http://localhost:8080//ModuloProjeto/assets/php/captar_livro.php", {
                    method: "POST",
                    body: formData,
                });

                if (response.ok && response.status == 200) {
                    console.log("Criado com sucesso!");
                } else {
                    console.log("Algum erro ocorreu!");
                }
            } catch (e) {
                console.error(e);
            }

            modal.close();
            resetForm();
        }
    }

    // Remover livro
    bookListContainer.addEventListener("click", (event) => {
        if (event.target.classList.contains("remove-book")) {
            event.target.closest(".book-item").remove();
        }
    });
});