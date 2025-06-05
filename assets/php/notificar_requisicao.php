<?php
session_start();
require_once 'config.php';
require_once '../../vendor/autoload.php';

// Verificar se é uma requisição autorizada (opcional)
// if (!isset($_SESSION['admin_id'])) { ... }

if (isset($_GET['id']) && isset($_GET['id_requisicao'])) {
    $userId = $_GET['id'];
    $reqIds = explode(',', $_GET['id_requisicao']);

    try {
        // Buscar dados do utilizador
        $stmt = $pdo->prepare("SELECT nome_completo, email FROM utilizadores WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        // Buscar detalhes dos livros
        $placeholders = str_repeat('?,', count($reqIds) - 1) . '?';
        $stmt = $pdo->prepare("
            SELECT l.titulo 
            FROM requisicoes r
            JOIN livros l ON r.cod_isbn = l.cod_isbn
            WHERE r.id IN ($placeholders)
        ");
        $stmt->execute($reqIds);
        $livros = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Preparar mensagem
        $livrosLista = implode("<br>• ", $livros);
        $livrosTexto = "• " . $livrosLista;

        // Enviar e-mail para o utilizador
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
        $mail->Subject = 'Seus livros estão prontos para levantar!';
        $mail->isHTML(true);
        
        $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Gill Sans MT; }
                </style>
            </head>
            <body>
                <h2>Olá, {$user['nome_completo']}!</h2>
                <p>Os seguintes livros estão prontos para serem levantados:</p>
                <p>$livrosTexto</p>
                <p>Por favor, dirija-se à biblioteca para recolhê-los.</p>
                <p><b>Prazo de levantamento:</b> 5 dias úteis</p>
            </body>
            </html>
        ";

        $mail->send();
        echo "E-mail enviado com sucesso para {$user['email']}!";
    } catch (Exception $e) {
        die("Erro: " . $e->getMessage());
    }
} else {
    die("Parâmetros inválidos.");
}