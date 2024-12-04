<?php
session_start(); // Inicia la sesión

// Verifica si el usuario no está logueado, redirige a login.php
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit; // Asegura que el código después de la redirección no se ejecute
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
</head>
<body>

<h2>Bienvenido, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
<p>Has iniciado sesión correctamente.</p>

<!-- Enlace para cerrar sesión -->
<a href="logout.php">Cerrar sesión</a>

</body>
</html>
