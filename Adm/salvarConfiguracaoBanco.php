<?php 
include('../includes/verificar_login.php');
include('../banco/conexao.php');
session_start();

if (!isset($_SESSION['configuracao_partida'])) {
    header('Location: configurarPartida.php');
    exit;
}

$personagens = json_decode($_POST['personagens'], true);
$temas = json_decode($_POST['temas'], true);
$eventos = json_decode($_POST['eventos'], true);
$eventosPersonagem = json_decode($_POST['eventosPersonagem'], true);

$configuracao = [
    'titulo' => $_POST['tituloConfiguracao'] ?? "Partida_" . date('d-m-Y_H-i'),
    'personagens' => $personagens,
    'temas' => $temas,
    'eventos' => $eventos,
    'eventosPersonagem' => $eventosPersonagem
];

// Gerar atribuição aleatória de casas
function atribuirCasasAleatorias($eventos, $eventosPersonagem) {
    $casasOcupadas = [];
    $eventosComCasas = [];
    
    // Função para encontrar casa disponível
    function encontrarCasaDisponivel($casasOcupadas) {
        do {
            $casa = rand(1, 40); // Tabuleiro tem 40 casas
        } while (in_array($casa, $casasOcupadas));
        return $casa;
    }
    
    // Atribuir casas para eventos gerais
    foreach ($eventos as $eventoId) {
        $casa = encontrarCasaDisponivel($casasOcupadas);
        $casasOcupadas[] = $casa;
        $eventosComCasas[] = [
            'id' => $eventoId,
            'casa' => $casa,
            'tipo' => 'geral'
        ];
    }
    
    // Atribuir casas para eventos dos personagens
    foreach ($eventosPersonagem as $eventoPersonagem) {
        $casa = encontrarCasaDisponivel($casasOcupadas);
        $casasOcupadas[] = $casa;
        $eventosComCasas[] = [
            'id' => $eventoPersonagem['id'],
            'casa' => $casa,
            'tipo' => 'personagem',
            'personagem' => $eventoPersonagem['personagem']
        ];
    }
    
    return $eventosComCasas;
}

$eventosCasas = atribuirCasasAleatorias($eventos, $eventosPersonagem);

// Salvar no banco de dados
try {
    $nomeConfiguracao = $configuracao['titulo'] ?? "Partida_" . date('d-m-Y_H-i');
    
    $stmt = $pdo->prepare("INSERT INTO tbConfiguracaoPartida (nomeConfiguracao, personagens, eventosPersonagem, temasSelecionados, eventosSelecionados, eventosCasas) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $nomeConfiguracao,
        json_encode($personagens),
        json_encode($eventosPersonagem),
        json_encode($temas),
        json_encode($eventos),
        json_encode($eventosCasas)
    ]);
    
    $idConfiguracao = $pdo->lastInsertId();
    
    // Atualizar sessão com ID da configuração
    $_SESSION['configuracao_partida']['id'] = $idConfiguracao;
    $_SESSION['configuracao_partida']['eventosCasas'] = $eventosCasas;
    
    header('Location: visualizarPartida.php?salvo=1');
    exit;
    
} catch (PDOException $e) {
    echo "Erro ao salvar configuração: " . $e->getMessage();
}
?>
