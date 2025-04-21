
    <div class="content-wrapper">
        <!-- Encabezado -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Nueva Producto</h1>
                        <p class="mb-0">Definir Producto</p>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                            <li class="breadcrumb-item"><a href="?c=productos">Productos</a></li>
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
                                <h3 class="card-title">Agregar nuevo producto</h3>
                            </div>
                            <form action="?c=productos&a=AñadirProducto" method="post">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="nombre">Nombre:</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre" autocomplete="off" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="categoria">Categoria:</label>
                                        <select class="form-control" id="categoria" name="categoria" required>
                                            <option value="">Seleccionar tipo</option>
                                            <?php foreach($categorias as $categoria){?>
                                                <option value="<?php echo $categoria['ID_categoria'] ?>"><?php echo ucfirst($categoria['Nombre']); ?></option>
                                            <?php } ?>
                                        </select>
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
