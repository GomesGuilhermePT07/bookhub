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
const bookPagesInput = document.getElementById("genre");
const bookImage = document.getElementById("bookImage");
const bookListContainer = document.createElement("div"); // Contêiner para exibir os livros
bookListContainer.id = "book-list";
document.body.appendChild(bookListContainer);

// Criar o botão "Salvar alterações" dentro do modal de texto completo
const saveTextModalBtn = document.createElement("button");
saveTextModalBtn.textContent = "Salvar alterações";
saveTextModalBtn.classList.add("modal-save-btn");
saveTextModalBtn.style.marginTop = "10px"; // Ajuste de estilo
textModal.appendChild(saveTextModalBtn); // Anexa o botão ao modal de texto completo

// Abrir o modal principal
button.onclick = function () {
    isbnInput.value = "";
    bookTitleInput.value = "";
    bookAuthorInput.value = "";
    bookEditionInput.value = "";
    bookPagesInput.value = "";
    textarea.value = "";
    bookImage.src = "https://via.placeholder.com/128x186"; // Resetar imagem para o padrão
    modal.showModal();
};

// Fechar o modal principal
closeModal.onclick = function () {
    modal.close();
};

// Função para buscar os detalhes do livro da Google Books API
async function fetchBookDetails(isbn) {
    try {
        const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
        const data = await response.json();

        if (data.totalItems > 0) {
            const book = data.items[0].volumeInfo;

            // Preencher os campos do modal com os dados do livro
            bookTitleInput.value = book.title || "Não disponível";
            bookAuthorInput.value = book.authors ? book.authors.join(", ") : "Não disponível";
            bookEditionInput.value = book.publishedDate || "Não disponível";
            bookPagesInput.value = book.pageCount || "Não disponível";
            textarea.value = book.description || "Resumo não disponível";

            // Alterar a imagem da capa do livro
            if (book.imageLinks && book.imageLinks.thumbnail) {
                bookImage.src = book.imageLinks.thumbnail;
            } else {
                bookImage.src = "https://via.placeholder.com/128x186"; // Imagem padrão
            }
        } else {
            alert("Nenhum livro encontrado com este ISBN.");
        }
    } catch (error) {
        console.error("Erro ao buscar os detalhes do livro:", error);
        alert("Erro ao buscar os detalhes do livro. Tente novamente.");
    }
}

// Detectar mudanças no campo ISBN e buscar os dados automaticamente
isbnInput.addEventListener("input", () => {
    const isbn = isbnInput.value.trim();

    if (isbn.length >= 10) { // ISBN deve ter ao menos 10 caracteres
        fetchBookDetails(isbn);
    } else {
        // Limpar os dados se o ISBN for apagado ou for inferior a 10 caracteres
        bookTitleInput.value = "";
        bookAuthorInput.value = "";
        bookEditionInput.value = "";
        bookPagesInput.value = "";
        textarea.value = "";
        bookImage.src ="https://via.placeholder.com/128x186"; // Resetar a imagem
        bookImage.alt = "Imagem do livro"; // Texto alternativo
    }
});

// Abrir o modal para exibir e editar o texto
viewFullTextBtn.addEventListener("click", () => {
    const text = textarea.value.trim();

    if (text) {
        fullTextContent.textContent = ""; // Limpa o conteúdo anterior
        const editableTextarea = document.createElement("textarea"); // Cria um textarea editável
        editableTextarea.value = text; // Define o texto existente no textarea
        editableTextarea.id = "editableTextarea";
        editableTextarea.style.width = "100%";
        editableTextarea.style.height = "200px";
        editableTextarea.style.resize = "none";
        fullTextContent.appendChild(editableTextarea); // Adiciona o textarea ao modal
        textModal.showModal(); // Abre o modal
    } else {
        alert("O campo de resumo está vazio!");
    }
});

// Salvar as alterações do modal de texto completo no textarea principal
saveTextModalBtn.addEventListener("click", () => {
    const editableTextarea = document.getElementById("editableTextarea");
    if (editableTextarea) {
        textarea.value = editableTextarea.value; // Atualiza o resumo original
        alert("Resumo atualizado com sucesso!");
        textModal.close(); // Fecha o modal
    }
});

// Fechar o modal de texto completo
closeTextModalBtn.addEventListener("click", () => {
    textModal.close(); // Fecha o modal
});

// Simulação do salvamento do livro
saveBook.onclick = function () {
    const title = bookTitleInput.value;
    const author = bookAuthorInput.value;
    const edition = bookEditionInput.value;
    const pages = bookPagesInput.value;
    const summary = textarea.value;
    const thumbnail = bookImage.src;

    if (title && author && edition && pages && summary) {
        // Criar a estrutura HTML para o livro
        const bookHtml = `
            <div class="book-item">
                <img src="${thumbnail}" alt="Capa do Livro" class="book-thumbnail">
                <h5>${title}</h5>
                <p>${author}</p>
                <!--<button class="remove-book">Remover</button>-->
            </div>
        `;
        bookListContainer.innerHTML += bookHtml;

        alert("Livro adicionado com sucesso!");
        modal.close(); // Fecha o modal principal

        // Limpar os campos do modal
        isbnInput.value = "";
        bookTitleInput.value = "";
        bookAuthorInput.value = "";
        bookEditionInput.value = "";
        bookPagesInput.value = "";
        textarea.value = "";
        bookImage.src = "https://via.placeholder.com/128x186";
    } else {
        alert("Por favor, preencha todos os campos.");
    }
};

// Evento para remover um livro
bookListContainer.addEventListener("click", (event) => {
    if (event.target.classList.contains("remove-book")) {
        event.target.closest(".book-item").remove();
    }
});