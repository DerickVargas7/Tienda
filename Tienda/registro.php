<?php
include('conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre_usuario = $_POST['nombre_usuario'];
    $contrasena = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nombre_usuario, contrasena) VALUES ('$nombre_usuario', '$contrasena')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro exitoso. <a href='login.php'>Iniciar sesión</a>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <link rel="stylesheet" href="css/formulario.css">
</head>
<body>
    <div class="container">
        <h2>Registrarse</h2>
        <form method="POST" action="registro.php">
            <label for="nombre_usuario">Nombre de Usuario:</label>
            <input type="text" name="nombre_usuario" required>

            <label for="contrasena">Contraseña:</label>
            <input type="password" name="contrasena" required>

            <button type="submit">Registrarse</button>
        </form>
        <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
    </div>
</body>
</html>

