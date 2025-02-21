async function removeBook(isbn) {
    try{
        const response = await fetch("https://localhost:8080/ModuloProjeto/assets/php/remover_livro.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `isbn=${isbn}`,
        });

        if(response.ok){
            alert("Livro removido com sucesso!");
            // Atualizar a lista de livros ou recarregar a página
            window.location.reload();
        } else {
            alert("Erro ao remover o livro.");
        }
    } catch (error) {
        console.error("Erro ao remover o livro: ", error);
        alert("Erro ao remover o livro. Tente novamente.");
    }
}

// Adicionar evento de clique para remover livro
bookListContainer.addEventListener("click", (event) => {
    if(event.target.classList.contains("remove-book")) {
        const isbn = event.target.getAttribute("data-isbn");
        if(isbn) {
            removeBook(isbn);
        }
    }
});