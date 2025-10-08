<!DOCTYPE html>
<html lang="pt-BR">

<head>
  <meta charset="UTF-8">
  <title>Escolher Eventos</title>
  <link rel="stylesheet" href="./selecaoEventos.css">
  <link rel="stylesheet" href="./bootstrap/css/bootstrap.min.css">
  <script src="./bootstrap/js/bootstrap.bundle.min.js"></script>

  <style>
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      background-image: url('../index/assets/Anoitecer.png');
      background-size: cover;
      background-repeat: no-repeat;
      background-position: center;
    }

    /* ===== SELEÇÃO DE PERSONAGENS ===== */
    #personagem-section {
      text-align: center;
      margin-bottom: 5%;
    }

    #personagem-grid {
      display: grid;
      grid-template-columns: repeat(3, 150px);
      gap: 8%;
      justify-content: center;
    }

    .personagem {
      position: relative;
      width: 100%;
      height: 150px;
      max-width: 1000px;
      border-radius: 10px;
      overflow: hidden;
      cursor: pointer;
      border: 3px solid transparent;
      transition: transform 0.2s, border-color 0.2s;
      background-color: #e3f2fd;
      margin-bottom: 20%;
    }

    .personagem img {
      position: absolute;
      top: 0;
      left: 50%;
      /* centraliza horizontalmente */
      transform: translateX(-50%);
      width: 80%;
      /* ocupa 80% da largura */
      height: 100%;
      object-fit: cover;
    }

    .personagem-nome {
      position: absolute;
      bottom: 0;
      left: 50%;
      /* centraliza a tarja também */
      transform: translateX(-50%);
      width: 100%;
      background: rgba(0, 0, 0, 0.6);
      color: #fff;
      text-align: center;
      font-size: 14px;
      padding: 4px 0;
      border-bottom-left-radius: 10px;
      border-bottom-right-radius: 10px;
    }


    .personagem:hover {
      transform: scale(1.05);
      border-color: #888;
    }

    .personagem.selecionado {
      border-color: #2ecc71;
    }

    #personagem-info {
      margin-top: 20px;
      max-width: 800px;
      background: rgba(255, 255, 255, 0.85);
      border-radius: 8px;
      padding: 15px;
      text-align: left;
      margin-left: auto;
      margin-right: auto;
    }

    .personagem.selecionado {
      background-color: #4CAF50;
      /* verde */
      color: white;
      border-radius: 8px;
      transition: background-color 0.3s ease;
    }


    /* ===== ESTILO DOS RADIO BUTTONS ===== */
    .bloco-filtros {
      background-color: rgba(25, 173, 219, 1);
      padding: 20px 30px;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      color: white;
      max-width: 600px;
      margin: 40px auto;
      text-align: center;
      font-family: 'Arial', sans-serif;
      user-select: none;

      transition: background-color 0.3s, transform 0.2s, box-shadow 0.2s;
    }

    .filtros-titulo {
      display: none;
    }


    .filtros-eventos {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .filtros-eventos label {
      font-size: 16px;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 10px;
      cursor: pointer;
      transition: color 0.3s ease;
    }

    .filtros-eventos input[type="checkbox"] {
      width: 18px;
      height: 18px;
      cursor: pointer;
      accent-color: white;
      border-radius: 4px;
      border: 2px solid white;
      background-color: transparent;
      transition: background-color 0.3s ease, border-color 0.3s ease;
    }

    .filtros-eventos input[type="checkbox"]:checked {
      background-color: white;
      border-color: rgba(25, 173, 219, 1);
      ;
    }


    /* ===== ESTILO DOS BOTÕES ===== */
    button[type="submit"],
    #ver-eventos {
      margin: 20px auto;
      display: block;
      padding: 10px 20px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      border: none;
      border-radius: 8px;
      background-color: rgba(25, 173, 219, 1);
      ;
      color: white;
      transition: background-color 0.3s, transform 0.2s;
    }


    button[type="submit"]:active {
      transform: scale(0.97);
    }

    a.botao-simples {
      display: block;
      width: fit-content;
      margin: 20px auto;
      padding: 10px 20px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      text-decoration: none;
      border: none;
      border-radius: 8px;
      background-color: rgba(25, 173, 219, 1);
      ;
      color: white;
      transition: background-color 0.3s, transform 0.2s, box-shadow 0.2s;
    }

    a.botao-simples:active {
      transform: scale(0.97);
    }

    /* ===== ESTILO DOS EVENTOS ===== */
    .background {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      object-fit: cover;
      z-index: -1;
    }

    body {
      font-family: Arial, Helvetica, sans-serif;
    }

    .accordion {
      width: 80%;
      max-width: 800px;
      margin: 20px auto;
    }

    .accordion-item {
      background-color: rgba(255, 255, 255, 0.9);
      border-radius: 10px;
      margin-bottom: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
      overflow: hidden;
    }

    .accordion-header {
      padding: 15px 20px;
      cursor: pointer;
      font-weight: bold;
      color: #222;
      display: flex;
      justify-content: space-between;
      align-items: center;
      user-select: none;
    }

    .accordion-header:hover {
      background-color: #f0f0f0;
    }

    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.3s ease;
      padding: 0 20px;
    }

    .accordion-content.open {
      padding: 15px 20px;
    }

    .accordion-content p {
      margin: 5px 0;
      color: #444;
    }

    h1 {
      text-align: center;
      color: #fff;
      margin-top: 40px;
    }

    .eventostxt {
      text-align: center;
      color: #000000;
      margin-top: 20px;
    }

    .accordion {
      width: 90%;
      max-width: 1000px;
      margin: 0 auto 20px auto;
    }

    .accordion-item {
      background: #fff;
      border-radius: 10px;
      margin-bottom: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }

    .accordion-header {
      padding: 15px 20px;
      cursor: pointer;
      font-weight: bold;
      color: #000000ff;
      background: #fffdfdff;
      display: flex;
      justify-content: space-between;
      align-items: center;
      user-select: none;
    }

    .accordion-header span {
      transition: transform 0.3s;
    }

    .accordion-content {
      max-height: 0;
      overflow: hidden;
      transition: max-height 0.5s ease, padding 0.3s ease;
      padding: 0 20px;
      background: #fafafa;
    }

    .accordion-content.open {
      padding: 15px 20px;
    }

    .evento-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      justify-content: center;
    }

    .card {
      width: 250px;
      border-radius: 8px;
      padding: 12px;
      margin: 8px;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
      transition: transform 0.2s;
    }

    .card:hover {
      transform: scale(1.03);
    }

    .card-bom {
      background-color: #e3f2fd;
      /* azul claro */
      border: 2px solid #2196f3;
      color: #0d47a1;
    }

    .card-ruim {
      background-color: #ffebee;
      /* vermelho claro */
      border: 2px solid #f44336;
      color: #b71c1c;
    }

    .card-body {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .nuvens {
      position: relative;
      display: flex;
      justify-content: center;
      align-items: center;
      margin-bottom: 2%;
      /* espaço abaixo */
    }

    .nuvens img {
      width: 50%;
      display: block;
    }

    .nuvens .titulo {
      position: absolute;
      top: 60%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 1.5rem;
      font-weight: 900;
      color: #416770;
      text-align: center;
      font-family: 'Quache', sans-serif;
      background: none;
      /* tira o fundo vermelho */
    }

    /* ==================== SEÇÃO DE JOGADORES ==================== */
    .players-section {
      background: linear-gradient(135deg, var(--white), var(--cream));
      border: 2px solid var(--yellow);
      border-radius: 15px;
      padding: 30px;
      margin-bottom: 40px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .scroll-notice {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
      margin-bottom: 25px;
      padding: 12px 20px;
      background: linear-gradient(135deg, var(--primary-blue), var(--dark-blue));
      color: var(--cream);
      border-radius: 15px;
      box-shadow: 0 4px 15px rgba(0, 76, 117, 0.3);
      font-weight: 600;
      font-size: 1.2em;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
      user-select: none;
    }

    .scroll-icon {
      font-size: 2em;
      animation: bounce 2s ease-in-out infinite;
      color: var(--yellow);
    }

    @keyframes bounce {

      0%,
      100% {
        transform: translateY(0);
      }

      50% {
        transform: translateY(-6px);
      }
    }

    .players-title {
      color: white;
      text-align: center;
      font-weight: 900;
      font-size: 1.6rem;
      margin-top: 50px;
      /* desce mais o título */
      margin-bottom: 20px;
      text-transform: uppercase;
      letter-spacing: 2px;
    }

    .counter-labels {
      display: flex;
      justify-content: center;
      gap: 25px;
      max-width: 300px;
      margin: -5px auto 0 auto;
      /* 🔼 sobe os números */
    }

    .counter-labels span {
      color: #1e3a8a;
      font-weight: bold;
      font-size: 1.1rem;
      line-height: 1;
      /* evita espaçamento vertical extra */
    }



    /* Container geral com fundo branco pastel, borda dourada, sombra e espaçamento */
    .players-section {
      width: calc(100% - 30px);
      /* ou outro valor baseado no pai */
      max-width: 1015px;
      /* por exemplo, se o anterior era 1000px */
      padding: 30px;
      background-color: #fdfdfd;
      border: 1px solid gold;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
      margin: 60px auto 30px auto;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
    }

    .players-intro-text {
      color: #1e3a8a;
      /* azul forte */
      font-weight: 500;
      /* menos grosso */
      font-size: 1.2rem;
      /* menor que antes */
      text-align: center;
      margin-bottom: 15px;
      text-transform: uppercase;
      letter-spacing: 1px;
    }

    /* Frase principal sem container extra, texto azul */
    .players-section>.scroll-notice {
      color: #1e3a8a;
      /* azul forte */
      font-weight: 600;
      /* ⬅️ ficou um pouco mais leve que 900 */
      font-size: 1.2rem;
      text-align: center;
      margin-bottom: 25px;
    }


    /* Container dos botões e contador */
    .players-counter {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 20px;
      margin-bottom: 15px;
      margin-top: 20px !important;
    }

    /* Botões com círculo branco e borda azul */
    .counter-btn {
      width: 54px;
      height: 54px;
      border: 2px solid #1e3a8a;
      /* mesma cor azul do texto */
      background: white;
      color: #1e3a8a;
      font-size: 1.8em;
      border-radius: 50%;
      cursor: pointer;
      transition: all 0.3s ease;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      box-shadow: 0 0 0 6px white;
      /* círculo branco extra em volta */
    }

    /* Hover: aumenta, inverte cores, sombra */
    .counter-btn:hover:not(:disabled) {
      background: #1e3a8a;
      color: white;
      transform: scale(1.15);
      box-shadow: 0 0 15px #1e3a8a;
    }

    /* Disabled */
    .counter-btn:disabled {
      opacity: 0.3;
      cursor: not-allowed;
    }

    /* Número do contador */
    .counter-display {
      text-align: center;
      min-width: 120px;
      font-size: 2.5rem;
      font-weight: bold;
      position: relative;
      top: 4px;
      /* desce o número um pouco */
      color: #1e3a8a;
    }

    /* Textos menores e labels */
    .counter-label-text {
      font-size: 0.8rem;
      color: #1e3a8a;
      margin-top: 2px;
      text-align: center;
    }

    .scroll-notice {
      margin-top: 30px;
      background-color: white;
      color: #1e3a8a;
      font-weight: 400;
      /* menos grosso */
      font-size: 1.2rem;
      /* menor que antes */
      padding: 8px 15px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(30, 58, 138, 0.15);
      max-width: 1015px;
      /* igual ao container de cima */
      width: 100%;
      margin-left: auto;
      margin-right: auto;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
    }

    .selected-container {
      background-color: #1e3a8a;
      color: white;
      padding: 5px 15px;
      border-radius: 10px;
      font-weight: 700;
      font-size: 1.1rem;
      font-weight: 400;
      white-space: nowrap;
      box-shadow: 0 0 10px rgba(30, 58, 138, 0.4);
    }


    @font-face {
      font-family: 'Quache';
      src: url('./assets/Quache-HeavyExExp_PERSONAL.ttf') format('truetype');
      font-weight: 900;
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
      z-index: 1000;
    }
    
    .back-btn:hover {
      background: rgba(52, 73, 94, 1);
      color: white;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <?php
  include("../banco/conexao.php");
  $stmt = $pdo->query("SELECT * FROM tbevento");

  // --- Build events grouped by difficulty (put this after include("../banco/conexao.php"); ) ---

  // Chaves que queremos mostrar e rótulos
  $dificuldades = [
    'facil'   => 'Fácil',
    'medio'   => 'Médio',
    'dificil' => 'Difícil',
    'extremo' => 'Extremo',
  ];

  // inicializa com arrays vazios para evitar "undefined variable"
  $eventosPorDificuldade = array_fill_keys(array_keys($dificuldades), []);

  // pega todos os eventos de uma vez
  try {
    $stmtAll = $pdo->query("SELECT * FROM tbevento");
    while ($row = $stmtAll->fetch(PDO::FETCH_ASSOC)) {
      // normaliza o valor da coluna dificuldadeEvento
      $raw = trim((string)($row['dificuldadeEvento'] ?? ''));
      $key = mb_strtolower($raw, 'UTF-8');

      // mapeamentos correntes (aceita variações como "Fácil", "facil", "fácil" etc)
      if ($key === 'fácil' || $key === 'facil') $key = 'facil';
      elseif ($key === 'médio' || $key === 'medio') $key = 'medio';
      elseif ($key === 'difícil' || $key === 'dificil') $key = 'dificil';
      elseif ($key === 'extremo') $key = 'extremo';

      // se não bater com nenhuma chave conhecida, joga em 'outros' (opcional)
      if (!array_key_exists($key, $eventosPorDificuldade)) {
        if (!isset($eventosPorDificuldade['outros'])) $eventosPorDificuldade['outros'] = [];
        $eventosPorDificuldade['outros'][] = $row;
      } else {
        $eventosPorDificuldade[$key][] = $row;
      }
    }
  } catch (PDOException $e) {
    // em caso de erro de query, mantém array vazio e loga (ou trate como preferir)
    error_log("Erro ao buscar eventos: " . $e->getMessage());
    $eventosPorDificuldade = array_fill_keys(array_keys($dificuldades), []);
  }

  ?>


    <a href="../Adm/index.php" class="back-btn">← Voltar ao Admin</a>
    
    <form method="POST" action="./tb.php">
    <div class="nuvens">
      <img src="./imageTabuleiro/nuvens.png" alt="Nuvem">
      <h2 class="titulo">SELECIONE DE 2 ATÉ 4 PERSONAGENS</h2>
    </div>

    <div id="personagem-section">

      <!-- <label>Quantidade de jogadores:
        <input type="number" id="qtd-jogadores" min="1" max="4" value="1">
      </label> -->

      <div id="personagem-grid">
        <!-- Personagens (mantive todos) -->
        <div class="personagem"
          data-id="1"
          data-nome="Idoso"
          data-emoji="👴"
          data-desc="Uma pessoa com muita experiência de vida, mas com limitações físicas."
          data-eventopos1="Lembra de um atalho: Por viver muito no mesmo lugar você sabe de alguns atalhos. (avança 2 casas)"
          data-eventopos2="Deus ajuda quem cedo madruga: Dorme cedo e acorda cedo, sempre pronto para o dia. (avança 2 casas)"
          data-eventoneg1="Dor nas costas: Já não tem a mesma resistência física de alguns anos atrás e suas costas doem. (volta 2 casa)"
          data-eventoneg2="Você perdeu seus óculos: Sua visão piorou com o tempo e você vive perdendo seus óculos. (volte 2 casas)">
          <img src="./imageTabuleiro/idoso.png" alt="Idoso">
          <span class="personagem-nome">IDOSO</span>
        </div>

        <div class="personagem"
          data-id="2"
          data-nome="Cego"
          data-emoji="👨‍🦯"
          data-desc="A vida te deu um desafio a mais, mas você não abaixou sua cabeça"
          data-eventopos1="Local com acessibilidade Você tem um tato muito aguçado, percebe detalhes que os outros não percebem. (avança 2 casas)"
          data-eventopos2="Memoria muscular: Mesmo sem enxergar, você é muito bom em se localizar nos lugares que já conhece. (avança 2 casas)"
          data-eventoneg1="Na maldade: Existem pessoas más no mundo, e uma delas te levou pra um caminho contrario ao que você queria. (volta 2 casas)"
          data-eventoneg2="bengala quebrada: Em uma das suas andanças, você trombou em alguem e sua bengala quebrou. (volta 2 casas)">
          <img src="./imageTabuleiro/cego.png" alt="Cego">
          <span class="personagem-nome">CEGO</span>
        </div>

        <div class="personagem"
          data-id="3"
          data-nome="Mulher Negra"
          data-emoji="👩🏽‍🦱"
          data-desc="Uma mulher que tem orgulho da sua cor, alguém que quer derrubar o preconceito."
          data-eventopos1="Em pé de igualdade: Lutando pelos seus direitos você recebe um salário melhor, sem reduções 'por ser mulher'. (avança 2 casas)"
          data-eventopos2="Cria do gueto: Você cresceu em um ambiente difícil, mas isso te fez mais forte. (avança 2 casas)"
          data-eventoneg1="Racismo: Mesmo sendo alguém legal, as pessoas ainda te julgam pela sua cor. (volta 2 casas)"
          data-eventoneg2="Machismo estrutural: Só por ser mulher, você não é levada tão a sério quanto os homens como deveria. (volta 2 casas)">
          <img src="./imageTabuleiro/negra.png" alt="MulherNegra">
          <span class="personagem-nome">MULHER NEGRA</span>
        </div>

        <div class="personagem"
          data-id="4"
          data-nome="Retirante"
          data-emoji="🧳"
          data-desc="Um viajante humilde que deixou sua terra natal em busca de novas oportunidades."
          data-eventopos1="Bom das pernas: Acostumado a longas caminhadas, suporta jornadas mais longas. (Avança 2 casas)"
          data-eventopos2="Carismatico: Tem uma boa habilidade em pedir alimento aos outros quando precisa. (Avança 2 casa)"
          data-eventoneg1="Novo na cidade: Sem conhecer muito as coisas onde você mora agora você se perde. (volta 2 casas)"
          data-eventoneg2="Saudade de casa: A saudade de casa abala te deixa triste em muitos momentos. (volta 2 casas)">
          <img src="./imageTabuleiro/retirante.png" alt="Retirante">
          <span class="personagem-nome">RETIRANTE</span>
        </div>

        <div class="personagem"
          data-id="5"
          data-nome="Mulher Trans"
          data-emoji="🌈"
          data-desc="Uma mulher que teve a coragem de ser quem realmente é."
          data-eventopos1="Sem medo: A coragem que te fez ser quem você é, te faz superar qualquer desafio (avança 2 casas)."
          data-eventopos2="Autoconfiança: Você sabe quem você é, e isso te dá uma confiança que poucos tem. (avança 2 casa)"
          data-eventoneg1="Transfobia: Você sofre preconceito por entrar em espaços que não são considerados 'seu lugar'. (volta 2 casas)"
          data-eventoneg2="Discriminação de gênero: Ninguem confia em você, por isso poucas pessoas te dão oportunidades de emprego. (volta 2 casas)">
          <img src="./imageTabuleiro/trans.png" alt="MulherTrans">
          <span class="personagem-nome">MULHER TRANS</span>
        </div>

        <div class="personagem"
          data-id="6"
          data-nome="Umbandista"
          data-emoji="👳🏽‍♂️"
          data-desc="Alguém que segue a religião de Umbanda, buscando sempre o equilíbrio e a paz no caminho de seus orixás."
          data-eventopos1="Sabedoria empática: Conhecimento espiritual e habilidades sociais para lidar com diferentes pessoas. (avança 1 casa)"
          data-eventopos2="Proteção dos orixás: Capacidade de manter calma e equilíbrio em situações de tensão. (avança 2 casas)"
          data-eventoneg1="Intolerencia religiosa: Sofre discriminação religiosa em alguns ambientes. (volta 2 casas)"
          data-eventoneg2="Estereótipos: Poucos se aproximam por medo de serem alvos de 'macumba'. (volta 2 casas)">
          <img src="./imageTabuleiro/umbandista.png" alt="Umbandista">
          <span class="personagem-nome">UMBANDISTA</span>
        </div>
      </div>

      <!-- <div id="personagem-info">
        <h3>Clique com o mouse sobre um personagem</h3>
        <p>Descrição aparecerá aqui</p>
      </div> -->
    </div>

    <!-- Hidden fields -->
    <input type="hidden" name="qtd_jogadores" id="hidden-qtd-jogadores" value="1">
    <input type="hidden" name="personagens" id="hidden-personagens" value="">


    <!-- Accordion container -->
    <div class="nuvens">
      <img src="./imageTabuleiro/nuvens.png" alt="Nuvem">
      <h2 class="titulo"> SELECIONE OS EVENTOS:</h2>
    </div>

    <!-- Accordion principal -->
    <div class="filtros-eventos">
      <label>
        <input type="checkbox" name="filtros[]" value="sus"> SUS
      </label>
      <label>
        <input type="checkbox" name="filtros[]" value="desigualda"> DESIGUALDADE
      </label>
      <label>
        <input type="checkbox" name="filtros[]" value="emprego"> EMPREGO
      </label>
    </div>



    <!-- Botões gerais -->
    <button type="submit" name="modoAleatorio" value="1">Atribuir Casas Aleatórias</button>
    <a href="../Adm/cadastrarEvento.php">CADASTRAR NOVO EVENTO</a>
  </form>
  <script>
    const minJogadores = 2; // mínimo
    const maxJogadores = 4; // máximo
    const personagens = document.querySelectorAll(".personagem");
    const infoBox = document.getElementById("personagem-info");
    const form = document.querySelector("form");
    let selecionados = [];

    personagens.forEach(p => {
      p.addEventListener("click", () => {
        if (p.classList.contains("selecionado")) {
          // desmarcar
          p.classList.remove("selecionado");
          selecionados = selecionados.filter(s => s !== p);
          infoBox.innerHTML = "<h3>Clique com o mouse sobre um personagem</h3><p>Descrição aparecerá aqui</p>";
        } else {
          if (selecionados.length < maxJogadores) {
            p.classList.add("selecionado");
            selecionados.push(p);
          } else {
            alert(`Você só pode selecionar até ${maxJogadores} personagens.`);
            return;
          }
        }

        // Atualiza info do personagem clicado
        infoBox.innerHTML = `
        <h3>${p.dataset.nome}</h3>
        <p>${p.dataset.desc}</p>
        <p><strong>Eventos Positivos:</strong> <br> ${p.dataset.eventopos1} <br> ${p.dataset.eventopos2}</p><br>
        <p><strong>Eventos Negativos:</strong> <br> ${p.dataset.eventoneg1} <br> ${p.dataset.eventoneg2}</p><br>
      `;
      });
    });



    // Antes de enviar o form, atualizar os hidden fields
    form.addEventListener("submit", e => {
      if (selecionados.length < minJogadores || selecionados.length > maxJogadores) {
        e.preventDefault();
        alert(`Selecione entre ${minJogadores} e ${maxJogadores} personagens antes de continuar!`);
      } else {
        document.getElementById("hidden-qtd-jogadores").value = selecionados.length;
        const personagensData = selecionados.map(p => ({
          idPersonagem: p.dataset.id,
          nomePersonagem: p.dataset.nome,
          emoji: p.dataset.emoji,
          desc: p.dataset.desc,
          eventosPos: [p.dataset.eventopos1, p.dataset.eventopos2],
          eventosNeg: [p.dataset.eventoneg1, p.dataset.eventoneg2]
        }));

        const jsonData = JSON.stringify(personagensData);
        document.getElementById("hidden-personagens").value = jsonData;

        // Log para debug
        console.log("Personagens selecionados:", personagensData);
        console.log("JSON enviado:", jsonData);
        console.log("Quantidade de personagens:", selecionados.length);
      }
    });
  </script>

  <script>
    document.querySelectorAll('.accordion-header').forEach(header => {
      header.addEventListener('click', () => {
        const content = header.nextElementSibling;
        const isOpen = content.classList.contains('open');

        // Fecha todos os outros
        document.querySelectorAll('.accordion-content').forEach(c => {
          c.style.maxHeight = null;
          c.classList.remove('open');
        });

        if (!isOpen) {
          content.classList.add('open');
          content.style.maxHeight = content.scrollHeight + "px";
        }
      });
    });
  </script>

</body>

</html>