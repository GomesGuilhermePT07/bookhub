<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../assets/php/config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verifica se o email existe
    $query = $conn->prepare("SELECT id FROM utilizadores WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Gera token seguro
        $token = bin2hex(random_bytes(32));
        $expira_em = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Insere/atualiza token (usando REPLACE para simplificar)
        $conn->prepare("REPLACE INTO password_resets (email, token, expira_em) VALUES (?, ?, ?)")
             ->execute([$email, $token, $expira_em]);

        // Configuração do email
        $mail = new PHPMailer(true);
        try {
            // ... (mantenha suas configurações SMTP aqui)

            // Link ABSOLUTO corrigido:
            $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/ModuloProjeto/logins/reset_password.php?token=" . $token;
            
            $mail->Body = "Clique para redefinir: <a href='$reset_link'>REDEFINIR SENHA</a>";
            
            if ($mail->send()) {
                $message = "<div class='success'>Email enviado! Verifique sua caixa de entrada.</div>";
            }
        } catch (Exception $e) {
            $message = "<div class='error'>Erro: {$mail->ErrorInfo}</div>";
        }
    } else {
        $message = "<div class='error'>Email não registrado!</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <link rel="stylesheet" href="../assets/css/login_style.css">
</head>
<body>
    <div class="login-container">
        <h1>Recuperação de Senha</h1>
        <?= $message ?>
        <form method="POST" action=""> <!-- Action vazio para enviar para a mesma página -->
            <div class="input-container">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" placeholder="Seu email" required>
            </div>
            <button type="submit">Enviar Link</button>
        </form>
        <p class="registo"><a href="login.php">Voltar ao Login</a></p>
    </div>
</body>
</html>