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
    <title>EasyPark - Painel Administrativo</title>
    <link rel="stylesheet" href="../../css/administracao.css">
    <style>
        .logo::before {
            content: "🚧";
        }

        .chart-section {
            width: 100%;
            box-sizing: border-box;
        }
    </style>
</head>

<body>

    <!-- Inclui a Sidebar Lateral -->
    <?php include 'sidebar.php'; ?>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="main-content">
        <!-- HEADER -->
        <div class="header">
            <div class="header-title">
                <h1>Painel Administrativo</h1>
                <p class="header-subtitle">Carregando...</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">A</div>
                <div class="user-details">
                    <div class="user-name">Carregando...</div>
                    <div class="user-role">Administrador</div>
                </div>
            </div>
        </div>

        <!-- DASHBOARD COMPLETO -->
        <div id="section-lotacoes" style="display: block;">

            <!-- 1. GRID DE ESTATÍSTICAS -->
            <div class="stats-grid">
                <!-- Lotação Total -->
                <div class="stat-card primary">
                    <div class="stat-header">
                        <div class="stat-icon">🅿️</div>
                        <span class="stat-badge">+0.0%</span>
                    </div>
                    <div class="stat-title">Lotação Total</div>
                    <div class="stat-value" id="lotacao-total">--</div>
                    <div class="stat-subtitle">Veículos no sistema</div>
                </div>

                <!-- Parque 1 -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🗺️</div>
                        <span class="stat-badge badge-success" id="badge-1">Disponível</span>
                    </div>
                    <div class="stat-title">Parque 1</div>
                    <div class="stat-value" id="lotacao-1">--</div>
                    <div class="stat-subtitle" id="subtitle-1">de -- lugares</div>
                </div>

                <!-- Parque 2 -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🗺️</div>
                        <span class="stat-badge badge-success" id="badge-2">Disponível</span>
                    </div>
                    <div class="stat-title">Parque 2</div>
                    <div class="stat-value" id="lotacao-2">--</div>
                    <div class="stat-subtitle" id="subtitle-2">de -- lugares</div>
                </div>

                <!-- Parque 3 -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🗺️</div>
                        <span class="stat-badge badge-success" id="badge-3">Disponível</span>
                    </div>
                    <div class="stat-title">Parque 3</div>
                    <div class="stat-value" id="lotacao-3">--</div>
                    <div class="stat-subtitle" id="subtitle-3">de -- lugares</div>
                </div>

                <!-- Entradas Hoje -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🚗</div>
                        <span class="stat-badge badge-success" id="badge-entradas">+0%</span>
                    </div>
                    <div class="stat-title">Entradas Hoje</div>
                    <div class="stat-value" id="entradas-hoje">--</div>
                    <div class="stat-subtitle">Veículos vs ontem</div>
                </div>

                <!-- Utilizadores -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🧑‍🤝‍🧑</div>
                        <span class="stat-badge badge-success">Total</span>
                    </div>
                    <div class="stat-title">Utilizadores</div>
                    <div class="stat-value" id="total-users">--</div>
                    <div class="stat-subtitle">Registados na BD</div>
                </div>

                <!-- Carros (CORRIGIDO) -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🚙</div>
                        <span class="stat-badge badge-success">Registados</span>
                    </div>
                    <div class="stat-title">Viaturas</div>
                    <!-- ID Corrigido: total-carros -->
                    <div class="stat-value" id="total-carros">--</div>
                    <div class="stat-subtitle">Carros no sistema</div>
                </div>
                
                <!-- Respostas Form (CORRIGIDO) -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">📝</div>
                        <span class="stat-badge badge-success">Feedback</span>
                    </div>
                    <div class="stat-title">Sugestões</div>
                    <!-- ID Corrigido: total-respostas -->
                    <div class="stat-value" id="total-respostas">--</div>
                    <div class="stat-subtitle">Respostas recebidas</div>
                </div>
            </div>

            <!-- 2. SECÇÃO DO GRÁFICO -->
            <div class="chart-section" style="margin-top: 30px;">
                <div class="chart-header">
                    <div>
                        <div class="chart-title">Entradas e Saídas</div>
                        <div class="chart-subtitle">Acompanhe o fluxo de veículos</div>
                    </div>
                    <div class="chart-controls">
                        <select class="select-dropdown" id="monthSelect" onchange="updateChart()">
                            <option value="1">Janeiro</option>
                            <option value="2">Fevereiro</option>
                            <option value="3">Março</option>
                            <option value="4">Abril</option>
                            <option value="5">Maio</option>
                            <option value="6">Junho</option>
                            <option value="7">Julho</option>
                            <option value="8">Agosto</option>
                            <option value="9">Setembro</option>
                            <option value="10">Outubro</option>
                            <option value="11" selected>Novembro</option>
                            <option value="12">Dezembro</option>
                        </select>
                        <select class="select-dropdown" id="yearSelect" onchange="updateChart()">
                            <option value="2025" selected>2025</option>
                            <option value="2026">2026</option>
                        </select>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="trafficChart"></canvas>
                </div>
                <div class="chart-legend">
                    <div class="legend-item">
                        <div class="legend-color legend-entradas"></div>
                        <span>Entradas</span>
                    </div>
                    <div class="legend-item">
                        <div class="legend-color legend-saidas"></div>
                        <span>Saídas</span>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let trafficChart = null;

        function showSection(section) {
            console.log("Navegação simplificada: tudo numa página.");
        }

        // --- FETCH USER INFO ---
        async function fetchUserInfo() {
            try {
                const response = await fetch('../../api/get_user_info.php');
                const text = await response.text();
                const parts = text.split('|');

                if (parts[0] === 'SUCCESS') {
                    const nome = parts[1];
                    const tipo = parts[2];
                    const iniciais = parts[3];

                    const avatar = document.querySelector('.user-avatar');
                    if (avatar) avatar.textContent = iniciais;

                    const userName = document.querySelector('.user-name');
                    if (userName) userName.textContent = nome;

                    const userRole = document.querySelector('.user-role');
                    if (userRole) userRole.textContent = tipo;
                }
            } catch (error) { console.log('Erro user info:', error); }
        }

        // --- FETCH LOTAÇÃO ---
        async function fetchLotacaoData() {
            try {
                const response = await fetch('../../api/get_disponibilidade.php');
                const text = await response.text().then(t => t.trim());

                const firstPipeIndex = text.indexOf('|');
                const secondPipeIndex = text.indexOf('|', firstPipeIndex + 1);
                const thirdPipeIndex = text.indexOf('|', secondPipeIndex + 1);

                const status = text.substring(0, firstPipeIndex);

                if (status === 'SUCCESS' && thirdPipeIndex !== -1) {
                    const totalAtual = parseInt(text.substring(secondPipeIndex + 1, thirdPipeIndex));
                    const parquesStr = text.substring(thirdPipeIndex + 1);

                    const lotacaoTotalEl = document.getElementById('lotacao-total');
                    if (lotacaoTotalEl) lotacaoTotalEl.textContent = totalAtual;

                    const parquesArray = parquesStr.split(';');
                    parquesArray.forEach((parqueStr) => {
                        const parts = parqueStr.trim().split('|');
                        if (parts.length >= 3) {
                            const id = parseInt(parts[0]);
                            const max = parseInt(parts[1]);
                            const atual = parseInt(parts[2]);

                            const el = document.getElementById(`lotacao-${id}`);
                            if (el) el.textContent = atual;

                            const sub = document.getElementById(`subtitle-${id}`);
                            if (sub) sub.textContent = `de ${max} lugares`;

                            const badge = document.getElementById(`badge-${id}`);
                            if (badge) {
                                const percentagem = (atual / max) * 100;
                                if (percentagem >= 90) {
                                    badge.textContent = 'Lotado';
                                    badge.className = 'stat-badge badge-danger';
                                } else if (percentagem >= 70) {
                                    badge.textContent = 'Quase Cheio';
                                    badge.className = 'stat-badge badge-warning';
                                } else {
                                    badge.textContent = 'Disponível';
                                    badge.className = 'stat-badge badge-success';
                                }
                            }
                        }
                    });
                }
            } catch (error) { console.error('Erro lotação:', error); }
        }

        // --- FETCH ENTRADAS HOJE ---
        async function fetchEntradasHoje() {
            try {
                const response = await fetch('../../api/get_entradas_dia.php');
                const text = await response.text();
                const parts = text.split('|');
                if (parts[0] === 'SUCCESS') {
                    const el = document.getElementById('entradas-hoje');
                    if (el) el.textContent = parts[1];
                }
            } catch (error) { console.error('Erro entradas:', error); }
        }

        // --- FETCH TOTAL UTILIZADORES ---
        async function fetchTotalUsers() {
            try {
                const response = await fetch('../../api/get_total_users.php');
                const text = await response.text().then(t => t.trim());
                const parts = text.split('|');

                if (parts[0] === 'SUCCESS' && parts.length >= 2) {
                    const totalAtivos = parts[1].trim();
                    const el = document.getElementById('total-users');
                    if (el) el.textContent = totalAtivos;
                }
            } catch (error) { console.error('Erro total users:', error); }
        }

        // --- [NOVO] FETCH TOTAL CARROS ---
        async function fetchTotalCarros() {
            try {
                const response = await fetch('../../api/get_total_carros.php');
                const text = await response.text();
                const parts = text.split('|');
                if (parts[0].trim() === 'SUCCESS') {
                    const el = document.getElementById('total-carros');
                    if(el) el.textContent = parts[1];
                }
            } catch (error) { console.error('Erro total carros:', error); }
        }

        // --- [NOVO] FETCH TOTAL RESPOSTAS ---
        async function fetchTotalRespostas() {
            try {
                const response = await fetch('../../api/get_total_respostas.php');
                const text = await response.text();
                const parts = text.split('|');
                if (parts[0].trim() === 'SUCCESS') {
                    const el = document.getElementById('total-respostas');
                    if(el) el.textContent = parts[1];
                }
            } catch (error) { console.error('Erro total respostas:', error); }
        }

        // --- INICIALIZAR GRÁFICO ---
        function initChart() {
            const ctx = document.getElementById('trafficChart').getContext('2d');
            trafficChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        { label: 'Entradas', data: [], backgroundColor: '#3b82f6', borderRadius: 8 },
                        { label: 'Saídas', data: [], backgroundColor: '#94a3b8', borderRadius: 8 }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                        x: { grid: { display: false } }
                    }
                }
            });
            updateChart();
        }

        // --- ATUALIZAR DADOS GRÁFICO ---
        async function updateChart() {
            if (!trafficChart) return;

            const month = document.getElementById('monthSelect').value;
            const year = document.getElementById('yearSelect').value;

            try {
                const response = await fetch(`../../api/get_grafico_dados.php?mes=${month}&ano=${year}`);
                const text = await response.text();
                const parts = text.split('|');

                if (parts[0] === 'SUCCESS') {
                    const entradas = parts[3].split(',').map(Number);
                    const saidas = parts[4].split(',').map(Number);
                    const dias = Array.from({ length: entradas.length }, (_, i) => i + 1);

                    trafficChart.data.labels = dias;
                    trafficChart.data.datasets[0].data = entradas;
                    trafficChart.data.datasets[1].data = saidas;
                    trafficChart.update();
                }
            } catch (error) { console.error('Erro gráfico:', error); }
        }

        // --- ATUALIZAR DATA HEADER ---
        function updateDate() {
            const days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            const months = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
            const now = new Date();
            const dateStr = `${days[now.getDay()]}, ${now.getDate()} de ${months[now.getMonth()]} de ${now.getFullYear()}`;

            const sub = document.querySelector('.header-subtitle');
            if (sub) sub.textContent = dateStr;
        }

        // --- START ---
        document.addEventListener('DOMContentLoaded', function () {
            updateDate();
            fetchUserInfo();
            fetchLotacaoData();
            fetchEntradasHoje();
            fetchTotalUsers();
            
            // Novas chamadas
            fetchTotalCarros();
            fetchTotalRespostas();
            
            initChart();

            // Atualizar dados a cada 30s
            setInterval(() => {
                fetchLotacaoData();
                fetchEntradasHoje();
                fetchTotalUsers();
                fetchTotalCarros();
                fetchTotalRespostas();
            }, 30000);
        });
    </script>
</body>
</html>