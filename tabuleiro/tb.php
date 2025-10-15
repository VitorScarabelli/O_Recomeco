    <?php
    include('../banco/conexao.php');
    session_start();

    // Verificar se há configuração salva na sessão
    if (isset($_SESSION['configuracao_partida'])) {
        $configuracao = $_SESSION['configuracao_partida'];
        $personagensSelecionados = $configuracao['personagens'];
        $eventosCasas = $configuracao['eventosCasas'];

        // Mapa de fallback (ícones padrão) e mapa a partir do banco
        $fallbackIcons = [
            1 => 'idosoicone.jpg',
            2 => 'cegoicone.jpg',
            3 => 'negraicone.png',
            4 => 'retiranteicone.png',
            5 => 'transicone.png',
            6 => 'umbandaicone.png'
        ];
        $iconMap = [];
        try {
            $stmtIcons = $pdo->query("SELECT idPersonagem, iconePersonagem FROM tbpersonagem");
            while ($row = $stmtIcons->fetch(PDO::FETCH_ASSOC)) {
                $idP = intval($row['idPersonagem']);
                $file = trim($row['iconePersonagem'] ?? '');
                if ($idP && $file !== '') {
                    $iconMap[$idP] = $file;
                }
            }
        } catch (Throwable $e) {
            // Se a coluna não existir, seguimos com os fallbacks
        }

        // Usar configuração salva
        $personagensCompletos = array_map(function ($p) use ($iconMap, $fallbackIcons) {
            $id = intval($p['idPersonagem'] ?? $p['id'] ?? 0);
            $img = $iconMap[$id] ?? ($fallbackIcons[$id] ?? 'miniidoso.png');
            return [
                'idPersonagem' => $id,
                'nome' => $p['nomePersonagem'] ?? $p['nome'] ?? 'Personagem',
                'emoji' => $p['emoji'] ?? '👤',
                'imagem' => $img
            ];
        }, $personagensSelecionados);

        error_log("Usando configuração salva: " . print_r($personagensCompletos, true));
    } else {
        // Fallback para método antigo
        $personagensSelecionados = isset($_POST['personagens']) ? json_decode($_POST['personagens'], true) : [];
        $eventosCasas = null;

        // Log para debug
        error_log("Personagens recebidos via POST: " . print_r($personagensSelecionados, true));

        // Verifica se há personagens selecionados, senão usa fallback
        if (!empty($personagensSelecionados) && is_array($personagensSelecionados)) {
            // Carrega ícones do banco com fallback
            $fallbackIcons = [
                1 => 'idosoicone.jpg',
                2 => 'cegoicone.jpg',
                3 => 'negraicone.png',
                4 => 'retiranteicone.png',
                5 => 'transicone.png',
                6 => 'umbandaicone.png'
            ];
            $iconMap = [];
            try {
                $ids = array_map(function ($p) {
                    return intval($p['idPersonagem']);
                }, $personagensSelecionados);
                $ids = array_values(array_filter($ids));
                if (!empty($ids)) {
                    $placeholders = implode(',', array_fill(0, count($ids), '?'));
                    $stmtIcons = $pdo->prepare("SELECT idPersonagem, iconePersonagem FROM tbpersonagem WHERE idPersonagem IN ($placeholders)");
                    $stmtIcons->execute($ids);
                    while ($row = $stmtIcons->fetch(PDO::FETCH_ASSOC)) {
                        $idP = intval($row['idPersonagem']);
                        $file = trim($row['iconePersonagem'] ?? '');
                        if ($idP && $file !== '') {
                            $iconMap[$idP] = $file;
                        }
                    }
                }
            } catch (Throwable $e) {
                // Se a coluna não existir, seguimos com os fallbacks
            }

            $personagensCompletos = array_map(function ($p) use ($iconMap, $fallbackIcons) {
                $id = intval($p['idPersonagem']);
                $img = $iconMap[$id] ?? ($fallbackIcons[$id] ?? 'miniidoso.png');
                return [
                    'idPersonagem' => $id,
                    'nome' => $p['nomePersonagem'],
                    'emoji' => $p['emoji'],
                    'imagem' => $img
                ];
            }, $personagensSelecionados);

            error_log("Personagens processados: " . print_r($personagensCompletos, true));
        } else {
            // Fallback apenas se não houver seleção
            error_log("Usando personagens de fallback");
            $personagensCompletos = [
                [
                    'idPersonagem' => 6,
                    'nome' => 'Umbandista',
                    'emoji' => '👳🏽‍♂️',
                    'imagem' => 'miniumbanda.png'
                ],
                [
                    'idPersonagem' => 3,
                    'nome' => 'Mulher Negra',
                    'emoji' => '👩🏽‍🦱',
                    'imagem' => 'mininegra.png'
                ]
            ];
        }
    }


    // --- Eventos ---
    $eventos = [];

    if ($eventosCasas) {
        // Usar eventos com casas pré-atribuídas da configuração salva
        foreach ($eventosCasas as $eventoCasa) {
            if ($eventoCasa['tipo'] === 'geral') {
                // Buscar evento geral
                $stmt = $pdo->prepare("SELECT * FROM tbevento WHERE idEvento = ?");
                $stmt->execute([$eventoCasa['id']]);
                $evento = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($evento) {
                    $eventos[] = [
                        'nome' => $evento['nomeEvento'],
                        'descricao' => $evento['descricaoEvento'],
                        'casas' => intval($evento['casaEvento']),
                        'casa' => $eventoCasa['casa']
                    ];
                }
            } else if ($eventoCasa['tipo'] === 'personagem') {
                // Buscar evento do personagem
                $stmt = $pdo->prepare("SELECT * FROM tbeventopersonagem WHERE idEvento = ?");
                $stmt->execute([$eventoCasa['id']]);
                $evento = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($evento) {
                    // Encontrar o personagem correspondente
                    $personagem = null;
                    foreach ($personagensCompletos as $p) {
                        if ($p['idPersonagem'] == $eventoCasa['personagem']) {
                            $personagem = $p;
                            break;
                        }
                    }

                    if ($personagem) {
                        $eventos[] = [
                            'nome' => $evento['nomeEvento'],
                            'descricao' => $evento['descricaoEvento'],
                            'casas' => intval($evento['casas']),
                            'casa' => $eventoCasa['casa'],
                            'idDono' => $personagem['idPersonagem'],
                            'emojiDono' => $personagem['emoji'],
                            'imagemDono' => $personagem['imagem']
                        ];
                    }
                }
            }
        }
    } else {
        // Método antigo (fallback)
        $modoAleatorio = isset($_POST['modoAleatorio']);
        $filtros = $_POST['filtros'] ?? [];
        $eventosSelecionados = $_POST['eventos'] ?? [];

        // --- Casas válidas no tabuleiro (1 até 47) ---
        $casasDisponiveis = range(1, 46);
        shuffle($casasDisponiveis);

        // Função para pegar a próxima casa válida
        function pegarCasaDisponivel(&$casas)
        {
            while (!empty($casas)) {
                $casa = array_shift($casas);
                if ($casa < 48) return $casa;
            }
            return null; // se não houver casa válida
        }

        // --- Eventos Aleatórios ---
        if ($modoAleatorio) {
            if (!empty($filtros)) {
                foreach ($filtros as $filtro) {
                    $stmt = $pdo->prepare("SELECT * FROM tbevento WHERE dificuldadeEvento = ?");
                    $stmt->execute([$filtro]);
                    $eventosFiltrados = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($eventosFiltrados as $row) {
                        $casa = pegarCasaDisponivel($casasDisponiveis);
                        if ($casa === null) break;
                        $eventos[] = [
                            'nome' => $row['nomeEvento'],
                            'descricao' => $row['descricaoEvento'],
                            'casas' => intval($row['casaEvento']),
                            'casa' => $casa
                        ];
                    }
                }
            }
        }

        // --- Eventos extras dos personagens ---
        foreach ($personagensCompletos as $p) {
            if (!empty($p['idPersonagem'])) {
                $stmt = $pdo->prepare("SELECT * FROM tbeventopersonagem WHERE idPersonagem = ?");
                $stmt->execute([$p['idPersonagem']]);
                $eventosPersonagem = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($eventosPersonagem as $ev) {
                    $casa = pegarCasaDisponivel($casasDisponiveis);
                    if ($casa === null) break;

                    $eventos[] = [
                        'nome' => $ev['nomeEvento'],
                        'descricao' => $ev['descricaoEvento'],
                        'casas' => intval($ev['casas']),
                        'casa' => $casa,
                        'idDono' => $p['idPersonagem'],
                        'emojiDono' => $p['emoji'], // Mantém o emoji para referência
                        'imagemDono' => $p['imagem'] // Adiciona a imagem do personagem
                    ];
                }
            }
        }
    }
    ?>

    <!DOCTYPE html>
    <html lang="pt-br">

    <head>
        <meta charset="UTF-8">
        <title>Jogo do Dado com Eventos</title>
        <link rel="stylesheet" href="style.css">
        <style>
            /* Ajustes específicos da legenda */
            #popupLegenda .popup-conteudo {
                width: 560px;
                max-width: 95%;
            }

            #lista-legenda {
                list-style: none;
                padding: 0;
                margin: 0;
            }

            #lista-legenda li {
                display: flex;
                align-items: center;
                gap: 10px;
                padding: 8px 6px;
                font-size: 1.05rem;
            }

            #lista-legenda .emoji {
                font-size: 1.6rem;
                width: 32px;
                text-align: center;
            }

            #lista-legenda .icon {
                width: 28px;
                height: 28px;
                border-radius: 6px;
                object-fit: contain;
                background: #fff;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
            }
        </style>
    </head>

    <body>

        <p class="clickParaJogar">CLIQUE NO DADO PARA JOGAR</p>
        <div id="dice-container">
            <div id="dice">
                <div class="face front">
                    <span class="dot center"></span>
                </div>
                <div class="face back">
                    <span class="dot top-left"></span>
                    <span class="dot top-right"></span>
                    <span class="dot bottom-left"></span>
                    <span class="dot bottom-right"></span>
                    <span class="dot middle-left"></span>
                    <span class="dot middle-right"></span>
                </div>
                <div class="face right">
                    <span class="dot top-left"></span>
                    <span class="dot center"></span>
                    <span class="dot bottom-right"></span>
                </div>
                <div class="face left">
                    <span class="dot top-left"></span>
                    <span class="dot top-right"></span>
                    <span class="dot bottom-left"></span>
                    <span class="dot bottom-right"></span>
                </div>
                <div class="face top">
                    <span class="dot top-left"></span>
                    <span class="dot top-right"></span>
                    <span class="dot center"></span>
                    <span class="dot bottom-left"></span>
                    <span class="dot bottom-right"></span>
                </div>
                <div class="face bottom">
                    <span class="dot top-left"></span>
                    <span class="dot bottom-right"></span>
                </div>
            </div>
            <p id="infoTurno">É A VEZ DO(A) <?php echo isset($personagensCompletos[0]['nome']) ? $personagensCompletos[0]['nome'] : 'JOGADOR(A) 1'; ?></p>
            <p id="resultado"></p>
        </div>

        <div class="tabuleiro" id="tabuleiro"></div>

        <div id="popupEvento" class="popup">
            <div class="popup-conteudo">
                <h2 id="popupNome">NOME DO EVENTO</h2>
                <p id="popupDescricao">DESCRIÇÃO DO EVENTO</p>
                <p id="popupModificador">CASAS: </p>
                <button>FECHAR</button>
            </div>
        </div>

        <!-- Botão Legenda (❓) -->
        <button id="btn-legenda" title="Abrir legenda" aria-label="Abrir legenda">❓</button>

        <!-- Botão Narração (toggle) -->
        <button id="btn-narracao" title="Desativar narração" aria-label="Narração ativa" aria-pressed="true">🔊</button>

        <!-- Modal/Popup da Legenda -->
        <div id="popupLegenda" class="popup" aria-hidden="true" role="dialog" aria-labelledby="tituloLegenda">
            <div class="popup-conteudo">
                <h2 id="tituloLegenda">LEGENDA</h2>
                <ul id="lista-legenda"></ul>
                <button id="fecharLegenda">FECHAR</button>
            </div>
        </div>

        <script>
            // === Narração (Web Speech API) ===
            const narrador = (() => {
                let enabled = true;
                let selectedVoice = null;
                const preferredLangs = ["pt-BR", "pt_PT", "pt" ];

                function loadPref() {
                    try {
                        const v = localStorage.getItem('narracaoAtiva');
                        if (v === '0') enabled = false;
                        if (v === '1') enabled = true;
                    } catch (e) {}
                }

                function savePref() {
                    try { localStorage.setItem('narracaoAtiva', enabled ? '1' : '0'); } catch (e) {}
                }

                function chooseVoice() {
                    if (!('speechSynthesis' in window)) return null;
                    const voices = window.speechSynthesis.getVoices() || [];
                    if (!voices.length) return null;
                    // Prioriza pt-BR
                    for (const lang of preferredLangs) {
                        const v = voices.find(v => (v.lang || '').toLowerCase().startsWith(lang.toLowerCase()));
                        if (v) return v;
                    }
                    return voices.find(v => (v.lang || '').toLowerCase().startsWith('pt')) || voices[0] || null;
                }

                function init() {
                    loadPref();
                    if (!('speechSynthesis' in window)) return;
                    selectedVoice = chooseVoice();
                    // Alguns navegadores carregam vozes de forma assíncrona
                    window.speechSynthesis.onvoiceschanged = () => {
                        selectedVoice = chooseVoice() || selectedVoice;
                    };
                }

                function cancel() {
                    if (!('speechSynthesis' in window)) return;
                    window.speechSynthesis.cancel();
                }

                function speak(text, opts = {}) {
                    if (!text || !enabled) return;
                    if (!('speechSynthesis' in window)) return;
                    const utter = new SpeechSynthesisUtterance(text.toString());
                    utter.lang = (selectedVoice && selectedVoice.lang) || 'pt-BR';
                    utter.voice = selectedVoice || null;
                    utter.rate = typeof opts.rate === 'number' ? opts.rate : 1.0;
                    utter.pitch = typeof opts.pitch === 'number' ? opts.pitch : 1.0;
                    utter.volume = typeof opts.volume === 'number' ? opts.volume : 1.0;
                    window.speechSynthesis.speak(utter);
                }

                function setEnabled(val) {
                    enabled = !!val;
                    savePref();
                    if (!enabled) cancel();
                }

                function toggle() { setEnabled(!enabled); }

                return {
                    init,
                    speak,
                    cancel,
                    setEnabled,
                    toggle,
                    get enabled() { return enabled; }
                };
            })();

            narrador.init();
            const linhas = 10;
            const colunas = 16;
            const tabuleiro = document.getElementById("tabuleiro");

            const eventos = <?php echo json_encode($eventos); ?>;
            const personagensSelecionados = <?php echo json_encode($personagensCompletos); ?>;
            // Expor também para uso em outras rotinas (ex.: legenda)
            window.personagensSelecionados = personagensSelecionados;

            // Log para debug no console
            console.log("Personagens recebidos no JavaScript:", personagensSelecionados);
            console.log("Quantidade de personagens:", personagensSelecionados.length);

            // Define os jogadores (máximo 4)
            const jogadores = personagensSelecionados.slice(0, 4).map((p, index) => ({
                idPersonagem: p.idPersonagem,
                nome: p.nome,
                emoji: p.emoji,
                classe: "boneco" + (index + 1),
                posicao: 0,
                terminou: false,
                historicoPessoal: [] // ✅ guarda eventos do jogador
            }));

            // Expor para outras rotinas (ex.: legenda)
            window.jogadores = jogadores;

            // Sobrescrever nome do jogador escolhido (se houver em sessão)
            (function() {
                const jogadorEscolhidoId = <?php echo isset($_SESSION['jogador_personagem']) ? intval($_SESSION['jogador_personagem']) : 'null'; ?>;
                const jogadorEscolhidoNome = <?php echo isset($_SESSION['jogador_nome']) ? json_encode($_SESSION['jogador_nome']) : 'null'; ?>;
                if (jogadorEscolhidoId && jogadorEscolhidoNome) {
                    const alvo = jogadores.find(j => j.idPersonagem === jogadorEscolhidoId);
                    if (alvo) {
                        alvo.nome = jogadorEscolhidoNome;
                    }
                }
                // Sobrescrever nomes por mapa de sessão (nomes_alunos: { idPersonagem: nomeAluno })
                <?php
                $nomesMapa = isset($_SESSION['nomes_alunos']) && is_array($_SESSION['nomes_alunos']) ? $_SESSION['nomes_alunos'] : [];
                echo 'const nomesAlunos = ' . json_encode($nomesMapa, JSON_UNESCAPED_UNICODE) . ';';
                ?>
                if (nomesAlunos && typeof nomesAlunos === 'object') {
                    for (const key in nomesAlunos) {
                        const pid = parseInt(key, 10);
                        const nome = nomesAlunos[key];
                        const j = jogadores.find(x => x.idPersonagem === pid);
                        if (j && typeof nome === 'string' && nome.trim().length > 0) {
                            j.nome = nome.trim();
                        }
                    }
                }
            })();


            let turno = 0;

            // Cria casas do tabuleiro
            for (let i = 0; i < linhas * colunas; i++) {
                const div = document.createElement("div");
                div.classList.add("casa");
                div.id = "casa-" + i;
                tabuleiro.appendChild(div);
            }

            // Define caminho do tabuleiro (espiral)
            const caminho = [];
            for (let i = 0; i < colunas; i++) caminho.push(i);
            for (let i = 1; i < linhas - 1; i++) caminho.push(i * colunas + (colunas - 1));
            for (let i = colunas * (linhas - 1) + (colunas - 1); i >= colunas * (linhas - 1); i--) caminho.push(i);
            for (let i = linhas - 2; i > 0; i--) caminho.push(i * colunas);

            // Define primeira e última casa
            document.getElementById("casa-" + caminho[0]).style.backgroundColor = "lightblue";
            document.getElementById("casa-" + caminho[caminho.length - 1]).style.backgroundColor = "gold";
            // Define primeira e última casa visualmente
            const casaInicio = document.getElementById("casa-" + caminho[0]);
            casaInicio.innerHTML = "🏁"; // emoji de bandeira
            casaInicio.classList.add("casa-inicio");

            const casaFinal = document.getElementById("casa-" + caminho[caminho.length - 1]);
            casaFinal.innerHTML = "🎓"; // emoji de formatura
            casaFinal.classList.add("casa-final");



            // Desenha bonecos
            function desenharBonecos() {
                document.querySelectorAll(".casa").forEach(c => {
                    // Salva fundo e emoji
                    const fundoOriginal = c.style.backgroundImage;
                    const emojiOriginal = c.classList.contains("casa-inicio") ? "🏁" :
                        c.classList.contains("casa-final") ? "🎓" : "";

                    // Remove apenas bonecos antigos
                    c.querySelectorAll(".boneco").forEach(b => b.remove());

                    // Restaura classes e fundo
                    c.classList.remove("ativo");
                    if (emojiOriginal) {
                        c.innerHTML = emojiOriginal;
                    }

                    // 🔹 Mantém o fundo da casa (para eventos com imagem)
                    if (fundoOriginal) {
                        c.style.backgroundImage = fundoOriginal;
                        c.style.backgroundSize = "60%";
                        c.style.backgroundRepeat = "no-repeat";
                        c.style.backgroundPosition = "center";
                        c.style.zindex = "1";
                    }
                });



                // Desenha os bonecos
                jogadores.forEach((j, index) => {
                    if (j.posicao >= caminho.length) {
                        return;
                    }

                    const idCasa = caminho[j.posicao];
                    const casaAtual = document.getElementById("casa-" + idCasa);
                    if (!casaAtual) {
                        return;
                    }

                    casaAtual.classList.add("ativo");

                    const span = document.createElement("span");
                    span.classList.add("boneco", j.classe);
                    span.innerText = j.emoji;

                    if (j.terminou) {
                        span.style.opacity = "0.6";
                        span.title = `${j.nome} terminou o jogo!`;
                    }

                    casaAtual.appendChild(span);
                });
            }


            desenharBonecos();

            // Popup de eventos
            function mostrarPopup(evento, callback) {
                document.getElementById("popupNome").innerText = "EVENTO: " + (evento.nome) + ":";
                document.getElementById("popupDescricao").innerText = evento.descricao;
                document.getElementById("popupModificador").innerText = "Casas: " + (evento.casas > 0 ? '+' + evento.casas : evento.casas);
                const popup = document.getElementById("popupEvento");
                popup.style.display = "flex";
                // Narração do popup
                try {
                    const desloc = (evento.casas === 0 || evento.casas === undefined) ? 'Sem deslocamento' : (evento.casas > 0 ? `Avance ${evento.casas} ${(evento.casas===1)?'casa':'casas'}` : `Volte ${Math.abs(evento.casas)} ${(Math.abs(evento.casas)===1)?'casa':'casas'}`);
                    const texto = `Evento: ${evento.nome}. ${evento.descricao}. ${desloc}.`;
                    narrador.speak(texto);
                } catch (e) {}
                const btn = popup.querySelector("button");
                btn.onclick = () => {
                    narrador.cancel();
                    popup.style.display = "none";
                    callback();
                }
            }

            // Movimento do jogador
            async function moverJogador(jogador, casas) {
                const passos = Math.abs(casas);
                const direcao = casas >= 0 ? 1 : -1;

                // Narra início do movimento
                try {
                    if (passos > 0) {
                        const fraseMov = direcao === 1
                            ? `${jogador.nome} avançará ${passos} ${(passos===1)?'casa':'casas'}.`
                            : `${jogador.nome} voltará ${passos} ${(passos===1)?'casa':'casas'}.`;
                        narrador.speak(fraseMov);
                    }
                } catch (e) {}

                for (let i = 0; i < passos; i++) {
                    await new Promise(resolve => {
                        setTimeout(() => {
                            jogador.posicao += direcao;

                            if (jogador.posicao >= caminho.length - 1) {
                                jogador.posicao = caminho.length - 1;
                                jogador.terminou = true;
                                if (!jogador.ordemChegada) {
                                    jogador.ordemChegada = jogadores.filter(j => j.terminou).length;
                                }
                                mostrarPopupVitoria(jogador); // 🏆 mostra a vitória

                                // Verificar se todos terminaram
                                setTimeout(() => {
                                    const todosTerminaram = jogadores.every(j => j.terminou);
                                    if (todosTerminaram) {
                                        mostrarPopupFinal();
                                    }
                                }, 2000);
                            }
                            if (jogador.posicao < 0) {
                                jogador.posicao = 0;
                            }
                            desenharBonecos();
                            resolve();
                        }, 300);
                    });
                }
                // Intencionalmente não narrar a casa final do jogador
            }


            // Função do dado
            let rolling = false;

            document.getElementById("dice-container").addEventListener("click", async () => {
                if (rolling) return;

                if (!jogadores || jogadores.length === 0) {
                    return;
                }

                if (jogadores[turno] === undefined) {
                    return;
                }

                rolling = true;
                const dado = Math.floor(Math.random() * 6) + 1;

                // Rotação 3D do dado
                let xRot = 0,
                    yRot = 0;
                switch (dado) {
                    case 1:
                        xRot = 0;
                        yRot = 0;
                        break;
                    case 2:
                        xRot = 90;
                        yRot = 0;
                        break;
                    case 3:
                        xRot = 0;
                        yRot = -90;
                        break;
                    case 4:
                        xRot = 0;
                        yRot = 90;
                        break;
                    case 5:
                        xRot = -90;
                        yRot = 0;
                        break;
                    case 6:
                        xRot = 180;
                        yRot = 0;
                        break;
                }
                xRot += 360 * (Math.floor(Math.random() * 3) + 3);
                yRot += 360 * (Math.floor(Math.random() * 3) + 3);
                document.getElementById("dice").style.transform = `rotateX(${xRot}deg) rotateY(${yRot}deg)`;

                setTimeout(async () => {
                    document.getElementById("resultado").innerText = "Saiu: " + dado;
                    try {
                        const jogadorAtual = jogadores[turno];
                        const quem = jogadorAtual ? `${jogadorAtual.nome}` : 'Jogador';
                        narrador.speak(`${quem}, saiu ${dado} no dado.`);
                    } catch (e) {}
                    await jogarDadoAnimado(dado);
                    rolling = false;
                }, 1000);
            });

            // Marca eventos nas casas
            eventos.forEach(e => {
                const idCasa = caminho[e.casa]; // agora e.casa já é zero-index
                const casaEl = document.getElementById("casa-" + idCasa);

                if (e.casas > 0) casaEl.classList.add("evento-bom");
                else if (e.casas < 0) casaEl.classList.add("evento-ruim");
                else casaEl.classList.add("evento");

                // Adiciona imagem do personagem como fundo sutil se for evento específico
                if (e.imagemDono) {
                    casaEl.style.backgroundImage = `url('./imageTabuleiro/${e.imagemDono}')`;
                    casaEl.style.backgroundSize = '60%';
                    casaEl.style.backgroundRepeat = 'no-repeat';
                    casaEl.style.backgroundPosition = 'center';

                }

                const tooltip = document.createElement("span");
                tooltip.classList.add("tooltip");
                const linha = Math.floor(idCasa / colunas);
                tooltip.classList.add(linha > linhas / 2 ? "cima" : "baixo");

                // Adiciona informação do personagem no tooltip se for evento específico
                let tooltipText = `${e.nome}\n${e.descricao}\nCasas: ${e.casas>0?'+':''}${e.casas}`;
                if (e.emojiDono) {
                    tooltipText += `\n\n👤 Evento específico do personagem: ${e.emojiDono}`;
                }
                tooltip.innerText = tooltipText;
                casaEl.appendChild(tooltip);
            });

            // Adicionar EVENTO RELÂMPAGO na penúltima casa
            const penultimaCasa = caminho[caminho.length - 2];
            const casaRelampago = document.getElementById("casa-" + penultimaCasa);
            casaRelampago.classList.add("evento-ruim");
            casaRelampago.style.backgroundColor = "#ff6b6b";
            // Adiciona ícone de bomba ao evento relâmpago (similar aos ícones de personagens)
            casaRelampago.style.backgroundImage = `url('./imageTabuleiro/bombaicone.png')`;
            casaRelampago.style.backgroundSize = '60%';
            casaRelampago.style.backgroundRepeat = 'no-repeat';
            casaRelampago.style.backgroundPosition = 'center';

            const tooltipRelampago = document.createElement("span");
            tooltipRelampago.classList.add("tooltip");
            const linhaRelampago = Math.floor(penultimaCasa / colunas);
            tooltipRelampago.classList.add(linhaRelampago > linhas / 2 ? "cima" : "baixo");
            tooltipRelampago.innerText = "EVENTO RELÂMPAGO\n⚡ VOCÊ TEVE QUE LARGAR A ESCOLA POR UM TEMPO. VOLTE AO INÍCIO!\nCasas: -" + (caminho.length - 1);
            casaRelampago.appendChild(tooltipRelampago);

            // Narração ao pairar sobre casas com evento (lê o tooltip após pequena pausa)
            (function setupHoverNarrationForEventTiles() {
                const tiles = Array.from(document.querySelectorAll('.casa'))
                    .filter(el => el.querySelector('.tooltip'));
                const hoverTimers = new WeakMap();

                function getTooltipSpeechText(tile) {
                    const tt = tile.querySelector('.tooltip');
                    if (!tt) return '';
                    return (tt.innerText || '')
                        .replace(/[\r\n]+/g, '. ')
                        .replace(/\s+/g, ' ')
                        .trim();
                }

                tiles.forEach(tile => {
                    tile.addEventListener('mouseenter', () => {
                        const existing = hoverTimers.get(tile);
                        if (existing) clearTimeout(existing);
                        const id = setTimeout(() => {
                            try {
                                if (!tile.matches(':hover')) return;
                                const text = getTooltipSpeechText(tile);
                                if (text) narrador.speak(text);
                            } catch (e) {}
                        }, 450);
                        hoverTimers.set(tile, id);
                    });
                    tile.addEventListener('mouseleave', () => {
                        const t = hoverTimers.get(tile);
                        if (t) {
                            clearTimeout(t);
                            hoverTimers.delete(tile);
                        }
                        // Interrompe a narração quando o mouse sai da casa do evento
                        try { narrador.cancel(); } catch (e) {}
                    });
                });
            })();

            // Função principal após rolar o dado
            async function jogarDadoAnimado(dado) {
                const jogadorAtual = jogadores[turno];

                if (!jogadorAtual) {
                    return;
                }

                if (jogadorAtual.terminou) {
                    proximoTurno();
                    return;
                }

                document.getElementById("infoTurno").innerText = "É A VEZ DO(A)	 " + jogadorAtual.nome + "!";

                // Move jogador
                await moverJogador(jogadorAtual, dado);

                // Contador de eventos para evitar loop infinito
                let eventosAtivados = 0;
                const limiteEventos = 1;

                while (eventosAtivados < limiteEventos) {
                    const eventoNaCasa = eventos.find(e =>
                        caminho[e.casa] === caminho[jogadorAtual.posicao] &&
                        (!e.idDono || e.idDono === jogadorAtual.idPersonagem)
                    );

                    // Verificar se está na penúltima casa (EVENTO RELÂMPAGO)
                    const penultimaCasa = caminho[caminho.length - 2];
                    if (caminho[jogadorAtual.posicao] === penultimaCasa) {
                        const eventoRelampago = {
                            nome: "EVENTO RELÂMPAGO",
                            descricao: "⚡ VOLTE AO INÍCIO!",
                            casas: -(caminho.length - 1)
                        };

                        await new Promise(resolve => {
                            mostrarPopup(eventoRelampago, resolve);
                        });

                        await moverJogador(jogadorAtual, eventoRelampago.casas);
                        adicionarHistorico(jogadorAtual, eventoRelampago, jogadorAtual.posicao, 0);
                        eventosAtivados++;
                        break;
                    }

                    if (!eventoNaCasa) break; // se não tem evento, sai do loop

                    const posicaoAntes = jogadorAtual.posicao;

                    await new Promise(resolve => {
                        mostrarPopup(eventoNaCasa, resolve);
                    });

                    if (eventoNaCasa.casas) {
                        await moverJogador(jogadorAtual, eventoNaCasa.casas);
                    }

                    const posicaoDepois = jogadorAtual.posicao;
                    adicionarHistorico(jogadorAtual, eventoNaCasa, posicaoAntes, posicaoDepois);

                    eventosAtivados++;
                }

                proximoTurno();
            }



            // Próximo turno
            function proximoTurno() {
                // gira até achar um jogador que ainda não terminou
                let tentativas = 0;
                do {
                    turno = (turno + 1) % jogadores.length;
                    tentativas++;
                } while (jogadores[turno].terminou && tentativas <= jogadores.length);

                // Se todos terminaram, exibe mensagem de fim
                const todosTerminaram = jogadores.every(j => j.terminou);
                if (todosTerminaram) {
                    document.getElementById("infoTurno").innerText = "🏁 Todos os jogadores terminaram!";
                    return;
                }

                const textoTurno = "É A VEZ DO(A) " + jogadores[turno].nome + "!";
                document.getElementById("infoTurno").innerText = textoTurno;
                try { narrador.speak(`É a vez de ${jogadores[turno].nome}.`); } catch (e) {}
                desenharBonecos();
            }
        </script>

        <!-- Históricos de eventos -->
        <div id="historico-negativo" class="historico">
            <h3 class="titulo-historico">EVENTOS NEGATIVOS</h3>
            <ul id="lista-negativa"></ul>
        </div>

        <div id="historico-positivo" class="historico" class="positivo">
            <h3 class="titulo-historico">EVENTOS POSITIVOS</h3>
            <ul id="lista-positiva"></ul>
        </div>


        <script>
            function adicionarHistorico(jogador, evento, posicaoAnterior, posicaoFinal) {
                const tipo = evento.casas >= 0 ? "positivo" : "negativo";
                const container = tipo === "positivo" ?
                    document.getElementById("historico-positivo") :
                    document.getElementById("historico-negativo");

                const msg = document.createElement("div");
                msg.innerText = `${jogador.emoji} ${jogador.nome}: Casa ${posicaoAnterior + 1} → Casa ${posicaoFinal + 1} (${evento.nome})`;
                container.appendChild(msg);

                // Scroll automático
                container.scrollTop = container.scrollHeight;

                // ✅ Só salva no histórico pessoal se for um evento NEGATIVO
                if (evento.casas < 0) {
                    jogador.historicoPessoal.push(evento);
                }
            }


            function mostrarPopupVitoria(jogador) {
                const popup = document.getElementById("popupVitoria");
                const titulo = document.getElementById("vitoriaTitulo");
                const mensagem = document.getElementById("vitoriaMensagem");
                const lista = document.getElementById("vitoriaHistorico");

                titulo.innerText = `🏆 ${jogador.emoji} ${jogador.nome} SE FORMOU!`;

                lista.innerHTML = "";

                const dificuldades = jogador.historicoPessoal.filter(ev => ev.casas < 0);

                if (dificuldades.length === 0) {
                    // 🟢 Caso não tenha passado por dificuldades
                    mensagem.innerText = "PARABÉNS! VOCÊ SE FORMOU COM UMA JORNADA TRANQUILA E SEM OBSTÁCULOS!";
                    const item = document.createElement("LI");
                    item.innerText = "NENHUMA DIFICULDADE ENFRENTADA.";
                    lista.appendChild(item);
                } else {
                    // 🔴 Caso tenha passado por dificuldades
                    mensagem.innerText = "PARABÉNS! VOCÊ SE FORMOU MESMO PASSANDO POR DIFICULDADES!";
                    dificuldades.forEach(ev => {
                        const item = document.createElement("LI");
                        item.innerText = `${ev.nome}`;
                        lista.appendChild(item);
                    });
                }

                popup.style.display = "flex";
                try {
                    const texto = `${titulo.innerText}. ${mensagem.innerText}`;
                    narrador.speak(texto);
                } catch (e) {}
            }



            function fecharPopupVitoria() {
                document.getElementById("popupVitoria").style.display = "none";
                try { narrador.cancel(); } catch (e) {}
            }

            function mostrarPopupFinal() {
                const popup = document.getElementById("popupFinal");
                const titulo = document.getElementById("finalTitulo");
                const mensagem = document.getElementById("finalMensagem");
                const lista = document.getElementById("finalOrdem");

                titulo.innerText = "🎓 PARABÉNS TODOS VOCÊS CONSEGUIRAM SE FORMAR!";
                mensagem.innerText = "TODOS OS JOGADORES COMPLETARAM SUA JORNADA ACADÊMICA!";

                lista.innerHTML = "";

                // Ordenar jogadores por ordem de chegada
                const jogadoresOrdenados = [...jogadores].sort((a, b) => (a.ordemChegada || 999) - (b.ordemChegada || 999));

                jogadoresOrdenados.forEach((j, index) => {
                    const item = document.createElement("LI");
                    const posicao = index + 1;
                    const medalha = posicao === 1 ? "🥇" : posicao === 2 ? "🥈" : posicao === 3 ? "🥉" : "🏅";
                    item.innerText = `${medalha} ${posicao}º LUGAR: ${j.emoji} ${j.nome}`;
                    lista.appendChild(item);
                });

                popup.style.display = "flex";
                try { narrador.speak(`${titulo.innerText}. ${mensagem.innerText}`); } catch (e) {}
            }

            function fecharPopupFinal() {
                window.location.href = "../selecionarPartida.php";
            }
        </script>

        <!-- Popup de vitória -->
        <div id="popupVitoria" class="popup">
            <div class="popup-conteudo">
                <h2 id="vitoriaTitulo">🎓 PARABÉNS!</h2>
                <p id="vitoriaMensagem"></p>
                <ul id="vitoriaHistorico"></ul>
                <button onclick="fecharPopupVitoria()">FECHAR</button>
            </div>
        </div>

        <!-- Popup final (todos terminaram) -->
        <div id="popupFinal" class="popup">
            <div class="popup-conteudo" style="width: 500px; max-width: 95%;">
                <h2 id="finalTitulo">🎓 PARABÉNS TODOS VOCÊS CONSEGUIRAM SE FORMAR!</h2>
                <p id="finalMensagem">TODOS OS JOGADORES COMPLETARAM SUA JORNADA ACADÊMICA!</p>
                <h3 style="margin: 20px 0 10px 0; color: #2c3e50;">🏆 ORDEM DE CHEGADA:</h3>
                <ul id="finalOrdem" style="text-align: left; margin: 0; padding: 0; list-style: none;">
                    <!-- Será preenchido pelo JavaScript -->
                </ul>
                <button onclick="fecharPopupFinal()" style="margin-top: 20px; background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 12px 24px; border-radius: 25px; font-weight: bold; cursor: pointer;">
                    🏠 VOLTAR À SELEÇÃO DE PARTIDA
                </button>
            </div>
        </div>

        <!-- Botão Voltar -->
        <a href="../index.php" id="btn-voltar">
            ← VOLTAR
        </a>

        <!-- Popup confirmação de saída -->
        <div id="popupSair" class="popup" aria-hidden="true" role="dialog" aria-labelledby="tituloSair">
            <div class="popup-conteudo">
                <h2 id="tituloSair">TEM CERTEZA QUE DESEJA SAIR?</h2>
                <p>SE CONCLUIR ESSA AÇÃO VOCÊ PERDERÁ TODO O SEU PROGRESSO</p>
                <div style="display:flex; gap:10px; justify-content:center; margin-top:10px;">
                    <button id="confirmarSair" style="background-color:#dc3545;">SIM, VOLTAR</button>
                    <button id="cancelarSair">CANCELAR</button>
                </div>
            </div>
        </div>

        <script>
            // Interceptar botão voltar com confirmação
            (function() {
                const linkVoltar = document.getElementById('btn-voltar');
                const popup = document.getElementById('popupSair');
                const confirmar = document.getElementById('confirmarSair');
                const cancelar = document.getElementById('cancelarSair');
                if (linkVoltar) {
                    const destino = linkVoltar.getAttribute('href');
                    linkVoltar.addEventListener('click', function(ev) {
                        ev.preventDefault();
                        popup.style.display = 'flex';
                        popup.setAttribute('aria-hidden', 'false');
                        try {
                            const titulo = document.getElementById('tituloSair');
                            const msg = 'SE CONCLUIR ESSA AÇÃO VOCÊ PERDERÁ TODO O SEU PROGRESSO';
                            narrador.speak(`${titulo ? titulo.innerText : 'Tem certeza que deseja sair?'} ${msg}.`);
                        } catch (e) {}
                    });
                    confirmar.addEventListener('click', function() {
                        try { narrador.cancel(); } catch (e) {}
                        window.location.href = destino;
                    });
                    cancelar.addEventListener('click', function() {
                        popup.style.display = 'none';
                        popup.setAttribute('aria-hidden', 'true');
                        try { narrador.cancel(); } catch (e) {}
                    });
                    popup.addEventListener('click', function(e) {
                        if (e.target === popup) {
                            cancelar.click();
                        }
                    });
                    window.addEventListener('keydown', function(e) {
                        if (e.key === 'Escape') {
                            cancelar.click();
                        }
                    });
                }
            })();

            // --- Legenda: abrir/fechar e preencher itens ---
            (function() {
                const btnLegenda = document.getElementById('btn-legenda');
                const popupLegenda = document.getElementById('popupLegenda');
                const lista = document.getElementById('lista-legenda');
                const fechar = document.getElementById('fecharLegenda');

                function abrirLegenda() {
                    preencherLegenda();
                    popupLegenda.style.display = 'flex';
                    popupLegenda.setAttribute('aria-hidden', 'false');
                }

                function fecharLegenda() {
                    popupLegenda.style.display = 'none';
                    popupLegenda.setAttribute('aria-hidden', 'true');
                }

                function addItem(emoji, texto, iconUrl, color) {
                    const li = document.createElement('li');
                    const spanEmoji = document.createElement('span');
                    spanEmoji.className = 'emoji';
                    spanEmoji.textContent = emoji;
                    if (!emoji) {
                        spanEmoji.style.width = '0';
                    }
                    if (iconUrl) {
                        const img = document.createElement('img');
                        img.className = 'icon';
                        img.src = iconUrl;
                        img.alt = '';
                        li.appendChild(img);
                    }
                    const spanTxt = document.createElement('span');
                    spanTxt.textContent = texto;
                    if (color) {
                        spanTxt.style.color = color;
                    }
                    li.appendChild(spanEmoji);
                    li.appendChild(spanTxt);
                    lista.appendChild(li);
                }

                function preencherLegenda() {
                    lista.innerHTML = '';
                    // Elementos fixos do tabuleiro
                    addItem('🏁', 'INÍCIO DO PERCURSO');
                    addItem('🎓', 'CHEGADA | FORMATURA');
                    addItem('', 'CASAS VERMELHAS → EVENTOS NEGATIVOS', null, '#e74c3c');
                    addItem('', 'CASAS AZUIS → EVENTOS POSITIVOS', null, '#3498db');

                    // Personagens da partida: só adiciona ícones de evento dos que estão no tabuleiro
                    const vistosPersonagens = new Set();
                    (window.personagensSelecionados || []).forEach(p => {
                        const pid = parseInt(p.idPersonagem, 10);
                        if (!pid || vistosPersonagens.has(pid)) return;
                        vistosPersonagens.add(pid);
                        const nome = (p.nome || p.nomePersonagem || 'PERSONAGEM').toString().trim().toUpperCase();
                        const isMulher = nome.includes('MULHER');
                        const artigo = isMulher ? 'DA' : 'DO';
                        const nomeAjustado = nome.replace(/^CEGO$/i, 'DEFICIENTE VISUAL');
                        const texto = `EVENTO ${artigo} ${nomeAjustado}`;
                        const iconPath = p.imagem ? `./imageTabuleiro/${p.imagem}` : null;
                        addItem('', texto, iconPath);
                    });
                    // Jogadores atuais (emoji + nome)
                    if (window.jogadores && Array.isArray(window.jogadores)) {
                        const vistos = new Set();
                        window.jogadores.forEach(j => {
                            if (!vistos.has(j.idPersonagem)) {
                                vistos.add(j.idPersonagem);
                                const pComp = (window.personagensSelecionados || []).find(p => parseInt(p.idPersonagem, 10) === parseInt(j.idPersonagem, 10));
                                let pNome = (pComp && (pComp.nome || pComp.nomePersonagem)) ? (pComp.nome || pComp.nomePersonagem) : 'Personagem';
                                pNome = pNome.toString().toUpperCase().replace(/^CEGO$/i, 'DEFICIENTE VISUAL');
                                addItem(j.emoji || '👤', pNome + ': ' + (j.nome || 'PARTICIPANTE'), '');
                            }
                        });
                    }
                }

                // Abrir/fechar
                btnLegenda.addEventListener('click', abrirLegenda);
                fechar.addEventListener('click', fecharLegenda);
                popupLegenda.addEventListener('click', (e) => {
                    if (e.target === popupLegenda) fecharLegenda();
                });
                window.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') fecharLegenda();
                });
            })();
        </script>

        <script>
            // Toggle do botão de narração
            (function() {
                const btn = document.getElementById('btn-narracao');
                if (!btn) return;
                // Estado inicial
                const ativo = (function(){ try { return localStorage.getItem('narracaoAtiva') !== '0'; } catch(e) { return true; } })();
                narrador.setEnabled(ativo);
                btn.setAttribute('aria-pressed', ativo ? 'true' : 'false');
                btn.title = ativo ? 'Desativar narração' : 'Ativar narração';
                btn.textContent = ativo ? '🔊' : '🔇';

                btn.addEventListener('click', function() {
                    const novo = !(btn.getAttribute('aria-pressed') === 'true');
                    narrador.setEnabled(novo);
                    btn.setAttribute('aria-pressed', novo ? 'true' : 'false');
                    btn.title = novo ? 'Desativar narração' : 'Ativar narração';
                    btn.textContent = novo ? '🔊' : '🔇';
                });
            })();
        </script>


    </body>

    </html>