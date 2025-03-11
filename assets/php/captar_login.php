<?php

include "config.php";

try{

    $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $password = $_POST["password"];
        $codigo_secreto = $_POST["cod_secreto"];

        $sql = "SELECT * FROM utilizadores WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([":email" => $email]);
        $utilizador = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($utilizador) {
            if (md5($password) === $utilizador["password"]){
                header("Location: ../../index.php");
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["email"] = $utilizador["email"];
                $_SESSION['id'] = $utilizador['id']; // Adiciona o ID do usuário à sessão
                

                // Verificar o código secreto
                if ($codigo_secreto === "1234"){
                    $_SESSION["admin"] = true;

                    // Atualizar a coluna "admin" na base de dados conforme o código inserido pelo utilizador
                    $updateSql = "UPDATE utilizadores SET admin = 1 WHERE email = :email;";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([":email" => $utilizador["email"]]);

                    header("Location: ../../index.php"); // Redireciona para o painel do administrador
                } else {
                    $_SESSION["admin"] = false;

                    // Atualizar a coluna "admin" na base de dados caso o utilizador erre o código
                    $updateSql = "UPDATE utilizadores SET admin = 0 WHERE email = :email;";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([":email" => $utilizador["email"]]);
                    header("Location: ../../index_user.php"); // Redireciona para o site normal como utilizador comum
                }
            } else {
                echo "Senha incorreta. Tente novamente.";
                header("Location: ../../logins/login.php");
            }
        } else {
            header("Location: ../../logins/login.php");
            alert("Utilizador não encontrado. Verifique as credenciais ou registe-se.");
        }
    }
} catch (PDOException $e){
    echo "Erro ao conectar à base de dados: " . $e->getMessage();
}