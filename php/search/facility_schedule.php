<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";
$open = $close = "";
$worker_result = array();
$appointment_result = array();

$facility_name = trim($_GET['facility_name']);
$given_date = trim($_GET['date']);
$day_of_week = date("w", strtotime($given_date));

// Get operation hour of the facility on given date
$link = connect();
$sql = "SELECT open, close FROM facility_operating_hour WHERE facility_name=? AND day_of_week=?";
$stmt = mysqli_prepare($link, $sql);

if ($stmt) {
  mysqli_stmt_bind_param($stmt, "si", $facility_name, $day_of_week);
  if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) == 1) {
      $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      $open = $row["open"];
      $close = $row["close"];
    } else {
      echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $date . ' for facility: ' . $facility_name . '</em></div>';
    }
  } else {
    echo "SQL query error: " . mysqli_stmt_errno($stmt);
  }
} else {
  echo "Prepare SQL error: " . $link->error;
}

mysqli_stmt_close($stmt);
mysqli_close($link);

// Get all workers on given facility on given date
$sql = "SELECT employee_id, person.first_name, person.last_name, role
        FROM healthcare_worker_assignment
        INNER JOIN person ON healthcare_worker_assignment.person_id = person.person_id
        INNER JOIN healthcare_worker ON healthcare_worker.person_id = healthcare_worker_assignment.person_id 
        AND healthcare_worker.facility_name = healthcare_worker_assignment.facility_name
        WHERE (start_date <= ? AND end_date >= ?) AND healthcare_worker_assignment.facility_name = ?
        ORDER BY role ASC";

$link = connect();
$stmt = mysqli_prepare($link, $sql);
if ($stmt) {
  mysqli_stmt_bind_param($stmt, "sss", $given_date, $given_date, $facility_name);
  if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_array($result)) {
        $worker_result[] = $row;
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

mysqli_stmt_close($stmt);
mysqli_close($link);

// Get appointments on given date
$link = connect();

$sql = "SELECT first_name, last_name, date, time
        FROM booking
        INNER JOIN person ON booking.person_id = person.person_id
        WHERE date = ? AND facility_name = ? AND booking.status='active'
        ORDER BY time ASC";

$stmt = mysqli_prepare($link, $sql);
if ($stmt) {
  mysqli_stmt_bind_param($stmt, "ss", $given_date, $facility_name);
  if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_array($result)) {
        $appointment_result[] = $row;
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

mysqli_stmt_close($stmt);
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
      <h4>Facility Operation Hour on <?php echo $given_date ?>, Open: <?php echo $open ?>, Close: <?php echo $close ?></h4>
      <div class="row">
        <div class="col-md-12">
          <p>Worker Schedule</p>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Employee ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Role</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($worker_result as $item) {
                echo "<tr>";
                echo "<td>" . $item['employee_id'] . "</td>";
                echo "<td>" . $item['first_name'] . "</td>";
                echo "<td>" . $item['last_name'] . "</td>";
                echo "<td>" . $item['role'] . "</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <p>Appointment Schedule</p>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Time</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($appointment_result as $item) {
                echo "<tr>";
                echo "<td>" . $item['first_name'] . "</td>";
                echo "<td>" . $item['last_name'] . "</td>";
                echo "<td>" . $item['time'] . "</td>";
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