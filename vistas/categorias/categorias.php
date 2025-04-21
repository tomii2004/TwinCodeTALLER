
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
                                                <a class="btn btn-warning btn-sm" href="?c=categorias&a=FormEditar&id=<?= $categoria['ID_categoria'] ?>">
                                                    <i class="fas fa-pen"></i>
                                                </a>
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

    document.addEventListener("DOMContentLoaded", function() {
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
                window.history.replaceState(null, null, window.location.pathname + window.location.search.replace(/([&?])alerta=[^&]*/, ''));
            });
        } else if (alerta === "error") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'La categoria ya existe en la base de datos.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Eliminar el parámetro sin recargar la página
                window.history.replaceState(null, null, window.location.pathname + window.location.search.replace(/([&?])alerta=[^&]*/, ''));
            });
        } else if (alerta === "uso") {  // 🚀 Nuevo caso para variantes en uso
            Swal.fire({
                icon: 'warning',
                title: 'No se puede eliminar',
                text: 'Esta categoria está en uso y no se puede eliminar.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Eliminar el parámetro sin recargar la página
                window.history.replaceState(null, null, window.location.pathname + window.location.search.replace(/([&?])alerta=[^&]*/, ''));
            });
        }
    });
</script>
