<?php
session_start();
require_once __DIR__ . './assets/php/config.php'; // Caminho absoluto mais seguro
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Debug session
error_log("SESSION: " . print_r($_SESSION, true));

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true || !isset($_SESSION['id'])) {
    error_log("Usuário não logado - Redirecionando");
    header("Location: ./ModuloProjeto/logins/login.php");
    exit;
}

try {
    $userId = $_SESSION['id'];
    error_log("ID Usuário: " . $userId);

    // Buscar informações do usuário
    $stmtUser = $pdo->prepare("SELECT nome_completo, email FROM utilizadores WHERE id = ?");
    $stmtUser->execute([$userId]);
    $user = $stmtUser->fetch();
    
    if (!$user) {
        error_log("Usuário não encontrado ID: " . $userId);
        throw new Exception("Usuário não encontrado");
    }

    // Buscar itens do carrinho
    $stmtCarrinho = $pdo->prepare("
        SELECT l.titulo, l.autor, l.cod_isbn 
        FROM carrinho c 
        JOIN livros l ON c.cod_isbn = l.cod_isbn 
        WHERE c.id_utilizador = ?
    ");
    $stmtCarrinho->execute([$userId]);
    $itens = $stmtCarrinho->fetchAll();

    error_log("Itens carrinho: " . print_r($itens, true));

    if (empty($itens)) {
        error_log("Carrinho vazio para usuário: " . $userId);
        $_SESSION['error'] = "Carrinho vazio!";
        header("Location: ./cart.php");
        exit;
    }

    // Construir email
    require_once __DIR__ . './vendor/autoload.php'; // Certifique-se do caminho do autoload

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);
    
    // Config SMTP Debug
    $mail->SMTPDebug = 3; // Ativa debug detalhado
    $mail->Debugoutput = function($str, $level) {
        error_log("SMTP Debug: $str");
    };

    try {
        // Configuração SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'bookhub.adm1@gmail.com'; // Seu email
        $mail->Password = 'bookhubAdministrador1!'; // Sua senha de app
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 587;

        // Destinatários
        $mail->setFrom('bookhub.adm1@gmail.com', 'BOOKhub');
        $mail->addAddress('bookhub.adm1@gmail.com'); // Email admin

        // Conteúdo
        $mail->Subject = 'Nova Requisição de Livros - ' . date('d/m/Y H:i');
        $mail->isHTML(true);
        
        $emailBody = "<h3>Nova Requisição</h3>";
        $emailBody .= "<p><strong>Usuário:</strong> {$user['nome_completo']} ({$user['email']})</p>";
        $emailBody .= "<h4>Livros:</h4><ul>";
        
        foreach ($itens as $item) {
            $emailBody .= "<li>{$item['titulo']} - {$item['autor']} (ISBN: {$item['cod_isbn']})</li>";
        }
        
        $emailBody .= "</ul>";
        $mail->Body = $emailBody;

        // Envio
        if (!$mail->send()) {
            error_log("Erro no envio: " . $mail->ErrorInfo);
            throw new Exception("Erro ao enviar email: " . $mail->ErrorInfo);
        }

        // Limpar carrinho somente após envio bem sucedido
        $pdo->beginTransaction();
        $del = $pdo->prepare("DELETE FROM carrinho WHERE id_utilizador = ?");
        $del->execute([$userId]);
        $pdo->commit();

        $_SESSION['success'] = "Requisição enviada com sucesso!";
        error_log("Requisição processada com sucesso");

    } catch (Exception $e) {
        $pdo->rollBack();
        error_log("Erro geral: " . $e->getMessage());
        $_SESSION['error'] = "Erro: " . $e->getMessage();
    }

} catch (PDOException $e) {
    error_log("Erro PDO: " . $e->getMessage());
    $_SESSION['error'] = "Erro no banco de dados";
} catch (Exception $e) {
    error_log("Erro Geral: " . $e->getMessage());
    $_SESSION['error'] = $e->getMessage();
}

header("Location: ./cart.php");
exit;