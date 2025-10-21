<?php
include('../banco/conexao.php');
include('../includes/verificar_login.php');

$errors = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Campos texto
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $emoji = trim($_POST['emoji'] ?? '');

    if ($nome === '') $errors[] = 'Nome é obrigatório';
    if ($descricao === '') $errors[] = 'Descrição é obrigatória';

    // Validação do emoji
    if ($emoji === '') {
        $errors[] = 'Emoji é obrigatório';
    } else {
        $stripped = preg_replace('/\x{FE0F}|\x{200D}/u', '', $emoji);
        if (preg_match('/[A-Za-z0-9]/', $stripped)) {
            $errors[] = 'O token deve ser somente emoji (sem letras ou números)';
        }
        if (!preg_match('/\p{So}|\p{Emoji}/u', $emoji)) {
            if (!preg_match('/[\x{1F000}-\x{1FAFF}]/u', $emoji)) {
                $errors[] = 'Informe um emoji válido';
            }
        }
    }

    // Upload do ícone (PNG/JPG)
    $iconeArquivo = null;
    if (!isset($_FILES['icone']) || $_FILES['icone']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = 'Ícone (PNG/JPG) é obrigatório';
    } else if (isset($_FILES['icone']) && $_FILES['icone']['error'] === UPLOAD_ERR_OK) {
        $tmp = $_FILES['icone']['tmp_name'];
        $size = intval($_FILES['icone']['size']);
        $maxSize = 2 * 1024 * 1024; // 2MB

        if ($size <= 0 || $size > $maxSize) {
            $errors[] = 'Tamanho do ícone inválido (máx 2MB)';
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo ? finfo_file($finfo, $tmp) : null;
            if ($finfo) finfo_close($finfo);

            $ext = ($mime === 'image/png') ? 'png' : (($mime === 'image/jpeg') ? 'jpg' : null);

            if (!$ext) {
                $errors[] = 'Apenas PNG ou JPG são permitidos';
            } else {
                $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($nome));
                $slug = trim($slug, '-');
                $unique = bin2hex(random_bytes(4));
                $fileName = ($slug ? $slug : 'personagem') . '-' . $unique . '.' . $ext;
                $destDir = realpath(__DIR__ . '/../tabuleiro/imageTabuleiro');

                if ($destDir === false) {
                    $errors[] = 'Pasta de imagens do tabuleiro não encontrada';
                } else {
                    $destPath = $destDir . DIRECTORY_SEPARATOR . $fileName;
                    if (!move_uploaded_file($tmp, $destPath)) {
                        $errors[] = 'Falha ao salvar o arquivo de ícone';
                    } else {
                        $iconeArquivo = $fileName;
                    }
                }
            }
        }
    } else {
        $errors[] = 'Erro no upload do ícone (código ' . intval($_FILES['icone']['error']) . ')';
    }

    // Eventos bons e ruins
    $bons = $_POST['eventos_bons'] ?? [];
    $ruins = $_POST['eventos_ruins'] ?? [];
    if (!is_array($bons) || count($bons) < 2) $errors[] = 'Cadastre pelo menos 2 eventos bons';
    if (!is_array($ruins) || count($ruins) < 2) $errors[] = 'Cadastre pelo menos 2 eventos ruins';

    // Se tudo estiver certo, salva tudo de uma vez
    if (empty($errors)) {
        try {
            $pdo->beginTransaction();

            // ✅ Cadastra o personagem apenas uma vez
            $stmt = $pdo->prepare("INSERT INTO tbpersonagem (nomePersonagem, descricaoPersonagem, emojiPersonagem, iconePersonagem) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $descricao, $emoji, $iconeArquivo]);
            $idPersonagem = $pdo->lastInsertId();

            // ✅ Insere eventos bons
            foreach ($bons as $b) {
                $nomeEv = trim($b['nome'] ?? '');
                $descEv = trim($b['descricao'] ?? '');
                $casas = intval($b['casas'] ?? 2);
                if ($nomeEv && $descEv && $casas > 0) {
                    $stmt = $pdo->prepare("INSERT INTO tbeventopersonagem (idPersonagem, nomeEvento, descricaoEvento, casas, tipo) VALUES (?, ?, ?, ?, 'bom')");
                    $stmt->execute([$idPersonagem, $nomeEv, $descEv, $casas]);
                }
            }

            // ✅ Insere eventos ruins
            foreach ($ruins as $r) {
                $nomeEv = trim($r['nome'] ?? '');
                $descEv = trim($r['descricao'] ?? '');
                $casas = -abs(intval($r['casas'] ?? 2));
                if ($nomeEv && $descEv && $casas < 0) {
                    $stmt = $pdo->prepare("INSERT INTO tbeventopersonagem (idPersonagem, nomeEvento, descricaoEvento, casas, tipo) VALUES (?, ?, ?, ?, 'ruim')");
                    $stmt->execute([$idPersonagem, $nomeEv, $descEv, $casas]);
                }
            }

            $pdo->commit();
            header('Location: configurarPartida.php?novoPersonagem=1');
            exit;
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}
?>


    <!DOCTYPE html>
    <html lang="pt-BR">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Novo Personagem - Admin</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="./css/criarPersonagem.css">
    </head>

    <body>
        <a href="configurarPartida.php" class="back-btn">← VOLTAR</a>

        <div class="admin-container">
            <div class="admin-header">
                <h1 class="admin-title">➕ NOVO PERSONAGEM</h1>
                <p class="admin-subtitle">CADASTRE UM PERSONAGEM COM 2 EVENTOS BONS E 2 RUINS</p>
            </div>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $er) {
                        echo '❌ ' . htmlspecialchars($er) . '<br>';
                    } ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="card-box">
                    <div class="mb-3">
                        <label class="form-label">NOME *</label>
                        <input type="text" class="form-control" name="nome" placeholder="Ex: ENFERMEIRA" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">DESCRIÇÃO *</label>
                        <textarea class="form-control" name="descricao" rows="3" placeholder="Descreva brevemente o personagem" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">EMOJI (TOKEN) *</label>
                        <input type="text" class="form-control" name="emoji" placeholder="Ex: 👩‍⚕️" maxlength="10" required>
                        <div class="muted">
                            <p>USE SOMENTE EMOJIS, PARA ABRIR A TABELA DE EMOJIS DO COMPUTADOR APERTE A TECLA WINDOWS + "."</p>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" text-transform: uppercase;>ÍCONE DO PERSONAGEM (PNG/JPG) *</label>
                        <input type="file" class="form-control" name="icone" accept="image/png, image/jpeg" required>
                        <div class="muted">A IMAGEM SERÁ USADA COMO IMAGEM DO EVENTO NO TABULEIRO.</div>
                    </div>
                </div>

                <br>

                <div class="card-box">
                    <h5 class="bom">EVENTOS BONS</h5>
                    <div class="grid-2">
                        <?php for ($i = 0; $i < 2; $i++): ?>
                            <div class="evt-item">
                                <div class="mb-2">
                                    <label class="form-label">NOME *</label>
                                    <input type="text" class="form-control" name="eventos_bons[<?php echo $i; ?>][nome]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">DESCRIÇÃO *</label>
                                    <textarea class="form-control" rows="2" name="eventos_bons[<?php echo $i; ?>][descricao]" required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">CASAS POSITIVAS *</label>
                                    <input type="number" class="form-control" name="eventos_bons[<?php echo $i; ?>][casas]" min="1" value="2" required>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <br>

                <div class="card-box">
                    <h5 class="ruim">EVENTOS RUINS</h5>
                    <div class="grid-2">
                        <?php for ($i = 0; $i < 2; $i++): ?>
                            <div class="evt-item">
                                <div class="mb-2">
                                    <label class="form-label">NOME *</label>
                                    <input type="text" class="form-control" name="eventos_ruins[<?php echo $i; ?>][nome]" required>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">DESCRIÇÃO *</label>
                                    <textarea class="form-control" rows="2" name="eventos_ruins[<?php echo $i; ?>][descricao]" required></textarea>
                                </div>
                                <div class="mb-2">
                                    <label class="form-label">CASAS NEGATIVAS *</label>
                                    <input type="number" class="form-control" name="eventos_ruins[<?php echo $i; ?>][casas]" min="1" value="2" required>
                                    <div class="muted">OS EVENTOS SERÃO SALVOS COMO NEGATIVOS AUTOMATICAMENTE</div>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <br>

                <button type="submit" class="btn-confirm">💾 CADASTRAR PERSONAGEM</button>
            </form>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
    </body>

    </html>