<?php
// Extraemos las categorías distintas del array de productos
$categoriasUnicas = array_unique(array_column($productos, 'nombrecat'));
?>

<div class="content-wrapper">
    <!-- Encabezado -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Presupuesto</h1>
                    <small>Crear presupuesto rápido</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <!-- Tabla izquierda: Presupuesto armado -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Presupuesto</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap" id="tablaPresupuesto">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Subtotal</th>
                                        <th>Quitar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Aquí se agregarán productos dinámicamente -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td><strong>Total</strong></td>
                                        <td colspan="2" id="totalPresupuesto">$0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="card-footer text-right">
                            <button class="btn btn-danger btn-sm mr-2" id="limpiarPresupuesto">
                                <i class="fas fa-trash"></i> Limpiar
                            </button>
                            <button class="btn btn-dark btn-sm" onclick="imprimirPDF()">
                                <i class="fas fa-file-pdf"></i> Descargar PDF
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tabla derecha: Productos -->
                <div class="col-md-6">
                    <!-- Filtro y búsqueda -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="text" id="buscadorProducto" class="form-control form-control-sm" placeholder="Buscar producto...">
                        </div>
                        <div class="col-md-6">
                            <select id="filtroCategoria" class="form-control form-control-sm">
                                <option value="todas">Todas las categorías</option>
                                <?php foreach ($categoriasUnicas as $cat): ?>
                                    <option value="<?= strtolower($cat) ?>"><?= ucfirst($cat) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Productos disponibles</h3>
                        </div>
                        <div class="card-body table-responsive p-0">
                            <table class="table table-hover text-nowrap" id="tablaProductos">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th>Agregar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($productos as $producto): ?>
                                        <tr>
                                            <td><?= ucfirst($producto['nombre']) ?></td>
                                            <td class="categoria"><?= ucfirst($producto['nombrecat']) ?></td>
                                            <td>
                                                <button class="btn btn-success btn-sm agregarProducto" data-nombre="<?= htmlspecialchars($producto['nombre']) ?>">
                                                    <i class="fas fa-plus"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div> <!-- /col-md-6 derecha -->

            </div> <!-- /row -->
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const buscador = document.getElementById('buscadorProducto');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const tablaProductos = document.querySelectorAll('#tablaProductos tbody tr');
    const tablaPresupuesto = document.querySelector('#tablaPresupuesto tbody');
    const totalPresupuesto = document.getElementById('totalPresupuesto');

    let total = 0;
    let productosPresupuesto = [];

    // 🔠 Capitalizar nombre de producto
    function capitalizarNombre(nombre) {
        return nombre
            .toLowerCase()
            .split(' ')
            .map(palabra => palabra.charAt(0).toUpperCase() + palabra.slice(1))
            .join(' ');
    }

    // 📦 Agregar producto con SweetAlert
    document.querySelectorAll('.agregarProducto').forEach(boton => {
        boton.addEventListener('click', async () => {
            const nombreOriginal = boton.dataset.nombre;
            const nombre = capitalizarNombre(nombreOriginal);

            const { value: importe } = await Swal.fire({
                title: 'Ingrese el importe',
                input: 'number',
                inputLabel: nombre,
                inputPlaceholder: 'Ej: 100.50',
                inputAttributes: {
                    min: 0,
                    step: 'any'
                },
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                cancelButtonText: 'Cancelar',
                inputValidator: (value) => {
                    if (!value) return 'Debe ingresar un valor';
                    if (isNaN(value) || parseFloat(value) < 0) return 'El valor debe ser un número positivo';
                }
            });

            if (importe !== undefined) {
                const valorUnitario = parseFloat(importe);
                let cantidad = 1;
                let subtotal = valorUnitario * cantidad; 

                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${nombre}</td>
                    <td>
                        <input type="number" value="${cantidad}" min="1" step="1" class="form-control form-control-sm cantidadPresupuesto" style="width: 70px;">
                    </td>
                    <td>$${valorUnitario.toFixed(2)}</td>
                    <td class="subtotalPresupuesto">$${subtotal.toFixed(2)}</td>
                    <td>
                        <button class="btn btn-danger btn-sm quitar"><i class="fas fa-times"></i></button>
                    </td>
                `;
                tablaPresupuesto.appendChild(fila);

                total += subtotal;
                totalPresupuesto.innerText = `$${total.toFixed(2)}`;

                productosPresupuesto.push({ producto: nombre, importe: valorUnitario,cantidad });

                // Escuchar cambio de cantidad
                const inputCantidad = fila.querySelector('.cantidadPresupuesto');
                inputCantidad.addEventListener('input', () => {
                    const nuevaCantidad = parseInt(inputCantidad.value) || 1;
                    const diferencia = (nuevaCantidad - cantidad) * valorUnitario;

                    cantidad = nuevaCantidad;
                    subtotal = cantidad * valorUnitario;

                    fila.querySelector('.subtotalPresupuesto').innerText = `$${subtotal.toFixed(2)}`;

                    total += diferencia;
                    totalPresupuesto.innerText = `$${total.toFixed(2)}`;

                    // Actualizar en array productosPresupuesto
                    const item = productosPresupuesto.find(p => p.producto === nombre && p.importe === valorUnitario);
                    if (item) item.cantidad = cantidad;
                });

                // Quitar producto
                fila.querySelector('.quitar').addEventListener('click', () => {
                    const subtotal = parseFloat(fila.querySelector('.subtotalPresupuesto').innerText.replace('$', '').replace(',', ''));
                    total -= subtotal;
                    totalPresupuesto.innerText = `$${total.toFixed(2)}`;
                    fila.remove();

                    productosPresupuesto = productosPresupuesto.filter(p => !(p.producto === nombre && p.importe === valorUnitario));
                });
            }
        });
    });

    // 🔍 Buscador en vivo
    if (buscador) {
        buscador.addEventListener('input', function () {
            const texto = this.value.toLowerCase();
            tablaProductos.forEach(fila => {
                fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
            });
        });
    }

    // 📂 Filtro de categoría
    if (filtroCategoria) {
        filtroCategoria.addEventListener('change', function () {
            const categoriaSeleccionada = this.value.toLowerCase();
            tablaProductos.forEach(fila => {
                const categoria = fila.querySelector('.categoria').textContent.toLowerCase();
                fila.style.display = (categoriaSeleccionada === 'todas' || categoria.includes(categoriaSeleccionada)) ? '' : 'none';
            });
        });
    }

    // 📄 Generar PDF
    window.imprimirPDF = async function () {
        if (productosPresupuesto.length === 0) {
            return Swal.fire('Atención', 'Agrega al menos un producto al presupuesto.', 'warning');
        }

        try {
            const response = await fetch('?c=presupuestos&a=generarPDF', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ datos_pdf: productosPresupuesto })
            });

            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);

            // Generar fecha con el mismo formato que PHP: YYYYMMDD
            const fecha = new Date();
            const fechaArchivo = fecha.getFullYear().toString() +
                            String(fecha.getMonth() + 1).padStart(2, '0') +
                            String(fecha.getDate()).padStart(2, '0') + '_' +
                            String(fecha.getHours()).padStart(2, '0') +
                            String(fecha.getMinutes()).padStart(2, '0');

            const a = document.createElement('a');
            a.href = url;
            a.download = 'presupuesto_' + fechaArchivo + '.pdf'; 
            document.body.appendChild(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(url);

        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
        }
    }

    // 🗑️ Limpiar presupuesto
    const btnLimpiar = document.getElementById('limpiarPresupuesto');
    if (btnLimpiar) {
        btnLimpiar.addEventListener('click', () => {
            if (productosPresupuesto.length === 0) {
                return Swal.fire('Atención', 'No hay productos que quitar.', 'info');
            }

            Swal.fire({
                title: '¿Limpiar presupuesto?',
                text: "Se eliminarán todos los productos.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sí, limpiar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    tablaPresupuesto.innerHTML = '';
                    total = 0;
                    productosPresupuesto = [];
                    totalPresupuesto.innerText = '$0.00';
                    Swal.fire('Listo', 'Presupuesto vacío.', 'success');
                }
            });
        });
    }

});
</script>
