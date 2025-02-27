<?php
session_start();
require_once 'captar_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = $_POST['book_id'];
    $title = $_POST['title'];
    $price = $_POST['price'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$book_id])) {
        $_SESSION['cart'][$book_id]['quantity']++;
    } else {
        $_SESSION['cart'][$book_id] = [
            'title' => $title,
            'price' => $price,
            'quantity' => 1
        ];
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}