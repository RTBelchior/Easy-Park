<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado e se é administrador (segurança extra)
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../paginas/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPark - Painel Administrativo</title>
    <link rel="stylesheet" href="../css/administracao.css">
    <style>
        /* Fix para emojis que podem não carregar corretamente */
        .logo::before {
            content: "🚧";
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
                <span class="menu-item-icon">📝</span>
                <span>Relatórios</span>
            </a>
        </div>

        <!-- MUDANÇA AQUI: Voltar ao Início no fundo -->
        <div class="sidebar-footer">
            <a href="../index.php" class="menu-item">
                <span class="menu-item-icon">🏠</span>
                <span>Voltar ao Início</span>
            </a>
        </div>
    </aside>

    <!-- MAIN CONTENT -->
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
                        <span class="stat-badge badge-success" id="badge-entradas">+0%</span>
                    </div>
                    <div class="stat-title">Entradas Hoje</div>
                    <div class="stat-value" id="entradas-hoje">--</div>
                    <div class="stat-subtitle">Veículos vs ontem</div>
                </div>

                <!--Visitas do Site -->
                <div class="stat-card">
                    <div class="stat-header">
                        <div class="stat-icon">🧑‍🤝‍🧑</div>
                        <span class="stat-badge badge-success">+0%</span>
                    </div>
                    <div class="stat-title">Visitas Hoje</div>
                    <div class="stat-value" id="visitas-hoje">--</div>
                    <div class="stat-subtitle">Visualizações</div>
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

        // Alternar entre seções
        function showSection(section) {
            document.getElementById('section-lotacoes').style.display = section === 'lotacoes' ? 'block' : 'none';
            document.getElementById('section-grafico').style.display = section === 'grafico' ? 'block' : 'none';

            document.querySelectorAll('.menu-item').forEach(item => {
                item.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            if (section === 'grafico' && !trafficChart) {
                initChart();
            }
        }

        // Buscar dados do utilizador
        async function fetchUserInfo() {
            try {
                const response = await fetch('../api/get_user_info.php');
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

                } else {
                    console.log('⚠️ Não autenticado');
                }
            } catch (error) {
                console.log('⚠️ Erro ao buscar utilizador:', error.message);
            }
        }

        // Buscar dados de lotação
        async function fetchLotacaoData() {
            try {
                const response = await fetch('../api/get_disponibilidade.php');
                const text = await response.text().then(t => t.trim());

                // Split apenas nos primeiros 3 pipes
                const firstPipeIndex = text.indexOf('|');
                const secondPipeIndex = text.indexOf('|', firstPipeIndex + 1);
                const thirdPipeIndex = text.indexOf('|', secondPipeIndex + 1);

                const status = text.substring(0, firstPipeIndex);
                const totalMax = parseInt(text.substring(firstPipeIndex + 1, secondPipeIndex));
                const totalAtual = parseInt(text.substring(secondPipeIndex + 1, thirdPipeIndex));
                const parquesStr = text.substring(thirdPipeIndex + 1);

                if (status === 'SUCCESS') {
                    // Atualizar total
                    const lotacaoTotalEl = document.getElementById('lotacao-total');
                    if (lotacaoTotalEl) {
                        lotacaoTotalEl.textContent = totalAtual;
                    }

                    // Processar cada parque
                    const parquesArray = parquesStr.split(';');

                    parquesArray.forEach((parqueStr, index) => {
                        const parqueParts = parqueStr.trim().split('|');
                        const id = parseInt(parqueParts[0]);
                        const max = parseInt(parqueParts[1]);
                        const atual = parseInt(parqueParts[2]);

                        const lotacaoEl = document.getElementById(`lotacao-${id}`);
                        if (lotacaoEl) {
                            lotacaoEl.textContent = atual;
                        }

                        const subtitleEl = document.getElementById(`subtitle-${id}`);
                        if (subtitleEl) {
                            subtitleEl.textContent = `de ${max} lugares`;
                        }

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
                    });
                }
            } catch (error) {
                console.error('❌ Erro ao buscar lotação:', error.message);
            }
        }

        // Buscar entradas de hoje
        async function fetchEntradasHoje() {
            try {
                const response = await fetch('../api/get_entradas_dia.php');
                const text = await response.text();
                const parts = text.split('|');

                if (parts[0] === 'SUCCESS') {
                    const total = parseInt(parts[1]);
                    const entradasEl = document.getElementById('entradas-hoje');
                    if (entradasEl) {
                        entradasEl.textContent = total;
                    }
                }
            } catch (error) {
                console.error('❌ Erro ao buscar entradas:', error.message);
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
                const response = await fetch(`../api/get_grafico_dados.php?mes=${month}&ano=${year}`);
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
            } catch (error) {
                console.error('❌ Erro ao buscar gráfico:', error);
            }
        }

        // Atualizar data
        function updateDate() {
            const days = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            const months = ['janeiro', 'fevereiro', 'março', 'abril', 'maio', 'junho', 'julho', 'agosto', 'setembro', 'outubro', 'novembro', 'dezembro'];

            const now = new Date();
            const dateStr = `${days[now.getDay()]}, ${now.getDate()} de ${months[now.getMonth()]} de ${now.getFullYear()}`;

            const subtitle = document.querySelector('.header-subtitle');
            if (subtitle) {
                subtitle.textContent = dateStr;
            }
        }

        // Inicializar
        document.addEventListener('DOMContentLoaded', function () {
            updateDate();
            fetchUserInfo();
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