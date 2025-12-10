<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPark - Meus Acessos</title>
    <link rel="icon" href="../imagens/barreira.png" type="image/x-icon">
    <!-- Ligação ao CSS externo -->
    <link rel="stylesheet" href="../css/utilizadores_acessos.css">
</head>

<body>
    <?php include('header.php'); ?>

    <div class="container">
        <div class="page-header">
            <h1>📊 Meus Acessos</h1>
            <p>Visualize seu histórico completo de entradas e saídas</p>
        </div>

        <div class="user-welcome">
            <div class="user-info-box">
                <div class="user-avatar" id="userAvatar">?</div>
                <div class="user-details">
                    <div class="user-name" id="userName">Carregando...</div>
                    <div class="user-number" id="userNumber">Nº ------</div>
                </div>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">📈</div>
                <div class="stat-value" id="totalAcessos">--</div>
                <div class="stat-label">Total de Acessos</div>
            </div>
            <div class="stat-card entrada">
                <div class="stat-icon">🚗</div>
                <div class="stat-value" id="totalEntradas">--</div>
                <div class="stat-label">Entradas</div>
            </div>
            <div class="stat-card saida">
                <div class="stat-icon">🚀</div>
                <div class="stat-value" id="totalSaidas">--</div>
                <div class="stat-label">Saídas</div>
            </div>
            <div class="stat-card tempo">
                <div class="stat-icon">⏱️</div>
                <div class="stat-value" id="tempoTotal">--</div>
                <div class="stat-label">Tempo Total (horas)</div>
            </div>
        </div>

        <div class="filters-section">
            <div class="filters-title">🔍 Filtros de Pesquisa</div>
            <div class="filters-grid">
                <div class="filter-group">
                    <label class="filter-label">Tipo de Acesso</label>
                    <select class="filter-select" id="filterTipo">
                        <option value="">Todos</option>
                        <option value="entrada">Entradas</option>
                        <option value="saida">Saídas</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Parque</label>
                    <select class="filter-select" id="filterParque">
                        <option value="">Todos os parques</option>
                        <option value="1">Parque 1</option>
                        <option value="2">Parque 2</option>
                        <option value="3">Parque 3</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Data Início</label>
                    <input type="date" class="filter-input" id="filterDataInicio">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Data Fim</label>
                    <input type="date" class="filter-input" id="filterDataFim">
                </div>
            </div>
            <div class="filter-actions">
                <button class="btn btn-secondary" onclick="limparFiltros()">🔄 Limpar</button>
                <button class="btn btn-primary" onclick="aplicarFiltros()">🔍 Filtrar</button>
            </div>
        </div>

        <div class="content-section">
            <div class="table-wrapper">
                <table class="access-table">
                    <thead>
                        <tr>
                            <th>Tipo</th>
                            <th>Parque</th>
                            <th>Data/Hora</th>
                            <th>Duração</th>
                        </tr>
                    </thead>
                    <tbody id="accessTableBody">
                        <tr>
                            <td colspan="4" class="empty-state">
                                <div class="empty-icon">🔄</div>
                                <div class="empty-text">Carregando seus acessos...</div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button class="btn btn-secondary back-button" onclick="window.history.back()">
                ← Voltar
            </button>
        </div>
    </div>

    <?php include('footer.php'); ?>

    <!-- Ligação ao JS externo -->
    <script src="../js/utilizadores_acessos.js"></script>
</body>

</html>