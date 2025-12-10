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
  <link rel="icon" href="../imagens/barreira.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/saibaMais.css">
</head>
<body>

  <?php include('header.php'); ?>

  <main>
      <div class="info-box" id="content">
        <h2>Sobre o EasyPark</h2>
        <p>O <strong>EasyPark</strong> é um projeto desenvolvido para facilitar o acesso e a gestão de estacionamentos automáticos através de tecnologia moderna e intuitiva. O sistema foi pensado para oferecer mais conforto, segurança e eficiência tanto para os utilizadores como para os administradores.</p>

        <p>Com o EasyPark, é possível controlar as cancelas, monitorizar o número de vagas em tempo real e recolher sugestões dos utilizadores para melhorias constantes. O nosso objetivo é tornar o estacionamento uma experiência simples e prática para todos.</p>

        <p>Estamos sempre abertos a ouvir as suas ideias! Se tiver alguma sugestão, visite a nossa <a href="formulario.php" class="link-texto">página de sugestões</a>.</p>

        <button type="button" class="voltar">Voltar</button>
      </div>
  </main>

  <?php include('footer.php'); ?>

  <script src="../js/saibaMais.js"></script>
</body>
</html>