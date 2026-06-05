<?php
require_once "../config/db.php";

/* =========================
   OBTENER PRODUCTO
========================= */
$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM productos WHERE id = ?");
$stmt->execute([$id]);
$producto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$producto) {
    die("Producto no encontrado");
}

/* =========================
   ACTUALIZAR PRODUCTO
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre']);
    $descripcion = trim($_POST['descripcion']);
    $precio = floatval($_POST['precio']);

    // 🔥 por defecto mantenemos la imagen actual
    $imagenNombre = $producto['imagen'];

    // si suben una nueva imagen, la reemplazamos
    if (!empty($_FILES['imagen']['name'])) {

        $directorio = "../uploads/";

        if (!is_dir($directorio)) {
            mkdir($directorio, 0777, true);
        }

        // borrar imagen anterior (opcional pero recomendable)
        if (!empty($producto['imagen']) && file_exists($directorio . $producto['imagen'])) {
            unlink($directorio . $producto['imagen']);
        }

        $imagenNombre = time() . "_" . basename($_FILES["imagen"]["name"]);
        $rutaFinal = $directorio . $imagenNombre;

        move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaFinal);
    }

    $stmt = $conn->prepare("
        UPDATE productos
        SET nombre = ?, descripcion = ?, precio = ?, imagen = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $nombre,
        $descripcion,
        $precio,
        $imagenNombre,
        $id
    ]);

    header("Location: productos.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar producto</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container py-5">

    <h1 class="mb-4">Editar producto</h1>

    <form method="POST" enctype="multipart/form-data">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control"
                   value="<?= htmlspecialchars($producto['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control" rows="3" required>
<?= htmlspecialchars($producto['descripcion']) ?>
            </textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Precio (€)</label>
            <input type="number" step="0.01" name="precio" class="form-control"
                   value="<?= $producto['precio'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Imagen actual</label><br>

            <?php if (!empty($producto['imagen'])): ?>
                <img src="../uploads/<?= htmlspecialchars($producto['imagen']) ?>" width="120">
            <?php else: ?>
                <p>Sin imagen</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Cambiar imagen</label>
            <input type="file" name="imagen" class="form-control">
        </div>

        <button class="btn btn-primary">
            Actualizar producto
        </button>

        <a href="productos.php" class="btn btn-secondary">
            Volver
        </a>

    </form>

</div>

</body>
</html>