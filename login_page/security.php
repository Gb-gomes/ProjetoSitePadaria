<?php
session_start();
// Verificação de segurança (Checklist item 3)
if (!isset($_SESSION['usuario_id'])) {
header("Location: login.php");
exit;
}
?>