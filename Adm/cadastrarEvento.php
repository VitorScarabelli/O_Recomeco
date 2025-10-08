<?php include('../banco/conexao.php'); include('../includes/verificar_login.php');?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Evento - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }
        
        .admin-container {
            max-width: 800px;
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
        
        .form-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .form-control, .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
            width: 100%;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
        }
        
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
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .help-text {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
        
        .preview-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed #dee2e6;
        }
        
        .preview-title {
            color: #495057;
            font-weight: bold;
            margin-bottom: 15px;
        }
        
        .preview-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-btn">← Voltar</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">➕ Cadastrar Evento</h1>
            <p class="admin-subtitle">Crie novos eventos para o jogo</p>
        </div>
        
        <div class="form-section">
            <?php
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
                        $stmt = $pdo->prepare("INSERT INTO tbevento (nomeEvento, descricaoEvento, casaEvento, temaAula) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$nomeEvento, $descricaoEvento, $casaEvento, $temaAula]);
                        
                        echo '<div class="alert alert-success">✅ Evento cadastrado com sucesso!</div>';
                        
                        // Limpar campos após sucesso
                        $nomeEvento = $descricaoEvento = '';
                        $casaEvento = 0;
                        $temaAula = '';
                    } catch (PDOException $e) {
                        echo '<div class="alert alert-danger">❌ Erro ao cadastrar evento: ' . $e->getMessage() . '</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">❌ ' . implode('<br>', $errors) . '</div>';
                }
            }
            ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nomeEvento" class="form-label">Nome do Evento *</label>
                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento" 
                           value="<?php echo htmlspecialchars($nomeEvento ?? ''); ?>" 
                           placeholder="Ex: Acesso ao SUS" required>
                    <div class="help-text">Nome curto e descritivo do evento</div>
                </div>
                
                <div class="form-group">
                    <label for="descricaoEvento" class="form-label">Descrição do Evento *</label>
                    <textarea class="form-control" id="descricaoEvento" name="descricaoEvento" 
                              rows="4" placeholder="Descreva o que acontece no evento..." required><?php echo htmlspecialchars($descricaoEvento ?? ''); ?></textarea>
                    <div class="help-text">Descrição detalhada do que acontece quando o evento é ativado</div>
                </div>
                
                <div class="form-group">
                    <label for="casaEvento" class="form-label">NÚMERO DE CASAS *</label>
                    <input type="number" class="form-control" id="casaEvento" name="casaEvento" 
                           value="<?php echo $casaEvento ?? 0; ?>" 
                           placeholder="Ex: 2 ou -2" required>
                    <div class="help-text">POSITIVO = AVANÇA CASAS, NEGATIVO = VOLTA CASAS</div>
                </div>
                
                 <div class="form-group">
                     <label for="temaAula" class="form-label">TEMA DA AULA *</label>
                     <input type="text" class="form-control" id="temaAula" name="temaAula" 
                            value="<?php echo htmlspecialchars($temaAula ?? ''); ?>" 
                            placeholder="Ex: SUS, DROGAS, EDUCAÇÃO, etc..." required>
                     <div class="help-text">DIGITE O TEMA QUE SERÁ TRABALHADO NA AULA (PODE SER QUALQUER TEMA)</div>
                 </div>
                
                <button type="submit" class="btn-submit">💾 Cadastrar Evento</button>
            </form>
            
            <div class="preview-section">
                <h4 class="preview-title">📋 Preview do Evento</h4>
                <div class="preview-item">
                    <strong>NOME:</strong> <span id="preview-nome">-</span><br>
                    <strong>DESCRIÇÃO:</strong> <span id="preview-descricao">-</span><br>
                    <strong>CASAS:</strong> <span id="preview-casas">-</span><br>
                    <strong>TEMA DA AULA:</strong> <span id="preview-tema">-</span>
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
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
