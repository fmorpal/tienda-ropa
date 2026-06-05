<?php
session_start();
require_once "../config/db.php";

$carrito = $_SESSION['carrito'] ?? [];

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Mi Carrito</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container py-5">

    <h1 class="mb-4">🛒 Mi carrito</h1>

    <?php if (empty($carrito)): ?>

        <div class="alert alert-warning">
            Tu carrito está vacío.
        </div>

        <a href="../index.php" class="btn btn-primary">
            Volver a la tienda
        </a>

    <?php else: ?>

        <table class="table table-bordered align-middle">

            <thead class="table-dark">
                <tr>
                    <th>Producto</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>

            <?php foreach ($carrito as $id => $cantidad): ?>

                <?php
                $stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
                $stmt->execute([$id]);

                $producto = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$producto) continue;

                $subtotal = $producto['precio'] * $cantidad;
                $total += $subtotal;
                ?>

                <tr>
                    <td>
                        <?= htmlspecialchars($producto['nombre']) ?>
                    </td>

                    <td>
                        <?= number_format($producto['precio'], 2) ?> €
                    </td>

                    <td>
                        <?= $cantidad ?>
                    </td>

                    <td>
                        <?= number_format($subtotal, 2) ?> €
                    </td>
                </tr>

            <?php endforeach; ?>

            </tbody>

        </table>

        <div class="text-end">

            <h3>
                Total: <?= number_format($total, 2) ?> €
            </h3>

        </div>

        <div class="mt-4 d-flex gap-2">

            <a href="../index.php" class="btn btn-secondary">
                Seguir comprando
            </a>

            <a href="vaciar.php" class="btn btn-danger">
                Vaciar carrito
            </a>

        </div>

    <?php endif; ?>

</div>

</body>
</html>