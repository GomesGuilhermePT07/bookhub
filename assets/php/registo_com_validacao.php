<?php
// Parâmetros de conexão
$servername = "localhost";
$username = "root";         // Nome de utilizador padrão no phpMyAdmin do USBWebserver
$password = "";             // A senha padrão é vazia para USBWebserver
$dbname = "bookhub";   // Nome da base de dados

// Criar conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

// Verificar se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obter e sanitizar os dados do formulário
    $nome_completo = $conn->real_escape_string(trim($_POST['nome_completo']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $senha = $conn->real_escape_string(trim($_POST['senha']));
    $genero = isset($_POST['genero']) ? $_POST['genero'] : '';

    // Validação dos campos
    $erros = [];

    if (strlen($nome_completo) < 5) {
        $erros[] = "O nome deve ter no mínimo 5 caracteres.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erros[] = "Digite um email válido.";
    }

    if (strlen($senha) < 8) {
        $erros[] = "A senha deve ter no mínimo 8 caracteres.";
    }

    if (!in_array($genero, ['m', 'f', 'o'])) {
        $erros[] = "Género inválido.";
    }

    // Se não houver erros, insira o utilizador na base de dados
    if (empty($erros)) {
        // Hash da senha para segurança
        $senha_hash = password_hash($senha, PASSWORD_BCRYPT);

        // Preparar a consulta de inserção
        $sql = "INSERT INTO usuarios (nome_completo, email, senha, genero) VALUES ('$nome_completo', '$email', '$senha_hash', '$genero')";

        if ($conn->query($sql) === TRUE) {
            echo "Registo efetuado com sucesso!";
        } else {
            echo "Erro ao registrar o utilizador: " . $conn->error;
        }
    } else {
        // Mostrar mensagens de erro
        foreach ($erros as $erro) {
            echo "<p>$erro</p>";
        }
    }
}

// Fechar a conexão com o banco de dados
$conn->close();
?>