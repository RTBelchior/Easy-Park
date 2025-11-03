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

    /* ===== HEADER ===== */
    header {
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 25px 80px;
    }

    .logo {
      font-size: 26px;
      font-weight: 600;
      color: white;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo::before {
      content: "🚧";
      font-size: 30px;
    }

    nav {
      display: flex;
      gap: 35px;
    }

    nav a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      position: relative;
      transition: all 0.3s ease;
    }

    nav a:hover {
      transform: translateY(-2px);
    }

    nav a::after {
      content: "";
      position: absolute;
      bottom: -6px;
      left: 0;
      width: 0;
      height: 2px;
      background: white;
      transition: width 0.3s;
    }

    nav a:hover::after {
      width: 100%;
    }

    /* ===== CONTEÚDO ===== */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      text-align: center;
      padding-bottom: 30px;
    }

    h1 {
      font-size: 42px;
      font-weight: bold;
      margin-top: 10px;
      animation: fadeIn 1s ease;
    }

    .map-gallery {
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 40px;
      flex-wrap: wrap;
      animation: slideUp 1s ease;
    }

    .map-card {
      margin-top: 7%;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 20px;
      overflow: hidden;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      width: 360px;
      transition: transform 0.3s;
    }

    .map-card img {
      width: 100%;
      height: 260px;
      display: block;
      object-fit: cover;
    }

    .map-info {
      background: rgba(255, 255, 255, 0.9);
      padding: 20px;
    }

    .map-card h3 {
      text-align: center;
      color: #1e3a8a;
      font-size: 22px;
      margin: 0 0 15px 0;
    }

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

    .status-disponivel {
      background: rgba(34, 197, 94, 0.2);
      color: #16a34a;
    }

    .status-poucos {
      background: rgba(234, 179, 8, 0.2);
      color: #ca8a04;
    }

    .status-lotado {
      background: rgba(239, 68, 68, 0.2);
      color: #dc2626;
    }

    .status-loading {
      background: rgba(100, 116, 139, 0.2);
      color: #64748b;
    }

    .map-card:hover {
      transform: translateY(-8px);
    }

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideUp {
      from { opacity: 0; transform: translateY(50px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    @media (max-width: 1000px) {
      header {
        padding: 20px 40px;
      }

      h1 {
        font-size: 32px;
      }

      .map-card {
        width: 300px;
      }

      .map-card img {
        height: 220px;
      }
    }
  </style>
</head>
<body>

  <header>
    <div class="logo">EasyPark</div>
    <nav>
      <a href="../index.php">Início</a>
      <a href="formulario.php">Sugestões</a>
      <a href="mapa.php">Parques</a>
      <a href="login.php">Login</a>
    </nav>
  </header>

  <main>
    <h1>Parques de estacionamento</h1>

    <div class="map-gallery">
      <div class="map-card">
        <img src="../imagens/parque1.png" alt="Mapa 1">
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
        <img src="../imagens/parque2.png" alt="Mapa 2">
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
        <img src="../imagens/parque3.png" alt="Mapa 3">
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

  <script>
    async function fetchParkingAvailability() {
      try {
        const response = await fetch('../api/get_disponibilidade.php');
        
        if (!response.ok) {
          throw new Error(`Erro HTTP: ${response.status}`);
        }
        
        const responseText = await response.text();
        console.log('Resposta do servidor:', responseText);
        
        let data;
        try {
          data = JSON.parse(responseText);
        } catch (e) {
          console.error('Erro ao fazer parse do JSON:', e);
          throw new Error('Resposta inválida do servidor');
        }
        
        if (data.success) {
          // Atualizar cada parque
          for (let i = 1; i <= 3; i++) {
            if (data.parques[i]) {
              const disponivel = data.parques[i].disponivel;
              const maxima = data.parques[i].lotacao_maxima;
              
              updateParkingCard(i, disponivel, maxima);
            }
          }
          
          console.log('Dados carregados com sucesso');
        } else {
          throw new Error(data.error || 'Erro desconhecido');
        }
        
      } catch (error) {
        console.error('Erro ao carregar disponibilidade:', error);
        
        // Mostrar erro em todos os cards
        for (let i = 1; i <= 3; i++) {
          const spotsEl = document.getElementById(`spots-${i}`);
          const statusEl = document.getElementById(`status-${i}`);
          
          if (spotsEl) spotsEl.textContent = '--';
          if (statusEl) {
            statusEl.textContent = 'Erro ao carregar';
            statusEl.className = 'status-badge status-loading';
          }
        }
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
      console.log('Página carregada, buscando disponibilidade...');
      fetchParkingAvailability();
      
      // Atualizar automaticamente a cada 30 segundos
      setInterval(fetchParkingAvailability, 30000);
    });
  </script>

</body>
</html>