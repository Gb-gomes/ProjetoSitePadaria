<?php
session_start();
require_once '../../includes/conecta.php';

// Verificar se o carrinho existe
if (!isset($_SESSION['carrinho']) || empty($_SESSION['carrinho'])) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrinho'])) {
        $_SESSION['carrinho'] = json_decode($_POST['carrinho'], true);
        $_SESSION['total'] = $_POST['total'] ?? 0;
    } else {
        header("Location: produtos.php");
        exit();
    }
}

$carrinho = $_SESSION['carrinho'];
$total = floatval($_SESSION['total']);

// Processar o pedido
$erro = '';
$sucesso = false;
$qr_code = '';
$pedido_id = '';
$numero_pedido = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['finalizar'])) {
    // Validar endereço
    if (empty($_POST['endereco']) || empty($_POST['cidade']) || empty($_POST['cep'])) {
        $erro = "Por favor, preencha todos os campos de endereço.";
    } else {
        try {
            // Gerar número do pedido
            $numero_pedido = 'PED-' . date('Ymd') . '-' . rand(1000, 9999);
            
            // Calcular taxa de entrega
            $taxa_entrega = 5.00;
            $total_com_entrega = $total + $taxa_entrega;
            
            // Inserir pedido
            $sql = "INSERT INTO pedidos (usuario_id, numero_pedido, subtotal, taxa_entrega, total, 
                    endereco_entrega, bairro, cidade, cep, complemento, forma_pagamento, pagamento_status) 
                    VALUES (:usuario_id, :numero_pedido, :subtotal, :taxa_entrega, :total, 
                    :endereco, :bairro, :cidade, :cep, :complemento, :forma_pagamento, :pagamento_status)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':usuario_id' => $_SESSION['usuario_id'] ?? null,
                ':numero_pedido' => $numero_pedido,
                ':subtotal' => $total,
                ':taxa_entrega' => $taxa_entrega,
                ':total' => $total_com_entrega,
                ':endereco' => $_POST['endereco'],
                ':bairro' => $_POST['bairro'],
                ':cidade' => $_POST['cidade'],
                ':cep' => $_POST['cep'],
                ':complemento' => $_POST['complemento'] ?? '',
                ':forma_pagamento' => 'pix',
                ':pagamento_status' => 'aguardando'
            ]);
            
            $pedido_id = $pdo->lastInsertId();
            
            // Inserir itens do pedido
            $sql_item = "INSERT INTO pedido_itens (pedido_id, produto_id, produto_nome, quantidade, preco_unitario, subtotal) 
                         VALUES (:pedido_id, :produto_id, :produto_nome, :quantidade, :preco_unitario, :subtotal)";
            
            $stmt_item = $pdo->prepare($sql_item);
            
            foreach ($carrinho as $item) {
                $stmt_item->execute([
                    ':pedido_id' => $pedido_id,
                    ':produto_id' => $item['id'],
                    ':produto_nome' => $item['nome'],
                    ':quantidade' => $item['quantidade'],
                    ':preco_unitario' => $item['preco'],
                    ':subtotal' => $item['total']
                ]);
            }
            
            // Gerar QR Code PIX fictício (simplificado)
            $chave_pix = "padaria@delicias.com.br";
            $nome_recebedor = "Padaria Artesanal Delicias";
            $cidade = "SAO PAULO";
            $valor = number_format($total_com_entrega, 2, '.', '');
            
            // Criar payload PIX simplificado (sem CRC16 para evitar erro)
            $qr_code = "00020126580014BR.GOV.BCB.PIX0136" . $chave_pix . 
                       "5204000053039865405" . $valor . "5802BR5909" . $nome_recebedor . 
                       "6008" . $cidade . "62070503***6304";
            
            // Atualizar pedido com QR code
            $sql_update = "UPDATE pedidos SET qr_code = :qr_code WHERE id = :id";
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->execute([
                ':qr_code' => $qr_code,
                ':id' => $pedido_id
            ]);
            
            $_SESSION['pedido_atual'] = [
                'id' => $pedido_id,
                'numero' => $numero_pedido,
                'total' => $total_com_entrega,
                'qr_code' => $qr_code
            ];
            
            $sucesso = true;
            
        } catch (PDOException $e) {
            $erro = "Erro ao processar pedido: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Padaria Artesanal Delícias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">

<div class="container mx-auto px-4 py-8 max-w-6xl">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Finalizar Pedido</h1>
        <a href="produtos.php" class="text-red-600 hover:text-red-800">
            <i class="fas fa-arrow-left"></i> Voltar para loja
        </a>
    </div>
    
    <?php if ($erro): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= htmlspecialchars($erro) ?>
        </div>
    <?php endif; ?>
    
    <?php if ($sucesso): ?>
        <!-- Página de pagamento PIX -->
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="mb-6">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-qrcode text-4xl text-green-600"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Pedido Realizado!</h2>
                <p class="text-gray-600">Número do pedido: <strong><?= $_SESSION['pedido_atual']['numero'] ?></strong></p>
            </div>
            
            <div class="border-t border-b py-6 my-6">
                <h3 class="text-xl font-semibold mb-4">Pagamento via PIX</h3>
                
                <!-- QR Code gerado via API -->
                <div class="flex justify-center mb-4">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($qr_code) ?>" 
                         alt="QR Code PIX" 
                         class="border p-2 rounded-lg">
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg mb-4">
                    <p class="text-sm text-gray-600 mb-2">Código PIX (copia e cola):</p>
                    <div class="bg-white p-3 rounded border">
                        <code class="text-xs break-all"><?= htmlspecialchars($qr_code) ?></code>
                    </div>
                    <button onclick="copiarPix()" class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                        <i class="fas fa-copy"></i> Copiar código
                    </button>
                </div>
                
                <div class="bg-yellow-50 p-4 rounded-lg">
                    <p class="text-sm text-yellow-800">
                        <i class="fas fa-clock"></i> Aguardando confirmação de pagamento...
                    </p>
                    <p class="text-sm text-gray-600 mt-2">
                        Valor: <strong class="text-green-600">R$ <?= number_format($_SESSION['pedido_atual']['total'], 2, ',', '.') ?></strong>
                    </p>
                </div>
            </div>
            
            <div class="flex gap-4 justify-center">
                <button onclick="verificarPagamento(<?= $_SESSION['pedido_atual']['id'] ?>)" 
                        class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700">
                    <i class="fas fa-check-circle"></i> Já paguei, verificar
                </button>
                <a href="produtos.php" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600">
                    <i class="fas fa-store"></i> Continuar comprando
                </a>
            </div>
        </div>
        
    <?php else: ?>
        <!-- Formulário de checkout -->
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Resumo do pedido -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Resumo do Pedido</h2>
                
                <div class="max-h-96 overflow-y-auto mb-4">
                    <?php if (empty($carrinho)): ?>
                        <p class="text-gray-500 text-center">Carrinho vazio</p>
                    <?php else: ?>
                        <?php foreach ($carrinho as $item): ?>
                            <div class="flex justify-between items-center border-b py-3">
                                <div>
                                    <p class="font-semibold"><?= htmlspecialchars($item['nome']) ?></p>
                                    <p class="text-sm text-gray-600">Quantidade: <?= $item['quantidade'] ?></p>
                                </div>
                                <p class="font-semibold">R$ <?= number_format($item['total'], 2, ',', '.') ?></p>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                
                <div class="border-t pt-4">
                    <div class="flex justify-between mb-2">
                        <span>Subtotal:</span>
                        <span>R$ <?= number_format($total, 2, ',', '.') ?></span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span>Taxa de entrega:</span>
                        <span>R$ 5,00</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold mt-2 pt-2 border-t">
                        <span>Total:</span>
                        <span class="text-red-600">R$ <?= number_format($total + 5, 2, ',', '.') ?></span>
                    </div>
                </div>
            </div>
            
            <!-- Formulário de endereço -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold mb-4">Endereço de Entrega</h2>
                
                <form method="POST" action="" id="formCheckout">
                    <div class="mb-4">
                        <label class="block text-gray-700 mb-2">Endereço completo *</label>
                        <input type="text" name="endereco" required
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-500">
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2">Bairro *</label>
                            <input type="text" name="bairro" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Cidade *</label>
                            <input type="text" name="cidade" required
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-500">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-gray-700 mb-2">CEP *</label>
                            <input type="text" name="cep" required maxlength="9"
                                   placeholder="00000-000"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-500">
                        </div>
                        <div>
                            <label class="block text-gray-700 mb-2">Complemento</label>
                            <input type="text" name="complemento"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:border-red-500">
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 mb-2">Forma de Pagamento</label>
                        <div class="border rounded-lg p-3 bg-gray-50">
                            <div class="flex items-center">
                                <i class="fab fa-pix text-2xl text-green-600 mr-3"></i>
                                <div>
                                    <p class="font-semibold">PIX</p>
                                    <p class="text-sm text-gray-600">Pagamento via QR Code - Imediato</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button type="submit" name="finalizar" 
                            class="w-full bg-red-600 text-white py-3 rounded-lg hover:bg-red-700 transition duration-300">
                        <i class="fas fa-credit-card"></i> Gerar QR Code PIX
                    </button>
                </form>
            </div>
        </div>
    <?php endif; ?>
</div>

<script>
function copiarPix() {
    const pixCode = '<?= htmlspecialchars($qr_code) ?>';
    navigator.clipboard.writeText(pixCode).then(function() {
        Swal.fire({
            icon: 'success',
            title: 'Código copiado!',
            text: 'Código PIX copiado com sucesso.',
            timer: 2000,
            showConfirmButton: false
        });
    });
}

function verificarPagamento(pedidoId) {
    Swal.fire({
        title: 'Processando pagamento...',
        text: 'Aguarde enquanto verificamos seu pagamento',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    setTimeout(() => {
        Swal.fire({
            icon: 'success',
            title: 'Pagamento Confirmado!',
            text: 'Seu pedido foi confirmado e está sendo preparado.',
            confirmButtonColor: '#b91c1c'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'pedido_confirmado.php?pedido=' + pedidoId;
            }
        });
    }, 2000);
}
</script>

</body>
</html>