<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado e se é administrador
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php");
    exit();
}

$pagina_atual = basename($_SERVER['PHP_SELF']);
?>

<!-- sidebar.php -->
<aside class="sidebar" id="mySidebar">
    
    <!-- Header da Sidebar com Logo e Botão Mobile -->
    <div class="sidebar-header">
        <!-- O link agora tem a classe 'logo-link' para alinharmos no CSS -->
        <a href="/Easy-Park/paginas/administracao/administracao.php" class="logo-link">
            <div class="logo">EasyPark</div>
        </a>
        
        <!-- Botão com os 3 traços -->
        <button class="mobile-toggle" onclick="toggleSidebar()">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </div>

    <!-- Contentor dos links -->
    <div class="sidebar-nav">
        <div class="menu-section">
            <div class="menu-label">Painel</div>
            <a href="administracao.php"
                class="menu-item <?php echo ($pagina_atual == 'administracao.php') ? 'active' : ''; ?>">
                <span class="menu-item-icon">📊</span>
                <span>Lotações</span>
            </a>
        </div>

        <div class="menu-section">
            <div class="menu-label">Gestão</div>
            <a href="historico.php" class="menu-item <?php echo ($pagina_atual == 'historico.php') ? 'active' : ''; ?>">
                <span class="menu-item-icon">🚗</span>
                <span>Gestão de Acessos</span>
            </a>
            <a href="gestao_cartoes.php" class="menu-item <?php echo ($pagina_atual == 'gestao_cartoes.php') ? 'active' : ''; ?>">
                <span class="menu-icon">💳</span>
                <span class="menu-text">Gestão de Cartões</span>
            </a>
            <a href="gerir_avaliacoes.php" class="menu-item <?php echo ($pagina_atual == 'gerir_avaliacoes.php') ? 'active' : ''; ?>">
                <span class="menu-icon">📝</span>
                <span class="menu-text">Feedbacks</span>
            </a>
        </div>

        <div class="sidebar-footer">
            <a href="../../index.php" class="menu-item">
                <span class="menu-item-icon">🏠</span>
                <span>Voltar ao Início</span>
            </a>
        </div>
    </div>
</aside>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('mySidebar');
        sidebar.classList.toggle('mobile-open');
    }
</script>

<style>
    /* CSS Base (Desktop) */
    .sidebar {
        width: 240px;
        background: white;
        padding: 30px 20px;
        box-shadow: 2px 0 10px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        height: 100vh;
        position: sticky;
        top: 0;
        z-index: 100;
        transition: all 0.3s ease;
    }

    /* Ocultar underline no link do logo */
    .logo-link {
        text-decoration: none;
    }

    /* Header e Logo */
    .sidebar-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 40px;
    }

    .logo {
        font-size: 24px;
        font-weight: 700;
        color: #1e3a8a;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .logo::before {
        content: "🚧";
        font-size: 28px;
    }

    /* Navegação */
    .sidebar-nav {
        display: flex;
        flex-direction: column;
        flex: 1;
    }

    .menu-section { margin-bottom: 30px; }

    .menu-label {
        font-size: 11px;
        font-weight: 600;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 10px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        border-radius: 10px;
        color: #64748b;
        text-decoration: none;
        font-weight: 500;
        transition: all 0.2s;
        margin-bottom: 4px;
    }

    .menu-item:hover { background: #f1f5f9; color: #1e3a8a; }
    .menu-item.active { background: #3b82f6; color: white; }
    .menu-item-icon, .menu-icon { font-size: 20px; }

    /* Footer / Sair */
    .sidebar-footer {
        margin-top: auto;
        border-top: 1px solid #f1f5f9;
        padding-top: 20px;
    }

    .menu-item.logout { color: #ef4444; }
    .menu-item.logout:hover {
        background: #fef2f2;
        color: #dc2626;
        transform: translateX(5px);
    }

    /* Botão Hamburguer (Oculto em Desktop) */
    .mobile-toggle {
        display: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 5px;
    }

    .mobile-toggle span {
        display: block;
        width: 25px;
        height: 3px;
        margin-bottom: 5px;
        position: relative;
        background: #1e3a8a;
        border-radius: 3px;
        transition: transform 0.3s cubic-bezier(0.77, 0.2, 0.05, 1.0);
    }
    .mobile-toggle span:last-child { margin-bottom: 0; }


    /* =========================================
       RESPONSIVIDADE (MOBILE / TABLET)
       ========================================= */
    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: sticky;
            top: 0;
            padding: 15px 20px;
            /* Altura fixa do header facilita alinhamento */
            min-height: 70px;
        }

        /* HEADER - O Segredo do alinhamento */
        .sidebar-header {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Alinhamento Vertical Crucial */
            width: 100%;
            margin-bottom: 0;
            height: 100%;
        }

        /* LINK DO LOGO */
        .logo-link {
            display: flex;       /* Torna o link flexível também */
            align-items: center; /* Alinha o conteúdo (logo div) ao centro do link */
            height: 40px;        /* Altura fixa ajuda a igualar ao botão */
        }

        /* LOGO */
        .logo {
            margin-bottom: 0 !important;
            font-size: 22px; 
            display: flex;
            align-items: center; /* Alinha Emoji com Texto */
            line-height: 1;      /* Remove espaçamentos estranhos da fonte */
        }

        .logo::before {
            font-size: 24px;
            margin-top: -2px; /* Pequeno ajuste fino visual */
            margin-right: 8px; /* Espaço entre emoji e texto */
        }

        /* BOTÃO TOGGLE */
        .mobile-toggle {
            display: flex;
            flex-direction: column;
            justify-content: center; /* Alinha traços verticalmente dentro do botão */
            height: 40px;            /* Mesma altura que o logo-link */
            padding: 0 0 0 10px;
        }

        /* MENU DESLIZANTE */
        .sidebar-nav {
            display: none;
            padding-top: 20px;
            width: 100%;
            border-top: 1px solid #f1f5f9;
            margin-top: 15px;
            animation: fadeIn 0.3s ease;
        }

        /* ESTADO ABERTO */
        .sidebar.mobile-open .sidebar-nav {
            display: flex;
            min-height: calc(100vh - 70px); 
        }

        /* Animação do X */
        .sidebar.mobile-open .mobile-toggle span:first-child {
            transform: rotate(45deg) translate(5px, 6px);
        }
        .sidebar.mobile-open .mobile-toggle span:nth-child(2) {
            opacity: 0;
        }
        .sidebar.mobile-open .mobile-toggle span:last-child {
            transform: rotate(-45deg) translate(6px, -6px);
        }
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>