<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: listar.php?erro=" . urlencode("ID do produto inválido"));
    exit();
}


try {
    $sql = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$produto) {
        header("Location: listar.php?erro=" . urlencode("Produto não encontrado"));
        exit();
    }
} catch (PDOException $e) {
    header("Location: listar.php?erro=" . urlencode("Erro ao buscar produto: " . $e->getMessage()));
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Excluir Produto - Admin Padaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">
    
    <?php include './../../includes/sidebar.php'; ?>
    
    <!-- Conteúdo principal -->
    <div class="flex-1 p-6">
        <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
            
            <h1 class="text-2xl font-bold mb-6 text-red-600">
                Confirmar Exclusão
            </h1>
            
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700">
                            <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <p class="text-gray-700 mb-2">Você tem certeza que deseja excluir o produto abaixo?</p>
                <div class="bg-gray-100 p-4 rounded">
                    <p class="font-bold text-lg"><?= htmlspecialchars($produto['nome']) ?></p>
                    <p class="text-gray-600">ID: <?= $produto['id'] ?></p>
                    <p class="text-gray-600">Preço: R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
                    <p class="text-gray-600">Categoria: <?= htmlspecialchars($produto['categoria']) ?></p>
                    <p class="text-gray-600">Estoque: <?= $produto['estoque'] ?> unidades</p>
                </div>
            </div>
            
            <div class="flex gap-3">
                <a href="processa_exclusao.php?id=<?= $produto['id'] ?>" 
                   class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">
                    Sim, excluir permanentemente
                </a>
                <a href="listar.php"
                   class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    Cancelar
                </a>
            </div>
            
        </div>
    </div>
    
</div>

</body>
</html>