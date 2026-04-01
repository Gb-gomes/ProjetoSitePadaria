<?php
require '../loginPHP/conecta.php'; 

$nome = $_POST['nome'];
$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
$stmt = $pdo->prepare($sql);

$stmt->bindValue(':nome', $nome);
$stmt->bindValue(':email', $email);
$stmt->bindValue(':senha', $senha);

if ($stmt->execute()) {
    header("Location: ../loginPHP/login.php");
    exit;
} else {
    echo "Erro ao cadastrar.";
}
?>