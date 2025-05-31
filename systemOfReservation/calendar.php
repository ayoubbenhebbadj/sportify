<?php
// Include your DB config
include("../phpConfig/constants.php");

// Set user type manually for testing (you'll later get this from session)
$user_id = 9;
$user_type_query = mysqli_query($conn, "SELECT type FROM user WHERE id = $user_id LIMIT 1");
$user_type = mysqli_fetch_assoc($user_type_query)['type'];

// Set month and year
$month = date('m');
$year = date('Y');

if (isset($_GET['month']) && isset($_GET['year'])) {
    $month = $_GET['month'];
    $year = $_GET['year'];
}

// Get first day of the month
$firstDayOfMonth = mktime(0, 0, 0, $month, 1, $year);
$daysInMonth = date('t', $firstDayOfMonth);
$dayOfWeek = date('w', $firstDayOfMonth);

$months = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reservation Calendar</title>
    <link rel="stylesheet" href="../style/calendar.css">
</head>
<body>

<h2 style="text-align:center">
    <?= $months[(int)$month] . " " . $year ?>
</h2>

<!-- Navigation -->
<div style="text-align:center; margin-bottom: 20px;">
    <?php
    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear--;
    }

    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
    }
    ?>
    <a href="?month=<?= $prevMonth ?>&year=<?= $prevYear ?>">&#8592; Précédent</a> |
    <a href="?month=<?= $nextMonth ?>&year=<?= $nextYear ?>">Suivant &#8594;</a>
</div>

<table>
    <tr>
        <th>Dim</th><th>Lun</th><th>Mar</th><th>Mer</th><th>Jeu</th><th>Ven</th><th>Sam</th>
    </tr>
    <tr>
        <?php
        $currentDay = 1;
        $today = date('Y-m-d');

        // Blank cells before first day
        for ($i = 0; $i < $dayOfWeek; $i++) {
            echo "<td></td>";
        }

        while ($currentDay <= $daysInMonth) {
            $dateStr = "$year-$month-" . str_pad($currentDay, 2, '0', STR_PAD_LEFT);
            $isToday = ($dateStr == $today);
            $link = "calendar.php?date=$dateStr";

            echo "<td" . ($isToday ? " class='today'" : "") . ">";
            echo "<a href='$link'>$currentDay</a>";
            echo "</td>";

            if (($dayOfWeek + $currentDay) % 7 == 0) {
                echo "</tr><tr>";
            }

            $currentDay++;
        }

        while (($dayOfWeek + $currentDay - 1) % 7 != 0) {
            echo "<td></td>";
            $currentDay++;
        }
        ?>
    </tr>
</table>

<?php if (isset($_GET['date']) || in_array($user_type, ['association', 'professional club'])): ?>
<h3 style="text-align:center; margin-top: 30px;">
    Reservation Form
</h3>
<div style="text-align:center; margin-top: 20px;">
    <form action="book_slot.php" method="POST">
        <?php if (in_array($user_type, ['association', 'professional club'])): ?>
            <label>Start Date:</label>
            <input type="date" name="start_date" required><br><br>
            <label>End Date:</label>
            <input type="date" name="end_date" required><br><br>
        <?php else: ?>
            <input type="hidden" name="start_date" value="<?= $_GET['date'] ?>">
            <input type="hidden" name="end_date" value="<?= $_GET['date'] ?>">
        <?php endif; ?>

        <label>Start Time:</label>
        <select name="time_slot" required>
            <?php
            $startHour = 8;
            for ($i = 0; $i < 8; $i++) {
                $hour = $startHour + $i * 1.5;
                $h = floor($hour);
                $m = ($hour - $h) * 60;
                $formatted = sprintf('%02d:%02d', $h, $m);
                echo "<option value='$formatted'>$formatted</option>";
            }
            ?>
        </select>

        <label>Duration:</label>
        <select name="duration" required>
            <option value="1.5">1h30</option>
            <option value="3">3h</option>
            <option value="4.5">4h30</option>
        </select><br><br>

        <label>Infrastructure:</label>
        <select name="infrastructure_id">
            <?php
            $q = mysqli_query($conn, "SELECT id, name FROM infrastructure WHERE available = 1");
            while ($row = mysqli_fetch_assoc($q)) {
                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['name']) . "</option>";
            }
            ?>
        </select>

        <input type="hidden" name="user_id" value="<?= $user_id ?>">
        <br><br>
        <button type="submit">Reserve</button>
    </form>
</div>
<?php endif; ?>

</body>
</html>