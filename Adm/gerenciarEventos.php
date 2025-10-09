<?php include('../banco/conexao.php');
include('../includes/verificar_login.php'); ?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Eventos - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            font-family: 'Arial', sans-serif;
        }

        .admin-container {
            max-width: 1400px;
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

        .events-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .events-table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 15px;
            font-weight: bold;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #eee;
        }

        .table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge-tipo {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 15px;
        }

        .badge-positivo {
            background-color: #28a745;
            color: white;
        }

        .badge-negativo {
            background-color: #dc3545;
            color: white;
        }

        .badge-tema {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 15px;
            background-color: #6c757d;
            color: white;
            font-weight: bold;
        }

        .btn-action {
            margin: 2px;
            padding: 5px 10px;
            font-size: 0.8rem;
        }

        .btn-edit {
            background-color: #17a2b8;
            border-color: #17a2b8;
            color: white;
        }

        .btn-delete {
            background-color: #dc3545;
            border-color: #dc3545;
            color: white;
        }

        .btn-edit:hover {
            background-color: #138496;
            border-color: #117a8b;
            color: white;
        }

        .btn-delete:hover {
            background-color: #c82333;
            border-color: #bd2130;
            color: white;
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
            z-index: 9999;
        }

        .back-btn:hover {
            background: rgba(52, 73, 94, 1);
            color: white;
            text-decoration: none;
        }

        .search-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 5px 5px;
            max-height: 200px;
            overflow-y: auto;
            z-index: 1000;
            display: none;
        }

        .suggestion-item {
            padding: 10px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }

        .suggestion-item:hover {
            background-color: #f8f9fa;
        }

        .suggestion-item:last-child {
            border-bottom: none;
        }

        .form-group {
            position: relative;
        }

        .add-event-btn {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            text-decoration: none;
            font-weight: bold;
            transition: transform 0.3s ease;
            display: inline-block;
            margin-bottom: 20px;
        }

        .add-event-btn:hover {
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }

        .search-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .search-form {
            display: flex;
            gap: 15px;
            align-items: end;
            flex-wrap: wrap;
        }

        .search-form .form-group {
            flex: 1;
            min-width: 200px;
        }

        .search-btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
        }

        .clear-btn {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 25px;
            font-weight: bold;
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

        .table td,
        .table th {
            word-wrap: break-word;
            white-space: normal;
            /* Permite quebra de linha */
            max-width: 180px;
            /* Ajusta conforme o tamanho da sua tabela */
        }

        .table td:nth-child(5) {
            word-wrap: break-word;
            white-space: normal;
            max-width: 180px;
        }
    </style>
</head>

<body>
    <a href="index.php" class="back-btn">← VOLTAR</a>

    <div class="admin-container">
        <div class="admin-header">
            <h1 class="admin-title">📝 Gerenciar Eventos</h1>
            <p class="admin-subtitle">Visualize, edite e exclua eventos do jogo</p>
        </div>

        <div class="search-section">
            <form method="GET" class="search-form">
                <div class="form-group">
                    <label for="search" class="form-label">BUSCAR POR NOME:</label>
                    <input type="text" class="form-control" id="search" name="search"
                        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                        placeholder="Digite o nome do evento..." autocomplete="off">
                    <div id="search-suggestions" class="search-suggestions"></div>
                </div>

                <div class="form-group">
                    <label for="tema" class="form-label">FILTRAR POR TEMA DA AULA:</label>
                    <input type="text" class="form-control" id="tema" name="tema"
                        value="<?php echo isset($_GET['tema']) ? htmlspecialchars($_GET['tema']) : ''; ?>"
                        placeholder="Digite o tema da aula..." autocomplete="off">
                    <div id="tema-suggestions" class="search-suggestions"></div>
                </div>

                <button type="submit" class="btn search-btn">🔍 Buscar</button>
                <a href="gerenciarEventos.php" class="btn clear-btn">🗑️ Limpar</a>
            </form>
        </div>

        <div class="events-section">
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success">✅ Evento atualizado com sucesso!</div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div class="alert alert-success">✅ Evento excluído com sucesso!</div>
            <?php endif; ?>

            <a href="cadastrarEvento.php" class="add-event-btn">➕ Adicionar Novo Evento</a>

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
                            $tipo = $row['casaEvento'] > 0 ? 'positivo' : 'negativo';
                            $casas = $row['casaEvento'];
                            $temaAula = $row['temaAula'] ?? 'Não definido';

                            echo "<tr>";
                            echo "<td>{$row['idEvento']}</td>";
                            echo "<td><strong>{$row['nomeEvento']}</strong></td>";
                            echo "<td>" . substr($row['descricaoEvento'], 0, 100) . (strlen($row['descricaoEvento']) > 100 ? '...' : '') . "</td>";
                            echo "<td><span class='badge-tipo badge-{$tipo}'>" . ucfirst($tipo) . "</span></td>";
                            echo "<td><span class='badge-tema'>" . htmlspecialchars($temaAula) . "</span></td>";
                            echo "<td><strong>" . ($casas > 0 ? '+' : '') . $casas . "</strong></td>";
                            echo "<td>";
                            echo "<a href='editarEvento.php?id={$row['idEvento']}' class='btn btn-action btn-edit'>✏️ Editar</a>";
                            echo "<a href='excluirEvento.php?id={$row['idEvento']}' class='btn btn-action btn-delete' onclick='return confirm(\"Tem certeza que deseja excluir este evento?\")'>🗑️ Excluir</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
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