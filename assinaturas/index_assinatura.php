<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assinatura | PAD</title>
    <link rel="stylesheet" href="index_assinatura.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css"
        integrity="sha512-DxV+EoADOkOygM4IR9yXP8Sb2qwgidEmeqAEmDKIOfPRQZOWbXCzLC6vjbZyy0vPisbH2SyW27+ddLVCN+OMzQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

</head> 

<body>
    <section class="all_page">
         <header class="header">

            <section class="logo">
                <img src="../img/logo.png" alt="Logo da padaria">
            </section>

            <nav class="menu">
                <a href="../index.php">Home</a>
                <a href="../produtos/Produtos.php">Produtos</a>
                <a href="../sobre_nos/index_sobre_nos.php">Sobre Nós</a>
                <a href="../contato/index_contato.php">Contato</a>

                <?php if (isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == 'admin'): ?>
                    <a href="../administrador/index_admin.php" class="admin-link">Painel Admin</a>
                <?php endif; ?>
            </nav>

            <section class="group">

                <div class="carrinho">
                    <i class="fa-solid fa-cart-plus"></i>
                </div>

                <div class="cadastro_conteiner">

                    <?php if (isset($_SESSION['usuario_id'])): ?>

                        <div class="user-info">

                            <?php if ($_SESSION['usuario_tipo'] == 'admin'): ?>
                                <span class="admin">
                                    ADMIN - <?php echo $_SESSION['usuario_nome']; ?>
                                </span>
                            <?php else: ?>
                                <span class="user">
                                    Olá, <?php echo $_SESSION['usuario_nome']; ?>
                                </span>
                            <?php endif; ?>

                            <a href="../loginPHP/login.php" class="button-logout">Sair</a>

                        </div>

                    <?php else: ?>
                        <a href="../loginPHP/login.php" class="button-login">Login</a>
                    <?php endif; ?>

                </div>

            </section>
        </header>

  <div class="container">
    <div class="form-section">
      <h1>Assinatura de Pães</h1>
      <p>Receba pães artesanais fresquinhos direto da padaria, com gostinho de vó.</p>

      <form>
        <label for="nome">Nome</label>
        <input type="text" id="nome" name="nome" placeholder="Seu nome" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="seu@email.com" required>

        <label for="plano">Plano</label>
        <select id="plano" name="plano" required>
          <option value="">Selecione</option>
          <option value="semanal">Semanal</option>
          <option value="quinzenal">Quinzenal</option>
          <option value="mensal">Mensal</option>
        </select>

        <button type="submit">Assinar agora</button>
      </form>
    </div>

    <div class="image-section">
      <img src="/img/ass-pao.png" alt="Pães artesanais">
    </div>
  </div>
</body>
</html>
</body>
</html>