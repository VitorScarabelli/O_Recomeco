    <?php
    include('../banco/conexao.php');

    // --- Jogadores escolhidos ---
    $personagensSelecionados = isset($_POST['personagens']) ? json_decode($_POST['personagens'], true) : [];
    
    // Log para debug
    error_log("Personagens recebidos via POST: " . print_r($personagensSelecionados, true));

    // Verifica se há personagens selecionados, senão usa fallback
    if (!empty($personagensSelecionados) && is_array($personagensSelecionados)) {
        $personagensCompletos = array_map(function($p) {
            return [
                'idPersonagem' => intval($p['idPersonagem']),
                'nome' => $p['nomePersonagem'],
                'emoji' => $p['emoji']
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
                'emoji' => '👳🏽‍♂️'
            ],
            [
                'idPersonagem' => 3,
                'nome' => 'Mulher Negra', 
                'emoji' => '👩🏽‍🦱'
            ]
        ];
    }


    // --- Modos de eventos ---
    $modoAleatorio = isset($_POST['modoAleatorio']);
    $filtros = $_POST['filtros'] ?? [];
    $eventosSelecionados = $_POST['eventos'] ?? [];
    $eventos = [];

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
            $stmt = $pdo->prepare("SELECT * FROM tbEventoPersonagem WHERE idPersonagem = ?");
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
                    'idDono' => $p['idPersonagem']
                ];
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
            <p id="infoTurno">É a vez do(a) Jogador(a) 1</p>
            <p id="resultado"></p>
        </div>

        <div class="tabuleiro" id="tabuleiro"></div>

        <div id="popupEvento" class="popup">
            <div class="popup-conteudo">
                <h2 id="popupNome">Nome do Evento</h2>
                <p id="popupDescricao">Descrição do evento</p>
                <p id="popupModificador">Casas: </p>
                <button>Fechar</button>
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
                    // Salva o emoji da casa inicial/final se existir
                    const emojiOriginal = c.classList.contains("casa-inicio") ? "🏁" :
                        c.classList.contains("casa-final") ? "🎓" : "";

                    // Remove bonecos antigos
                    c.querySelectorAll(".boneco").forEach(b => b.remove());
                    // Remove a classe ativo
                    c.classList.remove("ativo");

                    // Restaura emoji inicial/final
                    if (emojiOriginal) {
                        c.innerHTML = emojiOriginal;
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

                const tooltip = document.createElement("span");
                tooltip.classList.add("tooltip");
                const linha = Math.floor(idCasa / colunas);
                tooltip.classList.add(linha > linhas / 2 ? "cima" : "baixo");
                tooltip.innerText = `${e.nome}\n${e.descricao}\nCasas: ${e.casas>0?'+':''}${e.casas}`;
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
            <h3 class="titulo-historico">Eventos Negativos</h3>
            <ul id="lista-negativa"></ul>
        </div>

        <div id="historico-positivo" class="historico">
            <h3 class="titulo-historico">Eventos Positivos</h3>
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

                titulo.innerText = `🏆 ${jogador.emoji} ${jogador.nome} se formou!`;

                lista.innerHTML = "";

                const dificuldades = jogador.historicoPessoal.filter(ev => ev.casas < 0);

                if (dificuldades.length === 0) {
                    // 🟢 Caso não tenha passado por dificuldades
                    mensagem.innerText = "Parabéns! Você se formou com uma jornada tranquila e sem obstáculos!";
                    const item = document.createElement("li");
                    item.innerText = "Nenhuma dificuldade enfrentada.";
                    lista.appendChild(item);
                } else {
                    // 🔴 Caso tenha passado por dificuldades
                    mensagem.innerText = "Parabéns, você conseguiu se formar mesmo passando por dificuldades!";
                    dificuldades.forEach(ev => {
                        const item = document.createElement("li");
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
                <h2 id="vitoriaTitulo">🎓 Parabéns!</h2>
                <p id="vitoriaMensagem"></p>
                <ul id="vitoriaHistorico"></ul>
                <button onclick="fecharPopupVitoria()">Fechar</button>
            </div>
        </div>


    </body>

    </html>