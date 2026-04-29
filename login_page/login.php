
<?php include 'auth.php'; ?>

<!doctype html>
<html>
<header>
<title>Login</title>
<meta lang="pt-br">
<meta charset="UTF-8">
<link rel="stylesheet" href="css/estilo.css" >
</header>
<body>
<div class="form_container">
<h1>Entrar</h1>
<form method="POST" action="processa_login.php">
<input type="email" name="email" placeholder="E-mail" required>
<input type="password" name="senha" placeholder="Senha"

required>

<button type="submit">Entrar</button>
</form>
</div>
</body>
</html>