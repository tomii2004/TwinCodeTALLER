<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Clientes</h1>
                    <small>Definir Clientes</small>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row mb-3 align-items-center">
                <div class="col-md-6">
                    <input type="text" id="buscador" class="form-control form-control-sm" placeholder="Buscar clientes...">
                </div>
                <div class="col-md-3">
                    <a href="?c=clientes&a=FormNuevo" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nuevo
                    </a>
                </div>
            </div>

            <div class="card">
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap" id="tablaclientes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Teléfono</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($clientes)) : ?>
                                <?php foreach ($clientes as $cliente): ?>
                                    <tr>
                                        <td><?= $cliente['ID_cliente']; ?></td>
                                        <td><?= ucfirst($cliente['Nombre']); ?></td>
                                        <td><?= $cliente['Telefono']; ?></td>
                                        <td>
                                            <!-- Botón Agregar Vehículo -->
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAgregarVehiculo<?= $cliente['ID_cliente'] ?>">
                                                <i class="fas fa-car"></i> Agregar Vehículo
                                            </button>
                                            <!-- Botón Detalles -->
                                            <a class="btn btn-info btn-sm" href="?c=clientes&a=Detalles&id=<?= $cliente['ID_cliente'] ?>">
                                                <i class="fas fa-eye"></i> Detalles
                                            </a>
                                            <a class="btn btn-warning btn-sm" href="?c=clientes&a=FormEditar&id=<?= $cliente['ID_cliente'] ?>">
                                                <i class="fas fa-pen"></i> Editar Cliente
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- Modal para agregar un vehículo -->
                                    <div class="modal fade" id="modalAgregarVehiculo<?= $cliente['ID_cliente'] ?>" tabindex="-1" role="dialog" aria-labelledby="modalAgregarVehiculoLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="modalAgregarVehiculoLabel">Agregar Vehículo al Cliente</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form action="?c=clientes&a=AgregarVehiculo" method="POST"  autocomplete="off">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="vehiculoNombre">Modelo del Vehículo:</label>
                                                            <input type="text" class="form-control" id="vehiculoNombre" name="vehiculoNombre" required oninput="this.value = this.value.toUpperCase()">

                                                        </div>
                                                        <div class="form-group">
                                                            <label for="numeroMotor">Número de motor:</label>
                                                            <input type="text" class="form-control" id="numeroMotor" name="numeroMotor" required oninput="this.value = this.value.toUpperCase()">
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="numeroChasis">Número de chasis:</label>
                                                            <input type="text" class="form-control" id="numeroChasis" name="numeroChasis" required oninput="this.value = this.value.toUpperCase()">
                                                        </div>

                                                        <input type="hidden" name="clienteID" value="<?= $cliente['ID_cliente'] ?>">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                                        <button type="submit" class="btn btn-primary">Agregar Vehículo</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No hay clientes cargados.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Buscador en vivo
    document.getElementById('buscador').addEventListener('input', function() {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaclientes tbody tr');

        filas.forEach(fila => {
            const texto = fila.textContent.toLowerCase();
            fila.style.display = texto.includes(filtro) ? '' : 'none';
        });
    });

    // Alerta con SweetAlert2
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get("alerta");

        if (alerta === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Registro Exitoso',
                text: 'cliente añadido correctamente.',
                confirmButtonText: 'OK'
            });
        } else if (alerta === "error") {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Hubo un problema al agregar el vehículo.',
                confirmButtonText: 'OK'
            });
        }
    });
</script>
