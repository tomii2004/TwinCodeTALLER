

<div class="content-wrapper">
  <section class="content-header">
    <h1>Calendario de Turnos</h1>
  </section>

  <section class="content">
    <div class="row">
      <div class="col-md-12"> <!-- CAMBIADO A 12 PARA OCUPAR TODO EL ANCHO -->
        <div class="card card-primary">
          <div class="card-body p-0">
            <!-- Calendario -->
            <div id="calendar" style="padding: 10px;"></div>

            <!-- Modal -->
            <div class="modal fade" id="modalTurno" tabindex="-1" aria-labelledby="modalTurnoLabel" aria-hidden="true">
              <div class="modal-dialog">
                <form id="formTurnoModal">
                  <div class="modal-content">
                    <div class="modal-header bg-red">
                      <h5 class="modal-title" id="modalTurnoLabel">Agregar Turno</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <input type="hidden" id="fechaTurnoModal" name="fecha">
                      <div class="form-group">
                        <label for="nombreTurnoModal">Nombre del Cliente</label>
                        <input type="text" class="form-control" id="nombreTurnoModal" name="nombre" autocomplete='off' required>
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Guardar Turno</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!-- Fin Modal -->
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
