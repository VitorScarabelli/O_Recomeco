<?php
include('banco/conexao.php');
session_start();

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: selecionarPartida.php');
    exit;
}

// Buscar configuração no banco
$stmt = $pdo->prepare("SELECT * FROM tbConfiguracaoPartida WHERE idConfiguracao = ? AND ativo = 1");
$stmt->execute([$id]);
$configuracao = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$configuracao) {
    header('Location: selecionarPartida.php');
    exit;
}

// Carregar configuração na sessão
$_SESSION['configuracao_partida'] = [
    'id' => $configuracao['idConfiguracao'],
    'personagens' => json_decode($configuracao['personagens'], true),
    'temas' => json_decode($configuracao['temasSelecionados'], true),
    'eventos' => json_decode($configuracao['eventosSelecionados'], true),
    'eventosPersonagem' => json_decode($configuracao['eventosPersonagem'], true),
    'eventosCasas' => json_decode($configuracao['eventosCasas'], true),
    'data_configuracao' => $configuracao['dataCriacao']
];

// Redirecionar para o tabuleiro
header('Location: tabuleiro/tb.php');
exit;
?>
