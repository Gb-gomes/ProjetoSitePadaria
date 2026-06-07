<?php
require_once './../../includes/verifica_admin.php';
require_once './../../includes/config_page.php'; 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Produto</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex"> 
    
    <?php include './../../includes/sidebar.php'; ?>
   
    <div class="flex-1 p-6">
        <div class="max-w-3xl mx-auto bg-white p-6 rounded shadow">
            
            <h1 class="text-2xl font-bold mb-6">
                Cadastrar Produto
            </h1>

            <form action="processa_cadastro.php" method="POST">

                <div class="mb-4">
                    <label class="block mb-1">Nome</label>
                    <input
                        type="text"
                        name="nome"
                        required
                        class="w-full border p-2 rounded"
                    >
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Descrição</label>
                    <textarea
                        name="descricao"
                        class="w-full border p-2 rounded"
                    ></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Preço</label>
                    <input
                        type="number"
                        step="0.01"
                        name="preco"
                        required
                        class="w-full border p-2 rounded"
                    >
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Categoria</label>
                    <input
                        type="text"
                        name="categoria"
                        class="w-full border p-2 rounded"
                    >
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Estoque</label>
                    <input
                        type="number"
                        name="estoque"
                        value="0"
                        class="w-full border p-2 rounded"
                    >
                </div>

                <button
                    type="submit"
                    class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800"
                >
                    Salvar Produto
                </button>

            </form>

        </div>
    </div>
    
</div>

</body>
</html>