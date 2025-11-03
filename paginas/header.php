<!-- header.php -->
<header>
  <div class="header-container">
    <div class="header-logo">EasyPark</div>
    <nav class="header-nav">
        <a href="/Easy-Park/index.php">Início</a>
        <a href="/Easy-Park/paginas/formulario.php">Sugestões</a>
        <a href="/Easy-Park/paginas/mapa.php">Mapa</a>
        <a href="/Easy-Park/paginas/login.php">Login</a>
    </nav>
  </div>
</header>

<style>
  header {
    background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    padding: 20px 0;
    width: 100%;
    animation: slideDown 0.8s ease;
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
    gap: 40px;
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

    @keyframes headerSlideDown {
    from { opacity: 0; transform: translateY(-50px); }
    to { opacity: 1; transform: translateY(0); }
    }

    header {
    animation: headerSlideDown 0.8s ease;
    }

</style>
