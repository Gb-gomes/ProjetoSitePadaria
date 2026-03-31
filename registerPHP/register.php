<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
</head>
<body>

<h1>Cadastrar</h1>

<form method="POST" action="processa_register.php">
    <input type="text" name="nome" placeholder="Nome" required><br><br>
    
    <input type="email" name="email" placeholder="E-mail" required><br><br>
    
    <input type="password" name="senha" placeholder="Senha" required><br><br>
    
    <button type="submit">Cadastrar</button>
</form>

<a href="login.php">Voltar para login</a>

</body>
</html>