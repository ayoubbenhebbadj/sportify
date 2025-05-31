<!DOCTYPE html>
<html>
<head>
    <title>Admin Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
</head>
<body>
<div class="container mt-4">
    <h2>Admin Reservation Calendar</h2>
    <div id="calendar"></div>
</div>

<!-- Modal -->
<div class="modal fade" id="reservationModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Manage Reservation</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>User:</strong> <span id="modal-username"></span></p>
        <p><strong>Date:</strong> <span id="modal-date"></span></p>
        <p><strong>Time:</strong> <span id="modal-time"></span></p>
        <p><strong>Infrastructure:</strong> <span id="modal-infra"></span></p>

        <input type="hidden" id="reservation-id">

        <label for="status">Change Status:</label>
        <select id="status" class="form-control">
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
        </select>
      </div>
      <div class="modal-footer">
        <button id="save-status" class="btn btn-success">Save</button>
        <button class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>

<script>
$(document).ready(function() {
    $('#calendar').fullCalendar({
        defaultView: 'agendaWeek',
        allDaySlot: false,
        slotDuration: '00:30:00',
        events: {
            url: 'fetch_events.php',
            method: 'GET'
        },
        eventClick: function(event) {
            $('#modal-username').text(event.username);
            $('#modal-date').text(moment(event.start).format("YYYY-MM-DD"));
            $('#modal-time').text(moment(event.start).format("HH:mm") + " - " + moment(event.end).format("HH:mm"));
            $('#modal-infra').text(event.infrastructure);
            $('#reservation-id').val(event.id);
            $('#status').val(event.status);
            $('#reservationModal').modal('show');
        }
    });

    $('#save-status').on('click', function() {
        const id = $('#reservation-id').val();
        const newStatus = $('#status').val();

        $.post("update_reservation_status.php", {
            id: id,
            status: newStatus
        }, function(response) {
            $('#reservationModal').modal('hide');
            $('#calendar').fullCalendar('refetchEvents');
            alert(response.message);
        }, 'json');
    });
});
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
