<?php
session_start(); // le a mensagem de erro na tela
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style_login.css">
</head>
<body>
    <section class="all_page">
        <div class="container">
            <h2>Entrar</h2>

            <?php if (isset($_SESSION['erro_login'])): ?>
                <p id="mensagem"><?php echo $_SESSION['erro_login']; ?></p>
                <?php unset($_SESSION['erro_login']); // da o refresh, limpa o campo da msg erro ?>
            <?php endif; ?>

            <form method="POST" action="processa_login.php">
                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
                </div>
                
                <div class="input-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Digite sua senha" required>
                </div>
                
                <button type="submit">Entrar</button> 
            </form>
            
            <p class="footer-text">
                Ainda não tem uma conta? <a href="../registerPHP/register.php">Cadastre-se</a>
            </p>
        </div>
    </section>
</body>
</html>