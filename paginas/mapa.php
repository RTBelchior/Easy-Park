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
  <title>EasyPark - Mapas</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      color: white;
    }

    /* ===== CONTEÚDO ===== */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      align-items: center;
      padding-bottom: 50px;
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
    }

    h1 {
      font-size: 42px;
      font-weight: bold;
      margin-top: 30px;
      margin-bottom: 30px;
      animation: fadeIn 1s ease;
      text-align: center;
    }

    /* --- SECÇÃO TOTAL (GRANDE) --- */
    /* Agora está em cima, ajustei as margens */
    .total-section {
        width: 100%;
        padding: 0 20px;
        margin-bottom: 40px; /* Espaço em baixo para separar dos pequenos */
        animation: slideUp 1s ease;
        display: flex;
        justify-content: center;
    }

    .map-card.large {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        width: 100%;
        max-width: 1110px;
        display: flex;
        flex-direction: row; /* Imagem ao lado do texto */
        align-items: stretch;
        transition: transform 0.3s;
    }

    .map-card.large:hover {
        transform: translateY(-5px);
    }

    .map-card.large img {
        width: 60%;
        height: auto;
        min-height: 300px;
        object-fit: cover;
        display: block;
    }

    .map-card.large .map-info {
        width: 40%;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        background: rgba(255, 255, 255, 0.9);
    }

    .map-card.large h3 {
        color: #1e3a8a;
        font-size: 32px;
        margin-bottom: 30px;
        text-align: center;
    }

    .map-card.large .spots-number {
        font-size: 48px;
    }

    /* --- Galeria dos 3 Parques Pequenos --- */
    .map-gallery {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 30px;
      flex-wrap: wrap;
      animation: slideUp 1.2s ease; /* Animação ligeiramente atrasada */
      width: 100%;
      padding: 0 20px;
    }

    .map-card {
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      width: 350px;
      transition: transform 0.3s;
      display: flex;
      flex-direction: column;
    }

    .map-card img {
      width: 100%;
      height: 220px;
      display: block;
      object-fit: cover;
    }

    .map-info {
      background: rgba(255, 255, 255, 0.9);
      padding: 20px;
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .map-card h3 {
      text-align: center;
      color: #1e3a8a;
      font-size: 22px;
      margin: 0 0 15px 0;
    }

    /* --- Estilos Comuns de Disponibilidade --- */
    .availability-info {
      display: flex;
      flex-direction: column;
      gap: 10px;
      align-items: center;
    }

    .available-spots {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
      color: white;
      padding: 15px 25px;
      border-radius: 12px;
      font-size: 16px;
      width: 100%;
    }

    .spots-number {
      font-size: 32px;
      font-weight: bold;
      animation: pulse 2s ease-in-out infinite;
    }

    .spots-label {
      font-size: 14px;
      opacity: 0.9;
    }

    .status-badge {
      padding: 6px 16px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
    }

    /* Cores dos Estados */
    .status-disponivel { background: rgba(34, 197, 94, 0.2); color: #16a34a; }
    .status-poucos { background: rgba(234, 179, 8, 0.2); color: #ca8a04; }
    .status-lotado { background: rgba(239, 68, 68, 0.2); color: #dc2626; }
    .status-loading { background: rgba(100, 116, 139, 0.2); color: #64748b; }

    .map-card:hover {
      transform: translateY(-8px);
    }

    /* Animações */
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    @keyframes slideUp { from { opacity: 0; transform: translateY(50px); } to { opacity: 1; transform: translateY(0); } }
    @keyframes pulse { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.05); } }

    /* Responsividade */
    @media (max-width: 1000px) {
      .map-card.large {
          flex-direction: column;
      }
      .map-card.large img {
          width: 100%;
          height: 250px;
      }
      .map-card.large .map-info {
          width: 100%;
      }
    }
  </style>
</head>
<body>

  <?php include('header.php'); ?> 

  <main>
    <h1>Parques de Estacionamento</h1>

    <!-- 1. SECÇÃO TOTAL (GRANDE) - AGORA EM CIMA -->
    <div class="total-section">
        <div class="map-card large">
            <img src="../imagens/todos_parques.jpeg" alt="Visão Geral">
            <div class="map-info">
            <h3>Todos os Parques</h3>
            <div class="availability-info">
                <div class="available-spots">
                <div>
                    <div class="spots-number" id="spots-total">--</div>
                    <div class="spots-label">lugares totais</div>
                </div>
                </div>
                <span class="status-badge status-loading" id="status-total">A carregar...</span>
            </div>
            </div>
        </div>
    </div>

    <!-- 2. GALERIA DE PARQUES INDIVIDUAIS - AGORA EM BAIXO -->
    <div class="map-gallery">
      <div class="map-card">
        <img src="../imagens/parque1.jpeg" alt="Mapa 1">
        <div class="map-info">
          <h3>Parque 1</h3>
          <div class="availability-info">
            <div class="available-spots">
              <div>
                <div class="spots-number" id="spots-1">--</div>
                <div class="spots-label">lugares</div>
              </div>
            </div>
            <span class="status-badge status-loading" id="status-1">A carregar...</span>
          </div>
        </div>
      </div>

      <div class="map-card">
        <img src="../imagens/parque2.jpeg" alt="Mapa 2">
        <div class="map-info">
          <h3>Parque 2</h3>
          <div class="availability-info">
            <div class="available-spots">
              <div>
                <div class="spots-number" id="spots-2">--</div>
                <div class="spots-label">lugares</div>
              </div>
            </div>
            <span class="status-badge status-loading" id="status-2">A carregar...</span>
          </div>
        </div>
      </div>

      <div class="map-card">
        <img src="../imagens/parque3.jpeg" alt="Mapa 3">
        <div class="map-info">
          <h3>Parque 3</h3>
          <div class="availability-info">
            <div class="available-spots">
              <div>
                <div class="spots-number" id="spots-3">--</div>
                <div class="spots-label">lugares</div>
              </div>
            </div>
            <span class="status-badge status-loading" id="status-3">A carregar...</span>
          </div>
        </div>
      </div>
    </div>
  </main>

  <?php include('footer.php'); ?>

  <script>
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
            if(parts.length >= 3) {
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
    document.addEventListener('DOMContentLoaded', function() {
      fetchParkingAvailability();
      setInterval(fetchParkingAvailability, 30000);
    });
  </script>

</body>
</html>