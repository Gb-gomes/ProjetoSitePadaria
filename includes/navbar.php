<?php
if (!defined('BASE_URL')) {
    define('BASE_URL', '/projetositepadaria');
}
?>

<nav>

    <a href="<?= BASE_URL ?>/index.php">Home</a>

    <a href="<?= BASE_URL ?>/pages/produtos/Produtos.php">
        Produtos
    </a>

    <a href="<?= BASE_URL ?>/pages/sobre_nos/index_sobre_nos.php">
        Sobre Nós
    </a>

    <a href="<?= BASE_URL ?>/pages/contato/index_contato.php">
        Contato
    </a>

     <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 'admin'): ?>
            <a href="<?= BASE_URL ?>/administrador/index_admin.php" class="admin-link">Painel Admin</a>
    <?php endif; ?>
</nav>