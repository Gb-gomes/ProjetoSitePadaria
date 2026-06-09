<?php
require_once './includes/config_page.php';
session_start();
session_destroy();

header("Location: " . BASE_URL . "/loginPHP/login.php");
exit;
?>