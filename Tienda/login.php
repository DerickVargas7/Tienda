<?php
include('conexion.php');
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = $_POST['contrasena'];
    $stmt = $conn->prepare("SELECT id, nombre_usuario, contrasena FROM usuarios WHERE nombre_usuario = ?");
    $stmt->bind_param("s", $nombre_usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($contrasena, $row['contrasena'])) {
            
            $_SESSION['usuario_id'] = $row['id'];  
            $_SESSION['nombre_usuario'] = $nombre_usuario;
            header("Location: index.php");
            exit();
        } else {
            echo "Contraseña incorrecta.";
        }
    } else {
        echo "Usuario no encontrado.";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="css/formulario.css">
</head>
<body>
    <div class="container">
    <div class="logo-container">
        <a href="https://api.whatsapp.com/send/?phone=71767420&text&type=phone_number&app_absent=0">
            <img src="imagenes/logo2.jpg" alt="Logo" class="logo">
        </a>
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="login.php">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required>

            <button type="submit">Iniciar Sesión</button>
        </form>
        <p>¿No tienes cuenta? <a href="registro.php">Regístrate aquí</a></p>
    </div>
</body>

