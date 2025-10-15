<?php include('../banco/conexao.php'); include('../includes/verificar_login.php');?>

<?php
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: configurarPartida.php'); exit; }

// Carregar personagem
$stmt = $pdo->prepare("SELECT * FROM tbpersonagem WHERE idPersonagem = ?");
$stmt->execute([$id]);
$personagem = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$personagem) { header('Location: configurarPartida.php'); exit; }

$errors = [];
if ($_POST) {
    $nome = trim($_POST['nome'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $emoji = trim($_POST['emoji'] ?? '');
    if ($nome === '') $errors[] = 'Nome é obrigatório';
    if ($descricao === '') $errors[] = 'Descrição é obrigatória';
    if ($emoji === '') $errors[] = 'Emoji é obrigatório';
    else {
        $stripped = preg_replace('/\x{FE0F}|\x{200D}/u', '', $emoji);
        if (preg_match('/[A-Za-z0-9]/', $stripped)) $errors[] = 'O token deve ser somente emoji (sem letras ou números)';
    }
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE tbpersonagem SET nomePersonagem = ?, descricaoPersonagem = ?, emojiPersonagem = ? WHERE idPersonagem = ?");
        $stmt->execute([$nome, $descricao, $emoji, $id]);
        header('Location: configurarPartida.php?personagemAtualizado=1');
        exit;
    }
}

// Buscar eventos do personagem (bons e ruins)
$stmt = $pdo->prepare("SELECT * FROM tbeventopersonagem WHERE idPersonagem = ? ORDER BY idEvento ASC");
$stmt->execute([$id]);
$eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Personagem - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/editarPartida.css">
</head>
<body>
    <a href="configurarPartida.php" class="back-btn">← Voltar</a>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">✏️ Editar Personagem</h1>
            <p class="admin-subtitle">Atualize os dados e gerencie os eventos do personagem</p>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $er) { echo '❌ ' . htmlspecialchars($er) . '<br>'; } ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nome *</label>
                    <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($personagem['nomePersonagem']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Emoji *</label>
                    <input type="text" class="form-control" name="emoji" maxlength="10" value="<?php echo htmlspecialchars($personagem['emojiPersonagem']); ?>" required>
                </div>
                <div class="col-12">
                    <label class="form-label">Descrição *</label>
                    <textarea class="form-control" name="descricao" rows="3" required><?php echo htmlspecialchars($personagem['descricaoPersonagem']); ?></textarea>
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-success">💾 Salvar</button>
            </div>
        </form>

        <div class="card p-3">
            <h5>Eventos do personagem</h5>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>DESCRIÇÃO</th>
                            <th>CASAS</th>
                            <th>TIPO</th>
                            <th>AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($eventos as $ev): ?>
                        <tr>
                            <td><?php echo intval($ev['idEvento']); ?></td>
                            <td><?php echo htmlspecialchars($ev['nomeEvento']); ?></td>
                            <td><?php echo htmlspecialchars($ev['descricaoEvento']); ?></td>
                            <td><?php echo ($ev['casas'] > 0 ? '+' : '') . intval($ev['casas']); ?></td>
                            <td><?php echo htmlspecialchars(strtoupper($ev['tipo'])); ?></td>
                            <td>
                                <a class="btn btn-sm btn-primary" href="editarEventoPersonagem.php?id=<?php echo intval($ev['idEvento']); ?>">✏️ Editar</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


