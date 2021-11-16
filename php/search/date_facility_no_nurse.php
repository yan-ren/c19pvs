<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";
$link = connect();

$given_date = trim($_GET['date']);
$day_of_week = date("w", strtotime($given_date));
$result_rows = array();

$sql = "SELECT facility.name, facility.address, facility.phone, facility.capacity, open, close
FROM facility
INNER JOIN facility_operating_hour ON facility.name=facility_operating_hour.facility_name
LEFT JOIN (
  SELECT person_id, facility_name
  FROM healthcare_worker_assignment
  WHERE start_date <= ? AND end_date >= ? AND role='nurse'
) AS worker_assignment ON facility.name = worker_assignment.facility_name
WHERE day_of_week=? AND person_id IS NULL";

$stmt = mysqli_prepare($link, $sql);
if ($stmt) {
  mysqli_stmt_bind_param($stmt, "ssi", $given_date, $given_date, $day_of_week);
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
                <th>Facility Name</th>
                <th>Address</th>
                <th>Phone Number</th>
                <th>Capacity</th>
                <th>Operating Hour</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result_rows as $item) {
                echo "<tr>";
                echo "<td>" . $item['name'] . "</td>";
                echo "<td>" . $item['address'] . "</td>";
                echo "<td>" . $item['phone'] . "</td>";
                echo "<td>" . $item['capacity'] . "</td>";
                echo "<td>" . $item['open'] . ' - ' . $item['close'] . "</td>";
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