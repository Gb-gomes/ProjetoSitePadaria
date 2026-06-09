<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/projetositepadaria');
}
?>

<!-- Link para o CSS da navbar -->
<link rel="stylesheet" href="<?= BASE_URL ?>/includes/navbar.css">

<nav class="nav-modern">
    <div class="nav-container">
        <button class="menu-toggle" id="menuToggle">
            ☰
        </button>
        
        <div class="nav-links" id="navLinks">
            <a href="<?= BASE_URL ?>/index.php">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="<?= BASE_URL ?>/pages/produtos/Produtos.php">
                <i class="fas fa-bread-slice"></i> Produtos
            </a>
            <a href="<?= BASE_URL ?>/pages/sobre_nos/index_sobre_nos.php">
                <i class="fas fa-info-circle"></i> Sobre Nós
            </a>
            <a href="<?= BASE_URL ?>/pages/contato/index_contato.php">
                <i class="fas fa-envelope"></i> Contato
            </a>
            
            <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 'admin'): ?>
                <a href="<?= BASE_URL ?>/administrador/index_admin.php" class="admin-link">
                    <i class="fas fa-chart-line"></i> Painel Admin
                </a>
            <?php endif; ?>
        </div>

        <div class="cadastro_container">
            <?php if (isset($_SESSION['usuario_id'])): ?>
                <div class="user-info">
                    <?php if ($_SESSION['usuario_tipo'] == 'admin'): ?>
                        <span class="admin">
                            <i class="fas fa-crown"></i> ADMIN - <?php echo $_SESSION['usuario_nome']; ?>
                        </span>
                    <?php else: ?>
                        <span class="user">
                            <i class="fas fa-user"></i> Olá, <?php echo $_SESSION['usuario_nome']; ?>
                        </span>
                    <?php endif; ?>
                    
                    <a href="<?= BASE_URL ?>/loginPHP/logout.php" class="button-logout">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </div>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/loginPHP/login.php" class="button-login">
                    <i class="fas fa-key"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<script>
// Menu mobile toggle
document.getElementById('menuToggle').addEventListener('click', function() {
    document.getElementById('navLinks').classList.toggle('show');
});

// Fechar menu ao clicar em um link
document.querySelectorAll('#navLinks a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth <= 768) {
            document.getElementById('navLinks').classList.remove('show');
        }
    });
});
</script>