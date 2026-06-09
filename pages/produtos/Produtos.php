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
    <link rel="stylesheet" href="Produtos.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .produtos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .produto-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .produto-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .produto-imagem {
            width: 100%;
            height: 200px;
            object-fit: cover;
            background-color: #f3f4f6;
        }
        
        .produto-info {
            padding: 1rem;
        }
        
        .produto-nome {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #333;
        }
        
        .produto-descricao {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 1rem;
            line-height: 1.4;
        }
        
        .produto-preco {
            font-size: 1.3rem;
            font-weight: bold;
            color: #b91c1c;
            margin-bottom: 1rem;
        }
        

        .bnt_compra{
            display: flex;
            gap: 0.5rem;
            flex-direction: column;
        }

        .btn-carrinho {
            background-color: #b91c1c;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-compra {
             background-color: #4fe315;
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            font-size: 1rem;
            transition: background-color 0.3s ease;
        }

        .btn-compra:hover {
            background-color: #2b6a28;
        }
        
        .btn-carrinho:hover {
            background-color: #7f1d1d;
        }
        
        .produto-categoria {
            display: inline-block;
            background-color: #f3f4f6;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        
        .erro-produtos {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        @media (max-width: 768px) {
            .produtos-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
                gap: 1rem;
                padding: 1rem;
            }
        }

        /* Estilos para a lista de produtos */
.produtos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.produto-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
}

.produto-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.produto-imagem {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.produto-info {
    padding: 1rem;
}

.produto-nome {
    font-size: 1.2rem;
    font-weight: bold;
    margin-bottom: 0.5rem;
}

.produto-preco {
    font-size: 1.3rem;
    color: #b91c1c;
    font-weight: bold;
    margin: 0.5rem 0;
}

.btn-carrinho {
    background: #b91c1c;
    color: white;
    border: none;
    padding: 0.5rem;
    border-radius: 6px;
    cursor: pointer;
    width: 100%;
}

.btn-carrinho:hover {
    background: #7f1d1d;
}

/* Carrinho */
.carrinho {
    background: white;
    padding: 1.5rem;
    margin: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.carrinho ul {
    list-style: none;
    padding: 0;
}

.carrinho li {
    padding: 0.5rem;
    border-bottom: 1px solid #eee;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.carrinho input {
    padding: 0.25rem;
    border: 1px solid #ddd;
    border-radius: 4px;
}

.btn-finalizar {
    background: #16a34a;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 1rem;
}

.btn-finalizar:hover {
    background: #15803d;
}

@media (max-width: 768px) {
    .produtos-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 1rem;
        padding: 1rem;
    }
}
    </style>
</head>

<body>
    <section class="all_page">
        <header>
            <?php include '../../includes/header.php'; ?>
        
        </header>

        <main>
            <section class="produtos-destaque">
                <h1 style="text-align: center; padding: 2rem 0 0 0; color: #333;">🍞 Nossos Produtos</h1>
                
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
                                    <div class="produto-imagem" style="display: flex; align-items: center; justify-content: center; background: #f3f4f6;">
                                        <i class="fa-solid fa-bread-slice" style="font-size: 4rem; color: #ccc;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="produto-info">
                                    <?php if (!empty($produto['categoria'])): ?>
                                        <span class="produto-categoria"><?= htmlspecialchars($produto['categoria']) ?></span>
                                    <?php endif; ?>
                                    
                                    <h3 class="produto-nome"><?= htmlspecialchars($produto['nome']) ?></h3>
                                    
                                    <?php if (!empty($produto['descricao'])): ?>
                                        <p class="produto-descricao"><?= htmlspecialchars(substr($produto['descricao'], 0, 100)) ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="produto-preco">
                                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                    </div>
                                    
                                    <div class="bnt_compra">

                                        <button class="btn-compra" 
                                                onclick="adicionarAoCarrinho('<?= htmlspecialchars($produto['nome']) ?>', <?= $produto['preco'] ?>, <?= $produto['id'] ?>)">
                                            <i class="fa-solid fa-cart-plus"></i> Comprar Produto 
                                        </button>

                                        <button class="btn-carrinho" 
                                                onclick="adicionarAoCarrinho('<?= htmlspecialchars($produto['nome']) ?>', <?= $produto['preco'] ?>, <?= $produto['id'] ?>)">
                                            <i class="fa-solid fa-cart-plus"></i> Adicionar ao carrinho
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </section>

            <!-- Carrinho de compras -->
            <section class="carrinho">
                <h2>Meu Carrinho</h2>
                <ul id="lista-carrinho"></ul>
                <p id="total">Total: R$ 0,00</p>
                <button onclick="finalizarCompra()" class="btn-finalizar">Finalizar Compra</button>
            </section>
        </main>

        <footer>
            <div class="footer-container">
                <div class="footer-links">
                    <a href="privacy-policy.html">Política de Privacidade</a>
                    <a href="terms-of-servicy.html">Termos de Serviço</a>
                </div>
                <div class="footer-socials">
                    <a href="https://www.instagram.com/familiadelicias.br/"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@artesanaldelicias"><i class="fab fa-tiktok"></i></a>
                </div>
                <p class="footer-copy">&copy; 2025 Padaria Artesanal Delícias. All rights reserved.</p>
            </div>
        </footer>
        
    </section>

    <script>
        let carrinho = [];
        let total = 0;
        
        function adicionarAoCarrinho(nome, preco, id) {
            // Verificar se o produto já está no carrinho
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
        }
        
        function removerDoCarrinho(id) {
            const index = carrinho.findIndex(item => item.id === id);
            if (index !== -1) {
                carrinho.splice(index, 1);
                atualizarCarrinho();
            }
        }
        
        function atualizarQuantidade(id, novaQuantidade) {
            const item = carrinho.find(item => item.id === id);
            if (item && novaQuantidade > 0) {
                item.quantidade = novaQuantidade;
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
            
            carrinho.forEach(item => {
                total += item.total;
                
                const li = document.createElement('li');
                li.innerHTML = `
                    ${item.nome} - R$ ${item.preco.toFixed(2)} x 
                    <input type="number" min="1" value="${item.quantidade}" 
                           onchange="atualizarQuantidade(${item.id}, this.value)" 
                           style="width: 50px; text-align: center;">
                    = R$ ${item.total.toFixed(2)}
                    <button onclick="removerDoCarrinho(${item.id})" style="margin-left: 10px; background: #dc2626; color: white; border: none; padding: 2px 8px; border-radius: 4px; cursor: pointer;">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                `;
                lista.appendChild(li);
            });
            
            document.getElementById('total').textContent = `Total: R$ ${total.toFixed(2)}`;
            
            // Salvar carrinho no localStorage
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
                    body: 'carrinho=' + JSON.stringify(carrinho) + '&total=' + total
                    }).then(() => {
                        window.location.href = 'checkout.php';
                    });
                }
        
        // Carregar carrinho do localStorage ao iniciar
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