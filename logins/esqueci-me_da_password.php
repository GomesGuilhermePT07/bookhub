<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';
include '../assets/php/config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    try {
        // Verifica se o email existe
        $query = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
        $query->execute([$email]);
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Gera token seguro
            $token = bin2hex(random_bytes(32));
            $expira_em = date("Y-m-d H:i:s", strtotime("+1 hour"));

            // Insere/atualiza token
            $stmt = $pdo->prepare("REPLACE INTO password_resets (email, token, expira_em) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expira_em]);

            // Configuração do email
            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'suporte.bookhub@gmail.com'; 
                $mail->Password = 'mxmkqzyajniojvpa'; 

                // Remetente deve ser o mesmo do email
                $mail->setFrom('suporte.bookhub@gmail.com', 'Suporte Bookhub');
                $mail->addAddress($email);

                // Conteúdo
                $mail->isHTML(true);
                $mail->Subject = 'Redefinir Senha';
                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/ModuloProjeto/logins/reset_password.php?token=" . $token;
                $mail->Body = "
                    <h3>Redefinição de Password</h3>
                    <p>Clique no link abaixo para redefinir sua password:</p>
                    <p><a href='$reset_link'>Redefinir Password</a></p>
                    <p><small>Este link expira em 1 hora</small></p>
                ";

                if ($mail->send()) {
                    $message = "<div class='success'>Email enviado! Verifique sua caixa de entrada.</div>";
                }
            } catch (Exception $e) {
                $message = "<div class='error'>Erro no envio: " . $mail->ErrorInfo . "</div>";
            }
        } else {
            $message = "<div class='error'>Email não registrado!</div>";
        }
    } catch (PDOException $e) {
        $message = "<div class='error'>Erro de sistema: Por favor tente mais tarde.</div>";
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
        <form method="POST" action="">
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