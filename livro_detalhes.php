<?php
require_once 'assets/php/config.php';
$isbn = isset($_GET['isbn']) ? $_GET['isbn'] : ''; // Corrigido para versões antigas do PHP

// Buscar detalhes do livro no banco de dados
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
$stmt = $conn->prepare("SELECT * FROM livros WHERE cod_isbn = ?");
$stmt->bind_param("s", $isbn);
$stmt->execute();
$result = $stmt->get_result();
$livro = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Função auxiliar para buscar capa (adicione esta função ou inclua de outro arquivo)
function obterCapa($isbn) {
    $url = "https://www.googleapis.com/books/v1/volumes?q=isbn:$isbn";
    $response = json_decode(file_get_contents($url), true);
    
    if (!empty($response['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
        return $response['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
    }
    return 'assets/img/capa-padrao.jpg'; // Altere para o caminho padrão desejado
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOOKhub | <?php echo isset($livro['titulo']) ? $livro['titulo'] : 'Livro não encontrado'; ?></title>
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/livro_detalhes.css">
    <link rel="stylesheet" href="../ModuloProjeto/assets/css/index_style.css">
</head>
<body>
    <?php if ($livro): ?>
        <div class="livro-detalhes">
            <img src="<?php echo obterCapa($livro['cod_isbn']); ?>" alt="Capa do livro">
            <h1><?php echo $livro['titulo']; ?></h1>
            <p>Autor: <?php echo $livro['autor']; ?></p>
            <p>Descrição: <?php echo $livro['descricao']; ?></p>
        </div>
    <?php else: ?>
        <p>Livro não encontrado!</p>
    <?php endif; ?>
</body>
</html>