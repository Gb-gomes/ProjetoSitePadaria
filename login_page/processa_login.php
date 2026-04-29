<?php
session_start();

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

if ($user && password_verify($senha, $user['senha'])) {

    $_SESSION['usuario_id'] = $user['id'];
    $_SESSION['usuario_nome'] = $user['nome'];
    $_SESSION['tipo'] = $user['tipo'];

    echo json_encode([
        "status" => "ok",
        "redirect" => ($user['tipo'] == "admin")
            ? "admin/dashboard.php"
            : "cliente/dashboard.php"
    ]);

} else {
    echo json_encode([
        "status" => "erro"
    ]);
}
?>