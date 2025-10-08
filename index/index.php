<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Recomeço</title>
  <link rel="stylesheet" href="css/style.css">
</head>

<body>

  <div class="botoesAcima">
    <a href="../Adm/index.php"><img class="botaoPequeno" src="assets/Usuario.png" alt="Configurações"></a>
  </div>

  <div class="logo">
    <img src="assets/RecomecoLogo.png" alt="Logo">
  </div>

  <div class="botoesMenu">
    <a href="../selecionarPartida.php">
      <div class="conteinerBotoesPequenos">
        <img src="assets/caderno.png" alt="JOGAR">
        <span>JOGAR</span>
      </div>
    </a>

    <!-- Exemplo de botão extra
    <a href="../jogoPersonalizado/selecaoEventos.php">
      <div class="conteinerBotoesPequenos">
        <img src="assets/cadernodois.png" alt="CUSTOM.">
        <span>CUSTOM.</span>
      </div>
    </a> -->

    <a href="../login/login.php">
      <div class="conteinerBotoesPequenos">
        <?php
        session_start();
        ?>

        <?php if (isset($_SESSION['autorizacaoAdm']) == true): ?>
          <a href="../Adm/index.php">
            <div class="conteinerBotoesPequenos" >
              <img src="assets/caderno.png" alt="JOGAR">
              <span>Painel Admin</span>
            </div>
          </a>
        <?php else: ?>
          <a href="../login/login.php">
            <div class="conteinerBotoesPequenos">
              <img src="assets/caderno.png" alt="JOGAR">
              <span>Login ADM</span>
            </div>
          </a>
        <?php endif; ?>

      </div>
    </a>
  </div>

  <script>
    function toggleBold() {
      const body = document.body;
      const button = document.getElementById('toggle-bold');

      if (body.classList.contains('bold-mode')) {
        body.classList.remove('bold-mode');
        button.textContent = '📝 NEGRITO';
        localStorage.setItem('boldMode', 'false');
      } else {
        body.classList.add('bold-mode');
        button.textContent = '📝 NORMAL';
        localStorage.setItem('boldMode', 'true');
      }
    }

    // Aplicar modo salvo ao carregar a página
    window.addEventListener('load', function() {
      const boldMode = localStorage.getItem('boldMode') === 'true';
      const button = document.getElementById('toggle-bold');

      if (boldMode) {
        document.body.classList.add('bold-mode');
        button.textContent = '📝 NORMAL';
      }
    });
  </script>

</body>

</html>