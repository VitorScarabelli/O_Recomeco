<?php
include('../includes/verificar_login.php');
include('../banco/conexao.php');

$query = $_GET['q'] ?? '';

if (strlen($query) < 1) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT DISTINCT temaAula FROM tbevento WHERE temaAula LIKE ? AND temaAula IS NOT NULL AND temaAula != '' ORDER BY temaAula LIMIT 10");
$stmt->execute(["%$query%"]);

$temas = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $temas[] = $row['temaAula'];
}

echo json_encode($temas);
?>
