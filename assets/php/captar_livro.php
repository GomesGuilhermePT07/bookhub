<?php

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

    // Conectar à base de dados
    try {
        require_once "config.php";

        $pdo = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Preparar a query SQL para inserir os dados
        $query = "INSERT INTO livros (cod_isbn, titulo, edicao, autor, numero_paginas, quantidade, resumo)
                VALUES (:cod_isbn, :titulo, :edicao, :autor, :numero_paginas, :quantidade, :resumo)";
        
        $stmt = $pdo->prepare($query);

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

        if($stmt){
            echo "Livro cadastrado com sucesso!";
        } else {
            die("Query failed: " . $stmt->errorInfo());
        }

        if(empty($cod_isbn) || empty($titulo) || empty($edicao) || empty($autor) || empty($numero_paginas) || empty($quantidade)) {
            $_SESSION['mensagem'] = "Preencha todos os campos!";
            header("Location: index.php");
            exit();
        }

        $pdo = null;
        $stmt = null;

        header("Location: index.php");

        die();
    } catch (PDOException $e) {
        // Redirecionar de volta para o index.php com uma mensagem de erro
        die("Query failed: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
}