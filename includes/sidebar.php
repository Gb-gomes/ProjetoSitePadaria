<aside class="w-64 bg-red-700 text-white p-5 min-h-screen">

    <h1 class="text-2xl font-bold mb-8">
        Admin Padaria
    </h1>

    <nav class="flex flex-col gap-3">

        <a href="/ProjetoSitePadaria/administrador/index_admin.php"
           class="hover:bg-red-800 p-2 rounded">
            Dashboard
        </a>

        <a href="<?= BASE_URL ?>/administrador/produtos/listar.php"
           class="hover:bg-red-800 p-2 rounded">
            Produtos
        </a>

        <a href="<?= BASE_URL ?>/administrador/produtos/cadastra.php"
           class="hover:bg-red-800 p-2 rounded">
            Cadastrar Produto
        </a>

        <a href="<?= BASE_URL ?>/administrador/usuarios/listar.php"
           class="hover:bg-red-800 p-2 rounded">
            Usuários
        </a>

        <a href="<?= BASE_URL ?>/loginPHP/logout.php"
           class="hover:bg-red-800 p-2 rounded">
            Sair
        </a>

    </nav>

</aside>