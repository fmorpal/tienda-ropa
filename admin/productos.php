<?php
require_once "../config/db.php";

/* =========================
   CREAR PRODUCTO
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);

    // 🔥 IMAGEN
    $imagenNombre = '';

    if (!empty($_FILES['imagen']['name'])) {

        $directorio = "../uploads/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        $imagenNombre = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaFinal = $directorio . $imagenNombre;

        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFinal);
    }

    $stmt = $conn->prepare("
        INSERT INTO productos (nombre, descripcion, precio, imagen)
        VALUES (?, ?, ?, ?)
    ");

    $stmt->execute([
        $nombre,
        $descripcion,
        $precio,
        $imagenNombre
    ]);

    header("Location: productos.php");
    exit;
}

/* =========================
   LISTAR PRODUCTOS
========================= */
$productos = $conn->query("
    SELECT * FROM productos
    ORDER BY id DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Administrar Productos</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">

    <h1 class="mb-4">Panel de Administración</h1>

    <!-- =========================
         FORMULARIO CREAR
    ========================== -->
    <div class="card mb-4">
        <div class="card-body">

            <form method="POST" enctype="multipart/form-data">

                <div class="mb-3">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label">Precio (€)</label>
                    <input type="number" step="0.01" name="precio" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Imagen</label>
                    <input type="file" name="imagen" class="form-control">
                </div>

                <button class="btn btn-success">
                    Añadir producto
                </button>

            </form>

        </div>
    </div>

    <!-- =========================
         LISTA PRODUCTOS
    ========================== -->
    <h2 class="mb-3">Productos actuales</h2>

    <table class="table table-striped align-middle">

        <thead>
            <tr>
                <th>ID</th>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>

        <?php foreach ($productos as $producto): ?>

            <tr>
                <td><?= $producto['id'] ?></td>

                <td>
                    <?php if (!empty($producto['imagen'])): ?>
                        <img src="../uploads/<?= htmlspecialchars($producto['imagen']) ?>" width="60">
                    <?php else: ?>
                        Sin imagen
                    <?php endif; ?>
                </td>

                <td><?= htmlspecialchars($producto['nombre']) ?></td>

                <td><?= number_format($producto['precio'], 2) ?> €</td>

                <td>
                    <a href="editar.php?id=<?= $producto['id'] ?>" class="btn btn-warning btn-sm">
                        Editar
                    </a>

                    <a href="eliminar.php?id=<?= $producto['id'] ?>"
                       class="btn btn-danger btn-sm"
                       onclick="return confirm('¿Seguro que quieres eliminar este producto?')">
                        Eliminar
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>

        </tbody>

    </table>

</div>

</body>
</html>