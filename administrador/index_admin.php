<?php
require_once './../includes/verifica_admin.php';
require_once './includes/dashboard_data.php';
require_once './../includes/config_page.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Padaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .card-hover:hover {
            transform: translateY(-5px);
            transition: all 0.3s ease;
        }
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
        }
    </style>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">
    
    <?php include './../includes/sidebar.php'; ?>
    
    <div class="flex-1">
        
        <main class="p-6">
            
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-600">Bem-vindo ao painel administrativo</p>
            </div>
            
            <!-- Cards de estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div class="bg-white rounded-lg shadow p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Produtos</p>
                            <p class="text-3xl font-bold text-gray-800"><?= $total_produtos ?></p>
                            <p class="text-xs text-green-600 mt-2">
                                <i class="fas fa-box"></i> Cadastrados
                            </p>
                        </div>
                        <div class="bg-red-100 rounded-full p-3">
                            <i class="fas fa-cake text-2xl text-red-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Usuários</p>
                            <p class="text-3xl font-bold text-gray-800"><?= $total_usuarios ?></p>
                            <p class="text-xs text-blue-600 mt-2">
                                <i class="fas fa-users"></i> Cadastrados
                            </p>
                        </div>
                        <div class="bg-blue-100 rounded-full p-3">
                            <i class="fas fa-user text-2xl text-blue-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Pedidos</p>
                            <p class="text-3xl font-bold text-gray-800"><?= $total_pedidos ?></p>
                            <p class="text-xs text-purple-600 mt-2">
                                <i class="fas fa-shopping-cart"></i> Total realizados
                            </p>
                        </div>
                        <div class="bg-purple-100 rounded-full p-3">
                            <i class="fas fa-shopping-cart text-2xl text-purple-600"></i>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6 card-hover">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-gray-500 text-sm">Faturamento</p>
                            <p class="text-3xl font-bold text-green-600">R$ <?= number_format($total_vendido, 2, ',', '.') ?></p>
                            <p class="text-xs text-green-600 mt-2">
                                <i class="fas fa-dollar-sign"></i> Total vendido
                            </p>
                        </div>
                        <div class="bg-green-100 rounded-full p-3">
                            <i class="fas fa-chart-line text-2xl text-green-600"></i>
                        </div>
                    </div>
                </div>
                
            </div>
            
            <!-- Segunda linha de cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-500 text-sm">Ticket Médio</p>
                        <i class="fas fa-receipt text-gray-400"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800">R$ <?= number_format($ticket_medio, 2, ',', '.') ?></p>
                    <p class="text-xs text-gray-500 mt-2">Por pedido</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-500 text-sm">Clientes</p>
                        <i class="fas fa-user-friends text-gray-400"></i>
                    </div>
                    <p class="text-2xl font-bold text-gray-800"><?= $total_clientes ?></p>
                    <p class="text-xs text-gray-500 mt-2">Clientes únicos</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-500 text-sm">Pedidos Pendentes</p>
                        <i class="fas fa-clock text-yellow-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-yellow-600"><?= $status_counts['aguardando'] ?? 0 ?></p>
                    <p class="text-xs text-gray-500 mt-2">Aguardando pagamento</p>
                </div>
                
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-500 text-sm">Pedidos Confirmados</p>
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <p class="text-2xl font-bold text-green-600"><?= $status_counts['pago'] ?? 0 ?></p>
                    <p class="text-xs text-gray-500 mt-2">Pagamento confirmado</p>
                </div>
                
            </div>
            
            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                
                <!-- Gráfico de Vendas por Mês -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">
                        <i class="fas fa-chart-line text-blue-600 mr-2"></i>
                        Vendas por Mês
                    </h3>
                    <canvas id="vendasChart" height="250"></canvas>
                </div>
                
                <!-- Gráfico de Produtos Mais Vendidos -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">
                        <i class="fas fa-chart-bar text-green-600 mr-2"></i>
                        Produtos Mais Vendidos
                    </h3>
                    <canvas id="topProdutosChart" height="250"></canvas>
                </div>
                
            </div>
            
            <!-- Status dos Pedidos -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                
                <!-- Gráfico de Status dos Pedidos -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">
                        <i class="fas fa-chart-pie text-purple-600 mr-2"></i>
                        Status dos Pedidos
                    </h3>
                    <canvas id="statusChart" height="250"></canvas>
                </div>
                
                <!-- Produtos com Estoque Baixo -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="font-bold text-lg mb-4">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-2"></i>
                        Produtos com Estoque Baixo
                    </h3>
                    <?php if (empty($produtos_estoque_baixo)): ?>
                        <p class="text-green-600 text-center py-8">
                            <i class="fas fa-check-circle"></i> Todos os produtos têm estoque adequado!
                        </p>
                    <?php else: ?>
                        <div class="space-y-3">
                            <?php foreach ($produtos_estoque_baixo as $produto): ?>
                                <div class="flex justify-between items-center border-b pb-2">
                                    <span class="font-medium"><?= htmlspecialchars($produto['nome']) ?></span>
                                    <span class="text-red-600 font-bold">
                                        <?= $produto['estoque'] ?> unidade(s)
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
            </div>
            
            <!-- Pedidos Recentes -->
            <div class="bg-white rounded-lg shadow mb-8">
                <div class="p-6 border-b">
                    <h3 class="font-bold text-lg">
                        <i class="fas fa-history text-gray-600 mr-2"></i>
                        Pedidos Recentes
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="p-3 text-left">Pedido</th>
                                <th class="p-3 text-left">Data</th>
                                <th class="p-3 text-left">Total</th>
                                <th class="p-3 text-left">Pagamento</th>
                                <th class="p-3 text-left">Status</th>
                                <th class="p-3 text-left">Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($pedidos_recentes)): ?>
                                <tr>
                                    <td colspan="6" class="p-3 text-center text-gray-500">
                                        Nenhum pedido encontrado
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($pedidos_recentes as $pedido): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-medium"><?= $pedido['numero_pedido'] ?></td>
                                        <td class="p-3"><?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></td>
                                        <td class="p-3 font-semibold text-green-600">
                                            R$ <?= number_format($pedido['total'], 2, ',', '.') ?>
                                        </td>
                                        <td class="p-3">
                                            <?php
                                            $status_class = $pedido['pagamento_status'] == 'pago' ? 'bg-green-100 text-green-800' : 
                                                           ($pedido['pagamento_status'] == 'aguardando' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800');
                                            $status_text = $pedido['pagamento_status'] == 'pago' ? 'Pago' :
                                                           ($pedido['pagamento_status'] == 'aguardando' ? 'Aguardando' : 'Cancelado');
                                            ?>
                                            <span class="px-2 py-1 rounded text-xs <?= $status_class ?>">
                                                <?= $status_text ?>
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <?php
                                            $pedido_status_class = $pedido['status'] == 'entregue' ? 'bg-green-100 text-green-800' :
                                                                   ($pedido['status'] == 'cancelado' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800');
                                            ?>
                                            <span class="px-2 py-1 rounded text-xs <?= $pedido_status_class ?>">
                                                <?= ucfirst($pedido['status']) ?>
                                            </span>
                                        </td>
                                        <td class="p-3">
                                            <a href="pedidos/detalhes.php?id=<?= $pedido['id'] ?>" 
                                               class="text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
        </main>
        
    </div>
    
</div>

<script>
// Gráfico de Vendas por Mês
const ctxVendas = document.getElementById('vendasChart').getContext('2d');
new Chart(ctxVendas, {
    type: 'line',
    data: {
        labels: <?= json_encode($meses_labels) ?>,
        datasets: [{
            label: 'Vendas (R$)',
            data: <?= json_encode($vendas_valores) ?>,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'R$ ' + context.raw.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                    }
                }
            }
        }
    }
});

// Gráfico de Produtos Mais Vendidos
const ctxTopProdutos = document.getElementById('topProdutosChart').getContext('2d');
new Chart(ctxTopProdutos, {
    type: 'bar',
    data: {
        labels: <?= json_encode($top_produtos_labels) ?>,
        datasets: [{
            label: 'Quantidade Vendida',
            data: <?= json_encode($top_produtos_valores) ?>,
            backgroundColor: 'rgba(34, 197, 94, 0.7)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'top',
            }
        }
    }
});

// Gráfico de Status dos Pedidos
const ctxStatus = document.getElementById('statusChart').getContext('2d');
new Chart(ctxStatus, {
    type: 'doughnut',
    data: {
        labels: ['Aguardando Pagamento', 'Pagos', 'Cancelados'],
        datasets: [{
            data: [
                <?= $status_counts['aguardando'] ?? 0 ?>,
                <?= $status_counts['pago'] ?? 0 ?>,
                <?= $status_counts['cancelado'] ?? 0 ?>
            ],
            backgroundColor: [
                'rgb(234, 179, 8)',
                'rgb(34, 197, 94)',
                'rgb(239, 68, 68)'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom',
            }
        }
    }
});
</script>

</body>
</html>