<?php
require_once './../includes/verifica_admin.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<?php include './../includes/header.php'; ?>


<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<div class="flex">

    <?php include './../includes/sidebar.php'; ?>

    <div class="flex-1">

        <?php include './../includes/topbar.php'; ?>

        <main class="p-8">

            <h2 class="text-3xl font-bold mb-6">
                Dashboard
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">

                <div class="bg-white p-5 rounded shadow">
                    <h3 class="font-bold">
                        Produtos
                    </h3>

                    <p class="text-3xl">
                        0
                    </p>
                </div>

                <div class="bg-white p-5 rounded shadow">
                    <h3 class="font-bold">
                        Usuários
                    </h3>

                    <p class="text-3xl">
                        0
                    </p>
                </div>

            </div>

        </main>

    </div>

</div>

</body>
</html>