<?php
$host = "localhost";
$dbusername = "root";
$dbpassword = "usbw";
$dbname = "bookhubjb";


// Criar conexão
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

// Checar conexão
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}