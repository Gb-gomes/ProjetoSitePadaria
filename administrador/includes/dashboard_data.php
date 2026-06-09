<?php
require_once './../includes/conecta.php';

// Total de produtos
$sql_produtos = "SELECT COUNT(*) as total FROM produtos";
$stmt_produtos = $pdo->query($sql_produtos);
$total_produtos = $stmt_produtos->fetch(PDO::FETCH_ASSOC)['total'];

// Total de usuários
$sql_usuarios = "SELECT COUNT(*) as total FROM usuarios";
$stmt_usuarios = $pdo->query($sql_usuarios);
$total_usuarios = $stmt_usuarios->fetch(PDO::FETCH_ASSOC)['total'];

// Total de pedidos
$sql_pedidos = "SELECT COUNT(*) as total FROM pedidos";
$stmt_pedidos = $pdo->query($sql_pedidos);
$total_pedidos = $stmt_pedidos->fetch(PDO::FETCH_ASSOC)['total'];

// Pedidos por status
$sql_status = "SELECT 
                SUM(CASE WHEN pagamento_status = 'aguardando' THEN 1 ELSE 0 END) as aguardando,
                SUM(CASE WHEN pagamento_status = 'pago' THEN 1 ELSE 0 END) as pago,
                SUM(CASE WHEN pagamento_status = 'cancelado' THEN 1 ELSE 0 END) as cancelado
              FROM pedidos";
$stmt_status = $pdo->query($sql_status);
$status_counts = $stmt_status->fetch(PDO::FETCH_ASSOC);

// Total vendido (valor)
$sql_vendas = "SELECT COALESCE(SUM(total), 0) as total_vendido FROM pedidos WHERE pagamento_status = 'pago'";
$stmt_vendas = $pdo->query($sql_vendas);
$total_vendido = $stmt_vendas->fetch(PDO::FETCH_ASSOC)['total_vendido'];

// Produtos mais vendidos
$sql_top_produtos = "SELECT 
                        pi.produto_nome,
                        SUM(pi.quantidade) as total_vendido,
                        SUM(pi.subtotal) as valor_total
                    FROM pedido_itens pi
                    INNER JOIN pedidos p ON pi.pedido_id = p.id
                    WHERE p.pagamento_status = 'pago'
                    GROUP BY pi.produto_nome
                    ORDER BY total_vendido DESC
                    LIMIT 5";
$stmt_top_produtos = $pdo->query($sql_top_produtos);
$top_produtos = $stmt_top_produtos->fetchAll(PDO::FETCH_ASSOC);

// Vendas por mês (últimos 6 meses)
$sql_vendas_mes = "SELECT 
                    DATE_FORMAT(data_pedido, '%Y-%m') as mes,
                    COUNT(*) as total_pedidos,
                    COALESCE(SUM(total), 0) as valor_total
                  FROM pedidos
                  WHERE pagamento_status = 'pago'
                  AND data_pedido >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                  GROUP BY DATE_FORMAT(data_pedido, '%Y-%m')
                  ORDER BY mes ASC";
$stmt_vendas_mes = $pdo->query($sql_vendas_mes);
$vendas_por_mes = $stmt_vendas_mes->fetchAll(PDO::FETCH_ASSOC);

// Pedidos recentes
$sql_recentes = "SELECT 
                    id, numero_pedido, total, pagamento_status, status, data_pedido
                  FROM pedidos
                  ORDER BY data_pedido DESC
                  LIMIT 5";
$stmt_recentes = $pdo->query($sql_recentes);
$pedidos_recentes = $stmt_recentes->fetchAll(PDO::FETCH_ASSOC);

// Produtos com estoque baixo (menos de 10 unidades)
$sql_estoque_baixo = "SELECT nome, estoque FROM produtos WHERE estoque < 10 ORDER BY estoque ASC LIMIT 5";
$stmt_estoque_baixo = $pdo->query($sql_estoque_baixo);
$produtos_estoque_baixo = $stmt_estoque_baixo->fetchAll(PDO::FETCH_ASSOC);

// Ticket médio
$sql_ticket_medio = "SELECT COALESCE(AVG(total), 0) as ticket_medio FROM pedidos WHERE pagamento_status = 'pago'";
$stmt_ticket_medio = $pdo->query($sql_ticket_medio);
$ticket_medio = $stmt_ticket_medio->fetch(PDO::FETCH_ASSOC)['ticket_medio'];

// Total de clientes únicos
$sql_clientes = "SELECT COUNT(DISTINCT usuario_id) as total_clientes FROM pedidos WHERE usuario_id IS NOT NULL";
$stmt_clientes = $pdo->query($sql_clientes);
$total_clientes = $stmt_clientes->fetch(PDO::FETCH_ASSOC)['total_clientes'];

// Formatando dados para os gráficos
$meses_labels = [];
$vendas_valores = [];
foreach ($vendas_por_mes as $venda) {
    $meses_labels[] = date('M/Y', strtotime($venda['mes'] . '-01'));
    $vendas_valores[] = $venda['valor_total'];
}

$top_produtos_labels = [];
$top_produtos_valores = [];
foreach ($top_produtos as $produto) {
    $top_produtos_labels[] = $produto['produto_nome'];
    $top_produtos_valores[] = $produto['total_vendido'];
}
?>