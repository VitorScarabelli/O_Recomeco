<?php
include('../includes/verificar_login.php');
include('../banco/conexao.php');

// Processar exclusão de partida
if (isset($_GET['excluir']) && isset($_GET['id'])) {
    $idPartida = intval($_GET['id']);
    
    try {
        $stmt = $pdo->prepare("UPDATE tbConfiguracaoPartida SET ativo = 0 WHERE idConfiguracao = ?");
        $stmt->execute([$idPartida]);
        
        $mensagem = "Partida excluída com sucesso!";
        $tipoMensagem = "success";
    } catch (PDOException $e) {
        $mensagem = "Erro ao excluir partida: " . $e->getMessage();
        $tipoMensagem = "error";
    }
}

// Buscar todas as partidas ativas
$stmt = $pdo->query("SELECT * FROM tbConfiguracaoPartida WHERE ativo = 1 ORDER BY dataCriacao DESC");
$partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Partidas - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/index.css">
</head>

<body>
    <a href="../index.php" class="back-btn">← VOLTAR</a>

    <a href="./logoff.php" onclick="return confirm('TEM CERTEZA QUE DESEJA DESCONECTAR?')" class="logout-btn">DESCONECTAR</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">🎮 GERENCIAR PARTIDAS</h1>
            <p class="admin-subtitle">GERENCIE TODAS AS CONFIGURAÇÕES DE PARTIDAS SALVAS</p>
        </div>

        <?php if (isset($mensagem)): ?>
            <div class="alert alert-<?php echo $tipoMensagem === 'success' ? 'success' : 'danger'; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <?php if (empty($partidas)): ?>
            <div class="no-partidas">
                <h3>🎯 NENHUMA PARTIDA CONFIGURADA</h3>
                <p>VOCÊ AINDA NÃO TEM NENHUMA CONFIGURAÇÃO DE PARTIDA SALVA.</p>
                <a href="configurarPartida.php" class="btn-criar-partida">⚙️ CRIAR NOVA PARTIDA</a>
            </div>
        <?php else: ?>
            <div class="partidas-grid">
                <?php foreach ($partidas as $partida): ?>
                    <?php
                    $personagens = json_decode($partida['personagens'], true);
                    $temas = json_decode($partida['temasSelecionados'], true);
                    $eventos = json_decode($partida['eventosSelecionados'], true);
                    $eventosPersonagem = json_decode($partida['eventosPersonagem'], true);
                    $nomesJogadores = !empty($partida['nomesJogadores']) ? json_decode($partida['nomesJogadores'], true) : [];
                    // mapa de id->nomeUsuario
                    $mapaNomes = [];
                    if (is_array($nomesJogadores)) {
                        foreach ($nomesJogadores as $nj) {
                            $pid = intval($nj['idPersonagem'] ?? 0);
                            if ($pid > 0 && !empty($nj['nomeUsuario'])) {
                                $mapaNomes[$pid] = $nj['nomeUsuario'];
                            }
                        }
                    }
                    ?>
                    <div class="partida-card">
                        <div class="partida-header">
                            <div class="partida-nome"><?php echo htmlspecialchars($partida['nomeConfiguracao']); ?></div>
                            <div class="partida-codigo"><?php echo htmlspecialchars($partida['codigoPartida']); ?></div>
                        </div>
                        
                        <div class="partida-data">
                            <strong>CRIADO EM:</strong> <?php echo date('d/m/Y H:i', strtotime($partida['dataCriacao'])); ?>
                        </div>

                        <div class="partida-stats">
                            <div class="stat-item">
                                <div class="stat-number"><?php echo count($personagens); ?></div>
                                <div class="stat-label">PERSONAGENS</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?php echo count($eventos); ?></div>
                                <div class="stat-label">EVENTOS GERAIS</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?php echo count($eventosPersonagem); ?></div>
                                <div class="stat-label">EVENTOS PERSONAGENS</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number"><?php echo count($temas); ?></div>
                                <div class="stat-label">TEMAS</div>
                            </div>
                        </div>

                        <?php if (!empty($mapaNomes)): ?>
                        <div class="partida-jogadores" style="margin-top:10px;">
                            <strong>JOGADORES:</strong>
                            <ul style="margin:8px 0 0 16px; padding:0;">
                                <?php foreach ($personagens as $p): ?>
                                    <?php 
                                        $pid = intval($p['id'] ?? $p['idPersonagem'] ?? 0);
                                        if ($pid > 0 && isset($mapaNomes[$pid])): 
                                            $emoji = htmlspecialchars($p['emoji'] ?? '👤');
                                            $nome = htmlspecialchars($mapaNomes[$pid]);
                                            $pnomeRaw = $p['nome'] ?? $p['nomePersonagem'] ?? 'Personagem';
                                            $pnomeRaw = preg_match('/^CEGO$/i', $pnomeRaw) ? 'DEFICIENTE VISUAL' : $pnomeRaw;
                                            $pnome = htmlspecialchars($pnomeRaw);
                                    ?>
                                    <li><?php echo $emoji; ?> <?php echo $pnome; ?> — <?php echo $nome; ?></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                        <?php endif; ?>

                        <div class="partida-actions">
                            <a href="editarPartida.php?id=<?php echo $partida['idConfiguracao']; ?>" class="btn-partida btn-editar">✏️ EDITAR PARTIDA</a>
                            <?php
                            echo "<a class='btn-partida btn-excluir' href='excluirPartida.php?id={$partida['idConfiguracao']}' class='btn btn-action btn-delete' onclick='return confirm(\"TEM CERTEZA QUE DESEJA EXCLUIR ESTA PARTIDA?\")'>🗑️ EXCLUIR</a>";
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <br><br><br><br>
        <!-- Paginação entre páginas -->
        <div class="pagination-section">
            <div class="pagination-container">
                <div class="pagination-nav">
                    <a href="index.php" class="pagination-btn disabled">‹‹ INÍCIO</a>
                    <a href="index.php" class="pagination-btn active">1</a>
                    <a href="cadastrarEvento.php" class="pagination-btn">2</a>
                    <a href="gerenciarEventos.php" class="pagination-btn">3</a>
                    <a href="configurarPartida.php" class="pagination-btn">4</a>
                    <a href="configurarPartida.php" class="pagination-btn">FINAL ››</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
