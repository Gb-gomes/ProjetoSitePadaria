<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';


$sql = "SELECT * FROM produtos ORDER BY id DESC";
$stmt = $pdo->query($sql);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Produtos - Admin Padaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilo adicional para garantir que o flex funcione */
        .flex { display: flex; }
        .min-h-screen { min-height: 100vh; }
        .flex-1 { flex: 1; }
        .p-6 { padding: 1.5rem; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background-color: #f3f4f6; }
        tr:hover { background-color: #f9fafb; }
    </style>
</head>

<body class="bg-gray-100">

<div style="display: flex; min-height: 100vh;">
    
    <?php include './../../includes/sidebar.php'; ?>
    
    <!-- Conteúdo principal -->
    <div style="flex: 1; padding: 1.5rem;">
        <div style="max-width: 1200px; margin: 0 auto; background: white; padding: 1.5rem; border-radius: 0.5rem; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h1 style="font-size: 1.5rem; font-weight: bold;">
                    Produtos Cadastrados
                </h1>
                <a href="cadastra.php" 
                   style="background-color: #b91c1c; color: white; padding: 0.5rem 1rem; border-radius: 0.25rem; text-decoration: none;">
                    + Novo Produto
                </a>
            </div>
            
            <!-- Tabela de produtos -->
            <div style="overflow-x: auto;">
                <table style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="padding: 12px; text-align: left; background-color: #f3f4f6;">ID</th>
                            <th style="padding: 12px; text-align: left; background-color: #f3f4f6;">Nome</th>
                            <th style="padding: 12px; text-align: left; background-color: #f3f4f6;">Preço</th>
                            <th style="padding: 12px; text-align: left; background-color: #f3f4f6;">Categoria</th>
                            <th style="padding: 12px; text-align: left; background-color: #f3f4f6;">Estoque</th>
                            <th style="padding: 12px; text-align: left; background-color: #f3f4f6;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($produtos as $produto): ?>
                        <tr style="border-bottom: 1px solid #e5e7eb;">
                            <td style="padding: 12px;"><?= $produto['id'] ?></td>
                            <td style="padding: 12px; font-weight: 500;"><?= htmlspecialchars($produto['nome']) ?></td>
                            <td style="padding: 12px; color: #16a34a; font-weight: 600;">
                                R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                            </td>
                            <td style="padding: 12px;"><?= htmlspecialchars($produto['categoria']) ?></td>
                            <td style="padding: 12px;">
                                <?php if($produto['estoque'] <= 0): ?>
                                    <span style="color: #dc2626;"><?= $produto['estoque'] ?> un.</span>
                                <?php elseif($produto['estoque'] <= 5): ?>
                                    <span style="color: #ca8a04;"><?= $produto['estoque'] ?> un.</span>
                                <?php else: ?>
                                    <span style="color: #16a34a;"><?= $produto['estoque'] ?> un.</span>
                                <?php endif; ?>
                            </td>
                            <td style="padding: 12px;">
                                <a href="editar.php?id=<?= $produto['id'] ?>" style="color: #2563eb; text-decoration: none; margin-right: 10px;">Editar</a>
                                <a href="excluir.php?id=<?= $produto['id'] ?>" 
                                   style="color: #dc2626; text-decoration: none;"
                                   onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            
            <div style="margin-top: 1rem; font-size: 0.875rem; color: #6b7280;">
                Total de produtos: <?= count($produtos) ?>
            </div>
            
        </div>
    </div>
    
</div>

</body>
</html>