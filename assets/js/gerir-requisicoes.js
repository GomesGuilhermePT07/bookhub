document.addEventListener('DOMContentLoaded', function() {
  // --- Modal de Entrega ---
  const entregaModal       = document.getElementById('entregaModal');
  const closeEntregaModal  = document.getElementById('closeEntregaModal');
  const btnCancelarEntrega = document.getElementById('btnCancelarEntrega');
  const btnConfirmarEntrega= document.getElementById('btnConfirmarEntrega');
  let requisicaoId = null;

  function abrirModalEntrega(id, isbn) {
    requisicaoId = id;
    fetch(`assets/php/buscar_livro.php?isbn=${isbn}`)
      .then(response => {
        if (!response.ok) throw new Error('Erro na rede');
        return response.json();
      })
      .then(data => {
        document.getElementById('tituloLivro').textContent = data.titulo;
        document.getElementById('autorLivro').textContent  = data.autor;
        document.getElementById('isbnLivro').textContent   = isbn;
        // Capa via Google Books
        fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`)
          .then(res => res.json())
          .then(googleData => {
            const thumb = googleData.items?.[0]?.volumeInfo?.imageLinks?.thumbnail;
            document.getElementById('capaLivro').src = thumb || 'https://via.placeholder.com/128x186';
          })
          .catch(() => { document.getElementById('capaLivro').src = 'https://via.placeholder.com/128x186'; });
        entregaModal.style.display = 'block';
      })
      .catch(err => {
        console.error('Erro ao buscar livro:', err);
        alert('Erro ao carregar detalhes do livro');
      });
  }

  document.querySelectorAll('.entregar-livro').forEach(btn => {
    btn.addEventListener('click', function() {
      abrirModalEntrega(this.dataset.id, this.dataset.isbn);
    });
  });

  closeEntregaModal.onclick = () => entregaModal.style.display = 'none';
  btnCancelarEntrega.onclick = () => entregaModal.style.display = 'none';
  btnConfirmarEntrega.onclick = () => {
    if (requisicaoId) {
      fetch(`assets/php/entregar_livro.php?id=${requisicaoId}`)
        .then(res => res.json())
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
  };

  // --- Modal de Devolução ---
  const devolucaoModal       = document.getElementById('devolucaoModal');
  const closeDevolucaoModal  = document.getElementById('closeDevolucaoModal');
  const btnCancelarDevolucao = document.getElementById('btnCancelarDevolucao');
  const btnConfirmarDevolucao= document.getElementById('btnConfirmarDevolucao');
  let devolucaoId = null;

  document.querySelectorAll('.btn-confirmar-devolucao').forEach(btn => {
    btn.addEventListener('click', () => {
      devolucaoId = btn.dataset.id;
      const isbn = btn.dataset.isbn;
      document.getElementById('tituloLivroDevolucao').textContent = btn.dataset.titulo;
      document.getElementById('autorLivroDevolucao').textContent  = btn.dataset.autor;
      document.getElementById('isbnLivroDevolucao').textContent   = btn.dataset.isbn;

      fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`)
      .then(res => res.json())
      .then(googleData => {
        const thumb = googleData.items?.[0]?.volumeInfo?.imageLinks?.thumbnail;
        document.getElementById('capaLivroDevolucao')
                .src = thumb || 'https://via.placeholder.com/128x186';
      })
      .catch(() => {
        document.getElementById('capaLivroDevolucao')
                .src = 'https://via.placeholder.com/128x186';
      });

      devolucaoModal.style.display = 'block';
    });
  });

  [closeDevolucaoModal, btnCancelarDevolucao].forEach(el =>
    el.addEventListener('click', () => devolucaoModal.style.display = 'none')
  );

  btnConfirmarDevolucao.addEventListener('click', () => {
    if (devolucaoId) {
      window.location.href = `assets/php/concluir_devolucao.php?id=${devolucaoId}`;
    }
    devolucaoModal.style.display = 'none';
  });

  // Fecha ambos modais ao clicar fora
  window.addEventListener('click', event => {
    if (event.target === entregaModal) entregaModal.style.display = 'none';
    if (event.target === devolucaoModal) devolucaoModal.style.display = 'none';
  });
});