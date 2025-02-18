<?php

    session_start(); // Iniciar a sessão

    // Incluir o arquivo de configuração da base de dados
    include "config.php";

    $conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $cod_isbn = $_POST['isbn'];
    $titulo = $_POST['title'];
    $edicao = $_POST['edition'];
    $autor = $_POST['author'];
    $numero_paginas = $_POST['numero_paginas'];
    $quantidade = $_POST['quantity'];
    $resumo = $_POST['summary'];


    $sql = "INSERT INTO livros (cod_isbn, titulo, edicao, autor, numero_paginas, quantidade, resumo)
                    VALUES (:cod_isbn, :titulo, :edicao, :autor, :numero_paginas, :quantidade, :resumo);";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':cod_isbn', $cod_isbn);
    $stmt->bindParam(':titulo', $titulo);
    $stmt->bindParam(':edicao', $edicao);
    $stmt->bindParam(':autor', $autor);
    $stmt->bindParam(':numero_paginas', $numero_paginas);
    $stmt->bindParam(':quantidade', $quantidade);
    $stmt->bindParam(':resumo', $resumo);

    $stmt->execute();
?>