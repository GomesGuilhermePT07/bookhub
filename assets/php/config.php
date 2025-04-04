<?php
$host = "localhost";
$dbusername = "root";
$dbpassword = "usbw";
$dbname = "bookhubjb";

<<<<<<< HEAD
try{
    $pdo = new pdo("mysql:host=$host;port=3306;dbname=$dbname", $dbusername, $dbpassword);
    echo "Conexão bem sucedida";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
=======

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
>>>>>>> f31048b388960444f3ed41bd9392ae359b49400b
