<?php
include("../phpConfig/constants.php");
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sportify Reservation Calendar</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
</head>
<body>
<div class="container mt-4">
    <h2 class="text-center">Sportify Reservation Calendar</h2>
    <div id="calendar"></div>
</div>

<!-- Modal for Booking -->
<div class="modal fade" id="bookingModal" tabindex="-1" role="dialog" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <form id="bookingForm">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Book a Slot</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
            <input type="hidden" name="start_date" id="start_date" required>
            <input type="hidden" name="end_date" id="end_date" required>
            <div class="form-group">
                <label for="infrastructure">Choose Infrastructure:</label>
                <select name="infrastructure_id" id="infrastructure" class="form-control" required>
                    <option value="">Loading...</option>
                </select>
            </div>
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
            <input type="hidden" name="user_id" value="1"> <!-- replace with session ID later -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Reserve</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function() {
    // Fetch infrastructures for dropdown
    $.ajax({
        url: 'fetch_field.php',
        method: 'GET',
        dataType: 'json',
        success: function(data) {
            $('#infrastructure').empty().append('<option value="">Select Infrastructure</option>');
            $.each(data, function(index, infra) {
                $('#infrastructure').append('<option value="'+infra.id+'">'+infra.name+'</option>');
            });
        }
    });

    // Initialize FullCalendar
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
        events: 'fetch_events.php',
        eventRender: function(event, element) {
            element.css('background-color', event.color);
            
        },
        
        select: function(start, end) {
            $('#start_date').val(moment(start).format('YYYY-MM-DD'));
            $('#end_date').val(moment(end).format('YYYY-MM-DD'));
            $('#time_slot').val(moment(start).format('HH:mm'));
            $('#bookingModal').modal('show');
        }
    });

    // Handle form submission
    $('#bookingForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'book_slot.php',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                try {
                    let res = JSON.parse(response);
                    alert(res.message);
                    if (res.status === "success") {
                        $('#bookingModal').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                } catch (e) {
                    alert("Unexpected response: " + response);
                }
            }
        });
    });
});
</script>

</body>
</html>
