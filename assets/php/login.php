<?php
session_start();
require 'config.php';

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $nome_completo = $conn->real_escape_string(trim($_POST['nome_completo']));
    $senha = trim($_POST['senha']);

    $sql = "SELECT * FROM utilizadores WHERE nome_completo = '$nome_completo'";
    $result = $conn->query($sql);

    if($result->num_rows > 0){
        $row = $result->fetch_assoc();

        // Validar a senha
        if(password_verify($senha, $row['senha'])){
            $_SESSION['nome_completo'] = $row['nome_completo'];

            // Verificar se é administrador
            if($row['admin'] == 1){
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
?>