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
    <link rel="icon" href="../../imagens/barreira.png" type="image/x-icon">
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
    <script src="../../js/administracao.js"></script>
</body>
</html>