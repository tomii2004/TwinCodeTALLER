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
                    <div class="input-group input-group-sm">
                        <input type="text" id="buscador" class="form-control" placeholder="Buscar clientes...">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="clear-buscador">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
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
                                            <?php if ($cliente['estado'] == 1): ?>
                                            <a class="btn btn-danger btn-sm"
                                                href="?c=clientes&a=CambiarEstado&id=<?= $cliente['ID_cliente'] ?>&estado=0"
                                                title="Desactivar">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                            <?php else: ?>
                                            <a class="btn btn-success btn-sm"
                                                href="?c=clientes&a=CambiarEstado&id=<?= $cliente['ID_cliente'] ?>&estado=1"
                                                title="Activar">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <?php endif; ?>
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
                                                            <input type="text" class="form-control" id="vehiculoNombre" name="vehiculoNombre" required>

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
                <div id="paginacionClientes" class="d-flex justify-content-center mt-2 mb-2"></div>
            </div>
        </div>
    </section>
</div>

<script>
    
    // Alerta con SweetAlert2
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get("alerta");

        if (alerta === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Registro Exitoso',
                text: 'Cliente añadido correctamente.',
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // ——— ELEMENTOS DOM ———
        const buscador   = document.getElementById('buscador');
        const clearBtn   = document.getElementById('clear-buscador');
        const tablaBody  = document.querySelector('#tablaclientes tbody');
        const paginacion = document.getElementById('paginacionClientes');

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

            // Anterior
            const prev = document.createElement('button');
            prev.textContent = 'Anterior';
            prev.className   = 'btn btn-secondary btn-sm mr-1';
            prev.disabled    = paginaActual === 1;
            prev.addEventListener('click', () => cambiarPagina(paginaActual - 1));
            paginacion.appendChild(prev);

            // Números
            for (let i = 1; i <= totalPaginas; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className  = `btn btn-secondary btn-sm mx-1 ${i === paginaActual ? 'active' : ''}`;
                btn.addEventListener('click', () => cambiarPagina(i));
                paginacion.appendChild(btn);
            }

            // Siguiente
            const next = document.createElement('button');
            next.textContent = 'Siguiente';
            next.className  = 'btn btn-secondary btn-sm ml-1';
            next.disabled   = paginaActual === totalPaginas || totalPaginas === 0;
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

        function aplicarFiltros() {
            const texto = buscador.value.trim().toLowerCase();
            filasFiltradas = filasOriginales.filter(tr => {
                return tr.textContent.toLowerCase().includes(texto);
            });
            paginaActual = 1;
            mostrarPagina(paginaActual);
            generarPaginacion();
        }

        // ——— EVENTOS ———
        buscador.addEventListener('input', aplicarFiltros);
        clearBtn.addEventListener('click', () => {
            buscador.value = '';
            aplicarFiltros();
        });

        // ——— INICIALIZACIÓN ———
        aplicarFiltros();
    });
</script>
