<div class="content-wrapper">
    <!-- Encabezado -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Categorías</h1>
                    <small>Definir Categorías Básicas</small>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active">Categorías</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="content">
        <div class="container-fluid">
            <!-- Filtro + Botón Nueva -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="input-group input-group-sm">
                        <input type="text" id="buscador" class="form-control" placeholder="Buscar categorías...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="clear-buscador">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <a href="?c=categorias&a=FormNuevo" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nueva
                    </a>
                </div>
            </div>

            <!-- Tabla de Categorías -->
            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="tablacategorias">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($categorias)) : ?>
                            <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?= $categoria['ID_categoria']; ?></td>
                                <td><?= ucfirst($categoria['Nombre']); ?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm"
                                        href="?c=categorias&a=FormEditar&id=<?= $categoria['ID_categoria'] ?>">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <?php if ($categoria['estado'] == 1): ?>
                                    <a class="btn btn-danger btn-sm"
                                        href="?c=categorias&a=CambiarEstado&id=<?= $categoria['ID_categoria'] ?>&estado=0"
                                        title="Desactivar">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                    <?php else: ?>
                                    <a class="btn btn-success btn-sm"
                                        href="?c=categorias&a=CambiarEstado&id=<?= $categoria['ID_categoria'] ?>&estado=1"
                                        title="Activar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="3">No hay categorías disponibles.</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div id="paginacionCategorias" class="d-flex justify-content-center mt-2 mb-2"></div>
            </div>
        </div>
    </section>
    <script>

    document.addEventListener("DOMContentLoaded", function() {

        // ——— ELEMENTOS DOM ———
        const buscador   = document.getElementById('buscador');
        const clearBtn   = document.getElementById('clear-buscador');
        const filtroCat  = null; // no hay filtro de categoría en este listado
        const tablaBody  = document.querySelector('#tablacategorias tbody');
        const paginacion = document.getElementById('paginacionCategorias');

        // ——— ESTADO ———
        const filasOriginales = Array.from(tablaBody.querySelectorAll('tr'));
        let filasFiltradas    = [...filasOriginales];
        const filasPorPagina  = 10;
        let paginaActual      = 1;

        // ——— FUNCIONES ———
        function mostrarPagina(pagina) {
            const inicio = (pagina - 1) * filasPorPagina;
            const fin    = inicio + filasPorPagina;
            tablaBody.innerHTML = '';
            filasFiltradas.slice(inicio, fin).forEach(tr => tablaBody.appendChild(tr));
        }

        function generarPaginacion() {
            const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPagina);
            paginacion.innerHTML = '';

            // Si solo 1 o 0 páginas, no mostramos nada
            //if (totalPaginas <= 1) return;

            // Botón Anterior
            const prev = document.createElement('button');
            prev.textContent = 'Anterior';
            prev.className   = 'btn btn-secondary btn-sm mr-1';
            prev.disabled    = paginaActual === 1;
            prev.addEventListener('click', () => cambiarPagina(paginaActual - 1));
            paginacion.appendChild(prev);

            // Botones de número
            for (let i = 1; i <= totalPaginas; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className  = `btn btn-secondary btn-sm mx-1 ${i === paginaActual ? 'active' : ''}`;
                btn.addEventListener('click', () => cambiarPagina(i));
                paginacion.appendChild(btn);
            }

            // Botón Siguiente
            const next = document.createElement('button');
            next.textContent = 'Siguiente';
            next.className  = 'btn btn-secondary btn-sm ml-1';
            next.disabled   = paginaActual === totalPaginas;
            next.addEventListener('click', () => cambiarPagina(paginaActual + 1));
            paginacion.appendChild(next);
        }

        function cambiarPagina(nueva) {
            const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPagina);
            if (nueva < 1 || nueva > totalPaginas) return;
            paginaActual = nueva;
            mostrarPagina(paginaActual);
            generarPaginacion();
        }

        function aplicarBusqueda() {
            const texto = buscador.value.trim().toLowerCase();
            filasFiltradas = filasOriginales.filter(tr =>
                tr.textContent.toLowerCase().includes(texto)
            );
            paginaActual = 1;
            mostrarPagina(paginaActual);
            generarPaginacion();
        }

        // ——— EVENTOS ———
        buscador.addEventListener('input', aplicarBusqueda);
        clearBtn.addEventListener('click', () => {
            buscador.value = '';
            aplicarBusqueda();
        });

        // ——— INICIALIZAR ———
        aplicarBusqueda();

        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get("alerta");

        if (alerta === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Registro Exitoso',
                text: 'Categoria añadida correctamente.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Eliminar el parámetro sin recargar la página
                window.history.replaceState(null, null, window.location.pathname + window.location
                    .search.replace(/([&?])alerta=[^&]*/, ''));
            });
        } else if (alerta === "error") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La categoria ya existe en la base de datos.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Eliminar el parámetro sin recargar la página
                window.history.replaceState(null, null, window.location.pathname + window.location
                    .search.replace(/([&?])alerta=[^&]*/, ''));
            });
        } else if (alerta === "uso") { // 🚀 Nuevo caso para variantes en uso
            Swal.fire({
                icon: 'warning',
                title: 'No se puede desactivar',
                text: 'Esta categoria está en uso y no se puede desactivar.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Eliminar el parámetro sin recargar la página
                window.history.replaceState(null, null, window.location.pathname + window.location
                    .search.replace(/([&?])alerta=[^&]*/, ''));
            });
        }
    });
    </script>