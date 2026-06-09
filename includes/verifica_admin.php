<?php

session_start();

if (
    !isset($_SESSION['usuario_tipo']) ||
    $_SESSION['usuario_tipo'] !== 'admin'
) {
    header("Location: ../../loginPHP/login.php");
    exit();
} 