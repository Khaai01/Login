<?php
$host = "localhost";
$user = "root";
$password = ""; // Deja vacío si estás usando XAMPP/MAMP sin contraseñas.
$dbname = "login1";

// Conexión a la base de datos
$conn = new mysqli($host, $user, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>

