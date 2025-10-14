<?php include('../banco/conexao.php');
include('../includes/verificar_login.php'); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Eventos - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./css/gerenciarEventos.css">
</head>

<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">📝 GERENCIAR EVENTOS</h1>
            <p class="admin-subtitle">VISUALIZE, EDITE E EXCLUA EVENTOS DO JOGO</p>
        </div>
        
        <div class="search-section">
            <form method="GET" class="search-form">
                <div class="form-group">
                    <label for="search" class="form-label">BUSCAR POR NOME:</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" 
                           placeholder="DIGITE O NOME DO EVENTO..." autocomplete="off">
                    <div id="search-suggestions" class="search-suggestions"></div>
                </div>
                
                <div class="form-group">
                    <label for="tema" class="form-label">FILTRAR POR TEMA DA AULA:</label>
                    <input type="text" class="form-control" id="tema" name="tema" 
                           value="<?php echo isset($_GET['tema']) ? htmlspecialchars($_GET['tema']) : ''; ?>" 
                           placeholder="DIGITE O TEMA DA AULA..." autocomplete="off">
                    <div id="tema-suggestions" class="search-suggestions"></div>
                </div>
                
                <button type="submit" class="btn search-btn">🔍 BUSCAR</button>
                <a href="gerenciarEventos.php" class="btn clear-btn">🗑️ LIMPAR</a>
            </form>
        </div>

        <div class="events-section">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ EVENTO ATUALIZADO COM SUCESSO!</div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">✅ EVENTO EXCLUÍDO COM SUCESSO!</div>
            <?php endif; ?>

            <a href="cadastrarEvento.php" class="add-event-btn">➕ ADICIONAR NOVO EVENTO</a>

            <div class="events-table">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>NOME</th>
                            <th>DESCRIÇÃO</th>
                            <th>TIPO</th>
                            <th>TEMA DA AULA</th>
                            <th>CASAS</th>
                            <th>AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Construir query com filtros
                        $whereConditions = [];
                        $params = [];

                        if (isset($_GET['search']) && !empty($_GET['search'])) {
                            $whereConditions[] = "nomeEvento LIKE ?";
                            $params[] = '%' . $_GET['search'] . '%';
                        }

                        if (isset($_GET['tema']) && !empty($_GET['tema'])) {
                            $whereConditions[] = "temaAula LIKE ?";
                            $params[] = '%' . $_GET['tema'] . '%';
                        }

                        $whereClause = !empty($whereConditions) ? 'WHERE ' . implode(' AND ', $whereConditions) : '';
                        $query = "SELECT * FROM tbevento $whereClause ORDER BY idEvento ASC";

                        $stmt = $pdo->prepare($query);
                        $stmt->execute($params);

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $tipo = $row['casaEvento'] > 0 ? 'POSITIVO' : 'NEGATIVO';
                            $casas = $row['casaEvento'];
                            $temaAula = $row['temaAula'] ?? 'NÃO DEFINIDO';

                            echo "<tr>";
                            echo "<td>{$row['idEvento']}</td>";
                            echo "<td><strong>{$row['nomeEvento']}</strong></td>";
                            echo "<td>" . substr($row['descricaoEvento'], 0, 100) . (strlen($row['descricaoEvento']) > 100 ? '...' : '') . "</td>";
                            echo "<td><span class='badge-tipo badge-{$tipo}'>" . ucfirst($tipo) . "</span></td>";
                            echo "<td><span class='badge-tema'>" . htmlspecialchars($temaAula) . "</span></td>";
                            echo "<td><strong>" . ($casas > 0 ? '+' : '') . $casas . "</strong></td>";
                            echo "<td>";
                            echo "<a href='editarEvento.php?id={$row['idEvento']}' class='btn btn-action btn-edit'>✏️ EDITAR</a>";
                            echo "<a href='excluirEvento.php?id={$row['idEvento']}' class='btn btn-action btn-delete' onclick='return confirm(\"TEM CERTEZA QUE DESEJA EXCLUIR ESTE EVENTO?\")'>🗑️ EXCLUIR</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <br><br><br><br>

         <!-- Paginação entre páginas -->
        <div class="pagination-section">
            <div class="pagination-container">
                <div class="pagination-nav">
                    <a href="index.php" class="pagination-btn">‹‹ INÍCIO</a>
                    <a href="index.php" class="pagination-btn">1</a>
                    <a href="cadastrarEvento.php" class="pagination-btn">2</a>
                    <a href="gerenciarEventos.php" class="pagination-btn active">3</a>
                    <a href="configurarPartida.php" class="pagination-btn">4</a>
                    <a href="configurarPartida.php" class="pagination-btn">FINAL ››</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Autocomplete para busca de eventos
        document.getElementById('search').addEventListener('input', function() {
            const query = this.value;
            const suggestions = document.getElementById('search-suggestions');

            if (query.length < 2) {
                suggestions.style.display = 'none';
                return;
            }

            fetch(`buscarEventos.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestions.innerHTML = data.map(evento =>
                            `<div class="suggestion-item" onclick="selecionarEvento('${evento}')">${evento}</div>`
                        ).join('');
                        suggestions.style.display = 'block';
                    } else {
                        suggestions.style.display = 'none';
                    }
                })
                .catch(error => {
                    suggestions.style.display = 'none';
                });
        });

        // Autocomplete para tema
        document.getElementById('tema').addEventListener('input', function() {
            const query = this.value;
            const suggestions = document.getElementById('tema-suggestions');

            if (query.length < 1) {
                suggestions.style.display = 'none';
                return;
            }

            fetch(`buscarTemas.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        suggestions.innerHTML = data.map(tema =>
                            `<div class="suggestion-item" onclick="selecionarTema('${tema}')">${tema}</div>`
                        ).join('');
                        suggestions.style.display = 'block';
                    } else {
                        suggestions.style.display = 'none';
                    }
                })
                .catch(error => {
                    suggestions.style.display = 'none';
                });
        });

        function selecionarEvento(evento) {
            document.getElementById('search').value = evento;
            document.getElementById('search-suggestions').style.display = 'none';
        }

        function selecionarTema(tema) {
            document.getElementById('tema').value = tema;
            document.getElementById('tema-suggestions').style.display = 'none';
        }

        // Esconder sugestões ao clicar fora
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.form-group')) {
                document.getElementById('search-suggestions').style.display = 'none';
                document.getElementById('tema-suggestions').style.display = 'none';
            }
        });
    </script>
</body>

</html>