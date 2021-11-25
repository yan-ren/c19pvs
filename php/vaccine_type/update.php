<?php
// Include config file
require_once "../config.php";

$link = connect();

//define variables
$vaccine_name = $status = $approval = $dose = $suspension = "";
$status_error = $dose_error = $vaccine_name_error = $approval_error = $suspension_error = "";


//get status values
$sql = "SELECT DISTINCT `status` FROM vaccine;";
$result = mysqli_query($link, $sql);
$all_status = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Processing form data when form is submitted
if (isset($_POST["vaccine_name"]) && !empty($_POST["vaccine_name"])) {
  // Get hidden input value
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
    // Prepare an update statement
    $sql = "UPDATE vaccine SET vaccine_name=?, status=?, dose =?, approval =?, suspension =? WHERE vaccine_name=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ssisss", $vaccine_name, $status, $param_dose_id, $approval, $suspension, $vaccine_name);

    $param_dose_id = (int)($dose);

    if ($stmt) {
      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records updated successfully. Redirect to landing page
        header("location: vaccine_type.php");
        exit();
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
    }

    // Close statement
    mysqli_stmt_close($stmt);
  }

  // Close connection
  mysqli_close($link);
} else {
  // Check existence of id parameter before processing further
  if (isset($_GET["vaccine_name"]) && !empty(trim($_GET["vaccine_name"]))) {
    // Get URL parameter
    $vaccine_name = trim($_GET["vaccine_name"]);

    // Prepare a select statement
    $sql = "SELECT * FROM vaccine WHERE vaccine_name = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $param_vaccine_name);

      // Set parameters
      $param_vaccine_name = $vaccine_name;

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
          /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

          // Retrieve individual field value
          $vaccine_name = $row["vaccine_name"];
          $dose = $row["dose"];
          $status = $row["status"];
          $approval = $row["approval"];
          $suspension = $row["suspension"];
        } else {
          // URL doesn't contain valid id. Redirect to error page
          header("location: error.php");
          exit();
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
  } else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
  }
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
          <h2 class="mt-5">Update Vaccine Type Record</h2>
          <p>Please edit the input values and submit to update Vaccine Type record.</p>
          <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
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
            <input type="hidden" name="vaccine_name" value="<?php echo $vaccine_name; ?>" />
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="vaccine_type.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>