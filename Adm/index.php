<?php 
include('../banco/conexao.php'); 
include('../includes/verificar_login.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - O Recomeço</title>
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
        
        .admin-cards {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            .admin-cards {
                grid-template-columns: 1fr;
            }
        }
        
        .admin-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            text-decoration: none;
            color: inherit;
        }
        
        .admin-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            text-decoration: none;
            color: inherit;
        }
        
        .card-icon {
            font-size: 3rem;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .card-description {
            color: #7f8c8d;
            text-align: center;
            line-height: 1.6;
        }
        
        .stats-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .stats-title {
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
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
        
        .logout-btn {
            position: fixed;
            top: 20px;
            right: 20px;
            background: rgba(231, 76, 60, 0.9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
        }
        
        .logout-btn:hover {
            background: rgba(231, 76, 60, 1);
            color: white;
            text-decoration: none;
        }

        .sair-btn {
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
            z-index: 9999;
        }   

        .sair-btn:hover {
            background: rgba(52, 73, 94, 1);
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="../index/index.php" class="sair-btn">← VOLTAR</a>
    <br>
    <a href="./logoff.php" class="logout-btn">DESCONECTAR</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">📝 GERENCIAR EVENTOS</h1>
            <p class="admin-subtitle">VISUALIZE, EDITE E EXCLUA EVENTOS DO JOGO</p>
            <br>
            <h1>PASSO A PASSO PARA CONFIGURAR A PARTIDA</h1>
            <p>1. VÁ PARA O CARD <a href="cadastrarEvento.php"> "CADASTRAR EVENTO" </a></p>
            <p>2. CRIE UM EVENTO POSITIVO OU NEGATIVO</p>
            <p>3. VÁ PARA O CARD <a href="configurarPartida.php"> "CONFIGURAR PARTIDA" </a></p>
            <p>4. SELECIONE OS PERSONAGENS</p>
            <p>5. SELECIONE OS EVENTOS DO PERSONAGEM</p>
            <p>6. SELECIONE OS TEMAS</p>
            <p>7. SELECIONE OS EVENTOS</p>
            <p>8. SELECIONE OS TEMAS DA PARTIDA</p>
            <p>9. SALVE A CONFIGURAÇÃO</p>
            <p>10. VÁ PARA O CARD <a href="visualizarPartida.php"> "VISUALIZAR PARTIDA" </a></p>
            <p>11. INICIE A PARTIDA</p>
        </div>
        
        <div class="admin-cards">

            <a href="cadastrarEvento.php" class="admin-card">
                <div class="card-icon">➕</div>
                <h3 class="card-title" style="text-transform:uppercase;">CADASTRAR EVENTO</h3>
                <p class="card-description" style="text-transform:uppercase;">CRIE NOVOS EVENTOS PARA O JOGO. ADICIONE EVENTOS POSITIVOS E NEGATIVOS COM DIFERENTES DIFICULDADES.</p>
            </a>
            
            <a href="gerenciarEventos.php" class="admin-card">
                <div class="card-icon">📝</div>
                <h3 class="card-title" style="text-transform:uppercase;">GERENCIAR EVENTOS</h3>
                <p class="card-description" style="text-transform:uppercase;">VISUALIZE, EDITE E EXCLUA EVENTOS DO JOGO. GERENCIE TODOS OS EVENTOS DISPONÍVEIS PARA AS PARTIDAS.</p>
            </a>
            
            <a href="configurarPartida.php" class="admin-card">
                <div class="card-icon">⚙️</div>
                <h3 class="card-title" style="text-transform:uppercase;">CONFIGURAR PARTIDA</h3>
                <p class="card-description" style="text-transform:uppercase;">SELECIONE PERSONAGENS E EVENTOS PARA A PRÓXIMA PARTIDA. CONFIGURE OS PARÂMETROS DO JOGO.</p>
            </a>
            
            <a href="visualizarPartida.php" class="admin-card">
                <div class="card-icon">🎯</div>
                <h3 class="card-title" style="text-transform:uppercase;">INICIAR PARTIDA</h3>
                <p class="card-description" style="text-transform:uppercase;">VISUALIZE E INICIE A PARTIDA CONFIGURADA. ACESSE O TABULEIRO DO JOGO.</p>
            </a>
        </div>
        
        <div class="stats-section">
            <h2 class="stats-title">📊 ESTATÍSTICAS DO SISTEMA</h2>
            <div class="stats-grid">
                <?php
                // Contar eventos por dificuldade
                $stmt = $pdo->query("SELECT dificuldadeEvento, COUNT(*) as total FROM tbevento GROUP BY dificuldadeEvento");
                $eventosPorDificuldade = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Contar total de eventos
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM tbevento");
                $totalEventos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                // Contar eventos por tipo
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM tbevento WHERE casaEvento > 0");
                $eventosPositivos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                
                $stmt = $pdo->query("SELECT COUNT(*) as total FROM tbevento WHERE casaEvento < 0");
                $eventosNegativos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
                ?>
                
                <div class="stat-item">
                    <div class="stat-number"><?php echo strtoupper($totalEventos); ?></div>
                    <div class="stat-label"><?php echo strtoupper('Total de Eventos'); ?></div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number"><?php echo strtoupper($eventosPositivos); ?></div>
                    <div class="stat-label"><?php echo strtoupper('Eventos Positivos'); ?></div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number"><?php echo strtoupper($eventosNegativos); ?></div>
                    <div class="stat-label"><?php echo strtoupper('Eventos Negativos'); ?></div>
                </div>
                
                <div class="stat-item">
                    <div class="stat-number"><?php echo strtoupper('6'); ?></div>
                    <div class="stat-label"><?php echo strtoupper('Personagens Disponíveis'); ?></div>
                </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
