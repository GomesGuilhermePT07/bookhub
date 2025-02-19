<?php
include "config.php";

try {
    $conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT cod_isbn, titulo, edicao, autor, numero_paginas, quantidade, resumo FROM livros";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($livros);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erro ao buscar livros: " . $e->getMessage()]);
}
?>
