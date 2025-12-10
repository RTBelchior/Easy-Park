let allAccessData = [];
let filteredData = [];

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    loadUserData();
    fetchMyAccess();
});

// Load User Data
async function loadUserData() {
    try {
        const response = await fetch('../api/get_user_info.php');
        const text = await response.text();
        
        // Formato API: SUCCESS|Nome|Tipo|Iniciais|Email...
        const parts = text.split('|');

        if (parts[0] === 'SUCCESS') {
            const userAvatar = document.getElementById('userAvatar');
            if (userAvatar) userAvatar.textContent = parts[3]; // Iniciais

            const userName = document.getElementById('userName');
            if (userName) userName.textContent = parts[1]; // Nome

            const userNumber = document.getElementById('userNumber');
            if (userNumber) userNumber.textContent = parts[4]; // Email
        }
    } catch (error) {
        console.error('Erro ao carregar usuário:', error);
    }
}

// Fetch My Access (ADAPTADO PARA TEXTO)
async function fetchMyAccess() {
    try {
        const response = await fetch('../api/get_meus_acessos.php');
        const text = await response.text();
        
        console.log('Resposta Histórico:', text);

        const primeiroPipe = text.indexOf('|');
        const status = text.substring(0, primeiroPipe);
        const dadosRaw = text.substring(primeiroPipe + 1);

        if (status === 'SUCCESS') {
            
            // Se estiver vazio
            if (!dadosRaw || dadosRaw.trim() === "") {
                allAccessData = [];
                filteredData = [];
                updateStats();
                renderTable();
                return;
            }

            // Converter string "tipo|data|parque;..." para array de objetos
            const linhas = dadosRaw.split(';');
            
            allAccessData = linhas.map(linha => {
                if(linha.trim() === "") return null;
                
                const campos = linha.split('|');
                // Formato: TIPO|DATA|ID_PARQUE
                return {
                    tipo_acesso: campos[0],
                    data_hora_acesso: campos[1],
                    id_parque: campos[2]
                };
            }).filter(item => item !== null);

            filteredData = [...allAccessData];
            updateStats();
            renderTable();

        } else {
            throw new Error(dadosRaw || 'Erro desconhecido');
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
    
    // Para calcular o tempo, precisamos de ordenar cronologicamente (do antigo para o novo)
    const cronologico = [...allAccessData].reverse();

    cronologico.forEach((access, index) => {
        if (access.tipo_acesso === 'saida') {
            // Procura a entrada imediatamente anterior a esta saída
            for (let i = index - 1; i >= 0; i--) {
                const prev = cronologico[i];
                if (prev.tipo_acesso === 'entrada' && prev.id_parque === access.id_parque) {
                    const diff = new Date(access.data_hora_acesso) - new Date(prev.data_hora_acesso);
                    if (diff > 0) {
                        tempoTotalMin += Math.floor(diff / 60000);
                        break; 
                    }
                }
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
            const accessDateStr = access.data_hora_acesso.split(' ')[0];
            if (accessDateStr < dataInicio) return false;
        }

        if (dataFim) {
            const accessDateStr = access.data_hora_acesso.split(' ')[0];
            if (accessDateStr > dataFim) return false;
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

    tbody.innerHTML = filteredData.map((access, index) => {
        const tipoClass = access.tipo_acesso === 'entrada' ? 'type-entrada' : 'type-saida';
        const tipoIcon = access.tipo_acesso === 'entrada' ? '🚗' : '🚀';
        const tipoText = access.tipo_acesso === 'entrada' ? 'Entrada' : 'Saída';

        const date = new Date(access.data_hora_acesso);
        const formattedDate = date.toLocaleString('pt-PT', {
            day: '2-digit', month: '2-digit', year: 'numeric',
            hour: '2-digit', minute: '2-digit'
        });

        // Calculate duration
        let durationHTML = '<span style="color: #94a3b8;">--</span>';
        
        if (access.tipo_acesso === 'saida') {
            for (let i = index + 1; i < filteredData.length; i++) {
                const next = filteredData[i];
                if (next.tipo_acesso === 'entrada' && next.id_parque === access.id_parque) {
                    const diff = new Date(access.data_hora_acesso) - new Date(next.data_hora_acesso);
                    if (diff > 0) {
                        const hours = Math.floor(diff / 3600000);
                        const mins = Math.floor((diff % 3600000) / 60000);
                        durationHTML = `<span class="duration-display">${hours}h ${mins}m</span>`;
                    }
                    break; 
                }
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