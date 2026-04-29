<?php
$host = 'localhost';
$db = 'sistema_empresa';
$user = 'root';
$pass = '1234';
try {
$pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
// Configura o PDO para lançar exceções em caso de erro
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
die("Erro na conexão: " . $e->getMessage());
}
?>