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

        $pdo->beginTransaction();

        // Atualizar status das requisicoes para "pronto_para_levantar"
        $placeholders = str_repeat('?,', count($id_requisicao) - 1) . '?';
        $updateStmt = $pdo->prepare("
            UPDATE requisicoes
            SET status = 'pronto_para_levantar', data_conclusao = NOW()
            WHERE id IN ($placeholders)
        ");
        $updateStmt->execute($id_requisicao);

        // Buscar dados do utilizador
        $stmt = $pdo->prepare("SELECT nome_completo, email FROM utilizadores WHERE id = ?");
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!user) {
            throw new Exception("Utilizador não encontrado!");
        }

        // Buscar detalhes dos livros
        // $placeholders = str_repeat('?,', count($reqIds) - 1) . '?';
        // $stmt = $pdo->prepare("
        //     SELECT l.titulo 
        //     FROM requisicoes r
        //     JOIN livros l ON r.cod_isbn = l.cod_isbn
        //     WHERE r.id IN ($placeholders)
        // ");
        // $stmt->execute($reqIds);
        // $livros = $stmt->fetchAll(PDO::FETCH_COLUMN);

        $livrosStmt = $pdo->prepare("
            SELECT DISTINCT l.titulo
            FROM requisicoes r
            JOIN livros l ON r.cod_isbn = l.cod_isbn
            WHERE r.id IN ($placeholders)
        ");
        $livrosStmt->execute($id_requisicao);
        $livros = $livrosStmt->fetchAll(PDO::FETCH_COLUMN, 0);

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
        $mail->Subject = 'Os seus livros estão prontos para levantar!';
        $mail->isHTML(true);
        
        $mail->Body = "
            <html>
            <head>
                <style>
                    body { font-family: Gill Sans MT; }
                    h2 { color: #28a745; }
                </style>
            </head>
            <body>
                <h2>Olá, {$user['nome_completo']}!</h2>
                <p>Os seguintes livros estão prontos para serem levantados na biblioteca:</p>
                <p>$livrosTexto</p>
                <p>Por favor, dirija-se à biblioteca para recolhê-los.</p>
                <p>Apresente este email para a sua identificação ser verificada.</p>
                <p>Atenciosamente, <br>Equipa BOOKhub</p>
            </body>
            </html>
        ";

        $mail->send();
        $pdo->commit();

        // Redirecionar com mensagem de sucesso
        $_SESSION['admin_message'] = "Utilizador notificado com sucesso!";
        header("Location: ../../gerir-requisicoes.php?success=5");
        // echo "E-mail enviado com sucesso para {$user['email']}!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $_SESSION['admin_error'] = "Erro ao notificar utilizador: " . $e->getMessage();
        header("Location: ../../gerir-requisicoes.php?error=1");
        exit;
        // die("Erro: " . $e->getMessage());
    }
} else {
    $_SESSION['admin_error'] = "Parâmetros inválidos.";
    header("Location: ../../gerir-requisicoes.php?error=2");
    exit;
    // die("Parâmetros inválidos.");
}