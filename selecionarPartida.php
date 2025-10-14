<?php 
include('./banco/conexao.php');
session_start();
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selecionar Partida - O Recomeço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/selecionarPartida.css">
    <style>
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
    </style>
</head>

<body>
    <a href="./index.php" class="back-btn">← VOLTAR</a>

    <div class="container">
        <div class="header">
            <h1>🎮 SELECIONAR PARTIDA</h1>
            <p>DIGITE O CÓDIGO DA PARTIDA PARA COMEÇAR A JOGAR</p>
        </div>

        <?php if (isset($_GET['erro'])): ?>
            <div style="background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 10px; padding: 15px; margin-bottom: 20px; text-align: center; animation: shake 0.5s ease-in-out;">
        <?php
                switch ($_GET['erro']) {
                    case 'codigo_invalido':
                        $mensagemErro = "❌ CÓDIGO INVÁLIDO! DIGITE EXATAMENTE 6 CARACTERES.";
                        break;
                    case 'codigo_nao_encontrado':
                        $mensagemErro = "❌ CÓDIGO NÃO ENCONTRADO! VERIFIQUE SE DIGITOU CORRETAMENTE OU SE A PARTIDA AINDA ESTÁ ATIVA.";
                        break;
                    case 'partida_nao_encontrada':
                        $mensagemErro = "❌ PARTIDA NÃO ENCONTRADA!";
                        break;
                    case 'metodo_invalido':
                        $mensagemErro = "❌ MÉTODO INVÁLIDO!";
                        break;
                    default:
                        $mensagemErro = "❌ ERRO DESCONHECIDO!";
                }
                echo $mensagemErro;
                ?>
            </div>
        <?php endif; ?>

        <div class="config-section" style="background: rgba(255, 255, 255, 0.95); border-radius: 20px; padding: 40px; margin-bottom: 40px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2); text-align: center;">
            <h2 style="color: #2c3e50; font-size: 1.8rem; font-weight: bold; margin-bottom: 25px;">🎯 DIGITE O CÓDIGO DA PARTIDA</h2>
            <p style="color: #6c757d; margin-bottom: 30px; font-size: 1.1rem;">O PROFESSOR FORNECERÁ UM CÓDIGO DE 6 CARACTERES PARA VOCÊ ENTRAR NA PARTIDA</p>
            
            <form id="form-codigo" method="POST" action="carregarPartida.php" style="max-width: 400px; margin: 0 auto;">
                <div style="margin-bottom: 20px;">
                    <input type="text" name="codigoPartida" placeholder="Ex: FS24YH" maxlength="6" 
                           style="width: 100%; padding: 15px; border: 2px solid #667eea; border-radius: 10px; font-size: 1.5rem; text-align: center; font-weight: bold; text-transform: uppercase;" 
                           required autocomplete="off">
                </div>
                <button type="submit" style="background: linear-gradient(135deg, #28a745, #20c997); color: white; border: none; padding: 15px 30px; border-radius: 25px; font-weight: bold; font-size: 1.1rem; transition: all 0.3s ease; width: 100%;">
                    🎮 ENTRAR NA PARTIDA
                </button>
            </form>
        </div>

        <!-- Após código válido: inputs de nome para todos os personagens -->
        <div id="escolha-personagem" style="display:none; background: rgba(255, 255, 255, 0.95); border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);">
            <h2 style="color:#2c3e50; font-size:1.6rem; font-weight:bold; text-align:center; margin-bottom: 20px;">👥 DIGITE O NOME DE CADA JOGADOR</h2>
            <form id="form-jogador" method="POST" action="carregarPartida.php" style="display:flex; flex-direction:column; gap:14px; align-items:stretch; max-width:640px; margin:0 auto;">
                <input type="hidden" name="acao" value="definirNomes">
                <div id="lista-nomes"></div>
                <button type="submit" id="btn-confirmar-jogador" disabled 
                        style="background: linear-gradient(135deg, #0069d9, #0053ba); color: white; border: none; padding: 12px 22px; border-radius: 25px; font-weight: bold; align-self:center;">
                    ✅ CONFIRMAR NOMES E IR AO TABULEIRO
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Transformar input em maiúsculas automaticamente
        document.querySelector('input[name="codigoPartida"]').addEventListener('input', function() {
            this.value = this.value.toUpperCase();
        });

        // Validação do formulário
        document.getElementById('form-codigo').addEventListener('submit', async function(e) {
            const codigo = document.querySelector('input[name="codigoPartida"]').value.trim();
            
            // Verificar se o código tem exatamente 6 caracteres
            if (codigo.length !== 6) {
                e.preventDefault();
                alert('❌ CÓDIGO INVÁLIDO!\n\nDIGITE EXATAMENTE 6 CARACTERES.\n\nExemplo: FS24YH');
                return false;
            }
            
            // Verificar se contém apenas letras e números
            if (!/^[A-Z0-9]{6}$/.test(codigo)) {
                e.preventDefault();
                alert('❌ CÓDIGO INVÁLIDO!\n\nUSE APENAS LETRAS E NÚMEROS.\n\nExemplo: FS24YH');
                return false;
            }

            // Tenta validar por AJAX para exibir seleção de personagem
            e.preventDefault();
            try {
                const form = new FormData();
                form.append('codigoPartida', codigo);
                const resp = await fetch('carregarPartida.php', {
                    method: 'POST',
                    headers: { 'Accept': 'application/json' },
                    body: form
                });
                if (!resp.ok) {
                    throw new Error('Código inválido ou não encontrado');
                }
                const data = await resp.json();
                if (!data || !data.ok) {
                    throw new Error('Falha ao validar código');
                }

                // Mostrar seleção de personagem
                const cont = document.getElementById('escolha-personagem');
                const lista = document.getElementById('lista-nomes');
                cont.style.display = 'block';
                lista.innerHTML = '';

                const personagens = Array.isArray(data.personagens) ? data.personagens : [];
                // Renderizar um input por personagem
                personagens.forEach((p, idx) => {
                    const id = parseInt(p.idPersonagem || p.id, 10);
                    let nome = p.nomePersonagem || p.nome || 'PERSONAGEM';
                    if (/^CEGO$/i.test(nome)) nome = 'DEFICIENTE VISUAL';
                    const emoji = p.emoji || '👤';
                    const wrap = document.createElement('div');
                    wrap.style.display = 'grid';
                    wrap.style.gridTemplateColumns = '64px 1fr';
                    wrap.style.gap = '10px';
                    wrap.style.alignItems = 'center';
                    wrap.innerHTML = `
                        <div style="display:flex; align-items:center; justify-content:center; font-size:32px;">${emoji}</div>
                        <div style="display:flex; gap:10px; align-items:center;">
                            <label style="min-width:140px; font-weight:bold;">${nome}</label>
                            <input type="text" name="nomes[${id}]" placeholder="Nome do(a) jogador(a)" maxlength="32" required
                                   style="flex:1; padding: 10px 12px; border:2px solid #667eea; border-radius:10px; font-weight:bold;">
                        </div>
                    `;
                    lista.appendChild(wrap);
                });

                // Habilitar botão somente quando todos inputs não estiverem vazios
                function validarTodos() {
                    const campos = lista.querySelectorAll('input[type="text"][name^="nomes["]');
                    let ok = true;
                    campos.forEach(i => { if (i.value.trim().length === 0) ok = false; });
                    document.getElementById('btn-confirmar-jogador').disabled = !ok;
                }
                lista.addEventListener('input', validarTodos);
                validarTodos();

                // Scroll até a seção
                cont.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } catch (err) {
                alert('❌ CÓDIGO NÃO ENCONTRADO!\n\nVERIFIQUE SE DIGITOU CORRETAMENTE OU SE A PARTIDA AINDA ESTÁ ATIVA.');
                // fallback: submit normal
                this.submit();
            }
        });

    </script>
</body>

</html>
