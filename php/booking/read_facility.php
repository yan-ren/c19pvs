<?php
// Include config file
require_once "../config.php";
$link = connect();
$facility_name = $start_date = $end_date = "";

$facility_name = trim($_GET['facility_name']);
$start_date = trim($_GET['start_date']);
$end_date = trim($_GET['end_date']);
$result_rows = array();

// Attempt select query execution
$sql = "SELECT booking_id, first_name, last_name, date, time, status
FROM booking
INNER JOIN person ON booking.person_id = person.person_id
WHERE facility_name=? AND (date BETWEEN ? AND ?) ORDER BY date ASC";

$stmt = mysqli_prepare($link, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $facility_name, $start_date, $end_date);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $result_rows[] = $row;
            }
            mysqli_free_result($result);
        } else {
            echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
        }
    } else {
        echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
} else {
    echo "Prepare SQL error: " . $link->error;
}


// Close connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/html/head.html") ?>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Booking Date</th>
                                <th>Booking Time</th>
                                <th>Booking Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($result_rows as $item) {
                                echo "<tr>";
                                echo "<td>" . $item['booking_id'] . "</td>";
                                echo "<td>" . $item['first_name'] . "</td>";
                                echo "<td>" . $item['last_name'] . "</td>";
                                echo "<td>" . $item['date'] . "</td>";
                                echo "<td>" . $item['time'] . "</td>";
                                echo "<td>" . $item['status'] . "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>