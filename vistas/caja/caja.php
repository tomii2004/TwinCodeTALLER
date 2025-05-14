<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

    <!-- Header -->
    <section class="content-header">
        <div class="container-fluid">
            <h1>
                <?= htmlspecialchars($tituloFecha) ?>
            </h1>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">

            <!-- Fecha selector -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="get" action="" onsubmit="return validarFechas();">
                        <input type="hidden" name="c" value="caja">
                        <input type="hidden" name="a" value="Inicio">

                        <div class="form-group mb-2">
                            <label for="modo">Consultar por:</label>
                            <select id="modo" name="modo" class="form-control" onchange="mostrarCampos()">
                                <option value="dia" <?= (($_GET['modo'] ?? '') == 'dia' ? 'selected' : '') ?>>Un solo día</option>
                                <option value="rango" <?= (($_GET['modo'] ?? '') == 'rango' ? 'selected' : '') ?>>Rango de fechas</option>
                            </select>
                        </div>

                        <div id="campo-dia" style="display: none;">
                            <label for="fecha">Fecha:</label>
                            <input type="date" name="fecha" id="fecha" class="form-control" value="<?= htmlspecialchars($_GET['fecha'] ?? '') ?>">
                        </div>

                        <div id="campo-rango" style="display: none;">
                            <label>Desde:</label>
                            <input type="date" name="desde" class="form-control mb-2" value="<?= htmlspecialchars($_GET['desde'] ?? '') ?>">
                            <label>Hasta:</label>
                            <input type="date" name="hasta" class="form-control mb-2" value="<?= htmlspecialchars($_GET['hasta'] ?? '') ?>">
                        </div>

                        <button class="btn btn-primary mt-2" type="submit">
                            <i class="fas fa-search"></i> Consultar
                        </button>
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
                                — Cliente: <?= ucfirst(htmlspecialchars($d['nombre_cliente'] ?: '—')) ?>
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
                                        <td><?= ucfirst(htmlspecialchars($d['categoria'])) ?></td>
                                        <td><?= ucfirst(htmlspecialchars($d['producto'])) ?></td>
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
<script>
function mostrarCampos() {
    var modo = document.getElementById("modo").value;
    document.getElementById("campo-dia").style.display = (modo === "dia") ? "block" : "none";
    document.getElementById("campo-rango").style.display = (modo === "rango") ? "block" : "none";
}
function validarFechas() {
    const modo = document.getElementById("modo").value;

    if (modo === "dia") {
        const fecha = document.getElementById("fecha").value;
        if (!fecha) {
            Swal.fire({
                icon: 'warning',
                title: 'Falta la fecha',
                text: 'Por favor, ingresá una fecha.',
                confirmButtonColor: '#ff6daf'
            });
            return false;
        }
    } else if (modo === "rango") {
        const desde = document.querySelector("input[name='desde']").value;
        const hasta = document.querySelector("input[name='hasta']").value;
        if (!desde || !hasta) {
            Swal.fire({
                icon: 'warning',
                title: 'Faltan fechas',
                text: 'Por favor, ingresá ambas fechas: desde y hasta.',
                confirmButtonColor: '#ff6daf'
            });
            return false;
        }
        if (desde > hasta) {
            Swal.fire({
                icon: 'error',
                title: 'Fechas inválidas',
                text: "La fecha 'Desde' no puede ser mayor que 'Hasta'.",
                confirmButtonColor: '#ff6daf'
            });
            return false;
        }
    }

    return true;
}

// Ejecutar al cargar la página
document.addEventListener("DOMContentLoaded", mostrarCampos);
</script>