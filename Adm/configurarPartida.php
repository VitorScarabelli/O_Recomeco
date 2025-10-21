<?php
include('../banco/conexao.php');
include('../includes/verificar_login.php');


// Inicializar arrays
$personagens = [];
$temasSelecionados = [];
$eventosSelecionados = [];
$eventosPersonagem = [];
$idConfiguracao = null;

// Caso queira, pode armazenar na sessão também
$_SESSION['configuracao_partida'] = [
    'id' => $idConfiguracao,
    'personagens' => $personagens,
    'temasSelecionados' => $temasSelecionados,
    'eventosSelecionados' => $eventosSelecionados,
    'eventosPersonagem' => $eventosPersonagem
];
?>


<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Partida - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/configurarPartida.css">
</head>

<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">⚙️ CONFIGURAR PARTIDA</h1>
            <p class="admin-subtitle">SELECIONE PERSONAGENS E EVENTOS PARA A PRÓXIMA PARTIDA</p>
        </div>

        <form method="POST" action="salvarEJogar.php">
            <div class="config-section">
                <div class="form-section">
                    <h2 class="subsection-title">📝 TÍTULO DA CONFIGURAÇÃO</h2>
                    <div class="form-group">
                        <label for="tituloConfiguracao" class="form-label">NOME DA PARTIDA:</label>
                        <input type="text" class="form-control" id="tituloConfiguracao" name="tituloConfiguracao"
                            placeholder="Ex: PARTIDA SUS - AULA 1" required>
                        <div class="help-text" style="margin-top: 10px;">DIGITE UM NOME DESCRITIVO PARA ESTA CONFIGURAÇÃO</div>
                    </div>
                </div>

                <br><br>

                <h2 class="section-title">👥 SELECIONAR PERSONAGENS</h2>
                <div class="events-buttons" style="margin-bottom:10px;">
                    <a href="criarPersonagem.php" class="btn-evento-count-novo">➕ NOVO PERSONAGEM</a>
                </div>
                <div class="alert alert-info">
                    <strong>💡 DICA:</strong> CLIQUE NOS PERSONAGENS PARA ESCOLHER ENTRE 2 E 4 PERSONAGENS, CADA UM TEM SEUS EVENTOS ESPECÍFICOS QUE SERÃO SER INCLUÍDOS NA PARTIDA AUTOMATICAMENTE.
                </div>


                <div class="personagens-grid">
                    <?php
                    $stmt = $pdo->query("SELECT idPersonagem, nomePersonagem, descricaoPersonagem, emojiPersonagem FROM tbpersonagem ORDER BY idPersonagem ASC");
                    while ($p = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        $pid = (int)$p['idPersonagem'];
                        $pnome = strtoupper($p['nomePersonagem']);
                        $pdesc = $p['descricaoPersonagem'] ?: '';
                        $pemoji = $p['emojiPersonagem'] ?: '👤';
                        echo "<div class='personagem-card' data-id='{$pid}' data-nome='" . htmlspecialchars($pnome, ENT_QUOTES) . "' data-emoji='" . htmlspecialchars($pemoji, ENT_QUOTES) . "'>";
                        echo "<div class='personagem-img'>" . htmlspecialchars($pemoji) . "</div>";
                        echo "<div class='personagem-nome'>" . htmlspecialchars($pnome) . "</div>";
                        echo "<div class='personagem-desc'>" . htmlspecialchars($pdesc) . "</div>";
                        echo "<div style='margin-top:10px; display:flex; justify-content:flex-end;'>";
                        echo "<a href='editarPersonagem.php?id={$pid}' class='btn-evento-count' style='text-decoration:none;'>✏️ EDITAR</a>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>

                <div class="selected-count">
                    ESCOLHA DE 2 A 4 PERSONAGENS - PERSONAGENS SELECIONADOS: <span id="count-personagens">0</span> / 4
                </div>

                <!-- Eventos dos personagens -->
                <h2 class="section-title" style="margin-top: 5%;">🧩 EVENTOS DOS PERSONAGENS SELECIONADOS</h2>
                <div class="alert alert-info">
                    <strong>💡 DICA:</strong> AO SELECIONAR UM PERSONAGEM, OS EVENTOS ESPECÍFICOS DELE APARECERÃO ABAIXO. CLIQUE PARA SELECIONAR APENAS OS QUE VÃO PARA A PARTIDA. USE O BOTÃO ✏️ PARA EDITAR O EVENTO.
                </div>
                <div id="eventos-personagem-container" class="eventos-personagem-container"></div>
                <div class="selected-count">EVENTOS DE PERSONAGENS SELECIONADOS: <span id="count-eventos-personagem">0</span></div>
            </div>

            <!-- <div class="personagem-eventos-section">
                 <h2 class="section-title">🧩 EVENTOS DOS PERSONAGENS SELECIONADOS</h2>
                <div class="alert alert-info">
                    <strong>💡 DICA:</strong> AO SELECIONAR UM PERSONAGEM, OS EVENTOS ESPECÍFICOS DELE APARECERÃO ABAIXO. CLIQUE PARA SELECIONAR APENAS OS QUE VÃO PARA A PARTIDA. USE O BOTÃO ✏️ PARA EDITAR O EVENTO.
                </div>
                <div id="eventos-personagem-container" class="eventos-personagem-container"></div>
                <div class="selected-count">EVENTOS DE PERSONAGENS SELECIONADOS: <span id="count-eventos-personagem">0</span></div>
            </div> -->

            <div class="eventos-section">
                <h2 class="section-title">🎲 SELECIONAR EVENTOS</h2>

                <div class="events-buttons">
                    <a href="cadastrarEvento.php" class="btn-evento-count-novo">➕ NOVO EVENTO</a>
                    <a href="gerenciarEventos.php" class="btn-evento-count-novo">GERENCIAR EVENTOS</a>
                </div>

                <div class="alert alert-info">
                    <strong>💡 DICA:</strong> SELECIONE OS TIPOS DE EVENTOS QUE DESEJA INCLUIR NA PARTIDA. SELECIONE ATÉ 20 EVENTOS.
                </div>

                <h3 class="subsection-title" style="margin-top: 5%;">📚 FILTRAR POR TEMA DA AULA</h3>
                <div class="alert alert-info">
                    <strong>💡 DICA:</strong>SELECIONE OS TEMAS PARA FILTRAR OS EVENTOS E FACILITAR A BUSCA. CLIQUE NO EVENTO PARA SELECIONÁ-LO.
                </div>

                <div class="temas-disponiveis">
                    <?php
                    // Buscar todos os temas únicos
                    $stmt = $pdo->query("SELECT DISTINCT temaAula FROM tbevento WHERE temaAula IS NOT NULL AND temaAula != '' ORDER BY temaAula");
                    $temas = $stmt->fetchAll(PDO::FETCH_COLUMN);

                    foreach ($temas as $tema) {
                        echo "<label class='filtro-checkbox'>";
                        echo "<input type='checkbox' name='temas[]' value='" . htmlspecialchars($tema) . "'>";
                        echo "<span>" . htmlspecialchars($tema) . "</span>";
                        echo "</label>";
                    }
                    ?>
                </div>

                <div class="eventos-grid" id="eventos-grid" style="margin-top: 5%;">
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

                <div class="selected-count">VOCÊ PODE SELECIONAR ATÉ 20 EVENTOS - <span id="count-eventos">0</span> / 20</div>

            </div>

            <input type="hidden" name="personagens" id="hidden-personagens" value='<?php echo json_encode($personagens); ?>'>
            <input type="hidden" name="temas" id="hidden-temas" value='<?php echo json_encode($temasSelecionados); ?>'>
            <input type="hidden" name="eventos" id="hidden-eventos" value='<?php echo json_encode($eventosSelecionados); ?>'>
            <input type="hidden" name="eventosPersonagem" id="hidden-eventos-personagem" value='[]'>
            <input type="hidden" name="idConfiguracao" value="<?php echo htmlspecialchars($idConfiguracao); ?>">


            <button type="submit" class="btn-configurar" id="btn-configurar" disabled>
                💾 SALVAR CONFIGURAÇÃO DA PARTIDA
            </button>

        </form>


    </div>

    <script>
        let personagensSelecionados = [];
        let eventosSelecionados = [];
        let eventosPersonagemSelecionados = [];
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
                carregarEventosPersonagem();
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
                        alert('VOCÊ SÓ PODE SELECIONAR ATÉ 20 EVENTOS!');
                        return;
                    }
                }

                atualizarContadores();
            });
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


        function atualizarContadores() {
            document.getElementById('count-personagens').textContent = personagensSelecionados.length;
            document.getElementById('count-eventos').textContent = eventosSelecionados.length;
            document.getElementById('count-eventos-personagem').textContent = eventosPersonagemSelecionados.length;

            // Atualizar campos hidden
            document.getElementById('hidden-personagens').value = JSON.stringify(personagensSelecionados);
            document.getElementById('hidden-temas').value = JSON.stringify(temasSelecionados);
            document.getElementById('hidden-eventos').value = JSON.stringify(eventosSelecionados);
            document.getElementById('hidden-eventos-personagem').value = JSON.stringify(eventosPersonagemSelecionados);

            // Habilitar/desabilitar botão
            const btnConfigurar = document.getElementById('btn-configurar');
            if (personagensSelecionados.length >= 2 && personagensSelecionados.length <= 4) {
                btnConfigurar.disabled = false;
            } else {
                btnConfigurar.disabled = true;
            }
        }

        // Inicializar contadores
        atualizarContadores();

        function carregarEventosPersonagem() {
            const container = document.getElementById('eventos-personagem-container');
            const ids = personagensSelecionados.map(p => p.id).join(',');
            if (!ids) {
                container.innerHTML = '';
                eventosPersonagemSelecionados = [];
                atualizarContadores();
                return;
            }

            fetch(`buscarEventosPersonagem.php?personagens=${encodeURIComponent(ids)}`)
                .then(res => res.text())
                .then(html => {
                    container.innerHTML = html;
                    // Wire up selection on each per-character event card
                    container.querySelectorAll('.evento-personagem-card').forEach(card => {
                        card.addEventListener('click', function(e) {
                            if (e.target.closest('.btn-edit-evento-personagem')) {
                                return; // do not toggle when clicking edit
                            }
                            const id = this.dataset.id;
                            const personagem = this.dataset.personagem;
                            const key = `${personagem}:${id}`;
                            if (this.classList.contains('selecionado')) {
                                this.classList.remove('selecionado');
                                eventosPersonagemSelecionados = eventosPersonagemSelecionados.filter(ev => `${ev.personagem}:${ev.id}` !== key);
                            } else {
                                eventosPersonagemSelecionados.push({
                                    id,
                                    personagem
                                });
                                this.classList.add('selecionado');
                            }
                            atualizarContadores();
                        });
                    });
                })
                .catch(() => {
                    container.innerHTML = '<div class="alert alert-danger">Falha ao carregar eventos dos personagens selecionados.</div>';
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
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>