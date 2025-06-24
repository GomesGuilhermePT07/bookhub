<?php
    // session_start();
    require_once 'assets/php/config.php';
    require_once 'assets/php/check_login.php';

    $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }
    
    // Verificar se o usuário está logado
    $loggedIn = isset($_SESSION['id']);
    $cartItems = [];
    $cartCount = 0;

    // Mensagens de feedback
    $error_message = '';
    $success_message = '';

    if (isset($_SESSION['cart_error'])) {
        $error_message = $_SESSION['cart_error'];
        unset($_SESSION['cart_error']);
    }

    if (isset($_SESSION['cart_success'])) {
        $success_message = $_SESSION['cart_success'];
        unset($_SESSION['cart_success']);
    }

    // Buscar itens do carrinho apenas se estiver logado
    if ($loggedIn) {
        $userId = $_SESSION['id'];

        $stmt = $conn->prepare("
            SELECT c.cod_isbn, c.quantidade, l.titulo, l.autor 
            FROM carrinho c 
            JOIN livros l ON c.cod_isbn = l.cod_isbn 
            WHERE c.id_utilizador = ?
        ");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $cartItems = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();

        // Calcular total de itens
        $countStmt = $conn->prepare("SELECT SUM(quantidade) AS total FROM carrinho WHERE id_utilizador = ?");
        $countStmt->bind_param("i", $userId);
        $countStmt->execute();
        $countResult = $countStmt->get_result();
        $countData = $countResult->fetch_assoc();
        $cartCount = isset($countData['total']) ? $countData['total'] : 0;
        $countStmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/cart_style.css">
    <link rel="stylesheet" href="./assets/css/index_style.css">
    <title>BOOKhub | Carrinho</title>
</head>
<body>
    <header>

        <div class="box-img-header">
            <?php if ($_SESSION['admin'] == 1): ?>
                <a href="index.php">
                    <img class="img-logo" src="assets/img/bookhubFavicon.png" height="80" width="80" alt="Logo BOOKhub">
                </a>
            <?php elseif ($_SESSION['admin'] == 0): ?>
                <a href="index_user.php">
                    <img class="img-logo" src="assets/img/bookhubFavicon.png" height="80" width="80" alt="Logo BOOKhub">
                </a>
            <?php endif; ?>
        </div>

        <nav>
            <!-- HOME -->
            <?php if ($_SESSION['admin'] == 1): ?>
                <a href="index.php" class="nav-links">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
                    </svg>
                </a>
            <?php elseif ($_SESSION['admin'] == 0): ?>
                <a href="index_user.php" class="nav-links">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                        <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z"/>
                    </svg>
                </a>
            <?php endif; ?>
            
            <a href="./livros.php" class="nav-links">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-book" viewBox="0 0 16 16">
                    <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783"/>
                </svg>
            </a>

            <!-- <?php if ($_SESSION['admin'] == 0): ?>
                <a href="./wishlist.php" class="nav-links">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-suit-heart" viewBox="0 0 16 16">
                        <path d="m8 6.236-.894-1.789c-.222-.443-.607-1.08-1.152-1.595C5.418 2.345 4.776 2 4 2 2.324 2 1 3.326 1 4.92c0 1.211.554 2.066 1.868 3.37.337.334.721.695 1.146 1.093C5.122 10.423 6.5 11.717 8 13.447c1.5-1.73 2.878-3.024 3.986-4.064.425-.398.81-.76 1.146-1.093C14.446 6.986 15 6.131 15 4.92 15 3.326 13.676 2 12 2c-.777 0-1.418.345-1.954.852-.545.515-.93 1.152-1.152 1.595zm.392 8.292a.513.513 0 0 1-.784 0c-1.601-1.902-3.05-3.262-4.243-4.381C1.3 8.208 0 6.989 0 4.92 0 2.755 1.79 1 4 1c1.6 0 2.719 1.05 3.404 2.008.26.365.458.716.596.992a7.6 7.6 0 0 1 .596-.992C9.281 2.049 10.4 1 12 1c2.21 0 4 1.755 4 3.92 0 2.069-1.3 3.288-3.365 5.227-1.193 1.12-2.642 2.48-4.243 4.38z"/>
                    </svg>
                </a>
            <?php endif; ?> -->

            <!-- <?php if ($_SESSION['admin'] == 1): ?>
                <a href="./estatisticas.php" class="nav-links">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-percent" viewBox="0 0 16 16">
                        <path d="M13.442 2.558a.625.625 0 0 1 0 .884l-10 10a.625.625 0 1 1-.884-.884l10-10a.625.625 0 0 1 .884 0M4.5 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5m7 6a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3m0 1a2.5 2.5 0 1 0 0-5 2.5 2.5 0 0 0 0 5"/>
                    </svg>
                </a>
            <?php endif; ?> -->

            <?php if ($_SESSION['admin'] == 0): ?>
                <a href="./cart.php" class="nav-links">
                    <div class="cart-icon-container">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                            <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                        </svg>
                        <?php if ($cartCount > 0): ?>
                            <span class="cart-badge"><?= $cartCount ?></span>
                        <?php endif; ?>
                    </div>
                </a>
            <?php endif; ?>
            
            <a href="./gerir-requisicoes.php" class="nav-links">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-gear" viewBox="0 0 16 16">
                    <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
                    <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
                </svg>
            </a>
        </nav>

        <?php if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true): ?>    
            <a href="logins/registo_com_validacao.php" class="btn-action-ref">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person-add" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0M8 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4"/>
                    <path d="M8.256 14a4.5 4.5 0 0 1-.229-1.004H3c.001-.246.154-.986.832-1.664C4.484 10.68 5.711 10 8 10q.39 0 .74.025c.226-.341.496-.65.804-.918Q8.844 9.002 8 9c-5 0-6 3-6 4s1 1 1 1z"/>
                </svg>
            </a>

            <a href="logins/login.php" class="btn-action-ref1">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-box-arrow-left" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M6 12.5a.5.5 0 0 0 .5.5h8a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 0-.5-.5h-8a.5.5 0 0 0-.5.5v2a.5.5 0 0 1-1 0v-2A1.5 1.5 0 0 1 6.5 2h8A1.5 1.5 0 0 1 16 3.5v9a1.5 1.5 0 0 1-1.5 1.5h-8A1.5 1.5 0 0 1 5 12.5v-2a.5.5 0 0 1 1 0z"/>
                    <path fill-rule="evenodd" d="M.146 8.354a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L1.707 7.5H10.5a.5.5 0 0 1 0 1H1.707l2.147 2.146a.5.5 0 0 1-.708.708z"/>
                </svg>
            </a>

        <?php else: ?>
            <a href="./detalhes_conta.php" class="btn-action-ref">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                </svg>
            </a>

            <a href="./logins/logout.php" class="btn-action-logout">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/>
                </svg>
            </a>
        <?php endif; ?>

    </header>

    <main class="cart-container">
    <p>| Carrinho</p>

        <!-- Mensagens de feedback -->
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?= $error_message ?></div>
        <?php endif; ?>
        
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?= $success_message ?></div>
        <?php endif; ?>

        <?php if (!$loggedIn): ?>
            <!-- Mensagem para o utilizador -->
            <p class="empty-cart">
            <a href="./logins/login.php" class="login-link">Inicie sessão</a> para ver o seu carrinho.
            </p>
        <?php elseif ($loggedIn): ?>
        <div class="cart-items">
            <?php foreach ($cartItems as $item): ?>
                <div class="carrinho-item" data-isbn="<?= $item['cod_isbn'] ?>">
                    <div class="livro-esquerda">
                        <img src="https://via.placeholder.com/60x80" 
                            alt="Capa do livro" 
                            class="capa-livro"
                            data-isbn="<?= $item['cod_isbn'] ?>">
                    </div>
                    
                    <div class="info-livro">
                        <h3 class="titulo-livro"><?= htmlspecialchars($item['titulo']) ?></h3>
                        <p class="autor-livro"><?= htmlspecialchars($item['autor']) ?></p>
                    </div>
                        
                        <a href="#" class="btn-remover" data-isbn="<?= $item['cod_isbn'] ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 6h18"/>
                                <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                                <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                                <line x1="10" y1="11" x2="10" y2="17"/>
                                <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php else: ?>
            <p class="empty-cart">Seu carrinho está vazio.</p>
        <?php endif; ?>
        
        <?php if ($loggedIn && !empty($cartItems)): ?>
            <form action="./assets/php/enviar_requisicao.php" method="POST" class="requisitar-form">
                <button type="submit" class="btn-requisitar">
                    Requisitar Livros
                </button>
            </form>
        <?php endif; ?>
    </main>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Função para buscar capas
        async function fetchCartCovers() {
            const items = document.querySelectorAll('.carrinho-item');
            
            for (const item of items) {
                const isbn = item.dataset.isbn;
                const img = item.querySelector('.capa-livro');
                
                try {
                    const response = await fetch(`https://www.googleapis.com/books/v1/volumes?q=isbn:${isbn}`);
                    const data = await response.json();
                    
                    if (data.items && data.items[0].volumeInfo.imageLinks) {
                        img.src = data.items[0].volumeInfo.imageLinks.thumbnail;
                        img.style.opacity = 1; // Remove placeholder effect
                    }
                } catch (error) {
                    console.error(`Erro ao buscar capa para ISBN ${isbn}:`, error);
                }
            }
        }

        fetchCartCovers();
    });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Função para atualizar badge do carrinho
        function updateCartBadge(count) {
            const badge = document.querySelector('.cart-badge');
            if (badge) {
                badge.textContent = count;
                badge.style.display = count > 0 ? 'block' : 'none';
            }
        }

        document.querySelectorAll('.btn-remover').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const isbn = this.dataset.isbn;
                
                // if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
                    fetch(`assets/php/remove_from_cart.php?isbn=${isbn}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            // Remover item visualmente
                            this.closest('.carrinho-item').remove();
                            
                            // Atualizar badge
                            const badge = document.querySelector('.cart-badge');
                            if (badge) {
                                badge.textContent = data.cartCount;
                                if (data.cartCount <= 0) {
                                    badge.style.display = 'none';
                                }
                            }
                            
                            // Recarregar página se carrinho estiver vazio
                            if (document.querySelectorAll('.carrinho-item').length === 0) {
                                location.reload();
                            }
                        } else {
                            alert(data.message);
                        }
                    });
                // }
            });
        });

        // Requisitar livros
        const requisitarBtn = document.querySelector('.btn-requisitar');
        if (requisitarBtn) {
            requisitarBtn.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (confirm('Tem certeza que deseja finalizar a requisição?')) {
                    const form = this.closest('form');
                    
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: new URLSearchParams(new FormData(form))
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            location.reload();
                        } else {
                            alert(data.message);
                        }
                    });
                }
            });
        }
    });
    </script>
    <footer>
        <p>&copy; 2025 BOOKhub. Todos os direitos reservados</p>
    </footer>
</body>
</html>