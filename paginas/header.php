<header>
  <div class="header-container">
    <div class="header-logo">EasyPark</div>
    <nav class="header-nav">
      <a href="/Easy-Park/index.php">Início</a>
      <a href="/Easy-Park/paginas/formulario.php">Sugestões</a>
      <a href="/Easy-Park/paginas/mapa.php">Mapa</a>

      <?php if (isset($_SESSION['logado']) && $_SESSION['logado'] === true): ?>
        <span class="bem-vindo">Bem-vindo, <?= htmlspecialchars($_SESSION['nome']); ?>!</span>
        <a href="/Easy-Park/api/logout.php" class="logout-link">Sair</a>
      <?php else: ?>
        <a href="/Easy-Park/paginas/login.php">Login</a>
      <?php endif; ?>
    </nav>
  </div>
</header>

<style>
  header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    padding: 20px 0;
    width: 100%;
    position: fixed; /* mantém o header visível ao descer */
    top: 0;
    left: 0;
    z-index: 999;
    animation: headerSlideDown 0.8s ease;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
  }

  /* adiciona espaço pra o conteúdo não ficar escondido atrás do header */
  body {
    padding-top: 80px;
  }

  .header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }

  .header-logo {
    font-size: 28px;
    font-weight: bold;
    color: white;
    display: flex;
    align-items: center;
    gap: 10px;
  }

  .header-logo::before {
    content: "🚧";
    font-size: 32px;
  }

  .header-nav {
    display: flex;
    align-items: center;
    gap: 30px;
  }

  .header-nav a {
    color: white;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s;
    position: relative;
  }

  .header-nav a:hover {
    transform: translateY(-2px);
  }

  .header-nav a::after {
    content: '';
    position: absolute;
    bottom: -5px;
    left: 0;
    width: 0;
    height: 2px;
    background: white;
    transition: width 0.3s;
  }

  .header-nav a:hover::after {
    width: 100%;
  }

  .bem-vindo {
    color: #fff;
    font-weight: 500;
    background: rgba(255, 255, 255, 0.15);
    padding: 6px 16px;
    border-radius: 20px;
    backdrop-filter: blur(5px);
  }

  .logout-link {
    color: #ffcccc;
    font-weight: bold;
  }

  @keyframes headerSlideDown {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
  }
</style>
