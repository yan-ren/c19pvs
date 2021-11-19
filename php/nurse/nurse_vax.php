<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";
$link = connect();


$result_rows = array();
$totalDoseGiven = 0;


$sql = "SELECT first_name,  middle_name, last_name, phone, SUM(dose_given) AS dose
FROM healthcare_worker_assignment
INNER JOIN person on person.person_id = healthcare_worker_assignment.person_id
WHERE role='nurse'
GROUP BY healthcare_worker_assignment.person_id
HAVING SUM(dose_given) >= 20;
ORDER BY dose ASC 
";

$stmt = mysqli_prepare($link, $sql);
if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ssssi", $first_name, $middle_name, $last_name, $phone, $totalDoseGiven);
    if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $result_rows[] = $row;
            }
            mysqli_free_result($result);
        } else {
            // echo '<div class="alert alert-danger"><em>No Booking records were found.</em></div>';
        }
    } else {
        echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
} else {
    echo "Prepare SQL error: " . $link->error;
}

// Close statement
mysqli_stmt_close($stmt);
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
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Phone</th>
                        <th>Total Doses Given</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($result_rows as $item) {
                        echo "<tr>";
                        echo "<td>" . $item['first_name'] . "</td>";
                        echo "<td>" . $item['middle_name'] . "</td>";
                        echo "<td>" . $item['last_name'] . "</td>";
                        echo "<td>" . $item['phone'] . "</td>";
                        echo "<td>" . $item['totalDoseGiven'] . "</td>";
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