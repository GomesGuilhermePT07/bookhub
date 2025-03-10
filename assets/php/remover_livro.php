<?php

header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json");

    session_start();
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    include "config.php";

    try{
        // Conexão com a base de dados 
        $conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        if (!isset($_POST['isbn']) || empty($_POST['isbn'])) {
            echo json_encode(["error" => "ISBN não fornecido."]);
            exit();
        }

        // Capturar o ISBN do livro a ser removido
        $cod_isbn = $_POST['isbn'];

        // Verificar se o livro existe
        $sql_check = "SELECT cod_isbn FROM livros WHERE cod_isbn = :cod_isbn";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bindParam(':cod_isbn', $cod_isbn);
        $stmt_check->execute();

        if ($stmt_check->rowCount() > 0) {
            // Remover o livro
            $sql_delete = "DELETE FROM livros WHERE cod_isbn = :cod_isbn";
            $stmt_delete = $conn->prepare($sql_delete);
            $stmt_delete->bindParam(':cod_isbn', $cod_isbn);
            $stmt_delete->execute();

            $_SESSION['message'] = "Livro removido com sucesso!";
        } else {
            $_SESSION['message'] = "Livro não encontrado.";
        }
    } catch (PDOException $e) {
        $_SESSION['message'] = "Erro: " . $e->getMessage();
    }

    // Redirecionar de volta para a página principal
    // header("Location: index.php");
    // exit();