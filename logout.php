<?php
session_start();
ob_start();
unset($_SESSION['id'],$_SESSION['nome'],$_SESSION['status']);
$_SESSION['msg'] = "<p style='color:green'>Usuário deslogado</p>";
header("Location: admin.php");