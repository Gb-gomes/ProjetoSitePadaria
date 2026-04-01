<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Criar Conta</title>
    <link rel="stylesheet" href="style_register.css">
</head>
<body>
    <section class="all_page">
        <div class="container">
            <h2>Criar Conta</h2>
            
            <form method="POST" action="processa_register.php">
                <div class="input-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" name="nome" placeholder="Digite seu nome" required>
                </div>

                <div class="input-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" placeholder="Digite seu e-mail" required>
                </div>
                
                <div class="input-group">
                    <label for="senha">Senha</label>
                    <input type="password" id="senha" name="senha" placeholder="Crie uma senha" required>
                </div>
                
                <button type="submit">Cadastrar</button> 
            </form>
            
            <p class="footer-text">
                Já possui uma conta? <a href="login.php">Voltar ao Login</a>
            </p>
        </div>
    </section>
</body>
</html>