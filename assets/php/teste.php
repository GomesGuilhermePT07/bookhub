<?php

include "config.php";

$conn = new PDO("mysql:host=$host;port=3307;dbname=$dbname", $dbusername, $dbpassword);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(isset($_SESSION['submit'])){
    print_r('ISBN: ' . $_POST['isbn']);
}
?>