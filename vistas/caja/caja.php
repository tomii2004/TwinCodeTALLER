<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>Caja del día: <?= htmlspecialchars($datosCaja['fecha']) ?></h1>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Fecha selector -->
            <div class="row mb-3">
                <div class="col-md-4 offset-md-8">
                    <form method="get" action="">
                        <input type="hidden" name="c" value="caja">
                        <input type="hidden" name="a" value="Inicio">
                        <div class="input-group">
                            <input type="date" name="fecha" class="form-control"
                                   value="<?= htmlspecialchars($datosCaja['fecha']) ?>">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search"></i> Consultar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Resumen de Caja -->
            <div class="row">
                <div class="col-md-6 offset-md-3">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Resumen de Caja</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>Total Trabajado</th>
                                    <td>$<?= number_format($datosCaja['total'], 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Egresos (Repuestos)</th>
                                    <td>$<?= number_format($datosCaja['egresos'], 2) ?></td>
                                </tr>
                                <tr>
                                    <th>Ganancia</th>
                                    <td><strong>$<?= number_format($datosCaja['ingresos'], 2) ?></strong></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detalle por Trabajo -->
            <div class="row mt-4">
                <div class="col-12">
                    <?php
                    $currentTrabajo = null;
                    foreach ($detalle as $d):
                        // Cuando cambia de trabajo, abrimos una nueva tarjeta
                        if ($currentTrabajo !== $d['ID_trabajo']):
                            if ($currentTrabajo !== null) echo "</tbody></table></div></div>";
                            $currentTrabajo = $d['ID_trabajo'];
                    ?>
                    <div class="card card-secondary mb-4">
                        <div class="card-header">
                            <h5 class="card-title">
                                Trabajo #<?= $d['ID_trabajo'] ?>
                                — Cliente: <?= htmlspecialchars($d['nombre_cliente'] ?: '—') ?>
                                — Nota: <?= htmlspecialchars($d['Nota'] ?: '—') ?>
                                — Total Trabajo: $<?= number_format($d['total_trabajo'], 2) ?>
                            </h5>
                        </div>
                        <div class="card-body p-0">
                            <table class="table table-sm table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Categoría</th>
                                        <th>Producto / Servicio</th>
                                        <th class="text-center">Cant.</th>
                                        <th class="text-right">Unitario</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                        <?php endif; ?>
                                    <tr>
                                        <td><?= htmlspecialchars($d['categoria']) ?></td>
                                        <td><?= htmlspecialchars($d['producto']) ?></td>
                                        <td class="text-center"><?= $d['cantidad'] ?></td>
                                        <td class="text-right">$<?= number_format($d['preciounitario'], 2) ?></td>
                                        <td class="text-right">$<?= number_format($d['total'], 2) ?></td>
                                    </tr>
                    <?php endforeach;
                    if ($currentTrabajo !== null) echo "</tbody></table></div></div>";
                    if (empty($detalle)): ?>
                        <p class="text-center">No se encontraron registros para esta fecha.</p>
                    <?php endif; ?>
                </div>
            </div>

        </div><!-- /.container-fluid -->
    </section>
</div>
