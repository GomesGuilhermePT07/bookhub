<?php
include "config.php";
session_start();
header('Content-Type: application/json');

try {
    $conn = new PDO("mysql:host=$host;port=3306;dbname=$dbname", $dbusername, $dbpassword);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT cod_isbn, titulo, edicao, autor, numero_paginas, quantidade, resumo, disponivel FROM livros";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($livros);
} catch (PDOException $e) {
    echo json_encode(["error" => "Erro ao buscar livros: " . $e->getMessage()]);
}
?>