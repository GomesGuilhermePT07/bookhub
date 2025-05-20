<?php
session_start();
require_once 'config.php';

$idRequisicao = $_GET['id'];

try {
    // Buscar dados da requisição
    $stmt = $pdo->prepare("
        SELECT u.nome AS usuario, l.titulo, l.autor, l.cod_isbn 
        FROM requisicoes r 
        JOIN utilizadores u ON u.id = r.id_utilizador 
        JOIN livros l ON l.cod_isbn = r.cod_isbn 
        WHERE r.id = ?
    ");
    $stmt->execute([$idRequisicao]);
    $dados = $stmt->fetch();

    // Enviar email para o admin
    $linkConclusao = "http://localhost:8080/ModuloProjeto/assets/php/concluir_devolucao.php?id=" . $idRequisicao;
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    // ... (configuração SMTP)
    $mail->setFrom('bookhub.adm1@gmail.com', 'BOOKhub');
    $mail->addAddress('bookhub.adm1@gmail.com');
    $mail->Subject = 'Devolução Solicitada: ' . $dados['titulo'];
    $mail->Body = "
        <h3>Devolução Solicitada:</h3>
        <p><strong>Utilizador:</strong> {$dados['usuario']}</p>
        <p><strong>Livro:</strong> {$dados['titulo']} (ISBN: {$dados['cod_isbn']})</p>
        <a href='$linkConclusao' style='background: #4CAF50; color: white; padding: 10px; text-decoration: none;'>Concluir Devolução</a>
    ";
    $mail->send();

    header("Location: confirmacao_devolucao.php");
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}
?>