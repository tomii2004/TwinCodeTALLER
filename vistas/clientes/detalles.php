<div class="content-wrapper">
    <!-- Encabezado -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Trabajos realizados</h1>
                    <p class="mb-0">Información detallada de los trabajos realizados</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="?c=clientes">Clientes</a></li>
                        <li class="breadcrumb-item active">Trabajos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Detalles del Cliente -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Trabajos realizados</h3>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <label>Nombre:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="<?= ucfirst(htmlspecialchars($cliente['Nombre'])) ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Teléfono:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($cliente['Telefono']) ?>" readonly>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <h6>Filtrar por Vehículo:</h6>
                                <select id="vehiculoFiltro" class="form-control mb-3">
                                    <option value="todos" <?= $vehiculoSeleccionado === 'todos' ? 'selected' : '' ?>>
                                        Todos los vehículos</option>
                                    <?php foreach ($vehiculos as $vehiculo): ?>
                                        <option value="<?= htmlspecialchars($vehiculo['Nombre']) ?>"
                                            <?= $vehiculoSeleccionado === $vehiculo['Nombre'] ? 'selected' : '' ?>>
                                            <?= ucfirst(htmlspecialchars($vehiculo['Nombre'])) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover text-nowrap" id="tablaTrabajos">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Total</th>
                                            <th>Vehículo</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($trabajos as $trabajo): ?>
                                            <tr>
                                                <td><?= $trabajo['Fecha'] ?></td>
                                                <td>$<?= number_format($trabajo['Total'], 2, ',', '.') ?></td>
                                                <td><?= htmlspecialchars($trabajo['vehiculo']) ?></td>
                                                <td>
                                                    <button class="btn btn-info btn-sm"
                                                        onclick="showDetails(<?= $trabajo['ID_trabajo'] ?>, <?= json_encode($trabajo['Productos']) ?>)">
                                                        Ver detalles
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div style="text-align: right;">
                                <button onclick="event.preventDefault(); history.back();"
                                    class="btn btn-danger">Atrás</button>
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenedor de modales dinámicos -->
    <div id="modales"></div>

    <script>
        const clienteID = <?= isset($_GET['id']) ? json_encode($_GET['id']) : 'null' ?>;
        const filtro = document.getElementById('vehiculoFiltro');
        const tbody = document.querySelector('#tablaTrabajos tbody');
        const modalesContainer = document.getElementById('modales');
        let detalleTrabajoActual = null;

        let todosLosTrabajos = [];
        let trabajosPorPagina = 5; // Cantidad de filas por página
        let paginaActual = 1;

        async function cargar(vehiculo) {
            try {
                const res = await fetch(`?c=clientes&a=ObtenerTrabajosJSON&cliente=${clienteID}&vehiculo=${vehiculo}`);
                const trabajos = await res.json();

                todosLosTrabajos = trabajos;
                paginaActual = 1; // Resetea a la primera página
                renderizarTabla();
            } catch (error) {
                console.error('Error cargando trabajos:', error);
                tbody.innerHTML = '<tr><td colspan="4" class="text-center text-danger">Error cargando datos.</td></tr>';
            }
        }

        function renderizarTabla() {
            tbody.innerHTML = '';
            modalesContainer.innerHTML = '';

            if (todosLosTrabajos.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" class="text-center">No hay trabajos registrados.</td></tr>';
                document.getElementById('paginador')?.remove();
                return;
            }

            const inicio = (paginaActual - 1) * trabajosPorPagina;
            const fin = inicio + trabajosPorPagina;
            const trabajosPagina = todosLosTrabajos.slice(inicio, fin);

            trabajosPagina.forEach(t => {
                tbody.innerHTML += `
                <tr>
                    <td>${t.Fecha}</td>
                    <td>$${parseFloat(t.Total).toLocaleString('es-AR', {minimumFractionDigits:2})}</td>
                    <td>${capitalizar(t.vehiculo)}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick='showDetails(${t.ID_trabajo}, ${JSON.stringify(t.Productos)})'>
                            Ver detalles
                        </button>
                    </td>
                </tr>
            `;
            });

            renderizarPaginador();
        }

        function renderizarPaginador() {
            document.getElementById('paginador')?.remove(); // Elimina el paginador anterior si existe

            const totalPaginas = Math.ceil(todosLosTrabajos.length / trabajosPorPagina);
            if (totalPaginas <= 1) return; // No crear paginador si no hace falta

            let paginadorHTML = `
            <nav id="paginador" class="mt-3">
                <ul class="pagination justify-content-center">
                    <li class="page-item ${paginaActual === 1 ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="cambiarPagina(${paginaActual - 1})">Anterior</a>
                    </li>
        `;

            for (let i = 1; i <= totalPaginas; i++) {
                paginadorHTML += `
                <li class="page-item ${paginaActual === i ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="cambiarPagina(${i})">${i}</a>
                </li>
            `;
            }

            paginadorHTML += `
                    <li class="page-item ${paginaActual === totalPaginas ? 'disabled' : ''}">
                        <a class="page-link" href="#" onclick="cambiarPagina(${paginaActual + 1})">Siguiente</a>
                    </li>
                </ul>
            </nav>
        `;

            tbody.parentElement.insertAdjacentHTML('afterend', paginadorHTML);
        }

        function cambiarPagina(nuevaPagina) {
            const totalPaginas = Math.ceil(todosLosTrabajos.length / trabajosPorPagina);
            if (nuevaPagina < 1 || nuevaPagina > totalPaginas) return;
            paginaActual = nuevaPagina;
            renderizarTabla();
        }

        filtro.addEventListener('change', () => cargar(filtro.value));
        cargar(filtro.value);

        function capitalizar(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        }

        function showDetails(id, productos) {
            const trabajo = todosLosTrabajos.find(t => t.ID_trabajo === id);

            detalleTrabajoActual = {
                id_trabajo: id,
                fecha: trabajo.Fecha,
                total: parseFloat(trabajo.Total),
                propietario: capitalizar(trabajo.cliente),
                vehiculo: capitalizar(trabajo.vehiculo),
                nota: trabajo.Nota,
                productos: productos.map(p => ({
                    producto: p.NombreProducto,
                    cantidad: p.Cantidad,
                    importe: parseFloat(p.PrecioUnitario)
                }))
            };
            document.getElementById(`m${id}`)?.remove();

            let modalHTML = `
            <div class="modal fade" id="m${id}" tabindex="-1" role="dialog" aria-labelledby="modalLabel${id}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel${id}">Detalles del Trabajo</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ${productos.length > 0 ? `
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>Precio Unitario</th>
                                            <th>Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${productos.map(p => `
                                            <tr>
                                                <td>${capitalizar(p.NombreProducto)}</td>
                                                <td>${p.Cantidad}</td>
                                                <td>$${parseFloat(p.PrecioUnitario).toFixed(2)}</td>
                                                <td>$${(p.Cantidad * p.PrecioUnitario).toFixed(2)}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3" class="text-right">Total</th>
                                            <th>$${productos.reduce((sum, p) => sum + (p.Cantidad * p.PrecioUnitario), 0).toFixed(2)}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            ` : '<p>No hay productos para mostrar.</p>'}
                                <div class="alert alert-secondary text-start mt-3" role="alert" style="white-space:">
                                    <strong>Nota:</strong><br>
                                    ${detalleTrabajoActual.nota}
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button class="btn btn-danger" onclick="generarTrabajoPDF()">Generar PDF</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            modalesContainer.insertAdjacentHTML('beforeend', modalHTML);
            $(`#m${id}`).modal('show');
        }

        function generarTrabajoPDF() {
            if (!detalleTrabajoActual) {
                return alert("No hay datos del trabajo para generar el PDF.");
            }

            // Armamos el payload exactamente como el controlador espera
            const payload = {
                id_trabajo: detalleTrabajoActual.id_trabajo,
                fecha: detalleTrabajoActual.fecha,
                total: detalleTrabajoActual.total,
                propietario: detalleTrabajoActual.propietario,
                vehiculo: detalleTrabajoActual.vehiculo,
                productos: detalleTrabajoActual.productos,
                nota: detalleTrabajoActual.nota
            };

            fetch(`?c=trabajos&a=generarPDF`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        datos_pdf: payload
                    })
                })
                .then(res => {
                    if (!res.ok) throw new Error("HTTP " + res.status);
                    return res.blob();
                })
                .then(blob => {
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = `Trabajo_${detalleTrabajoActual.propietario}_${detalleTrabajoActual.vehiculo}_${detalleTrabajoActual.fecha}.pdf`;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                })
                .catch(err => {
                    console.error('Error generando PDF:', err);
                    alert('No se pudo generar el PDF.');
                });
        }

        // No olvides inicializar tu tabla y paginador
        document.addEventListener('DOMContentLoaded', () => {
            cargar(filtro.value);
        });
    </script>

</div>