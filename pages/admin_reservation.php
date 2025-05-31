<?php
// admin_reservation.php
// This page is for testing and does NOT implement user authentication.
// Anyone with the URL can access and modify reservation statuses.
include("constants.php"); // Include the database connection constants

// No session_start() or authentication checks for this test version.
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Administration des Réservations</title>

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
            line-height: 1.6;
            color: #333;
            background-color: #f8f8f8;
        }
        #calendar {
            max-width: 1000px;
            margin: 20px auto;
            padding: 15px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        h2 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        p.info {
            text-align: center;
            color: #555;
            font-size: 0.9em;
            margin-bottom: 20px;
        }
        .fc-event {
            cursor: pointer;
            border-radius: 4px;
            padding: 2px 4px;
            font-size: 0.85em;
            border: 1px solid transparent; /* Default transparent border */
        }

        /* Legend Styling */
        .legend {
            margin: 30px auto 10px;
            padding: 15px;
            border: 1px solid #e0e0e0;
            background-color: #fdfdfd;
            border-radius: 8px;
            display: table; /* Use table for centering */
            text-align: left;
        }
        .legend h4 {
            margin-top: 0;
            color: #333;
            text-align: center;
            margin-bottom: 15px;
        }
        .legend p {
            margin: 8px 0;
            display: flex;
            align-items: center;
            padding-left: 10px; /* Indent legend items */
        }
        .legend span {
            display: inline-block;
            width: 25px;
            height: 25px;
            margin-right: 12px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 3px rgba(0,0,0,0.1); /* Subtle inner shadow */
        }

        /* FullCalendar v6 Event Status Styling */
        .fc-event-pending { background-color: #ffeb3b; border-color: #fbc02d; color: #333; } /* Yellow */
        .fc-event-approved { background-color: #4CAF50; border-color: #388E3C; color: white; } /* Green */
        .fc-event-rejected { background-color: #f44336; border-color: #d32f2f; color: white; } /* Red */


        /* Modal Styling */
        .modal {
            display: none; /* Hidden by default */
            position: fixed; /* Stay in place */
            z-index: 1000; /* Sit on top */
            left: 0;
            top: 0;
            width: 100%; /* Full width */
            height: 100%; /* Full height */
            overflow: auto; /* Enable scroll if needed */
            background-color: rgba(0, 0, 0, 0.6); /* Black w/ opacity */
            display: flex; /* Use flexbox for centering */
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fff;
            padding: 35px;
            border-radius: 10px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.3);
            width: 400px; /* Increased width */
            max-width: 90%; /* Max width for responsiveness */
            text-align: left;
            position: relative;
            transform: translateY(-20px); /* Slight lift animation */
            animation: fadeInScale 0.3s ease-out forwards;
        }
        @keyframes fadeInScale {
            from { opacity: 0; transform: translateY(-30px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }

        .modal-content h4 {
            margin-top: 0;
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.5em;
        }
        .modal-content p {
            margin-bottom: 12px;
            font-size: 1.05em;
            color: #555;
        }
        .modal-content strong {
            color: #007bff;
            font-weight: bold;
        }
        .modal-content label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }
        .modal-content select {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 25px;
            border: 1px solid #ddd;
            border-radius: 6px;
            box-sizing: border-box;
            font-size: 1.05rem;
            background-color: #fefefe;
            appearance: none; /* Remove default arrow */
            background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007bff%22%20d%3D%22M287%2069.4L154.4%20202.9c-2.2%202.2-5%203.4-7.4%203.4s-5.2-1.2-7.4-3.4L5.4%2069.4c-4.6-4.6-4.6-12%200-16.6s12-4.6%2016.6%200l132.8%20132.8L270.4%2052.8c4.6-4.6%2012-4.6%2016.6%200s4.6%2012%200%2016.6z%22%2F%3E%3C%2Fsvg%3E');
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 12px;
        }

        .modal-buttons {
            text-align: center;
            margin-top: 30px;
        }
        .modal-buttons button {
            padding: 12px 25px;
            margin: 0 10px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .modal-buttons button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }

        #saveStatus {
            background-color: #28a745; /* Green */
            color: white;
        }
        #saveStatus:hover {
            background-color: #218838;
        }
        #closeModal {
            background-color: #dc3545; /* Red */
            color: white;
        }
        #closeModal:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <h2>Administration des Réservations</h2>
    <p class="info">Bienvenue sur le panneau d'administration des réservations. Cliquez sur un événement pour modifier son statut.</p>
    <p class="info">Ceci est une version de test sans connexion. Accédez à la page utilisateur via <a href="calendar.php">calendar.php</a>.</p>

    <div id="calendar"></div>

    <div class="legend">
        <h4>Légende des statuts :</h4>
        <p><span class="fc-event-approved"></span> Réservation Acceptée</p>
        <p><span class="fc-event-pending"></span> Réservation en Attente</p>
        <p><span class="fc-event-rejected"></span> Réservation Rejetée</p>
    </div>

    <div id="statusModal" class="modal">
        <div class="modal-content">
            <h4>Modifier le statut de la réservation</h4>
            <p><strong>Utilisateur :</strong> <span id="modalUsername"></span></p>
            <p><strong>Infrastructure :</strong> <span id="modalInfrastructure"></span></p>
            <p><strong>Période :</strong> <span id="modalTime"></span></p>

            <label for="statusDropdown">Nouveau statut :</label>
            <select id="statusDropdown">
                <option value="Pending">En attente</option>
                <option value="Approved">Approuvée</option>
                <option value="Rejected">Rejetée</option>
            </select>
            <div class="modal-buttons">
                <button id="saveStatus">Enregistrer</button>
                <button id="closeModal">Annuler</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const modal = document.getElementById('statusModal');
            const statusDropdown = document.getElementById('statusDropdown');
            const saveButton = document.getElementById('saveStatus');
            const closeButton = document.getElementById('closeModal');
            const modalUsername = document.getElementById('modalUsername');
            const modalInfrastructure = document.getElementById('modalInfrastructure');
            const modalTime = document.getElementById('modalTime');
            let selectedEvent = null; // Variable to store the FullCalendar event object

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth', // Default view when calendar loads
                locale: 'fr', // French localization
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay' // Views available
                },
                // Fetch all events for the admin view
                events: 'fetch_events.php?view_type=admin', // Relative path to fetch_events.php

                // Handler for when an event is clicked
                eventClick: function (info) {
                    selectedEvent = info.event; // Store the clicked event

                    // Set the current status in the dropdown
                    statusDropdown.value = selectedEvent.extendedProps.status || "Pending";

                    // Populate modal details from event's extended properties
                    modalUsername.textContent = selectedEvent.extendedProps.username || "N/A";
                    modalInfrastructure.textContent = selectedEvent.extendedProps.infrastructure_name || "N/A";

                    // Format and display the reservation period
                    const start = new Date(selectedEvent.start);
                    const end = new Date(selectedEvent.end);
                    const startDateFormatted = start.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    const startTimeFormatted = start.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
                    const endDateFormatted = end.toLocaleDateString('fr-FR', { day: '2-digit', month: '2-digit', year: 'numeric' });
                    const endTimeFormatted = end.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

                    if (startDateFormatted === endDateFormatted) {
                        modalTime.textContent = `${startDateFormatted} (${startTimeFormatted} - ${endTimeFormatted})`;
                    } else {
                        modalTime.textContent = `Du ${startDateFormatted} ${startTimeFormatted} au ${endDateFormatted} ${endTimeFormatted}`;
                    }

                    modal.style.display = "flex"; // Show the modal using flex for centering
                },

                // Handler for when an event is rendered (allows dynamic styling)
                eventDidMount: function(info) {
                    const status = info.event.extendedProps.status;
                    // Add specific CSS classes based on reservation status
                    if (status === 'Approved') {
                        info.el.classList.add('fc-event-approved');
                    } else if (status === 'Rejected') {
                        info.el.classList.add('fc-event-rejected');
                    } else if (status === 'Pending') {
                        info.el.classList.add('fc-event-pending');
                    }
                }
            });

            calendar.render(); // Render the calendar on the page

            // Event listener for the "Save" button in the modal
            saveButton.addEventListener('click', function () {
                const newStatus = statusDropdown.value;
                if (selectedEvent) {
                    fetch('update_reservation_status.php', { // AJAX call to update status
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: selectedEvent.id,
                            status: newStatus
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Statut mis à jour avec succès !");
                            selectedEvent.setExtendedProp('status', newStatus); // Update event data
                            // Dynamically update the event's appearance
                            const currentClassNames = selectedEvent.classNames.filter(name => !name.startsWith('fc-event-'));
                            selectedEvent.setProp('classNames', [...currentClassNames, `fc-event-${newStatus.toLowerCase()}`]);

                            calendar.refetchEvents(); // Re-fetch events to ensure consistency
                        } else {
                            alert("Erreur lors de la mise à jour du statut : " + data.error);
                        }
                        modal.style.display = "none"; // Hide the modal
                    })
                    .catch(error => {
                        console.error("Erreur de Fetch :", error);
                        alert("Une erreur est survenue lors de la mise à jour du statut.");
                        modal.style.display = "none";
                    });
                }
            });

            // Event listener for the "Cancel" button in the modal
            closeButton.addEventListener('click', function () {
                modal.style.display = "none"; // Hide the modal
            });

            // Close the modal if the user clicks outside of it
            window.addEventListener('click', function (event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            });
        });
    </script>

</body>
</html>