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

    $nome = $_SESSION['nome'] ?? 'Utilizador';
    $palavras = explode(' ', $nome);
    if (count($palavras) >= 2) {
        $iniciais = strtoupper(substr($palavras[0], 0, 1) . substr(end($palavras), 0, 1));
    } else {
        $iniciais = strtoupper(substr($nome, 0, 2));
    }

    $tipo_user = $_SESSION['tipo'] ?? 'cliente';

    // Verifica se é o ID 1 (Admin) OU a string 'Administrador'
    if ($tipo_user == 1 || $tipo_user === 'Administrador') {
        $link_administracao = '/Easy-Park/paginas/administracao/administracao.php';
    } else {
        $link_administracao = '/Easy-Park/paginas/utilizadores_acessos.php';
    }
}
?>

<header>
    <div class="header-container">
        <!-- Logo -->
        <a href="/Easy-Park/index.php" style="text-decoration:none;">
            <div class="header-logo">EasyPark</div>
        </a>

        <!-- Botão Mobile (Hambúrguer) -->
        <div class="menu-toggle" onclick="toggleMobileMenu()">
            <span class="bar"></span>
            <span class="bar"></span>
            <span class="bar"></span>
        </div>

        <nav class="header-nav" id="navMenu">
            <!-- Links Comuns -->
            <a href="/Easy-Park/index.php">Início</a>
            <a href="/Easy-Park/paginas/mapa.php">Mapa</a>
            <a href="/Easy-Park/paginas/formulario.php">Sugestões</a>
            
            <!-- Link Meu Cartão NFC (A classe .menu-item controla a visibilidade) -->
            <a href="/Easy-Park/paginas/cartao_nfc_virtual.php" class="menu-item">
                <span class="menu-text">Meu Cartão NFC</span>
            </a>

            <?php if ($mostrar_admin): ?>
                <!-- Link Dinâmico de Administração -->
                <a href="<?= $link_administracao ?>">Administração</a>

                <!-- Menu de Utilizador -->
                <div class="user-dropdown">
                    <div class="user-avatar">
                        <?= $iniciais ?>
                    </div>

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

<script>
    function toggleMobileMenu() {
        const nav = document.getElementById('navMenu');
        nav.classList.toggle('active');
    }
</script>

<style>
    header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 20px 0;
        width: 100%;
        position: fixed;
        top: 0;
        left: 0;
        z-index: 2000;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
    }

    body {
        padding-top: 80px;
    }

    .header-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
    }

    .header-logo {
        font-size: 26px;
        font-weight: bold;
        color: white !important;
        display: flex;
        align-items: center;
        gap: 10px;
        z-index: 2001;
    }

    .header-logo::before {
        content: "🚧";
        font-size: 30px;
    }

    /* --- Botão Hambúrguer --- */
    .menu-toggle {
        display: none;
        flex-direction: column;
        cursor: pointer;
        gap: 6px;
        z-index: 2001;
        padding: 5px;
    }

    .bar {
        width: 28px;
        height: 3px;
        background-color: white;
        border-radius: 3px;
        transition: all 0.3s ease;
    }

    /* --- Navegação --- */
    .header-nav {
        display: flex;
        align-items: center;
        gap: 25px;
    }

    .header-nav>a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.3s;
        position: relative;
        font-size: 16px;
    }

    /* --- REGRA NOVA: ESCONDER "MEU CARTÃO" NO DESKTOP --- */
    .menu-item {
        display: none; 
    }
    /* ---------------------------------------------------- */

    .header-nav>a:hover {
        transform: translateY(-2px);
    }

    .header-nav>a::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 0;
        height: 2px;
        background: white;
        transition: width 0.3s;
    }

    .header-nav>a:hover::after {
        width: 100%;
    }

    .login-btn {
        background: rgba(255, 255, 255, 0.2);
        padding: 8px 20px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        color: white !important;
    }

    .login-btn:hover {
        background: white;
        color: #1e3a8a !important;
    }

    /* --- Dropdown do Utilizador --- */
    .user-dropdown {
        position: relative;
        margin-left: 10px;
        display: flex;
        align-items: center;
        padding-bottom: 10px;
        margin-bottom: -10px;
    }

    .user-avatar {
        width: 40px;
        height: 40px;
        background-color: white;
        color: #1e3a8a;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 15px;
        cursor: pointer;
        border: 2px solid rgba(255, 255, 255, 0.5);
    }

    .dropdown-menu {
        display: none;
        position: absolute;
        top: 100%;
        right: 0;
        background: white;
        min-width: 200px;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        padding: 10px 0;
        z-index: 2002;
        margin-top: 5px;
    }

    .user-dropdown:hover .dropdown-menu {
        display: block;
    }

    .dropdown-menu::before {
        content: "";
        position: absolute;
        top: -20px;
        left: 0;
        width: 100%;
        height: 20px;
        background: transparent;
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
        color: #333 !important;
        text-decoration: none;
        font-size: 15px;
    }

    .dropdown-item:hover {
        background-color: #f3f4f6;
        color: #1e3a8a !important;
    }

    .dropdown-item.danger {
        color: #dc2626 !important;
    }

    .dropdown-divider {
        height: 1px;
        background-color: #eee;
        margin: 5px 0;
    }

    /* =========================================
       RESPONSIVIDADE (MOBILE) - AJUSTES
       ========================================= */
    @media (max-width: 768px) {

        /* --- REGRA NOVA: MOSTRAR "MEU CARTÃO" NO MOBILE --- */
        .menu-item {
            display: block !important; 
        }
        /* -------------------------------------------------- */

        .header-container {
            padding: 0 50px;
        }

        .menu-toggle {
            display: flex;
        }

        /* Menu Mobile */
        .header-nav {
            display: none;
            flex-direction: column;
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 10px 0;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .header-nav.active {
            display: flex;
            animation: slideDown 0.3s ease;
        }

        .header-nav>a {
            display: block;
            width: 100%;
            text-align: center;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            color: #ffffff !important;
            font-size: 18px;
            opacity: 1 !important;
            visibility: visible !important;
        }

        .header-nav>a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: none;
        }

        .header-nav>a::after {
            display: none;
        }

        .login-btn {
            width: 80%;
            margin: 20px auto;
            text-align: center;
            display: block;
        }

        .user-dropdown {
            width: 100%;
            justify-content: center;
            flex-direction: column;
            padding: 15px 0;
            margin: 0;
        }

        .dropdown-menu {
            position: relative;
            top: 0;
            left: 0;
            width: 90%;
            margin: 10px auto 0 auto;
            background: white;
            display: none;
        }

        .user-dropdown:hover .dropdown-menu {
            display: block;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }
</style>