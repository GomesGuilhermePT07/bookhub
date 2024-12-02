<?php
    $servername = "localhost";
    $username = "root";
    $password = ""; // Altere para a sua password do MySQL
    $dbname = "bookhub";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexão falhou: " . $conn->connect_error);
    }
?>