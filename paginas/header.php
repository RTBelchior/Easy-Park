<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Lógica para definir variáveis quando o utilizador está logado
$iniciais = '';
$link_administracao = '';
$mostrar_admin = false;

if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    $mostrar_admin = true;

    // 1. Gerar Iniciais
    $nome = $_SESSION['nome'] ?? 'Utilizador';
    $palavras = explode(' ', $nome);
    if (count($palavras) >= 2) {
        $iniciais = strtoupper(substr($palavras[0], 0, 1) . substr(end($palavras), 0, 1));
    } else {
        $iniciais = strtoupper(substr($nome, 0, 2));
    }

    // 2. Definir link da página de administração
    $tipo_user = $_SESSION['tipo'] ?? 'cliente';

    if ($tipo_user === 'administrador') {
        $link_administracao = '/Easy-Park/paginas/administracao.php';
    } else {
        $link_administracao = '/Easy-Park/paginas/registoEntradas.php';
    }
}
?>

<header>
    <div class="header-container">
        <!-- Logo -->
        <a href="/Easy-Park/index.php" style="text-decoration:none;">
            <div class="header-logo">EasyPark</div>
        </a>

        <nav class="header-nav">
            <!-- Links Comuns -->
            <a href="/Easy-Park/index.php">Início</a>
            <a href="/Easy-Park/paginas/mapa.php">Mapa</a>
            <a href="/Easy-Park/paginas/formulario.php">Sugestões</a>

            <?php if ($mostrar_admin): ?>
                <!-- Link Dinâmico de Administração -->
                <a href="<?= $link_administracao ?>">Administração</a>
                
                <!-- Menu de Utilizador (Bolinha com Iniciais) -->
                <div class="user-dropdown">
                    <!-- Removi o onclick, agora é só passar o rato -->
                    <div class="user-avatar">
                        <?= $iniciais ?>
                    </div>
                    
                    <!-- Submenu Dropdown -->
                    <div class="dropdown-menu">
                        <div class="dropdown-header">
                            Olá, <?= htmlspecialchars(strtok($_SESSION['nome'], " ")) ?>
                        </div>
                        <a href="/Easy-Park/paginas/perfil.php" class="dropdown-item">
                            👤 Meu Perfil
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="/Easy-Park/api/logout.php" class="dropdown-item danger">
                            🚪 Terminar Sessão
                        </a>
                    </div>
                </div>

            <?php else: ?>
                <!-- Link de Login -->
                <a href="/Easy-Park/paginas/login.php" class="login-btn">Login</a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<style>
    /* --- Estilos Gerais do Header --- */
    header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 15px 0;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 999;
        box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    }

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

    /* --- Navegação --- */
    .header-nav {
        display: flex;
        align-items: center;
        gap: 25px;
        height: 100%; /* Garante altura para alinhamento */
    }

    .header-nav > a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        position: relative;
        font-size: 16px;
    }

    .header-nav > a:hover {
        transform: translateY(-2px);
    }

    .header-nav > a::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 0;
        height: 2px;
        background: white;
        transition: width 0.3s;
    }

    .header-nav > a:hover::after {
        width: 100%;
    }

    .login-btn {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 20px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    .login-btn:hover {
        background: white;
        color: #1e3a8a !important;
    }

    /* --- SOLUÇÃO DO MENU RÁPIDO --- */
    
    .user-dropdown {
        position: relative;
        margin-left: 10px;
        /* Aumentamos a área clicável para baixo sem afetar o visual */
        padding-bottom: 20px; 
        margin-bottom: -20px; /* Compensa o padding para não desalinhar */
        display: flex;
        align-items: center;
    }

    .user-avatar {
        width: 42px;
        height: 42px;
        background-color: white;
        color: #1e3a8a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 16px;
        cursor: pointer;
        border: 2px solid rgba(255, 255, 255, 0.5);
        transition: all 0.3s ease;
        /* Garante que o avatar fica acima da ponte se necessário */
        position: relative; 
        z-index: 2;
    }

    .user-avatar:hover {
        transform: scale(1.05);
        box-shadow: 0 0 15px rgba(255, 255, 255, 0.4);
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        /* Posiciona o menu logo abaixo do avatar */
        top: 100%; 
        right: 0;
        background: white;
        min-width: 200px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        padding: 10px 0;
        overflow: visible; /* Importante para a ponte funcionar */
        animation: fadeIn 0.3s ease;
        z-index: 10;
        /* Removemos margem para colar mais na área do rato */
        margin-top: -10px; 
    }

    /* PONTE INVISÍVEL MÁGICA */
    /* Cria uma área transparente acima do menu que conecta à bolinha */
    .dropdown-menu::before {
        content: "";
        position: absolute;
        top: -30px; /* Sobe 30px para cobrir qualquer buraco */
        left: 0;
        width: 100%;
        height: 30px;
        background: transparent; /* Invisível */
    }

    /* Ao passar o rato em TODO o conjunto user-dropdown, mostra o menu */
    .user-dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-header {
        padding: 10px 20px;
        font-size: 14px;
        color: #666;
        border-bottom: 1px solid #eee;
        font-weight: bold;
    }

    .dropdown-item {
        display: block;
        padding: 12px 20px;
        color: #333;
        text-decoration: none;
        transition: background 0.2s;
        font-size: 15px;
    }

    .dropdown-item:hover {
        background-color: #f3f4f6;
        color: #1e3a8a;
    }

    .dropdown-item.danger {
        color: #dc2626;
    }
    
    .dropdown-item.danger:hover {
        background-color: #fee2e2;
    }

    .dropdown-divider {
        height: 1px;
        background-color: #eee;
        margin: 5px 0;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>