<?php 
include('../includes/verificar_login.php');
include('../banco/conexao.php');



// Verificar se há configuração salva
if (!isset($_SESSION['configuracao_partida'])) {
    header('Location: configurarPartida.php');
    exit;
}

$configuracao = $_SESSION['configuracao_partida'];
$personagens = $configuracao['personagens'];
$temas = $configuracao['temas'];
$eventos = $configuracao['eventos'];
$eventosPersonagem = $configuracao['eventosPersonagem'] ?? [];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualizar Partida - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/visualizarPartidaa.css">
</head>
<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">🎯 VISUALIZAR PARTIDA</h1>
            <p class="admin-subtitle">CONFIRA A CONFIGURAÇÃO ANTES DE INICIAR A PARTIDA</p>
        </div>

        <div class="config-info">
            <strong>🏷️ TÍTULO DA PARTIDA:</strong> <?php echo $configuracao['titulo']; ?><br>
            <strong>📅 CONFIGURADO EM:</strong> <?php echo date('d/m/Y H:i', strtotime($configuracao['data_configuracao'])); ?><br>
            <strong>🎮 CÓDIGO DA PARTIDA: </strong><?php echo $configuracao['codigoPartida']?></span><br>
            <strong>👥 PERSONAGENS:</strong> <?php echo count($personagens); ?> SELECIONADOS<br>
            <strong>🎲 EVENTOS:</strong> <?php echo count($eventos); ?> SELECIONADOS<br>
            <strong>📚 TEMAS:</strong> <?php echo count($temas); ?> TIPOS SELECIONADOS<br>
        </div>
        
        <div class="config-section">
            <h2 class="section-title">👥 PERSONAGENS SELECIONADOS</h2>
            <div class="personagens-grid">
                <?php foreach ($personagens as $personagem): ?>
                <div class="personagem-card">
                    <div class="personagem-img"><?php echo $personagem['emoji']; ?></div>
                    <div class="personagem-nome"><?php echo strtoupper($personagem['nome']); ?></div>
                    <div class="personagem-desc">
                        <?php
                        $descricoes = [
                            'IDOSO' => 'UMA PESSOA COM MUITA EXPERIÊNCIA DE VIDA, MAS COM LIMITAÇÕES FÍSICAS.',
                            'CEGO' => 'A VIDA TE DEU UM DESAFIO A MAIS, MAS VOCÊ NÃO ABAIXOU SUA CABEÇA.',
                            'MULHER NEGRA' => 'UMA MULHER QUE TEM ORGULHO DA SUA COR, ALGUÉM QUE QUER DERRUBAR O PRECONCEITO.',
                            'RETIRANTE' => 'UM VIAJANTE HUMILDE QUE DEIXOU SUA TERRA NATAL EM BUSCA DE NOVAS OPORTUNIDADES.',
                            'MULHER TRANS' => 'UMA MULHER QUE TEVE A CORAGEM DE SER QUEM REALMENTE É.',
                            'UMBANDISTA' => 'ALGUÉM QUE SEGUE A RELIGIÃO DE UMBANDA, BUSCANDO SEMPRE O EQUILÍBRIO E A PAZ.'
                        ];
                        echo $descricoes[$personagem['nome']] ?? 'Personagem selecionado';
                        ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="eventos-section">
            <h2 class="section-title">🎲 EVENTOS SELECIONADOS</h2>
            <div class="eventos-grid">
                <?php
                if (!empty($eventos)) {
                    $placeholders = str_repeat('?,', count($eventos) - 1) . '?';
                    $stmt = $pdo->prepare("SELECT * FROM tbevento WHERE idEvento IN ($placeholders) ORDER BY nomeEvento");
                    $stmt->execute($eventos);
                    
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tipo = $row['casaEvento'] > 0 ? 'positivo' : 'negativo';
                        $casas = $row['casaEvento'];
                        
                        echo "<div class='evento-card'>";
                        echo "<div class='evento-header'>";
                        echo "<div class='evento-nome'>{$row['nomeEvento']}</div>";
                        echo "<div class='evento-casas {$tipo}'>" . ($casas > 0 ? '+' : '') . $casas . "</div>";
                        echo "</div>";
                        echo "<div class='evento-descricao'>" . substr($row['descricaoEvento'], 0, 100) . (strlen($row['descricaoEvento']) > 100 ? '...' : '') . "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<div class='alert alert-info'>Nenhum evento específico selecionado. Apenas eventos dos personagens serão incluídos.</div>";
                }
                ?>
            </div>
        </div>
        
        
        <div class="config-section">
            <h2 class="section-title">🚀 AÇÕES</h2>
            <div class="action-buttons">
                <a href="../tabuleiro/tb.php" class="btn btn-play">🎮 TESTAR PARTIDA</a>
                <a href="../index.php" class="btn btn-back">🎮 VOLTAR PARA TELA INICIAL</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
