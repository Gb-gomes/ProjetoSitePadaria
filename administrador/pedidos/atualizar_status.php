<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Método não permitido']);
    exit();
}

$id = $_POST['id'] ?? 0;
$status = $_POST['status'] ?? '';

if ($id <= 0 || empty($status)) {
    echo json_encode(['success' => false, 'error' => 'Dados inválidos']);
    exit();
}

try {
    $sql = "UPDATE pedidos SET status = :status WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':status' => $status, ':id' => $id]);
    
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>