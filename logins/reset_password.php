<?php
include '../assets/php/config.php';

$error = '';
$success = '';

try {
    if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['token'])) {
        $token = $_GET['token'];
        
        $query = $pdo->prepare("SELECT email FROM password_resets 
                                WHERE token = ? 
                                AND expira_em > UTC_TIMESTAMP()"); // Usar UTC
        $query->execute([$token]);
        $reset = $query->fetch();

        if (!$reset) {
            $error = "Link inválido ou expirado!";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $token = $_POST['token'];
        $nova_password = $_POST['nova_password'];
        
        if (strlen($nova_password) < 8) {
            $error = "Senha deve ter mínimo 8 caracteres";
        } else {
            $query = $pdo->prepare("SELECT email FROM password_resets WHERE token = ? AND expira_em > NOW()");
            $query->execute([$token]);
            $reset = $query->fetch();

            if ($reset) {
                $hash = password_hash($nova_password, PASSWORD_DEFAULT);
                
                // Atualizar senha
                $query = $pdo->prepare("UPDATE utilizadores SET password = ? WHERE email = ?");
                $query->execute([$hash, $reset['email']]);
                
                // Remover token
                $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$reset['email']]);
                
                $success = "Senha alterada com sucesso!";
            } else {
                $error = "Token inválido!";
            }
        }
    }
} catch (PDOException $e) {
    $error = "Erro de base de dados: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Nova Password</title>
    <link rel="stylesheet" href="../assets/css/login_style.css">
</head>
<body>
    <div class="login-container">
        <h1>Definir Nova Password</h1>
        <?php if ($error): ?>
            <div class="error"><?= $error ?></div>
        <?php elseif ($success): ?>
            <div class="success"><?= $success ?></div>
        <?php else: ?>
            <form method="POST">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars(isset($_GET['token']) ? $_GET['token'] : '', ENT_QUOTES, 'UTF-8'); ?>">
                <div class="input-container">
                    <i class="fa fa-lock"></i>
                    <input type="password" name="nova_password" placeholder="Nova password" required minlength="8">
                </div>
                <button type="submit">Alterar Password</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>