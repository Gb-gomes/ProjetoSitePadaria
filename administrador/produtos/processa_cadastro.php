<?php
require_once './../../includes/conecta.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastra.php");
    exit();
}


if (empty($_POST['nome']) || empty($_POST['preco'])) {
    header("Location: cadastra.php?erro=Campos obrigatórios não preenchidos");
    exit();
}

try {
   
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'] ?? '';
    $preco = floatval($_POST['preco']);
    $categoria = $_POST['categoria'] ?? '';
    $estoque = intval($_POST['estoque'] ?? 0);

    $sql = "INSERT INTO produtos (nome, descricao, preco, categoria, estoque) 
            VALUES (:nome, :descricao, :preco, :categoria, :estoque)";
    

    $stmt = $pdo->prepare($sql);
   
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':preco' => $preco,
        ':categoria' => $categoria,
        ':estoque' => $estoque
    ]);
    
    
    header("Location: listar.php?sucesso=Produto cadastrado com sucesso");
    exit();
    
} catch (PDOException $e) {
    header("Location: cadastra.php?erro=Erro ao cadastrar: " . urlencode($e->getMessage()));
    exit();
}
?>