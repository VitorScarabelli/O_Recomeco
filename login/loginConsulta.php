<?php 
    session_start();
    include('../banco/conexao.php');

    //variaveis recuperadas lá do from
    $usuario = $_REQUEST['usuario'];
    $senha = $_REQUEST['senha'];

    //variaveis que serão usadas na verificação no banco 
    $usuarioBanco= 'admin';
    $senhaBanco = '12345';



    try {
        $stmt = $pdo -> prepare("SELECT nomeUsuario, senhaUsuario FROM tbusuario WHERE nomeUsuario='$nome' and senhaUsuario='$senha'");

        $stmt -> execute();

        while ($row = $stmt->fetch(PDO::FETCH_BOTH)) {
            $usuarioBanco = $row['nomeUsuario'];
            $senhaBanco = $row['senhaUsuario'];
        }

    } catch(PDOException $e){
        echo "ERRO: " . $e -> getMessage();
    }

    //validação do login
    if($email == $emailBanco && $senha == $senhaBanco){
        $_SESSION['autorizacao'] = true;
        $_SESSION['autorizacaoAdm'] = true;
        header("Location: ../index.php");
    } else {
        $_SESSION['autorizacao'] = false;
        unset($_SESSION['autorizacao']);
        session_destroy();
        header("Location: login.php");
    }

?>