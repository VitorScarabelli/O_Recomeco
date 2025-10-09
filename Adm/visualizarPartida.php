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
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }
        
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        
        .admin-header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        
        .admin-title {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .admin-subtitle {
            color: #7f8c8d;
            font-size: 1.2rem;
        }
        
        .config-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
            margin-top: 30px;
        }
        
        .section-title {
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .personagens-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .personagem-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 3px solid #28a745;
        }
        
        .personagem-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }
        
        .personagem-nome {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        
        .personagem-desc {
            font-size: 0.9rem;
            color: #6c757d;
            line-height: 1.4;
        }
        
        .eventos-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .eventos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .evento-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border: 3px solid #28a745;
        }
        
        .evento-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .evento-nome {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1rem;
        }
        
        .evento-casas {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
        }
        
        .evento-casas.positivo {
            background: #d4edda;
            color: #155724;
        }
        
        .evento-casas.negativo {
            background: #f8d7da;
            color: #721c24;
        }
        
        .evento-descricao {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 15px;
        }
        
        .evento-dificuldade {
            font-size: 0.8rem;
            padding: 3px 8px;
            border-radius: 10px;
            font-weight: bold;
        }
        
        .dificuldade-facil { background: #d4edda; color: #155724; }
        .dificuldade-medio { background: #fff3cd; color: #856404; }
        .dificuldade-dificil { background: #f8d7da; color: #721c24; }
        .dificuldade-extremo { background: #f5c6cb; color: #721c24; }
        
        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(52, 73, 94, 0.9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        
        .back-btn:hover {
            background: rgba(52, 73, 94, 1);
            color: white;
            text-decoration: none;
        }
        
        .btn-iniciar {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-iniciar:hover {
            transform: translateY(-2px);
        }
        
        .btn-reconfigurar {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }
        
        .btn-reconfigurar:hover {
            transform: translateY(-2px);
        }
        
        .config-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-item {
            text-align: center;
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 10px;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        .action-buttons {
            display: flex;
            flex-direction: row;
            gap: 15px;
            margin-top: 20px;
        }

        .btn-save{
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
            width: 33.3%;
            margin-top: 20px;
        }
        .btn-play{
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
            width: 33.3%;
            margin-top: 20px;
        }

        .btn-play {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .btn-play:hover {
            background: linear-gradient(135deg, #218838, #1ea085);
            color: white;
            text-decoration: none;
        }
        .btn-edit{
            background: linear-gradient(135deg, #ffb618ff 0%, #c9972bff 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
            width: 33.3%;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">🎯 VISUALIZAR PARTIDA</h1>
            <p class="admin-subtitle">CONFIRA A CONFIGURAÇÃO ANTES DE INICIAR A PARTIDA</p>
        </div>
        
        <div class="config-info">
        <strong>📅 CONFIGURADO EM:</strong> <?php echo date('d/m/Y H:i', strtotime($configuracao['data_configuracao'])); ?><br>
        <strong>👥 PERSONAGENS:</strong> <?php echo count($personagens); ?> SELECIONADOS<br>
        <strong>🎲 EVENTOS:</strong> <?php echo count($eventos); ?> SELECIONADOS<br>
        <strong>📚 TEMAS:</strong> <?php echo count($temas); ?> TIPOS SELECIONADOS<br>
        <strong>🎯 EVENTOS DOS PERSONAGENS:</strong> <?php echo count($eventosPersonagem); ?> EVENTOS SELECIONADOS
        </div>
        
        <!-- <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number">< ?php echo count($personagens); ?></div>
                <div class="stat-label">Personagens</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">< ?php echo count($eventos); ?></div>
                <div class="stat-label">Eventos Selecionados</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">< ?php echo count($temas); ?></div>
                <div class="stat-label">TEMAS SELECIONADOS</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">< ?php echo count($eventosPersonagem); ?></div>
                <div class="stat-label">EVENTOS DOS PERSONAGENS</div>
            </div>
            <div class="stat-item">
                <div class="stat-number">2-4</div>
                <div class="stat-label">Jogadores</div>
            </div>
        </div> -->
        
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
            <h2 class="section-title">🎭 EVENTOS DOS PERSONAGENS</h2>
            <div class="eventos-grid">
                <?php
                // Mapa rápido de personagens selecionados por id para mostrar nome/emoji
                $mapPersonagens = [];
                foreach ($personagens as $p) {
                    $idPers = intval($p['id'] ?? $p['idPersonagem'] ?? 0);
                    if ($idPers > 0) {
                        $mapPersonagens[$idPers] = $p;
                    }
                }

                if (!empty($eventosPersonagem) && is_array($eventosPersonagem)) {
                    // Extrai apenas os IDs dos eventos e mapeia para o personagem dono
                    $idsEventosPersonagem = [];
                    $eventoParaPersonagem = [];
                    foreach ($eventosPersonagem as $ep) {
                        if (!isset($ep['id'])) continue;
                        $idEvento = intval($ep['id']);
                        $idsEventosPersonagem[] = $idEvento;
                        if (isset($ep['personagem'])) {
                            $eventoParaPersonagem[$idEvento] = intval($ep['personagem']);
                        }
                    }

                    if (!empty($idsEventosPersonagem)) {
                        $placeholdersEP = implode(',', array_fill(0, count($idsEventosPersonagem), '?'));
                        $stmtEP = $pdo->prepare("SELECT * FROM tbeventopersonagem WHERE idEvento IN ($placeholdersEP) ORDER BY idPersonagem, nomeEvento");
                        $stmtEP->execute($idsEventosPersonagem);

                        while ($row = $stmtEP->fetch(PDO::FETCH_ASSOC)) {
                            $casas = intval($row['casas'] ?? 0);
                            $tipo = $casas > 0 ? 'positivo' : 'negativo';
                            $idEv = intval($row['idEvento']);
                            $idDono = $eventoParaPersonagem[$idEv] ?? intval($row['idPersonagem'] ?? 0);
                            $emojiDono = $mapPersonagens[$idDono]['emoji'] ?? '';
                            $nomeDono = $mapPersonagens[$idDono]['nome'] ?? '';

                            echo "<div class='evento-card'>";
                            echo "<div class='evento-header'>";
                            echo "<div class='evento-nome'>" . strtoupper($row['nomeEvento']) . "</div>";
                            echo "<div class='evento-casas {$tipo}'>" . ($casas > 0 ? '+' : '') . $casas . "</div>";
                            echo "</div>";
                            echo "<div class='evento-descricao'>" . substr($row['descricaoEvento'], 0, 100) . (strlen($row['descricaoEvento']) > 100 ? '...' : '') . "</div>";
                            if ($emojiDono || $nomeDono) {
                                echo "<div class='evento-dificuldade'>Específico: $emojiDono " . strtoupper($nomeDono) . "</div>";
                            }
                            echo "</div>";
                        }
                    } else {
                        echo "<div class='alert alert-info'>Nenhum evento de personagem selecionado.</div>";
                    }
                } else {
                    echo "<div class='alert alert-info'>Nenhum evento dos personagens selecionado.</div>";
                }
                ?>
            </div>
        </div>
        
        <div class="eventos-section">
            <h2 class="section-title">🎲 EVENTOS SELECIONADOS</h2>
            <div class="eventos-grid">
                <?php
                if (!empty($eventos)) {
                    $placeholders = str_repeat('?,', count($eventos) - 1) . '?';
                    $stmt = $pdo->prepare("SELECT * FROM tbevento WHERE idEvento IN ($placeholders) ORDER BY dificuldadeEvento, nomeEvento");
                    $stmt->execute($eventos);
                    
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $dificuldade = strtolower($row['dificuldadeEvento']);
                        $tipo = $row['casaEvento'] > 0 ? 'positivo' : 'negativo';
                        $casas = $row['casaEvento'];
                        
                        echo "<div class='evento-card'>";
                        echo "<div class='evento-header'>";
                        echo "<div class='evento-nome'>{$row['nomeEvento']}</div>";
                        echo "<div class='evento-casas {$tipo}'>" . ($casas > 0 ? '+' : '') . $casas . "</div>";
                        echo "</div>";
                        echo "<div class='evento-descricao'>" . substr($row['descricaoEvento'], 0, 100) . (strlen($row['descricaoEvento']) > 100 ? '...' : '') . "</div>";
                        echo "<div class='evento-dificuldade dificuldade-{$dificuldade}'>" . ucfirst($dificuldade) . "</div>";
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
                <a href="./salvarConfiguracaoBanco.php" class="btn btn-save">💾 SALVAR CONFIGURAÇÃO NO BANCO</a>
                <a href="../tabuleiro/tb.php" class="btn btn-play">🎮 JOGAR AGORA</a>
                <a href="./configurarPartida.php" class="btn btn-edit">✏️ EDITAR CONFIGURAÇÃO</a>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
