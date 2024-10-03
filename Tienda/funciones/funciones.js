let carrito = [];
let precioTotal = 0;

function agregarAlCarrito(nombre, precio, cantidad) {
    const item = carrito.find(item => item.nombre === nombre);
    if (item) {
        item.cantidad += parseInt(cantidad);
    } else {
        carrito.push({ nombre, precio, cantidad: parseInt(cantidad) });
    }
    actualizarCarrito();
}

function actualizarCarrito() {
    const itemsCarrito = document.getElementById('itemsCarrito');
    itemsCarrito.innerHTML = '';
    precioTotal = 0;

    carrito.forEach(item => {
        itemsCarrito.innerHTML += `<div class="item-carrito">
            ${item.nombre} - $${item.precio} x ${item.cantidad} <button onclick="eliminarDelCarrito('${item.nombre}')">Eliminar</button>
        </div>`;
        precioTotal += item.precio * item.cantidad;
    });

    document.getElementById('precioTotal').textContent = precioTotal.toFixed(2);
}

function eliminarDelCarrito(nombre) {
    carrito = carrito.filter(item => item.nombre !== nombre);
    actualizarCarrito();
}

function comprar() {
    if (carrito.length > 0) {
        let totalCompra = carrito.reduce((total, item) => total + (item.precio * item.cantidad), 0);
        let descuento = 0;
        if (totalCompra > 100) {
            descuento = totalCompra * 0.10;
            totalCompra -= descuento;
        }

        const compras = carrito.map(item => ({
            nombre: item.nombre, 
            precio: item.precio, 
            cantidad: item.cantidad,
            id: obtenerIdDelProducto(item.nombre) 
        }));

        fetch('procesar_compra.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ compras, total: totalCompra, descuento: descuento })
        })
        .then(response => response.text())
        .then(data => {
            console.log("Respuesta del servidor:", data); 
            if (data.includes("Compra procesada con éxito")) {
                window.location.href = 'generar_factura.php';
            } else {
                alert(data);
            }
            carrito = [];
            actualizarCarrito();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Hubo un problema al procesar la compra.');
        });
    } else {
        alert('El carrito está vacío.');
    }
}

const ids = {
    'COCA-COLA': 1,
    'CERVEZA CORONA': 2,
    'BOM BOM BUM': 3,
    'CHOCOLATE BARRA': 4,
    'PAPAFRITA': 5,
    'GALLETAS CON CHIPS': 6,
    'GELATINA YELI': 7,
    'YOGURT DE COCO': 8,
    'AGUA': 9,
    'JUGO NATURAL': 10,
    'RED BULL': 12,
    'LECHE EN CAJA': 11,
};

function obtenerIdDelProducto(nombre) {
    return ids[nombre];
}
