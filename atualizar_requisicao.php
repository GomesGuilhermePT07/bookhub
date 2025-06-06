<?php
session_start();

if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("Location: login.php");
    exit();
}

require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $novo_estado = isset($_POST['novo_estado']) ? $_POST['novo_estado'] : null;

    if ($id && $novo_estado) {
        // Atualizar estado da requisição
        $stmt = $pdo->prepare("UPDATE requisicoes SET estado = :estado WHERE id = :id");
        $stmt->execute([
            ':estado' => $novo_estado,
            ':id' => $id
        ]);
    }
}

header("Location: gerir-requisicoes.php");
exit();
?>