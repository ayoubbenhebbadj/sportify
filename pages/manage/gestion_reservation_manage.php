<?php
include("../../phpConfig/constants.php");


// Check login
if (!isset($_SESSION['gestion_id'])) {
    header("Location: ../login.php");
    exit();
}
$gestion_id = $_SESSION['gestion_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Mes Infrastructures - Réservations</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
        .main-page-content {
            margin-top: 2rem;
        }
    </style>
</head>
<body>
<div class="container main-page-content mt-4">
    <main class="main-content">
        <h1 class="text-center mb-4" style="font-size:1.7rem;"><i class="fas fa-calendar-alt"></i> Mes Infrastructures</h1>
        <div class="field-grid">
            <div class="row">
                <?php
                $stmt = $conn->prepare("SELECT id, name, description, location, image_name FROM infrastructure WHERE gestionnaire_id = ?");
                $stmt->bind_param("i", $gestion_id);
                $stmt->execute();
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc()):
                ?>
                <div class="col-md-4 mb-4">
                    <div class="card small-card">
                        <img src="../../img/infrastructure/<?= htmlspecialchars($row['image_name']) ?>" class="card-img-top" alt="Infrastructure Image">
                        <div class="card-body">
                            <h5 class="card-title small-title"><?= htmlspecialchars($row['name']) ?></h5>
                            <p class="card-text small-text"><?= htmlspecialchars($row['description']) ?></p>
                            <div class="d-flex justify-content-between">
                                <button class="btn btn-info btn-sm show-reservation-btn" 
                                        data-infra-id="<?= $row['id'] ?>" 
                                        data-infra-name="<?= htmlspecialchars($row['name']) ?>">
                                    <i class="fas fa-calendar-week"></i> <span class="d-none d-md-inline">Voir Calendrier</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </main>
</div>

<!-- Modal Calendar -->
<div class="modal fade" id="reservationCalendarModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="font-size:1.1rem;"><i class="fas fa-calendar"></i> Réservations - <span id="modal-infra-name" class="modal-infra-name-small"></span></h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body">
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Manage -->
<div class="modal fade" id="reservationManageModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" style="font-size:1.1rem;"><i class="fas fa-tasks"></i> Gérer la Réservation</h5>
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
      </div>
      <div class="modal-body reservation-modal-body-small">
        <p><strong>Utilisateur:</strong> <span id="modal-username" class="modal-value-small"></span></p>
        <p><strong>Date:</strong> <span id="modal-date" class="modal-value-small"></span></p>
        <p><strong>Heure:</strong> <span id="modal-time" class="modal-value-small"></span></p>
        <input type="hidden" id="reservation-id">
        <label for="status" class="form-label" style="font-size:0.95rem;">Changer le statut:</label>
        <select id="status" class="form-control form-control-sm">
          <option value="Pending">Pending</option>
          <option value="Approved">Approved</option>
          <option value="Rejected">Rejected</option>
        </select>
      </div>
      <div class="modal-footer">
        <button id="save-status" class="btn btn-success btn-sm">Enregistrer</button>
        <button id="cancel-reservation" class="btn btn-danger btn-sm">Annuler la Réservation</button>
        <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
let calendar = null;
$(document).ready(function () {
    $('.show-reservation-btn').on('click', function () {
        const infraId = $(this).data('infra-id');
        const infraName = $(this).data('infra-name');
        $('#modal-infra-name').text(infraName);

        if (calendar) $('#calendar').fullCalendar('destroy');

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
                url: 'fetch_gestion_events.php',
                method: 'GET',
                data: { infrastructure_id: infraId }
            },
            eventRender: function (event, element) {
                element.css('background-color', {
                    "Pending": "#ffc107",
                    "Approved": "#28a745",
                    "Rejected": "#dc3545"
                }[event.status]);
                element.attr('title', `Statut: ${event.status}\nUtilisateur: ${event.username}`);
                // Add Details icon
                const detailsBtn = $('<button class="btn btn-sm btn-light ms-2" style="float:right; font-size: 10px;"><i class="fas fa-info-circle"></i></button>');
                detailsBtn.on('click', function(e) {
                    e.stopPropagation();
                    $('#modal-username').text(event.username);
                    $('#modal-date').text(moment(event.start).format("YYYY-MM-DD"));
                    $('#modal-time').text(moment(event.start).format("HH:mm") + " - " + moment(event.end).format("HH:mm"));
                    $('#reservation-id').val(event.id);
                    $('#status').val(event.status);
                    $('#reservationManageModal').modal('show');
                });
                element.append(detailsBtn);
            },
            eventClick: function (event) {
                $('#modal-username').text(event.username);
                $('#modal-date').text(moment(event.start).format("YYYY-MM-DD"));
                $('#modal-time').text(moment(event.start).format("HH:mm") + " - " + moment(event.end).format("HH:mm"));
                $('#reservation-id').val(event.id);
                $('#status').val(event.status);
                $('#reservationManageModal').modal('show');
            }
        });

        new bootstrap.Modal(document.getElementById('reservationCalendarModal')).show();
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
