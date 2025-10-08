<?php include('../banco/conexao.php'); include('../includes/verificar_login.php');?>

<?php
if ($_POST) {
    $tituloConfiguracao = $_POST['tituloConfiguracao'] ?? '';
    $personagens = json_decode($_POST['personagens'], true);
    $temas = json_decode($_POST['temas'], true);
    $eventos = json_decode($_POST['eventos'], true);
    $eventosPersonagem = json_decode($_POST['eventosPersonagem'], true);
    
    // Validações
    if (empty($tituloConfiguracao)) {
        echo '<div class="alert alert-danger">❌ Título da configuração é obrigatório!</div>';
        exit;
    }
    
    // Salvar configuração na sessão ou banco de dados
    session_start();
    $_SESSION['configuracao_partida'] = [
        'titulo' => $tituloConfiguracao,
        'personagens' => $personagens,
        'temas' => $temas,
        'eventos' => $eventos,
        'eventosPersonagem' => $eventosPersonagem,
        'data_configuracao' => date('Y-m-d H:i:s')
    ];
    
    // Redirecionar para visualizar a partida
    header('Location: visualizarPartida.php');
    exit;
} else {
    header('Location: configurarPartida.php');
    exit;
}
?>
