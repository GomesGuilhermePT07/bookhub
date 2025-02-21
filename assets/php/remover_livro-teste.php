<?php

session_start();

include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $isbn = $_POST['isbn'];

    $conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "DELETE FROM livros WHERE cod_isbn = :isbn";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':isbn', $isbn);
    $stmt->execute();

    echo json_encode(["success" => true]);
    
} else {
    echo "erro";
}
