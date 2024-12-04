<?php 
include '../mi_login/includes/db.php';  

function guardarEnJson($usuario) {     
    $file = 'usuarios.json';     
    $usuarios = [];      

    if (file_exists($file)) {         
        $contenido = file_get_contents($file);         
        $usuarios = json_decode($contenido, true);          

        if ($usuarios === null) {             
            echo "Error al decodificar el JSON.<br>";         
        }     
    }      

    $usuarios[] = [         
        'username' => $usuario['username'],         
        'email' => $usuario['email']     
    ];      

    if (file_put_contents($file, json_encode($usuarios, JSON_PRETTY_PRINT))) {         
        echo "Datos guardados en JSON con éxito.<br>";     
    } else {         
        echo "Error al guardar en JSON.<br>";     
    } 
}  

if ($_SERVER["REQUEST_METHOD"] == "POST") {     
    $username = $_POST['username'];     
    $password = $_POST['password'];     
    $email = $_POST['email'];      

    $sql_check = "SELECT * FROM usuarios WHERE username = ?";     
    $stmt_check = $conn->prepare($sql_check);     
    $stmt_check->bind_param("s", $username);     
    $stmt_check->execute();     
    $stmt_check->store_result();      

    if ($stmt_check->num_rows > 0) {         
        $error = "El nombre de usuario ya está registrado.";     
    } else {         
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);          

        $sql = "INSERT INTO usuarios (username, password, email) VALUES (?, ?, ?)";         
        $stmt = $conn->prepare($sql);         
        $stmt->bind_param("sss", $username, $hashed_password, $email);          

        if ($stmt->execute()) {             
            guardarEnJson(['username' => $username, 'email' => $email]);              
            header("Location: login.php");             
            exit;         
        } else {             
            $error = "Error al registrar: " . $stmt->error;         
        }          

        $stmt->close();     
    }      

    $stmt_check->close(); 
} 
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,800" rel="stylesheet">
    <link rel="stylesheet" href="assets/styles.css">
</head>
<body>
<div class="container">
    <div class="form-container sign-up-container">
        <form method="POST">
            <h1>Crear cuenta</h1>
            <?php if(isset($error)): ?>
                <p style="color: red;"><?php echo $error; ?></p>
            <?php endif; ?>
            <input type="text" name="username" placeholder="Usuario" required>
            <input type="email" name="email" placeholder="Correo electrónico" required>
            <input type="password" name="password" placeholder="Contraseña" required>
            <button type="submit">Registrar</button>
        </form>
    </div>
    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>¡Bienvenido de vuelta!</h1>
                <p>¿Ya tienes una cuenta? Inicia sesión aquí</p>
                <a href="login.php" class="ghost">Iniciar sesión</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('.container');
    container.classList.add('right-panel-active');
});
</script>
</body>
</html>