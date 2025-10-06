<?php
include('../banco/conexao.php');

if (!isset($pdo)) {
    die("Erro: conexão com o banco não estabelecida.");
} elseif ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');
    $modi = trim($_POST['modi'] ?? '');
    $quantCasa = $_POST['quantCasas'] ?? '';
    $modificador = 'Avance x casas';

    if ($nome === '' || $descricao === '' || $tipo === '' || $modi === '' || $quantCasa === '') {
        header('Location: ../index/index.php?error=1');
        exit;
    }

    if (!is_numeric($quantCasa)) {
        header('Location: ../index/index.php?error=2');
        exit;
    }

    $quantCasa = (int)$quantCasa;

    if ($quantCasa < 2 || $quantCasa > 8) {
        header('Location: ../index/index.php?error=3');
        exit;
    }

    // 🔹 Se o evento for RUIM, transforma o número de casas em negativo
    if (strtolower($modi) === 'ruim') {
        $quantCasa = -abs($quantCasa);
    }

    $stmt = $pdo->prepare("INSERT INTO tbevento (nomeEvento, descricaoEvento, modificadorEvento, dificuldadeEvento, impactoEvento, casaEvento) 
        VALUES (:nome, :descricao, :modificador, :tipo, :modi, :quantCasa)");
    $executou = $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':modificador' => $modificador,
        ':tipo' => $tipo,
        ':modi' => $modi,
        ':quantCasa' => $quantCasa
    ]);

    if ($executou) {
        header('Location: ../index/index.php?success=1');
        exit;
    } else {
        header('Location: ../index/index.php?error=1');
        exit;
    }

} else {
    header('Location: ../index/index.php');
    exit;
}
?>
