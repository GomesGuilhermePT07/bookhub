<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["nome_completo"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $genero = $_POST["genero"];

    try{
        require_once "config.php";

        $query = "INSERT INTO utilizadores (nome_completo, email, password, genero)
                  VALUES (:username, :email, :password, :genero);";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":nome_completo", $username);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":genero", $genero);

        $stmt->execute();

        $pdo = null;
        $stmt = null;

        die();
    } catch (PDOException $e) {
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: ../registo_com_validacao.php");
}