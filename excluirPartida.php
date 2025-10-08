<?php
include('../banco/conexao.php');
include('../includes/verificar_login.php');

$id = $_GET['id'] ?? null;

if (!$id) {
    header('Location: selecionarPartida.php');
    exit;
}

// Marcar como inativo ao invés de excluir
$stmt = $pdo->prepare("UPDATE tbConfiguracaoPartida SET ativo = 0 WHERE idConfiguracao = ?");
$stmt->execute([$id]);

header('Location: selecionarPartida.php?excluido=1');
exit;
?>
