<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado e se é administrador (segurança extra)
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> <!-- Essencial para mobile -->
    <title>EasyPark - Gestão de Acessos</title>
    <link rel="icon" href="../../imagens/barreira.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/administracao.css">
    <link rel="stylesheet" href="../../css/historico.css">
</head>
<body>
    <?php include 'sidebar.php'; ?>
    

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- HEADER -->
        <div class="header">
            <div class="header-title">
                <h1>Gestão de Acessos</h1>
                <p class="header-subtitle">Entradas e saídas do parque</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div class="user-details">
                    <div class="user-name">Carregando...</div>
                    <div class="user-role">Administrador</div>
                </div>
            </div>
        </div>

        <!-- FILTROS -->
        <div class="filters-section">
            <div class="filters-grid">
                <div class="filter-group">
                    <label for="filterNome">Nome</label>
                    <input type="text" id="filterNome" class="filter-input" placeholder="Buscar por nome...">
                </div>

                <div class="filter-group">
                    <label for="filterNumero">Número de Aluno</label>
                    <input type="text" id="filterNumero" class="filter-input" placeholder="Ex: 1234">
                </div>

                <div class="filter-group">
                    <label for="filterTipo">Tipo de Acesso</label>
                    <select id="filterTipo" class="filter-select">
                        <option value="">Todos</option>
                        <option value="entrada">Entrada</option>
                        <option value="saida">Saída</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterParque">Parque</label>
                    <select id="filterParque" class="filter-select">
                        <option value="">Todos</option>
                        <option value="1">Parque 1</option>
                        <option value="2">Parque 2</option>
                        <option value="3">Parque 3</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="filterDataInicio">Data Início</label>
                    <input type="date" id="filterDataInicio" class="filter-input">
                </div>

                <div class="filter-group">
                    <label for="filterDataFim">Data Fim</label>
                    <input type="date" id="filterDataFim" class="filter-input">
                </div>
            </div>

            <div class="filter-actions">
                <button class="btn btn-secondary" onclick="limparFiltros()">🔄 Limpar</button>
                <button class="btn btn-primary" onclick="buscarAcessos()">🔍 Buscar</button>
            </div>
        </div>

        <!-- TABELA -->
        <div class="table-section">
            <div class="table-header">
                <div>
                    <div class="table-title">Registros de Acesso</div>
                    <div class="records-count" id="recordsCount">0 registros encontrados</div>
                </div>
            </div>

            <div id="tableContent">
                <div class="loading-state">
                    ⏳ Carregando dados...
                </div>
            </div>

            <div class="pagination" id="pagination" style="display: none;">
                <button onclick="previousPage()">← Anterior</button>
                <span class="page-info" id="pageInfo">Página 1 de 1</span>
                <button onclick="nextPage()">Próximo →</button>
            </div>
        </div>
    </main>

    <script src="../../js/historico.js"></script>
</body>
</html>