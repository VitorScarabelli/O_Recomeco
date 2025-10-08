<?php
include('./includes/verificar_login.php');
include('../banco/conexao.php');

$query = $_GET['q'] ?? '';

if (strlen($query) < 2) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT DISTINCT nomeEvento FROM tbevento WHERE nomeEvento LIKE ? ORDER BY nomeEvento LIMIT 10");
$stmt->execute(["%$query%"]);

$eventos = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $eventos[] = $row['nomeEvento'];
}

echo json_encode($eventos);
?>
