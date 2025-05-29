<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION['id'])) {
    header("Location: /ModuloProjeto/logins/login.php");
    exit;
}

// Buscar dados do usuário incluindo admin
$stmt = $pdo->prepare("SELECT * FROM utilizadores WHERE id = ?");
$stmt->execute([$_SESSION['id']]);
$user = $stmt->fetch();

if (!$user) {
    session_destroy();
    header("Location: /ModuloProjeto/logins/login.php");
    exit;
}

// Definir flag de admin na sessão
$_SESSION['admin'] = isset($user['admin']) ? $user['admin'] : 0;
$_SESSION['username'] = isset($user['username']) ? $user['username'] : '';
$_SESSION['email'] = isset($user['email']) ? $user['email'] : '';