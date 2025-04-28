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

        // Insere ou atualiza o token
        $query = $conn->prepare("REPLACE INTO password_resets (email, token, expira_em) VALUES (?, ?, ?)");
        $query->execute([$email, $token, $expira_em]);

        // Configuração do email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'seuemail@gmail.com'; // Alterar
            $mail->Password = 'suasenha'; // Alterar
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('suporte@bookhub.com', 'Suporte BookHub');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Redefinição de Senha";
            $reset_link = "http://localhost:8080/ModuloProjeto/logins/reset_password.php?token=$token";
            $mail->Body = "Clique para redefinir: <a href='$reset_link'>$reset_link</a> (válido por 1 hora)";
            
            if($mail->send()) {
                $message = "<div class='success'>Email enviado com sucesso!</div>";
            }
        } catch (Exception $e) {
            $message = "<div class='error'>Erro no envio: {$mail->ErrorInfo}</div>";
        }
    } else {
        $message = "<div class='error'>Email não registado</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Password</title>
    <link rel="stylesheet" href="../assets/css/login_style.css">
</head>
<body>
    <div class="login-container">
        <h1>Recuperação de Password</h1>
        <?= $message ?>
        <form method="POST">
            <div class="input-container">
                <i class="fa fa-envelope"></i>
                <input type="email" name="email" placeholder="Seu email" required>
            </div>
            <button type="submit">Enviar Link</button>
        </form>
        <p class="registo">Voltar ao <a href="login.php">Login</a></p>
    </div>
</body>
</html>