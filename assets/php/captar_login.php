<?php

include "config.php";

try{

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];

        $sql = "SELECT * FROM utilizadores WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
        $email = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($email) {
            if (password_verify($password, $email["password"])){
                header("Location: ../../index.php");
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["email"] = $email["email"];
            } else {
                echo "Senha incorreta. Tente novamente.";
            }
        } else {
            header("Location: ../../logins/login.php");
            alert("Utilizador não encontrado. Verifique as credenciais ou registe-se.");
        }
    }
} catch (PDOException $e){
    echo "Erro ao conectar à base de dados: " . $e->getMessage();
}