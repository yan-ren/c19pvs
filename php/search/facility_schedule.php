<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";
$link = connect();
$facility_name = $start_date = $end_date = "";

$facility_name = trim($_GET['facility_name']);
$given_date = trim($_GET['date']);
$result_rows = array();

// Get operation hour of the facility on given date
$day_of_week = date("w", strtotime($given_date));
$open = "";
$close = "";
$sql = "SELECT open, close FROM facility_operating_hour WHERE facility_name=? AND day_of_week=?";
$stmt = mysqli_prepare($link, $sql);

if ($stmt) {
  mysqli_stmt_bind_param($stmt, "si", $facility_name, $day_of_week);
  if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $open = strtotime($row["open"]);
      $close = strtotime($row["close"]);
    } else {
      echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $date . ' for facility: ' . $facility_name . '</em></div>';
    }
  } else {
    echo "SQL query error: " . mysqli_stmt_errno($stmt);
  }
} else {
  echo "Prepare SQL error: " . $link->error;
}

// Get all workers on given facility on given date
$sql = "SELECT person.first_name, person.last_name, role
FROM healthcare_worker_assignment
INNER JOIN person ON healthcare_worker_assignment.person_id = person.person_id
WHERE (start_date <= ? AND end_date >= ?) AND facility_name = ?";

$stmt = mysqli_prepare($link, $sql);
if ($stmt) {
  mysqli_stmt_bind_param($stmt, "sss", $given_date, $given_date, $facility_name);
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
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Hourly Rate</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result_rows as $item) {
                echo "<tr>";
                echo "<td>" . $item['employee_id'] . "</td>";
                echo "<td>" . $item['first_name'] . "</td>";
                echo "<td>" . $item['last_name'] . "</td>";
                echo "<td>" . $item['email'] . "</td>";
                echo "<td>" . $item['hourly_rate'] . "</td>";
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