<?php
session_start();
require 'conecta.php';

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':email', $email);

$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && $senha == $usuario['senha']) {
    $_SESSION['usuario_id'] = $usuario['id'];
    $_SESSION['usuario_nome'] = $usuario['nome'];

    header("Location: ../index.html");
    exit;
} else {
    echo "E-mail ou senha inválidos.";
}
?>