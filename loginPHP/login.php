<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>

<!doctype html>
<html>
    <header>
    <title>Login</title>
    <meta lang="pt-br">
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style_login.css">
    </header>
    <body>
        <section class="all_page">
        <div class="container">
            <h1>Entrar</h1>
                <form method="POST" action="processa_login.php">
                    <input type="email" name="email" placeholder="E-mail" required>
                    <input type="password" name="senha" placeholder="Senha" required>
                        <button type="submit">Entrar</button>   
                    </form>
                    
                <h3>Ainda não tem uma conta? <a href="../cadastroPHP/cadastro.php">Cadastre-se</a></h3>
        </div>
        </section>
    </body>
</html>