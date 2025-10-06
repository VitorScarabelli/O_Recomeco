<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Cadastro</title>
    <link rel="stylesheet" href="./CSS/index.css" />
</head>
<body>

    <div class="ImgFundo">
        <img class="background" src="../KitComposiçãoCadastro/svg/CéuTardio.svg" alt="Fundo do jogo">
    </div> 

   <!-- Botões do canto superior esquerdo -->
    <div class="top-left-buttons">
        <div class="btn-voltar">    
            <a href="../../login/login.php"><img src="../KitComposiçãoCadastro/svg/Buttons/BackButton.svg" alt="" width="70px" height="70px"></img></a>
            <a href="../../index/index.php"><img src="../KitComposiçãoCadastro/svg/Buttons/HomeButton.svg" alt="" width="70px" height="70px"></img></a>
        </div>
    </div>

    <!-- Botões do canto superior direito -->
    <!-- <div class="top-right-buttons">
        <div class="btn-musica">
            <img src="../KitComposiçãoCadastro/svg/Buttons/MusicButton.svg" alt="Música" />
        </div>
        <div class="btn-config">
            <img src="../KitComposiçãoCadastro/svg/Buttons/ConfigButton.svg" alt="Configurações" />
        </div>
    </div> -->

    <div class="livro-cadastro-wrapper">
        <img src="../KitComposiçãoCadastro/png/LivroCadastro.png" alt="Livro de Cadastro" class="img-livro" />

        <form action="cadusuario.php" method="post" class="form-cadastro">
            <h2>CADASTRO</h2>

            <label>Nome:</label>
            <input type="text" name="nome" placeholder="Nome completo" required />

            <label>Email:</label>
            <input type="email" name="email" placeholder="Seu email" required />

            <label>Senha:</label>
            <input type="password" name="senha" placeholder="Senha" required />

            <label>Confirmação de Senha:</label>
            <input type="password" name="confirmaSenha" placeholder="Confirmar senha" required />

             <?php  
                 if (isset($_GET['success'])) {  
                    echo '<p class="message success">Cadastro realizado com sucesso!</p>';  
                 } elseif (isset($_GET['error'])) {  
                    echo '<p class="message error">Erro ao cadastrar, verifique os dados e tente novamente.</p>';  
                 } elseif (isset($_GET['senhaerrada'])) {  
                    echo '<p class="message error">As senhas não coincidem!</p>';  
                 }  
            ?>

            <button type="submit" class="btn-cadastrar">CADASTRAR</button>
        </form>

        <div class="guia-preenchimento">
            <h3>Como preencher:</h3>
            <img src="../KitComposiçãoCadastro/svg/ComoPreencher.svg" alt="Guia de preenchimento" />

            <div class="login-container">
                <p class="login-guia-texto">Caso já possua uma conta?</p>
                <p class="login-link"><a href="login.php">Entrar</a></p>
            </div>
        </div>
    </div>
    
    <script src="../index/JS/index.js"></script>
</body>
</html>
