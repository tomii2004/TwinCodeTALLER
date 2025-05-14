<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Trabajos</h1>
                    <small>Definir Trabajos</small>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <input type="text" id="buscadorTrabajos" class="form-control form-control-sm" placeholder="Buscar trabajos...">
                </div>
                <div class="col-md-3">
                    <a href="?c=trabajos&a=nuevoTrabajo" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo Trabajo
                    </a>
                </div>
            </div>
            <!-- Filtros -->
            <div class="row mb-3">
                <!-- Filtro por fecha (rango) -->
                <div class="col-md-5">
                    <label for="filtroFechaInicio" class="form-label">Fecha Inicio</label>
                    <input type="date" id="filtroFechaInicio" class="form-control form-control-sm">
                </div>

                <div class="col-md-5">
                    <label for="filtroFechaFin" class="form-label">Fecha Fin</label>
                    <input type="date" id="filtroFechaFin" class="form-control form-control-sm">
                </div>



            </div>

            <!-- Filtro por rango de fecha -->
            <div class="row mb-3">
                <div class="col-md-10">
                    <button id="filtroRangoFecha" class="btn btn-info btn-sm w-100">Filtrar por Rango de Fecha</button>
                </div>
                <div class="col-md-2">
                    <button id="limpiarFiltro" class="btn btn-danger btn-sm">Limpiar Filtro</button>
                </div>
            </div>



            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="tablaTrabajos">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Vehiculo</th>
                                <th>Fecha</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="trabajosTableBody">

                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Paginación -->
            <div id="pagination" class="d-flex justify-content-center mt-2" style="margin-top: -20px; margin-bottom: 20px;">
                <!-- Los botones de paginación serán insertados aquí mediante JavaScript -->
            </div>
        </div>
    </section>
    <div id="modalesContainer"></div>

</div>

<script>
    // Variables globales
    let trabajosOriginales = <?= json_encode($trabajos); ?>; // Traída desde PHP
    let trabajosFiltrados = [...trabajosOriginales];
    let trabajosPorPagina = 5;
    let currentPage = 1;
    let detalleTrabajoActual = null;

    function capitalizar(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    async function showDetails(id) {
        // Eliminamos cualquier modal previo
        document.getElementById(`m${id}`)?.remove();

        try {
            // Encontramos el trabajo para obtener Cliente y Vehículo
            const trabajo = trabajosOriginales.find(t => t.ID_trabajo === id);
            if (!trabajo) throw new Error("Trabajo no encontrado");

            // Traemos los productos del trabajo
            const res = await fetch(`?c=trabajos&a=ObtenerProductosTrabajoJSON&ID_trabajo=${id}`);
            const productosRaw = await res.json();

            // Normalizamos los productos
            const productos = productosRaw.map(p => ({
                ID_producto: p.ID_producto,
                NombreProducto: capitalizar(p.NombreProducto),
                Cantidad: p.Cantidad,
                PrecioUnitario: parseFloat(p.PrecioUnitario),
                Subtotal: parseFloat((p.Cantidad * p.PrecioUnitario).toFixed(2))
            }));

            // Guardamos los datos en la variable global
            // …
            detalleTrabajoActual = {
                id_trabajo: trabajo.ID_trabajo,
                fecha: trabajo.Fecha,
                total: parseFloat(trabajo.Total),
                cliente: capitalizar(trabajo.Cliente),
                vehiculo: capitalizar(trabajo.Vehiculo),
                nota: trabajo.Nota,
                productos
            };
            console.log(detalleTrabajoActual.productos);
            // Construimos el HTML del modal
            let modalHTML = `
            <div class="modal fade" id="m${id}" tabindex="-1" role="dialog" aria-labelledby="modalLabel${id}" aria-hidden="true">
              <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel${id}">Detalles del Trabajo</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body">
                    ${productos.length > 0
                      ? `<table class="table table-bordered">
                          <thead>
                            <tr>
                              <th>Producto</th><th>Cantidad</th><th>Precio Unitario</th><th>Subtotal</th>
                            </tr>
                          </thead>
                          <tbody>
                            ${productos.map((p, i) => `
                            <tr>
                                <td>${p.NombreProducto}</td>
                                <td>${p.Cantidad}</td>
                                <td>
                                <input type="number" min="0" step="0.01" class="form-control form-control-sm precio-unitario" 
                                    value="${p.PrecioUnitario.toFixed(2)}" 
                                    data-index="${i}" 
                                    oninput="actualizarSubtotal(${i})">
                                </td>
                                <td class="subtotal" data-index="${i}">$${p.Subtotal.toFixed(2)}</td>
                            </tr>
                            `).join('')}
                          </tbody>
                          <tfoot>
                            <tr>
                                <th colspan="3" class="text-right">Total</th>
                                <th id="totalGeneral">$${productos.reduce((s,p) => s + p.Subtotal, 0).toFixed(2)}</th>
                            </tr>
                          </tfoot>
                        </table>`
                      : '<p>No hay productos para mostrar.</p>'}
                    <div class="alert alert-secondary text-start mt-3" role="alert" style="white-space:">
                        <strong>Nota:</strong><br>
                        ${detalleTrabajoActual.nota}
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button class="btn btn-success" onclick="guardarCambiosImportes(${id})">Guardar Cambios</button>
                    <button class="btn btn-danger" onclick="generarPresupuestoPDF()">Generar PDF</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                  </div>
                </div>
              </div>
            </div>`;

            // Insertamos y mostramos el modal
            document.getElementById('modalesContainer').insertAdjacentHTML('beforeend', modalHTML);
            $(`#m${id}`).modal('show');

            document.querySelectorAll(`#m${id} input.precio-unitario`).forEach(input => {
                input.addEventListener('input', function () {
                    const index = this.getAttribute('data-index');
                    actualizarSubtotal(index);
                });
            });

        } catch (error) {
            console.error('Error al obtener detalles del trabajo:', error);
            alert('No se pudieron cargar los detalles del trabajo.');
        }
    }

    function actualizarSubtotal(index) {
        const input = document.querySelector(`input.precio-unitario[data-index="${index}"]`);
        const precio = parseFloat(input.value);
        const cantidad = detalleTrabajoActual.productos[index].Cantidad;
        const nuevoSubtotal = precio * cantidad;

        // Actualizar en JS
        detalleTrabajoActual.productos[index].PrecioUnitario = precio;
        detalleTrabajoActual.productos[index].Subtotal = nuevoSubtotal;

        // Actualizar en HTML
        const subtotalElem = document.querySelector(`.subtotal[data-index="${index}"]`);
        subtotalElem.textContent = `$${nuevoSubtotal.toFixed(2)}`;

        // Actualizar total general
        const total = detalleTrabajoActual.productos.reduce((s, p) => s + p.Subtotal, 0);
        document.querySelector(`#m${detalleTrabajoActual.id_trabajo} #totalGeneral`).textContent = `$${total.toFixed(2)}`;
        detalleTrabajoActual.total = total;
    }
    function guardarCambiosImportes(id) {
        console.log("Guardando cambios para el trabajo ID:", id);
        const payload = {
            id_trabajo: id,
            productos: detalleTrabajoActual.productos.map(p => ({
                id_producto: p.ID_producto,
                precio: p.PrecioUnitario
            }))
        };
        console.log(JSON.stringify(payload));
        fetch('?c=trabajos&a=actualizarImportesTrabajo', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                Swal.fire({
                icon: 'success',
                title: '¡Importes actualizados!',
                text: 'Importes actualizados correctamente',
                confirmButtonText: 'OK'
                }).then(() => {
                // Cerrás el modal
                $(`#m${id}`).modal('hide');
                
                // 🔁 ACTUALIZAMOS EL TOTAL EN trabajosOriginales Y trabajosFiltrados
                const trabajo = trabajosOriginales.find(t => t.ID_trabajo === id);
                if (trabajo) trabajo.Total = detalleTrabajoActual.total.toFixed(2);

                const trabajoFiltrado = trabajosFiltrados.find(t => t.ID_trabajo === id);
                if (trabajoFiltrado) trabajoFiltrado.Total = detalleTrabajoActual.total.toFixed(2);

                // 🔁 ACTUALIZAMOS LA TABLA SIN RECARGAR TODO
                mostrarTrabajosPagina(currentPage);
                });

            } else {
                Swal.fire({
                icon: 'error',
                title: 'Error al actualizar',
                text: res.error,
                confirmButtonText: 'Cerrar'
                });
            }
            })
        .catch(err => {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo guardar los cambios.',
                confirmButtonText: 'Cerrar'
            });
        });

    }

    function mostrarTrabajos(trabajos) {
        const tbody = document.getElementById('trabajosTableBody');
        tbody.innerHTML = '';
        trabajos.forEach(trabajo => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td>${trabajo.ID_trabajo}</td>
                <td>${capitalizar(trabajo.Cliente)}</td>
                <td>${capitalizar(trabajo.Vehiculo)}</td>
                <td>${trabajo.Fecha}</td>
                <td>${trabajo.Total}</td>
                <td>
                  <button class="btn btn-info btn-sm" onclick="showDetails(${trabajo.ID_trabajo})">
                    <i class="fas fa-eye"></i> Detalles
                  </button>
                  <button class="btn btn-danger btn-sm ms-1" onclick="confirmDelete(${trabajo.ID_trabajo})">
                    <i class="fas fa-trash"></i> Eliminar
                  </button>
                </td>`;
            tbody.appendChild(tr);
        });
    }

    function mostrarTrabajosPagina(pagina) {
        const start = (pagina - 1) * trabajosPorPagina;
        const end = start + trabajosPorPagina;
        mostrarTrabajos(trabajosFiltrados.slice(start, end));
    }

    function confirmDelete(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡Este trabajo se eliminará de forma permanente!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
        eliminarTrabajo(id);
        }
    });
    }

    /**
     * Llama al backend para eliminar el trabajo y refresca la tabla.
     */
    function eliminarTrabajo(id) {
    fetch(`?c=trabajos&a=eliminarTrabajo`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ id_trabajo: id })
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
        Swal.fire({
            icon: 'success',
            title: 'Eliminado',
            text: 'El trabajo ha sido eliminado.',
            timer: 1500,
            showConfirmButton: false
        });
        // Quitamos del array original y filtrado
        trabajosOriginales = trabajosOriginales.filter(t => t.ID_trabajo !== id);
        trabajosFiltrados = trabajosFiltrados.filter(t => t.ID_trabajo !== id);
        // Re-render de la página actual (o ir a la anterior si era el último)
        const totalPages = Math.ceil(trabajosFiltrados.length / trabajosPorPagina);
        if (currentPage > totalPages) currentPage = totalPages;
        mostrarTrabajosPagina(currentPage);
        generarPaginacion();
        } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: res.error || 'No se pudo eliminar el trabajo.'
        });
        }
    })
    .catch(err => {
        console.error(err);
        Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo eliminar el trabajo.'
        });
    });
    }

    function generarPaginacion() {
        const div = document.getElementById('pagination');
        div.innerHTML = '';
        const totalPages = Math.ceil(trabajosFiltrados.length / trabajosPorPagina);

        const prev = document.createElement('button');
        prev.textContent = 'Anterior';
        prev.className = 'btn btn-secondary btn-sm';
        prev.disabled = currentPage === 1;
        prev.onclick = () => cambiarPagina(currentPage - 1);
        div.appendChild(prev);

        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.textContent = i;
            btn.className = `btn btn-secondary btn-sm mx-1 ${i === currentPage ? 'active' : ''}`;
            btn.onclick = () => cambiarPagina(i);
            div.appendChild(btn);
        }

        const next = document.createElement('button');
        next.textContent = 'Siguiente';
        next.className = 'btn btn-secondary btn-sm';
        next.disabled = currentPage === totalPages;
        next.onclick = () => cambiarPagina(currentPage + 1);
        div.appendChild(next);
    }

    function cambiarPagina(pagina) {
        const totalPages = Math.ceil(trabajosFiltrados.length / trabajosPorPagina);
        if (pagina < 1 || pagina > totalPages) return;
        currentPage = pagina;
        mostrarTrabajosPagina(currentPage);
        generarPaginacion();
    }

    document.getElementById('filtroRangoFecha').addEventListener('click', async () => {
        const inicio = document.getElementById('filtroFechaInicio').value;
        const fin = document.getElementById('filtroFechaFin').value;
        if (!inicio || !fin) {
            return alert('Selecciona ambas fechas para filtrar.');
        }
        try {
            const res = await fetch(`?c=trabajos&a=filtrarPorFecha&fecha_inicio=${inicio}&fecha_fin=${fin}`);
            const data = await res.json();
            if (data.error) return alert(data.error);
            trabajosFiltrados = data.trabajos;
            currentPage = 1;
            mostrarTrabajosPagina(currentPage);
            generarPaginacion();
        } catch (e) {
            console.error(e);
            alert('Error al filtrar los trabajos.');
        }
    });

    document.getElementById('limpiarFiltro').addEventListener('click', () => {
        trabajosFiltrados = [...trabajosOriginales];
        currentPage = 1;
        document.getElementById('filtroFechaInicio').value = '';
        document.getElementById('filtroFechaFin').value = '';
        mostrarTrabajosPagina(currentPage);
        generarPaginacion();
    });

    document.getElementById('buscadorTrabajos').addEventListener('input', function() {
        const term = this.value.toLowerCase();
        trabajosFiltrados = trabajosOriginales.filter(t =>
            t.ID_trabajo.toString().includes(term) ||
            t.Cliente.toLowerCase().includes(term) ||
            t.Vehiculo.toLowerCase().includes(term) ||
            t.Fecha.toLowerCase().includes(term) ||
            t.Total.toString().includes(term)
        );
        currentPage = 1;
        mostrarTrabajosPagina(currentPage);
        generarPaginacion();
    });

    function generarPresupuestoPDF() {
        if (!detalleTrabajoActual) {
            return alert("No hay datos cargados del trabajo.");
        }

        const payload = {
            id_trabajo: detalleTrabajoActual.id_trabajo,
            fecha: detalleTrabajoActual.fecha,
            total: detalleTrabajoActual.total,
            propietario: detalleTrabajoActual.cliente,
            vehiculo: detalleTrabajoActual.vehiculo,
            nota: detalleTrabajoActual.nota,
            productos: detalleTrabajoActual.productos.map(p => ({
                producto: p.NombreProducto,
                cantidad: p.Cantidad,
                importe: p.PrecioUnitario
            }))
        };

        fetch('?c=trabajos&a=generarPDF', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    datos_pdf: payload
                })
            })
            .then(res => res.blob())
            .then(blob => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = `Trabajo_${detalleTrabajoActual.cliente}_${detalleTrabajoActual.vehiculo}_${detalleTrabajoActual.fecha}.pdf`;
                document.body.appendChild(a);
                a.click();
                a.remove();
            })
            .catch(err => {
                console.error('Error PDF:', err);
                alert('No se pudo generar el PDF.');
            });
    }


    // Inicialización al cargar la página
    document.addEventListener('DOMContentLoaded', () => {
        mostrarTrabajosPagina(currentPage);
        generarPaginacion();
    });
</script>