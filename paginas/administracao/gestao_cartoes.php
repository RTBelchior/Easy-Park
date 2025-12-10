<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado e se é administrador
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
    <title>EasyPark - Gestão de Cartões</title>
    <link rel="icon" href="../../imagens/barreira.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/administracao.css">
    <link rel="stylesheet" href="../../css/gestao_cartoes.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="header">
            <div class="header-title">
                <h1>Gestão de Cartões</h1>
                <p class="header-subtitle">Ativar e desativar cartões de acesso</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div class="user-details">
                    <div class="user-name">Carregando...</div>
                    <div class="user-role">Administrador</div>
                </div>
            </div>
        </div>

        <div class="stats-row">
            <div class="stat-mini">
                <div class="stat-mini-value" id="total-cartoes">--</div>
                <div class="stat-mini-label">Total de Cartões</div>
            </div>
            <div class="stat-mini" style="border-left-color: #10b981;">
                <div class="stat-mini-value" id="cartoes-ativos">--</div>
                <div class="stat-mini-label">Cartões Ativos</div>
            </div>
            <div class="stat-mini" style="border-left-color: #ef4444;">
                <div class="stat-mini-value" id="cartoes-inativos">--</div>
                <div class="stat-mini-label">Cartões Inativos</div>
            </div>
        </div>

        <div class="cards-container">
            <div class="search-bar">
                <input type="text" class="search-input" id="searchInput"
                    placeholder="🔍 Pesquisar por nome, número ou cartão...">
                <select class="filter-select" id="filterStatus">
                    <option value="todos">Todos os Status</option>
                    <option value="1">Apenas Ativos</option>
                    <option value="0">Apenas Inativos</option>
                </select>
                <select class="filter-select" id="filterTipo">
                    <option value="todos">Todos os Tipos</option>
                    <option value="1">Tag</option>
                    <option value="2">Cartão</option>
                </select>
            </div>

            <table class="cards-table">
                <thead>
                    <tr>
                        <th>Utilizador</th>
                        <th>Número</th>
                        <th>Cartão</th>
                        <th>Tipo</th>
                        <th>Status</th>
                        <th>Data Registo</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="cardsTableBody">
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-icon">🔄</div>
                            <div>Carregando cartões...</div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

    <!-- Modal de Confirmação -->
    <div class="modal" id="confirmModal">
        <div class="modal-content">
            <div class="modal-title" id="modalTitle">Confirmar Ação</div>
            <div class="modal-text" id="modalText">Tem certeza que deseja realizar esta ação?</div>
            <div class="modal-actions">
                <button class="action-btn btn-cancel" onclick="closeModal()">Cancelar</button>
                <button class="action-btn btn-confirm" id="confirmBtn">Confirmar</button>
            </div>
        </div>
    </div>

    <script src="../../js/gestao_cartoes.js"></script>
</body>

</html>