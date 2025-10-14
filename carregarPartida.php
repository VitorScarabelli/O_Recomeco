<?php
include('banco/conexao.php');
session_start();

// Helper para resposta JSON
function responde_json($status, $payload) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($payload);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Definição do jogador (nome + personagem) e redirecionamento
    if (isset($_POST['acao']) && $_POST['acao'] === 'definirJogador') {
        $nomeJogador = isset($_POST['nomeJogador']) ? trim($_POST['nomeJogador']) : '';
        $idPersonagem = isset($_POST['idPersonagem']) ? intval($_POST['idPersonagem']) : 0;

        if ($idPersonagem > 0 && $nomeJogador !== '') {
            $_SESSION['jogador_nome'] = $nomeJogador;
            $_SESSION['jogador_personagem'] = $idPersonagem;

            // Persistir no banco de dados dentro de tbConfiguracaoPartida.nomesJogadores
            if (isset($_SESSION['configuracao_partida']['id'])) {
                $idConfiguracao = intval($_SESSION['configuracao_partida']['id']);

                // Buscar nomesJogadores atuais
                $stmt = $pdo->prepare("SELECT nomesJogadores FROM tbConfiguracaoPartida WHERE idConfiguracao = ?");
                $stmt->execute([$idConfiguracao]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $nomes = [];
                if ($row && !empty($row['nomesJogadores'])) {
                    $dec = json_decode($row['nomesJogadores'], true);
                    if (is_array($dec)) { $nomes = $dec; }
                }

                // Atualizar ou inserir registro do personagem
                $atualizado = false;
                foreach ($nomes as &$n) {
                    if (intval($n['idPersonagem'] ?? 0) === $idPersonagem) {
                        $n['nomeUsuario'] = $nomeJogador;
                        $atualizado = true;
                        break;
                    }
                }
                if (!$atualizado) {
                    $nomes[] = [ 'idPersonagem' => $idPersonagem, 'nomeUsuario' => $nomeJogador ];
                }

                $stmt = $pdo->prepare("UPDATE tbConfiguracaoPartida SET nomesJogadores = ? WHERE idConfiguracao = ?");
                $stmt->execute([ json_encode($nomes, JSON_UNESCAPED_UNICODE), $idConfiguracao ]);
                $_SESSION['configuracao_partida']['nomesJogadores'] = $nomes;
            }
            header('Location: tabuleiro/tb.php');
            exit;
        }

        header('Location: selecionarPartida.php?erro=metodo_invalido');
        exit;
    }

    // Definição de múltiplos nomes: nomes[personagemId] => nomeAluno
    if (isset($_POST['acao']) && $_POST['acao'] === 'definirNomes') {
        $nomes = isset($_POST['nomes']) && is_array($_POST['nomes']) ? $_POST['nomes'] : [];
        // Sanitizar e normalizar
        $nomesAlunos = [];
        foreach ($nomes as $pid => $nome) {
            $pidInt = intval($pid);
            $nomeStr = trim($nome);
            if ($pidInt > 0 && $nomeStr !== '') {
                $nomesAlunos[$pidInt] = $nomeStr;
            }
        }
        $_SESSION['nomes_alunos'] = $nomesAlunos; // não persiste no banco, apenas sessão
        header('Location: tabuleiro/tb.php');
        exit;
    }

    // Validação de código e carga da configuração
    if (isset($_POST['codigoPartida'])) {
        $codigoPartida = trim(strtoupper($_POST['codigoPartida']));

        // Validar formato do código (6 caracteres)
        if (strlen($codigoPartida) !== 6) {
            // Se a requisição espera JSON, responde JSON
            if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
                responde_json(400, ['erro' => 'codigo_invalido']);
            }
            header('Location: selecionarPartida.php?erro=codigo_invalido');
            exit;
        }

        // Buscar configuração no banco pelo código
        $stmt = $pdo->prepare("SELECT * FROM tbConfiguracaoPartida WHERE codigoPartida = ? AND ativo = 1");
        $stmt->execute([$codigoPartida]);
        $configuracao = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$configuracao) {
            if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
                responde_json(404, ['erro' => 'codigo_nao_encontrado']);
            }
            header('Location: selecionarPartida.php?erro=codigo_nao_encontrado');
            exit;
        }

        // Carregar configuração na sessão
        $_SESSION['configuracao_partida'] = [
            'id' => $configuracao['idConfiguracao'],
            'titulo' => $configuracao['nomeConfiguracao'],
            'personagens' => json_decode($configuracao['personagens'], true),
            'temas' => json_decode($configuracao['temasSelecionados'], true),
            'eventos' => json_decode($configuracao['eventosSelecionados'], true),
            'eventosPersonagem' => json_decode($configuracao['eventosPersonagem'], true),
            'eventosCasas' => json_decode($configuracao['eventosCasas'], true),
            'codigoPartida' => $configuracao['codigoPartida'],
            'data_configuracao' => $configuracao['dataCriacao'],
            'nomesJogadores' => !empty($configuracao['nomesJogadores']) ? json_decode($configuracao['nomesJogadores'], true) : []
        ];

        // Resposta JSON para front mostrar os personagens
        if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false) {
            responde_json(200, [
                'ok' => true,
                'titulo' => $configuracao['nomeConfiguracao'],
                'personagens' => json_decode($configuracao['personagens'], true),
                'nomesJogadores' => !empty($configuracao['nomesJogadores']) ? json_decode($configuracao['nomesJogadores'], true) : []
            ]);
        }

        // Fluxo tradicional
        header('Location: tabuleiro/tb.php');
        exit;
    }
}

header('Location: selecionarPartida.php?erro=metodo_invalido');
exit;
?>

