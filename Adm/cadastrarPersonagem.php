<?php include('../banco/conexao.php'); include('../includes/verificar_login.php'); ?>
<?php
$errors = [];
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Campos texto
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $emoji = trim($_POST['emoji'] ?? '');

    if ($nome === '') $errors[] = 'Nome é obrigatório';
    if ($descricao === '') $errors[] = 'Descrição é obrigatória';

    // Validar emoji básico como no exemplo do usuário
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
        $orig = $_FILES['icone']['name'];
        $size = intval($_FILES['icone']['size']);
        $maxSize = 2 * 1024 * 1024; // 2MB

        if ($size <= 0 || $size > $maxSize) {
            $errors[] = 'Tamanho do ícone inválido (máx 2MB)';
        } else {
            // Validar MIME pelo conteúdo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = $finfo ? finfo_file($finfo, $tmp) : null;
            if ($finfo) finfo_close($finfo);

            $ext = null;
            if ($mime === 'image/png') $ext = 'png';
            if ($mime === 'image/jpeg') $ext = 'jpg';

            if (!$ext) {
                $errors[] = 'Apenas PNG ou JPG são permitidos';
            } else {
                // Sanitiza nome e cria destino
                $slug = preg_replace('/[^a-z0-9]+/i', '-', strtolower($nome));
                $slug = trim($slug, '-');
                $unique = bin2hex(random_bytes(4));
                $fileName = $slug ? ($slug . '-' . $unique . '.' . $ext) : ('personagem-' . $unique . '.' . $ext);
                $destDir = realpath(__DIR__ . '/../tabuleiro/imageTabuleiro');
                if ($destDir === false) {
                    $errors[] = 'Pasta de imagens do tabuleiro não encontrada';
                } else {
                    $destPath = $destDir . DIRECTORY_SEPARATOR . $fileName;
                    if (!move_uploaded_file($tmp, $destPath)) {
                        $errors[] = 'Falha ao salvar o arquivo de ícone';
                    } else {
                        $iconeArquivo = $fileName; // salvar apenas o nome do arquivo
                    }
                }
            }
        }
    } else {
        $errors[] = 'Erro no upload do ícone (código ' . intval($_FILES['icone']['error']) . ')';
    }

    if (empty($errors)) {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("INSERT INTO tbpersonagem (nomePersonagem, descricaoPersonagem, emojiPersonagem, iconePersonagem) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome, $descricao, $emoji, $iconeArquivo]);
            $idPersonagem = $pdo->lastInsertId();
            $pdo->commit();
            $sucesso = true;
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
    <link rel="stylesheet" href="./css/cadastrarEvento.css">
</head>
<body>
    <a href="configurarPartida.php" class="back-btn">← Voltar</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">➕ Novo Personagem</h1>
            <p class="admin-subtitle">Cadastre um personagem com ícone (PNG/JPG)</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $er) { echo '❌ ' . htmlspecialchars($er) . '<br>'; } ?>
            </div>
        <?php endif; ?>

        <?php if ($sucesso): ?>
            <div class="alert alert-success">✅ Personagem criado com sucesso!</div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="card-box">
                <div class="mb-3">
                    <label class="form-label">Nome *</label>
                    <input type="text" class="form-control" name="nome" placeholder="Ex: ENFERMEIRA" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Descrição *</label>
                    <textarea class="form-control" name="descricao" rows="3" placeholder="Descreva brevemente o personagem" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Emoji (token) *</label>
                    <input type="text" class="form-control" name="emoji" placeholder="Ex: 👩‍⚕️" maxlength="10" required>
                    <div class="muted">
                        <p>USE SOMENTE EMOJIS (Windows + .)</p>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ícone do personagem (PNG/JPG) *</label>
                    <input type="file" class="form-control" name="icone" accept="image/png, image/jpeg" required>
                    <div class="muted">A imagem será salva em <code>tabuleiro/imageTabuleiro/</code> e usada no tabuleiro.</div>
                </div>
            </div>

            <br>

            <button type="submit" class="btn btn-success">💾 Salvar personagem</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
