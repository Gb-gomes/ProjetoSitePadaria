<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: listar.php?erro=ID do produto inválido");
    exit();
}


try {
    $sql = "SELECT * FROM produtos WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $produto = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$produto) {
        header("Location: listar.php?erro=Produto não encontrado");
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
    <title>Editar Produto - Admin Padaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">
    
    <?php include './../../includes/sidebar.php'; ?>
    
    <!-- Conteúdo principal -->
    <div class="flex-1 p-6">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            
            <h1 class="text-2xl font-bold mb-6">
                Editar Produto
            </h1>
            
            <?php if (isset($_GET['erro'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= htmlspecialchars($_GET['erro']) ?>
                </div>
            <?php endif; ?>
            
            <form action="processa_edicao.php" method="POST">
                
                <input type="hidden" name="id" value="<?= $produto['id'] ?>">
                
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Nome *</label>
                    <input
                        type="text"
                        name="nome"
                        value="<?= htmlspecialchars($produto['nome']) ?>"
                        required
                        class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-red-700"
                    >
                </div>
                
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Descrição</label>
                    <textarea
                        name="descricao"
                        rows="4"
                        class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-red-700"
                    ><?= htmlspecialchars($produto['descricao']) ?></textarea>
                </div>
                
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Preço *</label>
                    <input
                        type="number"
                        step="0.01"
                        name="preco"
                        value="<?= $produto['preco'] ?>"
                        required
                        class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-red-700"
                    >
                </div>
                
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Categoria</label>
                    <input
                        type="text"
                        name="categoria"
                        value="<?= htmlspecialchars($produto['categoria']) ?>"
                        class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-red-700"
                    >
                </div>
                
                <div class="mb-4">
                    <label class="block mb-1 font-medium">Estoque</label>
                    <input
                        type="number"
                        name="estoque"
                        value="<?= $produto['estoque'] ?>"
                        class="w-full border p-2 rounded focus:outline-none focus:ring-2 focus:ring-red-700"
                    >
                </div>
                
                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800"
                    >
                        Atualizar Produto
                    </button>
                    <a
                        href="listar.php"
                        class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600"
                    >
                        Cancelar
                    </a>
                </div>
                
            </form>
            
        </div>
    </div>
    
</div>

</body>
</html>