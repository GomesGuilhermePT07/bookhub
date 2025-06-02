<?php
$host = "localhost";
$dbusername = "root";
$dbpassword = "usbw";
$dbname = "bookhubjb";

try{
    $pdo = new pdo("mysql:host=$host;port=3306;dbname=$dbname", $dbusername, $dbpassword);
    // echo "Conexão bem sucedida";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'suporte.bookhub@gmail.com');
define('SMTP_PASS', 'mxmkqzyajniojvpa'); // Senha de app

if($_SESSION['admin'] == 1){
    define('SITE_URL', 'http://localhost:8080/ModuloProjeto/index.php');
} elseif ($_SESSION['admin'] == 0) {
    define('SITE_URL', 'http://localhost:8080/ModuloProjeto/index_user.php');
}

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);