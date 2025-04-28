<?php
session_start();
require_once 'check_login.php';
require_once 'config.php';

$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];
    $userId = $_SESSION['id'];

    $stmt = $conn->prepare("DELETE FROM carrinho WHERE id_utilizador = ? AND cod_isbn = ?");
    $stmt->bind_param("is", $userId, $isbn);
    
    if ($stmt->execute()) {
        $_SESSION['success'] = "Livro removido do carrinho!";
    } else {
        $_SESSION['error'] = "Erro ao remover livro: " . $stmt->error;
    }
    
    $stmt->close();
    $conn->close();
}
header("Location: ../../cart.php");
exit;
?>