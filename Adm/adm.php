<?php include('../banco/conexao.php'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>

</head>
<body>

        <table class="table">
        <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">Nome</th>
                <th scope="col">Descrição</th>
                <th scope="col">Efeito</th>
                <th scope="col">Consequência</th>
            </tr>
        </thead>
        <tbody>
    <?php  
        $stmt = $pdo->prepare("select * from tbEvento");	
        $stmt ->execute();
        
        while($row = $stmt ->fetch(PDO::FETCH_BOTH)){  
    ?>    

            <tr>
                <th scope="row"> <?php echo $row[0] ?> </th>
                <td> <?php echo $row[1] ?> </td>
                <td> <?php echo $row[2] ?> </td>
                <td> <?php echo $row[3] ?> </td>
                <td> <?php echo $row[5] ?> </td>
                
                <td>
                    <a href="./excluir_contato.php?id=<?php echo $row[0] ?>"> Excluir </a>
                </td>
            </tr>  

                   
              
        <?php }	?>  
        
        </tbody>
        </table>
    
</body>
</html>