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
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <a href="../index.php" class="back-btn">← VOLTAR</a>
    <a href="./logoff.php" onclick="return confirm('TEM CERTEZA QUE DESEJA DESCONECTAR?')" class="logout-btn">DESCONECTAR</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">🔧 ÁREA ADMINISTRATIVA</h1>
            <p class="admin-subtitle">ESCOLHA UMA DAS OPÇÕES ABAIXO</p>
        </div>

        <div class="cards-hub">
            <a class="hub-card" href="gerenciarPartidas.php">
                <div class="hub-title">🗂️ PARTIDAS JÁ CRIADAS</div>
                <p class="hub-desc">VEJA, EDITE E EXCLUA PARTIDAS CRIADAS ANTERIORMENTE.</p>
            </a>

            <a class="hub-card" href="configurarPartida.php">
                <div class="hub-title">➕ CRIAR NOVA PARTIDA</div>
                <p class="hub-desc">CONFIGURE PERSONAGENS E EVENTOS E SALVE UMA NOVA PARTIDA.</p>
            </a>
        </div>

        <!-- <div style="height: 382px; width: 100px; background-color: transparent;"></div> -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
