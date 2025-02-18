<?php

session_start(); // Iniciar a sessão


// Incluir o arquivo de configuração da base de dados
require_once 'config.php';

// Verificar se o formulário foi submetido
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capturar os dados do formulário
    $cod_isbn = $_POST['isbn'];
    $titulo = $_POST['title'];
    $edicao = $_POST['edition'];
    $autor = $_POST['author'];
    $numero_paginas = $_POST['numero_paginas'];
    $quantidade = $_POST['quantity'];
    $resumo = $_POST['summary'];

    
    try {
        $conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar a query SQL para inserir os dados
        $sql = "INSERT INTO livros (cod_isbn, titulo, edicao, autor, numero_paginas, quantidade, resumo)
                VALUES (:cod_isbn, :titulo, :edicao, :autor, :numero_paginas, :quantidade, :resumo);";
        
        $stmt = $conn->prepare($sql);

        // Bind dos parâmetros
        $stmt->bindParam(':cod_isbn', $cod_isbn);
        $stmt->bindParam(':titulo', $titulo);
        $stmt->bindParam(':edicao', $edicao);
        $stmt->bindParam(':autor', $autor);
        $stmt->bindParam(':numero_paginas', $numero_paginas);
        $stmt->bindParam(':quantidade', $quantidade);
        $stmt->bindParam(':resumo', $resumo);

        if(empty($cod_isbn) || empty($titulo) || empty($edicao) || empty($autor) || empty($numero_paginas) || empty($quantidade)) {
            // $_SESSION['mensagem'] = "Preencha todos os campos!";
            header("Location: index.php");
            exit();
        }

        // Executar a query
        $stmt->execute();

        // Redirecionar de volta para o index.php com uma mensagem de sucesso
        $_SESSION['mensagem'] = "Livro adicionado com sucesso!";
        header("Location: index.php");
        exit();
    } catch (PDOException $e) {
        // Redirecionar de volta para o index.php com uma mensagem de erro
        $_SESSION['mensagem'] = "Erro ao adicionar o livro: " . $e->getMessage();
    }

    // Fechar a sessão
    $conn = null;
} else {
    // Se o formulário não foi submetido, redirecionar para o index.php
    header("Location: index.php");
    exit();
}
?>