<?php
require_once './../../includes/config_page.php';
require_once './../../includes/conecta.php';
require_once './../../includes/verifica_admin.php';

// Processar promoção/rebaixamento de admin
$mensagem = '';
$erro = '';

if (isset($_POST['toggle_admin']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    
    try {
        // Buscar tipo atual do usuário
        $query = "SELECT tipo FROM usuarios WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':id' => $user_id]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Impedir que o próprio admin se rebaixe
            if ($user_id == $_SESSION['usuario_id']) {
                $erro = "Você não pode alterar seu próprio nível de acesso!";
            } else {
                $novo_tipo = ($user['tipo'] === 'admin') ? 'user' : 'admin';
                
                $update = "UPDATE usuarios SET tipo = :tipo WHERE id = :id";
                $stmt = $pdo->prepare($update);
                $stmt->execute([':tipo' => $novo_tipo, ':id' => $user_id]);
                
                $mensagem = "Usuário atualizado com sucesso!";
            }
        } else {
            $erro = "Usuário não encontrado!";
        }
    } catch (PDOException $e) {
        $erro = "Erro ao atualizar usuário!";
    }
}

// Processar exclusão de usuário
if (isset($_POST['delete_user']) && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    
    try {
        // Impedir auto-exclusão
        if ($user_id == $_SESSION['usuario_id']) {
            $erro = "Você não pode excluir seu próprio usuário!";
        } else {
            // Verificar se usuário existe
            $query = "SELECT id FROM usuarios WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':id' => $user_id]);
            
            if ($stmt->fetch()) {
                // Excluir usuário
                $delete = "DELETE FROM usuarios WHERE id = :id";
                $stmt = $pdo->prepare($delete);
                $stmt->execute([':id' => $user_id]);
                
                $mensagem = "Usuário excluído com sucesso!";
            } else {
                $erro = "Usuário não encontrado!";
            }
        }
    } catch (PDOException $e) {
        $erro = "Erro ao excluir usuário!";
    }
}

// Filtrar por tipo de usuário
$tipo_filtro = $_GET['tipo'] ?? 'todos';
$busca = $_GET['busca'] ?? '';

// Montar query
$sql = "SELECT * FROM usuarios WHERE 1=1";
$params = [];

if ($tipo_filtro != 'todos') {
    $sql .= " AND tipo = :tipo";
    $params[':tipo'] = $tipo_filtro;
}

if (!empty($busca)) {
    $sql .= " AND (nome LIKE :busca OR email LIKE :busca)";
    $params[':busca'] = "%$busca%";
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Estatísticas
$stats_sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN tipo = 'admin' THEN 1 ELSE 0 END) as admin,
                SUM(CASE WHEN tipo = 'user' THEN 1 ELSE 0 END) as user
              FROM usuarios";
$stats_stmt = $pdo->query($stats_sql);
$stats = $stats_stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Usuários - Admin Padaria</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">
    
    <?php include './../../includes/sidebar.php'; ?>
    
    <!-- Conteúdo principal -->
    <div class="flex-1 p-6">
        <div class="max-w-7xl mx-auto">
            
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">
                    <i class="fas fa-users"></i> Gerenciar Usuários
                </h1>
            </div>
            
            <!-- Mensagens -->
            <?php if ($mensagem): ?>
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    <?= $mensagem ?>
                </div>
            <?php endif; ?>
            
            <?php if ($erro): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <?= $erro ?>
                </div>
            <?php endif; ?>
            
            <!-- Cards de estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Total de Usuários</p>
                            <p class="text-2xl font-bold"><?= $stats['total'] ?? 0 ?></p>
                        </div>
                        <i class="fas fa-users text-3xl text-blue-500"></i>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Administradores</p>
                            <p class="text-2xl font-bold text-red-600"><?= $stats['admin'] ?? 0 ?></p>
                        </div>
                        <i class="fas fa-crown text-3xl text-red-500"></i>
                    </div>
                </div>
                
                <div class="bg-white rounded-lg shadow p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-500 text-sm">Usuários Comuns</p>
                            <p class="text-2xl font-bold text-green-600"><?= $stats['user'] ?? 0 ?></p>
                        </div>
                        <i class="fas fa-user text-3xl text-green-500"></i>
                    </div>
                </div>
            </div>
            
            <!-- Filtros e busca -->
            <div class="bg-white rounded-lg shadow p-4 mb-6">
                <div class="flex flex-wrap gap-4 items-center justify-between">
                    <div class="flex gap-2">
                        <a href="?tipo=todos" 
                           class="px-4 py-2 rounded <?= $tipo_filtro == 'todos' ? 'bg-red-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            Todos
                        </a>
                        <a href="?tipo=admin" 
                           class="px-4 py-2 rounded <?= $tipo_filtro == 'admin' ? 'bg-red-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            <i class="fas fa-crown"></i> Administradores
                        </a>
                        <a href="?tipo=user" 
                           class="px-4 py-2 rounded <?= $tipo_filtro == 'user' ? 'bg-red-700 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' ?>">
                            <i class="fas fa-user"></i> Usuários Comuns
                        </a>
                    </div>
                    
                    <form method="GET" class="flex gap-2">
                        <input type="hidden" name="tipo" value="<?= $tipo_filtro ?>">
                        <input type="text" name="busca" value="<?= htmlspecialchars($busca) ?>" 
                               placeholder="Buscar por nome ou email..."
                               class="border border-gray-300 rounded-lg px-4 py-2 w-64">
                        <button type="submit" class="bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Tabela de usuários -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-200">
                            <tr>
                                <th class="p-3 text-left">ID</th>
                                <th class="p-3 text-left">Nome</th>
                                <th class="p-3 text-left">Email</th>
                                <th class="p-3 text-left">Tipo</th>
                                <th class="p-3 text-left">Data de Cadastro</th>
                                <th class="p-3 text-left">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($usuarios)): ?>
                                <tr>
                                    <td colspan="6" class="p-3 text-center text-gray-500">
                                        Nenhum usuário encontrado
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="p-3 font-semibold">#<?= $usuario['id'] ?></td>
                                        <td class="p-3">
                                            <?= htmlspecialchars($usuario['nome']) ?>
                                            <?php if (isset($_SESSION['usuario_id']) && $usuario['id'] == $_SESSION['usuario_id']): ?>
                                                <span class="ml-2 text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded">
                                                    <i class="fas fa-user-check"></i> Você
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3"><?= htmlspecialchars($usuario['email']) ?></td>
                                        <td class="p-3">
                                            <?php if ($usuario['tipo'] === 'admin'): ?>
                                                <span class="px-2 py-1 rounded text-xs bg-red-100 text-red-800">
                                                    <i class="fas fa-crown"></i> Administrador
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">
                                                    <i class="fas fa-user"></i> Usuário
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="p-3"><?= date('d/m/Y H:i', strtotime($usuario['criado_em'])) ?></td>
                                        <td class="p-3">
                                            <div class="flex gap-3">
                                                <?php if (isset($_SESSION['usuario_id']) && $usuario['id'] != $_SESSION['usuario_id']): ?>
                                                    <!-- Form para promover/rebaixar -->
                                                    <form method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Tem certeza que deseja alterar o tipo deste usuário?');">
                                                        <input type="hidden" name="user_id" value="<?= $usuario['id'] ?>">
                                                        <?php if ($usuario['tipo'] === 'admin'): ?>
                                                            <button type="submit" name="toggle_admin" class="text-yellow-600 hover:text-yellow-800" title="Rebaixar para User">
                                                                <i class="fas fa-arrow-down text-lg"></i>
                                                            </button>
                                                        <?php else: ?>
                                                            <button type="submit" name="toggle_admin" class="text-green-600 hover:text-green-800" title="Promover a Admin">
                                                                <i class="fas fa-arrow-up text-lg"></i>
                                                            </button>
                                                        <?php endif; ?>
                                                    </form>
                                                    
                                                    <!-- Form para excluir -->
                                                    <form method="POST" style="display: inline;" 
                                                          onsubmit="return confirm('Tem certeza que deseja EXCLUIR este usuário? Esta ação não pode ser desfeita!');">
                                                        <input type="hidden" name="user_id" value="<?= $usuario['id'] ?>">
                                                        <button type="submit" name="delete_user" class="text-red-600 hover:text-red-800" title="Excluir">
                                                            <i class="fas fa-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <span class="text-gray-400 text-sm">(Você)</span>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Auto-esconder mensagens após 3 segundos
setTimeout(function() {
    let messages = document.querySelectorAll('.bg-green-100, .bg-red-100');
    messages.forEach(function(message) {
        message.style.display = 'none';
    });
}, 3000);
</script>

</body>
</html>