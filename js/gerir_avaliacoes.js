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
        switch (sortBy) {
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