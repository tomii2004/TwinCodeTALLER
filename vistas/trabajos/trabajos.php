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
    function capitalizar(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

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


    document.addEventListener("DOMContentLoaded", function() {
    let trabajosOriginales = <?= json_encode($trabajos); ?>;  // Lista completa de trabajos (sin filtrar)
    let trabajosFiltrados = [...trabajosOriginales];  // Copia de la lista original que puede ser filtrada
    let trabajosPorPagina = 5;
    let currentPage = 1;

    // Función para mostrar los trabajos en la tabla
    function mostrarTrabajos(trabajos) {
        const tablaCuerpo = document.getElementById('trabajosTableBody');
        tablaCuerpo.innerHTML = '';  // Limpiar tabla antes de insertar nuevos datos

        // Insertar los trabajos en la tabla
        trabajos.forEach(trabajo => {
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

    // Función para mostrar los trabajos de una página específica
    function mostrarTrabajosPagina(pagina) {
        const inicio = (pagina - 1) * trabajosPorPagina;
        const fin = inicio + trabajosPorPagina;
        const trabajosPaginados = trabajosFiltrados.slice(inicio, fin);

        // Limpiar la tabla y mostrar los trabajos filtrados y paginados
        mostrarTrabajos(trabajosPaginados);
    }

    // Función para generar los botones de paginación
    function generarPaginacion() {
        const paginationDiv = document.getElementById('pagination');
        paginationDiv.innerHTML = '';

        // Calcular el número total de páginas
        const totalPaginas = Math.ceil(trabajosFiltrados.length / trabajosPorPagina);

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
    function cambiarPagina(pagina) {
        const totalPaginas = Math.ceil(trabajosFiltrados.length / trabajosPorPagina);
        if (pagina < 1 || pagina > totalPaginas) return;

        currentPage = pagina;
        mostrarTrabajosPagina(currentPage);
        generarPaginacion();
    }

    // Al hacer clic en el botón de "Filtrar por Rango de Fecha"
    document.getElementById('filtroRangoFecha').addEventListener('click', async function() {
        const fechaInicio = document.getElementById('filtroFechaInicio').value;
        const fechaFin = document.getElementById('filtroFechaFin').value;

        // Verificar que ambas fechas están seleccionadas
        if (!fechaInicio || !fechaFin) {
            alert('Por favor, selecciona ambas fechas para el filtro.');
            return;
        }

        try {
            // Realizar la solicitud Fetch para obtener los trabajos filtrados
            const res = await fetch(`?c=trabajos&a=filtrarPorFecha&fecha_inicio=${fechaInicio}&fecha_fin=${fechaFin}`);
            const data = await res.json();

            if (data.error) {
                alert(data.error);  // Mostrar mensaje de error si no hay resultados
            } else {
                trabajosFiltrados = data.trabajos; // Actualizar trabajos filtrados
                currentPage = 1;  // Resetear a la primera página al aplicar el filtro
                mostrarTrabajosPagina(currentPage);  // Mostrar los trabajos filtrados en la primera página
                generarPaginacion();  // Generar los botones de paginación para los resultados filtrados
            }
        } catch (error) {
            console.error('Error al filtrar los trabajos:', error);
            alert('Ocurrió un error al filtrar los trabajos.');
        }
    });

    // Buscador en vivo para trabajos
    document.getElementById('buscadorTrabajos').addEventListener('input', function() {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaTrabajos tbody tr');

        filas.forEach(fila => {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = texto.includes(filtro) ? '' : 'none';
        });
    });

    // Función para limpiar los filtros y volver a mostrar todos los trabajos
    document.getElementById('limpiarFiltro').addEventListener('click', function() {
        // Restablecer la lista de trabajos filtrados a la lista original
        trabajosFiltrados = [...trabajosOriginales];
        currentPage = 1;  // Volver a la primera página
        mostrarTrabajosPagina(currentPage);  // Mostrar todos los trabajos en la primera página
        generarPaginacion();  // Generar los botones de paginación para los trabajos no filtrados
        document.getElementById('filtroFechaInicio').value = '';  // Limpiar los campos de filtro
        document.getElementById('filtroFechaFin').value = '';
    });

    // Inicializar la página
    mostrarTrabajosPagina(currentPage);
    generarPaginacion();
});


</script>