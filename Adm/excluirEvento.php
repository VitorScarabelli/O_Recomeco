<?php include('../banco/conexao.php'); include('../includes/verificar_login.php');?>

<?php
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: gerenciarEventos.php');
    exit;
}

// Buscar evento
$stmt = $pdo->prepare("SELECT * FROM tbevento WHERE idEvento = ?");
$stmt->execute([$id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    header('Location: gerenciarEventos.php');
    exit;
}

// Processar exclusão
if ($_POST && isset($_POST['confirmar'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM tbevento WHERE idEvento = ?");
        $stmt->execute([$id]);
        
        header('Location: gerenciarEventos.php?deleted=1');
        exit;
    } catch (PDOException $e) {
        $error = "Erro ao excluir evento: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Evento - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/excluirEvento.css">
</head>
<body>
    <a href="gerenciarEventos.php" class="back-btn">← VOLTAR</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">🗑️ EXCLUIR EVENTO</h1>
            <p class="admin-subtitle">CONFIRME A EXCLUSÃO DO EVENTO</p>
        </div>
        
        <div class="form-section">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="warning-section">
                <div class="warning-title">⚠️ ATENÇÃO!</div>
                <div class="warning-text">
                    ESTA AÇÃO NÃO PODE SER DESFEITA. O EVENTO SERÁ PERMANENTEMENTE REMOVIDO DO SISTEMA E NÃO ESTARÁ MAIS DISPONÍVEL PARA AS PARTIDAS.
                </div>
            </div>
            
            <div class="evento-info">
                <div class="evento-nome"><?php echo htmlspecialchars($evento['nomeEvento']); ?></div>
                <div class="evento-descricao"><?php echo htmlspecialchars($evento['descricaoEvento']); ?></div>
                
                <div class="evento-detalhes">
                    <div class="detalhe-item">
                        <div class="detalhe-label">ID DO EVENTO</div>
                        <div class="detalhe-valor">#<?php echo $evento['idEvento']; ?></div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">TIPO</div>
                        <div class="detalhe-valor tipo-<?php echo $evento['casaEvento'] > 0 ? 'positivo' : 'negativo'; ?>">
                            <?php echo $evento['casaEvento'] > 0 ? 'Positivo' : 'Negativo'; ?>
                        </div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">CASAS</div>
                        <div class="detalhe-valor"><?php echo $evento['casaEvento'] > 0 ? '+' . $evento['casaEvento'] : $evento['casaEvento']; ?></div>
                    </div>
                </div>
            </div>
            
            <form method="POST">
                <button type="submit" name="confirmar" class="btn-delete" onclick="return confirm('TEM CERTEZA QUE DESEJA EXCLUIR ESTE EVENTO? ESTA AÇÃO NÃO PODE SER DESFEITA!')">
                    🗑️ CONFIRMAR EXCLUSÃO
                </button>
            </form>
            
            <a href="gerenciarEventos.php" class="btn-cancel">
                ❌ CANCELAR
            </a>
        </div>
        <br><br><br><br>

        <!-- Paginação entre páginas -->
        <div class="pagination-section">
            <div class="pagination-container">
                <div class="pagination-nav">
                    <a href="index.php" class="pagination-btn">‹‹ INÍCIO</a>
                    <a href="index.php" class="pagination-btn">1</a>
                    <a href="cadastrarEvento.php" class="pagination-btn">2</a>
                    <a href="gerenciarEventos.php" class="pagination-btn active">3</a>
                    <a href="configurarPartida.php" class="pagination-btn">4</a>
                    <a href="configurarPartida.php" class="pagination-btn">FINAL ››</a>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>