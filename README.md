# Tienda
Interfaz de una tienda online con login e inventario
Este proyecto es una tienda en línea desarrollada en PHP con una base de datos MySQL. El sistema permite a los usuarios registrarse, iniciar sesión, navegar productos, añadir productos al carrito y realizar compras.

# Contenido del proyecto
index.php: Página principal que muestra los productos disponibles.

login.php: Página de inicio de sesión para los usuarios registrados.

registro.php: Página de registro de nuevos usuarios.

procesar_compra.php: Lógica para procesar la compra de los productos en el carrito.

generar_factura.php: Archivo que genera una factura detallada después de realizar una compra.

historial_compras.php: Página donde los usuarios pueden ver su historial de compras.

conexion.php: Archivo de conexión a la base de datos.

funciones/: Carpeta que contiene funciones auxiliares utilizadas en diferentes partes del sistema.

css/: Carpeta que contiene los estilos CSS para el diseño de la tienda.

imagenes/: Carpeta donde se almacenan las imágenes del proyecto (logo, fondo, etc.).

ventas.txt: Archivo de registro de ventas.

# Instalación

Copia el proyecto a tu servidor local (por ejemplo, usando XAMPP o WAMP).

Configura tu base de datos en MySQL y actualiza las credenciales en el archivo conexion.php.

Importa el archivo SQL de la base de datos (si está disponible) para crear las tablas necesarias.

Abre tu navegador y navega a http://localhost/Tienda/index.php para ver la tienda.

# Funcionalidades

Registro e inicio de sesión de usuarios.

Gestión de carrito de compras.

Compra de productos con actualización del stock en la base de datos.

Generación de facturas con detalles de la compra.

Historial de compras para los usuarios registrados.

Diseño responsivo utilizando CSS.

# Requisitos

Servidor web con soporte para PHP (por ejemplo, XAMPP, WAMP).

Base de datos MySQL.

Navegador web moderno.

Instrucciones adicionales

Asegúrate de que los permisos y las direcciones de las carpetas y archivos sean correctos en tu servidor para evitar problemas de acceso.
