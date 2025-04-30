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
                    <h1>Productos</h1>
                    <small>Definir Productos</small>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item active">Productos</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="content">
        <div class="container-fluid">
            <!-- Botón Nueva -->
            <div class="row mb-3 align-items-center">
                <div class="col-md-5">
                    <div class="input-group input-group-sm">
                        <input type="text" id="buscador" class="form-control" placeholder="Buscar productos...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="clear-buscador">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <!-- Filtro por categoría -->
                <div class="col-md-4">
                    <select id="filtroCategoria" class="form-control form-control-sm">
                        <option value="todas">Todas las categorías</option>
                        <?php foreach ($categoriasUnicas as $cat): ?>
                        <option value="<?= strtolower($cat) ?>"><?= ucfirst($cat) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 ">
                    <a href="?c=productos&a=FormNuevo" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo
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
                                <th>Categoria</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($productos)) : ?>
                            <?php foreach ($productos as $producto): ?>
                            <tr>
                                <td><?= $producto['ID_productos']; ?></td>
                                <td><?= ucfirst($producto['nombre']); ?></td>
                                <td><?= ucfirst($producto['nombrecat']);?></td>
                                <td>
                                    <a class="btn btn-warning btn-sm"
                                        href="?c=productos&a=FormEditar&id=<?= $producto['ID_productos'] ?>">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <?php if ($producto['estado'] == 1): ?>
                                    <a class="btn btn-danger btn-sm"
                                        href="?c=productos&a=CambiarEstado&id=<?= $producto['ID_productos'] ?>&estado=0"
                                        title="Desactivar">
                                        <i class="fas fa-ban"></i>
                                    </a>
                                    <?php else: ?>
                                    <a class="btn btn-success btn-sm"
                                        href="?c=productos&a=CambiarEstado&id=<?= $producto['ID_productos'] ?>&estado=1"
                                        title="Activar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php else: ?>
                            <tr>
                                <td colspan="3">No hay productos cargados.</td>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Elementos del DOM
        const buscador    = document.getElementById('buscador');
        const clearBtn    = document.getElementById('clear-buscador');
        const filtroCat   = document.getElementById('filtroCategoria');
        const tablaBody   = document.querySelector('#tablacategorias tbody');
        const paginacion  = document.getElementById('paginacionCategorias');

        // Estado para paginación
        const filasOriginales    = Array.from(tablaBody.querySelectorAll('tr'));
        let filasFiltradas       = [...filasOriginales];
        const filasPorPagina     = 10;
        let paginaActual         = 1;

        // Funciones de paginación
        function mostrarPagina(pagina) {
            const inicio = (pagina - 1) * filasPorPagina;
            const fin    = inicio + filasPorPagina;
            tablaBody.innerHTML = '';
            filasFiltradas.slice(inicio, fin).forEach(tr => tablaBody.appendChild(tr));
        }

        function generarPaginacion() {
            const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPagina);
            paginacion.innerHTML = '';

            // Botón Anterior
            const prev = document.createElement('button');
            prev.textContent = 'Anterior';
            prev.className   = 'btn btn-secondary btn-sm mr-1';
            prev.disabled    = paginaActual === 1;
            prev.addEventListener('click', () => cambiarPagina(paginaActual - 1));
            paginacion.appendChild(prev);

            // Botones de página
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
            next.disabled   = paginaActual === totalPaginas || totalPaginas === 0;
            next.addEventListener('click', () => cambiarPagina(paginaActual + 1));
            paginacion.appendChild(next);
        }

        function cambiarPagina(nuevaPagina) {
            const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPagina);
            if (nuevaPagina < 1 || nuevaPagina > totalPaginas) return;
            paginaActual = nuevaPagina;
            mostrarPagina(paginaActual);
            generarPaginacion();
        }

        // Filtrado combinado (buscador + categoría)
        function aplicarFiltros() {
            const texto = buscador.value.trim().toLowerCase();
            const cat   = filtroCat.value.toLowerCase();
            filasFiltradas = filasOriginales.filter(tr => {
                const cols = tr.children;
                const nombre  = cols[1].textContent.toLowerCase();
                const categoria = cols[2].textContent.toLowerCase();
                const coincideTexto = nombre.includes(texto) || cols[0].textContent.toLowerCase().includes(texto);
                const coincideCat   = (cat === 'todas') || categoria.includes(cat);
                return coincideTexto && coincideCat;
            });
            paginaActual = 1;
            mostrarPagina(paginaActual);
            generarPaginacion();
        }

        // Eventos
        buscador.addEventListener('input', aplicarFiltros);
        clearBtn.addEventListener('click', () => {
            buscador.value = '';
            aplicarFiltros();
        });
        filtroCat.addEventListener('change', aplicarFiltros);

        // Inicialización
        aplicarFiltros();
    });
    </script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get("alerta");

        if (alerta === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Registro Exitoso',
                text: 'Producto añadido correctamente.',
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
                text: 'El producto ya existe en la base de datos.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Eliminar el parámetro sin recargar la página
                window.history.replaceState(null, null, window.location.pathname + window.location
                    .search.replace(/([&?])alerta=[^&]*/, ''));
            });
        }
    });
    </script>