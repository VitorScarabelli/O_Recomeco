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
                        <div class="help-text">DIGITE UM NOME DESCRITIVO PARA ESTA CONFIGURAÇÃO</div>
                    </div>
                </div>

                <br><br>

                <h2 class="section-title">👥 SELECIONAR PERSONAGENS</h2>
                <div class="alert alert-info">
                    <strong>💡 DICA:</strong> CLIQUE NOS PERSONAGENS PARA ESCOLHER ENTRE 2 E 4 PERSONAGENS, CADA UM TEM SEUS EVENTOS ESPECÍFICOS QUE SERÃO SER INCLUÍDOS NA PARTIDA AUTOMATICAMENTE.
                </div>


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
            </div>

            <div class="eventos-section">
                <h2 class="section-title">🎲 SELECIONAR EVENTOS</h2>
                <div class="alert alert-info">
                    <strong>💡 DICA:</strong> SELECIONE OS TIPOS DE EVENTOS QUE DESEJA INCLUIR NA PARTIDA. OS EVENTOS DOS PERSONAGENS SELECIONADOS SERÃO INCLUÍDOS AUTOMATICAMENTE.
                </div>

                <div class="filtros-temas-section">
                    <h3 class="subsection-title">📚 FILTRAR POR TEMA DA AULA</h3>
                    <div class="alert alert-info">
                        <strong>💡 DICA:</strong> DIGITE O TEMA OU SELECIONE OS CHECKBOXES PARA FILTRAR OS EVENTOS. CLIQUE NO EVENTO PARA SELECIONÁ-LO.
                    </div>

                    <div class="form-group">
                        <label for="buscar-tema" class="form-label">BUSCAR TEMA:</label>
                        <input type="text" class="form-control" id="buscar-tema"
                            placeholder="Digite o tema da aula..." autocomplete="off">
                        <div id="tema-suggestions" class="search-suggestions"></div>
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
                </div>

                <div class="eventos-grid" id="eventos-grid">
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
            </div>

            <input type="hidden" name="personagens" id="hidden-personagens" value='<?php echo json_encode($personagens); ?>'>
            <input type="hidden" name="temas" id="hidden-temas" value='<?php echo json_encode($temasSelecionados); ?>'>
            <input type="hidden" name="eventos" id="hidden-eventos" value='<?php echo json_encode($eventosSelecionados); ?>'>
            <input type="hidden" name="idConfiguracao" value="<?php echo htmlspecialchars($idConfiguracao); ?>">


            <button type="submit" class="btn-configurar" id="btn-configurar" disabled>
                💾 SALVAR CONFIGURAÇÃO DA PARTIDA
            </button>

        </form>

        <br><br><br><br>
        <!-- Paginação entre páginas -->
        <div class="pagination-section">
            <div class="pagination-container">
                <div class="pagination-nav">
                    <a href="index.php" class="pagination-btn">‹‹ INÍCIO</a>
                    <a href="index.php" class="pagination-btn">1</a>
                    <a href="cadastrarEvento.php" class="pagination-btn">2</a>
                    <a href="gerenciarEventos.php" class="pagination-btn">3</a>
                    <a href="configurarPartida.php" class="pagination-btn active">4</a>
                    <a href="configurarPartida.php" class="pagination-btn disabled">FINAL ››</a>
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

            // Atualizar campos hidden
            document.getElementById('hidden-personagens').value = JSON.stringify(personagensSelecionados);
            document.getElementById('hidden-temas').value = JSON.stringify(temasSelecionados);
            document.getElementById('hidden-eventos').value = JSON.stringify(eventosSelecionados);

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