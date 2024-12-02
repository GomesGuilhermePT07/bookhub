<?php
session_start();

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
$senha = $_POST['senha'];

// Buscar o utilizador pelo nome
$sql = "SELECT * FROM utilizadores WHERE nome_completo = '$nome_completo'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "Utilizador não encontrado. Você pode registar-se <a href='../logins/registo com validacao.html'>aqui</a>.";
    $row = $result->fetch_assoc();
    
    // Verificar a senha
    if (password_verify($senha, $row['password'])) {
        // Armazenar o nome do utilizador na sessão
        $_SESSION['nome_completo'] = $row['nome_completo'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['admin'] = $row['admin'];

        // Verificar o código secreto
        if ($_POST['codigo_secreto'] == '1234'){
            $_SESSION['admin'] = 1;
        }

        // Registar a atividade
        $atividade = isset($_POST['oquefazer']) ? implode(", " $_POST['oquefazer']) : 'Nenhuma';
        $sql = "INSERT INTO atividades (nome_completo, atividade) VALUES ('{$_SESSION['user_id']}', '$atividade')";
        $conn->query($sql);

        // Redirecionar para index.html
        header("Location: index.html");
        exit();
    } else {
        echo "Senha incorreta.";
    }
} else {
    echo "Utilizador não encontrado.";
}

$conn->close();
?>
