<?php
$host = "localhost";
$dbusername = "root";
$dbpassword = "usbw";
$dbname = "bookhubjb";


// Criar conexão
// $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);

try{
    $conn = new PDO("mysql:host=$host;port=3306;dbname=$dbname", $dbusername, $dbpassword);
    echo "Conectado com sucesso";
} catch (PDOException $e) {
    die("Erro ao conectar à base de dados: " . $e->getMessage());
}

// Checar conexão
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }