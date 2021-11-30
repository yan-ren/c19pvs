<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";

$person_id = $first_name = $last_name = $location = $date = $time = "";

// Handle POST
if (isset($_POST["vaccine"])) {
  // Extract input values
  $person_id = (int)(trim($_POST['person_id']));
  $vaccine_name = trim($_POST['vaccine']);
  $dose = (int)(trim($_POST['dose']));
  $date = trim($_POST['date']);
  $location = trim($_POST['facility_name']);
  // Optional valuess
  $lot = trim($_POST['lot']);
  $city = trim($_POST['city']);
  $province = trim($_POST['province']);
  $country = trim($_POST['country']);
  if (empty($lot)) {
    $lot = null;
  }
  if (empty($city)) {
    $city = null;
  }
  if (empty($province)) {
    $province = null;
  }
  if (empty($country)) {
    $country = null;
  }

  // Check availiability, number of nurse working on given date * open hours * 3 > booking on given date
  // Get operation hour of the facility on given date
  $link = connect();
  $open = $close = "";
  $day_of_week = date("w", strtotime($date));
  $sql = "SELECT open, close FROM facility_operating_hour WHERE facility_name=? AND day_of_week=?";
  $stmt = mysqli_prepare($link, $sql);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "si", $location, $day_of_week);
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $open = strtotime($row["open"]);
        $close = strtotime($row["close"]);
      } else {
        echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $date . ' for facility: ' . $location . '</em></div>';
      }
    } else {
      echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
  } else {
    echo "Prepare SQL error: " . $link->error;
  }

  mysqli_stmt_close($stmt);
  mysqli_close($link);

  // Get number of nurses on given facility on given date
  $link = connect();
  $nurses = 0;
  $sql = "SELECT COUNT(DISTINCT(person_id)) AS count 
          FROM healthcare_worker_assignment 
          WHERE role='nurse' AND start_date <= ? AND end_date >= ? AND facility_name=?";
  $stmt = mysqli_prepare($link, $sql);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "sss", $date, $date, $location);
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

  mysqli_stmt_close($stmt);
  mysqli_close($link);

  // Get number of bookings on given date
  $link = connect();
  $bookings = 0;
  $sql = "SELECT COUNT(booking_id) as count
        FROM booking
        INNER JOIN person ON booking.person_id = person.person_id
        WHERE date = ? AND facility_name = ? AND booking.status='active'";

  $stmt = mysqli_prepare($link, $sql);
  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "ss", $given_date, $location);
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $bookings =  (int)($row["count"]);
      } else {
        echo '<div class="alert alert-danger"><em>Error: ' . $result . '</em></div>';
      }
    } else {
      echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
  } else {
    echo "Prepare SQL error: " . $link->error;
  }

  // Compare available slots with existing bookings
  $slots = floor(abs($close - $open) / 60 / 20) * $nurses;
  if ($slots <= $bookings) {
    echo "<script>alert('Cannot perform vaccine, no availiable slots');location.href='../../vaccine.php';</script>";
    exit();
  }

  // Insert vaccination
  $link = connect();
  $sql = "INSERT INTO vaccination (person_id, vaccine_name, dose, date, location, lot, city, province, country)
          VALUES (?,?,?,?,?,?,?,?,?)";
  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "isissssss", $person_id, $vaccine_name, $dose, $date, $location, $lot, $city, $province, $country);
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      echo "<script>alert('Vaccination Successful!');location.href='../../vaccine.php'</script>";
    } else {
      $error = mysqli_stmt_error($stmt);
      echo '<script> alert("' . $error . '")</script>';
    }
  } else {
    echo "<script>alert('Oops! Something went wrong. Please try again later');location.href='../../vaccine.php';</script>";
  }

  // Close statement
  mysqli_stmt_close($stmt);
  // Close connection
  mysqli_close($link);
}
// Handle GET
else if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $first_name = trim($_GET['first_name']);
  $last_name = trim($_GET['last_name']);
  $facility_name = trim($_GET['preferred_location']);
  $date = trim($_GET['date']);

  $link = connect();
  // Check if person is registered in the system
  $sql = "SELECT person_id, first_name, last_name FROM person WHERE first_name=? AND last_name=?";
  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ss", $first_name, $last_name);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) == 1) {
        /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

        // Retrieve individual field value
        $person_id = (int) ($row['person_id']);
      } else {
        // person not found
        echo '<script>alert("Name: ' . $first_name . ' ' . $last_name . ' not found in system. Please check the name!");location.href=\'../../vaccine.php\'</script>';
        exit();
      }
    } else {
      echo '<script> alert("' . mysqli_stmt_error($stmt) . '")</script>';
    }
  } else {
    echo '<script>alert("Oops! Something went wrong. ' . $link->error . '");location.href=\'../../vaccine.php\';</script>';
  }

  // Fetch vaccine table
  $sql = "SELECT * FROM vaccine WHERE status='safe'";
  $result = mysqli_query($link, $sql);
  $vaccine_json = mysqli_fetch_all($result, MYSQLI_ASSOC);

  // Close statement
  mysqli_stmt_close($stmt);
  // Close connection
  mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <title>COVID-19 Public Health Care Population Vaccination System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
  <!-- Material Design Bootstrap -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
</head>

<body>
  <div class="container">
    <div class="container-fluid">
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Availiable Vaccine</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <?php
              $obj;
              foreach ($vaccine_json as $obj) {
                echo '<div class="form-group">';
                echo '<p><b>' . $obj['vaccine_name'] . ', Available Dose: ' . $obj['dose'] . '</b></p>';
                echo '</div>';
              }
              ?>
            </td>
          </tr>
        </tbody>
      </table>
      <table class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>Take Vaccination</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>
              <form id="vaccine" action="/php/vaccine/without_appointment.php?vaccine=1 ?>" method="post">
                <div class="form-group">
                  <label>Person ID</label>
                  <input class="form-control" placeholder="<?php echo $person_id; ?>" name="person_id" value="<?php echo htmlspecialchars($person_id); ?>" readonly>
                </div>
                <div class="form-group">
                  <label>First Name</label>
                  <input class="form-control" placeholder="<?php echo $first_name; ?>" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Last Name</label>
                  <input class="form-control" placeholder="<?php echo $last_name; ?>" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Vaccine</label>
                  <select class="custom-select" id="inputGroupSelectVaccine" name="vaccine">
                    <?php
                    foreach ($vaccine_json as $vaccine) {
                      echo '<option values=\"' . $vaccine['vaccine_name'] . '\">' . $vaccine['vaccine_name'] . '</option>';
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label>Dose</label>
                  <input class="form-control" placeholder="Required" name="dose">
                </div>
                <div class="form-group">
                  <label>Lot</label>
                  <input class="form-control" placeholder="Optional" name="lot">
                </div>
                <div class="form-group">
                  <label>City</label>
                  <input class="form-control" placeholder="Optional" name="city">
                </div>
                <div class="form-group">
                  <label>Province</label>
                  <input class="form-control" placeholder="Optional" name="province">
                </div>
                <div class="form-group">
                  <label>Country</label>
                  <input class="form-control" placeholder="Optional" name="country">
                </div>
                <div class="form-group">
                  <label>Booking Location</label>
                  <input class="form-control" placeholder="<?php echo $facility_name; ?>" name="facility_name" value="<?php echo htmlspecialchars($facility_name); ?>" readonly>
                </div>
                <div class="form-group">
                  <label>Date</label>
                  <input class="form-control" placeholder="<?php echo $date; ?>" name="date" value="<?php echo htmlspecialchars($date); ?>" readonly>
                </div>
                <button type="submit" class="btn aqua-gradient">Vaccine</button>
              </form>
            </td>
          </tr>
        </tbody>
      </table>
      <p class="pull-right"><a href="../../vaccine.php">Back</a></p>
    </div>
  </div>
</body>

</html>