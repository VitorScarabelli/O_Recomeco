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

// Processar formulário
if ($_POST) {
     $nomeEvento = $_POST['nomeEvento'] ?? '';
     $descricaoEvento = $_POST['descricaoEvento'] ?? '';
     $casaEvento = $_POST['casaEvento'] ?? 0;
     $temaAula = $_POST['temaAula'] ?? '';
    
    // Validações
    $errors = [];
    
    if (empty($nomeEvento)) {
        $errors[] = "NOME DO EVENTO É OBRIGATÓRIO";
    }
    
     if (empty($descricaoEvento)) {
         $errors[] = "DESCRIÇÃO DO EVENTO É OBRIGATÓRIA";
     }
     
     if (empty($temaAula)) {
         $errors[] = "TEMA DA AULA É OBRIGATÓRIO";
     }
    
    if (!is_numeric($casaEvento)) {
        $errors[] = "NÚMERO DE CASAS DEVE SER UM VALOR NUMÉRICO";
    }
    
    if (empty($errors)) {
        try {
             $stmt = $pdo->prepare("UPDATE tbevento SET nomeEvento = ?, descricaoEvento = ?, casaEvento = ?, temaAula = ? WHERE idEvento = ?");
             $stmt->execute([$nomeEvento, $descricaoEvento, $casaEvento, $temaAula, $id]);
            
            header('Location: gerenciarEventos.php?success=1');
            exit;
        } catch (PDOException $e) {
            $error = "ERRO AO ATUALIZAR EVENTO: " . $e->getMessage();
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/editarEvento.css">
</head>
<body>
    <a href="gerenciarEventos.php" class="back-btn">← VOLTAR</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">✏️ EDITAR EVENTO</h1>
            <p class="admin-subtitle">MODIFIQUE OS DADOS DO EVENTO</p>
        </div>
        
        <div class="form-section">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nomeEvento" class="form-label">NOME DO EVENTO *</label>
                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento" 
                           value="<?php echo htmlspecialchars($evento['nomeEvento']); ?>" 
                           placeholder="Ex: ACESSO AO SUS" required>
                    <div class="help-text">NOME CURTO E DESCRITIVO DO EVENTO</div>
                </div>
                
                <div class="form-group">
                    <label for="descricaoEvento" class="form-label">DESCRIÇÃO DO EVENTO *</label>
                    <textarea class="form-control" id="descricaoEvento" name="descricaoEvento" 
                              rows="4" placeholder="DESCREVA O QUE ACONTECE NO EVENTO..." required><?php echo htmlspecialchars($evento['descricaoEvento']); ?></textarea>
                    <div class="help-text">DESCRIÇÃO DETALHADA DO QUE ACONTECE QUANDO O EVENTO É ATIVADO</div>
                </div>
                
                <div class="form-group">
                    <label for="casaEvento" class="form-label">NÚMERO DE CASAS *</label>
                    <input type="number" class="form-control" id="casaEvento" name="casaEvento" 
                           value="<?php echo $evento['casaEvento']; ?>" 
                           placeholder="Ex: 2 ou -2" required>
                    <div class="help-text">POSITIVO = AVANÇA CASAS, NEGATIVO = VOLTA CASAS</div>
                </div>
                
                 <div class="form-group">
                     <label for="temaAula" class="form-label">TEMA DA AULA *</label>
                     <input type="text" class="form-control" id="temaAula" name="temaAula" 
                            value="<?php echo htmlspecialchars($evento['temaAula'] ?? ''); ?>" 
                            placeholder="Ex: SUS, DROGAS, EDUCAÇÃO, etc..." required>
                     <div class="help-text">DIGITE O TEMA QUE SERÁ TRABALHADO NA AULA (PODE SER QUALQUER TEMA)</div>
                 </div>
                
                 <button type="button" class="btn-confirm" onclick="confirmarAlteracoes()">✅ CONFIRMAR ALTERAÇÕES</button>
            </form>
            
            <div class="preview-section">
                <h4 class="preview-title">📋 PREVIEW DO EVENTO</h4>
                 <div class="preview-item">
                     <strong>NOME:</strong> <span id="preview-nome"><?php echo htmlspecialchars($evento['nomeEvento']); ?></span><br>
                     <strong>DESCRIÇÃO:</strong> <span id="preview-descricao"><?php echo htmlspecialchars($evento['descricaoEvento']); ?></span><br>
                     <strong>CASAS:</strong> <span id="preview-casas"><?php echo $evento['casaEvento'] > 0 ? '+' . $evento['casaEvento'] : $evento['casaEvento']; ?></span><br>
                     <strong>TEMA DA AULA:</strong> <span id="preview-tema"><?php echo htmlspecialchars($evento['temaAula'] ?? 'Não definido'); ?></span>
                 </div>
            </div>
        </div>
    </div>
    
    <script>
        // Preview em tempo real
        document.getElementById('nomeEvento').addEventListener('input', function() {
            document.getElementById('preview-nome').textContent = this.value || '-';
        });
        
        document.getElementById('descricaoEvento').addEventListener('input', function() {
            document.getElementById('preview-descricao').textContent = this.value || '-';
        });
        
         document.getElementById('casaEvento').addEventListener('input', function() {
             const value = this.value;
             document.getElementById('preview-casas').textContent = value ? (value > 0 ? '+' + value : value) : '-';
         });
         
         document.getElementById('temaAula').addEventListener('input', function() {
             document.getElementById('preview-tema').textContent = this.value || '-';
         });
         
         function confirmarAlteracoes() {
             if (confirm('TEM CERTEZA QUE DESEJA SALVAR AS ALTERAÇÕES?')) {
                 document.getElementById('btn-salvar').style.display = 'block';
                 document.getElementById('btn-salvar').click();
             }
         }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
