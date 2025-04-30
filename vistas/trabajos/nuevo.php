<?php
// Extraemos las categorías distintas del array de productos
$categoriasUnicas = array_unique(array_column($productos, 'nombrecat'));
?>

<div class="content-wrapper">
    <form id="formTrabajo" action="?c=trabajos&a=guardarTrabajo" method="POST">
        <!-- Agrego el ID al form, y encierro todo -->

        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Nuevo Trabajo</h1>

                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label for="cliente">Cliente</label>
                        <select id="cliente" name="cliente" class="form-control form-control-sm">
                            <option value="">Seleccionar Cliente</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="vehiculo">Vehículo</label>
                        <select id="vehiculo" name="vehiculo" class="form-control form-control-sm" disabled>
                            <option value="">Seleccionar Vehículo</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="chasis">Número de Chasis</label>
                        <input type="text" id="chasis" class="form-control form-control-sm" readonly disabled>
                    </div>

                    <div class="col-md-6">
                        <label for="motor">Número de Motor</label>
                        <input type="text" id="motor" class="form-control form-control-sm" readonly disabled>
                    </div>
                    <!-- Coloca estos dos botones en una fila distinta para que se alineen mejor -->
                    <div class="col-md-12 d-flex mt-3">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#nuevoClienteModal">
                            <i class="fas fa-plus"></i> Nuevo Cliente
                        </button>
                    </div>
                </div>

        </section>

        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Presupuesto</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="tablaPresupuesto">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Cantidad</th>
                                            <th>Precio</th>
                                            <th>Subtotal</th>
                                            <th>Quitar</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                    <tfoot>
                                        <tr>
                                            <td><strong>Total</strong></td>
                                            <td colspan="3" id="totalPresupuesto">$0.00</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- Aquí pegás el mini recuadro de Nota -->
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="nota">Nota</label>
                                    <textarea id="nota"
                                        name="nota"
                                        class="form-control form-control-sm"
                                        rows="3"
                                        placeholder="Escribe una nota..."></textarea>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-danger btn-sm mr-2" id="limpiarPresupuesto">
                                    <i class="fas fa-trash"></i> Limpiar
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-save"></i> Guardar Trabajo
                                </button>
                            </div>
                        </div>

                    </div>

                    <!-- Agregamos los inputs ocultos -->
                    <input type="hidden" id="productos" name="productos" value="[]">
                    <input type="hidden" id="totalPresupuestoHidden" name="totalPresupuesto" value="0">

                    <div class="col-md-6">
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <input type="text" id="buscadorProducto" class="form-control form-control-sm" placeholder="Buscar producto...">
                            </div>
                            <div class="col-md-6">
                                <select id="filtroCategoria" class="form-control form-control-sm">
                                    <option value="todas">Todas las categorías</option>
                                    <?php foreach ($categoriasUnicas as $cat): ?>
                                        <option value="<?= strtolower($cat) ?>"><?= ucfirst($cat) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Productos disponibles</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-hover text-nowrap" id="tablaProductos">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Categoría</th>
                                            <th>Agregar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productos as $producto): ?>
                                            <tr>
                                                <td><?= ucfirst($producto['nombre']) ?></td>
                                                <td class="categoria"><?= ucfirst($producto['nombrecat']) ?></td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-success btn-sm agregarProducto"
                                                        data-id="<?= $producto['ID_productos'] ?>"
                                                        data-nombre="<?= htmlspecialchars($producto['nombre']) ?>">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>



                    </div> <!-- col-md-6 productos -->
                </div> <!-- row -->
            </div> <!-- container-fluid -->
        </section>

    </form> <!-- cierre form -->
</div>
<!-- Modal -->
<div class="modal fade" id="nuevoClienteModal" tabindex="-1" aria-labelledby="nuevoClienteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="nuevoClienteModalLabel">Agregar nuevo cliente</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="?c=clientes&a=AgregarClienteEnTrabajo" method="post" class="needs-validation" novalidate autocomplete="off">
                <div class="modal-body">
                    <!-- Información del Cliente -->
                    <div class="form-group">
                        <label for="nombre">Nombre:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                            </div>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                            <div class="invalid-feedback">Por favor, ingrese el nombre.</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="telefono">Teléfono:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                            </div>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                            <div class="invalid-feedback">Por favor, ingrese el teléfono.</div>
                        </div>
                    </div>

                    <hr>
                    <h5>Datos del Vehículo</h5>

                    <div class="form-group">
                        <label for="vehiculoNombre">Modelo</label>
                        <input type="text" name="vehiculoNombre" id="vehiculoNombre" class="form-control" required oninput="this.value = this.value.toUpperCase()" >
                        <div class="invalid-feedback">Por favor, ingrese el modelo.</div>
                    </div>

                    <div class="form-group">
                        <label for="numeroMotor">Número de Motor</label>
                        <input type="text" name="numeroMotor" id="numeroMotor" class="form-control" required oninput="this.value = this.value.toUpperCase()">
                        <div class="invalid-feedback">Por favor, ingrese el número de motor.</div>
                    </div>

                    <div class="form-group">
                        <label for="numeroChasis">Número de Chasis</label>
                        <input type="text" name="numeroChasis" id="numeroChasis" class="form-control" required oninput="this.value = this.value.toUpperCase()">
                        <div class="invalid-feedback">Por favor, ingrese el número de chasis.</div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-arrow-left"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const forms = document.querySelectorAll('.needs-validation');
  Array.from(forms).forEach(form => {
    form.addEventListener('submit', function (event) {
      if (!form.checkValidity()) {
        event.preventDefault();
        event.stopPropagation();
      }
      form.classList.add('was-validated');
    });
  });
});
</script>


<script>
    // Alerta con SweetAlert2
    document.addEventListener("DOMContentLoaded", function() {
        const urlParams = new URLSearchParams(window.location.search);
        const alerta = urlParams.get("alerta");

        if (alerta === "success") {
            Swal.fire({
                icon: 'success',
                title: 'Registro Exitoso',
                text: 'Cliente y vehiculo añadido correctamente.',
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
    document.addEventListener('DOMContentLoaded', () => {
        // ——— Elementos del DOM ———
        const form = document.getElementById('formTrabajo');
        const tablaPresupuesto = document.querySelector('#tablaPresupuesto tbody');
        const totalPresupuestoEl = document.getElementById('totalPresupuesto');
        const productosHidden = document.getElementById('productos');
        const totalHidden = document.getElementById('totalPresupuestoHidden');

        // Para buscador y filtro
        const buscador = document.getElementById('buscadorProducto');
        const filtroCategoria = document.getElementById('filtroCategoria');
        const rowsProductos = document.querySelectorAll('#tablaProductos tbody tr');

        let total = 0;
        let productosPresupuesto = [];

        // Capitalizar nombre
        function capitalizarNombre(nombre) {
            return nombre
                .toLowerCase()
                .split(' ')
                .map(p => p.charAt(0).toUpperCase() + p.slice(1))
                .join(' ');
        }

        // ——— Al hacer click en “+ Agregar” ———
        document.querySelectorAll('.agregarProducto').forEach(btn => {
            btn.addEventListener('click', async () => {
                const idProducto = btn.dataset.id;
                const nombre = capitalizarNombre(btn.dataset.nombre);

                const {
                    value: importe
                } = await Swal.fire({
                    title: 'Ingrese el importe',
                    input: 'number',
                    inputLabel: nombre,
                    inputPlaceholder: 'Ej: 100.50',
                    inputAttributes: {
                        min: 0,
                        step: 'any'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Agregar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: v => {
                        if (!v) return 'Debe ingresar un valor';
                        if (isNaN(v) || parseFloat(v) <= 0) return 'El valor debe ser un número positivo';
                    }
                });
                if (importe === undefined) return;

                const valorUnitario = parseFloat(importe);
                let cantidad = 1;
                let subtotal = valorUnitario;

                // Crear fila en presupuesto
                const tr = document.createElement('tr');
                tr.innerHTML = `
                <td>${nombre}</td>
                <td>
                  <input type="number" value="1" min="1" class="cantidadPresupuesto form-control form-control-sm" style="width:70px;">
                </td>
                <td>$${valorUnitario.toFixed(2)}</td>
                <td class="subtotalPresupuesto">$${subtotal.toFixed(2)}</td>
                <td>
                  <button type="button" class="btn btn-danger btn-sm quitar"><i class="fas fa-times"></i></button>
                </td>
            `;
                tablaPresupuesto.appendChild(tr);

                // Agregar al array y total
                productosPresupuesto.push({
                    id_producto: idProducto,
                    nombre,
                    importe: valorUnitario,
                    cantidad
                });
                total += subtotal;
                totalPresupuestoEl.innerText = `$${total.toFixed(2)}`;

                // — Cambio de cantidad —
                tr.querySelector('.cantidadPresupuesto').addEventListener('input', e => {
                    const nueva = parseInt(e.target.value) || 1;
                    const diff = (nueva - cantidad) * valorUnitario;
                    cantidad = nueva;
                    subtotal = cantidad * valorUnitario;
                    tr.querySelector('.subtotalPresupuesto').innerText = `$${subtotal.toFixed(2)}`;
                    total += diff;
                    totalPresupuestoEl.innerText = `$${total.toFixed(2)}`;
                    productosPresupuesto.find(p => p.id_producto == idProducto).cantidad = cantidad;
                });

                // — Quitar producto —
                tr.querySelector('.quitar').addEventListener('click', () => {
                    total -= subtotal;
                    totalPresupuestoEl.innerText = `$${total.toFixed(2)}`;
                    tr.remove();
                    productosPresupuesto = productosPresupuesto.filter(p => p.id_producto != idProducto);
                });
            });
        });

        // ——— Al enviar el formulario ———
        form.addEventListener('submit', e => {
            e.preventDefault();
            if (productosPresupuesto.length === 0) {
                return Swal.fire('Atención', 'Debes agregar al menos un producto.', 'warning');
            }
            // Serializar a JSON en hidden inputs
            productosHidden.value = JSON.stringify(productosPresupuesto);
            totalHidden.value = total.toFixed(2);
            form.submit();
        });



        // 🔍 Buscador en vivo
        if (buscador) {
            buscador.addEventListener('input', function() {
                const texto = this.value.toLowerCase();
                rowsProductos.forEach(fila => {
                    fila.style.display = fila.textContent.toLowerCase().includes(texto) ? '' : 'none';
                });
            });
        }

        // 📂 Filtro de categoría
        if (filtroCategoria) {
            filtroCategoria.addEventListener('change', function() {
                const categoriaSeleccionada = this.value.toLowerCase();
                rowsProductos.forEach(fila => {
                    const categoria = fila.querySelector('.categoria').textContent.toLowerCase();
                    fila.style.display = (categoriaSeleccionada === 'todas' || categoria.includes(categoriaSeleccionada)) ? '' : 'none';
                });
            });
        }




        // 🗑️ Limpiar presupuesto
        const btnLimpiar = document.getElementById('limpiarPresupuesto');
        if (btnLimpiar) {
            btnLimpiar.addEventListener('click', () => {
                if (productosPresupuesto.length === 0) {
                    return Swal.fire('Atención', 'No hay productos que quitar.', 'info');
                }

                Swal.fire({
                    title: '¿Limpiar presupuesto?',
                    text: "Se eliminarán todos los productos.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Sí, limpiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        tablaPresupuesto.innerHTML = '';
                        total = 0;
                        productosPresupuesto = [];
                        totalPresupuesto.innerText = '$0.00';
                        Swal.fire('Listo', 'Presupuesto vacío.', 'success');
                    }
                });
            });
        }

    });



    document.addEventListener('DOMContentLoaded', function() {
        const clienteSelect = document.getElementById('cliente');
        const vehiculoSelect = document.getElementById('vehiculo');
        const chasisInput = document.getElementById('chasis');
        const motorInput = document.getElementById('motor');

        // Cargar clientes cuando se cargue la página
        fetchClientes();

        // Evento para seleccionar cliente
        clienteSelect.addEventListener('change', function() {
            const clienteId = this.value;
            if (clienteId) {
                fetchVehiculos(clienteId);
            } else {
                // Limpiar datos si no hay cliente seleccionado
                vehiculoSelect.innerHTML = '<option value="">Seleccionar Vehículo</option>';
                vehiculoSelect.disabled = true;
                chasisInput.value = '';
                motorInput.value = '';
                chasisInput.disabled = true;
                motorInput.disabled = true;
            }
        });

        // Evento para seleccionar vehículo
        vehiculoSelect.addEventListener('change', function() {
            const vehiculoId = this.value;
            if (vehiculoId) {
                fetchDatosVehiculo(vehiculoId);
            } else {
                chasisInput.value = '';
                motorInput.value = '';
                chasisInput.disabled = true;
                motorInput.disabled = true;
            }
        });

        // Función para obtener los clientes
        function fetchClientes() {
            fetch('?c=clientes&a=obtenerClientes') // Cambia esta URL según tu controlador
                .then(response => response.json())
                .then(data => {
                    data.forEach(cliente => {
                        const option = document.createElement('option');
                        option.value = cliente.ID_cliente;
                        option.textContent = cliente.Nombre;
                        clienteSelect.appendChild(option);
                    });
                });
        }

        // Función para obtener los vehículos de un cliente
        function fetchVehiculos(clienteId) {
            fetch(`?c=vehiculos&a=obtenerVehiculosPorCliente&id_cliente=${clienteId}`)
                .then(response => response.json())
                .then(data => {
                    vehiculoSelect.innerHTML = '<option value="">Seleccionar Vehículo</option>';
                    data.forEach(vehiculo => {
                        const option = document.createElement('option');
                        option.value = vehiculo.ID_vehiculo;
                        option.textContent = vehiculo.Nombre;
                        vehiculoSelect.appendChild(option);
                    });
                    vehiculoSelect.disabled = false;
                });
        }

        // Función para obtener los datos del vehículo (chasis y motor)
        function fetchDatosVehiculo(vehiculoId) {
            fetch(`?c=vehiculos&a=obtenerDatosVehiculo&id_vehiculo=${vehiculoId}`)
                .then(response => response.json())
                .then(data => {
                    chasisInput.value = data.Numero_Chasis;
                    motorInput.value = data.Numero_Motor;
                    chasisInput.disabled = false;
                    motorInput.disabled = false;
                });
        }
    });
</script>