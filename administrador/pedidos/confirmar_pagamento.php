<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

$id = $_GET['id'] ?? 0;

if ($id <= 0) {
    header("Location: listar.php?erro=ID inválido");
    exit();
}

try {
    $sql = "UPDATE pedidos SET pagamento_status = 'pago', status = 'preparando' WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    
    header("Location: listar.php?sucesso=Pagamento confirmado com sucesso!");
} catch (PDOException $e) {
    header("Location: listar.php?erro=" . urlencode($e->getMessage()));
}
exit();
?>