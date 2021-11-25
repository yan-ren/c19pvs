<?php
// Include config file
require_once "../config.php";

$link = connect();

// Define variables and initialize with empty values
$assignment_id = "";
$person_id = $facility_name = $start_date = $end_date = $role = $vaccine_name = $dose = $lot = "";
$availability_error = "";

// Get all facilities
$sql = "SELECT * FROM facility";
$result = mysqli_query($link, $sql);
$all_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get all safe vaccine for assignment
$sql = "SELECT * FROM vaccine where status = 'safe'";
$result = mysqli_query($link, $sql);
$all_vaccines = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $assignment_id = trim($_POST["assignment_id"]);
  $person_id = trim($_POST["person_id"]);
  $facility_name = trim($_POST["facility_name"]);
  $start_date = trim($_POST["start_date"]);
  $end_date = trim($_POST["end_date"]);
  $role = trim($_POST["role"]);
  $vaccine_name = trim($_POST["vaccine_name"]);
  $dose = trim($_POST["dose_given"]);
  $lot = trim($_POST["lot"]);

  if ($role == 'nurse') {
    // Check for facility capacity, loop through each day check if nurse working on that day is within facaility capacity
    $capacity = 0;
    $link = connect();
    $sql_check = "SELECT capacity FROM facility WHERE name= ?";
    $stmt = mysqli_prepare($link, $sql_check);
    if ($stmt) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $facility_name);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 1) {
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          $capacity = $row['capacity'];
        }
      } else {
        $error = mysqli_stmt_error($stmt);
        echo '<script> alert("' . $error . '")</script>';
        exit();
      }
    } else {
      echo "<script>alert('Ops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
      exit();
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    $number_of_nurses = 0;
    $link = connect();
    $end = new DateTime($end_date);
    $start = new DateTime($start_date);
    for ($date = $start; $date <= $end; $date->modify('+1 day')) {
      $number_of_nurses = 0;
      $date_string = $date->format('Y-m-d');
      $sql_check = "SELECT COUNT(person_id) AS count FROM healthcare_worker_assignment WHERE facility_name= ? AND start_date <= ? AND end_date >= ?";

      if ($stmt = mysqli_prepare($link, $sql_check)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $facility_name, $date_string, $date_string);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
          $result = mysqli_stmt_get_result($stmt);
          if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $number_of_nurses = $row['count'];
          }
        } else {
          $error = mysqli_stmt_error($stmt);
          echo '<script> alert("' . $error . '")</script>';
          exit();
        }
      } else {
        echo "<script>alert('Ops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
        exit();
      }

      if ($number_of_nurses >= $capacity) {
        $availability_error = "Number of nurses exceed facility capacity on date " . $date_string;
        echo '<script> if(!alert("' . $availability_error . '")){
                            document.location =\'create.php\';
                        }
              </script>';
        exit();
      }
    }
    mysqli_close($link);
  } else {
    $vaccine_name = null;
    $dose = null;
    $lot = null;
  }

  // Update healthcare_worker_assignment
  $link = connect();
  $sql = "UPDATE healthcare_worker_assignment SET start_date=?, end_date=?, role=?, vaccine_name=?, dose_given=?, lot=?
          WHERE assignment_id=?";
  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "ssssisi", $start_date, $end_date, $role, $vaccine_name, $dose, $lot, $assignment_id);
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Records update successfully. Redirect to landing page
      echo "<script>alert('Update Assignment Successful!');location.href='assignment.php'</script>";
      exit();
    } else {
      $error = mysqli_stmt_error($stmt);
      echo '<script> alert("' . $error . '")</script>';
      exit();
    }
  } else {
    echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
    exit();
  }
  // Close statement
  mysqli_stmt_close($stmt);
  mysqli_close($link);
} else if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["assignment_id"])) {
  $link = connect();

  // Prepare a select statement
  $sql = "SELECT * FROM healthcare_worker_assignment WHERE assignment_id = ?";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $param_id);

    // Set parameters
    $param_id = trim($_GET["assignment_id"]);
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) == 1) {
        /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $assignment_id = $row["assignment_id"];
        $person_id = $row["person_id"];
        $facility_name = $row["facility_name"];
        $start_date = $row["start_date"];
        $end_date = $row["end_date"];
        $role = $row["role"];
        $vaccine_name = $row["vaccine_name"];
        $dose = $row["dose_given"];
        $lot = $row["lot"];
      } else {
        // URL doesn't contain valid id parameter. Redirect to error page
        header("location: error.php");
        exit();
      }
    } else {
      echo "Oops! Something went wrong. Please try again later.";
      exit();
    }
  }

  // Close statement
  mysqli_stmt_close($stmt);

  // Close connection
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
          <h2 class="mt-5">Update Public Health Worker Assignment</h2>
          <p>Please fill this form and submit to update Public Health Worker Assignment record to the database</p>
          <form name="update_assignment" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="return validateForm(this.name)" method="post">
            <div class="form-group">
              <label>Person ID</label>
              <input type="number" id="person_id" name="person_id" class="form-control" value="<?php echo $person_id; ?>" readonly>
            </div>

            <div class="form-group">
              <label>Facility name</label>
              <input type="text" id="facility_name" name="facility_name" class="form-control" value="<?php echo $facility_name; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Start Date</label>
              <input type="date" name="start_date" class="form-control" value="<?php echo $start_date; ?>">
            </div>
            <div class="form-group">
              <label>End Date</label>
              <input type="date" name="end_date" class="form-control" value="<?php echo $end_date; ?>">
            </div>
            <div class="form-group">
              <label>Role</label>
              <select class="custom-select" id="inputGroupSelectRole" name="role" onchange="readOnly()">
                <option value="nurse">Nurse</option>
                <option value="manager">Manager</option>
                <option value="security">Security</option>
                <option value="secretary">Secretary</option>
                <option value="regular">Regular</option>
              </select>
            </div>
            <div class="form-group">
              <label>Vaccine Name</label>
              <select class="custom-select" id="inputGroupSelectVaccine" name="vaccine_name">
                <?php
                foreach ($all_vaccines as $vaccine) {
                  echo '<option values=\"' . $vaccine['vaccine_name'] . '\">' . $vaccine['vaccine_name'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Dose Given</label>
              <input type="number" id="dose" name="dose_given" class="form-control" value="<?php echo $dose; ?>">
              </select>
            </div>
            <div class="form-group">
              <label>Lot ID</label>
              <input type="text" id="lot" name="lot" class="form-control" value="<?php echo $lot; ?>">
            </div>
            <input type="hidden" name="assignment_id" value="<?php echo $assignment_id; ?>" />
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="assignment.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
<script>
  function readOnly() {
    let role = document.getElementById("inputGroupSelectRole").value;
    if (role != "nurse") {
      document.getElementById("dose").value = "";
      document.getElementById("lot").value = "";
      document.getElementById("dose").readOnly = true;
      document.getElementById("lot").readOnly = true;
      var selectElement = document.getElementById("inputGroupSelectVaccine");
      selectElement.value = "";
      selectElement.setAttribute("disabled", "disabled");
    } else {
      document.getElementById("dose").readOnly = false;
      document.getElementById("lot").readOnly = false;
      document.getElementById("inputGroupSelectVaccine").disabled = false;
    }
  }

  function validateForm(formName) {
    var x = document.forms[formName];
    if (x["person_id"] && x["person_id"].value == "") {
      alert("Person ID cannot be empty");
      return false;
    }
    if (x["start_date"] && x["start_date"].value == "") {
      alert("Start Date cannot be empty");
      return false;
    }
    if (x["end_date"] && x["end_date"].value == "") {
      alert("End Date cannot be empty");
      return false;
    }
    var startDate = new Date(x["start_date"].value);
    var endDate = new Date(x["end_date"].value);
    if (startDate > endDate) {
      alert("Start date cannot be later than end date");
      return false;
    }
  }
</script>

</html>