<?php
session_start();
require_once 'config.php';
require_once '../../vendor/autoload.php';

// Verificar se é admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    die("Acesso negado. Apenas administradores podem executar esta ação.");
}

// Obter parâmetros da URL
$userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
$reqIds = isset($_GET['req_ids']) ? explode(',', $_GET['req_ids']) : array();

if (!$userId || empty($reqIds)) {
    die("Parâmetros inválidos.");
}

// Se o formulário foi submetido, enviar o email
$emailEnviado = false;
$erro = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // 1. Atualizar status das requisições
        $placeholders = str_repeat('?,', count($reqIds) - 1) . '?';
        
        $updateStmt = $pdo->prepare("
            UPDATE requisicoes 
            SET status = 'pronto_para_levantar', data_conclusao = NOW() 
            WHERE id IN ($placeholders)
        ");
        $updateStmt->execute($reqIds);

        // 2. Buscar dados do utilizador
        $userStmt = $pdo->prepare("SELECT nome_completo, email FROM utilizadores WHERE id = ?");
        $userStmt->execute(array($userId));
        $user = $userStmt->fetch();

        if (!$user) {
            throw new Exception("Utilizador não encontrado!");
        }

        // 3. Buscar detalhes dos livros
        $livrosStmt = $pdo->prepare("
            SELECT DISTINCT l.titulo 
            FROM requisicoes r
            JOIN livros l ON r.cod_isbn = l.cod_isbn
            WHERE r.id IN ($placeholders)
        ");
        $livrosStmt->execute($reqIds);
        $livros = $livrosStmt->fetchAll(PDO::FETCH_COLUMN, 0);

        // 4. Enviar e-mail para o utilizador
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASS;
        $mail->SMTPSecure = 'tls';
        $mail->Port = SMTP_PORT;
        $mail->CharSet = 'UTF-8';
        
        $mail->setFrom(SMTP_USER, 'BOOKhub - Biblioteca');
        $mail->addAddress($user['email'], $user['nome_completo']);
        $mail->Subject = 'Os seus livros estão prontos para levantar!';
        $mail->isHTML(true);
        
        $livrosTexto = "• " . implode("<br>• ", $livros);
        
        $mail->Body = "
            <html>
            <body>
                <h2>Olá, {$user['nome_completo']}!</h2>
                <p>Os seguintes livros estão prontos para serem levantados na biblioteca:</p>
                <p>$livrosTexto</p>
                <p><b>Local:</b> Biblioteca Escolar</p>
                <p><b>Horário:</b> 09:00 - 18:00 (Segunda a Sexta)</p>
                <p>Por favor, traga um documento de identificação quando vier recolher os livros.</p>
            </body>
            </html>
        ";

        // Ignorar problemas de certificado SSL
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        if($mail->send()) {
            $emailEnviado = true;
            
            // Redirecionar de volta para a gestão de requisições
            header("Location: ../../gerir-requisicoes.php?success=5");
            exit;
        } else {
            throw new Exception("Erro ao enviar email: " . $mail->ErrorInfo);
        }
        
    } catch (Exception $e) {
        $erro = $e->getMessage();
    }
}

// Se não foi submetido, mostrar o formulário de confirmação
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificar Utilizador</title>
    <style>
        body {
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, sans-serif;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
        }
        .btn {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn:hover {
            background-color: #218838;
        }
        .error {
            color: #dc3545;
            padding: 10px;
            background: #f8d7da;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Notificar Utilizador</h1>
        
        <?php if ($erro): ?>
            <div class="error">
                <p><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
            </div>
        <?php endif; ?>
        
        <p>Você está prestes a notificar o utilizador que os livros estão prontos para levantamento.</p>
        
        <form method="POST">
            <button type="submit" class="btn">Confirmar e Enviar Notificação</button>
        </form>
    </div>
</body>
</html>