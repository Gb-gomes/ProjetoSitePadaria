<?php
session_start();

if (!isset($_SESSION['usuario_tipo']) || $_SESSION['usuario_tipo'] != 'admin') {
    header("Location: ../loginPHP/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>

    <nav class="menu">
        <a href="../index.php">Home</a>
        <a href="../produtos/Produtos.php">Produtos</a>
        <a href="../sobre_nos/index_sobre_nos.php">Sobre Nós</a>
        <a href="../contato/index_contato.php">Contato</a>
    </nav>
    <meta charset="UTF-8">
    <title>Painel Admin</title>


</head>
<body>

<h1>Painel do Administrador</h1>

<ul>
    <li><a href="adicionar_produto.php">Adicionar Produto</a></li>
    <li><a href="listar_produtos.php">Gerenciar Produtos</a></li>
    <li><a href="listar_usuarios.php">Gerenciar Usuários</a></li>
    <li><a href="adicionar_promocao.php">Adicionar Promoção</a></li>
    <li><a href="adicionar_promocao.php">Gerenciar Assinaturas</a></li>
    <li><a href="adicionar_promocao.php">Gerenciar Finanças</a></li>
</ul>

</body>
</html>