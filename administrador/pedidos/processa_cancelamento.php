<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

$id = $_GET['id'] ?? 0;

if ($id <= 0) {
    header("Location: listar.php?erro=" . urlencode("ID do pedido inválido"));
    exit();
}

try {
    // Verificar se o pedido existe
    $sql_check = "SELECT id, numero_pedido, pagamento_status, status FROM pedidos WHERE id = :id";
    $stmt_check = $pdo->prepare($sql_check);
    $stmt_check->execute([':id' => $id]);
    $pedido = $stmt_check->fetch(PDO::FETCH_ASSOC);
    
    if (!$pedido) {
        header("Location: listar.php?erro=" . urlencode("Pedido não encontrado"));
        exit();
    }
    
    // Verificar se já está cancelado
    if ($pedido['pagamento_status'] == 'cancelado') {
        header("Location: listar.php?erro=" . urlencode("Este pedido já está cancelado"));
        exit();
    }
    
    // Verificar se já foi entregue
    if ($pedido['status'] == 'entregue') {
        header("Location: listar.php?erro=" . urlencode("Pedidos entregues não podem ser cancelados"));
        exit();
    }
    
    // Cancelar o pedido
    $sql = "UPDATE pedidos SET pagamento_status = 'cancelado', status = 'cancelado' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    header("Location: listar.php?sucesso=" . urlencode("Pedido #{$pedido['numero_pedido']} cancelado com sucesso!"));
    
} catch (PDOException $e) {
    header("Location: listar.php?erro=" . urlencode("Erro ao cancelar pedido: " . $e->getMessage()));
}
exit();
?>