<?php
try {
    $conn = new PDO("sqlite:" . __DIR__ . "/tienda.db");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Crear tabla si no existe
    $conn->exec("
        CREATE TABLE IF NOT EXISTS productos (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nombre TEXT,
            descripcion TEXT,
            precio REAL,
            imagen TEXT
        )
    ");

} catch (PDOException $e) {
    die("Error: " . $e->getMessage());
}
?>