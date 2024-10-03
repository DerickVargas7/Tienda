<?php 
session_start();
date_default_timezone_set('America/La_Paz');
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$usuario_id = $_SESSION['usuario_id'];
$nombre_usuario = $_SESSION['nombre_usuario'];
$archivoVentas = '../Tienda/ventas.txt';
$compras = [];
if (file_exists($archivoVentas)) {
    $contenido = file_get_contents($archivoVentas);
    $ventas = explode("=============================\n\n", $contenido);

    foreach ($ventas as $venta) {
        if (strpos($venta, "Usuario: $nombre_usuario") !== false) {
            $lineas = explode("\n", trim($venta));
            $compra = [
                'fecha' => '',
                'productos' => [],
                'total' => 0,
                'descuento' => 0,
                'total_con_descuento' => 0
            ];

            foreach ($lineas as $linea) {
                if (strpos($linea, 'Fecha y Hora:') !== false) {
                    $compra['fecha'] = trim(str_replace('Fecha y Hora:', '', $linea));
                } elseif (strpos($linea, 'Usuario:') !== false) {
                    continue; 
                } elseif (strpos($linea, 'Productos:') !== false) {
                    continue; 
                } elseif (strpos($linea, '-----------------------------') !== false) {
                    continue; 
                } elseif (strpos($linea, 'Total: Bs') !== false) {
                    $compra['total'] = floatval(trim(str_replace('Total: Bs ', '', $linea)));
                } elseif (strpos($linea, 'Descuento: Bs') !== false) {
                    $compra['descuento'] = floatval(trim(str_replace('Descuento: Bs ', '', $linea)));
                } elseif (strpos($linea, 'Total con Descuento: Bs') !== false) {
                    $compra['total_con_descuento'] = floatval(trim(str_replace('Total con Descuento: Bs ', '', $linea)));
                } elseif (!empty(trim($linea))) {
                    $compra['productos'][] = trim($linea);
                }
            }
            if ($compra['total'] > 100) {
                $compra['descuento'] = 0.10 * $compra['total'];
                $compra['total_con_descuento'] = $compra['total'] - $compra['descuento'];
            } else {
                $compra['descuento'] = 0; 
                $compra['total_con_descuento'] = $compra['total'];
            }
            
            $compras[] = $compra;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historial de Compras</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .historial {
            border: 1px solid #000;
            padding: 20px;
            max-width: 800px;
            margin: auto;
        }
        h1, h2 {
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
        .boton-volver-tienda, .boton-imprimir {
            display: block;
            width: 200px;
            padding: 10px;
            margin: 20px auto;
            text-align: center;
            color: white;
            border: none;
            font-size: 16px;
            cursor: pointer;
        }
        .boton-volver-tienda {
            background-color: #007BFF; 
        }
        .boton-volver-tienda:hover {
            background-color: #0056b3; 
        }
        .boton-imprimir {
            background-color: #4CAF50; 
        }
        .boton-imprimir:hover {
            background-color: #45a049; 
        }
    </style>
</head>
<body>
    <div class="historial">
        <h1>TIENDA SISCORP</h1>
        <h2>Historial de Compras de <?php echo htmlspecialchars($nombre_usuario); ?></h2>
        
        <div class="detalle">
            <h3>Compras Realizadas</h3>
            <?php if (empty($compras)): ?>
                <p>No has realizado ninguna compra aún.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Fecha y Hora</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Descuento</th>
                            <th>Total con Descuento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($compras as $compra): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($compra['fecha']); ?></td>
                                <td>
                                    <ul>
                                        <?php foreach ($compra['productos'] as $producto): ?>
                                            <li><?php echo htmlspecialchars($producto); ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </td>
                                <td>Bs <?php echo number_format($compra['total'], 2); ?></td>
                                <td>Bs <?php echo number_format($compra['descuento'], 2); ?></td>
                                <td>Bs <?php echo number_format($compra['total_con_descuento'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
        <button class="boton-volver-tienda" onclick="window.location.href='index.php';">Volver a la Tienda</button>
        <button class="boton-imprimir" onclick="window.print();">Imprimir</button>
    </div>
</body>
</html>
