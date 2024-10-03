<?php
include('conexion.php');
session_start();
date_default_timezone_set('America/La_Paz');
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $data = json_decode(file_get_contents('php://input'), true);
    $compras = $data['compras']; 
    $total = $data['total']; 
    $descuento = $data['descuento']; 

    $usuario_id = $_SESSION['usuario_id'];
    $conn->begin_transaction();

    try {
        foreach ($compras as $compra) {
            $id_producto = $compra['id'];
            $cantidad_comprada = $compra['cantidad'];

            
            $stmt = $conn->prepare("SELECT cantidad FROM productos WHERE id = ?");
            $stmt->bind_param('i', $id_producto);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $cantidad_actual = $row['cantidad'];

                if ($cantidad_actual >= $cantidad_comprada) {
                    $nueva_cantidad = $cantidad_actual - $cantidad_comprada;
                    $stmt_update = $conn->prepare("UPDATE productos SET cantidad = ? WHERE id = ?");
                    $stmt_update->bind_param('ii', $nueva_cantidad, $id_producto);                    
                    if (!$stmt_update->execute()) {
                        throw new Exception("Error al actualizar el stock: " . $conn->error);
                    }
                } else {
                    throw new Exception("No hay suficiente stock para el producto con ID $id_producto.");
                }
            } else {
                throw new Exception("Producto con ID $id_producto no encontrado.");
            }
        }

        
        $fecha_actual = date("Y-m-d H:i:s");
        $stmt_insert = $conn->prepare("INSERT INTO ventas (usuario_id, total, fecha) VALUES (?, ?, ?)");
        $stmt_insert->bind_param('ids', $usuario_id, $total, $fecha_actual);

        if ($stmt_insert->execute()) {
            $_SESSION['carrito'] = $compras; 
            $conn->commit();
            echo "Compra procesada con éxito";
        } else {
            throw new Exception("Error al registrar la venta: " . $conn->error);
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo $e->getMessage();
    }
}
?>
