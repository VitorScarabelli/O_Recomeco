<?php

    include("conexao.php"); 

    $id = $_GET['id'];    
    

    //echo "$nome $email $assunto $mensagem";

    
    $stmt = $pdo->prepare("delete from tbContato where idContato='$id';");	

    $stmt ->execute();

    header('location:index.php');
    

?>