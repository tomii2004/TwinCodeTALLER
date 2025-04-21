
    <div class="content-wrapper">
        <!-- Encabezado -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Editar Categoría</h1>
                        <p class="mb-0">Modificar categorías básicas existentes</p>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="?c=categorias">Categorías</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>

        <!-- Formulario -->
        <section class="content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card card-warning">
                            <div class="card-header">
                                <h3 class="card-title">Editar categoría</h3>
                            </div>
                            <form action="?c=categorias&a=ActualizarCategoria" method="post">
                                <input type="hidden" name="id" value="<?php echo $categoria['ID_categoria']; ?>">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nombre">Nuevo nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo $categoria['nombre']; ?>" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="card-footer d-flex flex-column gap-2">
                                    <button type="button" onclick="history.back();" class="btn btn-secondary mb-2">
                                        <i class="fas fa-arrow-left"></i> Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary mb-2">
                                        <i class="fas fa-save"></i> Actualizar
                                    </button>
                                    <button type="button" class="btn btn-danger" onclick="confirmarEliminacion(<?php echo $categoria['ID_categoria']; ?>)">
                                        <i class="fas fa-trash-alt"></i> Eliminar
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<script>
function confirmarEliminacion(id) {
    Swal.fire({
        title: '¿Estás seguro que quieres eliminarla?',
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
        customClass: {
            popup: 'alerta-grande'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'post';
            form.action = '?c=categorias&a=EliminarCategoria';

            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'id';
            input.value = id;

            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>

