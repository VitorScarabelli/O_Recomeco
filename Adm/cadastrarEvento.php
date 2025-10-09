<?php include('../banco/conexao.php');
include('../includes/verificar_login.php'); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Evento - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }

        .admin-container {
            max-width: 800px;
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

        .form-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            border: 2px solid #e9ecef;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }

        .btn-submit {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 1.1rem;
            transition: transform 0.3s ease;
            width: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
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
        }

        .back-btn:hover {
            background: rgba(52, 73, 94, 1);
            color: white;
            text-decoration: none;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .form-row {
            display: flex;
            gap: 20px;
        }

        .form-row .form-group {
            flex: 1;
        }

        .help-text {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }

        .preview-section {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-top: 20px;
            border: 2px dashed #dee2e6;
        }

        .preview-title {
            color: #495057;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .preview-item {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
            border-left: 4px solid #667eea;
        }
    </style>
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

                        echo '<div class="alert alert-success">✅ EVENTO CADASTRADO COM SUCESSO!</div>';

                        // Limpar campos após sucesso
                        $nomeEvento = $descricaoEvento = '';
                        $casaEvento = 0;
                        $temaAula = '';
                        $novoTema = '';
                    } catch (PDOException $e) {
                        echo '<div class="alert alert-danger">❌ Erro ao cadastrar evento: ' . $e->getMessage() . '</div>';
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
    </div>

    <script>
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