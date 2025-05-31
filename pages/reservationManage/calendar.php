<?php
include("../../phpConfig/constants.php");
$infrastructure_id = isset($_GET['infrastructure_id']) ? intval($_GET['infrastructure_id']) : 0;
if ($infrastructure_id <= 0) {
    die("Invalid or missing infrastructure ID.");
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 7; // fallback default
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sportify Reservation Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="calendar.css" />
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Sportify Reservation Calendar</h2>
    <div id="calendar"></div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="bookingForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Book a Slot</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="start_date" id="start_date" required>
<input type="hidden" name="infrastructure_id" id="infrastructure_id" value="<?php echo $infrastructure_id; ?>">

            <div class="form-group">
                <label for="time_slot">Start Time (HH:MM):</label>
                <input type="time" name="time_slot" id="time_slot" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="duration">Duration:</label>
                <select name="duration" id="duration" class="form-control" required>
                    <option value="90">1 hour 30 minutes</option>
                    <option value="180">3 hours</option>
                    <option value="270">4 hours 30 minutes</option>
                </select>
            </div>
            <input type="hidden" name="user_id" value="<?= $user_id ?>">
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Reserve</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- View Reservation Modal -->
<div class="modal fade" id="viewReservationModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Reservation Details</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <p><strong>Reservation ID:</strong> <span id="view_reservation_id"></span></p>
        <p><strong>Infrastructure:</strong> <span id="view_infrastructure_name"></span></p>
        <p><strong>Date:</strong> <span id="view_start_date"></span></p>
        <p><strong>Time:</strong> <span id="view_time_slot"></span> - <span id="view_end_time"></span></p>
        <p><strong>Duration:</strong> <span id="view_duration"></span> minutes</p>
        <p><strong>Status:</strong> <span id="view_status"></span></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-danger" id="cancelReservationBtn">Cancel Reservation</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
const infrastructureId = <?= $infrastructure_id ?>;
const userId = <?= $user_id ?>;

$(document).ready(function() {

    function loadInfrastructures() {
        $.getJSON('fetch_field.php', function(data) {
            const $select = $('#infrastructure');
            $select.empty().append('<option value="">Select Infrastructure</option>');
            data.forEach(function(infra) {
                const selected = (infra.id == infrastructureId) ? 'selected' : '';
                $select.append(`<option value="${infra.id}" ${selected}>${infra.name}</option>`);
            });
        });
    }

    function showReservationDetails(event) {
        $('#view_reservation_id').text(event.id);
        $('#view_infrastructure_name').text(event.infrastructure);
        $('#view_start_date').text(moment(event.start).format('YYYY-MM-DD'));
        $('#view_time_slot').text(moment(event.start).format('HH:mm'));
        $('#view_end_time').text(moment(event.end).format('HH:mm'));
        $('#view_duration').text(event.duration);
        $('#view_status').text(event.status);

        if ((event.status === 'Approved' || event.status === 'Pending') && event.user_id == userId) {
            $('#cancelReservationBtn').show().data('reservation-id', event.id);
        } else {
            $('#cancelReservationBtn').hide();
        }

        $('#viewReservationModal').modal('show');
    }

    function setupCalendar() {
        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'agendaWeek',
            selectable: true,
            selectHelper: true,
            allDaySlot: false,
            minTime: "08:00:00",
            maxTime: "22:00:00",
            slotDuration: "01:30:00",
            events: `fetch_events.php?infrastructure_id=${infrastructureId}`,
            eventRender: function(event, element) {
                element.css('background-color', event.color);
                element.attr('title', `Infrastructure: ${event.infrastructure}\nStatus: ${event.status}\nUser: ${event.username}`);

                // Add Details button
                const detailsBtn = $('<button class="btn btn-sm btn-light ml-2" style="float:right; font-size: 10px;">Details</button>');
                detailsBtn.on('click', function(e) {
                    e.stopPropagation(); // Prevent triggering the default calendar click
                    showReservationDetails(event);
                });
                element.append(detailsBtn);
            },
            eventClick: function(calEvent) {
                showReservationDetails(calEvent);
            },
            select: function(start, end) {
                if (moment().isAfter(start)) {
                    alert('Cannot book a slot in the past.');
                    $('#calendar').fullCalendar('unselect');
                    return;
                }

                $('#start_date').val(moment(start).format('YYYY-MM-DD'));
                $('#time_slot').val(moment(start).format('HH:mm'));
                $('#bookingModal').modal('show');
            }
        });
    }

    function handleBookingForm() {
        $('#bookingForm').submit(function(e) {
            e.preventDefault();
            $.post('book_slot.php', $(this).serialize(), function(response) {
                try {
                    const res = JSON.parse(response);
                    alert(res.message);
                    if (res.status === "success") {
                        $('#bookingModal').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                } catch (err) {
                    alert("Unexpected response: " + response);
                }
            }).fail(function(xhr, status, error) {
                alert("Booking failed: " + error);
            });
        });
    }

    function handleCancelReservation() {
        $('#cancelReservationBtn').on('click', function() {
            const reservationId = $(this).data('reservation-id');
            if (confirm('Are you sure you want to cancel this reservation?')) {
                $.post('cancel_reservation.php', { id: reservationId, user_id: userId })
    .done(function(response) {
        // ✅ If `response` is a string, parse it. If already an object, skip parsing.
        let res;
        try {
            res = typeof response === 'string' ? JSON.parse(response) : response;
        } catch (e) {
            alert("Invalid JSON: " + response);
            return;
        }

        alert(res.message);
        if (res.status === "success") {
            $('#viewReservationModal').modal('hide');
            $('#calendar').fullCalendar('refetchEvents');
        }
    })
    .fail(function(xhr, status, error) {
        alert("Cancellation failed: " + error);
    });

            }
        });
    }

    loadInfrastructures();
    setupCalendar();
    handleBookingForm();
    handleCancelReservation();
});

</script>
</body>
</html>
