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
                    <small>Crear presupuesto</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="content" >
        <div class="container-fluid">
            <div class="form-group px-2 pt-2">
                <label for="inputPropietario">Propietario:</label>
                <input type="text" class="form-control form-control-sm" id="inputPropietario"
                    placeholder="Ej: Juan Pérez" autocomplete="off">
            </div>
            <div class="form-group px-2">
                <label for="inputVehiculo">Vehículo:</label>
                <input type="text" class="form-control form-control-sm" id="inputVehiculo"
                    placeholder="Ej: Honda CG 150cc" autocomplete="off">
            </div>
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
                                        <td colspan="3" id="totalPresupuesto">$0</td>
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
                            <input type="text" id="buscadorProducto" class="form-control form-control-sm"
                                placeholder="Buscar producto..." autocomplete="off">
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
                                            <button class="btn btn-success btn-sm agregarProducto"
                                                data-nombre="<?= htmlspecialchars($producto['nombre']) ?>">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <div id="paginacionProductos" class="d-flex justify-content-center mt-2 mb-2"></div>
                        </div>
                    </div>

                </div> <!-- /col-md-6 derecha -->

            </div> <!-- /row -->
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ——— UTILIDADES ———
    function capitalizar(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
    function capitalizarNombre(nombre) {
        return nombre
            .toLowerCase()
            .split(' ')
            .map(p => capitalizar(p))
            .join(' ');
    }

    // ——— ELEMENTOS DOM ———
    const buscador = document.getElementById('buscadorProducto');
    const filtroCategoria = document.getElementById('filtroCategoria');
    const contenedorTabla = document.querySelector('#tablaProductos tbody');
    const paginacionDiv = document.getElementById('paginacionProductos');
    const tablaPresupuesto = document.querySelector('#tablaPresupuesto tbody');
    const totalPresupuesto = document.getElementById('totalPresupuesto');

    // ——— ESTADO GLOBAL ———
    let total = 0;
    let productosPresupuesto = [];

    // ——— PAGINACIÓN ———
    const filasOriginales = Array.from(document.querySelectorAll('#tablaProductos tbody tr'));
    let productosFiltrados = [...filasOriginales];
    const productosPorPagina = 6;
    let currentPage = 1;

    function mostrarProductos(lista) {
        contenedorTabla.innerHTML = '';
        lista.forEach(fila => contenedorTabla.appendChild(fila));
    }

    function mostrarProductosPagina(pagina) {
        const inicio = (pagina - 1) * productosPorPagina;
        const fin = inicio + productosPorPagina;
        mostrarProductos(productosFiltrados.slice(inicio, fin));
    }

    function cambiarPagina(pagina) {
        const totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
        if (pagina < 1 || pagina > totalPaginas) return;
        currentPage = pagina;
        mostrarProductosPagina(currentPage);
        generarPaginacion();
    }

    function generarPaginacion() {
        const totalPaginas = Math.ceil(productosFiltrados.length / productosPorPagina);
        paginacionDiv.innerHTML = '';

        // Botón "Anterior"
        const prev = document.createElement('button');
        prev.textContent = 'Anterior';
        prev.className = 'btn btn-secondary btn-sm mr-1';
        prev.disabled = currentPage === 1;
        prev.addEventListener('click', () => cambiarPagina(currentPage - 1));
        paginacionDiv.appendChild(prev);

        // Botones de página
        for (let i = 1; i <= totalPaginas; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `btn btn-secondary btn-sm mx-1 ${i === currentPage ? 'active' : ''}`;
            btn.addEventListener('click', () => cambiarPagina(i));
            paginacionDiv.appendChild(btn);
        }

        // Botón "Siguiente"
        const next = document.createElement('button');
        next.textContent = 'Siguiente';
        next.className = 'btn btn-secondary btn-sm ml-1';
        next.disabled = currentPage === totalPaginas;
        next.addEventListener('click', () => cambiarPagina(currentPage + 1));
        paginacionDiv.appendChild(next);
    }

    function aplicarFiltros() {
        const texto = buscador.value.trim().toLowerCase();
        const categoria = filtroCategoria.value.toLowerCase();
        productosFiltrados = filasOriginales.filter(fila => {
            const nombre = fila.cells[0].textContent.toLowerCase();
            const cat    = fila.querySelector('.categoria').textContent.toLowerCase();
            return nombre.includes(texto) && (categoria === 'todas' || cat.includes(categoria));
        });
        currentPage = 1;
        mostrarProductosPagina(currentPage);
        generarPaginacion();
    }

    // ——— EVENTOS DE FILTRADO & PAGINACIÓN ———
    buscador.addEventListener('input', aplicarFiltros);
    filtroCategoria.addEventListener('change', aplicarFiltros);

    // Inicializa paginación
    aplicarFiltros();

    // ——— AGREGAR PRODUCTO AL PRESUPUESTO ———
    document.querySelectorAll('.agregarProducto').forEach(boton => {
        boton.addEventListener('click', async () => {
            const nombreOriginal = boton.dataset.nombre;
            const nombre = capitalizarNombre(nombreOriginal);
            const { value: importe } = await Swal.fire({
                title: 'Ingrese el importe',
                input: 'number',
                inputLabel: nombre,
                inputPlaceholder: 'Ej: 100.50',
                inputAttributes: { min: 0, step: 'any' },
                showCancelButton: true,
                confirmButtonText: 'Agregar',
                cancelButtonText: 'Cancelar',
                inputValidator: v => {
                    if (!v) return 'Debe ingresar un valor';
                    if (isNaN(v) || parseFloat(v) <= 0) return 'Ingrese un número mayor a 0';
                }
            });
            if (importe !== undefined) {
                const valorUnitario = parseFloat(importe);
                let cantidad = 1;
                let subtotal = valorUnitario;
                const fila = document.createElement('tr');
                fila.innerHTML = `
                    <td>${nombre}</td>
                    <td>
                        <input type="number" value="1" min="1" step="1"
                            class="form-control form-control-sm cantidadPresupuesto"
                            style="width:70px">
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
                productosPresupuesto.push({ producto: nombre, importe: valorUnitario, cantidad });

                // Cambiar cantidad
                fila.querySelector('.cantidadPresupuesto').addEventListener('input', e => {
                    const nueva = parseInt(e.target.value) || 1;
                    const diff = (nueva - cantidad) * valorUnitario;
                    cantidad = nueva;
                    subtotal = cantidad * valorUnitario;
                    fila.querySelector('.subtotalPresupuesto').innerText = `$${subtotal.toFixed(2)}`;
                    total += diff;
                    totalPresupuesto.innerText = `$${total.toFixed(2)}`;
                    productosPresupuesto.find(p => p.producto === nombre && p.importe === valorUnitario).cantidad = cantidad;
                });

                // Quitar línea
                fila.querySelector('.quitar').addEventListener('click', () => {
                    total -= subtotal;
                    totalPresupuesto.innerText = `$${total.toFixed(2)}`;
                    fila.remove();
                    productosPresupuesto = productosPresupuesto.filter(p => !(p.producto === nombre && p.importe === valorUnitario));
                });
            }
        });
    });

    // ——— GENERAR PDF ———
    window.imprimirPDF = async function() {
        if (!productosPresupuesto.length) {
            return Swal.fire('Atención', 'Agrega al menos un producto.', 'warning');
        }
        const propietario = document.getElementById('inputPropietario').value.trim() || 'No especificado';
        const vehiculo    = document.getElementById('inputVehiculo').value.trim() || 'No especificado';
        try {
            const res = await fetch('?c=presupuestos&a=generarPDF', {
                method: 'POST',
                headers: { 'Content-Type':'application/json' },
                body: JSON.stringify({ datos_pdf:{ productos:productosPresupuesto, propietario, vehiculo } })
            });
            const blob = await res.blob();
            const url  = URL.createObjectURL(blob);
            const now  = new Date();
            const stamp = now.getFullYear().toString()
                + String(now.getMonth()+1).padStart(2,'0')
                + String(now.getDate()).padStart(2,'0') + '_'
                + String(now.getHours()).padStart(2,'0')
                + String(now.getMinutes()).padStart(2,'0');
            const a = document.createElement('a');
            a.href = url;
            a.download = `presupuesto_${stamp}.pdf`;
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        } catch (err) {
            console.error(err);
            Swal.fire('Error', 'No se pudo generar el PDF.', 'error');
        }
    };

    // ——— LIMPIAR PRESUPUESTO ———
    document.getElementById('limpiarPresupuesto').addEventListener('click', () => {
        if (!productosPresupuesto.length) {
            return Swal.fire('Atención', 'No hay productos que quitar.', 'info');
        }
        Swal.fire({
            title: '¿Limpiar presupuesto?',
            text: 'Se eliminarán todos los productos.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, limpiar',
            cancelButtonText: 'Cancelar'
        }).then(res => {
            if (res.isConfirmed) {
                tablaPresupuesto.innerHTML = '';
                total = 0;
                productosPresupuesto = [];
                totalPresupuesto.innerText = '$0.00';
                Swal.fire('Listo', 'Presupuesto vacío.', 'success');
            }
        });
    });
});
</script>
