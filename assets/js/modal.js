const button = document.getElementById("openModal");
const modal = document.querySelector("dialog");
const closeModal = document.getElementById("closeModal");
const saveBook = document.getElementById("saveBook");
const viewFullTextBtn = document.getElementById("viewFullText");
const textModal = document.getElementById("textModal");
const closeTextModalBtn = document.getElementById("closeTextModal");
const fullTextContent = document.getElementById("fullTextContent");
const textarea = document.getElementById("summary");

// Criar o botão "Salvar alterações" dentro do modal de texto completo
const saveTextModalBtn = document.createElement("button");
saveTextModalBtn.textContent = "Salvar alterações";
saveTextModalBtn.classList.add("modal-save-btn");
saveTextModalBtn.style.marginTop = "10px"; // Ajuste de estilo
textModal.appendChild(saveTextModalBtn); // Anexa o botão ao modal de texto completo

// Abrir o modal principal
button.onclick = function () {
    document.getElementById("isbn").value = "";
    document.getElementById("title").value = "";
    document.getElementById("edition").value = "";
    document.getElementById("author").value = "";
    document.getElementById("genre").value = "";
    textarea.value = "";
    modal.showModal();
};

// Fechar o modal principal
closeModal.onclick = function () {
    modal.close();
};

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
    alert("Livro salvo com sucesso!");
    modal.close();
};