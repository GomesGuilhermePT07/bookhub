<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] != 1) {
    header("Location: login.html");
    exit();
}

// Conexão com o banco de dados
include 'config.php'; // Inclua seu arquivo de configuração

// Buscar atividades
$sql = "SELECT atividade, COUNT(*) as total FROM atividades GROUP BY atividade";
$result = $conn->query($sql);

$atividades = [];
$totais = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $atividades[] = $row['atividade'];
        $totais[] = $row['total'];
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
    <meta charset="UTF-8">
    <title>Painel do Administrador</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h1>Painel do Administrador</h1>
    <canvas id="myChart"></canvas>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const myChart = new Chart(ctx, {
            type: 'bar', // ou 'pie' para gráfico circular
            data: {
                labels: <?php echo json_encode($atividades); ?>,
                datasets: [{
                    label: 'Atividades dos Usuários',
                    data: <?php echo json_encode($totais); ?>,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>