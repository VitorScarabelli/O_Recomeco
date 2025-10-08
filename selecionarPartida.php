<?php
include('./banco/conexao.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Partida - O Recomeço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }

        .header h1 {
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .header p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        .partidas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .partida-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 3px solid transparent;
        }

        .partida-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
            border-color: #667eea;
        }

        .partida-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .partida-nome {
            font-size: 1.4rem;
            font-weight: bold;
            color: #2c3e50;
        }

        .partida-data {
            font-size: 0.9rem;
            color: #6c757d;
        }

        .partida-stats {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }

        .stat-item {
            text-align: center;
            padding: 10px;
            background: rgba(102, 126, 234, 0.1);
            border-radius: 10px;
        }

        .stat-number {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
            font-weight: bold;
        }

        .partida-temas {
            margin-bottom: 20px;
        }

        .temas-title {
            font-size: 0.9rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .temas-list {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .tema-badge {
            background: #667eea;
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .partida-actions {
            display: flex;
            gap: 10px;
        }

        .btn-partida {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            text-align: center;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .btn-jogar {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
        }

        .btn-jogar:hover {
            background: linear-gradient(135deg, #218838, #1ea085);
            color: white;
            text-decoration: none;
        }

        .btn-excluir {
            background: linear-gradient(135deg, #dc3545, #e74c3c);
            color: white;
        }

        .btn-excluir:hover {
            background: linear-gradient(135deg, #c82333, #c0392b);
            color: white;
            text-decoration: none;
        }

        .no-partidas {
            text-align: center;
            color: white;
            padding: 60px 20px;
        }

        .no-partidas h3 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .no-partidas p {
            font-size: 1.1rem;
            margin-bottom: 30px;
        }

        .btn-criar-partida {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 15px 30px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }

        .btn-criar-partida:hover {
            background: linear-gradient(135deg, #5a6fd8, #6a4190);
            color: white;
            text-decoration: none;
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
            z-index: 9999;
        }

        .back-btn:hover {
            background: rgba(52, 73, 94, 1);
            color: white;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <a href="./index/index.php" class="back-btn">← Voltar</a>

    <div class="container">
        <div class="header">
            <h1>🎮 SELECIONAR PARTIDA</h1>
            <p>ESCOLHA UMA CONFIGURAÇÃO DE PARTIDA SALVA OU CRIE UMA NOVA</p>
        </div>

        <?php
        // Buscar configurações salvas
        $stmt = $pdo->query("SELECT * FROM tbConfiguracaoPartida WHERE ativo = 1 ORDER BY dataCriacao DESC");
        $partidas = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (empty($partidas)) {
            echo "<div class='no-partidas'>";
            echo "<h3>🎯 NENHUMA PARTIDA CONFIGURADA</h3>";
            echo "<p>VOCÊ AINDA NÃO TEM NENHUMA CONFIGURAÇÃO DE PARTIDA SALVA.</p>";

            // Verificar se está logado como admin
            if (isset($_SESSION['admin_logado']) && $_SESSION['admin_logado'] === true) {
                echo "<a href='Adm/configurarPartida.php' class='btn-criar-partida'>⚙️ CRIAR NOVA PARTIDA</a>";
            } else {
                echo "<a href='login/login.php' class='btn-criar-partida'>🔐 FAZER LOGIN COMO ADMIN</a>";
            }
            echo "</div>";
        } else {
            echo "<div class='partidas-grid'>";

            foreach ($partidas as $partida) {
                $personagens = json_decode($partida['personagens'], true);
                $temas = json_decode($partida['temasSelecionados'], true);
                $eventos = json_decode($partida['eventosSelecionados'], true);
                $eventosPersonagem = json_decode($partida['eventosPersonagem'], true);

                echo "<div class='partida-card'>";
                echo "<div class='partida-header'>";
                echo "<div class='partida-nome'>{$partida['nomeConfiguracao']}</div>";
                echo "<div class='partida-data'>" . date('d/m/Y H:i', strtotime($partida['dataCriacao'])) . "</div>";
                echo "</div>";

                echo "<div class='partida-stats'>";
                echo "<div class='stat-item'>";
                echo "<div class='stat-number'>" . count($personagens) . "</div>";
                echo "<div class='stat-label'>PERSONAGENS</div>";
                echo "</div>";
                echo "<div class='stat-item'>";
                echo "<div class='stat-number'>" . count($eventos) . "</div>";
                echo "<div class='stat-label'>EVENTOS</div>";
                echo "</div>";
                echo "<div class='stat-item'>";
                echo "<div class='stat-number'>" . count($temas) . "</div>";
                echo "<div class='stat-label'>TEMAS</div>";
                echo "</div>";
                echo "<div class='stat-item'>";
                echo "<div class='stat-number'>" . count($eventosPersonagem) . "</div>";
                echo "<div class='stat-label'>EVENTOS PERSONAGENS</div>";
                echo "</div>";
                echo "</div>";

                if (!empty($temas)) {
                    echo "<div class='partida-temas'>";
                    echo "<div class='temas-title'>TEMAS SELECIONADOS:</div>";
                    echo "<div class='temas-list'>";
                    foreach ($temas as $tema) {
                        echo "<span class='tema-badge'>" . htmlspecialchars($tema) . "</span>";
                    }
                    echo "</div>";
                    echo "</div>";
                }

                echo "<div class='partida-actions'>";
                echo "<a href='carregarPartida.php?id={$partida['idConfiguracao']}' class='btn-partida btn-jogar'>🎮 JOGAR</a>";
                echo "</div>";
                echo "</div>";
            }

            echo "</div>";

            echo "<div style='text-align: center; margin-top: 30px;'>";
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>