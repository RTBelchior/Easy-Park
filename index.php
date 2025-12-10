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
    <title>EasyPark - Sistema de Cancela Inteligente</title>
    <link rel="icon" href="imagens/barreira.png" type="image/x-icon">
    <link rel="stylesheet" href="css/pagina_inicial.css">
</head>

<body>
    <?php include('paginas/header.php'); ?>
    <div class="container">

        <section class="hero">
            <div class="hero-content">
                <h1>Controlo Inteligente de Acessos</h1>
                <p>Gere o estacionamento de forma automática e eficiente com tecnologia de ponta</p>
                <!--<button class="cta-button">Saber Mais</button>-->
                <a href="paginas/saibaMais.php" class="cta-button">Saber Mais</a>

            </div>

            <div class="availability-card">
                <h2>Lugares Disponíveis</h2>

                <div class="availability-display">
                    <div class="availability-label">Disponíveis Agora</div>
                    <div class="availability-number" id="availableSpots">--</div>
                    <div class="availability-label" id="parkingName">todos os parques</div>
                </div>

                <div class="status-message" id="statusMessage">
                    <span class="loading">A carregar dados...</span>
                </div>

                <!-- Filtros de Parques -->
                <div class="parking-filters">

                    <!-- BLOCO DE "TODOS" EM CIMA -->
                    <div class="filter-card total-card active" onclick="filterParking('all')">
                        <div class="icon">🅿️</div>
                        <div class="name">Todos</div>
                        <div class="count" id="count-all">--</div>
                    </div>

                    <!-- BLOCO DOS OUTROS PARQUES EM BAIXO -->
                    <div class="parking-subfilters">
                        <div class="filter-card" onclick="filterParking('1')">
                            <div class="icon">🗺️</div>
                            <div class="name">Parque 1</div>
                            <div class="count" id="count-1">--</div>
                        </div>

                        <div class="filter-card" onclick="filterParking('2')">
                            <div class="icon">🗺️</div>
                            <div class="name">Parque 2</div>
                            <div class="count" id="count-2">--</div>
                        </div>

                        <div class="filter-card" onclick="filterParking('3')">
                            <div class="icon">🗺️</div>
                            <div class="name">Parque 3</div>
                            <div class="count" id="count-3">--</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="features">
            <div class="feature-card">
                <div class="feature-icon">⚡</div>
                <h3>Automação Total</h3>
                <p>Sistema 100% automatizado com reconhecimento de matrículas e controlo inteligente</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Análise em Tempo Real</h3>
                <p>Monitorize a ocupação e obtenha estatísticas detalhadas do seu estacionamento</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">🔒</div>
                <h3>Segurança Máxima</h3>
                <p>Controlo de acessos com registo completo de entradas e saídas</p>
            </div>
        </section>
    </div>

    <?php include('paginas/footer.php'); ?>

    <script src="js/pagina_inicial.js"></script>

</body>

</html>