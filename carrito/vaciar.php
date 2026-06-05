<?php
session_start();

unset($_SESSION['carrito']);

header("Location: ver.php");
exit;