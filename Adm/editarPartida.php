<?php 
include('../banco/conexao.php');
include('../includes/verificar_login.php');

$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: index.php');
    exit;
}

// Buscar partida
$stmt = $pdo->prepare("SELECT * FROM tbConfiguracaoPartida WHERE idConfiguracao = ?");
$stmt->execute([$id]);
$partida = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$partida) {
    header('Location: index.php');
    exit;
}

// Decodificar dados JSON
$personagens = json_decode($partida['personagens'], true) ?? [];
$temas = json_decode($partida['temasSelecionados'], true) ?? [];
$eventos = json_decode($partida['eventosSelecionados'], true) ?? [];
$eventosPersonagem = json_decode($partida['eventosPersonagem'], true) ?? [];

// Processar formulário
if ($_POST) {
    $nomeConfiguracao = $_POST['nomeConfiguracao'] ?? '';
    $personagensSelecionados = json_decode($_POST['personagens'] ?? '[]', true);
    $temasSelecionados = json_decode($_POST['temas'] ?? '[]', true);
    $eventosSelecionados = json_decode($_POST['eventos'] ?? '[]', true);
    
    // Validações
    $errors = [];
    
    if (empty($nomeConfiguracao)) {
        $errors[] = "Nome da partida é obrigatório";
    }
    
    if (count($personagensSelecionados) < 2) {
        $errors[] = "Selecione pelo menos 2 personagens";
    }
    
    if (count($personagensSelecionados) > 4) {
        $errors[] = "Máximo de 4 personagens permitidos";
    }
    
    if (empty($errors)) {
        try {
            // Gerar eventos dos personagens automaticamente
            $eventosPersonagemAtualizados = [];
            foreach ($personagensSelecionados as $personagem) {
                $idPersonagem = $personagem['id'];
                
                // Buscar todos os eventos do personagem
                $stmt = $pdo->prepare("SELECT * FROM tbeventopersonagem WHERE idPersonagem = ?");
                $stmt->execute([$idPersonagem]);
                $eventosPersonagemDB = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                // Adicionar todos os eventos do personagem
                foreach ($eventosPersonagemDB as $evento) {
                    $eventosPersonagemAtualizados[] = [
                        'id' => $evento['idEvento'],
                        'personagem' => $idPersonagem
                    ];
                }
            }
            
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
            
            $eventosCasas = atribuirCasasAleatorias($eventosSelecionados, $eventosPersonagemAtualizados);
            
            $stmt = $pdo->prepare("UPDATE tbConfiguracaoPartida SET nomeConfiguracao = ?, personagens = ?, eventosPersonagem = ?, temasSelecionados = ?, eventosSelecionados = ?, eventosCasas = ? WHERE idConfiguracao = ?");
            $stmt->execute([
                $nomeConfiguracao,
                json_encode($personagensSelecionados),
                json_encode($eventosPersonagemAtualizados),
                json_encode($temasSelecionados),
                json_encode($eventosSelecionados),
                json_encode($eventosCasas),
                $id
            ]);
            
            header('Location: index.php?success=1');
            exit;
        } catch (PDOException $e) {
            $error = "ERRO AO ATUALIZAR PARTIDA: " . $e->getMessage();
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Partida - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/editarPartida.css">
</head>
<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>
    
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">✏️ EDITAR PARTIDA</h1>
            <p class="admin-subtitle">MODIFIQUE OS DADOS DA PARTIDA: <?php echo htmlspecialchars($partida['nomeConfiguracao']); ?></p>
        </div>
        
        <div class="form-section">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="nomeConfiguracao" class="form-label">NOME DA PARTIDA </label>
                    <input type="text" class="form-control" id="nomeConfiguracao" name="nomeConfiguracao" 
                           value="<?php echo htmlspecialchars($partida['nomeConfiguracao']); ?>" 
                           placeholder="Ex: PARTIDA SUS - AULA 1" required>
                    <div class="alert-text">NOME DESCRITIVO PARA ESTA CONFIGURAÇÃO DE PARTIDA</div>
                </div>
                
                <h3 class="form-label">👥 PERSONAGENS SELECIONADOS</h3>
                <div class="personagens-grid">
                    <div class="personagem-card" data-id="1" data-nome="IDOSO" data-emoji="👴">
                        <div class="personagem-img">👴</div>
                        <div class="personagem-nome">IDOSO</div>
                        <div class="personagem-desc">UMA PESSOA COM MUITA EXPERIÊNCIA DE VIDA, MAS COM LIMITAÇÕES FÍSICAS.</div>
                    </div>

                    <div class="personagem-card" data-id="2" data-nome="DEFICIENTE VISUAL" data-emoji="👨‍🦯">
                        <div class="personagem-img">👨‍🦯</div>
                        <div class="personagem-nome">DEFICIENTE VISUAL</div>
                        <div class="personagem-desc">VOCÊ ENFRENTA DESAFIOS VISUAIS COM AUTONOMIA E DETERMINAÇÃO.</div>
                    </div>

                    <div class="personagem-card" data-id="3" data-nome="MULHER NEGRA" data-emoji="👩🏽‍🦱">
                        <div class="personagem-img">👩🏽‍🦱</div>
                        <div class="personagem-nome">MULHER NEGRA</div>
                        <div class="personagem-desc">UMA MULHER QUE TEM ORGULHO DA SUA COR, ALGUÉM QUE QUER DERRUBAR O PRECONCEITO.</div>
                    </div>

                    <div class="personagem-card" data-id="4" data-nome="RETIRANTE" data-emoji="🧳">
                        <div class="personagem-img">🧳</div>
                        <div class="personagem-nome">RETIRANTE</div>
                        <div class="personagem-desc">UM VIAJANTE HUMILDE QUE DEIXOU SUA TERRA NATAL EM BUSCA DE NOVAS OPORTUNIDADES.</div>
                    </div>

                    <div class="personagem-card" data-id="5" data-nome="MULHER TRANS" data-emoji="🌈">
                        <div class="personagem-img">🌈</div>
                        <div class="personagem-nome">MULHER TRANS</div>
                        <div class="personagem-desc">UMA MULHER QUE TEVE A CORAGEM DE SER QUEM REALMENTE É.</div>
                    </div>

                    <div class="personagem-card" data-id="6" data-nome="UMBANDISTA" data-emoji="👳🏽‍♂️">
                        <div class="personagem-img">👳🏽‍♂️</div>
                        <div class="personagem-nome">UMBANDISTA</div>
                        <div class="personagem-desc">ALGUÉM QUE SEGUE A RELIGIÃO DE UMBANDA, BUSCANDO SEMPRE O EQUILÍBRIO E A PAZ.</div>
                    </div>
                </div>

                <div class="selected-count">
                    PERSONAGENS SELECIONADOS: <span id="count-personagens">0</span> / 4
                </div>
                
                <h3 class="form-label">🎲 EVENTOS SELECIONADOS</h3>

                <div class="help-text">SELECIONE OS TIPOS DE EVENTOS QUE DESEJA INCLUIR NA PARTIDA. OS EVENTOS DOS PERSONAGENS SELECIONADOS SERÃO INCLUÍDOS AUTOMATICAMENTE.</div>
                
                <div class="filtros-temas-section">
                    <h4 class="form-label">📚 FILTRAR POR TEMA DA AULA</h4>
                    <div class="help-text">DIGITE O TEMA OU SELECIONE OS CHECKBOXES PARA FILTRAR OS EVENTOS. CLIQUE NO EVENTO PARA SELECIONÁ-LO.</div>
                    
                    <div class="form-group">
                        <label for="buscar-tema" class="form-label">BUSCAR TEMA:</label>
                        <input type="text" class="form-control" id="buscar-tema"
                            placeholder=" DIGITE O TEMA DA AULA..." autocomplete="off">
                        <div id="tema-suggestions" class="search-suggestions"></div>
                    </div>
                    
                    <div class="temas-disponiveis">
                        <?php
                        // Buscar todos os temas únicos
                        $stmt = $pdo->query("SELECT DISTINCT temaAula FROM tbevento WHERE temaAula IS NOT NULL AND temaAula != '' ORDER BY temaAula");
                        $temasDisponiveis = $stmt->fetchAll(PDO::FETCH_COLUMN);
                        
                        foreach ($temasDisponiveis as $tema) {
                            echo "<label class='filtro-checkbox'>";
                            echo "<input type='checkbox' name='temas[]' value='" . htmlspecialchars($tema) . "'>";
                            echo "<span>" . htmlspecialchars($tema) . "</span>";
                            echo "</label>";
                        }
                        ?>
                    </div>
                </div>
                <div class="eventos-grid">
                    <?php
                    $stmt = $pdo->query("SELECT * FROM tbevento ORDER BY nomeEvento");
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $tipo = $row['casaEvento'] > 0 ? 'positivo' : 'negativo';
                        $casas = $row['casaEvento'];
                        $temaAula = $row['temaAula'] ?? 'Sem tema';

                        echo "<div class='evento-card' data-id='{$row['idEvento']}' data-tema='" . htmlspecialchars($temaAula) . "'>";
                        echo "<div class='evento-header'>";
                        echo "<div class='evento-nome'>{$row['nomeEvento']}</div>";
                        echo "<div class='evento-casas {$tipo}'>" . ($casas > 0 ? '+' : '') . $casas . "</div>";
                        echo "</div>";
                        echo "<div class='evento-descricao'>" . substr($row['descricaoEvento'], 0, 100) . (strlen($row['descricaoEvento']) > 100 ? '...' : '') . "</div>";
                        echo "<div class='evento-tema'>TEMA: " . htmlspecialchars($temaAula) . "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>

                <div class="selected-count">
                    EVENTOS SELECIONADOS: <span id="count-eventos">0</span> / 20
                </div>

                <input type="hidden" name="personagens" id="hidden-personagens" value='<?php echo json_encode($personagens); ?>'>
                <input type="hidden" name="temas" id="hidden-temas" value='<?php echo json_encode($temas); ?>'>
                <input type="hidden" name="eventos" id="hidden-eventos" value='<?php echo json_encode($eventos); ?>'>
                
                <button type="button" class="btn-confirm" onclick="confirmarAlteracoes()">✅ CONFIRMAR ALTERAÇÕES</button>
            </form>
            
            <div class="preview-section">
                <h4 class="preview-title">📋 PREVIEW DA PARTIDA</h4>
                <div class="preview-item">
                    <strong>NOME:</strong> <span id="preview-nome"><?php echo htmlspecialchars($partida['nomeConfiguracao']); ?></span><br>
                    <strong>CÓDIGO:</strong> <?php echo htmlspecialchars($partida['codigoPartida']); ?><br>
                    <strong>PERSONAGENS:</strong> <span id="preview-personagens"><?php echo count($personagens); ?></span><br>
                    <strong>EVENTOS:</strong> <span id="preview-eventos"><?php echo count($eventos); ?></span><br>
                    <strong>CRIADO EM:</strong> <?php echo date('d/m/Y H:i', strtotime($partida['dataCriacao'])); ?>
                </div>
            </div>
        </div>
        <br><br><br><br>

        <!-- Paginação entre páginas -->
        <div class="pagination-section">
            <div class="pagination-container">
                <div class="pagination-nav">
                    <a href="index.php" class="pagination-btn disabled">‹‹ INÍCIO</a>
                    <a href="index.php" class="pagination-btn active">1</a>
                    <a href="cadastrarEvento.php" class="pagination-btn">2</a>
                    <a href="gerenciarEventos.php" class="pagination-btn">3</a>
                    <a href="configurarPartida.php" class="pagination-btn">4</a>
                    <a href="configurarPartida.php" class="pagination-btn">FINAL ››</a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        let personagensSelecionados = [];
        let eventosSelecionados = [];
        let temasSelecionados = [];

        // Seleção de personagens
        document.querySelectorAll('.personagem-card').forEach(card => {
            card.addEventListener('click', function() {
                const id = this.dataset.id;
                const nome = this.dataset.nome;
                const emoji = this.dataset.emoji;

                if (this.classList.contains('selecionado')) {
                    this.classList.remove('selecionado');
                    personagensSelecionados = personagensSelecionados.filter(p => p.id !== id);
                } else {
                    if (personagensSelecionados.length < 4) {
                        this.classList.add('selecionado');
                        personagensSelecionados.push({
                            id,
                            nome,
                            emoji
                        });
                    } else {
                        alert('VOCÊ SÓ PODE SELECIONAR ATÉ 4 PERSONAGENS!');
                        return;
                    }
                }

                atualizarContadores();
            });
        });

        // Seleção de eventos
        document.querySelectorAll('.evento-card').forEach(card => {
            card.addEventListener('click', function() {
                const id = this.dataset.id;

                if (this.classList.contains('selecionado')) {
                    this.classList.remove('selecionado');
                    eventosSelecionados = eventosSelecionados.filter(e => e !== id);
                } else {
                    if (eventosSelecionados.length < 20) {
                        this.classList.add('selecionado');
                        eventosSelecionados.push(id);
                    } else {
                        alert('VOCÊ SÓ PODE SELECIONAR ATÉ 25 EVENTOS!');
                        return;
                    }
                }

                atualizarContadores();
            });
        });

        function atualizarContadores() {
            document.getElementById('count-personagens').textContent = personagensSelecionados.length;
            document.getElementById('count-eventos').textContent = eventosSelecionados.length;

            // Atualizar campos hidden
            document.getElementById('hidden-personagens').value = JSON.stringify(personagensSelecionados);
            document.getElementById('hidden-temas').value = JSON.stringify(temasSelecionados);
            document.getElementById('hidden-eventos').value = JSON.stringify(eventosSelecionados);

            // Atualizar preview
            document.getElementById('preview-personagens').textContent = personagensSelecionados.length;
            document.getElementById('preview-eventos').textContent = eventosSelecionados.length;
        }

        // Carregar dados existentes
        personagensSelecionados = <?php echo json_encode($personagens); ?>;
        eventosSelecionados = <?php echo json_encode($eventos); ?>;
        temasSelecionados = <?php echo json_encode($temas); ?>;

        // Pré-selecionar personagens
        personagensSelecionados.forEach(personagem => {
            const card = document.querySelector(`[data-id="${personagem.id}"]`);
            if (card) {
                card.classList.add('selecionado');
            }
        });

        // Pré-selecionar eventos
        eventosSelecionados.forEach(eventoId => {
            const card = document.querySelector(`[data-id="${eventoId}"]`);
            if (card) {
                card.classList.add('selecionado');
            }
        });

        // Pré-selecionar temas
        temasSelecionados.forEach(tema => {
            const checkbox = document.querySelector(`input[name="temas[]"][value="${tema}"]`);
            if (checkbox) {
                checkbox.checked = true;
            }
        });

        // Aplicar filtros de tema
        filtrarEventosPorTema();

        // Inicializar contadores
        atualizarContadores();

        // Preview em tempo real
        document.getElementById('nomeConfiguracao').addEventListener('input', function() {
            document.getElementById('preview-nome').textContent = this.value || '-';
        });

        // Seleção de temas (filtro)
        document.querySelectorAll('input[name="temas[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    temasSelecionados.push(this.value);
                } else {
                    temasSelecionados = temasSelecionados.filter(t => t !== this.value);
                }

                filtrarEventosPorTema();
                atualizarContadores();
            });
        });

        // Função para filtrar eventos por tema
        function filtrarEventosPorTema() {
            const eventosCards = document.querySelectorAll('.evento-card');

            eventosCards.forEach(card => {
                const temaEvento = card.dataset.tema;
                const deveMostrar = temasSelecionados.length === 0 || temasSelecionados.includes(temaEvento);

                if (deveMostrar) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                    // Desmarcar se estava selecionado
                    if (card.classList.contains('selecionado')) {
                        card.classList.remove('selecionado');
                        const id = card.dataset.id;
                        eventosSelecionados = eventosSelecionados.filter(e => e !== id);
                    }
                }
            });
        }

        // Autocomplete para tema na configuração
        document.getElementById('buscar-tema').addEventListener('input', function() {
            const query = this.value;
            const suggestions = document.getElementById('tema-suggestions');

            if (query.length < 1) {
                suggestions.style.display = 'none';
                return;
            }

            fetch(`buscarTemas.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestions.innerHTML = data.map(tema =>
                            `<div class="suggestion-item" onclick="selecionarTema('${tema}')">${tema}</div>`
                        ).join('');
                        suggestions.style.display = 'block';
                    } else {
                        suggestions.style.display = 'none';
                    }
                })
                .catch(error => {
                    suggestions.style.display = 'none';
                });
        });

        function selecionarTema(tema) {
            document.getElementById('buscar-tema').value = tema;
            document.getElementById('tema-suggestions').style.display = 'none';

            // Marcar checkbox correspondente
            const checkboxes = document.querySelectorAll('input[name="temas[]"]');
            checkboxes.forEach(checkbox => {
                if (checkbox.value === tema) {
                    checkbox.checked = true;
                    filtrarEventosPorTema();
                    atualizarContadores();
                }
            });
        }

        // Esconder sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.form-group')) {
                document.getElementById('tema-suggestions').style.display = 'none';
            }
        });

        function confirmarAlteracoes() {
            if (confirm('TEM CERTEZA QUE DESEJA SALVAR AS ALTERAÇÕES?')) {
                // Submit the form directly
                document.querySelector('form').submit();
            }
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
