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
    <title>EasyPark - Feedback dos Utilizadores</title>
    <link rel="icon" href="../../imagens/barreira.png" type="image/x-icon">
    <link rel="stylesheet" href="../../css/administracao.css">
    <link rel="stylesheet" href="../../css/gerir_avaliacoes.css">
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <main class="main-content">
        <div class="header">
            <div class="header-title">
                <h1>📝 Feedback dos Utilizadores</h1>
                <p class="header-subtitle">Visualize todas as avaliações e comentários</p>
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
            <div class="average-rating">
                <div class="avg-value" id="avg-rating">--</div>
                <div class="avg-stars" id="avg-stars"></div>
                <div class="avg-label">Avaliação Média</div>
                <div class="total-responses" id="total-responses">-- respostas</div>
            </div>
            <div class="stat-box rating-5">
                <div class="stat-box-value" id="count-5">0</div>
                <div class="stat-box-label">⭐⭐⭐⭐⭐ Excelente</div>
            </div>
            <div class="stat-box rating-4">
                <div class="stat-box-value" id="count-4">0</div>
                <div class="stat-box-label">⭐⭐⭐⭐ Muito Bom</div>
            </div>
            <div class="stat-box rating-3">
                <div class="stat-box-value" id="count-3">0</div>
                <div class="stat-box-label">⭐⭐⭐ Bom</div>
            </div>
            <div class="stat-box rating-2">
                <div class="stat-box-value" id="count-2">0</div>
                <div class="stat-box-label">⭐⭐ Regular</div>
            </div>
            <div class="stat-box rating-1">
                <div class="stat-box-value" id="count-1">0</div>
                <div class="stat-box-label">⭐ Fraco</div>
            </div>
        </div>

        <div class="feedback-container">
            <div class="filters-bar">
                <input type="text" class="search-input" id="searchInput"
                    placeholder="🔍 Pesquisar por nome ou mensagem...">
                <select class="filter-select" id="filterRating">
                    <option value="todos">Todas as Avaliações</option>
                    <option value="5">⭐⭐⭐⭐⭐ Excelente</option>
                    <option value="4">⭐⭐⭐⭐ Muito Bom</option>
                    <option value="3">⭐⭐⭐ Bom</option>
                    <option value="2">⭐⭐ Regular</option>
                    <option value="1">⭐ Fraco</option>
                </select>
                <select class="filter-select" id="sortBy">
                    <option value="recent">Mais Recentes</option>
                    <option value="oldest">Mais Antigas</option>
                    <option value="highest">Maior Avaliação</option>
                    <option value="lowest">Menor Avaliação</option>
                </select>
            </div>

            <div class="feedback-grid" id="feedbackGrid">
                <div class="empty-state">
                    <div class="empty-icon">🔄</div>
                    <div class="empty-text">Carregando feedbacks...</div>
                </div>
            </div>
        </div>
    </main>
    <script src="../../js/gerir_avaliacoes.js"></script>
</body>

</html>