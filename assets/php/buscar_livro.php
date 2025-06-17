<?php 
require_once 'config.php';

$isbn = isset($_GET['isbn']) ? trim($_GET['isbn']) : '';

if (empty($isbn)) {
    http_response_code(400);
    echo json_encode(['error' => 'ISBN não especificado']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT titulo, autor FROM livros WHERE cod_isbn = ?");
    $stmt->execute([$isbn]);
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($livro) {
        header('Content-Type: application/json');
        echo json_encode([
            'titulo' => $livro['titulo'],
            'autor' => $livro['autor']
        ]);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Livro não encontrado']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Erro no servidor: ' . $e->getMessage()]);
}