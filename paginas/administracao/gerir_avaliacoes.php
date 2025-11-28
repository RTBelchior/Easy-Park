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
    <link rel="stylesheet" href="../../css/administracao.css">
    <style>
        .feedback-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .stats-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            border-radius: 12px;
            color: white;
            text-align: center;
        }

        .stat-box.rating-1 { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); }
        .stat-box.rating-2 { background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); }
        .stat-box.rating-3 { background: linear-gradient(135deg, #eab308 0%, #ca8a04 100%); }
        .stat-box.rating-4 { background: linear-gradient(135deg, #84cc16 0%, #65a30d 100%); }
        .stat-box.rating-5 { background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%); }

        .stat-box-value {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .stat-box-label {
            font-size: 13px;
            opacity: 0.9;
        }

        .filters-bar {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
            flex-wrap: wrap;
            align-items: center;
        }

        .filter-select {
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            background: white;
            cursor: pointer;
        }

        .search-input {
            flex: 1;
            min-width: 250px;
            padding: 10px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
        }

        .feedback-grid {
            display: grid;
            gap: 16px;
        }

        .feedback-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            transition: all 0.2s;
        }

        .feedback-card:hover {
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 12px;
        }

        .feedback-user {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
        }

        .user-details {
            display: flex;
            flex-direction: column;
        }

        .user-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 15px;
        }

        .user-number {
            font-size: 12px;
            color: #64748b;
        }

        .rating-stars {
            display: flex;
            gap: 4px;
        }

        .star {
            font-size: 20px;
        }

        .star.filled {
            color: #fbbf24;
        }

        .star.empty {
            color: #e2e8f0;
        }

        .feedback-message {
            background: white;
            padding: 16px;
            border-radius: 8px;
            margin-top: 12px;
            color: #334155;
            line-height: 1.6;
            border-left: 4px solid #3b82f6;
        }

        .feedback-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 12px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
        }

        .feedback-date {
            font-size: 13px;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .rating-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-1 { background: #fee2e2; color: #991b1b; }
        .badge-2 { background: #fed7aa; color: #9a3412; }
        .badge-3 { background: #fef3c7; color: #854d0e; }
        .badge-4 { background: #d9f99d; color: #3f6212; }
        .badge-5 { background: #d1fae5; color: #065f46; }

        .empty-state {
            text-align: center;
            padding: 60px 24px;
            color: #94a3b8;
        }

        .empty-icon {
            font-size: 64px;
            margin-bottom: 16px;
        }

        .empty-text {
            font-size: 18px;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .empty-subtext {
            font-size: 14px;
        }

        .average-rating {
            background: white;
            padding: 24px;
            border-radius: 12px;
            border: 2px solid #e2e8f0;
            text-align: center;
        }

        .avg-value {
            font-size: 48px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .avg-label {
            color: #64748b;
            font-size: 14px;
        }

        .avg-stars {
            display: flex;
            justify-content: center;
            gap: 4px;
            margin: 12px 0;
        }

        .total-responses {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 8px;
        }
    </style>
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
                <input type="text" class="search-input" id="searchInput" placeholder="🔍 Pesquisar por nome ou mensagem...">
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

    <script>
        let allFeedbacks = [];

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

        // Fetch Feedbacks
        async function fetchFeedbacks() {
            try {
                const response = await fetch('../../api/get_formularios.php');
                const text = await response.text();
                const trimmedText = text.trim();
                console.log('Resposta:', trimmedText);

                const parts = trimmedText.split('|');
                
                if (parts[0] === 'SUCCESS') {
                    allFeedbacks = JSON.parse(parts[1]);
                    updateStats();
                    renderFeedbacks(allFeedbacks);
                }
            } catch (error) {
                console.error('Erro ao carregar feedbacks:', error);
                document.getElementById('feedbackGrid').innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">❌</div>
                        <div class="empty-text">Erro ao carregar feedbacks</div>
                    </div>
                `;
            }
        }

        // Update Stats
        function updateStats() {
            const total = allFeedbacks.length;
            const counts = { 1: 0, 2: 0, 3: 0, 4: 0, 5: 0 };
            let sum = 0;

            allFeedbacks.forEach(fb => {
                const rating = parseInt(fb.avaliacao_form);
                counts[rating]++;
                sum += rating;
            });

            const avg = total > 0 ? (sum / total).toFixed(1) : 0;

            // Update average
            document.getElementById('avg-rating').textContent = avg;
            document.getElementById('total-responses').textContent = `${total} ${total === 1 ? 'resposta' : 'respostas'}`;

            // Update average stars
            const avgStarsEl = document.getElementById('avg-stars');
            avgStarsEl.innerHTML = generateStars(Math.round(avg));

            // Update counts
            for (let i = 1; i <= 5; i++) {
                document.getElementById(`count-${i}`).textContent = counts[i];
            }
        }

        // Generate Stars HTML
        function generateStars(rating) {
            let html = '';
            for (let i = 1; i <= 5; i++) {
                html += `<span class="star ${i <= rating ? 'filled' : 'empty'}">★</span>`;
            }
            return html;
        }

        // Get Rating Text
        function getRatingText(rating) {
            const texts = {
                1: 'Fraco',
                2: 'Regular',
                3: 'Bom',
                4: 'Muito Bom',
                5: 'Excelente'
            };
            return texts[rating] || '';
        }

        // Format Date
        function formatDate(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diff = now - date;
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));

            if (days === 0) return 'Hoje';
            if (days === 1) return 'Ontem';
            if (days < 7) return `Há ${days} dias`;
            
            return date.toLocaleDateString('pt-PT', { 
                day: '2-digit', 
                month: '2-digit', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Get User Initials
        function getInitials(name) {
            return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        }

        // Render Feedbacks
        function renderFeedbacks(feedbacks) {
            const grid = document.getElementById('feedbackGrid');
            
            if (feedbacks.length === 0) {
                grid.innerHTML = `
                    <div class="empty-state">
                        <div class="empty-icon">🔍</div>
                        <div class="empty-text">Nenhum feedback encontrado</div>
                        <div class="empty-subtext">Tente ajustar os filtros de pesquisa</div>
                    </div>
                `;
                return;
            }

            grid.innerHTML = feedbacks.map(fb => {
                const rating = parseInt(fb.avaliacao_form);
                const stars = generateStars(rating);
                const initials = getInitials(fb.nome_utilizador);
                const ratingText = getRatingText(rating);
                const formattedDate = formatDate(fb.data_hora_form);

                return `
                    <div class="feedback-card">
                        <div class="feedback-header">
                            <div class="feedback-user">
                                <div class="user-avatar">${initials}</div>
                                <div class="user-details">
                                    <div class="user-name">${fb.nome_utilizador}</div>
                                    <div class="user-number">Nº ${fb.numero_utilizador}</div>
                                </div>
                            </div>
                            <div class="rating-stars">${stars}</div>
                        </div>
                        <div class="feedback-message">${fb.mensagem_form}</div>
                        <div class="feedback-footer">
                            <div class="feedback-date">
                                🕒 ${formattedDate}
                            </div>
                            <div class="rating-badge badge-${rating}">${ratingText}</div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Filter and Sort
        function applyFilters() {
            const searchText = document.getElementById('searchInput').value.toLowerCase();
            const ratingFilter = document.getElementById('filterRating').value;
            const sortBy = document.getElementById('sortBy').value;

            let filtered = [...allFeedbacks];

            // Search filter
            if (searchText) {
                filtered = filtered.filter(fb => 
                    fb.nome_utilizador.toLowerCase().includes(searchText) ||
                    fb.mensagem_form.toLowerCase().includes(searchText) ||
                    fb.numero_utilizador.toString().includes(searchText)
                );
            }

            // Rating filter
            if (ratingFilter !== 'todos') {
                filtered = filtered.filter(fb => fb.avaliacao_form === ratingFilter);
            }

            // Sort
            filtered.sort((a, b) => {
                switch(sortBy) {
                    case 'recent':
                        return new Date(b.data_hora_form) - new Date(a.data_hora_form);
                    case 'oldest':
                        return new Date(a.data_hora_form) - new Date(b.data_hora_form);
                    case 'highest':
                        return parseInt(b.avaliacao_form) - parseInt(a.avaliacao_form);
                    case 'lowest':
                        return parseInt(a.avaliacao_form) - parseInt(b.avaliacao_form);
                    default:
                        return 0;
                }
            });

            renderFeedbacks(filtered);
        }

        // Event Listeners
        document.getElementById('searchInput').addEventListener('input', applyFilters);
        document.getElementById('filterRating').addEventListener('change', applyFilters);
        document.getElementById('sortBy').addEventListener('change', applyFilters);

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            fetchUserInfo();
            fetchFeedbacks();
        });
    </script>
</body>
</html>