<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se está logado e se é administrador (segurança extra)
if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: ../login.php");
    exit();
}

$pagina_atual = basename($_SERVER['PHP_SELF']);
?>

<!-- sidebar.php -->
<aside class="sidebar">
    <div class="logo">EasyPark</div>

    <div class="menu-section">
        <div class="menu-label">Painel</div>
        <a href="administracao.php"
            class="menu-item <?php echo ($pagina_atual == 'administracao.php') ? 'active' : ''; ?>"
            onclick="if(typeof showSection === 'function') showSection('lotacoes', this)">
            <span class="menu-item-icon">📊</span>
            <span>Lotações</span>
        </a>
    </div>

    <div class="menu-section">
        <div class="menu-label">Gestão</div>

        <!-- Botão Histórico / Gestão de Acessos -->
        <a href="historico.php" class="menu-item <?php echo ($pagina_atual == 'historico.php') ? 'active' : ''; ?>">
            <span class="menu-item-icon">🚗</span>
            <span>Gestão de Acessos</span>
        </a>

        <a href="gestao_cartoes.php" class="menu-item">
            <span class="menu-icon">💳</span>
            <span class="menu-text">Gestão de Cartões</span>
        </a>

        <a href="gerir_avaliacoes.php" class="menu-item">
            <span class="menu-icon">📝</span>
            <span class="menu-text">Feedbacks</span>
        </a>

        <a href="#" class="menu-item">
            <span class="menu-item-icon">📝</span>
            <span>Relatórios</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <a href="../../index.php" class="menu-item">
            <span class="menu-item-icon">🏠</span>
            <span>Voltar ao Início</span>
        </a>
    </div>
</aside>