<?php
require_once 'assets/php/config.php';
$isbn = $_GET['isbn'] ?? '';

// Buscar detalhes do livro no banco de dados
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
$stmt = $conn->prepare("SELECT * FROM livros WHERE cod_isbn = ?");
$stmt->bind_param("s", $isbn);
$stmt->execute();
$livro = $stmt->get_result()->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= $livro['titulo'] ?? 'Livro não encontrado' ?></title>
    <!-- Inclua seus estilos aqui -->
</head>
<body>
    <?php if ($livro): ?>
        <div class="livro-detalhes">
            <img src="<?= obterCapa($livro['cod_isbn']) ?>" alt="Capa do livro">
            <h1><?= $livro['titulo'] ?></h1>
            <p>Autor: <?= $livro['autor'] ?></p>
            <p>Descrição: <?= $livro['descricao'] ?></p>
            <!-- Outros detalhes do livro -->
        </div>
    <?php else: ?>
        <p>Livro não encontrado!</p>
    <?php endif; ?>
</body>
</html>