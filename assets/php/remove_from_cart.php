<?php
session_start();
require_once 'check_login.php';

if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
    
    if (isset($_SESSION['cart'][$isbn])) {
        unset($_SESSION['cart'][$isbn]);
    }
}

header('Location: cart.php');
exit;