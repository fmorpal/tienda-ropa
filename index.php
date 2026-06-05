<?php
session_start();
require_once "config/db.php";

// Obtener productos
$sql = "SELECT * FROM productos";
$stmt = $conn->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Contador carrito
$totalCarrito = 0;

if (isset($_SESSION['carrito'])) {
    $totalCarrito = array_sum($_SESSION['carrito']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Tienda de Ropa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
        }

        .producto-card {
            transition: all .3s ease;
            height: 100%;
        }

        .producto-card:hover {
            transform: translateY(-5px);
        }

        .producto-img {
            height: 250px;
            object-fit: cover;
        }

        .precio {
            font-size: 1.3rem;
            font-weight: bold;
            color: #198754;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .sin-productos {
            text-align: center;
            padding: 50px;
            background: white;
            border-radius: 10px;
        }
    </style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">

        <a class="navbar-brand" href="index.php">
            🛍️ Mi Tienda de Ropa
        </a>

        <div class="d-flex">

            <a href="carrito/ver.php" class="btn btn-outline-light">
                🛒 Carrito
                <span class="badge bg-danger">
                    <?= $totalCarrito ?>
                </span>
            </a>

        </div>

    </div>
</nav>

<!-- CONTENIDO -->
<div class="container py-5">

    <h1 class="text-center mb-5">
        Productos disponibles
    </h1>

    <?php if (empty($productos)): ?>

        <div class="sin-productos">
            <h3>No hay productos disponibles</h3>
            <p class="text-muted">
                Añade productos a la base de datos para comenzar.
            </p>
        </div>

    <?php else: ?>

        <div class="row">

            <?php foreach ($productos as $producto): ?>

                <div class="col-md-4 col-lg-3 mb-4">
                    <div class="card producto-card shadow-sm">

                        <!-- IMAGEN CORREGIDA -->
                        <?php if (!empty($producto['imagen'])): ?>

                            <img
                                src="uploads/<?= htmlspecialchars($producto['imagen']) ?>"
                                class="card-img-top producto-img"
                                alt="<?= htmlspecialchars($producto['nombre']) ?>"
                            >

                        <?php else: ?>

                            <img
                                src="https://via.placeholder.com/400x300?text=Sin+Imagen"
                                class="card-img-top producto-img"
                                alt="Sin imagen"
                            >

                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title">
                                <?= htmlspecialchars($producto['nombre']) ?>
                            </h5>

                            <p class="card-text flex-grow-1">
                                <?= htmlspecialchars($producto['descripcion']) ?>
                            </p>

                            <div class="precio mb-3">
                                <?= number_format($producto['precio'], 2) ?> €
                            </div>

                            <a
                                href="carrito/agregar.php?id=<?= $producto['id'] ?>"
                                class="btn btn-primary w-100"
                            >
                                Añadir al carrito
                            </a>

                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>