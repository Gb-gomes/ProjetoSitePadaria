<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: listar.php?erro=" . urlencode("ID do produto inválido"));
    exit();
}

try {
    // Buscar nome do produto antes de excluir (para mensagem)
    $sql_check = "SELECT nome FROM produtos WHERE id = :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':id' => $id]);
    $produto = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$produto) {
        header("Location: listar.php?erro=" . urlencode("Produto não encontrado"));
        exit();
    }
    
    // Executar exclusão
    $sql_delete = "DELETE FROM produtos WHERE id = :id";
    $stmt_delete = $pdo->prepare($sql_delete);
    $stmt_delete->execute([':id' => $id]);
    
    header("Location: listar.php?sucesso=" . urlencode("Produto '{$produto['nome']}' excluído com sucesso!"));
    exit();
    
} catch (PDOException $e) {
    header("Location: listar.php?erro=" . urlencode("Erro ao excluir: " . $e->getMessage()));
    exit();
}
?>