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
  <link rel="icon" href="../imagens/barreira.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/mapa.css">
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
  <script src="../js/mapa.js"></script>

</body>

</html>