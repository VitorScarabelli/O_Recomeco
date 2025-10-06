<?php 
    include("../../banco/conexao.php");
    $nome = $_POST['cadNome'];
    $vantagem1 = $_POST['vantagem-1'] ;
    $vantagem2 = $_POST['vantagem-2'];
    $desvantagem1 = $_POST['desvantagem-1'] ;
    $desvantagem2 = $_POST['desvantagem-2'];
    $descri = $_POST['descPersona'];

    $sql = "INSERT INTO tbpersonagens (nomePersonagem, vantagem1personagem, vantagem2personagem, desvatagem1personagem, desvatagem2personagem, descricaoPersonagem) VALUES (:nome, :vantagem1, :vantagem2, :desvantagem1, :desvantagem2, :descri)";

    $stmt = $pdo->prepare($sql);

    $stmt->bindParam(':nome', $nome);
    $stmt->bindParam(':vantagem1', $vantagem1);
    $stmt->bindParam(':vantagem2', $vantagem2);
    $stmt->bindParam(':desvantagem1', $desvantagem1);
    $stmt->bindParam(':desvantagem2', $desvantagem2);
    $stmt->bindParam(':descri', $descri);

    $stmt-> execute();

    header("location:cadastroPersonagem.php");

?>