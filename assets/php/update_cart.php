<?php
session_start();
require_once 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0 && isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity'] = $quantity;
    }

    header('Location: cart.php');
    exit;
}