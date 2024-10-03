<?php
include('conexion.php');
session_start();
date_default_timezone_set('America/La_Paz');

if (!isset($_SESSION['usuario_id']) || !isset($_SESSION['carrito'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nombre_usuario = $_SESSION['nombre_usuario'];
$fecha = date("Y-m-d H:i:s");


$carrito = $_SESSION['carrito'];
$precioTotal = 0;
$detalle = [];
foreach ($carrito as $item) {
    $nombre = isset($item['nombre']) ? $item['nombre'] : 'Desconocido';
    $precio = isset($item['precio']) ? $item['precio'] : 0;
    $cantidad = isset($item['cantidad']) ? $item['cantidad'] : 0;

    $precioTotal += $precio * $cantidad;
    $detalle[] = [
        'nombre' => $nombre,
        'precio' => $precio,
        'cantidad' => $cantidad,
        'total' => $precio * $cantidad
    ];
}

$descuento = $precioTotal > 100 ? 0.10 * $precioTotal : 0;
$totalConDescuento = $precioTotal - $descuento;


$_SESSION['carrito'] = [];


$archivoVentas = '../Tienda/ventas.txt'; 
$file = fopen($archivoVentas, 'a'); 

if ($file) {
    fwrite($file, "Fecha y Hora: $fecha\n");
    fwrite($file, "Usuario: $nombre_usuario\n");
    fwrite($file, "Productos:\n");
    foreach ($detalle as $item) {
        $lineaProducto = $item['nombre'] . " x" . $item['cantidad'] . "\n";
        fwrite($file, $lineaProducto);
    }
    fwrite($file, "-----------------------------\n");
    fwrite($file, "Total: Bs " . number_format($precioTotal, 2) . "\n");
    fwrite($file, "Descuento: Bs " . number_format($descuento, 2) . "\n");
    fwrite($file, "Total con Descuento: Bs " . number_format($totalConDescuento, 2) . "\n");
    fwrite($file, "=============================\n\n");
    
    
    fclose($file);
} else {
    echo "No se pudo registrar la venta en el archivo de texto.";
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Factura de Compra</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .factura {
            border: 1px solid #000;
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }
        h1, h2, h3 {
            margin: 0 0 10px;
        }
        .detalle {
            margin: 20px 0;
        }
        .detalle table {
            width: 100%;
            border-collapse: collapse;
        }
        .detalle th, .detalle td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .detalle th {
            background-color: #f2f2f2;
        }
        .boton-imprimir, .boton-volver-tienda {
            display: block;
            width: 200px;
            padding: 10px;
            margin: 20px auto;
            text-align: center;
            background-color: #4CAF50;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
        .boton-imprimir:hover, .boton-volver-tienda:hover {
            background-color: #45a049;
        }
        .boton-volver-tienda {
            background-color: #007BFF; 
        }
        .boton-volver-tienda:hover {
            background-color: #0056b3; 
        }
    </style>
</head>
<body>
    <div class="factura">
        <h1>TIENDA SISCORP</h1>
        <h2>Factura de Compra</h2>
        <p><strong>USUARIO:</strong> <?php echo htmlspecialchars($nombre_usuario); ?></p>
        <p><strong>Fecha:</strong> <?php echo htmlspecialchars($fecha); ?></p>
        <div class="detalle">
            <h3>Detalle de la Compra</h3>
            <table>
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Precio Unitario</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($detalle as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['nombre']); ?></td>
                            <td>Bs <?php echo number_format($item['precio'], 2); ?></td>
                            <td><?php echo htmlspecialchars($item['cantidad']); ?></td>
                            <td>Bs <?php echo number_format($item['total'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p><strong>Subtotal:</strong> Bs <?php echo number_format($precioTotal, 2); ?></p>
        <p><strong>Descuento (Compras mayores a Bs 100: -10% de DESCUENTO):</strong> Bs <?php echo number_format($descuento, 2); ?></p>
        <p><strong>Total con Descuento:</strong> Bs <?php echo number_format($totalConDescuento, 2); ?></p>
        <button class="boton-imprimir" onclick="window.print();">Imprimir Factura</button>
        <button class="boton-volver-tienda" onclick="window.location.href='index.php';">Volver a la Tienda</button>
    </div>
</body>
</html>
