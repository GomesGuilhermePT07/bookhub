<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION['id'])) {
    header("Location: ../ModuloProjeto/logins/login.php");
    exit;
}
?>