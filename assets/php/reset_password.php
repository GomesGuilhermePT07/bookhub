<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $nova_senha = md5($_POST['nova_senha']); // ENCRIPTA COM MD5

    // Verifica se o token é válido
    $query = $conn->prepare("SELECT email FROM password_resets WHERE token = ? AND expira_em > NOW()");
    $query->execute([$token]);
    $reset = $query->fetch(PDO::FETCH_ASSOC);

    if ($reset) {
        $email = $reset['email'];

        // Atualiza a senha do usuário
        $query = $conn->prepare("UPDATE utilizadores SET password = ? WHERE email = ?");
        $query->execute([$nova_senha, $email]);

        // Remove o token usado
        $query = $conn->prepare("DELETE FROM password_resets WHERE email = ?");
        $query->execute([$email]);

        echo "Senha redefinida com sucesso!";
    } else {
        echo "Token inválido ou expirado!";
    }
}
?>
