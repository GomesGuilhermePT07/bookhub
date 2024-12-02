<?php
session_start();

// Conexão com a base de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bookhub";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber os dados do formulário
$nome_completo = $_POST['nome_completo'];
$senha = $_POST['senha'];

// Buscar o utilizador pelo nome
$sql = "SELECT * FROM utilizadores WHERE nome_completo = '$nome_completo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Verificar a senha
    if (password_verify($senha, $row['senha'])) {
        // Armazenar o nome do utilizador na sessão
        $_SESSION['nome_completo'] = $row['nome_completo'];
        
        // Redirecionar para index.html
        header("Location: ../Módulo Projeto/index.html");
        exit();
    } else {
        echo "Senha incorreta.";
    }
} else {
    // Se o utilizador não for encontrado, redireciona para a página de registro
    header("Location: ../logins/registo com validacao.html");
    exit();
}

$conn->close();
?>