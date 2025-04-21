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
                                                <a class="btn btn-warning btn-sm" href="?c=productos&a=FormEditar&id=<?= $producto['ID_productos'] ?>">
                                                    <i class="fas fa-pen"></i>
                                                </a>
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
                </div>               
            </div>
        </section>
<script>
    // Buscador en vivo
    document.getElementById('buscador').addEventListener('input', function () {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablacategorias tbody tr');

        filas.forEach(fila => {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = texto.includes(filtro) ? '' : 'none';
        });
    });

    // Limpiar buscador
    document.getElementById('clear-buscador').addEventListener('click', function () {
        const buscador = document.getElementById('buscador');
        buscador.value = '';
        buscador.dispatchEvent(new Event('input'));
    });

    // Filtrado
    document.getElementById('filtroCategoria').addEventListener('change', function () {
        const categoriaSeleccionada = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablacategorias tbody tr');

        filas.forEach(fila => {
            const categoria = fila.children[2].textContent.toLowerCase(); // columna 3
            fila.style.display = (categoriaSeleccionada === 'todas' || categoria.includes(categoriaSeleccionada)) ? '' : 'none';
        });
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
                window.history.replaceState(null, null, window.location.pathname + window.location.search.replace(/([&?])alerta=[^&]*/, ''));
            });
        } else if (alerta === "error") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'El producto ya existe en la base de datos.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Eliminar el parámetro sin recargar la página
                window.history.replaceState(null, null, window.location.pathname + window.location.search.replace(/([&?])alerta=[^&]*/, ''));
            });
        } 
    });
</script>
