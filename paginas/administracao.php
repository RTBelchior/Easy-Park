<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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
            
            // Atualizar menu ativo
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
                const data = await response.json();
                
                if (data.success) {
                    const user = data.user;
                    
                    // Atualizar avatar com iniciais
                    const avatar = document.querySelector('.user-avatar');
                    if (avatar) {
                        avatar.textContent = user.iniciais;
                    }
                    
                    // Atualizar nome
                    const userName = document.querySelector('.user-name');
                    if (userName) {
                        userName.textContent = user.nome;
                    }
                    
                    // Atualizar role/tipo
                    const userRole = document.querySelector('.user-role');
                    if (userRole) {
                        userRole.textContent = user.tipo_formatado;
                    }
                    
                    console.log('✅ Dados do utilizador carregados:', user);
                } else {
                    console.log('⚠️ Utilizador não autenticado');
                }
            } catch (error) {
                console.log('⚠️ Erro ao buscar dados do utilizador:', error.message);
            }
        }

        // Buscar dados de lotação
        async function fetchLotacaoData() {
            try {
                console.log('🔄 Buscando dados de lotação...');
                const response = await fetch('../api/get_disponibilidade.php');
                
                if (!response.ok) {
                    throw new Error(`Erro HTTP: ${response.status}`);
                }
                
                const responseText = await response.text();
                console.log('📥 Resposta bruta:', responseText);
                
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('❌ Erro ao fazer parse do JSON:', e);
                    throw new Error('Resposta inválida do servidor');
                }
                
                console.log('📊 Dados parseados:', data);
                
                if (data.success) {
                    // Atualizar lotação total
                    const lotacaoTotalEl = document.getElementById('lotacao-total');
                    if (lotacaoTotalEl) {
                        lotacaoTotalEl.textContent = data.total.lotacao_atual;
                        console.log('✅ Lotação total atualizada:', data.total.lotacao_atual);
                    } else {
                        console.error('❌ Elemento lotacao-total não encontrado');
                    }
                    
                    // Atualizar cada parque
                    for (let i = 1; i <= 3; i++) {
                        console.log(`🔍 Verificando parque ${i}...`);
                        
                        if (data.parques[i]) {
                            const parque = data.parques[i];
                            console.log(`📍 Dados do Parque ${i}:`, parque);
                            
                            // Atualizar número
                            const lotacaoEl = document.getElementById(`lotacao-${i}`);
                            if (lotacaoEl) {
                                lotacaoEl.textContent = parque.lotacao_atual;
                                console.log(`✅ Lotação do parque ${i} atualizada:`, parque.lotacao_atual);
                            } else {
                                console.error(`❌ Elemento lotacao-${i} não encontrado`);
                            }
                            
                            // Atualizar subtitle
                            const subtitleEl = document.getElementById(`subtitle-${i}`);
                            if (subtitleEl) {
                                subtitleEl.textContent = `de ${parque.lotacao_maxima} lugares`;
                                console.log(`✅ Subtitle do parque ${i} atualizado`);
                            }
                            
                            // Atualizar badge
                            const badge = document.getElementById(`badge-${i}`);
                            if (badge) {
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
                                console.log(`✅ Badge do parque ${i} atualizado: ${percentagem.toFixed(1)}%`);
                            }
                        } else {
                            console.warn(`⚠️ Parque ${i} não encontrado nos dados`);
                        }
                    }
                    
                    console.log('✅ Todos os dados de lotação atualizados com sucesso!');
                } else {
                    console.error('❌ API retornou erro:', data.error);
                }
            } catch (error) {
                console.error('❌ Erro ao buscar lotação:', error.message);
            }
        }

        // Buscar entradas de hoje
        async function fetchEntradasHoje() {
            try {
                console.log('🔄 Buscando entradas de hoje...');
                const response = await fetch('../api/get_entradas_dia.php');
                const data = await response.json();
                
                if (data.success) {
                    const entradasEl = document.getElementById('entradas-hoje');
                    if (entradasEl) {
                        entradasEl.textContent = data.total_entradas;
                        console.log('✅ Entradas de hoje atualizadas:', data.total_entradas);
                    }
                }
            } catch (error) {
                console.error('❌ Erro ao buscar entradas:', error.message);
                const entradasEl = document.getElementById('entradas-hoje');
                if (entradasEl) entradasEl.textContent = '0';
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
                const data = await response.json();
                
                if (data.success) {
                    trafficChart.data.labels = data.dias;
                    trafficChart.data.datasets[0].data = data.entradas;
                    trafficChart.data.datasets[1].data = data.saidas;
                    trafficChart.update();
                    console.log('✅ Gráfico atualizado');
                }
            } catch (error) {
                console.error('❌ Erro ao buscar dados do gráfico:', error);
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

        // Inicializar ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            console.log('🚀 Página carregada! Inicializando...');
            
            updateDate();
            fetchUserInfo();
            fetchLotacaoData();
            fetchEntradasHoje();
            
            // Atualizar a cada 30 segundos
            setInterval(() => {
                console.log('🔄 Atualizando dados automaticamente...');
                fetchLotacaoData();
                fetchEntradasHoje();
            }, 30000);
        });
    </script>
</body>
</html>