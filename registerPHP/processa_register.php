<?php
require '../loginPHP/conecta.php';

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Inserir no banco
$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
$stmt = $pdo->prepare($sql);

$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':senha', $senha);

if ($stmt->execute()) {
    echo "Usuário cadastrado com sucesso! <a href='login.php'>Fazer login</a>";
} else {
    echo "Erro ao cadastrar.";
}
?>