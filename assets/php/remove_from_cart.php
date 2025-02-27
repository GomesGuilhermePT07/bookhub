<?php
session_start();
require_once 'captar_login.php';

if (isset($_GET['id'])) {
    $book_id = $_GET['id'];
    
    if (isset($_SESSION['cart'][$book_id])) {
        unset($_SESSION['cart'][$book_id]);
    }
}

header('Location: cart.php');
exit;