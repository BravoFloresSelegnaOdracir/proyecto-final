<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar</title>
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="includes/calendar.css">

</head>
<body>
    <h1>Preventive Maintenances</h1>
    <div id="calendar"></div>

    <div class="modal-overlay" id="modalOverlay"></div>
    <div class="modal" id="maintenanceModal">
        <div class="modal-header" id="modalTitle"></div>
        <div class="modal-body" id="modalBody"></div>
        <div class="modal-footer">
            <button onclick="closeModal()">Cerrar</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: function (fetchInfo, successCallback, failureCallback) {
                $.ajax({
                    url: 'data/getPreventiveMaintenance.php',
                    method: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        console.log("Datos recibidos:", data);
                        let events = data.map(item => ({
                            title: `${item.nombre} (${item.estadoMantenimiento})`,
                            start: item.fechaProgramada,
                            color: item.estadoMantenimiento === 'In Process' ? 'orange' : 'green',
                            extendedProps: {
                                equipo: item.equipo,
                                tipo: item.tipoMantenimiento,
                                tecnico: item.tecnico,
                                estado: item.estadoMantenimiento
                            }
                        }));
                        successCallback(events);
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("Error al cargar eventos:", textStatus, errorThrown);
                        failureCallback();
                    }
                });
            },
            eventClick: function (info) {
                showModal(info.event);
            }
        });
        calendar.render();
    });

    function showModal(event) {
        const modal = document.getElementById('maintenanceModal');
        const overlay = document.getElementById('modalOverlay');

        document.getElementById('modalTitle').innerText = `Detalles del Mantenimiento (${event.extendedProps.tipo || "N/A"})`;
        document.getElementById('modalBody').innerHTML = `
            <p><strong>Equipment:</strong> ${event.extendedProps.equipo || "Unknown"}</p>
            <p><strong>Technician:</strong> ${event.extendedProps.tecnico || "Not assigned"}</p>
            <p><strong>Status:</strong> ${event.extendedProps.estado || "N/A"}</p>
            <p><strong>Scheduled Date:</strong> ${event.start ? event.start.toLocaleDateString() : "N/A"}</p>
        `;

        modal.classList.add('active');
        overlay.classList.add('active');
    }

    function closeModal() {
        const modal = document.getElementById('maintenanceModal');
        const overlay = document.getElementById('modalOverlay');
        modal.classList.remove('active');
        overlay.classList.remove('active');
    }
</script>
</body>
</html>