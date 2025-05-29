<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['id'])) {
    header("Location: /ModuloProjeto/logins/login.php");
    exit;
}

if (!isset($_GET['isbn'])) {
    die("ISBN não especificado");
}

$userId = $_SESSION['id'];
$isbn = $_GET['isbn'];

try {
    // Adaptação para chave primária composta
    $stmt = $pdo->prepare("
        DELETE FROM carrinho 
        WHERE id_utilizador = ? AND cod_isbn = ?
    ");
    $stmt->execute([$userId, $isbn]);
    
    header("Location: ../../cart.php");
} catch (PDOException $e) {
    die("Erro ao remover item: " . $e->getMessage());
}