<?php include('../banco/conexao.php');
include('../includes/verificar_login.php'); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configurar Partida - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }

        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px;
        }

        .admin-header {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .admin-title {
            color: #2c3e50;
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .admin-subtitle {
            color: #7f8c8d;
            font-size: 1.2rem;
        }

        .config-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .section-title {
            color: #2c3e50;
            font-size: 1.8rem;
            font-weight: bold;
            margin-bottom: 25px;
            text-align: center;
        }

        .personagens-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            /* 3 colunas iguais */
            grid-template-rows: repeat(2, auto);
            /* 2 linhas automáticas */
            gap: 20px;
            margin-bottom: 30px;
        }


        .personagem-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: 3px solid transparent;
        }

        .personagem-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .personagem-card.selecionado {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }

        .personagem-img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 0 auto 15px;
            background: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .personagem-nome {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .personagem-desc {
            font-size: 0.9rem;
            color: #6c757d;
            line-height: 1.4;
        }

        .eventos-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .filtros-eventos {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .filtro-checkbox {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 25px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .filtro-checkbox:hover {
            transform: translateY(-2px);
        }

        .filtro-checkbox input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: white;
        }

        .eventos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .evento-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
            border: 3px solid transparent;
        }

        .evento-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }

        .evento-card.selecionado {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }

        .evento-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .evento-nome {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1.1rem;
        }

        .evento-casas {
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9rem;
        }

        .evento-casas.positivo {
            background: #d4edda;
            color: #155724;
        }

        .evento-casas.negativo {
            background: #f8d7da;
            color: #721c24;
        }

        .evento-descricao {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
            margin-bottom: 15px;
        }

        .evento-dificuldade {
            font-size: 0.8rem;
            padding: 3px 8px;
            border-radius: 10px;
            font-weight: bold;
        }

        .dificuldade-facil {
            background: #d4edda;
            color: #155724;
        }

        .dificuldade-medio {
            background: #fff3cd;
            color: #856404;
        }

        .dificuldade-dificil {
            background: #f8d7da;
            color: #721c24;
        }

        .dificuldade-extremo {
            background: #f5c6cb;
            color: #721c24;
        }

        .back-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background: rgba(52, 73, 94, 0.9);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.3s ease;
            z-index: 9999;
        }

        .back-btn:hover {
            background: rgba(52, 73, 94, 1);
            color: white;
            text-decoration: none;
        }

        .btn-configurar {
            background: linear-gradient(135deg, #007bff 0%, #6610f2 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 0.9rem;
            transition: transform 0.3s ease;
            width: 100%;
            margin-top: 20px;
        }

        .btn-configurar:hover {
            transform: translateY(-2px);
        }

        .btn-configurar:disabled {
            background: #6c757d;
            cursor: not-allowed;
            transform: none;
        }

        .selected-count {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #2c3e50;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 20px;
        }

        .subsection-title {
            color: #2c3e50;
            font-size: 1.4rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }

        .personagem-eventos-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .eventos-personagem-control {
            text-align: center;
        }

        .control-label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 15px;
            display: block;
        }

        .eventos-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn-evento-count {
            background: #e9ecef;
            color: #495057;
            border: 2px solid #dee2e6;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-evento-count:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .btn-evento-count.active {
            background: #28a745;
            color: white;
            border-color: #28a745;
        }

        .filtros-temas-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .filtros-temas {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .temas-disponiveis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .eventos-personagem-container {
            display: flex;
            flex-direction: column;
            gap: 30px;
        }

        .personagem-eventos-group {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .personagem-titulo {
            color: #2c3e50;
            font-size: 1.3rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }

        .eventos-personagem-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
        }

        .evento-personagem-card {
            background: white;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 3px solid transparent;
        }

        .evento-personagem-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .evento-personagem-card.selecionado {
            border-color: #28a745;
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
        }

        .evento-personagem-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .evento-personagem-nome {
            font-weight: bold;
            color: #2c3e50;
            font-size: 1rem;
        }

        .evento-personagem-casas {
            font-weight: bold;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.9rem;
        }

        .evento-personagem-casas.positivo {
            background: #d4edda;
            color: #155724;
        }

        .evento-personagem-casas.negativo {
            background: #f8d7da;
            color: #721c24;
        }

        .evento-personagem-descricao {
            color: #6c757d;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .sem-eventos {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }

        .evento-tema {
            font-size: 0.8rem;
            color: #6c757d;
            margin-top: 10px;
            font-weight: bold;
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .suggestion-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .suggestion-item:hover {
            background-color: #f8f9fa;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .form-group {
            position: relative;
        }
    </style>
</head>

<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">⚙️ CONFIGURAR PARTIDA</h1>
            <p class="admin-subtitle">SELECIONE PERSONAGENS E EVENTOS PARA A PRÓXIMA PARTIDA</p>
        </div>
        <form method="POST" action="salvarConfiguracao.php">
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
                    <strong>💡 DICA:</strong> CLIQUE NOS PERSONAGENS PARA ESCOLHER ENTRE 2 E 4 PERSONAGENS PARA A PARTIDA. CADA PERSONAGEM TEM EVENTOS ESPECÍFICOS QUE DEVERÃO SER ESCOLHIDOS PARA SEREM INCLUÍDOS NA PARTIDA.
                </div>


                <div class="personagens-grid">
                    <div class="personagem-card" data-id="1" data-nome="IDOSO" data-emoji="👴">
                        <div class="personagem-img">👴</div>
                        <div class="personagem-nome">IDOSO</div>
                        <div class="personagem-desc">UMA PESSOA COM MUITA EXPERIÊNCIA DE VIDA, MAS COM LIMITAÇÕES FÍSICAS.</div>
                    </div>

                    <div class="personagem-card" data-id="2" data-nome="CEGO" data-emoji="👨‍🦯">
                        <div class="personagem-img">👨‍🦯</div>
                        <div class="personagem-nome">CEGO</div>
                        <div class="personagem-desc">A VIDA TE DEU UM DESAFIO A MAIS, MAS VOCÊ NÃO ABAIXOU SUA CABEÇA.</div>
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

                <div class="personagem-eventos-section">
                    <h3 class="subsection-title">🎲 EVENTOS DOS PERSONAGENS</h3>
                    <div class="alert alert-info">
                        <strong>💡 DICA:</strong> SELECIONE QUAIS EVENTOS DE CADA PERSONAGEM DESEJA INCLUIR NA PARTIDA. CLIQUE NO EVENTO PARA SELECIONÁ-LO.
                    </div>

                    <div class="eventos-personagem-container" id="eventos-personagem-container">
                        <!-- Os eventos dos personagens serão carregados dinamicamente via JavaScript -->
                    </div>
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
                    EVENTOS SELECIONADOS: <span id="count-eventos">0</span>
                </div>
            </div>


            <input type="hidden" name="personagens" id="hidden-personagens" value="">
            <input type="hidden" name="temas" id="hidden-temas" value="">
            <input type="hidden" name="eventos" id="hidden-eventos" value="">
            <input type="hidden" name="eventosPersonagem" id="hidden-eventos-personagem" value="">

            <button type="submit" class="btn-configurar" id="btn-configurar" disabled>
                💾 SALVAR CONFIGURAÇÃO DA PARTIDA
            </button>
            <!-- <a href="./gerenciarConfiguracoes.php" class="btn-configurar" id="btn-configurar" disabled>
                💾 Mudar configurações
            </button> -->

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
                carregarEventosPersonagens();
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
                    this.classList.add('selecionado');
                    eventosSelecionados.push(id);
                }

                atualizarContadores();
            });
        });

        // Seleção de eventos dos personagens
        document.querySelectorAll('.evento-personagem-card').forEach(card => {
            card.addEventListener('click', function() {
                const id = this.dataset.id;
                const personagem = this.dataset.personagem;

                if (this.classList.contains('selecionado')) {
                    this.classList.remove('selecionado');
                    eventosPersonagemSelecionados = eventosPersonagemSelecionados.filter(e => e.id !== id);
                } else {
                    this.classList.add('selecionado');
                    eventosPersonagemSelecionados.push({
                        id,
                        personagem
                    });
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

        // Função para carregar eventos dos personagens selecionados
        function carregarEventosPersonagens() {
            const container = document.getElementById('eventos-personagem-container');
            container.innerHTML = '';

            if (personagensSelecionados.length === 0) {
                container.innerHTML = '<p class="sem-eventos">SELECIONE PELO MENOS UM PERSONAGEM PARA VER OS EVENTOS.</p>';
                return;
            }

            // Fazer requisição AJAX para buscar eventos
            const personagensIds = personagensSelecionados.map(p => p.id).join(',');

            fetch(`buscarEventosPersonagem.php?personagens=${personagensIds}`)
                .then(response => response.text())
                .then(html => {
                    container.innerHTML = html;

                    // Reaplicar event listeners
                    document.querySelectorAll('.evento-personagem-card').forEach(card => {
                        card.addEventListener('click', function() {
                            const id = this.dataset.id;
                            const personagem = this.dataset.personagem;

                            if (this.classList.contains('selecionado')) {
                                this.classList.remove('selecionado');
                                eventosPersonagemSelecionados = eventosPersonagemSelecionados.filter(e => e.id !== id);
                            } else {
                                this.classList.add('selecionado');
                                eventosPersonagemSelecionados.push({
                                    id,
                                    personagem
                                });
                            }

                            atualizarContadores();
                        });
                    });
                })
                .catch(error => {
                    console.error('Erro ao carregar eventos:', error);
                    container.innerHTML = '<p class="sem-eventos">ERRO AO CARREGAR EVENTOS DOS PERSONAGENS.</p>';
                });
        }

        function atualizarContadores() {
            document.getElementById('count-personagens').textContent = personagensSelecionados.length;
            document.getElementById('count-eventos').textContent = eventosSelecionados.length;

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