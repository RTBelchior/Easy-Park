let allAccessData = [];
let filteredData = [];

// Load User Data
async function loadUserData() {
    try {
        const response = await fetch('../api/get_meu_cartao.php');
        const text = await response.text();
        const parts = text.trim().split('|');

        if (parts[0] === 'SUCCESS') {
            document.getElementById('userAvatar').textContent = parts[4];
            document.getElementById('userName').textContent = parts[1];
            document.getElementById('userNumber').textContent = `Nº ${parts[2]}`;
        }
    } catch (error) {
        console.error('Erro ao carregar usuário:', error);
    }
}

// Load Parking Lots
async function loadParkingLots() {
    try {
        const response = await fetch('../api/get_parques.php');
        const text = await response.text();
        const parts = text.trim().split('|');

        if (parts[0] === 'SUCCESS') {
            const parks = JSON.parse(parts[1]);
            const select = document.getElementById('filterParque');
            select.innerHTML = '<option value="">Todos os parques</option>' +
                parks.map(p => `<option value="${p.id_parque}">Parque ${p.id_parque}</option>`).join('');
        }
    } catch (error) {
        console.error('Erro ao carregar parques:', error);
    }
}

// Fetch My Access
async function fetchMyAccess() {
    try {
        const response = await fetch('../api/get_meus_acessos.php');
        const text = await response.text();
        const parts = text.trim().split('|');

        if (parts[0] === 'SUCCESS') {
            allAccessData = JSON.parse(parts[1]);
            filteredData = [...allAccessData];
            updateStats();
            renderTable();
        }
    } catch (error) {
        console.error('Erro ao carregar acessos:', error);
        document.getElementById('accessTableBody').innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-state">
                            <div class="empty-icon">❌</div>
                            <div class="empty-text">Erro ao carregar acessos</div>
                        </td>
                    </tr>
                `;
    }
}

// Update Stats
function updateStats() {
    const entradas = allAccessData.filter(a => a.tipo_acesso === 'entrada').length;
    const saidas = allAccessData.filter(a => a.tipo_acesso === 'saida').length;

    // Calcular tempo total em minutos
    let tempoTotalMin = 0;
    allAccessData.forEach(access => {
        if (access.tipo_acesso === 'saida') {
            // Encontrar entrada correspondente
            const entrada = allAccessData.find(a =>
                a.tipo_acesso === 'entrada' &&
                new Date(a.data_hora_acesso) < new Date(access.data_hora_acesso)
            );
            if (entrada) {
                const diff = new Date(access.data_hora_acesso) - new Date(entrada.data_hora_acesso);
                tempoTotalMin += Math.floor(diff / 60000);
            }
        }
    });
    const tempoTotalHoras = (tempoTotalMin / 60).toFixed(1);

    document.getElementById('totalAcessos').textContent = allAccessData.length;
    document.getElementById('totalEntradas').textContent = entradas;
    document.getElementById('totalSaidas').textContent = saidas;
    document.getElementById('tempoTotal').textContent = tempoTotalHoras;
}

// Apply Filters
function aplicarFiltros() {
    const tipo = document.getElementById('filterTipo').value;
    const parque = document.getElementById('filterParque').value;
    const dataInicio = document.getElementById('filterDataInicio').value;
    const dataFim = document.getElementById('filterDataFim').value;

    filteredData = allAccessData.filter(access => {
        if (tipo && access.tipo_acesso !== tipo) return false;
        if (parque && access.id_parque != parque) return false;

        if (dataInicio) {
            const accessDate = new Date(access.data_hora_acesso);
            const startDate = new Date(dataInicio);
            if (accessDate < startDate) return false;
        }

        if (dataFim) {
            const accessDate = new Date(access.data_hora_acesso);
            const endDate = new Date(dataFim + ' 23:59:59');
            if (accessDate > endDate) return false;
        }

        return true;
    });

    renderTable();
}

// Clear Filters
function limparFiltros() {
    document.getElementById('filterTipo').value = '';
    document.getElementById('filterParque').value = '';
    document.getElementById('filterDataInicio').value = '';
    document.getElementById('filterDataFim').value = '';
    filteredData = [...allAccessData];
    renderTable();
}

// Render Table
function renderTable() {
    const tbody = document.getElementById('accessTableBody');

    if (filteredData.length === 0) {
        tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="empty-state">
                            <div class="empty-icon">🔍</div>
                            <div class="empty-text">Nenhum acesso encontrado</div>
                        </td>
                    </tr>
                `;
        return;
    }

    tbody.innerHTML = filteredData.map(access => {
        const tipoClass = access.tipo_acesso === 'entrada' ? 'type-entrada' : 'type-saida';
        const tipoIcon = access.tipo_acesso === 'entrada' ? '🚗' : '🚀';
        const tipoText = access.tipo_acesso === 'entrada' ? 'Entrada' : 'Saída';

        const date = new Date(access.data_hora_acesso);
        const formattedDate = date.toLocaleString('pt-PT', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });

        // Calculate duration
        let durationHTML = '<span style="color: #94a3b8;">--</span>';
        if (access.tipo_acesso === 'saida') {
            const entrada = filteredData.find(a =>
                a.tipo_acesso === 'entrada' &&
                new Date(a.data_hora_acesso) < new Date(access.data_hora_acesso)
            );
            if (entrada) {
                const diff = new Date(access.data_hora_acesso) - new Date(entrada.data_hora_acesso);
                const hours = Math.floor(diff / 3600000);
                const mins = Math.floor((diff % 3600000) / 60000);
                durationHTML = `<span class="duration-display">${hours}h ${mins}m</span>`;
            }
        }

        return `
                    <tr>
                        <td><span class="type-badge ${tipoClass}">${tipoIcon} ${tipoText}</span></td>
                        <td><span class="park-badge">Parque ${access.id_parque}</span></td>
                        <td>${formattedDate}</td>
                        <td>${durationHTML}</td>
                    </tr>
                `;
    }).join('');
}

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadUserData();
    loadParkingLots();
    fetchMyAccess();
});