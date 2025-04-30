<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Trabajos</h1>
                    <small>Gestión de Trabajos</small>
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
            </div>


            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="tablaTrabajos">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>ID Trabajo</th>
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
    async function showDetails(id) {
        document.getElementById(`m${id}`)?.remove();

        try {
            const res = await fetch(`?c=trabajos&a=ObtenerProductosTrabajoJSON&ID_trabajo=${id}`);
            const productos = await res.json();

            let modalHTML = `
            <div class="modal fade" id="m${id}" tabindex="-1" role="dialog" aria-labelledby="modalLabel${id}" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalLabel${id}">Detalle de Productos</h5>
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
                                                <td>${p.NombreProducto}</td>
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
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        `;

            modalesContainer.insertAdjacentHTML('beforeend', modalHTML);
            $(`#m${id}`).modal('show');
        } catch (error) {
            console.error('Error al obtener productos:', error);
            alert('No se pudieron cargar los productos.');
        }
    }


    // Buscador en vivo para trabajos
    document.getElementById('buscadorTrabajos').addEventListener('input', function() {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaTrabajos tbody tr');

        filas.forEach(fila => {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = texto.includes(filtro) ? '' : 'none';
        });
    });

    // Alerta con SweetAlert2 (igual que en clientes)
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get("alerta");

        if (alerta === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Registro Exitoso',
                text: 'Trabajo añadido correctamente.',
                confirmButtonText: 'OK'
            });
        } else if (alerta === "error") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al agregar el trabajo.',
                confirmButtonText: 'OK'
            });
        }
    });
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const trabajosPorPagina = 5;
        const trabajos = <?= json_encode($trabajos); ?>;
        const totalTrabajos = trabajos.length;

        // Calcular el número total de páginas
        const totalPaginas = Math.ceil(totalTrabajos / trabajosPorPagina);

        // Función para mostrar los trabajos en una página específica
        function mostrarTrabajos(pagina) {
            const inicio = (pagina - 1) * trabajosPorPagina;
            const fin = inicio + trabajosPorPagina;
            const trabajosPaginados = trabajos.slice(inicio, fin);

            // Limpiar el cuerpo de la tabla
            const tablaCuerpo = document.getElementById('trabajosTableBody');
            tablaCuerpo.innerHTML = '';

            // Insertar los trabajos en la tabla
            trabajosPaginados.forEach(trabajo => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${trabajo['ID_trabajo']}</td>
                    <td>${trabajo['Cliente']}</td>
                    <td>${trabajo['Vehiculo']}</td>
                    <td>${trabajo['Fecha']}</td>
                    <td>${trabajo['Total']}</td>
                    <td>
                        <button class="btn btn-info btn-sm" onclick="showDetails(${trabajo['ID_trabajo']})">
    <i class="fas fa-eye"></i> Detalles
</button>

                    </td>
                `;
                tablaCuerpo.appendChild(tr);
            });
        }

        // Función para generar los botones de paginación
        function generarPaginacion() {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';

            // Crear botones de "Anterior" y "Siguiente"
            const prevButton = document.createElement('button');
            prevButton.textContent = 'Anterior';
            prevButton.className = 'btn btn-secondary btn-sm';
            prevButton.disabled = currentPage === 1;
            prevButton.addEventListener('click', () => cambiarPagina(currentPage - 1));
            paginationDiv.appendChild(prevButton);

            // Crear los botones de las páginas
            for (let i = 1; i <= totalPaginas; i++) {
                const pageButton = document.createElement('button');
                pageButton.textContent = i;
                pageButton.className = `btn btn-secondary btn-sm mx-1 ${i === currentPage ? 'active' : ''}`;
                pageButton.addEventListener('click', () => cambiarPagina(i));
                paginationDiv.appendChild(pageButton);
            }

            // Crear el botón de "Siguiente"
            const nextButton = document.createElement('button');
            nextButton.textContent = 'Siguiente';
            nextButton.className = 'btn btn-secondary btn-sm';
            nextButton.disabled = currentPage === totalPaginas;
            nextButton.addEventListener('click', () => cambiarPagina(currentPage + 1));
            paginationDiv.appendChild(nextButton);
        }

        // Función para cambiar la página
        let currentPage = 1;

        function cambiarPagina(pagina) {
            if (pagina < 1 || pagina > totalPaginas) return;
            currentPage = pagina;
            mostrarTrabajos(currentPage);
            generarPaginacion();
        }

        // Inicializar la página
        mostrarTrabajos(currentPage);
        generarPaginacion();
    });
</script>