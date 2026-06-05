<?php
session_start();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!isset($_SESSION['carrito'])) {
    $_SESSION['carrito'] = [];
}

if ($id > 0) {
    if (!isset($_SESSION['carrito'][$id])) {
        $_SESSION['carrito'][$id] = 1;
    } else {
        $_SESSION['carrito'][$id]++;
    }
}

header("Location: ../index.php");
exit;