async function fetchParkingAvailability() {
    try {
        const response = await fetch('../api/get_disponibilidade.php');

        if (!response.ok) {
            throw new Error(`Erro HTTP: ${response.status}`);
        }

        const text = await response.text();
        console.log('📥 Resposta do servidor:', text);

        // Parse manual
        const firstPipe = text.indexOf('|');
        const secondPipe = text.indexOf('|', firstPipe + 1);
        const thirdPipe = text.indexOf('|', secondPipe + 1);

        const status = text.substring(0, firstPipe);
        const totalMax = parseInt(text.substring(firstPipe + 1, secondPipe));
        const totalAtual = parseInt(text.substring(secondPipe + 1, thirdPipe));
        const parquesStr = text.substring(thirdPipe + 1);

        if (status === 'SUCCESS') {

            // 1. Atualizar o Cartão GRANDE (Total)
            const totalDisponivel = totalMax - totalAtual;
            updateParkingCard('total', totalDisponivel, totalMax);

            // 2. Atualizar Parques Individuais
            const parquesArray = parquesStr.split(';');

            parquesArray.forEach(parqueStr => {
                const parts = parqueStr.trim().split('|');
                if (parts.length >= 3) {
                    const id = parseInt(parts[0]);
                    const max = parseInt(parts[1]);
                    const atual = parseInt(parts[2]);
                    const disponivel = max - atual;

                    updateParkingCard(id, disponivel, max);
                }
            });

            console.log('✅ Dados carregados com sucesso');
        } else {
            throw new Error('Erro ao buscar dados');
        }

    } catch (error) {
        console.error('❌ Erro ao carregar disponibilidade:', error);

        ['1', '2', '3', 'total'].forEach(id => {
            const spotsEl = document.getElementById(`spots-${id}`);
            const statusEl = document.getElementById(`status-${id}`);

            if (spotsEl) spotsEl.textContent = '--';
            if (statusEl) {
                statusEl.textContent = 'Erro ao carregar';
                statusEl.className = 'status-badge status-loading';
            }
        });
    }
}

function updateParkingCard(parkId, disponivel, maxima) {
    const spotsEl = document.getElementById(`spots-${parkId}`);
    const statusEl = document.getElementById(`status-${parkId}`);

    if (spotsEl) {
        spotsEl.textContent = disponivel;
    }

    if (statusEl) {
        const percentagem = (disponivel / maxima) * 100;

        if (disponivel === 0) {
            statusEl.textContent = 'Lotado';
            statusEl.className = 'status-badge status-lotado';
        } else if (percentagem < 20) {
            statusEl.textContent = 'Poucos lugares';
            statusEl.className = 'status-badge status-poucos';
        } else {
            statusEl.textContent = 'Disponível';
            statusEl.className = 'status-badge status-disponivel';
        }
    }
}

// Carregar dados ao iniciar a página
document.addEventListener('DOMContentLoaded', function () {
    fetchParkingAvailability();
    setInterval(fetchParkingAvailability, 30000);
});