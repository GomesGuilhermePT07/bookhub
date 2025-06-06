<?php
require_once 'config.php';
require_once '../../vendor/autoload.php';

// Token de segurança (adicione no config.php: define('API_TOKEN', 'seu_token_secreto'))
$data = json_decode(file_get_contents('php://input'), true);

// Verificação básica de segurança
if (!isset($data['token']) || $data['token'] !== API_TOKEN) {
    http_response_code(403);
    die("Acesso não autorizado.");
}

$userId = isset($data['user_id']) ? $data['user_id'] : null;
$reqIds = isset($data['req_ids']) ? $data['req_ids'] : array();

if (!$userId || empty($reqIds)) {
    http_response_code(400);
    die("Parâmetros inválidos.");
}

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
    $userStmt->execute([$userId]);
    $user = $userStmt->fetch();

    if (!$user) {
        throw new Exception("Utilizador não encontrado!");
    }

    // 3. Buscar detalhes dos livros (usando novo array para os parâmetros)
    $livrosStmt = $pdo->prepare("
        SELECT DISTINCT l.titulo 
        FROM requisicoes r
        JOIN livros l ON r.cod_isbn = l.cod_isbn
        WHERE r.id IN ($placeholders)
    ");
    $livrosStmt->execute($reqIds); // Usando o mesmo array
    $livros = $livrosStmt->fetchAll(PDO::FETCH_COLUMN);

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
    $mail->SMTPDebug = 2; // Ativa debug - REMOVER APÓS TESTES!
    $mail->Debugoutput = function($str, $level) {
        error_log("SMTP: $str");
    };
    
    $mail->setFrom(SMTP_USER, 'BOOKhub - Biblioteca');
    $mail->addAddress($user['email'], $user['nome_completo']);
    $mail->Subject = 'Seus livros estão prontos para levantar!';
    $mail->isHTML(true);
    
    $livrosTexto = "• " . implode("<br>• ", $livros);
    
    $mail->Body = "
        <html>
        <body>
            <h2>Olá, {$user['nome_completo']}!</h2>
            <p>Os seguintes livros estão prontos para serem levantados na biblioteca:</p>
            <p>$livrosTexto</p>
            <p><b>Local:</b> Biblioteca Central BOOKhub</p>
            <p><b>Horário:</b> 09:00 - 18:00 (Segunda a Sexta)</p>
            <p>Por favor, traga um documento de identificação quando vier recolher os livros.</p>
        </body>
        </html>
    ";

    if(!$mail->send()) {
        throw new Exception("Erro ao enviar email: " . $mail->ErrorInfo);
    }
    
    echo "E-mail enviado com sucesso para {$user['email']}!";
    
} catch (Exception $e) {
    http_response_code(500);
    error_log($e->getMessage());
    die("Erro: " . $e->getMessage());
}