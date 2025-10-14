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
        $errors[] = "Nome do evento é obrigatório";
    }
    
     if (empty($descricaoEvento)) {
         $errors[] = "Descrição do evento é obrigatória";
     }
     
     if (empty($temaAula)) {
         $errors[] = "Tema da aula é obrigatório";
     }
    
    if (!is_numeric($casaEvento)) {
        $errors[] = "Número de casas deve ser um valor numérico";
    }
    
    if (empty($errors)) {
        try {
             $stmt = $pdo->prepare("UPDATE tbevento SET nomeEvento = ?, descricaoEvento = ?, casaEvento = ?, temaAula = ? WHERE idEvento = ?");
             $stmt->execute([$nomeEvento, $descricaoEvento, $casaEvento, $temaAula, $id]);
            
            header('Location: gerenciarEventos.php?success=1');
            exit;
        } catch (PDOException $e) {
            $error = "Erro ao atualizar evento: " . $e->getMessage();
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
    <a href="gerenciarEventos.php" class="back-btn">← Voltar</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">✏️ Editar Evento</h1>
            <p class="admin-subtitle">Modifique os dados do evento</p>
        </div>
        
        <div class="form-section">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nomeEvento" class="form-label">Nome do Evento *</label>
                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento" 
                           value="<?php echo htmlspecialchars($evento['nomeEvento']); ?>" 
                           placeholder="Ex: Acesso ao SUS" required>
                    <div class="help-text">Nome curto e descritivo do evento</div>
                </div>
                
                <div class="form-group">
                    <label for="descricaoEvento" class="form-label">Descrição do Evento *</label>
                    <textarea class="form-control" id="descricaoEvento" name="descricaoEvento" 
                              rows="4" placeholder="Descreva o que acontece no evento..." required><?php echo htmlspecialchars($evento['descricaoEvento']); ?></textarea>
                    <div class="help-text">Descrição detalhada do que acontece quando o evento é ativado</div>
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
                 <button type="submit" class="btn-submit" id="btn-salvar" style="display: none;">💾 SALVAR ALTERAÇÕES</button>
            </form>
            
            <div class="preview-section">
                <h4 class="preview-title">📋 Preview do Evento</h4>
                 <div class="preview-item">
                     <strong>NOME:</strong> <span id="preview-nome"><?php echo htmlspecialchars($evento['nomeEvento']); ?></span><br>
                     <strong>DESCRIÇÃO:</strong> <span id="preview-descricao"><?php echo htmlspecialchars($evento['descricaoEvento']); ?></span><br>
                     <strong>CASAS:</strong> <span id="preview-casas"><?php echo $evento['casaEvento'] > 0 ? '+' . $evento['casaEvento'] : $evento['casaEvento']; ?></span><br>
                     <strong>TEMA DA AULA:</strong> <span id="preview-tema"><?php echo htmlspecialchars($evento['temaAula'] ?? 'Não definido'); ?></span>
                 </div>
            </div>
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
