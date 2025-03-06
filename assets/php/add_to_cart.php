<?php
session_start();
require_once 'captar_login.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $isbn = $_POST['isbn'];
    $title = $_POST['title'];
    // $price = $_POST['author'];

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$isbn])) {
        $_SESSION['cart'][$isbn]['quantity']++;
    } else {
        $_SESSION['cart'][$isbn] = [
            'title' => $title,
            // 'price' => $price,
            'quantity' => 1
        ];
    }

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit;
}