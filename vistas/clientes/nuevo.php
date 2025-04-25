<div class="content-wrapper">
    <!-- Encabezado -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Nuevo Cliente</h1>
                    <p class="mb-0">Registrar un nuevo cliente</p>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="?c=clientes">Clientes</a></li>
                        <li class="breadcrumb-item active">Nuevo</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Contenido principal -->
    <section class="content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Agregar nuevo cliente</h3>
                        </div>
                        <form action="?c=clientes&a=AgregarCliente" method="post">
                            <div class="card-body">
                                <div class="form-group">
                                    <label>Nombre:</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="nombre">
                                    </div>

                                </div>

                                <div class="form-group">
                                    <label>Teléfono:</label>

                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" name="telefono">
                                    </div>

                                </div>
                                <hr>
                                <h5>Datos del Vehículo</h5>
                                <div class="form-group">
                                    <label>Modelo</label>
                                    <input type="text" name="vehiculoNombre" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Número de Motor</label>
                                    <input type="text" name="numeroMotor" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Número de Chasis</label>
                                    <input type="text" name="numeroChasis" class="form-control">
                                </div>
                            </div>



                            <div class="card-footer d-flex justify-content-between">
                                <button type="button" onclick="history.back();" class="btn btn-secondary">
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
        </div>
    </section>
</div>