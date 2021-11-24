<?php
// Include config file
require_once "../config.php";
$link = connect();
$facility_name = $start_date = $end_date = "";

$facility_name = trim($_GET['facility_name']);
$start_date = trim($_GET['start_date']);
$end_date = trim($_GET['end_date']);
$result_rows = array();
$date_spots = array();

// Query all bookings for facility given time period
$sql = "SELECT booking_id, first_name, last_name, date, time, booking.status
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
      // echo '<div class="alert alert-danger"><em>No Booking records were found.</em></div>';
    }
  } else {
    echo "SQL query error: " . mysqli_stmt_errno($stmt);
  }
} else {
  echo "Prepare SQL error: " . $link->error;
}

// Calculate availability of spots for given period
// For each date, get how many nurses are assigned to the facility and the open hour of the facility
$begin = new  DateTime($start_date);
$end = new DateTime($end_date);
$i = $begin;

for ($i = $begin; $i <= $end; $i->modify('+1 day')) {
  // get number of nurses on given day
  $nurses = 0;
  $sql = "SELECT COUNT(DISTINCT(person_id)) AS count FROM healthcare_worker_assignment WHERE role='nurse' AND start_date <= ? AND end_date >= ? AND facility_name=?";
  $stmt = mysqli_prepare($link, $sql);
  if ($stmt) {
    $date_string = $i->format("Y-m-d");
    mysqli_stmt_bind_param($stmt, "sss", $date_string, $date_string, $facility_name);
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $nurses =  (int)($row["count"]);
      } else {
        echo '<div class="alert alert-danger"><em>Error: ' . $result . '</em></div>';
      }
    } else {
      echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
  } else {
    echo "Prepare SQL error: " . $link->error;
  }

  // get open hours on given day then caluclate slots for the day if there is one nurses
  $slots = 0;
  $day_of_week = date("w", $i->getTimestamp());
  $sql = "SELECT open, close FROM  facility_operating_hour WHERE facility_name=? AND day_of_week=?";
  $stmt = mysqli_prepare($link, $sql);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "si", $facility_name, $day_of_week);
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $open = strtotime($row["open"]);
        $close = strtotime($row["close"]);
        $slots = floor(abs($close - $open) / 60 / 20) * $nurses;
      } else {
        echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $i->format("Y-m-d") . ' for facility: ' . $facility_name . '</em></div>';
      }
    } else {
      echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
  } else {
    echo "Prepare SQL error: " . $link->error;
  }

  $date_spots[$i->format("Y-m-d")] = $slots;
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
            <?php echo "<h4>Available slots in " . $facility_name . " from " . $start_date . " to " . $end_date . "</h4>"; ?>
            <thead>
              <tr>
                <th>Date</th>
                <th>Total Available Spots For Vaccination</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($date_spots as $key => $val) {
                echo "<tr>";
                echo "<td>" . $key . "</td>";
                echo "<td>" . $val . "</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
          <table class="table table-bordered table-striped">
            <h4>Display Bookings</h4>
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