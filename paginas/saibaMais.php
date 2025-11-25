<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="pt-PT">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>EasyPark - Saiba Mais</title>
  <style>
    /* Reset básico */
    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
      min-height: 100vh;
      color: #003366;
      padding-top: 100px; 
      display: flex;
      flex-direction: column;
    }

    main {
        flex: 1; 
        display: flex;
        flex-direction: column;
        align-items: center;
        width: 100%;
    }

    /* Caixa de informação */
    .info-box {
      background-color: #f4f6ff;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      width: 90%;
      max-width: 800px;
      margin: 20px auto 50px auto;
      padding: 50px;
      text-align: center;
      opacity: 0;
      transform: translateY(30px);
      transition: opacity 1s ease, transform 1s ease;
    }

    .info-box.visible {
      opacity: 1;
      transform: translateY(0);
    }

    h2 {
      color: #1e3a8a;
      font-size: 2em;
      margin-bottom: 30px;
      font-weight: 700;
    }

    /* Regra geral para o texto da caixa */
    .info-box p {
      font-size: 1.1em;
      color: #333;
      line-height: 1.8;
      margin-bottom: 25px;
      text-align: justify;
    }

    button.voltar {
        background: white;
        color: #1e3a8a;
        border: 2px solid #1e3a8a;
        padding: 12px 35px;
        border-radius: 30px;
        font-size: 16px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.3s;
        margin-top: 20px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    button.voltar:hover {
        background: #1e3a8a;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    
    .link-texto {
        color: #3b82f6;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }
    
    .link-texto:hover {
        color: #1e3a8a;
        text-decoration: underline;
    }

    /* --- AJUSTE DO FOOTER --- */
    .site-footer .footer-bottom {
        display: block !important;
        text-align: center !important;
        width: 100%;
    }
    
    /* Forçamos a cor original (branco transparente) */
    .site-footer .footer-bottom p, 
    .site-footer .footer-bottom div {
        color: rgba(255, 255, 255, 0.6) !important;
        margin: 0 auto;
        text-align: center;
    }
  </style>
</head>
<body>

  <?php include('header.php'); ?>

  <main>
      <div class="info-box" id="content">
        <h2>Sobre o EasyPark</h2>
        <p>O <strong>EasyPark</strong> é um projeto desenvolvido para facilitar o acesso e a gestão de estacionamentos automáticos através de tecnologia moderna e intuitiva. O sistema foi pensado para oferecer mais conforto, segurança e eficiência tanto para os utilizadores como para os administradores.</p>

        <p>Com o EasyPark, é possível controlar as cancelas, monitorizar o número de vagas em tempo real e recolher sugestões dos utilizadores para melhorias constantes. O nosso objetivo é tornar o estacionamento uma experiência simples e prática para todos.</p>

        <p>Estamos sempre abertos a ouvir as suas ideias! Se tiver alguma sugestão, visite a nossa <a href="formulario.php" class="link-texto">página de sugestões</a>.</p>

        <button type="button" class="voltar" onclick="history.back()">Voltar</button>
      </div>
  </main>

  <?php include('footer.php'); ?>

  <script>
    window.addEventListener("load", () => {
      const content = document.getElementById("content");
      if(content) {
          setTimeout(() => {
            content.classList.add("visible");
          }, 200);
      }
    });
  </script>
</body>
</html>