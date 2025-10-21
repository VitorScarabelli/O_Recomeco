<?php 
include('../banco/conexao.php');
include('../includes/verificar_login.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Buscar partida
$stmt = $pdo->prepare("SELECT * FROM tbConfiguracaoPartida WHERE idConfiguracao = ?");
$stmt->execute([$id]);
$partida = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$partida) {
    header('Location: index.php');
    exit;
}

// Decodificar dados JSON
$personagens = json_decode($partida['personagens'], true) ?? [];
$temas = json_decode($partida['temasSelecionados'], true) ?? [];
$eventos = json_decode($partida['eventosSelecionados'], true) ?? [];
$eventosPersonagem = json_decode($partida['eventosPersonagem'], true) ?? [];

// Processar exclusão
if ($_POST && isset($_POST['confirmar'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM tbConfiguracaoPartida WHERE idConfiguracao = ?");
        $stmt->execute([$id]);
        
        header('Location: index.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        $error = "ERRO AO EXCLUIR PARTIDA: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Partida - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/excluirPartida.css">
</head>
<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">🗑️ EXCLUIR PARTIDA</h1>
            <p class="admin-subtitle">CONFIRME A EXCLUSÃO DA PARTIDA</p>
        </div>
        
        <div class="form-section">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="warning-section">
                <div class="warning-title">⚠️ ATENÇÃO!</div>
                <div class="warning-text">
                    ESTA AÇÃO NÃO PODE SER DESFEITA. A PARTIDA SERÁ PERMANENTEMENTE REMOVIDA DO SISTEMA E NÃO ESTARÁ MAIS DISPONÍVEL PARA OS JOGADORES.
                </div>
            </div>
            
            <div class="partida-info">
                <div class="partida-nome"><?php echo htmlspecialchars($partida['nomeConfiguracao']); ?></div>
                <div class="partida-codigo"><?php echo htmlspecialchars($partida['codigoPartida']); ?></div>
                
                <div class="partida-detalhes">
                    <div class="detalhe-item">
                        <div class="detalhe-label">ID DA PARTIDA</div>
                        <div class="detalhe-valor">#<?php echo $partida['idConfiguracao']; ?></div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">PERSONAGENS</div>
                        <div class="detalhe-valor"><?php echo count($personagens); ?></div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">EVENTOS GERAIS</div>
                        <div class="detalhe-valor"><?php echo count($eventos); ?></div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">EVENTOS PERSONAGENS</div>
                        <div class="detalhe-valor"><?php echo count($eventosPersonagem); ?></div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">TEMAS</div>
                        <div class="detalhe-valor"><?php echo count($temas); ?></div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">CRIADO EM</div>
                        <div class="detalhe-valor"><?php echo date('d/m/Y H:i', strtotime($partida['dataCriacao'])); ?></div>
                    </div>
                </div>
                
                <?php if (!empty($personagens)): ?>
                <div class="personagens-list">
                    <div class="detalhe-label" style="margin-bottom: 10px;">PERSONAGENS SELECIONADOS:</div>
                    <?php foreach ($personagens as $personagem): ?>
                        <div class="personagem-item">
                            <div class="personagem-emoji"><?php echo $personagem['emoji']; ?></div>
                            <div class="personagem-nome"><?php 
                                $nome = ($personagem['nome']);
                                echo $nome; 
                            ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <form method="POST">
                <button type="submit" name="confirmar" class="btn-delete" onclick="return confirm('TEM CERTEZA QUE DESEJA EXCLUIR ESTA PARTIDA? ESTA AÇÃO NÃO PODE SER DESFEITA!')">
                    🗑️ CONFIRMAR EXCLUSÃO
                </button>
            </form>
            
            <a href="index.php" class="btn-cancel">
                ❌ CANCELAR
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
