<?php
// Configurações do banco de dados
$servername = "localhost";  // ou "127.0.0.1"
$username = "root";         // Username do banco (padrão para PHPMyAdmin é "root")
$password = "";             // Password do banco (deixa vazio se o MySQL estiver sem senha)
$dbname = "bookhub";        // Nome da base de dados que criaste

// Conexão ao banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica se a conexão está bem-sucedida
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Recebe os dados do formulário
$nome_completo = $_POST['nome_completo'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Encripta a senha
$genero = $_POST['genero'];

// Inserção dos dados na tabela
$sql = "INSERT INTO utilizadores (nome_completo, email, senha, genero) VALUES ('$nome_completo', '$email', '$senha', '$genero')";

if ($conn->query($sql) === TRUE) {
    echo "Registo efetuado com sucesso!";
    header("Location: login.html"); // Redireciona para a página de login
    exit();
} else {
    echo "Erro: " . $sql . "<br>" . $conn->error;
}

// Fecha a conexão
$conn->close();
?>
