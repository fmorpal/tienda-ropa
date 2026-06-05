<?php
session_start();

// Usuario y contraseña simples (puedes cambiarlo luego por BD)
$usuario_correcto = "admin";
$pass_correcta = "1234";

if (isset($_POST['login'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    if ($user === $usuario_correcto && $pass === $pass_correcta) {
        $_SESSION['admin'] = true;
        header("Location: panel.php");
        exit;
    } else {
        $error = "Credenciales incorrectas";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login Admin</title>
</head>
<body>

<h2>Acceso administrador</h2>

<form method="POST">
    <input type="text" name="user" placeholder="Usuario"><br><br>
    <input type="password" name="pass" placeholder="Contraseña"><br><br>
    <button type="submit" name="login">Entrar</button>
</form>

<?php if (!empty($error)) echo "<p style='color:red'>$error</p>"; ?>

</body>
</html>