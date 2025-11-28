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
    <link rel="stylesheet" href="../../css/administracao.css">
    <style>
        .cards-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .search-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }

        .search-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .filter-select {
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .cards-table {
            width: 100%;
            border-collapse: collapse;
        }

        .cards-table thead {
            background: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }

        .cards-table th {
            padding: 12px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            font-size: 14px;
        }

        .cards-table td {
            padding: 16px 12px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            color: #334155;
        }

        .cards-table tbody tr:hover {
            background: #f8fafc;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .status-badge.ativo {
            background: #dcfce7;
            color: #15803d;
        }

        .status-badge.inativo {
            background: #fee2e2;
            color: #991b1b;
        }

        .status-badge::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
        }

        .action-btn {
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-deactivate {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-deactivate:hover {
            background: #fecaca;
        }

        .btn-activate {
            background: #dcfce7;
            color: #15803d;
        }

        .btn-activate:hover {
            background: #bbf7d0;
        }

        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #94a3b8;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
        }

        .tipo-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .tipo-tag {
            background: #dbeafe;
            color: #1e40af;
        }

        .tipo-cartao {
            background: #fce7f3;
            color: #9f1239;
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-mini {
            background: white;
            padding: 16px;
            border-radius: 8px;
            border-left: 4px solid #3b82f6;
        }

        .stat-mini-value {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
        }

        .stat-mini-label {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            align-items: center;
            justify-content: center;
        }

        .modal.show {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            padding: 24px;
            max-width: 400px;
            width: 90%;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .modal-text {
            color: #64748b;
            margin-bottom: 24px;
        }

        .modal-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
        }

        .btn-confirm {
            background: #3b82f6;
            color: white;
        }

        .btn-confirm:hover {
            background: #2563eb;
        }

        /* Mobile View */
        @media (max-width: 952px) {
            .header { flex-direction: column; align-items: flex-start; }
            .user-info { width: 100%; justify-content: space-between; margin-top: 10px; }
            .cards-container { padding: 15px; }
            .search-bar { flex-direction: column; gap: 10px; }
            .search-input, .filter-select { width: 100%; }

            .cards-table thead { display: none; }
            .cards-table, .cards-table tbody, .cards-table tr, .cards-table td { display: block; width: 100%; }
            
            .cards-table tr { 
                margin-bottom: 20px; background: white; 
                border: 1px solid #e2e8f0; border-radius: 12px; 
                box-shadow: 0 2px 4px rgba(0,0,0,0.05); padding: 15px; 
            }
            
            .cards-table td { 
                padding: 10px 0; border-bottom: 1px solid #f1f5f9; 
                display: flex; justify-content: space-between; align-items: center; 
                text-align: right; 
            }
            .cards-table td:last-child { border-bottom: none; padding-top: 15px; justify-content: center; }
            
            .cards-table td::before { 
                content: attr(data-label); font-weight: 600; color: #94a3b8; 
                font-size: 12px; text-transform: uppercase; 
            }
            .action-btn { width: 100%; justify-content: center; }
        }
    </style>
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
                <input type="text" class="search-input" id="searchInput" placeholder="🔍 Pesquisar por nome, número ou cartão...">
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

    <script>
        let allCards = [];
        let currentAction = null;

        // Fetch User Info
        async function fetchUserInfo() {
            try {
                const response = await fetch('../../api/get_user_info.php');
                const text = await response.text();
                const parts = text.split('|');

                if (parts[0] === 'SUCCESS') {
                    document.querySelector('.user-avatar').textContent = parts[3];
                    document.querySelector('.user-name').textContent = parts[1];
                }
            } catch (error) {
                console.error('Erro ao carregar info do usuário:', error);
            }
        }

        // Fetch Cards
        async function fetchCards() {
            try {
                const response = await fetch('../../api/get_cartoes.php');
                const text = await response.text();
                const trimmedText = text.trim();
                console.log('Resposta:', trimmedText);

                const parts = trimmedText.split('|');
                
                if (parts[0] === 'SUCCESS') {
                    allCards = JSON.parse(parts[1]);
                    updateStats();
                    renderCards(allCards);
                }
            } catch (error) {
                console.error('Erro ao carregar cartões:', error);
                document.getElementById('cardsTableBody').innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-icon">❌</div>
                            <div>Erro ao carregar cartões</div>
                        </td>
                    </tr>
                `;
            }
        }

        // Update Stats
        function updateStats() {
            const total = allCards.length;
            // Verificar tanto string '1' quanto número 1
            const ativos = allCards.filter(c => c.ativo_cartao === '1' || c.ativo_cartao === 1).length;
            const inativos = total - ativos;

            console.log('Stats:', { total, ativos, inativos });
            console.log('Cartões:', allCards.map(c => ({ id: c.id_cartao, ativo: c.ativo_cartao, tipo: typeof c.ativo_cartao })));

            document.getElementById('total-cartoes').textContent = total;
            document.getElementById('cartoes-ativos').textContent = ativos;
            document.getElementById('cartoes-inativos').textContent = inativos;
        }

        // Render Cards
        function renderCards(cards) {
            const tbody = document.getElementById('cardsTableBody');
            
            if (cards.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-icon">🔍</div>
                            <div>Nenhum cartão encontrado</div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = cards.map(card => {
                // Verificar tanto string '1' quanto número 1
                const isAtivo = card.ativo_cartao === '1' || card.ativo_cartao === 1;
                const statusClass = isAtivo ? 'ativo' : 'inativo';
                const statusText = isAtivo ? 'Ativo' : 'Inativo';
                const tipoClass = card.id_tipo_cartao === '1' || card.id_tipo_cartao === 1 ? 'tipo-tag' : 'tipo-cartao';
                const tipoText = card.id_tipo_cartao === '1' || card.id_tipo_cartao === 1 ? 'Tag' : 'Cartão';
                const btnClass = isAtivo ? 'btn-deactivate' : 'btn-activate';
                const btnText = isAtivo ? '🚫 Desativar' : '✅ Ativar';
                const dataFormatada = new Date(card.data_registo_cartao).toLocaleDateString('pt-PT');

                return `
                    <tr>
                        <td><strong>${card.nome_utilizador}</strong></td>
                        <td>${card.numero_utilizador}</td>
                        <td><code>${card.numero_cartao}</code></td>
                        <td><span class="tipo-badge ${tipoClass}">${tipoText}</span></td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>${dataFormatada}</td>
                        <td>
                            <button class="action-btn ${btnClass}" onclick="toggleCardStatus(${card.id_cartao}, ${isAtivo}, '${card.nome_utilizador}', '${card.numero_cartao}')">
                                ${btnText}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Toggle Card Status
        function toggleCardStatus(idCartao, isAtivo, nomeUtilizador, numeroCartao) {
            const action = isAtivo ? 'desativar' : 'ativar';
            const newStatus = isAtivo ? 0 : 1;

            document.getElementById('modalTitle').textContent = `${action.charAt(0).toUpperCase() + action.slice(1)} Cartão`;
            document.getElementById('modalText').textContent = `Tem certeza que deseja ${action} o cartão ${numeroCartao} de ${nomeUtilizador}?`;
            
            currentAction = { idCartao, newStatus };
            document.getElementById('confirmModal').classList.add('show');
        }

        // Confirm Action
        document.getElementById('confirmBtn').addEventListener('click', async () => {
            if (!currentAction) return;

            console.log('Enviando requisição:', currentAction);

            try {
                const response = await fetch('../../api/toggle_cartao.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id_cartao=${currentAction.idCartao}&status=${currentAction.newStatus}`
                });

                const text = await response.text();
                console.log('Resposta do servidor:', text);
                
                if (text.startsWith('SUCCESS')) {
                    console.log('Sucesso! Recarregando cartões...');
                    await fetchCards();
                    closeModal();
                } else {
                    console.error('Erro na resposta:', text);
                    alert('Erro ao atualizar cartão: ' + text);
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                alert('Erro ao atualizar cartão: ' + error.message);
            }
        });

        // Close Modal
        function closeModal() {
            document.getElementById('confirmModal').classList.remove('show');
            currentAction = null;
        }

        // Filter Cards
        function filterCards() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const statusFilter = document.getElementById('filterStatus').value;
            const tipoFilter = document.getElementById('filterTipo').value;

            let filtered = allCards;

            if (searchText) {
                filtered = filtered.filter(card => 
                    card.nome_utilizador.toLowerCase().includes(searchText) ||
                    card.numero_utilizador.toString().includes(searchText) ||
                    card.numero_cartao.toLowerCase().includes(searchText)
                );
            }

            if (statusFilter !== 'todos') {
                // Comparar considerando string e número
                filtered = filtered.filter(card => 
                    card.ativo_cartao === statusFilter || 
                    card.ativo_cartao === parseInt(statusFilter)
                );
            }

            if (tipoFilter !== 'todos') {
                // Comparar considerando string e número
                filtered = filtered.filter(card => 
                    card.id_tipo_cartao === tipoFilter || 
                    card.id_tipo_cartao === parseInt(tipoFilter)
                );
            }

            renderCards(filtered);
        }

        // Event Listeners
        document.getElementById('searchInput').addEventListener('input', filterCards);
        document.getElementById('filterStatus').addEventListener('change', filterCards);
        document.getElementById('filterTipo').addEventListener('change', filterCards);

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            fetchUserInfo();
            fetchCards();
        });
    </script>
</body>
</html>