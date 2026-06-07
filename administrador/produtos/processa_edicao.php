<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

// Verificar se veio do formulário
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: listar.php");
    exit();
}

// Pegar os dados
$id = isset($_POST['id']) ? intval($_POST['id']) : 0;
$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$preco = isset($_POST['preco']) ? floatval($_POST['preco']) : 0;
$categoria = $_POST['categoria'] ?? '';
$estoque = isset($_POST['estoque']) ? intval($_POST['estoque']) : 0;

// Validar campos obrigatórios
if ($id <= 0 || empty($nome) || $preco <= 0) {
    header("Location: editar.php?id=$id&erro=" . urlencode("Campos obrigatórios não preenchidos corretamente"));
    exit();
}

try {
    // SQL de atualização
    $sql = "UPDATE produtos SET 
            nome = :nome, 
            descricao = :descricao, 
            preco = :preco, 
            categoria = :categoria, 
            estoque = :estoque 
            WHERE id = :id";
    
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':nome' => $nome,
        ':descricao' => $descricao,
        ':preco' => $preco,
        ':categoria' => $categoria,
        ':estoque' => $estoque,
        ':id' => $id
    ]);
    
    // Verificar se atualizou algum registro
    if ($stmt->rowCount() > 0) {
        header("Location: listar.php?sucesso=" . urlencode("Produto atualizado com sucesso!"));
    } else {
        header("Location: listar.php?erro=" . urlencode("Nenhuma alteração foi realizada"));
    }
    exit();
    
} catch (PDOException $e) {
    header("Location: editar.php?id=$id&erro=" . urlencode("Erro ao atualizar: " . $e->getMessage()));
    exit();
}
?>