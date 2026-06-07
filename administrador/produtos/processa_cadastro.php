<?php
require_once './../../includes/conecta.php';

// Verificar se os dados vieram via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cadastra.php");
    exit();
}

// Validar campos obrigatórios
if (empty($_POST['nome']) || empty($_POST['preco'])) {
    header("Location: cadastra.php?erro=Campos obrigatórios não preenchidos");
    exit();
}

try {
    // Pegar os dados do formulário
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'] ?? '';
    $preco = floatval($_POST['preco']);
    $categoria = $_POST['categoria'] ?? '';
    $estoque = intval($_POST['estoque'] ?? 0);
    
    // SQL de inserção
    $sql = "INSERT INTO produtos (nome, descricao, preco, categoria, estoque) 
            VALUES (:nome, :descricao, :preco, :categoria, :estoque)";
    
    // Preparar a consulta
    $stmt = $pdo->prepare($sql);
    
    // Executar com os valores
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':preco' => $preco,
        ':categoria' => $categoria,
        ':estoque' => $estoque
    ]);
    
    // Redirecionar com mensagem de sucesso
    header("Location: listar.php?sucesso=Produto cadastrado com sucesso");
    exit();
    
} catch (PDOException $e) {
    // Em caso de erro, volta para o formulário com a mensagem
    header("Location: cadastra.php?erro=Erro ao cadastrar: " . urlencode($e->getMessage()));
    exit();
}
?>