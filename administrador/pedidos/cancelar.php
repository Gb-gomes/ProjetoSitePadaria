<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

$id = $_GET['id'] ?? 0;

if ($id <= 0) {
    header("Location: listar.php?erro=" . urlencode("ID do pedido inválido"));
    exit();
}

// Buscar dados do pedido
try {
    $sql = "SELECT * FROM pedidos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    
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
    
} catch (PDOException $e) {
    header("Location: listar.php?erro=" . urlencode("Erro ao buscar pedido: " . $e->getMessage()));
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancelar Pedido - Admin Padaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">
    
    <?php include './../../includes/sidebar.php'; ?>
    
    <!-- Conteúdo principal -->
    <div class="flex-1 p-6">
        <div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-6">
            
            <div class="text-center mb-6">
                <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-exclamation-triangle text-4xl text-red-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800">Cancelar Pedido</h1>
                <p class="text-gray-600 mt-2">Você tem certeza que deseja cancelar este pedido?</p>
            </div>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <h2 class="font-semibold text-lg mb-3">Informações do Pedido</h2>
                
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div>
                        <p class="text-gray-600">Número do pedido:</p>
                        <p class="font-semibold"><?= $pedido['numero_pedido'] ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600">Data:</p>
                        <p class="font-semibold"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total:</p>
                        <p class="font-semibold text-red-600">R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>
                    </div>
                    <div>
                        <p class="text-gray-600">Status atual:</p>
                        <p class="font-semibold">
                            <?php
                            $status_text = [
                                'pendente' => 'Pendente',
                                'preparando' => 'Preparando',
                                'saiu_entrega' => 'Saiu para entrega',
                                'entregue' => 'Entregue'
                            ];
                            echo $status_text[$pedido['status']] ?? $pedido['status'];
                            ?>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-yellow-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Atenção!</strong> Esta ação não pode ser desfeita. O pedido será cancelado e o cliente será notificado.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex gap-4 justify-center">
                <a href="processa_cancelamento.php?id=<?= $pedido['id'] ?>" 
                   class="bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition duration-300">
                    <i class="fas fa-check-circle"></i> Sim, cancelar pedido
                </a>
                <a href="listar.php" 
                   class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300">
                    <i class="fas fa-arrow-left"></i> Não, voltar
                </a>
            </div>
            
        </div>
    </div>
    
</div>

</body>
</html>