<?php
session_start();
// Se já estiver logado, redireciona
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

  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; }
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    main {
      flex: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .auth-card {
      width: 100%;
      max-width: 400px;
      background: rgba(255, 255, 255, 0.95);
      border-radius: 24px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      padding: 40px 30px;
      text-align: center;
      position: relative;
      border: 1px solid rgba(255,255,255,0.4);
    }

    h1 {
      color: #1e3a8a;
      margin-bottom: 8px;
      font-size: 26px;
    }

    .subtitle {
      font-size: 14px;
      color: #64748b;
      margin-bottom: 30px;
    }

    /* Caixa de Erro */
    .error-message {
      background-color: #fef2f2;
      color: #dc2626;
      padding: 12px;
      border-radius: 12px;
      border: 1px solid #fecaca;
      font-size: 14px;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
      animation: shake 0.5s ease-in-out;
      text-align: left;
    }

    .form-group {
      margin-bottom: 20px;
      text-align: left;
      position: relative;
    }

    label {
      display: block;
      font-weight: 600;
      color: #334155;
      margin-bottom: 8px;
      font-size: 14px;
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper input {
      width: 100%;
      padding: 14px 14px 14px 45px;
      border-radius: 12px;
      border: 2px solid #e2e8f0;
      font-size: 15px;
      transition: all 0.3s ease;
      background: #f8fafc;
    }

    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #94a3b8;
      font-size: 18px;
    }

    .input-wrapper input:focus {
      border-color: #3b82f6;
      background: #fff;
      box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
      outline: none;
    }

    .input-wrapper input:focus + .input-icon {
      color: #3b82f6;
    }

    .primary-btn {
      width: 100%;
      padding: 14px;
      border: none;
      border-radius: 12px;
      background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
      color: white;
      font-weight: bold;
      cursor: pointer;
      font-size: 16px;
      margin-top: 10px;
      transition: all 0.3s ease;
      box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }

    .primary-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
    }

    .footer-note {
      font-size: 13px;
      color: #64748b;
      margin-top: 25px;
      line-height: 1.5;
    }

    @keyframes shake {
      0%, 100% { transform: translateX(0); }
      25% { transform: translateX(-5px); }
      75% { transform: translateX(5px); }
    }
  </style>
</head>
<body>

  <?php  include('header.php'); ?>

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
                <input 
                  type="email" 
                  id="email" 
                  name="email" 
                  placeholder="ex: numero@estudantes.ips.pt" 
                  required
                  autocomplete="email">
                <span class="input-icon">📧</span>
            </div>
        </div>
        
        <div class="form-group">
            <label for="password">Palavra-passe</label>
            <div class="input-wrapper">
                <input 
                  type="password" 
                  id="password" 
                  name="password" 
                  placeholder="••••••••" 
                  required>
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