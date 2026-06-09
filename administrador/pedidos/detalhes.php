<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

$id = $_GET['id'] ?? 0;

if ($id <= 0) {
    header("Location: listar.php");
    exit();
}

// Buscar dados do pedido
$sql = "SELECT * FROM pedidos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header("Location: listar.php");
    exit();
}

// Buscar itens do pedido
$sql_itens = "SELECT * FROM pedido_itens WHERE pedido_id = :pedido_id";
$stmt_itens = $pdo->prepare($sql_itens);
$stmt_itens->execute([':pedido_id' => $id]);
$itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Pedido <?= $pedido['numero_pedido'] ?> - Admin Padaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">
    
    <?php include './../../includes/sidebar.php'; ?>
    
    <div class="flex-1 p-6">
        <div class="max-w-5xl mx-auto">
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-shopping-cart"></i> Pedido #<?= $pedido['numero_pedido'] ?>
                </h1>
                <a href="listar.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                <!-- Informações do pedido -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Itens do Pedido</h2>
                        
                        <table class="w-full">
                            <thead class="border-b">
                                <tr>
                                    <th class="text-left p-2">Produto</th>
                                    <th class="text-center p-2">Qtd</th>
                                    <th class="text-right p-2">Preço</th>
                                    <th class="text-right p-2">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($itens as $item): ?>
                                <tr class="border-b">
                                    <td class="p-2"><?= htmlspecialchars($item['produto_nome']) ?></td>
                                    <td class="text-center p-2"><?= $item['quantidade'] ?></td>
                                    <td class="text-right p-2">R$ <?= number_format($item['preco_unitario'], 2, ',', '.') ?></td>
                                    <td class="text-right p-2 font-semibold">R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="border-t pt-4">
                                <tr>
                                    <td colspan="3" class="text-right p-2 font-bold">Subtotal:</td>
                                    <td class="text-right p-2">R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="text-right p-2 font-bold">Taxa de entrega:</td>
                                    <td class="text-right p-2">R$ <?= number_format($pedido['taxa_entrega'], 2, ',', '.') ?></td>
                                </tr>
                                <tr class="text-lg">
                                    <td colspan="3" class="text-right p-2 font-bold text-red-600">TOTAL:</td>
                                    <td class="text-right p-2 font-bold text-red-600">R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
                
                <!-- Informações do cliente e entrega -->
                <div>
                    <div class="bg-white rounded-lg shadow p-6 mb-6">
                        <h2 class="text-xl font-bold mb-4">Status</h2>
                        
                        <div class="mb-4">
                            <label class="block text-gray-600 mb-1">Status Pagamento:</label>
                            <?php
                            $status_class = $pedido['pagamento_status'] == 'pago' ? 'text-green-600' : 'text-yellow-600';
                            ?>
                            <p class="font-semibold <?= $status_class ?>">
                                <?= ucfirst($pedido['pagamento_status']) ?>
                            </p>
                        </div>
                        
                        <div class="mb-4">
                            <label class="block text-gray-600 mb-1">Status Pedido:</label>
                            <select onchange="alterarStatus(<?= $pedido['id'] ?>, this.value)" 
                                    class="border rounded px-3 py-2 w-full">
                                <option value="pendente" <?= $pedido['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                <option value="preparando" <?= $pedido['status'] == 'preparando' ? 'selected' : '' ?>>Preparando</option>
                                <option value="saiu_entrega" <?= $pedido['status'] == 'saiu_entrega' ? 'selected' : '' ?>>Saiu para entrega</option>
                                <option value="entregue" <?= $pedido['status'] == 'entregue' ? 'selected' : '' ?>>Entregue</option>
                                <option value="cancelado" <?= $pedido['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-xl font-bold mb-4">Endereço de Entrega</h2>
                        <p class="text-gray-700"><?= nl2br(htmlspecialchars($pedido['endereco_entrega'])) ?></p>
                        <p class="text-gray-600 mt-2"><?= htmlspecialchars($pedido['bairro']) ?></p>
                        <p class="text-gray-600"><?= htmlspecialchars($pedido['cidade']) ?> - CEP: <?= htmlspecialchars($pedido['cep']) ?></p>
                        <?php if ($pedido['complemento']): ?>
                            <p class="text-gray-600">Complemento: <?= htmlspecialchars($pedido['complemento']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function alterarStatus(pedidoId, novoStatus) {
    fetch('atualizar_status.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'id=' + pedidoId + '&status=' + novoStatus
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Status atualizado!',
                text: 'O status do pedido foi atualizado com sucesso.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Erro!',
                text: data.error || 'Erro ao atualizar status.'
            });
        }
    });
}
</script>

</body>
</html>