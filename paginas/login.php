<?php
session_start();

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
  header("Location: ../index.php");
  exit();
}
?>
<!DOCTYPE html>
<html lang="pt">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>EasyPark - Login</title>
  <link rel="icon" href="../imagens/barreira.png" type="image/x-icon">
  <link rel="stylesheet" href="../css/login.css">
</head>

<body>

  <?php include('header.php'); ?>

  <main>
    <div class="auth-card">
      <h1>Bem-vindo de volta</h1>
      <p class="subtitle">Insira as suas credenciais IPS para continuar</p>

      <!-- ÁREA DE ERROS DINÂMICA -->
      <?php if (isset($_SESSION['login_erro'])): ?>
        <div class="error-message">
          <span>⚠️</span>
          <span><?= htmlspecialchars($_SESSION['login_erro']) ?></span>
        </div>
        <?php unset($_SESSION['login_erro']); ?>
      <?php endif; ?>

      <form action="../api/verifi_login.php" method="post">

        <div class="form-group">
          <label for="email">Email Institucional</label>
          <div class="input-wrapper">
            <input type="email" id="email" name="email" placeholder="ex: numero@estudantes.ips.pt" required
              value="<?= isset($_SESSION['temp_email']) ? htmlspecialchars($_SESSION['temp_email']) : '' ?>"
              autocomplete="email">
            <span class="input-icon">📧</span>
          </div>
        </div>

        <div class="form-group">
          <label for="password">Palavra-passe</label>
          <div class="input-wrapper">
            <input type="password" id="password" name="password" placeholder="••••••••" required>
            <span class="input-icon">🔒</span>
          </div>
        </div>

        <button type="submit" class="primary-btn">Entrar na Plataforma</button>

        <p class="footer-note">
          Acesso exclusivo para membros do<br><strong>Instituto Politécnico de Setúbal</strong>
        </p>
      </form>
    </div>
  </main>

    <?php include('footer.php'); ?>
</body>

</html>