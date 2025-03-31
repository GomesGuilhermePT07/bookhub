<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php'; // Certifique-se de que o PHPMailer foi instalado corretamente
include '../assets/php/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Verifica se o email existe
    $query = $conn->prepare("SELECT id FROM utilizadores WHERE email = ?");
    $query->execute([$email]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Gera um token único
        $token = md5(uniqid(mt_rand(), true));
        $expira_em = date("Y-m-d H:i:s", strtotime("+1 hour"));

        // Insere ou atualiza o token na base de dados
        $query = $conn->prepare("INSERT INTO password_resets (email, token, expira_em) 
                                 VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE token = ?, expira_em = ?");
        $query->execute([$email, $token, $expira_em, $token, $expira_em]);

        // Configurar o PHPMailer para envio
        $mail = new PHPMailer(true);
        try {
            // Configurações do servidor SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Servidor SMTP (exemplo com Gmail)
            $mail->SMTPAuth = true;
            $mail->Username = 'seuemail@gmail.com'; // SEU EMAIL SMTP
            $mail->Password = 'suasenha'; // SUA SENHA SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Configuração do email
            $mail->setFrom('seuemail@gmail.com', 'Suporte do BookHubJB');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = "Redefinição de Senha";
            $reset_link = "http://localhost:8080/ModuloProjeto/logins/reset_password.php?token=" . $token;
            $mail->Body = "Clique no link para redefinir sua senha: <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            echo "Um email foi enviado para redefinir sua senha.";
        } catch (Exception $e) {
            echo "Erro ao enviar email: {$mail->ErrorInfo}";
        }
    } else {
        echo "Email não encontrado!";
    }
}
?>
