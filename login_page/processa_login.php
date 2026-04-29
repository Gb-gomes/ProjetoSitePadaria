<?php
$conn = new mysqli("localhost", "root", "", "sistema_empresa");

if ($conn->connect_error) {
    die("Erro na conexão");
}

$email = $_POST['email'];
$senha = $_POST['senha'];

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user && $senha == $user['senha']) {
    header("Location: ../index.html");
} else {
    echo "Email ou senha incorretos!";
}
?>