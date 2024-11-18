const button = document.getElementById("openModal");
const modal = document.querySelector("dialog");
const closeModal = document.getElementById("closeModal");
const saveBook = document.getElementById("saveBook");

button.onclick = function(){
    modal.showModal();
};

closeModal.onclick = function(){
    modal.close();
};

saveBook.onclick = function(){
    alert("Livro salvo com sucesso!");
    modal.close();
}