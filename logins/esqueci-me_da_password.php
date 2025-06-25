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

            // Converter para o fuso horário
            $timezone = new DateTimeZone('Europe/Lisbon');
            $data = new DateTime('now +1 hour', $timezone);
            $expira_em = $data->format('Y-m-d H:i:s');

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
                $mail->Subject = 'Redefinir password';
                $reset_link = "http://" . $_SERVER['HTTP_HOST'] . "/ModuloProjeto/logins/reset_password.php?token=" . $token;
                $mail->Body = "
                    <h3>Redefinição de Password</h3>
                    <p>Clique no link abaixo para redefinir a sua password:</p>
                    <p><a href='$reset_link'>Redefinir Password</a></p>
                    <p><small>Este link expira em 1 hora</small></p>
                ";

                if ($mail->send()) {
                    $message = "<div class='success'>Email enviado! Verifique a sua caixa de entrada.</div>";
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
    <title>BOOKhub | Recuperar password</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/login_style.css">
</head>
<body>
    <div class="logo">
        <img src="../assets/img/Logotipo_Bookhub.png" alt="Bookhub Logo" class="logo-img">
    </div>
    
    <div class="password-container">
        <div class="password-header">
            <h1>Recuperação de password</h1>
            <p>Digite o seu email para receber instruções de redefinição de password</p>
        </div>
        
        <?= $message ?>
        
        <form class="password-form" method="POST" action="">
            <div class="input-group">
                <i class="fas fa-envelope"></i>
                <input type="email" name="email" placeholder="Seu endereço de email" required>
            </div>
            <button type="submit" class="btn-submit">Enviar Link de Recuperação</button>
        </form>
        
        <div class="password-links">
            <p><a href="login.php">Voltar ao Login</a></p>
        </div>
    </div>
</body>
</html>