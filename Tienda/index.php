<?php
include('conexion.php');
session_start();

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT * FROM productos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda SISCORP</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>
    <h1>Tienda SISCORP</h1>
    <p>Bienvenido, <?php echo $_SESSION['nombre_usuario']; ?>! <a href="logout.php">Cerrar sesión</a></p>

    <div class="productos">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="producto">
                    <h2><?php echo htmlspecialchars($row['nombre']); ?></h2>
                    <img src="<?php echo htmlspecialchars($row['imagen']); ?>" alt="<?php echo htmlspecialchars($row['nombre']); ?>">
                    <p>Precio: Bs <?php echo htmlspecialchars($row['precio']); ?></p>
                    <p>Stock: <?php echo htmlspecialchars($row['cantidad']); ?></p>
                    <label for="cantidad<?php echo $row['id']; ?>">Cantidad:</label>
                    <input type="number" id="cantidad<?php echo $row['id']; ?>" name="cantidad" value="1" min="1" max="<?php echo htmlspecialchars($row['cantidad']); ?>">
                    <button class="boton" onclick="agregarAlCarrito('<?php echo htmlspecialchars($row['nombre']); ?>', <?php echo htmlspecialchars($row['precio']); ?>, document.getElementById('cantidad<?php echo $row['id']; ?>').value)">Añadir al carrito</button>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </div>
    <div class="carrito">
        <h2>Carrito de Compras</h2>
        <div id="itemsCarrito"></div>
        <p>Total: Bs <span id="precioTotal">0</span></p>
        <button class="boton" onclick="comprar()">Comprar</button>
    </div>
    <div class="historial">
        <button class="boton" onclick="window.location.href='historial_compras.php'">Historial de Compras</button>
    </div>        
    <script src="funciones/funciones.js"></script>
</body>
</html>

