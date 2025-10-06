<?php
//include('./includes/verificacao_session_usu.php.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro Evento</title>
    <link rel="stylesheet" href="./css/style.css" />
</head>

<body>

    <div class="ImgFundo">
        <img class="background" src="./KitComposiçãoCadastro/svg/CéuTardio.svg" alt="Fundo do jogo">
    </div>

    <!-- Botões do canto superior esquerdo -->
    <div class="top-left-buttons">
        <div class="btn-voltar">
            <a href="../index/index.php"><img src="./KitComposiçãoCadastro/svg/Buttons/BackButton.svg" alt="" width="70px" height="70px"></img></a>
            <a href="../index/index.php"><img src="./KitComposiçãoCadastro/svg/Buttons/HomeButton.svg" alt="" width="70px" height="70px"></img></a>
        </div>
    </div>

    <!-- Botões do canto superior direito -->
    <!-- <div class="top-right-buttons">
        <div class="btn-musica">
            <img src="./KitComposiçãoCadastro/svg/Buttons/MusicButton.svg" alt="Música" />
        </div>
        <div class="btn-config">
            <img src="./KitComposiçãoCadastro/svg/Buttons/ConfigButton.svg" alt="Configurações" />
        </div>
    </div> -->

    <div class="livro-cadastro-wrapper">
        <img src="./KitComposiçãoCadastro/png/LivroCadastro.png" alt="Livro de Cadastro" class="img-livro" />

        <form action="cadEvento.php" method="post" class="form-cadastro">
            <h2>CADASTRO DE EVENTOS</h2>

            <label>NOME DO EVENTO:</label>
            <input type="text" name="nome" placeholder="NOME DO EVENTO" required />

            <label>DESCRIÇÃO DO EVENTO:</label>
            <input type="text" name="descricao" placeholder="DESCRIÇÃO DO EVENTO" required />

            <label>TIPO DO EVENTO:</label>
            <select name="tipo" id="tipo" required>
                <option value="sus">SUS</option>
                <option value="desigualdade" selected>DESIGUALDADE</option>
                <option value="emprego">EMPREGO</option>
            </select>

            <div class="guia-preenchimento">
            <label>IMPACTO DO EVENTO:</label>
            <div class="radios">
                <input type="radio" name="modi" id="modiBom" value="bom" required /> <p>BOM</p>
                <input type="radio" name="modi" id="modiRuim" value="ruim" required /> <p>RUIM</p>
            </div>
            
            <label>QUANTIDADE DE CASAS:</label>
            <input type="number" name="quantCasas" placeholder="QUANTIDADE DE CASAS" required min="2" max="8"/>

            <?php
            if (isset($_GET['success'])) {
                echo '<p class="message success">CADASTRO REALIZADO COM SUCESSO!</p>';
            } elseif (isset($_GET['error'])) {
                echo '<p class="message error">ERRO AO CADASTRAR, VERIFIQUE OS DADOS E TENTE NOVAMENTE.</p>';
            }?>

            <button type="submit" class="btn-cadastrar">CADASTRAR</button>

            <div class="login-container">
                <p class="login-guia-texto">JÁ CADASTROU O EVENTO?</p>
                <p class="login-link"><a href="../tabuleiro/selecaoEventos.php">VOLTAR</a></p>
            </div>
        </div>
        </form>

        
    </div>

    <script src="./js/script.js"></script>
</body>

</html>