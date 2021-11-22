<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$booking_id = "";
$first_name = $last_name = $facility_name = $date = $time = "";
$avaliability_error = "";
$available_booking_slots = [];
$is_heathcare_worker = false;

// Processing form data when form is submitted
if (isset($_GET["booking_id"]) && !empty(trim($_GET["booking_id"]))) {

  $booking_id = (int)(trim($_GET["booking_id"]));

  // Get booking by id
  $link = connect();
  $sql = "SELECT first_name, last_name, facility_name, date, time
    FROM booking
    INNER JOIN person ON booking.person_id=person.person_id
    WHERE booking_id=?";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $booking_id);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $first_name = $row["first_name"];
        $last_name = $row["last_name"];
        $facility_name = $row["facility_name"];
        $date = $row["date"];
        $time = $row["time"];
      } else {
        echo '<script> alert("Bookings Not Found")</script>';
      }
    } else {
      $error = mysqli_stmt_error($stmt);
      echo '<script> alert("' . $error . '")</script>';
    }
  } else {
    echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');</script>";
  }
  mysqli_stmt_close($stmt);
  mysqli_close($link);

  // Check for facility avaliability
  // Find how many nurses working on given date
  $number_of_nurses = 0;
  $sql = "SELECT count(*) AS nurses
    FROM healthcare_worker_assignment
    WHERE start_date <= ? AND end_date >= ? AND role='nurse' AND facility_name=?";

  $link = connect();
  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "sss", $date, $date, $facility_name);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $number_of_nurses = (int)($row["nurses"]);
      }
    } else {
      $error = mysqli_stmt_error($stmt);
      echo '<script> alert("' . $error . '")</script>';
    }
  } else {
    echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');</script>";
  }
  mysqli_stmt_close($stmt);
  mysqli_close($link);

  if ($number_of_nurses == 0) {
    $avaliability_error = "No more availiability on given date!";
    echo '<script> if(!alert("' . $avaliability_error . '")){
                // document.location = \'../../booking.php\';
            }
            </script>';
    exit();
  }

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
        echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $date . ' for facility: ' . $facility_name . '</em></div>';
      }
    } else {
      echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
  } else {
    echo "Prepare SQL error: " . $link->error;
  }

  // Find how many bookings exisit in each time slot of the day
  for ($i = $open; $i < $close; $i += 1200) { // 1200 = 20min x 60sec
    $link = connect();
    $sql = "SELECT time, COUNT(booking_id) AS booking_count
            FROM booking 
            WHERE facility_name=? AND date=? AND time=? AND status='active'
            GROUP BY time";

    $time_string = strftime('%H:%M:%S', $i);
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt) {
      mysqli_stmt_bind_param($stmt, "sss", $facility_name, $date, $time_string);
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        // find booking records, compare the number of booking is less than number of nurses
        if (mysqli_num_rows($result) == 1) {
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          if ((int)($row['booking_count']) < $number_of_nurses) {
            $available_booking_slots[] = array('time' => $time_string, 'available' => $number_of_nurses - (int)($row['booking_count']));
          }
        }
        // no booking records 
        else {
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          $available_booking_slots[] = array('time' => $time_string, 'available' => $number_of_nurses);
          // echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $i->format("Y-m-d") . ' for facility: ' . $facility_name . '</em></div>';
        }
      } else {
        echo "SQL query error: " . mysqli_stmt_errno($stmt);
      }
    } else {
      echo "Prepare SQL error: " . $link->error;
    }
  }

  if (count($available_booking_slots) == 0) {
    $avaliability_error = "No available slots on given date!" . " Nurses: " . $number_of_nurses;
    echo '<script> if(!alert("' . $avaliability_error . '")){
                // document.location = \'check_avaliability.php\';
            }
            </script>';
  }

  mysqli_stmt_close($stmt);
  mysqli_close($link);
}
// Handle INSERT into booking 
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $link = connect();
  $booking_id = (int)($_POST['booking_id']);
  $time = trim($_POST['time']);

  $sql = "UPDATE booking SET time=? WHERE booking_id=?";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "si", $time, $booking_id);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Records created successfully. Redirect to landing page
      echo "<script>alert('Booking Successful!');location.href='../../booking.php'</script>";
      exit();
    } else {
      $error = mysqli_stmt_error($stmt);
      echo '<script> alert("' . $error . '");location.href=\'../../booking.php\'</script>';
    }
  } else {
    echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='../../booking.php';</script>";
  }

  mysqli_stmt_close($stmt);
  mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Update Record</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .wrapper {
      width: 600px;
      margin: 0 auto;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <h2 class="mt-5">Update booking</h2>
          <form name="avaliability" action="update.php" onsubmit="return validateForm(this.name)" method="post">
            <div class="form-group">
              <label>Booking ID</label>
              <input type="number" name="booking_id" class="form-control" value="<?php echo $booking_id; ?>" readonly>
            </div>
            <div class="form-group">
              <label>First Name</label>
              <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Facility</label>
              <input type="text" name="facility_name" class="form-control" value="<?php echo $facility_name; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Date</label>
              <input type="date" name="date" class="form-control" value="<?php echo $date; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Time</label>
              <select class="form-select browser-default custom-select" name="time">
                <?php
                foreach ($available_booking_slots as $slot) {
                  echo '<option value=' . $slot['time'] . '>' . $slot['time'] . ' - available: ' . $slot['available'] . '</option>';
                }
                ?>
              </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="../../booking.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>