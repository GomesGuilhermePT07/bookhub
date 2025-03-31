<?php

include 'config.php';

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

        // Enviar email ao usuário (simulação)
        $reset_link = "http://seusite.com/reset_password.php?token=" . $token;
        mail($email, "Redefinir sua senha", "Clique aqui para redefinir sua senha: $reset_link");

        echo "Um email foi enviado para redefinir sua senha.";
    } else {
        echo "Email não encontrado!";
    }
}

?>

<!DOCTYPE html>
<html lang="pt-PT">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial scale=1.0">
        <title>Esqueci-me da Password</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            body {
                font-family: Arial, Helvetica, sans-serif;
                background-image: linear-gradient(30deg, #e52b4b, #f3a69e, #83d0f0, #426877);
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                color: #ffffff;
            }

            .container {
                background-color: rgba(0, 0, 0, 0.8);
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                width: 90%;
                max-width: 400px;
            }

            .inputs {
                font-size: 15px;
                padding: 10px;
                border-radius: 5px;
                background-color: #181818;
                border: 2px solid #181818;
                color: #ffffff;
                width: 100%;
                margin-bottom: 15px;
                box-sizing: border-box;
            }

            .inputs:focus {
                border-color: #426877;
                outline: none;
            }

            button {
                background-color: dodgerblue;
                border: none;
                padding: 10px;
                width: 100%;
                border-radius: 5px;
                color: #fff;
                font-size: 15px;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            button:hover {
                background-color: deepskyblue;
            }

            .message {
                margin-top: 15px;
                font-size: 14px;
                color: #83d0f0;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h2>Recuperar Password</h2>
            <p>Insira seu email para receber instruções de redefinição de password.</p>
            <input type="email" class="inputs" id="email" placeholder="Email" required>
            <button onclick="sendResetLink()">Enviar</button>
            <div id="message" class="message"></div>
        </div>

        <script>
            function sendResetLink() {
                const email = document.getElementById('email').value;
                const message = document.getElementById('message');

                if (email) {
                    message.textContent = 'Um link de redefinição foi enviado para ' + email + '.';
                } else {
                    message.textContent = 'Por favor, insira um email válido.';
                }
            }
        </script>
    </body>
</html>
