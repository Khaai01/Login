<?php
header("Access-Control-Allow-Origin: *");  
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");  
header("Access-Control-Allow-Headers: Content-Type, Authorization");

include 'db.php';

$data = json_decode(file_get_contents("php://input"));

if (isset($data->username) && isset($data->email) && isset($data->password)) {
    $username = $data->username;
    $email = $data->email;
    $password = $data->password;

    // Verificar si el usuario ya existe
    $sql_check = "SELECT * FROM usuarios WHERE username = ? OR email = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("ss", $username, $email);
    $stmt_check->execute();
    $stmt_check->store_result();

    if ($stmt_check->num_rows > 0) {
        // El usuario o el correo ya están registrados
        echo json_encode([
            'success' => false,
            'message' => 'El nombre de usuario o el correo ya están registrados.'
        ]);
    } else {
        // Registrar el usuario
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO usuarios (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo json_encode([
                'success' => true,
                'message' => '¡Registro exitoso! Ahora puedes iniciar sesión.'
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Error al registrar: ' . $stmt->error
            ]);
        }

        $stmt->close();
    }

    $stmt_check->close();
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Datos incompletos.'
    ]);
}
?>

