// Elementos do modal
const entregaModal = document.getElementById('entregaModal');
const closeEntregaModal = document.getElementById('closeEntregaModal');
const btnCancelarEntrega = document.getElementById('btnCancelarEntrega');
const btnConfirmarEntrega = document.getElementById('btnConfirmarEntrega');

// Variáveis para armazenar dados da requisição
let requisicaoId = null;

// Função para abrir o modal de entrega
function abrirModalEntrega(id, isbn) {
    requisicaoId = id;
    
    // Buscar detalhes do livro do banco de dados
    fetch(`assets/php/buscar_livro.php?isbn=${isbn}`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erro na rede');
            }
            return response.json();
        })
        .then(data => {
            document.getElementById('tituloLivro').textContent = data.titulo;
            document.getElementById('autorLivro').textContent = data.autor;
            document.getElementById('isbnLivro').textContent = isbn;
            
            // Buscar capa do livro na Google Books API
            fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`)
                .then(res => res.json())
                .then(googleData => {
                    if (googleData.items && googleData.items[0]?.volumeInfo?.imageLinks?.thumbnail) {
                        document.getElementById('capaLivro').src = 
                            googleData.items[0].volumeInfo.imageLinks.thumbnail;
                    } else {
                        document.getElementById('capaLivro').src = 'https://via.placeholder.com/128x186';
                    }
                })
                .catch(() => {
                    document.getElementById('capaLivro').src = 'https://via.placeholder.com/128x186';
                });
            
            entregaModal.style.display = 'block';
        })
        .catch(error => {
            console.error('Erro ao buscar livro:', error);
            alert('Erro ao carregar detalhes do livro');
        });
}

// Fechar modal
closeEntregaModal.onclick = function() {
    entregaModal.style.display = 'none';
}

btnCancelarEntrega.onclick = function() {
    entregaModal.style.display = 'none';
}

// Confirmar entrega
btnConfirmarEntrega.onclick = function() {
    if (requisicaoId) {
        fetch(`assets/php/entregar_livro.php?id=${requisicaoId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Livro entregue com sucesso!');
                    location.reload();
                } else {
                    alert('Erro ao entregar livro: ' + data.error);
                }
            });
    }
    entregaModal.style.display = 'none';
}

// Fechar modal se clicar fora
window.onclick = function(event) {
    if (event.target == entregaModal) {
        entregaModal.style.display = 'none';
    }
}

// Adicionar event listeners aos botões de entrega
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.entregar-livro').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const isbn = this.dataset.isbn;
            abrirModalEntrega(id, isbn);
        });
    });
});