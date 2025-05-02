<?php
// session_start();
require_once './assets/php/config.php';
require_once './assets/php/check_login.php';


// Obter dados do utilizador
$dados = [];
$erro = '';

try {
    $stmt = $pdo->prepare("SELECT nome_completo, email, genero, admin FROM utilizadores WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$dados) {
        $erro = "Utilizador não encontrado!";
    }
} catch(PDOException $e) {
    $erro = "Erro ao carregar dados: " . $e->getMessage();
}

$cartCount = 0;
    if (isset($_SESSION['id'])) {
        $conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
        $stmt = $conn->prepare("SELECT SUM(quantidade) AS total FROM carrinho WHERE id_utilizador = ?");
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $cartCount = isset($row['total']) ? $row['total'] : 0;
        $stmt->close();
        $conn->close();
    }
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKhub | Minha Conta</title>
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/index_style.css">
    <style>
        .conta-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .dados-lista {
            margin: 2rem 0;
        }

        .dado-item {
            display: flex;
            justify-content: space-between;
            padding: 1rem;
            border-bottom: 1px solid #eee;
        }

        .dado-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .dado-valor {
            color: #7f8c8d;
        }

        .admin-tag {
            background: #e74c3c;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 0.85em;
            margin-left: 1rem;
        }
    </style>
</head>
<body>
    <!-- Manter o header original -->
    <header>
        <!-- ... seu header existente ... -->
    </header>

    <main class="conta-container">
        <h2>Minha Conta 
            <?php if($dados['admin']): ?>
                <span class="admin-tag">ADMINISTRADOR</span>
            <?php endif; ?>
        </h2>
        
        <?php if ($erro): ?>
            <p style="color: var(--color-red1); margin: 1rem 0;"><?= $erro ?></p>
        <?php else: ?>
            <div class="dados-lista">
                <div class="dado-item">
                    <span class="dado-label">Nome Completo:</span>
                    <span class="dado-valor"><?= htmlspecialchars($dados['nome_completo']) ?></span>
                </div>
                
                <div class="dado-item">
                    <span class="dado-label">Email:</span>
                    <span class="dado-valor"><?= htmlspecialchars($dados['email']) ?></span>
                </div>
                
                <div class="dado-item">
                    <span class="dado-label">Gênero:</span>
                    <span class="dado-valor">
                        <?php 
                        switch($dados['genero']) {
                            case 'm': echo 'Masculino'; break;
                            case 'f': echo 'Feminino'; break;
                            case 'o': echo 'Outro'; break;
                            default: echo 'Não especificado';
                        }
                        ?>
                    </span>
                </div>
                
                <div class="dado-item">
                    <span class="dado-label">Tipo de Conta:</span>
                    <span class="dado-valor">
                        <?= ($dados['admin'] ? 'Administrador' : 'Utilizador Regular') ?>
                    </span>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2025 BOOKhub. Todos os direitos reservados.</p>
    </footer>
</body>
</html>