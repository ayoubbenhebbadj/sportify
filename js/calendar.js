document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        selectable: true,
        events: 'fetch_events.php',
        dateClick: function (info) {
            const start = prompt("Enter start time (YYYY-MM-DD HH:MM:SS):", info.dateStr + " 10:00:00");
            const duration = prompt("Duration in minutes (90, 180, 270...):", "90");

            const user_id = 1; // Replace with session or real ID
            const infrastructure_id = 1; // Replace with selected infra from dropdown

            fetch('book_slot.php', {
                method: 'POST',
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                body: `user_id=${user_id}&infrastructure_id=${infrastructure_id}&start=${start}&duration=${duration}`
            })
                .then(response => response.text())
                .then(result => {
                    alert(result);
                    calendar.refetchEvents();
                });
        }
    });

    calendar.render();
});
