<?php 
include('../includes/verificar_login.php');
include('../banco/conexao.php');
session_start();

if (!isset($_SESSION['configuracao_partida'])) {
    header('Location: configurarPartida.php');
    exit;
}

$configuracao = $_SESSION['configuracao_partida'];
$personagens = $configuracao['personagens'];
$temas = $configuracao['temas'];
$eventos = $configuracao['eventos'];

// Gerar eventos dos personagens automaticamente
function gerarEventosPersonagemAutomaticos($personagens, $pdo) {
    $eventosPersonagem = [];
    
    foreach ($personagens as $personagem) {
        $idPersonagem = $personagem['id'];
        
        // Buscar todos os eventos do personagem
        $stmt = $pdo->prepare("SELECT * FROM tbeventopersonagem WHERE idPersonagem = ?");
        $stmt->execute([$idPersonagem]);
        $eventosPersonagemDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Adicionar todos os eventos do personagem
        foreach ($eventosPersonagemDB as $evento) {
            $eventosPersonagem[] = [
                'id' => $evento['idEvento'],
                'personagem' => $idPersonagem
            ];
        }
    }
    
    return $eventosPersonagem;
}

// Gerar código único tipo Kahoot
function gerarCodigoPartida($pdo) {
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $codigo = '';
    
    do {
        $codigo = '';
        for ($i = 0; $i < 6; $i++) {
            $codigo .= $caracteres[rand(0, strlen($caracteres) - 1)];
        }
        
        // Verificar se o código já existe
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM tbconfiguracaopartida WHERE codigoPartida = ?");
        $stmt->execute([$codigo]);
        $existe = $stmt->fetchColumn() > 0;
    } while ($existe);
    
    return $codigo;
}

$eventosPersonagem = gerarEventosPersonagemAutomaticos($personagens, $pdo);

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
    
    // Verificar se é uma edição (tem ID) ou criação nova
    if (isset($configuracao['id']) && !empty($configuracao['id'])) {
        // É uma edição - fazer UPDATE
        $idConfiguracao = $configuracao['id'];
        
        // Buscar o código existente para manter
        $stmt = $pdo->prepare("SELECT codigoPartida FROM tbConfiguracaoPartida WHERE idConfiguracao = ?");
        $stmt->execute([$idConfiguracao]);
        $partidaExistente = $stmt->fetch(PDO::FETCH_ASSOC);
        $codigoPartida = $partidaExistente['codigoPartida'];
        
        $stmt = $pdo->prepare("UPDATE tbConfiguracaoPartida SET nomeConfiguracao = ?, personagens = ?, eventosPersonagem = ?, temasSelecionados = ?, eventosSelecionados = ?, eventosCasas = ? WHERE idConfiguracao = ?");
        $stmt->execute([
            $nomeConfiguracao,
            json_encode($personagens),
            json_encode($eventosPersonagem),
            json_encode($temas),
            json_encode($eventos),
            json_encode($eventosCasas),
            $idConfiguracao
        ]);
    } else {
        // É uma criação nova - fazer INSERT
        $codigoPartida = gerarCodigoPartida($pdo);
        
        $stmt = $pdo->prepare("INSERT INTO tbConfiguracaoPartida (nomeConfiguracao, personagens, eventosPersonagem, temasSelecionados, eventosSelecionados, eventosCasas, codigoPartida) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $nomeConfiguracao,
            json_encode($personagens),
            json_encode($eventosPersonagem),
            json_encode($temas),
            json_encode($eventos),
            json_encode($eventosCasas),
            $codigoPartida
        ]);
        
        $idConfiguracao = $pdo->lastInsertId();
    }
    
    // Atualizar sessão com ID da configuração
    $_SESSION['configuracao_partida']['id'] = $idConfiguracao;
    $_SESSION['configuracao_partida']['eventosCasas'] = $eventosCasas;
    $_SESSION['configuracao_partida']['codigoPartida'] = $codigoPartida;
    
    header('Location: visualizarPartida.php?salvo=1');
    exit;
    
} catch (PDOException $e) {
    echo "Erro ao salvar configuração: " . $e->getMessage();
}
?>
