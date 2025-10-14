<?php 
    include("../../banco/conexao.php");
    $nome = $_POST['cadNome'];
    $descri = ($_POST['descEvento']) ;
    $modificacao = strtolower($_POST['modEvento']);
    $dificuldade = $_POST['difEvento'];
    $tipo = $_POST['tipoEvento'];

    $sql = "INSERT INTO tbevento (nomeEvento, descricaoEvento, modificadorEvento, dificuldadeEvento, impactoEvento) VALUES (:nome, :descri, :modificacao, :dificuldade, :tipo)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':descri', $descri);
    $stmt->bindParam(':modificacao', $modificacao);
    $stmt->bindParam(':dificuldade', $dificuldade);
    $stmt->bindParam(':tipo', $tipo);

    $stmt-> execute();

    header("location:cadastroEvento.php");

?>