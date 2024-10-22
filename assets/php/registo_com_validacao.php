<?php
// Conexão com a base de dados
$servername = "localhost";
$username = "root";
$password = "usbw";
$dbname = "bookhub";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificação da conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Receber os dados do formulário
$nome_completo = $_POST['nome_completo'];
$email = $_POST['email'];
$senha = password_hash($_POST['senha'], PASSWORD_DEFAULT); // Criptografar a senha
$genero = $_POST['genero'];

// Inserir os dados na tabela 'utilizadores'
$sql = "INSERT INTO utilizadores (nome_completo, email, senha, genero) VALUES ('$nome_completo', '$email', '$senha', '$genero')";

if ($conn->query($sql) === TRUE) {
    // Redirecionar para a página de login após o registo
    header("Location: login.html");
    exit();
} else {
    echo "Erro ao registar: " . $conn->error;
}

$conn->close();
?>
