<?php
$host = "localhost";
$dbusername = "root";
$dbpassword = "usbw";
$dbname = "bookhubjb";

try{
    $pdo = new pdo("mysql:host=$host;port=3306;dbname=$dbname", $dbusername, $dbpassword);
    // echo "Conexão bem sucedida";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

// Verificar conexão
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }