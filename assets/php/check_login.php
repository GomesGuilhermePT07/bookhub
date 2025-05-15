<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION['id'])) {
    header("Location: /ModuloProjeto/logins/login.php");
    exit;
}

// Verificar se é admin
$stmt = $pdo->prepare("SELECT admin FROM utilizadores WHERE id = ?;");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch();

$_SESSION['admin'] = (isset($user['admin'])) ? $user['admin'] : 0;
?>