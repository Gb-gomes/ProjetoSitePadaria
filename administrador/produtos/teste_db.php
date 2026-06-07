<?php
require_once './../../includes/verifica_admin.php';
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once './../../includes/conecta.php';

echo "<h2>Teste de Conexão</h2>";

// Verificar se $pdo existe
if (isset($pdo)) {
    echo "<p style='color:green'>✓ Variável \$pdo existe</p>";
} else {
    echo "<p style='color:red'>✗ Variável \$pdo NÃO existe!</p>";
    die();
}

// Testar consulta
try {
    $sql = "SHOW TABLES";
    $stmt = $pdo->query($sql);
    $tables = $stmt->fetchAll();
    
    echo "<h3>Tabelas no banco:</h3>";
    echo "<ul>";
    foreach ($tables as $table) {
        echo "<li>" . implode(', ', $table) . "</li>";
    }
    echo "</ul>";
    
    // Verificar tabela produtos
    $sql = "SELECT COUNT(*) as total FROM produtos";
    $stmt = $pdo->query($sql);
    $count = $stmt->fetch();
    
    echo "<h3>Produtos:</h3>";
    echo "<p>Total de produtos na tabela: " . $count['total'] . "</p>";
    
    if ($count['total'] > 0) {
        $sql = "SELECT * FROM produtos LIMIT 5";
        $stmt = $pdo->query($sql);
        $produtos = $stmt->fetchAll();
        
        echo "<h3>Lista de Produtos:</h3>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Nome</th><th>Preço</th><th>Categoria</th><th>Estoque</th></tr>";
        foreach ($produtos as $produto) {
            echo "<tr>";
            echo "<td>" . ($produto['id'] ?? '-') . "</td>";
            echo "<td>" . ($produto['nome'] ?? '-') . "</td>";
            echo "<td>" . ($produto['preco'] ?? '-') . "</td>";
            echo "<td>" . ($produto['categoria'] ?? '-') . "</td>";
            echo "<td>" . ($produto['estoque'] ?? '-') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color:red'>Erro: " . $e->getMessage() . "</p>";
}
?>