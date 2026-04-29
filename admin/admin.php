<?php

require 'security.php';
require 'conecta.php';
echo "usuário logado";

?>
<!doctype html>
<html>
<header>
	<title>Login</title>
	<meta lang="pt-br">
    <meta charset="UTF-8">
	<!--link rel="stylesheet" href="css/estilo.css" -->
</header>
<body>
	<a href="logout.php">Sair 2</a>
<?php	
	$sql = "SELECT * FROM produto";
	$stmt = $pdo->query($sql);
	//Com loop usando while
	echo "<h2>Lista de Clientes</h2>";
	while ($row = $stmt->fetch()) {
        echo "ID: " . $row['id_produto'] . "<br>";
        echo "Descrição: " . $row['descricao'] . "<br>";
        echo "modelo: " . $row['modelo'] . "<br>";
        echo "gramatura: " . $row['gramatura'] . "<br>";
		echo "cor: " . $row['cor'] . "<br>";
		echo "preco: " . $row['preco'] . "<br>";
        echo "-----------------------<br>";
    }
	//Com loop usando for
	/*
	$resultados = $stmt->fetchAll(); // Transforma tudo em um array
	$total = count($resultados);    // Conta quantos registros existem
	for ($i = 0; $i < $total; $i++) {
		echo "ID: " . $resultados[$i]['id_produto'] . "<br>";
        echo "Descrição: " . $resultados[$i]['descricao'] . "<br>";
	}
	*/
?>
	
</body>
</html>