let currentPage = 1;
let totalPages = 1;
const recordsPerPage = 20;

// --- FETCH USER INFO ---
async function fetchUserInfo() {
    try {
        // Ajusta o caminho da API conforme a tua estrutura
        const response = await fetch('../../api/get_user_info.php');
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
        }
    } catch (error) { console.log('Erro user info:', error); }
}

// Buscar acessos
async function buscarAcessos(page = 1) {
    currentPage = page;

    const nome = document.getElementById('filterNome').value;
    const numero = document.getElementById('filterNumero').value;
    const tipo = document.getElementById('filterTipo').value;
    const parque = document.getElementById('filterParque').value;
    const dataInicio = document.getElementById('filterDataInicio').value;
    const dataFim = document.getElementById('filterDataFim').value;

    const params = new URLSearchParams({
        page: page,
        limit: recordsPerPage,
        nome: nome,
        numero: numero,
        tipo: tipo,
        parque: parque,
        data_inicio: dataInicio,
        data_fim: dataFim
    });

    try {
        document.getElementById('tableContent').innerHTML = '<div class="loading-state">⏳ Carregando dados...</div>';

        const response = await fetch(`../../api/get_historico_entradas.php?${params}`);
        const text = await response.text();

        // console.log('📥 Resposta:', text);

        const parts = text.trim().split('|');

        if (parts[0] === 'SUCCESS') {
            const totalRecords = parseInt(parts[1]);
            const recordsData = parts[2];

            totalPages = Math.ceil(totalRecords / recordsPerPage);

            document.getElementById('recordsCount').textContent =
                `${totalRecords} registro${totalRecords !== 1 ? 's' : ''} encontrado${totalRecords !== 1 ? 's' : ''}`;

            if (totalRecords === 0) {
                mostrarEstadoVazio();
            } else {
                renderizarTabela(recordsData);
                atualizarPaginacao();
            }
        } else {
            throw new Error(parts[1] || 'Erro ao buscar dados');
        }

    } catch (error) {
        console.error('❌ Erro:', error);
        document.getElementById('tableContent').innerHTML =
            '<div class="empty-state"><div class="empty-state-icon">⚠️</div><p>Erro ao carregar dados: ' + error.message + '</p></div>';
    }
}

function renderizarTabela(recordsData) {
    if (!recordsData || recordsData === 'EMPTY') {
        mostrarEstadoVazio();
        return;
    }

    const records = recordsData.split(';');

    // MODIFICADO: Adicionado style="width:100%" para garantir responsividade
    let html = `
                <table style="width: 100%">
                    <thead>
                        <tr>
                            <th>Data/Hora</th>
                            <th>Nome</th>
                            <th>Nº Aluno</th>
                            <th>Tipo</th>
                            <th>Parque</th>
                            <th>Cartão</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

    records.forEach(record => {
        const fields = record.split(',');
        if (fields.length >= 6) {
            const dataHora = fields[0];
            const nome = fields[1];
            const numero = fields[2];
            const tipo = fields[3];
            const parque = fields[4];
            const cartao = fields[5];

            const badgeClass = tipo === 'entrada' ? 'badge-entrada' : 'badge-saida';
            const tipoText = tipo === 'entrada' ? '🟢 Entrada' : '🔴 Saída';

            // MODIFICADO: Adicionado 'data-label' em cada <td> para a view mobile
            html += `
                        <tr>
                            <td data-label="Data/Hora">${formatarDataHora(dataHora)}</td>
                            <td data-label="Nome"><strong>${nome}</strong></td>
                            <td data-label="Nº Aluno">${numero}</td>
                            <td data-label="Tipo"><span class="badge ${badgeClass}">${tipoText}</span></td>
                            <td data-label="Parque"><span class="parque-badge">🅿️ Parque ${parque}</span></td>
                            <td data-label="Cartão"><code>${cartao}</code></td>
                        </tr>
                    `;
        }
    });

    html += '</tbody></table>';
    document.getElementById('tableContent').innerHTML = html;
}

function mostrarEstadoVazio() {
    document.getElementById('tableContent').innerHTML = `
                <div class="empty-state">
                    <div class="empty-state-icon">📭</div>
                    <p>Nenhum registro encontrado com os filtros aplicados.</p>
                </div>
            `;
    document.getElementById('pagination').style.display = 'none';
}

function atualizarPaginacao() {
    const pagination = document.getElementById('pagination');
    const pageInfo = document.getElementById('pageInfo');

    if (totalPages > 1) {
        pagination.style.display = 'flex';
        pageInfo.textContent = `Página ${currentPage} de ${totalPages}`;

        const prevBtn = pagination.querySelector('button:first-child');
        const nextBtn = pagination.querySelector('button:last-child');

        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    } else {
        pagination.style.display = 'none';
    }
}

function previousPage() {
    if (currentPage > 1) {
        buscarAcessos(currentPage - 1);
    }
}

function nextPage() {
    if (currentPage < totalPages) {
        buscarAcessos(currentPage + 1);
    }
}

function limparFiltros() {
    document.getElementById('filterNome').value = '';
    document.getElementById('filterNumero').value = '';
    document.getElementById('filterTipo').value = '';
    document.getElementById('filterParque').value = '';
    document.getElementById('filterDataInicio').value = '';
    document.getElementById('filterDataFim').value = '';
    buscarAcessos(1);
}

function formatarDataHora(dataHora) {
    const data = new Date(dataHora);
    const dia = String(data.getDate()).padStart(2, '0');
    const mes = String(data.getMonth() + 1).padStart(2, '0');
    const ano = data.getFullYear();
    const hora = String(data.getHours()).padStart(2, '0');
    const min = String(data.getMinutes()).padStart(2, '0');

    return `${dia}/${mes}/${ano} ${hora}:${min}`;
}

document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Página carregada!');
    fetchUserInfo();
    buscarAcessos(1);
});

document.querySelectorAll('.filter-input, .filter-select').forEach(element => {
    element.addEventListener('keypress', function (e) {
        if (e.key === 'Enter') {
            buscarAcessos(1);
        }
    });
});