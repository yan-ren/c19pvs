<?php
// Include config file
require_once "../config.php";

// Define variables and initialize with empty values
$first_name = $last_name = $facility_name = $date = "";
$person_id = "";
$avaliability_error = "";
$available_booking_slots = [];
$is_heathcare_worker = false;

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['check'])) {

  $first_name = trim($_POST["first_name"]);
  $last_name = trim($_POST["last_name"]);
  $facility_name = trim($_POST["facility_name"]);
  $date = trim($_POST["date"]);

  // Check if person exisits in system
  $link = connect();
  $sql = "SELECT person_id, first_name, last_name
    FROM person
    WHERE first_name=? AND last_name=?";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ss", $first_name, $last_name);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $person_id = (int)($row["person_id"]);
      } else {
        $avaliability_error = "Person not found!";
        echo '<script> if(!alert("' . $avaliability_error . '")){
                  document.location = \'check_avaliability.php\';
              }
              </script>';
      }
    } else {
      $error = mysqli_stmt_error($stmt);
      echo '<script> alert("' . $error . '")</script>';
    }
  } else {
    echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
  }
  mysqli_stmt_close($stmt);
  mysqli_close($link);

  // Check if person is healthcare worker
  $link = connect();
  $sql = "SELECT first_name, last_name
    FROM person
    INNER JOIN healthcare_worker ON person.person_id=healthcare_worker.person_id
    WHERE first_name=? AND last_name=?";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ss", $first_name, $last_name);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) > 0) {
        $is_heathcare_worker = true;
      }
    } else {
      $error = mysqli_stmt_error($stmt);
      echo '<script> alert("' . $error . '")</script>';
    }
  } else {
    echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
  }
  mysqli_stmt_close($stmt);
  mysqli_close($link);

  // Check for person age group
  if (!$is_heathcare_worker) {
    $link = connect();
    $sql = "SELECT first_name, last_name
                FROM person
                INNER JOIN age_group ON person.age_group_id=age_group.age_group_id
                WHERE first_name=? AND last_name=? AND vaccination_date <= ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $first_name, $last_name, $date);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) != 1) {
          $avaliability_error = "Person doesn't belong to available age group";
          echo '<script> if(!alert("' . $avaliability_error . '")){
                            document.location = \'check_avaliability.php\';
                        }
                        </script>';
        }
      } else {
        $error = mysqli_stmt_error($stmt);
        echo '<script> alert("' . $error . '")</script>';
      }
    } else {
      echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }

  // Check for facility avaliability
  // Find how many nurses working on given date
  $number_of_nurses = 0;
  $link = connect();
  $sql = "SELECT count(*) AS nurses
    FROM healthcare_worker_assignment
    WHERE start_date <= ? AND end_date >= ? AND role='nurse' AND facility_name=?";

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
    echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
  }
  mysqli_stmt_close($stmt);
  mysqli_close($link);

  if ($number_of_nurses == 0) {
    $avaliability_error = "No availiability on given date!";
    echo '<script> if(!alert("' . $avaliability_error . '")){
                document.location = \'check_avaliability.php\';
            }
            </script>';
  }

  // For each 20min between open to close, see if booking is exceed number of nurses
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
        echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $i->format("Y-m-d") . ' for facility: ' . $facility_name . '</em></div>';
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
                document.location = \'check_avaliability.php\';
            }
            </script>';
  }

  mysqli_stmt_close($stmt);
  mysqli_close($link);
}
// Handle INSERT into booking 
else if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $link = connect();
  $person_id = (int)(trim($_POST["person_id"]));
  $facility_name = trim($_POST["facility_name"]);
  $date = trim($_POST["date"]);
  $time = trim($_POST['time']);

  $sql = "INSERT INTO booking (person_id, facility_name, date, time) VALUES (?, ?, ?, ?)";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "isss", $person_id, $facility_name, $date, $time);

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
  <title>Create Record</title>
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
          <h2 class="mt-5">Create New booking</h2>
          <form name="avaliability" action="create.php" onsubmit="return validateForm(this.name)" method="post">
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
            <input type="hidden" name="person_id" value="<?php echo $person_id; ?>" />
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="../../booking.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>