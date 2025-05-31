<?php include("../../phpConfig/constants.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations par Infrastructure</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="../style/admin_reservation_manage.css">
    <style>
        .small-card {
            font-size: 0.9rem;
        }
        .small-title {
            font-size: 1.2rem;
        }
        .small-text {
            font-size: 0.8rem;
        }
        .reservation-modal-body-small {
            font-size: 0.9rem;
        }
        .modal-title {
            font-size: 1.1rem;
        }
        .modal-infra-name-small {
            font-size: 1rem;
        }
        .modal-value-small {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
<div class="container main-page-content mt-4">
    <main class="main-content">
        <h1 class="text-center mb-4" style="font-size:1.7rem;"><i class="fas fa-calendar-alt"></i> Gestion des Réservations par Infrastructure</h1>
        <div class="field-grid">
            <div class="row">
            <?php
            $sql = "SELECT id, name, description, location, image_name FROM infrastructure";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $id, $name, $description, $location, $image_name);
            while (mysqli_stmt_fetch($stmt)) {
            ?>
                <div class="col-md-4">
                    <div class="card small-card">
                        <img src="../../img/infrastructure/<?php echo htmlspecialchars($image_name); ?>" class="card-img-top" alt="Infrastructure Image">
                        <div class="card-body">
                            <h5 class="card-title small-title"><?php echo htmlspecialchars($name); ?></h5>
                            <p class="card-text small-text"><?php echo htmlspecialchars($description); ?></p>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-info btn-sm show-reservation-btn" data-infra-id="<?php echo $id; ?>" data-infra-name="<?php echo htmlspecialchars($name); ?>">
                                    <i class="fas fa-calendar-week"></i> <span class="d-none d-md-inline">Show Reservations</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            }
            mysqli_stmt_close($stmt);
            ?>
            </div>
        </div>
    </main>
</div>

<!-- Modal for Calendar -->
<div class="modal fade" id="reservationCalendarModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="font-size:1.1rem;"><i class="fas fa-calendar"></i> Reservations for <span id="modal-infra-name" class="modal-infra-name-small"></span></h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>
<!-- Reservation Manage Modal -->
<div class="modal fade" id="reservationManageModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="font-size:1.1rem;"><i class="fas fa-tasks"></i> Manage Reservation</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body reservation-modal-body-small">
        <p><strong>User:</strong> <span id="modal-username" class="modal-value-small"></span></p>
        <p><strong>Date:</strong> <span id="modal-date" class="modal-value-small"></span></p>
        <p><strong>Time:</strong> <span id="modal-time" class="modal-value-small"></span></p>
        <p><strong>Infrastructure:</strong> <span id="modal-infra" class="modal-value-small"></span></p>
        <input type="hidden" id="reservation-id">
        <label for="status" class="form-label" style="font-size:0.95rem;">Change Status:</label>
        <select id="status" class="form-control form-control-sm">
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="Rejected">Rejected</option>
        </select>
      </div>
      <div class="modal-footer">
          <button id="save-status" class="btn btn-success btn-sm">Save</button>
          <button id="cancel-reservation" class="btn btn-danger btn-sm">Cancel Reservation</button>
          <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<!-- FullCalendar & Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
let calendar = null;
$(document).ready(function() {
    $('.show-reservation-btn').on('click', function() {
        const infraId = $(this).data('infra-id');
        const infraName = $(this).data('infra-name');
        $('#modal-infra-name').text(infraName);

        if (calendar) {
            $('#calendar').fullCalendar('destroy');
        }

        $('#calendar').fullCalendar({
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultView: 'agendaWeek',
            allDaySlot: false,
            minTime: "08:00:00",
            maxTime: "22:00:00",
            slotDuration: "01:30:00",
            events: {
                url: '../reservationManage/fetch_events.php',
                method: 'GET',
                data: { infrastructure_id: infraId }
            },
            eventRender: function(event, element) {
                element.css('background-color', event.color || '#00BFFF');
                element.attr('title', `Infrastructure: ${event.infrastructure}\nStatus: ${event.status}\nUser: ${event.username}`);
                // Add Details icon
                const detailsBtn = $('<button class="btn btn-sm btn-light ml-2" style="float:right; font-size: 10px;"><i class="fas fa-info-circle"></i></button>');
                detailsBtn.on('click', function(e) {
                    e.stopPropagation();
                    $('#modal-username').text(event.username);
                    $('#modal-date').text(moment(event.start).format("YYYY-MM-DD"));
                    $('#modal-time').text(moment(event.start).format("HH:mm") + " - " + moment(event.end).format("HH:mm"));
                    $('#modal-infra').text(event.infrastructure);
                    $('#reservation-id').val(event.id);
                    $('#status').val(event.status);
                    $('#reservationManageModal').modal('show');
                });
                element.append(detailsBtn);
            },
            eventClick: function(event) {
                $('#modal-username').text(event.username);
                $('#modal-date').text(moment(event.start).format("YYYY-MM-DD"));
                $('#modal-time').text(moment(event.start).format("HH:mm") + " - " + moment(event.end).format("HH:mm"));
                $('#modal-infra').text(event.infrastructure);
                $('#reservation-id').val(event.id);
                $('#status').val(event.status);
                $('#reservationManageModal').modal('show');
            }
        });

        $('#reservationCalendarModal').modal('show');
    });

    $('#save-status').on('click', function () {
        $.post('update_reservation_status.php', {
            id: $('#reservation-id').val(),
            status: $('#status').val()
        }, function (response) {
            $('#reservationManageModal').modal('hide');
            $('#calendar').fullCalendar('refetchEvents');
            alert(response);
        });
    });
    $('#cancel-reservation').on('click', function () {
        if (confirm("Êtes-vous sûr de vouloir annuler cette réservation ?")) {
            const reservationId = $('#reservation-id').val();

            $.post('cancel_reservation.php', { id: reservationId }, function (response) {
                $('#reservationManageModal').modal('hide');
                $('#calendar').fullCalendar('refetchEvents');
                alert(response);
            });
        }
    });

    // Destroy calendar when modal is closed so it refreshes for next field
    $('#reservationCalendarModal').on('hidden.bs.modal', function () {
        if ($('#calendar').data('fullCalendar')) {
            $('#calendar').fullCalendar('destroy');
            calendar = null;
        }
    });

    // Destroy calendar when reservation manage modal is closed so it refreshes when returning
    $('#reservationManageModal').on('hidden.bs.modal', function () {
        if ($('#calendar').data('fullCalendar')) {
            $('#calendar').fullCalendar('destroy');
            calendar = null;
        }
    });
});
</script>
</body>
</html>
