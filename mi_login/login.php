<?php
session_start(); // Inicia la sesión

include '../mi_login/includes/db.php';

// Verifica si el usuario ya está logueado, si es así, lo redirige a home.php
if (isset($_SESSION['username'])) {
    header("Location: home.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consultar la base de datos para ver si el usuario existe
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        // Verifica si la contraseña es correcta
        if (password_verify($password, $user['password'])) {
            // Inicia la sesión y guarda el nombre de usuario
            $_SESSION['username'] = $user['username'];

            // Redirige a la página principal (home.php)
            header("Location: home.php");
            exit; // Asegura que el código después de la redirección no se ejecute
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/styles.css">
    <title>Iniciar sesión</title>
</head>
<body>

<h2>Iniciar sesión</h2>

<!-- Formulario de inicio de sesión -->
<form method="POST">
    <label for="username">Usuario:</label>
    <input type="text" name="username" id="username" required>
    <br>
    <label for="password">Contraseña:</label>
    <input type="password" name="password" id="password" required>
    <br>
    <button type="submit">Iniciar sesión</button>
</form>

<!-- Botón para registrarse -->
<p>¿No tienes una cuenta? <a href="register.php">Regístrate</a></p>

</body>
</html>