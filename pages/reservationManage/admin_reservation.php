<!DOCTYPE html>
<html>
<head>
    <title>Admin Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="admin_reservation.css"/>
</head>
<body>
<div class="container mt-4">
    <h2>Admin Reservation Calendar</h2>
    <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#adminBookingModal">New Reservation</button>
    <div id="calendar"></div>
</div>

<!-- Admin Booking Modal -->
<div class="modal fade" id="adminBookingModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="adminBookingForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create Reservation (as Admin)</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="admin_user_id">User</label>
                <select name="user_id" id="admin_user_id" class="form-control" required>
                  <option value="">Loading...</option>
                </select>
            </div>
            <div class="form-group">
                <label for="admin_infra_id">Infrastructure</label>
                <select name="infrastructure_id" id="admin_infra_id" class="form-control" required>
                  <option value="">Loading...</option>
                </select>
            </div>
            <div class="form-group">
                <label for="admin_start_date">Date</label>
                <input type="date" name="start_date" id="admin_start_date" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="admin_time_slot">Start Time (HH:MM)</label>
                <input type="time" name="time_slot" id="admin_time_slot" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="admin_duration">Duration</label>
                <select name="duration" id="admin_duration" class="form-control" required>
                    <option value="90">1 hour 30 minutes</option>
                    <option value="180">3 hours</option>
                    <option value="270">4 hours 30 minutes</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Reserve</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
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
            url: 'fetch_events.php', // No filtering by infrastructure for admin
            method: 'GET'
        },
        eventClick: function(event) {
            $('#modal-username').text(event.username);
            $('#modal-date').text(moment(event.start).format("YYYY-MM-DD"));
            $('#modal-time').text(moment(event.start).format("HH:mm") + " - " + moment(event.end).format("HH:mm"));
            $('#modal-infra').text(event.infrastructure); // Show infrastructure name
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

    // Populate users and infrastructures for admin booking
    function loadAdminBookingDropdowns() {
        $.getJSON('fetch_all_users.php', function(users) {
            let $user = $('#admin_user_id');
            $user.empty().append('<option value="">Select user</option>');
            users.forEach(u => $user.append(`<option value="${u.id}">${u.firstname} ${u.lastname} (${u.username})</option>`));
        });
        $.getJSON('fetch_all_infrastructures.php', function(infras) {
            let $infra = $('#admin_infra_id');
            $infra.empty().append('<option value="">Select infrastructure</option>');
            infras.forEach(i => $infra.append(`<option value="${i.id}">${i.name}</option>`));
        });
    }
    $('#adminBookingModal').on('show.bs.modal', loadAdminBookingDropdowns);

    // Admin booking form submit
    $('#adminBookingForm').submit(function(e) {
        e.preventDefault();
        $.post('book_slot.php', $(this).serialize(), function(response) {
            try {
                const res = typeof response === 'object' ? response : JSON.parse(response);
                alert(res.message);
                if (res.status === "success") {
                    $('#adminBookingModal').modal('hide');
                    $('#calendar').fullCalendar('refetchEvents');
                }
            } catch (err) {
                alert("Unexpected response: " + response);
            }
        }).fail(function(xhr, status, error) {
            alert("Booking failed: " + error);
        });
    });
});
</script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
