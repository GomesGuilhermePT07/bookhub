<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    try{
        session_start();
        require_once "captar.php"; 

        $email = $pdo->quote(trim($_POST["email"])); 
        $password = trim($_POST["password"]);

        // Consultar o banco de dados para verificar se o email existe
        $sql = "SELECT * FROM utilizadores WHERE email = $email;";
        $result = $pdo->query($sql);

        if ($result->rowCount() > 0) {
            $row = $result->fetch(PDO::FETCH_ASSOC);

            // Validar a senha
            if (password_verify(:password, $password)) { // Certifique-se de que a coluna de senha é 'password'
                // Iniciar a sessão
                $_SESSION["email"] = :email;
                $_SESSION["password"] = :password; 

                // Redirecionar para a página principal
                header("Location: ../../index.html");
                exit();
            } else {
                echo "Password incorreta.";
            }
        } else {
            echo "Utilizador não encontrado.";
        }
    } catch (PDOException $e){
        echo "Erro ao conectar à base de dados: " . $e->getMessage();
    }
}