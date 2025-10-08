<?php 
    session_start();
    include('../banco/conexao.php');

    //variaveis recuperadas lá do from
    $email = $_REQUEST['email'];
    $senha = $_REQUEST['senha'];

    //variaveis que serão usadas na verificação no banco 
    $emailBanco = 'adm@gmail.com';
    $senhaBanco = '12345';



    try {
        $stmt = $pdo -> prepare("SELECT emailUsuario, senhaUsuario FROM tbusuario WHERE emailUsuario='$email' and senhaUsuario='$senha'");

        $stmt -> execute();

        while ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
            $emailBanco = $row['emailUsuario'];
            $senhaBanco = $row['senhaUsuario'];
        }

    } catch(PDOException $e){
        echo "ERRO: " . $e -> getMessage();
    }

    //validação do login
    if($email == $emailBanco && $senha == $senhaBanco){
        $_SESSION['autorizacao'] = true;
        $_SESSION['autorizacaoAdm'] = true;
        header("Location: ../index/index.php");
    } else {
        $_SESSION['autorizacao'] = false;
        unset($_SESSION['autorizacao']);
        session_destroy();
        header("Location: login.php");
    }

?>