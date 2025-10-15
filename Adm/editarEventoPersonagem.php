<?php include('../banco/conexao.php'); include('../includes/verificar_login.php');?>

<?php
$id = $_GET['id'] ?? null;
if (!$id) {
    header('Location: gerenciarEventos.php');
    exit;
}

// Buscar evento de personagem
$stmt = $pdo->prepare("SELECT * FROM tbeventopersonagem WHERE idEvento = ?");
$stmt->execute([$id]);
$evento = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$evento) {
    header('Location: gerenciarEventos.php');
    exit;
}

// Processar formulário
if ($_POST) {
     $nomeEvento = $_POST['nomeEvento'] ?? '';
     $descricaoEvento = $_POST['descricaoEvento'] ?? '';
     $casas = $_POST['casas'] ?? 0;
     $idPersonagem = $_POST['idPersonagem'] ?? $evento['idPersonagem'];
    
    $errors = [];
    if (empty($nomeEvento)) $errors[] = 'Nome do evento é obrigatório';
    if (empty($descricaoEvento)) $errors[] = 'Descrição do evento é obrigatória';
    if (!is_numeric($casas)) $errors[] = 'Número de casas deve ser numérico';
    if (!is_numeric($idPersonagem)) $errors[] = 'Personagem inválido';

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE tbeventopersonagem SET nomeEvento = ?, descricaoEvento = ?, casas = ?, idPersonagem = ? WHERE idEvento = ?");
            $stmt->execute([$nomeEvento, $descricaoEvento, $casas, $idPersonagem, $id]);
            header('Location: gerenciarEventos.php?success=1');
            exit;
        } catch (PDOException $e) {
            $error = 'Erro ao atualizar: ' . $e->getMessage();
        }
    } else {
        $error = implode('<br>', $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Evento de Personagem - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/editarEvento.css">
</head>
<body>
    <a href="configurarPartida.php" class="back-btn">← Voltar</a>
    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">✏️ Editar Evento de Personagem</h1>
            <p class="admin-subtitle">Modifique os dados do evento específico do personagem</p>
        </div>

        <div class="form-section">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">❌ <?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="nomeEvento" class="form-label">Nome do Evento *</label>
                    <input type="text" class="form-control" id="nomeEvento" name="nomeEvento" 
                           value="<?php echo htmlspecialchars($evento['nomeEvento']); ?>" required>
                </div>

                <div class="form-group">
                    <label for="descricaoEvento" class="form-label">Descrição do Evento *</label>
                    <textarea class="form-control" id="descricaoEvento" name="descricaoEvento" rows="4" required><?php echo htmlspecialchars($evento['descricaoEvento']); ?></textarea>
                </div>

                <div class="form-group">
                    <label for="casas" class="form-label">Número de Casas *</label>
                    <input type="number" class="form-control" id="casas" name="casas" value="<?php echo intval($evento['casas']); ?>" required>
                </div>
                <button type="submit" class="btn-submit">💾 SALVAR ALTERAÇÕES</button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


