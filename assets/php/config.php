<?php
$dsn = "mysql:host=localhost;dbname=bookhub";
$username = "root";
$password = "usbw";

try {
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
    die("Connection failed: " . $e->getMessage();)
}