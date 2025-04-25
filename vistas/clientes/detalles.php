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
                                    <input type="text" class="form-control" id="nombreCliente" value="<?php echo htmlspecialchars($cliente['Nombre']); ?>" readonly>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Teléfono:</label>

                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="telefonoCliente" value="<?php echo htmlspecialchars($cliente['Telefono']); ?>" readonly>
                                </div>

                                <h6 class="mt-4">Filtrar por Vehículo:</h6>
                                <select id="vehiculoFiltro" class="form-control mb-3">
                                    <option value="todos" <?php echo $vehiculoSeleccionado === 'todos' ? 'selected' : ''; ?>>Todos los vehículos</option>
                                    <?php foreach ($vehiculos as $vehiculo): ?>
                                        <option value="<?php echo htmlspecialchars($vehiculo['Nombre']); ?>"
                                            <?php echo $vehiculoSeleccionado === $vehiculo['Nombre'] ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($vehiculo['Nombre']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>

                                <div class="table-responsive">
                                    <table class="table table-hover text-nowrap" id="tablaTrabajos">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Total</th>
                                                <th>Vehículo (Chasis)</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($trabajos as $trabajo): ?>
                                                <tr data-vehiculo="<?php echo htmlspecialchars($trabajo['vehiculo']); ?>">
                                                    <td><?php echo $trabajo['Fecha']; ?></td>
                                                    <td>$<?php echo number_format($trabajo['Total'], 2, ',', '.'); ?></td>
                                                    <td><?php echo $trabajo['vehiculo']; ?></td>
                                                    <td>
                                                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#DetalleProductos<?= $trabajo['ID_trabajo'] ?>">
                                                            Ver detalles
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <?php if (count($trabajos) == 0): ?>
                                    <p>No hay trabajos registrados para este cliente.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal debe estar fuera de la tabla, en el mismo nivel -->
            <?php foreach ($trabajos as $trabajo): ?>
                <div class="modal fade" id="DetalleProductos<?= $trabajo['ID_trabajo'] ?>" tabindex="-1" role="dialog" aria-labelledby="DetalleProductosLabel<?= $trabajo['ID_trabajo'] ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="DetalleProductosLabel<?= $trabajo['ID_trabajo'] ?>">Detalle de Productos</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>

                            <div class="modal-body">
                                <?php if (!empty($trabajo['Productos'])): ?>
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
                                            <?php
                                            $total = 0;
                                            foreach ($trabajo['Productos'] as $producto):
                                                $subtotal = $producto['Cantidad'] * $producto['PrecioUnitario'];
                                                $total += $subtotal;
                                            ?>
                                                <tr>
                                                    <td><?= htmlspecialchars($producto['NombreProducto']) ?></td>
                                                    <td><?= $producto['Cantidad'] ?></td>
                                                    <td>$<?= number_format($producto['PrecioUnitario'], 2, ',', '.') ?></td>
                                                    <td>$<?= number_format($subtotal, 2, ',', '.') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3" class="text-right">Total</th>
                                                <th>$<?= number_format($total, 2, ',', '.') ?></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                <?php else: ?>
                                    <p>No hay productos registrados para este trabajo.</p>
                                <?php endif; ?>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

    </section>

    <script>
        const clienteID = <?= json_encode($_GET['id']) ?>;
        const filtro = document.getElementById('vehiculoFiltro');
        const tbody = document.querySelector('#tablaTrabajos tbody');

        async function cargar(veh) {
            const res = await fetch(`?c=clientes&a=ObtenerTrabajosJSON&cliente=${clienteID}&vehiculo=${veh}`);
            const trabajos = await res.json();
            tbody.innerHTML = trabajos.length ?
                trabajos.map(t => `
      <tr>
        <td>${t.Fecha}</td>
        <td>$${parseFloat(t.Total).toLocaleString('es-AR',{minimumFractionDigits:2})}</td>
        <td>${t.vehiculo}</td>
        <td>
          <button class="btn btn-info btn-sm" onclick="showDetails(${t.ID_trabajo}, ${JSON.stringify(t.Productos).replace(/"/g,'&quot;')})">
            Ver detalles
          </button>
        </td>
      </tr>`).join('') :
                '<tr><td colspan="4">No hay trabajos.</td></tr>';
        }

        // al cargar por primera vez y al cambiar
        filtro.addEventListener('change', () => cargar(filtro.value));
        cargar(filtro.value);

        // crea y muestra el modal con los productos
        function showDetails(id, productos) {
            let html = `<div class="modal fade" id="m${id}" tabindex="-1"><div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Productos</h5>
      <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">` +
                (productos.length ?
                    `<table class="table"><thead><tr><th>Producto</th><th>Cant.</th><th>PU</th><th>Sub</th></tr></thead><tbody>` +
                    productos.map(p => {
                        const sub = p.Cantidad * p.PrecioUnitario;
                        return `<tr>
            <td>${p.NombreProducto}</td>
            <td>${p.Cantidad}</td>
            <td>$${p.PrecioUnitario.toFixed(2)}</td>
            <td>$${sub.toFixed(2)}</td>
          </tr>`;
                    }).join('') +
                    `</tbody><tfoot>
          <tr><th colspan="3" class="text-right">Total</th><th>$${productos.reduce((sum,p)=>sum+p.Cantidad*p.PrecioUnitario,0).toFixed(2)}</th></tr>
        </tfoot></table>` :
                    '<p>No hay productos.</p>'
                ) +
                `</div>
    <div class="modal-footer"><button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button></div>
  </div></div></div>`;

            document.getElementById(`m${id}`)?.remove();
            document.body.insertAdjacentHTML('beforeend', html);
            $(`#m${id}`).modal('show');
        }
    </script>

</div>