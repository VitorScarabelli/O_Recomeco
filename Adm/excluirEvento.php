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
        
        .evento-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            border-left: 4px solid #dc3545;
        }
        
        .evento-nome {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
        }
        
        .evento-descricao {
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        
        .evento-detalhes {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        
        .detalhe-item {
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }
        
        .detalhe-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
        }
        
        .detalhe-valor {
            font-weight: bold;
            color: #2c3e50;
        }
        
        .dificuldade-facil { color: #28a745; }
        .dificuldade-medio { color: #ffc107; }
        .dificuldade-dificil { color: #fd7e14; }
        .dificuldade-extremo { color: #dc3545; }
        
        .tipo-positivo { color: #28a745; }
        .tipo-negativo { color: #dc3545; }
        
        .btn-delete {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .btn-delete:hover {
            transform: translateY(-2px);
        }
        
        .btn-cancel {
            background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        
        .btn-cancel:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
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
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }
        
        .warning-section {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
        }
        
        .warning-title {
            color: #856404;
            font-weight: bold;
            margin-bottom: 10px;
        }
        
        .warning-text {
            color: #856404;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <a href="gerenciarEventos.php" class="back-btn">← Voltar</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">🗑️ Excluir Evento</h1>
            <p class="admin-subtitle">Confirme a exclusão do evento</p>
        </div>
        
        <div class="form-section">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="warning-section">
                <div class="warning-title">⚠️ Atenção!</div>
                <div class="warning-text">
                    Esta ação não pode ser desfeita. O evento será permanentemente removido do sistema e não estará mais disponível para as partidas.
                </div>
            </div>
            
            <div class="evento-info">
                <div class="evento-nome"><?php echo htmlspecialchars($evento['nomeEvento']); ?></div>
                <div class="evento-descricao"><?php echo htmlspecialchars($evento['descricaoEvento']); ?></div>
                
                <div class="evento-detalhes">
                    <div class="detalhe-item">
                        <div class="detalhe-label">ID do Evento</div>
                        <div class="detalhe-valor">#<?php echo $evento['idEvento']; ?></div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">Dificuldade</div>
                        <div class="detalhe-valor dificuldade-<?php echo strtolower($evento['dificuldadeEvento']); ?>">
                            <?php echo ucfirst($evento['dificuldadeEvento']); ?>
                        </div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">Tipo</div>
                        <div class="detalhe-valor tipo-<?php echo $evento['casaEvento'] > 0 ? 'positivo' : 'negativo'; ?>">
                            <?php echo $evento['casaEvento'] > 0 ? 'Positivo' : 'Negativo'; ?>
                        </div>
                    </div>
                    
                    <div class="detalhe-item">
                        <div class="detalhe-label">Casas</div>
                        <div class="detalhe-valor"><?php echo $evento['casaEvento'] > 0 ? '+' . $evento['casaEvento'] : $evento['casaEvento']; ?></div>
                    </div>
                </div>
            </div>
            
            <form method="POST">
                <button type="submit" name="confirmar" class="btn-delete" onclick="return confirm('Tem certeza que deseja excluir este evento? Esta ação não pode ser desfeita!')">
                    🗑️ Confirmar Exclusão
                </button>
            </form>
            
            <a href="gerenciarEventos.php" class="btn-cancel">
                ❌ Cancelar
            </a>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>