<?php include('../banco/conexao.php');
include('../includes/verificar_login.php'); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Evento - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/cadastrarEvento.css">
</head>

<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">➕ CADASTRAR EVENTOS</h1>
            <p class="admin-subtitle">CRIE NOVOS EVENTOS PARA O JOGO</p>
        </div>

        <div class="form-section">
            <?php
            $modalSucesso = false;
            if ($_POST) {
                $nomeEvento   = $_POST['nomeEvento'] ?? '';
                $descricaoEvento = $_POST['descricaoEvento'] ?? '';
                $casaEvento   = $_POST['casaEvento'] ?? 0;
                $temaAula     = $_POST['temaAula'] ?? '';
                $novoTema     = $_POST['novoTema'] ?? ''; // capturando o novo tema

                // Se o usuário criou um novo tema
                if ($temaAula === 'novo' && !empty($novoTema)) {
                    $temaAula = $novoTema;
                }

                // Validações
                $errors = [];

                if (empty($nomeEvento)) {
                    $errors[] = "NOME DO EVENTO É OBRIGATÓRIO";
                }

                if (empty($descricaoEvento)) {
                    $errors[] = "DESCRIÇÃO DO EVENTO É OBRIGATÓRIA";
                }

                if (empty($temaAula)) {
                    $errors[] = "TEMA DA AULA É OBRIGATÓRIO";
                }

                if (!is_numeric($casaEvento)) {
                    $errors[] = "NÚMERO DE CASAS DEVE SER UM VALOR NUMÉRICO";
                }

                if (empty($errors)) {
                    try {
                        $stmt = $pdo->prepare("INSERT INTO tbevento (nomeEvento, descricaoEvento, casaEvento, temaAula) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$nomeEvento, $descricaoEvento, $casaEvento, $temaAula]);
                        // Marcar para mostrar modal de sucesso
                        $modalSucesso = true;

                        // Limpar campos após sucesso
                        $nomeEvento = $descricaoEvento = '';
                        $casaEvento = 0;
                        $temaAula = '';
                        $novoTema = '';
                    } catch (PDOException $e) {
                        echo '<div class="alert alert-danger">❌ ERRO AO CADASTRAR EVENTO: ' . $e->getMessage() . '</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger">❌ ' . implode('<br>', $errors) . '</div>';
                }
            }
            ?>


            <form method="POST">
                <div class="form-group">
                    <label for="nomeEvento" class="form-label">NOME DO EVENTO *</label>
                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento"
                        value="<?php echo htmlspecialchars($nomeEvento ?? ''); ?>"
                        placeholder="EX.: ACESSO AO SUS" required>
                    <div class="help-text">NOME CURTO E DESCRITIVO DO EVENTO</div>
                </div>

                <div class="form-group">
                    <label for="descricaoEvento" class="form-label">DESCRIÇÃO DO EVENTO *</label>
                    <textarea class="form-control" id="descricaoEvento" name="descricaoEvento"
                        rows="4" placeholder="DESCREVA O QUE ACONTECE NO EVENTO..." required><?php echo htmlspecialchars($descricaoEvento ?? ''); ?></textarea>
                    <div class="help-text">DESCRIÇÃO DETALHADA DO QUE ACONTECE QUANDO O EVENTO É ATIVADO</div>
                </div>

                <div class="form-group">
                    <label for="casaEvento" class="form-label">NÚMERO DE CASAS *</label>
                    <input type="number" class="form-control" id="casaEvento" name="casaEvento"
                        value="<?php echo $casaEvento ?? 0; ?>"
                        placeholder="Ex: 2 ou -2"
                        min="-8" max="8" required>
                    <div class="help-text">POSITIVO = AVANÇA CASAS, NEGATIVO = VOLTA CASAS</div>
                </div>

                <div class="form-group">
                    <?php
                    $stmt = $pdo->query("SELECT DISTINCT temaAula FROM tbEvento WHERE temaAula IS NOT NULL AND temaAula <> '' ORDER BY temaAula ASC");
                    $temas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <label for="temaAula" class="form-label">TEMA DA AULA *</label>
                    <select class="form-control" id="temaAula" name="temaAula" required>
                        <option value="">SELECIONE UM TEMA</option>

                        <?php foreach ($temas as $t): ?>
                            <option value="<?= htmlspecialchars($t['temaAula']) ?>">
                                <?= htmlspecialchars($t['temaAula']) ?>
                            </option>
                        <?php endforeach; ?>

                        <option value="novo">+ ADICIONAR NOVO TEMA...</option>
                    </select>

                    <div id="novoTemaDiv" style="display:none; margin-top:8px;">
                        <input type="text" class="form-control" name="novoTema" id="novoTema"
                            placeholder="Digite o novo tema" />
                        <div class="help-text">DIGITE O TEMA QUE SERÁ TRABALHADO NA AULA (PODE SER QUALQUER TEMA)</div>
                    </div>
                </div>

                <script>
                    document.getElementById('temaAula').addEventListener('change', function() {
                        const div = document.getElementById('novoTemaDiv');
                        div.style.display = (this.value === 'novo') ? 'block' : 'none';
                    });
                </script>

                <button type="submit" class="btn-submit">💾 CADASTRAR EVENTO</button>
            </form>

            <div class="preview-section">
                <h4 class="preview-title">📋 PREVIEW DO EVENTO</h4>
                <div class="preview-item">
                    <strong>NOME:</strong> <span id="preview-nome">-</span><br>
                    <strong>DESCRIÇÃO:</strong> <span id="preview-descricao">-</span><br>
                    <strong>CASAS:</strong> <span id="preview-casas">-</span><br>
                    <strong>TEMA DA AULA:</strong> <span id="preview-tema">-</span>
                </div>
            </div>
        </div>

        <br><br><br><br>

        <!-- Paginação entre páginas -->
        <div class="pagination-section">
            <div class="pagination-container">
                <div class="pagination-nav">
                    <a href="index.php" class="pagination-btn">‹‹ INÍCIO</a>
                    <a href="index.php" class="pagination-btn">1</a>
                    <a href="cadastrarEvento.php" class="pagination-btn active">2</a>
                    <a href="gerenciarEventos.php" class="pagination-btn">3</a>
                    <a href="configurarPartida.php" class="pagination-btn">4</a>
                    <a href="configurarPartida.php" class="pagination-btn">FINAL ››</a>
                </div>
            </div>
        </div>

        <div id="modalSucesso" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); align-items:center; justify-content:center;">
            <div class="conteudoModal" style="background:white; padding:20px; border-radius:8px; max-width:90%; text-align:center;">
                <h2 class="modalText">✅ EVENTO CRIADO COM SUCESSO!</h2>
                <button class="modal-btn btn btn-primary" onclick="fecharModal()">FECHAR</button>
            </div>
        </div>

    </div>

        <script>
        function fecharModal() {
            const modal = document.getElementById('modalSucesso');
            if (modal) modal.style.display = 'none';
        }

        // Se foi cadastrado com sucesso, exibe o modal (injetado pelo PHP)
        <?php if (!empty($modalSucesso)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                const modal = document.getElementById('modalSucesso');
                if (modal) modal.style.display = 'flex';
            });
        <?php endif; ?>

        const temaAula = document.getElementById('temaAula');
        const novoTemaDiv = document.getElementById('novoTemaDiv');
        const novoTemaInput = document.getElementById('novoTema');

        temaAula.addEventListener('change', function() {
            if (this.value === 'novo') { // verifica se selecionou "novo"
                novoTemaDiv.style.display = 'block'; // mostra o input
                novoTemaInput.focus(); // foca no input
            } else {
                novoTemaDiv.style.display = 'none'; // esconde o input
            }
        });

        // Preview em tempo real do tema (selecionado ou digitado)
        temaAula.addEventListener('input', () => {
            const previewTema = document.getElementById('preview-tema');
            if (temaAula.value === 'novo') {
                previewTema.textContent = novoTemaInput.value || '-';
            } else {
                previewTema.textContent = temaAula.value || '-';
            }
        });

        novoTemaInput.addEventListener('input', () => {
            const previewTema = document.getElementById('preview-tema');
            previewTema.textContent = novoTemaInput.value || '-';
        });

        document.getElementById('temaAula').addEventListener('change', function() {
            document.getElementById('novoTemaDiv').style.display = (this.value === 'NOVO') ? 'block' : '-';
        });

        // Preview em tempo real
        document.getElementById('nomeEvento').addEventListener('input', function() {
            document.getElementById('preview-nome').textContent = this.value || '-';
        });

        document.getElementById('descricaoEvento').addEventListener('input', function() {
            document.getElementById('preview-descricao').textContent = this.value || '-';
        });

        document.getElementById('casaEvento').addEventListener('input', function() {
            const value = this.value;
            document.getElementById('preview-casas').textContent = value ? (value > 0 ? '+' + value : value) : '-';
        });

        document.getElementById('temaAula').addEventListener('input', () => {
            document.getElementById('preview-tema').textContent = document.getElementById('temaAula').value || '-';
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>