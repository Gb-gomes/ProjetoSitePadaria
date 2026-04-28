<?php
session_start();

if ($_SESSION['usuario_tipo'] == 'admin') {
    echo "Você é ADMIN";
} else {
    echo "Você é USUÁRIO";
}
?>