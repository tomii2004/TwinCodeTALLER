
document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');

  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth'
    },
    initialView: 'dayGridMonth',
    themeSystem: 'bootstrap',
    locale: 'es',
    events: 'index.php?c=turnos&a=obtenerTurnosJson',
    editable: false,

    dateClick: function (info) {
      // Guardamos la fecha seleccionada en el input oculto
      document.getElementById('fechaTurnoModal').value = info.dateStr;
      // Limpiamos el campo del nombre
      document.getElementById('nombreTurnoModal').value = '';
      // Mostramos el modal
      $('#modalTurno').modal('show');
    },
    eventClick: function (info) {
      Swal.fire({
        title: '¿Eliminar turno?',
        text: `Cliente: ${info.event.title}`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
      }).then((result) => {
        if (result.isConfirmed) {
          $.ajax({
            url: 'index.php?c=turnos&a=eliminarTurnoDesdeCalendario',
            method: 'POST',
            data: {
              id: info.event.id
            },
            success: function () {
              calendar.refetchEvents();
              Swal.fire({
                icon: 'success',
                title: 'Turno eliminado',
                showConfirmButton: false,
                timer: 1200
              });
            },
            error: function () {
              Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo eliminar el turno'
              });
            }
          });
        }
      });
    },
    eventContent: function (info) {
      var eventTitle = document.createElement('div');
      eventTitle.classList.add('fc-title');
      eventTitle.innerText = info.event.title; // solo el nombre, sin hora
      return { domNodes: [eventTitle] };
    }
  });

  calendar.render();

  // Manejamos el submit del modal
  $('#formTurnoModal').on('submit', function (e) {
    e.preventDefault();

    const nombre = $('#nombreTurnoModal').val();
    const fecha = $('#fechaTurnoModal').val();

    $.ajax({
      url: 'index.php?c=turnos&a=guardarTurnoDesdeCalendario',
      method: 'POST',
      data: {
        nombre: nombre,
        fecha: fecha
      },
      success: function (response) {
        $('#modalTurno').modal('hide');
        calendar.refetchEvents();

        Swal.fire({
          icon: 'success',
          title: 'Turno guardado con éxito',
          showConfirmButton: false,
          timer: 1500
        });
      },
      error: function () {
        Swal.fire({
          icon: 'error',
          title: 'Hubo un error',
          text: 'No se pudo guardar el turno',
        });
      }
    });
  });
});


