<?php

    session_start(); // Iniciar a sessão

    // Incluir o arquivo de configuração da base de dados
    include "config.php";

    try{
        $conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Capturar os dados do formulário
        $cod_isbn = $_POST['isbn'];
        $titulo = $_POST['title'];
        $edicao = $_POST['edition'];
        $autor = $_POST['author'];
        $numero_paginas = $_POST['numero_paginas'];
        $quantidade = $_POST['quantity'];
        $resumo = $_POST['summary'];

        // Verificar se o livro já existe na tabela
        $sql_check = "SELECT quantidade FROM livros WHERE cod_isbn = :cod_isbn;";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':cod_isbn', $cod_isbn);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0){
            // Livro já existe, incrementar a quantidade
            $row = $stmt_check->fetch(PDO::FETCH_ASSOC);
            $nova_quantidade = $row['quantidade'] + $quantidade;

            $sql_update = "UPDATE livros SET quantidade = :nova_quantidade WHERE cod_isbn = :cod_isbn;";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bindParam(':nova_quantidade', $nova_quantidade);
            $stmt_update->bindParam(':cod_isbn', $cod_isbn);
            $stmt_update->execute();

            $_SESSION['message'] = "Quantidade do livro atualizada com sucesso!";
        } else {
            // Livro não existe, inserir novo registo
            $sql_insert = "INSERT INTO livros (cod_isbn, titulo, edicao, autor, numero_paginas, quantidade, resumo)
                           VALUES (:cod_isbn, :titulo, :edicao, :autor, :numero_paginas, :quantidade, :resumo);";
            
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bindParam(':cod_isbn', $cod_isbn);
            $stmt_insert->bindParam(':titulo', $titulo);
            $stmt_insert->bindParam(':edicao', $edicao);
            $stmt_insert->bindParam(':autor', $autor);
            $stmt_insert->bindParam(':numero_paginas', $numero_paginas);
            $stmt_insert->bindParam(':quantidade', $quantidade);
            $stmt_insert->bindParam(':resumo', $resumo);
            
            $stmt_insert->execute();

            $_SESSION['message'] = "Novo livro registado com sucesso!";
        }

        // $sql = "INSERT INTO livros (cod_isbn, titulo, edicao, autor, numero_paginas, quantidade, resumo)
        //         VALUES (:cod_isbn, :titulo, :edicao, :autor, :numero_paginas, :quantidade, :resumo);";

        // $stmt = $conn->prepare($sql);

        // $stmt->bindParam(':cod_isbn', $cod_isbn);
        // $stmt->bindParam(':titulo', $titulo);
        // $stmt->bindParam(':edicao', $edicao);
        // $stmt->bindParam(':autor', $autor);
        // $stmt->bindParam(':numero_paginas', $numero_paginas);
        // $stmt->bindParam(':quantidade', $quantidade);
        // $stmt->bindParam(':resumo', $resumo);

        // $stmt->execute();
    } catch (PDOException $e) {
        $_SESSION['message'] = "Erro: " . $e.getMessage();
    }

    // Redirecionar de volta para a página principal
    header("Location: index.php");
    exit();
?>