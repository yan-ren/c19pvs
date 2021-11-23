<?php
// Include config file
require_once "../config.php";

$link = connect();

// Define variables and initialize with empty values
$person_id = $facility_name = $start_date = $end_date = $role = $vaccine_name = $dose = $lot = "";
$start_date_error = $end_date_error = $role_error = $lot_error = $availability_error = "";

//get all facilities
$sql = "SELECT * FROM facility";
$result = mysqli_query($link, $sql);
$all_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

//get all safe vaccine for assignment
$sql2 = "SELECT * FROM vaccine where status = 'safe'";
$result2 = mysqli_query($link, $sql2);
$all_vaccines = mysqli_fetch_all($result2, MYSQLI_ASSOC);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $person_id = (int)trim($_POST["person_id"]);
  $facility_name = trim($_POST["facility_name"]);
  $start_date = trim($_POST["start_date"]);
  $end_date = trim($_POST["end_date"]);
  $role = trim($_POST["role"]);
  $vaccine_name = trim($_POST["vaccine_name"]);
  $dose = trim($_POST["dose_given"]);
  $lot = trim($_POST["lot"]);

  //    echo "<pre>";
  //    echo $person_id;
  //    echo $facility_name;
  //    echo $start_date;
  //    echo $end_date;
  //    echo $role;
  //    echo $vaccine_name;
  //    echo $dose;
  //    echo $lot;
  //    echo "</pre>";
  //    exit;

  //check if person in the public health worker database
  $sql_check = "SELECT * FROM healthcare_worker WHERE person_id = ? AND facility_name= ? ";

  if ($stmt = mysqli_prepare($link, $sql_check)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "is", $person_id, $facility_name);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (empty(mysqli_num_rows($result))) {
        $availability_error = "Person and Facility Name don't exist in Public Heath Worker Group";
        echo '<script> if(!alert("' . $availability_error . '")){
                            document.location =\'create.php\';
                        }
                        </script>';
      }
    } else {
      $error = mysqli_stmt_error($stmt);
      echo '<script> alert("' . $error . '")</script>';
    }
  } else {
    echo "<script>alert('Ops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
  }
  mysqli_stmt_close($stmt);
  mysqli_close($link);

  if ($role == "nurse") {
    // Check for facility availability
    // Find how many nurses working on given date
    $number_of_nurses = 0;
    $link = connect();
    $sql_check2 = "SELECT count(*) AS nurses
    FROM healthcare_worker_assignment
    WHERE start_date >= ? AND end_date <= ? AND role='nurse' AND facility_name=?";

    if ($stmt = mysqli_prepare($link, $sql_check2)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "sss", $start_date, $end_date, $facility_name);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) == 1) {
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
          $number_of_nurses = (int)($row["nurses"]);
          echo var_dump($number_of_nurses);
        }
      } else {
        $error = mysqli_stmt_error($stmt);
        echo '<script> alert("' . $error . '")</script>';
      }
    } else {
      echo "<script>alert('Oooooooooops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
    }
    mysqli_stmt_close($stmt);
    mysqli_close($link);

    if ($number_of_nurses == 0) {
      $availability_error = "No availability on given date!";
      //            echo '<script> if(!alert("' . $availability_error . '")){
      //                document.location = \'check_availability.php\';
      //            }
      //            </script>';
    }


    $link = connect();
    // Prepare an insert statement
    $sql = "INSERT INTO healthcare_worker_assignment (person_id, facility_name, start_date, end_date, role, vaccine_name, dose_given, lot) VALUES (?, ?, ?, ?,?,?,?,?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "isssssss", $person_id, $facility_name, $start_date, $end_date, $role, $vaccine_name, $dose, $lot);


      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records created successfully. Redirect to landing page
        header("location: assignment.php");
        exit();
      } else {
        $error = mysqli_stmt_error($stmt);
        echo '<script> alert("' . $error . '")</script>';
      }
    } else {
      echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
    }
    // Close statement
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  } else {

    $link = connect();

    //all roles except nurse
    // Prepare an insert statement
    $sql = "INSERT INTO healthcare_worker_assignment (person_id, facility_name, start_date, end_date, role, vaccine_name, dose_given, lot) VALUES (?,?,?,?,?,NULL,NULL,NULL)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "issss", $person_id, $facility_name, $start_date, $end_date, $role);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records created successfully. Redirect to landing page
        header("location: assignment.php");
        exit();
      } else {
        $error = mysqli_stmt_error($stmt);
        echo '<script> alert("' . $error . '")</script>';
      }
    } else {
      echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
    }
    // Close statement
    mysqli_stmt_close($stmt);
    mysqli_close($link);
  }
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
      width: 800px;
      margin: 0 auto;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <h2 class="mt-5">Create Public Health Worker Assignment</h2>
          <p>Please fill this form and submit to add Public Health Worker Assignment record to the database</p>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
              <label>Person ID</label>
              <input type="number" name="person_id" class="form-control" value="<?php echo $person_id; ?>">
            </div>

            <div class="form-group">
              <label>Facility name</label>
              <select class="custom-select" id="inputGroupSelect01" name="facility_name">
                <?php
                foreach ($all_facility as $facility) {
                  echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Start Date</label>
              <input type="date" name="start_date" class="form-control <?php echo (!empty($start_rate_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $start_date; ?>">
              <span class="invalid-feedback"><?php echo $start_date_error; ?></span>
            </div>
            <div class="form-group">
              <label>End Date</label>
              <input type="date" name="end_date" class="form-control <?php echo (!empty($end_date_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $end_date; ?>">
              <span class="invalid-feedback"><?php echo $end_date_error; ?></span>
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
              <input type="number" id="dose" name="dose_given" class="form-control dose" value="<?php echo $dose; ?>">
              </select>
            </div>
            <div class="form-group">
              <label>Lot ID</label>
              <input type="text" id="lot" name="lot" class="form-control" value="<?php echo $lot; ?>">
            </div>
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
</script>

</html>