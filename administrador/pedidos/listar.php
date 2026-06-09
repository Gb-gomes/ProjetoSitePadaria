<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

// Filtrar por status
$status_filtro = $_GET['status'] ?? 'todos';
$busca = $_GET['busca'] ?? '';

// Montar query
$sql = "SELECT p.*, 
        (SELECT COUNT(*) FROM pedido_itens WHERE pedido_id = p.id) as total_itens
        FROM pedidos p 
        WHERE 1=1";

$params = [];

if ($status_filtro != 'todos') {
    $sql .= " AND p.pagamento_status = :status";
    $params[':status'] = $status_filtro;
}

if (!empty($busca)) {
    $sql .= " AND (p.numero_pedido LIKE :busca OR p.cliente_nome LIKE :busca)";
    $params[':busca'] = "%$busca%";
}

$sql .= " ORDER BY p.id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estatísticas
$stats_sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN pagamento_status = 'aguardando' THEN 1 ELSE 0 END) as aguardando,
                SUM(CASE WHEN pagamento_status = 'pago' THEN 1 ELSE 0 END) as pago,
                SUM(CASE WHEN pagamento_status = 'cancelado' THEN 1 ELSE 0 END) as cancelado,
                SUM(CASE WHEN status = 'entregue' THEN 1 ELSE 0 END) as entregue
              FROM pedidos";
$stats_stmt = $pdo->query($stats_sql);
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Pedidos - Admin Padaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">
    
    <?php include './../../includes/sidebar.php'; ?>
    
    <!-- Conteúdo principal -->
    <div class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-shopping-cart"></i> Gerenciar Pedidos
                </h1>
            </div>
            
            <!-- Cards de estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Total de Pedidos</p>
                            <p class="text-2xl font-bold"><?= $stats['total'] ?? 0 ?></p>
                        </div>
                        <i class="fas fa-chart-line text-3xl text-blue-500"></i>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Aguardando Pagamento</p>
                            <p class="text-2xl font-bold text-yellow-600"><?= $stats['aguardando'] ?? 0 ?></p>
                        </div>
                        <i class="fas fa-clock text-3xl text-yellow-500"></i>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Pagamento Confirmado</p>
                            <p class="text-2xl font-bold text-green-600"><?= $stats['pago'] ?? 0 ?></p>
                        </div>
                        <i class="fas fa-check-circle text-3xl text-green-500"></i>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Entregues</p>
                            <p class="text-2xl font-bold text-blue-600"><?= $stats['entregue'] ?? 0 ?></p>
                        </div>
                        <i class="fas fa-truck text-3xl text-blue-500"></i>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Cancelados</p>
                            <p class="text-2xl font-bold text-red-600"><?= $stats['cancelado'] ?? 0 ?></p>
                        </div>
                        <i class="fas fa-times-circle text-3xl text-red-500"></i>
                    </div>
                </div>
            </div>
            
            <!-- Filtros e busca -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="flex flex-wrap gap-4 items-center justify-between">
                    <div class="flex gap-2">
                        <a href="?status=todos" 
                           class="px-4 py-2 rounded <?= $status_filtro == 'todos' ? 'bg-red-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            Todos
                        </a>
                        <a href="?status=aguardando" 
                           class="px-4 py-2 rounded <?= $status_filtro == 'aguardando' ? 'bg-yellow-500 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            Aguardando
                        </a>
                        <a href="?status=pago" 
                           class="px-4 py-2 rounded <?= $status_filtro == 'pago' ? 'bg-green-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            Pago
                        </a>
                        <a href="?status=entregue" 
                           class="px-4 py-2 rounded <?= $status_filtro == 'entregue' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            Entregue
                        </a>
                        <a href="?status=cancelado" 
                           class="px-4 py-2 rounded <?= $status_filtro == 'cancelado' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            Cancelado
                        </a>
                    </div>
                    
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="status" value="<?= $status_filtro ?>">
                        <input type="text" name="busca" value="<?= htmlspecialchars($busca) ?>" 
                               placeholder="Buscar por pedido ou cliente..."
                               class="border border-gray-300 rounded-lg px-4 py-2 w-64">
                        <button type="submit" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Tabela de pedidos -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="w-full">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="p-3 text-left">Pedido</th>
                            <th class="p-3 text-left">Cliente</th>
                            <th class="p-3 text-left">Data</th>
                            <th class="p-3 text-left">Total</th>
                            <th class="p-3 text-left">Itens</th>
                            <th class="p-3 text-left">Status Pagamento</th>
                            <th class="p-3 text-left">Status Pedido</th>
                            <th class="p-3 text-left">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($pedidos)): ?>
                            <tr>
                                <td colspan="8" class="p-3 text-center text-gray-500">
                                    Nenhum pedido encontrado
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($pedidos as $pedido): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-3 font-semibold"><?= $pedido['numero_pedido'] ?></td>
                                    <td class="p-3">
                                        <?= htmlspecialchars($pedido['cliente_nome'] ?? 'Cliente não identificado') ?>
                                        <br>
                                        <small class="text-gray-500"><?= htmlspecialchars($pedido['telefone'] ?? '') ?></small>
                                    </td>
                                    <td class="p-3"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                                    <td class="p-3 font-bold text-green-600">R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                                    <td class="p-3 text-center"><?= $pedido['total_itens'] ?></td>
                                    <td class="p-3">
                                        <?php
                                        $status_colors = [
                                            'aguardando' => 'bg-yellow-100 text-yellow-800',
                                            'pago' => 'bg-green-100 text-green-800',
                                            'cancelado' => 'bg-red-100 text-red-800'
                                        ];
                                        $status_text = [
                                            'aguardando' => 'Aguardando',
                                            'pago' => 'Pago',
                                            'cancelado' => 'Cancelado'
                                        ];
                                        $status_class = $status_colors[$pedido['pagamento_status']] ?? 'bg-gray-100 text-gray-800';
                                        ?>
                                        <span class="px-2 py-1 rounded text-xs <?= $status_class ?>">
                                            <?= $status_text[$pedido['pagamento_status']] ?? $pedido['pagamento_status'] ?>
                                        </span>
                                    </td>
                                    <td class="p-3">
                                        <select onchange="alterarStatus(<?= $pedido['id'] ?>, this.value)" 
                                                class="border rounded px-2 py-1 text-sm">
                                            <option value="pendente" <?= $pedido['status'] == 'pendente' ? 'selected' : '' ?>>Pendente</option>
                                            <option value="preparando" <?= $pedido['status'] == 'preparando' ? 'selected' : '' ?>>Preparando</option>
                                            <option value="saiu_entrega" <?= $pedido['status'] == 'saiu_entrega' ? 'selected' : '' ?>>Saiu para entrega</option>
                                            <option value="entregue" <?= $pedido['status'] == 'entregue' ? 'selected' : '' ?>>Entregue</option>
                                            <option value="cancelado" <?= $pedido['status'] == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                        </select>
                                    </td>
                                    <td class="p-3">
                                        <div class="flex gap-2">
                                            <a href="detalhes.php?id=<?= $pedido['id'] ?>" 
                                               class="text-blue-600 hover:text-blue-800" title="Ver detalhes">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if ($pedido['pagamento_status'] == 'aguardando'): ?>
                                                <a href="confirmar_pagamento.php?id=<?= $pedido['id'] ?>" 
                                                   onclick="return confirm('Confirmar pagamento deste pedido?')"
                                                   class="text-green-600 hover:text-green-800" title="Confirmar pagamento">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($pedido['pagamento_status'] != 'cancelado'): ?>
                                                <a href="cancelar.php?id=<?= $pedido['id'] ?>" 
                                                   onclick="return confirm('Cancelar este pedido?')"
                                                   class="text-red-600 hover:text-red-800" title="Cancelar">
                                                    <i class="fas fa-times-circle"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

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