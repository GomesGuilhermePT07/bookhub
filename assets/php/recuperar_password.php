<?php
include('config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    $stmt = $conn->prepare("SELECT id FROM utilizadores WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Aqui normalmente enviar-se-ia um link para redefinir a password para o email
        echo "Um link de redefinição foi enviado para " . htmlspecialchars($email);
    } else {
        echo "O email não foi encontrado.";
    }

    $stmt->close();
    $conn->close();
}
?>