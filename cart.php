<?php

// session_start();
require_once 'assets/php/check_login.php'; // Verifica se o utilizador está logado

?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/cart_style.css">
    <title>BOOKhub | Carrinho</title>
</head>
<body>
    <header>
        <!-- Mesmo header do index_user.php -->
    </header>

    <main class ="cart-container">
        <h1>O teu carrinho</h1>

        <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
            <div class="cart-items">
                <table>
                    <thead>
                        <tr>
                            <th>Livro</th>
                            <!-- <th>Preço</th> -->
                            <th>Quantidade</th>
                            <!-- <th>Total</th> -->
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            // $total = 0;
                            foreach($_SESSION['cart'] as $isbn => $item):
                            // $subtotal = $item['price'] * $item['quantity'];
                            // $total += $subtotal;
                        ?>
                    <tr>
                        <td><?= $item['title'] ?></td>
                        
                        <td>
                            <form action="./assets/php/update_cart.php" method="POST">
                                <input type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1">
                                <input type="hidden" name="isbn" value="<?= $isbn ?>">
                                <button type="submit">Atualizar</button>
                            </form>
                        </td>
                        
                        <td>
                            <a href="./assets/php/remove_from_cart.php?isbn=<?= $isbn ?>" class="btn-remove">Remover</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

                 
            </div>
            <?php else: ?>
            <p class="empty-cart">Seu carrinho está vazio.</p>
            <?php endif; ?>
    </main>
</body>
</html>