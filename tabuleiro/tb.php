    <?php
    include('../banco/conexao.php');
    session_start();

    // Verificar se há configuração salva na sessão
    if (isset($_SESSION['configuracao_partida'])) {
        $configuracao = $_SESSION['configuracao_partida'];
        $personagensSelecionados = $configuracao['personagens'];
        $eventosCasas = $configuracao['eventosCasas'];
        
        // Usar configuração salva
        $personagensCompletos = array_map(function ($p) {
            $imagensPersonagens = [
                1 => 'idosoicone.jpg',
                2 => 'cegoicone.jpg',
                3 => 'negraicone.png',
                4 => 'retiranteicone.png',
                5 => 'transicone.png',
                6 => 'umbandaicone.png'
            ];

            return [
                'idPersonagem' => intval($p['idPersonagem'] ?? $p['id'] ?? 0),
                'nome' => $p['nomePersonagem'] ?? $p['nome'] ?? 'Personagem',
                'emoji' => $p['emoji'] ?? '👤',
                'imagem' => $imagensPersonagens[intval($p['idPersonagem'] ?? $p['id'] ?? 0)] ?? 'miniidoso.png'
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
            $personagensCompletos = array_map(function ($p) {
                // Mapeia ID do personagem para imagem
                $imagensPersonagens = [
                    1 => 'idosoicone.jpg',      // Idoso
                    2 => 'cegoicone.jpg',       // Cego
                    3 => 'negraicone.png',      // Mulher Negra
                    4 => 'retiranteicone.png',  // Retirante
                    5 => 'transicone.png',      // Mulher Trans
                    6 => 'umbandaicone.png'     // Umbandista
                ];

                return [
                    'idPersonagem' => intval($p['idPersonagem']),
                    'nome' => $p['nomePersonagem'],
                    'emoji' => $p['emoji'],
                    'imagem' => $imagensPersonagens[intval($p['idPersonagem'])] ?? 'miniidoso.png'
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
    </head>

    <body>
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
            <p id="infoTurno">É A VEZ DO(A) JOGADOR(A) 1</p>
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

        <script>
            const linhas = 10;
            const colunas = 16;
            const tabuleiro = document.getElementById("tabuleiro");

            const eventos = <?php echo json_encode($eventos); ?>;
            const personagensSelecionados = <?php echo json_encode($personagensCompletos); ?>;

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
                document.getElementById("popupNome").innerText = evento.nome;
                document.getElementById("popupDescricao").innerText = evento.descricao;
                document.getElementById("popupModificador").innerText = "Casas: " + (evento.casas > 0 ? '+' + evento.casas : evento.casas);
                const popup = document.getElementById("popupEvento");
                popup.style.display = "flex";
                const btn = popup.querySelector("button");
                btn.onclick = () => {
                    popup.style.display = "none";
                    callback();
                }
            }

            // Movimento do jogador
            async function moverJogador(jogador, casas) {
                const passos = Math.abs(casas);
                const direcao = casas >= 0 ? 1 : -1;

                for (let i = 0; i < passos; i++) {
                    await new Promise(resolve => {
                        setTimeout(() => {
                            jogador.posicao += direcao;

                            if (jogador.posicao >= caminho.length - 1) {
                                jogador.posicao = caminho.length - 1;
                                jogador.terminou = true;
                                mostrarPopupVitoria(jogador); // 🏆 mostra a vitória
                            }
                            if (jogador.posicao < 0) {
                                jogador.posicao = 0;
                            }
                            desenharBonecos();
                            resolve();
                        }, 300);
                    });
                }
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

                document.getElementById("infoTurno").innerText = "É a vez de " + jogadorAtual.nome;

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

                document.getElementById("infoTurno").innerText = "É a vez de " + jogadores[turno].nome;
                desenharBonecos();
            }
        </script>

        <!-- Históricos de eventos -->
        <div id="historico-negativo" class="historico">
            <h3 class="titulo-historico">EVENTOS NEGATIVOS</h3>
            <ul id="lista-negativa"></ul>
        </div>

        <div id="historico-positivo" class="historico">
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
            }



            function fecharPopupVitoria() {
                document.getElementById("popupVitoria").style.display = "none";
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


    </body>

    </html>