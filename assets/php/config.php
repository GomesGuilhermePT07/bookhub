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
define('SMTP_USER', 'bookhub.adm1@gmail.com');
define('SMTP_PASS', 'bookhubAdministrador1!'); // Use uma senha de app do Google

if($_SESSION['admin'] == 1){
    define('SITE_URL', 'http://localhost:8080/ModuloProjeto/index.php');
} elseif ($_SESSION['admin'] == 0) {
    define('SITE_URL', 'http://localhost:8080/ModuloProjeto/index_user.php');
}
