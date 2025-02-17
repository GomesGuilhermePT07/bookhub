<?php

// Iniciar a sessão
session_start();

// Incluir o arquivo de configuração da base de dados
require_once 'config.php';

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os dados do formulário
    $cod_isbn = $_POST['isbn'];
    $titulo = $_POST['title'];
    $edicao = $_POST['edition'];
    $autor = $_POST['author'];
    $numero_paginas = $_POST['genre'];
    $quantidade = $_POST['quantity'];
    $resumo = $_POST['summary'];

    // Conectar à base de dados
    try {
        $conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar a query SQL para inserir os dados
        $sql = "INSERT INTO livros (cod_isbn, titulo, edicao, autor, numero_paginas, quantidade, resumo)
                VALUES (:cod_isbn, :titulo, :edicao, :autor, :numero_paginas, :quantidade, :resumo)";
        
        $stmt = $conn->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':cod_isbn', $cod_isbn);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':edicao', $edicao);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':numero_paginas', $numero_paginas);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':resumo', $resumo);

        // Executar a query
        $stmt->execute();

        // Redirecionar de volta para o index.php com uma mensagem de sucesso
        $_SESSION['mensagem'] = "Livro adicionado com sucesso!";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        // Redirecionar de volta para o index.php com uma mensagem de erro
        $_SESSION['mensagem'] = "Erro ao adicionar o livro: " . $e->getMessage();
        header("Location: index.php");
        exit();
    }

    // Fechar a sessão
    $conn = null;
} else {
    // Se o formulário não foi submetido, redirecionar para o index.php
    header("Location: index.php");
    exit();
}
?>