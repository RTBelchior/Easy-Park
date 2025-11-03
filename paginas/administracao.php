<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPark - Painel Administrativo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f5f6fa;
            display: flex;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 240px;
            background: white;
            padding: 30px 20px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
        }

        .logo {
            font-size: 24px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo::before {
            content: "🚧";
            font-size: 28px;
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-label {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s;
            margin-bottom: 4px;
        }

        .menu-item:hover {
            background: #f1f5f9;
            color: #1e3a8a;
        }

        .menu-item.active {
            background: #3b82f6;
            color: white;
        }

        .menu-item-icon {
            font-size: 20px;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            flex: 1;
            padding: 30px 40px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: 32px;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .header-subtitle {
            color: #64748b;
            font-size: 14px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            background: white;
            padding: 10px 16px;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #3b82f6, #1e3a8a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: #64748b;
        }

        /* ===== STATS GRID ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            padding: 24px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }

        .stat-card.primary {
            background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%);
            color: white;
        }

        .stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            background: rgba(59, 130, 246, 0.1);
        }

        .stat-card.primary .stat-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .stat-badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-success {
            background: rgba(34, 197, 94, 0.15);
            color: #16a34a;
        }

        .badge-warning {
            background: rgba(234, 179, 8, 0.15);
            color: #ca8a04;
        }

        .badge-danger {
            background: rgba(239, 68, 68, 0.15);
            color: #dc2626;
        }

        .stat-card.primary .stat-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
        }

        .stat-title {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 8px;
        }

        .stat-card.primary .stat-title {
            color: rgba(255, 255, 255, 0.9);
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .stat-card.primary .stat-value {
            color: white;
        }

        .stat-subtitle {
            font-size: 12px;
            color: #94a3b8;
        }

        .stat-card.primary .stat-subtitle {
            color: rgba(255, 255, 255, 0.8);
        }

        /* ===== CHART SECTION ===== */
        .chart-section {
            background: white;
            padding: 28px;
            border-radius: 16px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .chart-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }

        .chart-subtitle {
            font-size: 13px;
            color: #64748b;
        }

        .chart-controls {
            display: flex;
            gap: 10px;
        }

        .select-dropdown {
            padding: 8px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            background: white;
            color: #64748b;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .select-dropdown:hover {
            border-color: #3b82f6;
        }

        .chart-container {
            height: 320px;
            position: relative;
        }

        .chart-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100%;
            color: #94a3b8;
            font-style: italic;
        }

        .loading {
            text-align: center;
            color: #64748b;
            font-style: italic;
            padding: 20px;
        }

        /* ===== LEGEND ===== */
        .chart-legend {
            display: flex;
            gap: 24px;
            margin-top: 20px;
            justify-content: center;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #64748b;
        }

        .legend-color {
            width: 12px;
            height: 12px;
            border-radius: 3px;
        }

        .legend-entradas {
            background: #3b82f6;
        }

        .legend-saidas {
            background: #94a3b8;
        }

        @media (max-width: 768px) {
            body {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                padding: 20px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="logo">EasyPark</div>
        
        <div class="menu-section">
            <div class="menu-label">Menu</div>
            <a href="#" class="menu-item active" onclick="showSection('lotacoes')">
                <span class="menu-item-icon">📊</span>
                <span>Lotações</span>
            </a>
            <a href="#" class="menu-item" onclick="showSection('grafico')">
                <span class="menu-item-icon">📈</span>
                <span>Gráfico</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-label">Ferramentas</div>
            <a href="#" class="menu-item">
                <span class="menu-item-icon">⚙️</span>
                <span>Configurações</span>
            </a>
            <a href="#" class="menu-item">
                <span class="menu-item-icon">📝</span>
                <span>Relatórios</span>
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <!-- HEADER -->
        <div class="header">
            <div class="header-title">
                <h1>Painel Administrativo</h1>
                <p class="header-subtitle">Sexta-feira, 15 de dezembro de 2023</p>
            </div>
            <div class="user-info">
                <div class="user-avatar">FA</div>
                <div class="user-details">
                    <div class="user-name">Ferra Alexandra</div>
                    <div class="user-role">Administradora</div>
                </div>
            </div>
        </div>

        <!-- SEÇÃO LOTAÇÕES -->
        <div id="section-lotacoes">
            <!-- STATS GRID -->
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
                        <span class="stat-badge badge-success" id="badge-entradas">+12.1%</span>
                    </div>
                    <div class="stat-title">Entradas Hoje</div>
                    <div class="stat-value" id="entradas-hoje">--</div>
                    <div class="stat-subtitle">Veículos vs ontem</div>
                </div>
            </div>
        </div>

        <!-- SEÇÃO GRÁFICO -->
        <div id="section-grafico" style="display: none;">
            <div class="chart-section">
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
                            <option value="11">Novembro</option>
                            <option value="12" selected>Dezembro</option>
                        </select>
                        <select class="select-dropdown" id="yearSelect" onchange="updateChart()">
                            <option value="2023" selected>2023</option>
                            <option value="2024">2024</option>
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

        // Alternar entre seções
        function showSection(section) {
            document.getElementById('section-lotacoes').style.display = section === 'lotacoes' ? 'block' : 'none';
            document.getElementById('section-grafico').style.display = section === 'grafico' ? 'block' : 'none';
            
            // Atualizar menu ativo
            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            if (section === 'grafico' && !trafficChart) {
                initChart();
            }
        }

        // Buscar dados de lotação
        async function fetchLotacaoData() {
            try {
                const response = await fetch('api/get_disponibilidade.php');
                const data = await response.json();
                
                if (data.success) {
                    // Atualizar lotação total
                    document.getElementById('lotacao-total').textContent = data.total.lotacao_atual;
                    
                    // Atualizar cada parque
                    for (let i = 1; i <= 3; i++) {
                        if (data.parques[i]) {
                            const parque = data.parques[i];
                            document.getElementById(`lotacao-${i}`).textContent = parque.lotacao_atual;
                            document.getElementById(`subtitle-${i}`).textContent = `de ${parque.lotacao_maxima} lugares`;
                            
                            // Atualizar badge
                            const badge = document.getElementById(`badge-${i}`);
                            const percentagem = (parque.lotacao_atual / parque.lotacao_maxima) * 100;
                            
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
                }
            } catch (error) {
                console.error('Erro ao buscar lotação:', error);
            }
        }

        // Buscar entradas de hoje
        async function fetchEntradasHoje() {
            try {
                const response = await fetch('api/get_entradas_dia.php');
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('entradas-hoje').textContent = data.total_entradas;
                }
            } catch (error) {
                console.error('Erro ao buscar entradas:', error);
                document.getElementById('entradas-hoje').textContent = '0';
            }
        }

        // Inicializar gráfico
        function initChart() {
            const ctx = document.getElementById('trafficChart').getContext('2d');
            trafficChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Entradas',
                            data: [],
                            backgroundColor: '#3b82f6',
                            borderRadius: 8
                        },
                        {
                            label: 'Saídas',
                            data: [],
                            backgroundColor: '#94a3b8',
                            borderRadius: 8
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            updateChart();
        }

        // Atualizar gráfico
        async function updateChart() {
            if (!trafficChart) return;

            const month = document.getElementById('monthSelect').value;
            const year = document.getElementById('yearSelect').value;

            try {
                const response = await fetch(`api/get_grafico_dados.php?mes=${month}&ano=${year}`);
                const data = await response.json();
                
                if (data.success) {
                    trafficChart.data.labels = data.dias;
                    trafficChart.data.datasets[0].data = data.entradas;
                    trafficChart.data.datasets[1].data = data.saidas;
                    trafficChart.update();
                }
            } catch (error) {
                console.error('Erro ao buscar dados do gráfico:', error);
            }
        }

        // Atualizar data
        function updateDate() {
            const days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            const months = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];
            
            const now = new Date();
            const dateStr = `${days[now.getDay()]}, ${now.getDate()} de ${months[now.getMonth()]} de ${now.getFullYear()}`;
            
            document.querySelector('.header-subtitle').textContent = dateStr;
        }

        // Inicializar ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            updateDate();
            fetchLotacaoData();
            fetchEntradasHoje();
            
            // Atualizar a cada 30 segundos
            setInterval(() => {
                fetchLotacaoData();
                fetchEntradasHoje();
            }, 30000);
        });
    </script>
</body>
</html>