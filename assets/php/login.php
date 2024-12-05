<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string(trim($_POST['email']));
    $senha = trim($_POST['senha']);

    // Alterar a consulta para buscar pelo email
    $sql = "SELECT * FROM utilizadores WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Validar a senha
        if (password_verify($senha, $row['password'])) { // Certifique-se de que a coluna de senha é 'password'
            $_SESSION['nome_completo'] = $row['nome_completo'];

            // Verificar se é administrador
            if ($row['admin'] == 1) {
                header("Location: ../admin/index.html"); // Página do administrador
            } else {
                header("Location: ../Módulo Projeto/index.html"); // Página do utilizador comum
            }
            exit();
        } else {
            echo "Senha incorreta.";
        }
    } else {
        echo "Utilizador não encontrado.";
    }
}
$conn->close();