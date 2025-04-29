<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>Resumen de Caja - <?= htmlspecialchars($datos['fecha']) ?></h1>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Primera fila: Resumen y Selección de Fecha -->
            <div class="row">
                <!-- Resumen Financiero -->
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Resumen</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Ingresos por Servicios</th>
                                    <td>$<?= number_format($datos['servicios'], 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Costo de Productos</th>
                                    <td>$<?= number_format($datos['productos'], 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Utilidad Bruta</th>
                                    <td>$<?= number_format($datos['servicios'] - $datos['productos'], 2) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Selección de Fecha -->
                <div class="col-md-6">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">Seleccionar Fecha</h3>
                        </div>
                        <div class="card-body">
                            <form method="get" action="?c=caja">
                                <div class="form-group">
                                    <label for="fecha">Fecha:</label>
                                    <input type="date" name="fecha" class="form-control"
                                           value="<?= htmlspecialchars($datos['fecha']) ?>">
                                </div>
                                <button type="submit" class="btn btn-info">Consultar</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Segunda fila: Movimiento Manual y Cierre de Caja -->
            <div class="row">
                <!-- Movimiento Manual -->
                <div class="col-md-6">
                    <div class="card card-warning">
                        <div class="card-header">
                            <h3 class="card-title">Registrar Movimiento Manual</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" action="?c=caja">
                                <input type="hidden" name="fecha" value="<?= htmlspecialchars($datos['fecha']) ?>">
                                <div class="form-group">
                                    <label>Tipo:</label>
                                    <select name="tipo" class="form-control">
                                        <option value="ingreso">Ingreso</option>
                                        <option value="retiro">Retiro</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Concepto:</label>
                                    <input type="text" name="concepto" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Monto:</label>
                                    <input type="number" step="0.01" name="monto" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-warning">Registrar Movimiento</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cierre de Caja -->
                <div class="col-md-6">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Cierre de Caja</h3>
                        </div>
                        <div class="card-body">
                            <form method="post" action="?c=caja">
                                <input type="hidden" name="fecha" value="<?= htmlspecialchars($datos['fecha']) ?>">
                                <div class="form-group">
                                    <label>Observaciones:</label>
                                    <textarea name="observaciones" class="form-control"></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger">Cerrar Caja del Día</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tercera fila: Lista de movimientos -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Movimientos Manuales del <?= htmlspecialchars($datos['fecha']) ?></h3>
                        </div>
                        <div class="card-body">
                            <?php if (!empty($movimientos)): ?>
                                <table class="table table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th>Fecha y hora</th>
                                            <th>Tipo</th>
                                            <th>Concepto</th>
                                            <th>Monto</th>
                                            <th>Registrado por</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($movimientos as $m): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($m['creado_en']) ?></td>
                                                <td>
                                                    <span class="badge badge-<?= $m['tipo'] == 'ingreso' ? 'success' : 'danger' ?>">
                                                        <?= ucfirst($m['tipo']) ?>
                                                    </span>
                                                </td>
                                                <td><?= htmlspecialchars($m['concepto']) ?></td>
                                                <td>$<?= number_format($m['monto'], 2) ?></td>
                                                <td><?= htmlspecialchars($m['creado_por']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No se han registrado movimientos manuales para esta fecha.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
</div>
