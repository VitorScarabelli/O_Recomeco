<?php
include('../includes/verificar_login.php');
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/index.css">
    <style>
        .cards-hub { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 20px; }
        .hub-card { background: rgba(255,255,255,0.95); border-radius: 16px; padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,.15); text-decoration: none; color: #2c3e50; transition: transform .2s ease, box-shadow .2s ease; display: block; }
        .hub-card:hover { transform: translateY(-4px); box-shadow: 0 14px 36px rgba(0,0,0,.2); text-decoration: none; }
        .hub-icon { font-size: 2rem; margin-bottom: 8px; }
        .hub-title { font-weight: bold; font-size: 1.25rem; margin-bottom: 6px; }
        .hub-desc { color: #6c757d; margin: 0; }
    </style>
</head>

<body>
    <a href="../index.php" class="back-btn">← VOLTAR</a>
    <a href="./logoff.php" onclick="return confirm('TEM CERTEZA QUE DESEJA DESCONECTAR?')" class="logout-btn">DESCONECTAR</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">🔧 ÁREA ADMINISTRATIVA</h1>
            <p class="admin-subtitle">Escolha uma opção abaixo</p>
        </div>

        <div class="cards-hub">
            <a class="hub-card" href="gerenciarPartidas.php">
                <div class="hub-icon">🗂️</div>
                <div class="hub-title">Partidas já feitas</div>
                <p class="hub-desc">Veja, edite e exclua partidas criadas anteriormente.</p>
            </a>

            <a class="hub-card" href="configurarPartida.php">
                <div class="hub-icon">➕</div>
                <div class="hub-title">Criar nova partida</div>
                <p class="hub-desc">Configure personagens e eventos e salve uma nova partida.</p>
            </a>
        </div>

        <br><br><br><br>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
