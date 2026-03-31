<?php
require 'security.php';
?>

<h1>Bem-vindo, <?php echo $_SESSION['usuario_nome']; ?>!</h1>
<a href="logout.php">Sair</a>