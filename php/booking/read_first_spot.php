<?php
// Include config file
require_once "../config.php";
$link = connect();
$facility_name = $query_start_date = "";

$facility_name = trim($_GET['facility_name']);
$query_start_date = trim($_GET['start_date']);
$workers = array();
$date_workers = array();

/*
First, find on which day there are nurses working. Then, for each day that has nurse working, find slot is not booked yet
get all nurses in facility whose finish time is after start day
1. query heathcare_work_assignment role = 'nurse' whose end date > given start day, get list of worker assignment
2. in the list, calculate the min date and max date
3. from min date to max date, query how many nurses on each day, which is the capacity for each slots
3.1 query open hour on that day, calculate slots
3.2 for each slots query booking if not exceed max, that's the first available booking
*/
$sql = "SELECT person_id, start_date, end_date
FROM healthcare_worker_assignment
WHERE end_date >= ?";

$stmt = mysqli_prepare($link, $sql);
if ($stmt) {
  mysqli_stmt_bind_param($stmt, "s", $query_start_date);
  if (mysqli_stmt_execute($stmt)) {
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
      while ($row = mysqli_fetch_array($result)) {
        $workers[] = $row;
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

foreach ($workers as $worker) {
  $end = new DateTime($worker['end_date']);
  $start = strtotime($worker['start_date']) < strtotime($query_start_date) ? new DateTime($query_start_date) : new DateTime($worker['start_date']);
  for ($date = $start; $date <= $end; $date->modify('+1 day')) {
    if (isset($date_workers[$date->format('Y-m-d')])) {
      $date_workers[$date->format('Y-m-d')]++;
    } else {
      $date_workers[$date->format('Y-m-d')] = 1;
    }
  }
}

foreach ($date_workers as $date => $workers) {
  $link = connect();
  // query operation hour on given date
  $day_of_week = date("w", strtotime($date));
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
        continue;
        // echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $i->format("Y-m-d") . ' for facility: ' . $facility_name . '</em></div>';
      }
    } else {
      echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
  } else {
    echo "Prepare SQL error: " . $link->error;
  }

  // 1200 = 20min x 60sec
  for ($i = $open; $i < $close; $i += 1200) {
    $link = connect();
    $sql = "SELECT COUNT(booking_id) AS booking_count 
        FROM booking 
        WHERE facility_name=? AND date=? AND time=? AND status='active'";

    $time_string = strftime('%H:%M:%S', $i);
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "sss", $facility_name, $date, $time_string);
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 1) {
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          $booking_count = (int)($row['booking_count']);
        } else {
          // echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $i->format("Y-m-d") . ' for facility: ' . $facility_name . '</em></div>';
          // Close statement
          mysqli_stmt_close($stmt);
          // Close connection
          mysqli_close($link);
          exit();
        }
        if ($booking_count < $workers) {
          // Close statement
          mysqli_stmt_close($stmt);
          // Close connection
          mysqli_close($link);
          echo 'First available spot at ' . $facility_name . ' starting ' . $query_start_date . ' is ' . $date . ' at ' . $time_string;
          exit();
        }
      } else {
        echo "SQL query error: " . mysqli_stmt_errno($stmt);
        exit();
      }
    } else {
      echo "Prepare SQL error: " . $link->error;
      exit();
    }
  }
  // Close statement
  mysqli_stmt_close($stmt);
  // Close connection
  mysqli_close($link);
}

echo 'No booking spot available, cannot find nurses assignment in ' . $facility_name . ' after the given date: ' . $query_start_date;

// Close statement
mysqli_stmt_close($stmt);
// Close connection
mysqli_close($link);
