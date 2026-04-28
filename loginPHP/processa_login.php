<?php
session_start();
require 'conecta.php';

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$sql = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);
$stmt->execute();

$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && $senha == $usuario['senha']) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];
    $_SESSION['usuario_tipo'] = $usuario['tipo'];
    
    header("Location: ../index.php");
    exit;
} else {
    $_SESSION['erro_login'] = "E-mail ou senha incorretos.";
    header("Location: login.php");
    exit;
}
?>