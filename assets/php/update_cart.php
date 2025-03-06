<?php
session_start();
require_once 'check_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = $_POST['isbn'];
    $quantity = (int)$_POST['quantity'];

    if ($quantity > 0 && isset($_SESSION['cart'][$isbn])) {
        $_SESSION['cart'][$isbn]['quantity'] = $quantity;
    }

    header('Location: cart.php');
    exit;
}