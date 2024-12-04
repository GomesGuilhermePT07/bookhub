<?php

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Obter e validar os dados do formulário
        $nome_completo = $_POST["nome_completo"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $genero = $_POST["genero"] ? $_POST["genero"] : "";

        try{
            require_once "config.php";

            $query = "INSERT INTO utilizadores (nome_completo, email, password, genero) VALUES (?, ?, ?, ?);";
            
            $stmt = $pdo->prepare($query);
            $stmt->execute([$nome_completo, $email, $password, $genero]);

            $pdo = null;
            $stmt = null;

            header("Location: ../logins/login.html");

            die();
        } catch (PDOException $e){
            die("Query failed: " . $e->getMessage());
        }
    } else {
            header("Location: ../logins/registo_com_validacao.html");
    }