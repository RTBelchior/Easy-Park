let parkingData = {
    all: 0,
    1: 0,
    2: 0,
    3: 0
};

let currentFilter = 'all';

async function fetchAvailableSpots() {
    const statusMsg = document.getElementById('statusMessage');

    try {
        if (statusMsg) {
            statusMsg.innerHTML = '<span class="loading">A carregar dados...</span>';
        }

        // Buscar dados de todos os parques
        const response = await fetch('api/get_disponibilidade.php');

        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }

        const text = await response.text();
        console.log('📥 Resposta do servidor:', text);

        // Parse manual do formato texto
        const firstPipe = text.indexOf('|');
        const secondPipe = text.indexOf('|', firstPipe + 1);
        const thirdPipe = text.indexOf('|', secondPipe + 1);

        const status = text.substring(0, firstPipe);
        const totalMax = parseInt(text.substring(firstPipe + 1, secondPipe));
        const totalAtual = parseInt(text.substring(secondPipe + 1, thirdPipe));
        const parquesStr = text.substring(thirdPipe + 1);

        console.log('📊 Status:', status);
        console.log('📊 Total Max:', totalMax, 'Atual:', totalAtual);
        console.log('🏢 Parques:', parquesStr);

        if (status === 'SUCCESS') {
            // Calcular total disponível
            parkingData.all = totalMax - totalAtual;

            // Processar cada parque
            const parquesArray = parquesStr.split(';');
            parquesArray.forEach(parqueStr => {
                const parts = parqueStr.trim().split('|');
                const id = parseInt(parts[0]);
                const max = parseInt(parts[1]);
                const atual = parseInt(parts[2]);
                const disponivel = max - atual;

                parkingData[id] = disponivel;
                console.log(`✅ Parque ${id}: ${disponivel} disponíveis (${atual}/${max})`);
            });

            updateAllDisplays();
            console.log('✅ Dados carregados:', parkingData);

            if (statusMsg) {
                statusMsg.innerHTML = '<span class="success">✓ Dados atualizados</span>';
            }
        } else {
            throw new Error('Erro ao buscar dados');
        }

    } catch (error) {
        console.error('❌ Erro completo:', error);
        if (statusMsg) {
            statusMsg.innerHTML = `<span class="error">✗ ${error.message}</span>`;
        }
    }
}

function updateAllDisplays() {
    // Atualizar contadores dos filtros
    document.getElementById('count-all').textContent = parkingData.all;
    document.getElementById('count-1').textContent = parkingData[1];
    document.getElementById('count-2').textContent = parkingData[2];
    document.getElementById('count-3').textContent = parkingData[3];

    // Atualizar display principal
    updateMainDisplay();
}

function updateMainDisplay() {
    const display = document.getElementById('availableSpots');
    const parkingName = document.getElementById('parkingName');

    const names = {
        'all': 'todos os parques',
        '1': 'Parque 1',
        '2': 'Parque 2',
        '3': 'Parque 3'
    };

    // Atualizar número com animação
    display.style.animation = 'none';
    setTimeout(() => {
        display.textContent = parkingData[currentFilter];
        display.style.animation = 'pulse 2s ease-in-out infinite';
    }, 10);

    // Atualizar nome
    if (parkingName) {
        parkingName.textContent = names[currentFilter] || 'todos os parques';
    }
}

function filterParking(type) {
    currentFilter = type;

    // Remover classe active de todos
    document.querySelectorAll('.filter-card').forEach(card => {
        card.classList.remove('active');
    });

    // Adicionar classe active ao clicado
    event.currentTarget.classList.add('active');

    // Atualizar display principal
    updateMainDisplay();
}

// Atualizar ao carregar a página
document.addEventListener('DOMContentLoaded', function () {
    console.log('🚀 Página carregada, iniciando busca de dados...');
    fetchAvailableSpots();

    // Atualizar automaticamente a cada 30 segundos
    setInterval(fetchAvailableSpots, 30000);
});