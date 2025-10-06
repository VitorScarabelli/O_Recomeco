<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/login.css">
    <title>Login</title>
</head>
<body>
    <img src="./KitComposiçãoLogin/svg/CéuTardio.svg" alt="" class="background">

    <div class="left">
        <a href="../index/index.php"><img src="./KitComposiçãoLogin/svg/Botões/BackButton.svg" alt="" width="70px" height="70px"></a>
        <a href="../index/index.php"><img src="./KitComposiçãoLogin/svg/Botões/HomeButton.svg" alt="" width="70px" height="70px"></a>
    </div>

    <!-- <div class="right">
        <a href="#"><img src="./KitComposiçãoLogin/svg/Botões/MusicButton.svg" alt="" width="70px" height="70px"></a>
        <a href="#"><img src="./KitComposiçãoLogin/svg/Botões/ConfigButton.svg" alt="" width="70px" height="70px"></a>
    </div> -->

    <div class="container">
        <img src="./KitComposiçãoLogin/svg/LivroLogin.svg" alt="" class="img-livro">
        <h1 class="titulo">LOGIN</h1>

        <form action="loginConsulta.php" method="post" class="formulario">
            <label for="email" class="email">Email:</label>
            <div class="caixaTexto">
                <input type="email" name="email" id="email" placeholder="@gmail.com" required>
            </div>

            <br />

            <label for="senha" class="senha">Senha:</label>
            <div class="caixaTexto">
                <input type="password" name="senha" id="senha" placeholder="password" required>
            </div>

            <br />

            <div class="caixa-btn">
                <button type="submit" class="btn text-cadastro">CONFIRMAR</button>
            </div>
        </form>

        <div class="links">
            <p class="text-cadastro">Não possui uma conta ainda?</p>
            <a href="../cadastro/index/index.php" class="link-cadastro">Criar uma</a>

            <p class="text-cadastro" style="font-size: 25px;">
                Quer deslogar?
                <a href="./logoff.php" class="link-cadastro" style="font-size: 25px;">Deslogar</a>
            </p>
        </div>
    </div>
</body>
</html>
