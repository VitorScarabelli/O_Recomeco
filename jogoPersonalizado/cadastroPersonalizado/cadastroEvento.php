    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="../css-teste/jogoPersonalizado-Teste.css">
    </head>
    <body>
        <h1 class="titulo">Cadastre um novo Evento !</h1>

        <section class="Container">

            <div class="form">
                <form action="cadEvento.php" method="post" class="formuEvento">

                    <label class="texto">Nome do Evento</label>
                        <input type="text" name="cadNome" id="nome" class="inputTxt">

                    <label class="texto">Descrição do Evento </label>
                        <textarea name="descEvento" id="descriEvento"></textarea>

                    <label class="texto">Modificador do Evento </label>
                        <input type="text" name="modEvento" id="modEve" class="inputTxt">

                    <label class="texto">Dificuldade do evento</label>
                        <div class="linha"></div>
                        <div class="rad">
                            <input type="radio" name="difEvento" value="facil" id="facil" class="radio"> <Span>Facil</Span>
                            <input type="radio" name="difEvento" value="medio" id="medio" class="radio"> <Span>Medio</Span>
                            <input type="radio" name="difEvento" value="dificil" id="dificil" class="radio"> <Span>Dificil</Span>
                            <input type="radio" name="difEvento" value="extremo" id="extremo" class="radio"> <Span>Extremo</Span>
                        </div>
                        <div class="linha"></div>

                    <label class="texto">Tipo do Evento</label>
                        <div class="linha"></div>
                        <div class="rad">
                            <input type="radio" name="tipoEvento" value="bom" id="benigno" class="radio"> <span>Benigno</span>
                            <input type="radio" name="tipoEvento" value="ruim" id="maligno" class="radio"> <span>Maligno</span>
                        </div>
                        <div class="linha"></div>  
                    <input type="submit" value="Criar Evento" class="botaoLogin" >


                </form>
            </div>

        </section>
        <?php 
            include('../../banco/conexao.php');
        ?>
    </body>
    </html>