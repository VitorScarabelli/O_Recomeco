<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
        <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="../css-teste/jogoPersonalizado-Teste.css">
    </head>
    <body>
        <h1 class="titulo">Cadastre um novo Personagem !</h1>

        <section class="Container">

            <div class="form">
                <form action="cadPersona.php" method="post" class="formuEvento">

                    <label class="texto">Nome do Personagem</label>
                        <input type="text" name="cadNome" id="nome" class="inputTxt" placeholder="Pessoa Idosa">

                    <label class="texto">Vantagem 1: </label>
                        <input type="text" name="vantagem-1" id="vantagem" class="inputTxt" placeholder="INSS...">

                        
                    <label class="texto">Vantagem 2: </label>
                        <input type="text" name="vantagem-2" id="vantagem2" class="inputTxt" placeholder="Preferencial...">

                        
                    <label class="texto">Desvantagem 1: </label>
                        <input type="text" name="desvantagem-1" id="desvantagem" class="inputTxt" placeholder="Saúde Fragil...">

                        
                    <label class="texto">Desantagem 2: </label>
                        <input type="text" name="desvantagem-2" id="desvantagem2" class="inputTxt" placeholder="Ingênuo...">

                        
                    <label class="texto">Descrição do Personagem </label>
                        <textarea name="descPersona" id="descriEvento"></textarea>

                    
                    <input type="submit" value="Criar Personagem" class="botaoLogin" >


                </form>
            </div>

        </section>
        <?php 
            include('../../banco/conexao.php');
        ?>
    </body>
    </html>
</body>
</html>