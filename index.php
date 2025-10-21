<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recomeço</title>
  <link rel="stylesheet" href="./index/css/style.css  ">
</head>

<body>

  <!-- <div class="botoesAcima">
    <a href="../Adm/index.php"><img class="botaoPequeno" src="assets/Usuario.png" alt="Configurações"></a>
  </div> -->

  <div class="logo">
    <img src="./index/assets/RecomecoLogo.png" alt="Logo">
  </div>

  <div class="botoesMenu">
    <a href="./selecionarPartida.php">
      <div class="conteinerBotoesPequenos">
        <img src="./index/assets/caderno.png" alt="JOGAR">
        <span>JOGAR</span>
      </div>
    </a>

    <!-- Exemplo de botão extra
    <a href="../jogoPersonalizado/selecaoEventos.php">
      <div class="conteinerBotoesPequenos">
        <img src="./index/assets/cadernodois.png" alt="CUSTOM.">
        <span>CUSTOM.</span>
      </div>
    </a> -->

    <a href="./login/login.php">
      <div class="conteinerBotoesPequenos">
        <?php
        session_start();
        ?>

        <?php if (isset($_SESSION['autorizacaoAdm']) == true): ?>
          <a href="./Adm/index.php">
            <div class="conteinerBotoesPequenos" >
              <img src="./index/assets/caderno.png" alt="JOGAR">
              <span>PAINEL ADM</span>
            </div>
          </a>
        <?php else: ?>
          <a href="./login/login.php">
            <div class="conteinerBotoesPequenos">
              <img src="./index/assets/caderno.png" alt="JOGAR">
              <span>ENTRAR ADM</span>
            </div>
          </a>
        <?php endif; ?>

      </div>
    </a>
  </div>
</body>

</html>