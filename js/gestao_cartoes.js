let allCards = [];
let currentAction = null;

// Buscar User Info
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

// Buscar Cards
async function fetchCards() {
    try {
        const response = await fetch('../../api/get_cartoes.php');
        const text = await response.text();
        const trimmedText = text.trim();
        console.log('Resposta:', trimmedText);

        const parts = trimmedText.split('|');

        if (parts[0] === 'SUCCESS') {
            allCards = JSON.parse(parts[1]);
            updateStats();
            renderCards(allCards);
        }
    } catch (error) {
        console.error('Erro ao carregar cartões:', error);
        document.getElementById('cardsTableBody').innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-icon">❌</div>
                            <div>Erro ao carregar cartões</div>
                        </td>
                    </tr>
                `;
    }
}

// autalizar Stats
function updateStats() {
    const total = allCards.length;
    // Verificar tanto string '1' quanto número 1
    const ativos = allCards.filter(c => c.ativo_cartao === '1' || c.ativo_cartao === 1).length;
    const inativos = total - ativos;

    console.log('Stats:', { total, ativos, inativos });
    console.log('Cartões:', allCards.map(c => ({ id: c.id_cartao, ativo: c.ativo_cartao, tipo: typeof c.ativo_cartao })));

    document.getElementById('total-cartoes').textContent = total;
    document.getElementById('cartoes-ativos').textContent = ativos;
    document.getElementById('cartoes-inativos').textContent = inativos;
}

// renderizar Cards
function renderCards(cards) {
    const tbody = document.getElementById('cardsTableBody');

    if (cards.length === 0) {
        tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <div class="empty-state-icon">🔍</div>
                            <div>Nenhum cartão encontrado</div>
                        </td>
                    </tr>
                `;
        return;
    }

    tbody.innerHTML = cards.map(card => {
        // Verificar tanto string '1' quanto número 1
        const isAtivo = card.ativo_cartao === '1' || card.ativo_cartao === 1;
        const statusClass = isAtivo ? 'ativo' : 'inativo';
        const statusText = isAtivo ? 'Ativo' : 'Inativo';
        const tipoClass = card.id_tipo_cartao === '1' || card.id_tipo_cartao === 1 ? 'tipo-tag' : 'tipo-cartao';
        const tipoText = card.id_tipo_cartao === '1' || card.id_tipo_cartao === 1 ? 'Tag' : 'Cartão';
        const btnClass = isAtivo ? 'btn-deactivate' : 'btn-activate';
        const btnText = isAtivo ? '🚫 Desativar' : '✅ Ativar';
        const dataFormatada = new Date(card.data_registo_cartao).toLocaleDateString('pt-PT');

        return `
                    <tr>
                        <td><strong>${card.nome_utilizador}</strong></td>
                        <td>${card.numero_utilizador}</td>
                        <td><code>${card.numero_cartao}</code></td>
                        <td><span class="tipo-badge ${tipoClass}">${tipoText}</span></td>
                        <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                        <td>${dataFormatada}</td>
                        <td>
                            <button class="action-btn ${btnClass}" onclick="toggleCardStatus(${card.id_cartao}, ${isAtivo}, '${card.nome_utilizador}', '${card.numero_cartao}')">
                                ${btnText}
                            </button>
                        </td>
                    </tr>
                `;
    }).join('');
}

// Toggle Card Status
function toggleCardStatus(idCartao, isAtivo, nomeUtilizador, numeroCartao) {
    const action = isAtivo ? 'desativar' : 'ativar';
    const newStatus = isAtivo ? 0 : 1;

    document.getElementById('modalTitle').textContent = `${action.charAt(0).toUpperCase() + action.slice(1)} Cartão`;
    document.getElementById('modalText').textContent = `Tem certeza que deseja ${action} o cartão ${numeroCartao} de ${nomeUtilizador}?`;

    currentAction = { idCartao, newStatus };
    document.getElementById('confirmModal').classList.add('show');
}

// Confirmar ação
document.getElementById('confirmBtn').addEventListener('click', async () => {
    if (!currentAction) return;

    console.log('Enviando requisição:', currentAction);

    try {
        const response = await fetch('../../api/toggle_cartao.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id_cartao=${currentAction.idCartao}&status=${currentAction.newStatus}`
        });

        const text = await response.text();
        console.log('Resposta do servidor:', text);

        if (text.startsWith('SUCCESS')) {
            console.log('Sucesso! Recarregando cartões...');
            await fetchCards();
            closeModal();
        } else {
            console.error('Erro na resposta:', text);
            alert('Erro ao atualizar cartão: ' + text);
        }
    } catch (error) {
        console.error('Erro na requisição:', error);
        alert('Erro ao atualizar cartão: ' + error.message);
    }
});

// Close Modal
function closeModal() {
    document.getElementById('confirmModal').classList.remove('show');
    currentAction = null;
}

// Filtrar cartoes 
function filterCards() {
    const searchText = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('filterStatus').value;
    const tipoFilter = document.getElementById('filterTipo').value;

    let filtered = allCards;

    if (searchText) {
        filtered = filtered.filter(card =>
            card.nome_utilizador.toLowerCase().includes(searchText) ||
            card.numero_utilizador.toString().includes(searchText) ||
            card.numero_cartao.toLowerCase().includes(searchText)
        );
    }

    if (statusFilter !== 'todos') {
        // Comparar considerando string e número
        filtered = filtered.filter(card =>
            card.ativo_cartao === statusFilter ||
            card.ativo_cartao === parseInt(statusFilter)
        );
    }

    if (tipoFilter !== 'todos') {
        // Comparar considerando string e número
        filtered = filtered.filter(card =>
            card.id_tipo_cartao === tipoFilter ||
            card.id_tipo_cartao === parseInt(tipoFilter)
        );
    }

    renderCards(filtered);
}

// Event Listeners
document.getElementById('searchInput').addEventListener('input', filterCards);
document.getElementById('filterStatus').addEventListener('change', filterCards);
document.getElementById('filterTipo').addEventListener('change', filterCards);

document.addEventListener('DOMContentLoaded', () => {
    fetchUserInfo();
    fetchCards();
});