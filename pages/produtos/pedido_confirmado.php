<?php
session_start();
require_once '../../includes/conecta.php';

$pedido_id = $_GET['pedido'] ?? 0;

if ($pedido_id <= 0) {
    header("Location: produtos.php");
    exit();
}

// Buscar dados do pedido
$sql = "SELECT * FROM pedidos WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $pedido_id]);
$pedido = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pedido) {
    header("Location: produtos.php");
    exit();
}

// Buscar itens do pedido
$sql_itens = "SELECT * FROM pedido_itens WHERE pedido_id = :pedido_id";
$stmt_itens = $pdo->prepare($sql_itens);
$stmt_itens->execute([':pedido_id' => $pedido_id]);
$itens = $stmt_itens->fetchAll(PDO::FETCH_ASSOC);

// Limpar carrinho da sessão
unset($_SESSION['carrinho']);
unset($_SESSION['total']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pedido Confirmado - Padaria Artesanal Delícias</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-8 max-w-4xl">
    <div class="bg-white rounded-lg shadow-lg p-8 text-center">
        <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <i class="fas fa-check-circle text-5xl text-green-600"></i>
        </div>
        
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Pedido Confirmado!</h1>
        <p class="text-gray-600 mb-6">Obrigado por comprar conosco!</p>
        
        <div class="bg-gray-50 rounded-lg p-6 text-left mb-6">
            <h2 class="font-bold text-lg mb-4">Detalhes do Pedido</h2>
            <p class="mb-2"><strong>Número do pedido:</strong> <?= $pedido['numero_pedido'] ?></p>
            <p class="mb-2"><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['data_pedido'])) ?></p>
            <p class="mb-2"><strong>Status:</strong> 
                <span class="text-green-600 font-semibold">Pagamento confirmado - Em preparação</span>
            </p>
            
            <div class="border-t my-4 pt-4">
                <h3 class="font-semibold mb-2">Itens do pedido:</h3>
                <?php foreach ($itens as $item): ?>
                    <div class="flex justify-between text-sm mb-2">
                        <span><?= $item['quantidade'] ?>x <?= htmlspecialchars($item['produto_nome']) ?></span>
                        <span>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="border-t pt-4">
                <div class="flex justify-between mb-2">
                    <span>Subtotal:</span>
                    <span>R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between mb-2">
                    <span>Taxa de entrega:</span>
                    <span>R$ <?= number_format($pedido['taxa_entrega'], 2, ',', '.') ?></span>
                </div>
                <div class="flex justify-between text-xl font-bold mt-2 pt-2 border-t">
                    <span>Total:</span>
                    <span class="text-red-600">R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span>
                </div>
            </div>
            
            <div class="border-t mt-4 pt-4">
                <h3 class="font-semibold mb-2">Endereço de entrega:</h3>
                <p class="text-sm"><?= nl2br(htmlspecialchars($pedido['endereco_entrega'])) ?></p>
                <p class="text-sm"><?= htmlspecialchars($pedido['cidade']) ?> - CEP: <?= htmlspecialchars($pedido['cep']) ?></p>
            </div>
        </div>
        
        <a href="produtos.php" class="inline-block bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700">
            <i class="fas fa-store"></i> Continuar comprando
        </a>
    </div>
</div>

</body>
</html>