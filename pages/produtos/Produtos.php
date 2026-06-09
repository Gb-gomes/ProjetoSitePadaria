<?php
session_start();
require_once '../../includes/conecta.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['carrinho_json'])) {
    $_SESSION['carrinho'] = json_decode($_POST['carrinho_json'], true);
    $_SESSION['total'] = $_POST['total'] ?? 0;
    exit();
}

try {
    $sql = "SELECT * FROM produtos ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    $produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $produtos = [];
    $erro = "Erro ao carregar produtos: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos | Padaria Artesanal Delícias</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        /* Reset básico */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #fdf6e3;
            color: #402416;
            font-family: 'Segoe UI', Arial, sans-serif;
        }

        /* Container principal */
        .all_page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Main content */
        main {
            flex: 1;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
            padding: 40px 20px;
        }

        /* Título */
        .titulo-produtos {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .titulo-produtos h1 {
            font-size: 2.5rem;
            color: #8B4513;
            font-family: 'Georgia', serif;
            margin-bottom: 10px;
        }
        
        .titulo-produtos p {
            color: #A0522D;
            font-size: 1.1rem;
        }

        /* Grid de produtos */
        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 30px;
            margin-bottom: 50px;
        }

        .produto-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .produto-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .produto-imagem {
            width: 100%;
            height: 250px;
            object-fit: cover;
            background-color: #f3f4f6;
        }

        .produto-info {
            padding: 20px;
        }

        .produto-categoria {
            display: inline-block;
            background-color: #fef3c7;
            color: #d97706;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .produto-nome {
            font-size: 1.3rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        .produto-descricao {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .produto-preco {
            font-size: 1.6rem;
            font-weight: bold;
            color: #b91c1c;
            margin-bottom: 20px;
        }

        .bnt_compra {
            display: flex;
            gap: 12px;
            flex-direction: column;
        }

        .btn-compra {
            background: linear-gradient(135deg, #4fe315 0%, #3ba80e 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-compra:hover {
            transform: scale(1.02);
            background: linear-gradient(135deg, #3ba80e 0%, #2d8a0a 100%);
        }

        .btn-carrinho {
            background: linear-gradient(135deg, #b91c1c 0%, #8b1515 100%);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 10px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-carrinho:hover {
            transform: scale(1.02);
            background: linear-gradient(135deg, #8b1515 0%, #6b1010 100%);
        }

        /* Carrinho */
        .carrinho-section {
            background: white;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            margin-top: 20px;
        }

        .carrinho-section h2 {
            color: #8B4513;
            margin-bottom: 20px;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        #lista-carrinho {
            list-style: none;
            padding: 0;
        }

        #lista-carrinho li {
            padding: 15px;
            border-bottom: 1px solid #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 15px;
        }

        #lista-carrinho li:last-child {
            border-bottom: none;
        }

        #lista-carrinho input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 70px;
            text-align: center;
        }

        #lista-carrinho button {
            background: #dc2626;
            color: white;
            border: none;
            padding: 6px 15px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s;
        }

        #lista-carrinho button:hover {
            background: #b91c1c;
        }

        .total {
            font-size: 1.3rem;
            font-weight: bold;
            color: #8B4513;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 2px solid #f0f0f0;
            text-align: right;
        }

        .btn-finalizar {
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            cursor: pointer;
            margin-top: 20px;
            font-size: 1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            width: 100%;
        }

        .btn-finalizar:hover {
            transform: scale(1.02);
            background: linear-gradient(135deg, #15803d 0%, #0f5c2c 100%);
        }

        /* Erro */
        .erro-produtos {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 20px;
            margin: 40px 0;
        }

        .erro-produtos p {
            color: #666;
            margin: 10px 0;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            main {
                padding: 20px 15px;
            }
            
            .titulo-produtos h1 {
                font-size: 1.8rem;
            }
            
            .produtos-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }
            
            .carrinho-section {
                padding: 15px;
            }
            
            #lista-carrinho li {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .total {
                text-align: center;
            }
        }
    </style>
</head>

<body>
    <div class="all_page">
        
        <?php include '../../includes/header.php'; ?>
        <main>
            <div class="titulo-produtos">
                <h1>🍞 Nossos Produtos</h1>
                <p>Feitos com amor e ingredientes selecionados</p>
            </div>
            
            <?php if (isset($erro)): ?>
                <div class="erro-produtos">
                    <p><?= htmlspecialchars($erro) ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (empty($produtos)): ?>
                <div class="erro-produtos">
                    <p>Nenhum produto disponível no momento.</p>
                    <p>Volte em breve!</p>
                </div>
            <?php else: ?>
                <div class="produtos-grid">
                    <?php foreach($produtos as $produto): ?>
                        <div class="produto-card">
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="../uploads/<?= htmlspecialchars($produto['imagem']) ?>" 
                                     alt="<?= htmlspecialchars($produto['nome']) ?>"
                                     class="produto-imagem">
                            <?php else: ?>
                                <div class="produto-imagem" style="display: flex; align-items: center; justify-content: center;">
                                    <i class="fas fa-bread-slice" style="font-size: 5rem; color: #ccc;"></i>
                                </div>
                            <?php endif; ?>
                            
                            <div class="produto-info">
                                <?php if (!empty($produto['categoria'])): ?>
                                    <span class="produto-categoria"><?= htmlspecialchars($produto['categoria']) ?></span>
                                <?php endif; ?>
                                
                                <h3 class="produto-nome"><?= htmlspecialchars($produto['nome']) ?></h3>
                                
                                <?php if (!empty($produto['descricao'])): ?>
                                    <p class="produto-descricao"><?= htmlspecialchars(substr($produto['descricao'], 0, 100)) ?>...</p>
                                <?php endif; ?>
                                
                                <div class="produto-preco">
                                    R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                </div>
                                
                                <div class="bnt_compra">
                                    <button class="btn-compra" 
                                            onclick="comprarAgora('<?= htmlspecialchars($produto['nome']) ?>', <?= $produto['preco'] ?>, <?= $produto['id'] ?>)">
                                        <i class="fas fa-bolt"></i> Comprar Agora
                                    </button>
                                    
                                    <button class="btn-carrinho" 
                                            onclick="adicionarAoCarrinho('<?= htmlspecialchars($produto['nome']) ?>', <?= $produto['preco'] ?>, <?= $produto['id'] ?>)">
                                        <i class="fas fa-cart-plus"></i> Adicionar ao Carrinho
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Carrinho de compras -->
            <section class="carrinho-section">
                <h2><i class="fas fa-shopping-cart"></i> Meu Carrinho</h2>
                <ul id="lista-carrinho"></ul>
                <p class="total" id="total">Total: R$ 0,00</p>
                <button onclick="finalizarCompra()" class="btn-finalizar">
                    <i class="fas fa-check-circle"></i> Finalizar Compra
                </button>
            </section>
        </main>

        <footer>
            <div class="footer-container">
                <div class="footer-links">
                    <a href="#">Política de Privacidade</a>
                    <a href="#">Termos de Serviço</a>
                </div>
                <div class="footer-socials">
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-tiktok"></i></a>
                </div>
                <p class="footer-copy">&copy; 2025 Padaria Artesanal Delícias. All rights reserved.</p>
            </div>
        </footer>
    </div>

    <script>
        let carrinho = [];
        let total = 0;
        
        function adicionarAoCarrinho(nome, preco, id) {
            const itemExistente = carrinho.find(item => item.id === id);
            
            if (itemExistente) {
                itemExistente.quantidade++;
                itemExistente.total = itemExistente.quantidade * itemExistente.preco;
            } else {
                carrinho.push({
                    id: id,
                    nome: nome,
                    preco: preco,
                    quantidade: 1,
                    total: preco
                });
            }
            
            atualizarCarrinho();
            
            Swal.fire({
                icon: 'success',
                title: 'Adicionado!',
                text: `${nome} foi adicionado ao carrinho.`,
                timer: 1500,
                showConfirmButton: false
            });
        }
        
        function comprarAgora(nome, preco, id) {
            carrinho = [{
                id: id,
                nome: nome,
                preco: preco,
                quantidade: 1,
                total: preco
            }];
            atualizarCarrinho();
            
            Swal.fire({
                icon: 'success',
                title: 'Produto selecionado!',
                text: 'Clique em Finalizar Compra para continuar.',
                timer: 1500,
                showConfirmButton: false
            });
        }
        
        function removerDoCarrinho(id) {
            const index = carrinho.findIndex(item => item.id === id);
            if (index !== -1) {
                const item = carrinho[index];
                carrinho.splice(index, 1);
                atualizarCarrinho();
                
                Swal.fire({
                    icon: 'info',
                    title: 'Removido!',
                    text: `${item.nome} foi removido do carrinho.`,
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        }
        
        function atualizarQuantidade(id, novaQuantidade) {
            const item = carrinho.find(item => item.id === id);
            if (item && novaQuantidade > 0) {
                item.quantidade = parseInt(novaQuantidade);
                item.total = item.quantidade * item.preco;
                atualizarCarrinho();
            } else if (novaQuantidade <= 0) {
                removerDoCarrinho(id);
            }
        }
        
        function atualizarCarrinho() {
            const lista = document.getElementById('lista-carrinho');
            lista.innerHTML = '';
            total = 0;
            
            if (carrinho.length === 0) {
                lista.innerHTML = '<li style="text-align: center; color: #999;">Seu carrinho está vazio</li>';
            } else {
                carrinho.forEach(item => {
                    total += item.total;
                    
                    const li = document.createElement('li');
                    li.innerHTML = `
                        <span style="flex: 2;"><strong>${item.nome}</strong> - R$ ${item.preco.toFixed(2)}</span>
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            <label>Qtd:</label>
                            <input type="number" min="1" value="${item.quantidade}" 
                                   onchange="atualizarQuantidade(${item.id}, this.value)">
                            <span><strong>= R$ ${item.total.toFixed(2)}</strong></span>
                            <button onclick="removerDoCarrinho(${item.id})">
                                <i class="fas fa-trash"></i> Remover
                            </button>
                        </div>
                    `;
                    lista.appendChild(li);
                });
            }
            
            document.getElementById('total').textContent = `Total: R$ ${total.toFixed(2)}`;
            localStorage.setItem('carrinho', JSON.stringify(carrinho));
        }
        
        function finalizarCompra() {
            if (carrinho.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Carrinho vazio!',
                    text: 'Adicione produtos ao carrinho antes de finalizar.',
                    confirmButtonColor: '#b91c1c'
                });
                return;
            }
            
            fetch('checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'carrinho_json=' + JSON.stringify(carrinho) + '&total=' + total
            }).then(() => {
                window.location.href = 'checkout.php';
            });
        }
        
        window.onload = function() {
            const carrinhoSalvo = localStorage.getItem('carrinho');
            if (carrinhoSalvo) {
                carrinho = JSON.parse(carrinhoSalvo);
                atualizarCarrinho();
            }
        }
    </script>
</body>

</html>