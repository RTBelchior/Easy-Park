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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .page-header {
            text-align: center;
            color: white;
            margin-bottom: 50px;
            animation: fadeIn 1s ease;
        }

        .page-header h1 {
            font-size: 48px;
            margin-bottom: 15px;
            animation: slideDown 1s ease;
        }

        .page-header p {
            font-size: 20px;
            opacity: 0.9;
            animation: slideDown 1.2s ease;
        }

        .user-welcome {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 25px 35px;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: slideRight 1s ease;
        }

        .user-info-box {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: white;
            color: #1e3a8a;
            border: 2px solid #3b82f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 24px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-size: 20px;
            font-weight: 700;
            color: #1e3a8a;
        }

        .user-number {
            font-size: 16px;
            color: #666;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 25px;
            margin-bottom: 40px;
            animation: fadeIn 1.5s ease;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            transition: all 0.3s;
            border-left: 5px solid #3b82f6;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .stat-card.entrada {
            border-left-color: #10b981;
        }

        .stat-card.saida {
            border-left-color: #ef4444;
        }

        .stat-card.tempo {
            border-left-color: #f59e0b;
        }

        .stat-icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .stat-value {
            font-size: 42px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 8px;
            animation: pulse 2s ease-in-out infinite;
        }

        .stat-label {
            font-size: 15px;
            color: #666;
            font-weight: 600;
        }

        .filters-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: slideLeft 1s ease;
        }

        .filters-title {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .filter-label {
            font-size: 14px;
            font-weight: 600;
            color: #1e3a8a;
        }

        .filter-input,
        .filter-select {
            padding: 14px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: white;
        }

        .filter-input:focus,
        .filter-select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .filter-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
        }

        .btn {
            padding: 14px 30px;
            border: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(30, 58, 138, 0.4);
        }

        .btn-secondary {
            background: white;
            color: #1e3a8a;
            border: 2px solid #3b82f6;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
        }

        .content-section {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 35px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1.8s ease;
        }

        .table-wrapper {
            overflow-x: auto;
            border-radius: 15px;
        }

        .access-table {
            width: 100%;
            border-collapse: collapse;
        }

        .access-table thead {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        }

        .access-table th {
            padding: 18px;
            text-align: left;
            font-weight: 600;
            color: white;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .access-table td {
            padding: 20px 18px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 15px;
        }

        .access-table tbody tr {
            transition: all 0.3s;
        }

        .access-table tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
            transform: scale(1.01);
        }

        .type-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 18px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
        }

        .type-entrada {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            color: #065f46;
        }

        .type-saida {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #991b1b;
        }

        .park-badge {
            padding: 8px 16px;
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            color: #1e40af;
            border-radius: 15px;
            font-size: 13px;
            font-weight: 600;
        }

        .duration-display {
            font-weight: 700;
            color: #f59e0b;
            font-size: 16px;
        }

        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #94a3b8;
        }

        .empty-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: pulse 2s ease-in-out infinite;
        }

        .empty-text {
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #64748b;
        }

        .back-button {
            margin-top: 25px;
            width: 100%;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideRight {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideLeft {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        @media (max-width: 1200px) {

            .stats-grid,
            .filters-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 768px) {

            .stats-grid,
            .filters-grid {
                grid-template-columns: 1fr;
            }

            .page-header h1 {
                font-size: 32px;
            }

            .user-welcome {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
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

    <script>
        let allAccessData = [];
        let filteredData = [];

        // Load User Data
        async function loadUserData() {
            try {
                const response = await fetch('../api/get_meu_cartao.php');
                const text = await response.text();
                const parts = text.trim().split('|');

                if (parts[0] === 'SUCCESS') {
                    document.getElementById('userAvatar').textContent = parts[4];
                    document.getElementById('userName').textContent = parts[1];
                    document.getElementById('userNumber').textContent = `Nº ${parts[2]}`;
                }
            } catch (error) {
                console.error('Erro ao carregar usuário:', error);
            }
        }

        // Load Parking Lots
        async function loadParkingLots() {
            try {
                const response = await fetch('../api/get_parques.php');
                const text = await response.text();
                const parts = text.trim().split('|');

                if (parts[0] === 'SUCCESS') {
                    const parks = JSON.parse(parts[1]);
                    const select = document.getElementById('filterParque');
                    select.innerHTML = '<option value="">Todos os parques</option>' +
                        parks.map(p => `<option value="${p.id_parque}">Parque ${p.id_parque}</option>`).join('');
                }
            } catch (error) {
                console.error('Erro ao carregar parques:', error);
            }
        }

        // Fetch My Access
        async function fetchMyAccess() {
            try {
                const response = await fetch('../api/get_meus_acessos.php');
                const text = await response.text();
                const parts = text.trim().split('|');

                if (parts[0] === 'SUCCESS') {
                    allAccessData = JSON.parse(parts[1]);
                    filteredData = [...allAccessData];
                    updateStats();
                    renderTable();
                }
            } catch (error) {
                console.error('Erro ao carregar acessos:', error);
                document.getElementById('accessTableBody').innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-state">
                            <div class="empty-icon">❌</div>
                            <div class="empty-text">Erro ao carregar acessos</div>
                        </td>
                    </tr>
                `;
            }
        }

        // Update Stats
        function updateStats() {
            const entradas = allAccessData.filter(a => a.tipo_acesso === 'entrada').length;
            const saidas = allAccessData.filter(a => a.tipo_acesso === 'saida').length;

            // Calcular tempo total em minutos
            let tempoTotalMin = 0;
            allAccessData.forEach(access => {
                if (access.tipo_acesso === 'saida') {
                    // Encontrar entrada correspondente
                    const entrada = allAccessData.find(a =>
                        a.tipo_acesso === 'entrada' &&
                        new Date(a.data_hora_acesso) < new Date(access.data_hora_acesso)
                    );
                    if (entrada) {
                        const diff = new Date(access.data_hora_acesso) - new Date(entrada.data_hora_acesso);
                        tempoTotalMin += Math.floor(diff / 60000);
                    }
                }
            });
            const tempoTotalHoras = (tempoTotalMin / 60).toFixed(1);

            document.getElementById('totalAcessos').textContent = allAccessData.length;
            document.getElementById('totalEntradas').textContent = entradas;
            document.getElementById('totalSaidas').textContent = saidas;
            document.getElementById('tempoTotal').textContent = tempoTotalHoras;
        }

        // Apply Filters
        function aplicarFiltros() {
            const tipo = document.getElementById('filterTipo').value;
            const parque = document.getElementById('filterParque').value;
            const dataInicio = document.getElementById('filterDataInicio').value;
            const dataFim = document.getElementById('filterDataFim').value;

            filteredData = allAccessData.filter(access => {
                if (tipo && access.tipo_acesso !== tipo) return false;
                if (parque && access.id_parque != parque) return false;

                if (dataInicio) {
                    const accessDate = new Date(access.data_hora_acesso);
                    const startDate = new Date(dataInicio);
                    if (accessDate < startDate) return false;
                }

                if (dataFim) {
                    const accessDate = new Date(access.data_hora_acesso);
                    const endDate = new Date(dataFim + ' 23:59:59');
                    if (accessDate > endDate) return false;
                }

                return true;
            });

            renderTable();
        }

        // Clear Filters
        function limparFiltros() {
            document.getElementById('filterTipo').value = '';
            document.getElementById('filterParque').value = '';
            document.getElementById('filterDataInicio').value = '';
            document.getElementById('filterDataFim').value = '';
            filteredData = [...allAccessData];
            renderTable();
        }

        // Render Table
        function renderTable() {
            const tbody = document.getElementById('accessTableBody');

            if (filteredData.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-state">
                            <div class="empty-icon">🔍</div>
                            <div class="empty-text">Nenhum acesso encontrado</div>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = filteredData.map(access => {
                const tipoClass = access.tipo_acesso === 'entrada' ? 'type-entrada' : 'type-saida';
                const tipoIcon = access.tipo_acesso === 'entrada' ? '🚗' : '🚀';
                const tipoText = access.tipo_acesso === 'entrada' ? 'Entrada' : 'Saída';

                const date = new Date(access.data_hora_acesso);
                const formattedDate = date.toLocaleString('pt-PT', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Calculate duration
                let durationHTML = '<span style="color: #94a3b8;">--</span>';
                if (access.tipo_acesso === 'saida') {
                    const entrada = filteredData.find(a =>
                        a.tipo_acesso === 'entrada' &&
                        new Date(a.data_hora_acesso) < new Date(access.data_hora_acesso)
                    );
                    if (entrada) {
                        const diff = new Date(access.data_hora_acesso) - new Date(entrada.data_hora_acesso);
                        const hours = Math.floor(diff / 3600000);
                        const mins = Math.floor((diff % 3600000) / 60000);
                        durationHTML = `<span class="duration-display">${hours}h ${mins}m</span>`;
                    }
                }

                return `
                    <tr>
                        <td><span class="type-badge ${tipoClass}">${tipoIcon} ${tipoText}</span></td>
                        <td><span class="park-badge">Parque ${access.id_parque}</span></td>
                        <td>${formattedDate}</td>
                        <td>${durationHTML}</td>
                    </tr>
                `;
            }).join('');
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            loadUserData();
            loadParkingLots();
            fetchMyAccess();
        });
    </script>
</body>

</html>