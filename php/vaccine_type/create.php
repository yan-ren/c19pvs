<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$vaccine_name = $status = $approval = $dose = $suspension = "";
$status_error = $dose_error = $vaccine_name_error = $approval_error = $suspension_error = "";

//get status values
$sql = "SELECT DISTINCT `status` FROM vaccine;";
$result = mysqli_query($link, $sql);
$all_status = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $vaccine_name = trim($_POST["vaccine_name"]);
  $approval = trim($_POST["approval"]);
  $status = trim($_POST["status"]);
  $suspension = trim($_POST['suspension']);
  $dose = (int)trim($_POST['dose']);


  // Validate vaccine name
  if (empty($vaccine_name) && $vaccine_name === 'NULL') {
    $vaccine_name_error = "Please enter a valid Vaccine name";
  }

  if (empty($approval)) {
    $approval = null;
  }
  if (empty($suspension)) {
    $suspension = null;
  }

  // Check input errors before inserting in database
  if (empty($vaccine_name_error)) {
    // Prepare an insert statement
    $sql = "INSERT INTO vaccine (vaccine_name, status, dose, approval, suspension) VALUES (?, ?, ?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "ssiss", $vaccine_name, $status, $param_dose_id, $approval, $suspension);

      $param_dose_id = (int)($dose);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records created successfully. Redirect to landing page
        header("location: vaccine_type.php");
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
  }

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
          <h2 class="mt-5">Create Vaccine Type Record</h2>
          <p>Please fill this form and submit to add Vaccine Type Record to the database</p>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
              <label>Vaccine Name</label>
              <input type="text" name="vaccine_name" class="form-control <?php echo (!empty($vaccine_name_error)) ? 'is-invalid' : '' ?>" value="<?php echo $vaccine_name; ?>">
              <span class="invalid-feedback"><?php echo $vaccine_name_error; ?></span>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="custom-select" id="inputGroupSelect01" name="status">
                <?php
                foreach ($all_status as $sta) {
                  echo '<option values=\"' . $sta['status'] . '\">' . $sta['status'] . '</option>';
                }
                ?>
              </select>
            </div>

            <div class="form-group">
              <label>Dose</label>
              <input type="number" name="dose" class="form-control <?php echo (!empty($dose_error)) ? 'is-invalid' : '' ?>" value="<?php echo $dose; ?>">
              <span class="invalid-feedback"><?php echo $dose_error; ?></span>
            </div>
            <div class="form-group">
              <label>Approval Date</label>
              <input type="date" name="approval" class="form-control <?php echo (!empty($approval_error)) ? 'is-invalid' : '' ?>" value="<?php echo $approval; ?>">
              <span class="invalid-feedback"><?php echo $approval_error; ?></span>
            </div>

            <div class="form-group">
              <label>Suspension Date</label>
              <input type="date" name="suspension" class="form-control <?php echo (!empty($suspension_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $suspension; ?>">
              <span class="invalid-feedback"><?php echo $suspension_error; ?></span>
            </div>

            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="vaccine_type.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>