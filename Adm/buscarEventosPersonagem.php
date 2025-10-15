<?php
include('../includes/verificar_login.php');
include('../banco/conexao.php');

$personagens = $_GET['personagens'] ?? '';
$personagensIds = array_filter(array_map('intval', explode(',', $personagens)));

foreach ($personagensIds as $id) {
    if (empty($id)) continue;
    
    $id = intval($id);
    if ($id <= 0) continue;
    // Buscar personagem no banco
    $stmt = $pdo->prepare("SELECT nomePersonagem, emojiPersonagem FROM tbpersonagem WHERE idPersonagem = ?");
    $stmt->execute([$id]);
    $personagemRow = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$personagemRow) continue;
    $personagem = [
        'nome' => strtoupper($personagemRow['nomePersonagem'] ?? 'PERSONAGEM'),
        'emoji' => $personagemRow['emojiPersonagem'] ?: '👤'
    ];
    
    echo "<div class='personagem-eventos-group'>";
    echo "<h4 class='personagem-titulo'>{$personagem['emoji']} {$personagem['nome']}</h4>";
    
    // Buscar eventos do personagem
    $stmt = $pdo->prepare("SELECT * FROM tbeventopersonagem WHERE idPersonagem = ?");
    $stmt->execute([$id]);
    $eventosPersonagem = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($eventosPersonagem)) {
        echo "<p class='sem-eventos'>NENHUM EVENTO ESPECÍFICO CADASTRADO PARA ESTE PERSONAGEM.</p>";
    } else {
        echo "<div class='eventos-personagem-grid'>";
        foreach ($eventosPersonagem as $evento) {
            $tipo = $evento['casas'] > 0 ? 'positivo' : 'negativo';
            echo "<div class='evento-personagem-card' data-id='{$evento['idEvento']}' data-personagem='{$id}'>";
            echo "<div class='evento-personagem-header'>";
            echo "<div class='evento-personagem-nome'>" . strtoupper($evento['nomeEvento']) . "</div>";
            echo "<div class='evento-personagem-casas {$tipo}'>" . ($evento['casas'] > 0 ? '+' : '') . $evento['casas'] . "</div>";
            echo "</div>";
            echo "<div class='evento-personagem-descricao'>" . strtoupper(substr($evento['descricaoEvento'], 0, 80)) . (strlen($evento['descricaoEvento']) > 80 ? '...' : '') . "</div>";
            echo "<div style=\"margin-top:10px; display:flex; justify-content:flex-end;\">";
            echo "<a class='btn-edit-evento-personagem' href='editarEventoPersonagem.php?id=" . intval($evento['idEvento']) . "' title='Editar evento' style='text-decoration:none;'>✏️ Editar</a>";
            echo "</div>";
            echo "</div>";
        }
        echo "</div>";
    }
    echo "</div>";
}
?>

