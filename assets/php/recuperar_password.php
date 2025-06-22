<?php
include('../assets/php/config.php'); // Caminho corrigido

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    try {
        $stmt = $pdo->prepare("SELECT id FROM utilizadores WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($stmt->rowCount() > 0) {
            // Gerar token
            $token = bin2hex(random_bytes(32));
            $expira_em = date("Y-m-d H:i:s", strtotime("+1 hour"));
            
            // Inserir token na base de dados
            $stmt = $pdo->prepare("REPLACE INTO password_resets (email, token, expira_em) VALUES (?, ?, ?)");
            $stmt->execute([$email, $token, $expira_em]);
            
            echo "Um link de redefinição foi enviado para " . htmlspecialchars($email);
        } else {
            echo "O email não foi encontrado.";
        }
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>