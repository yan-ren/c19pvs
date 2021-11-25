<?php
// Include config file
require_once "../config.php";
$link = connect();

// Define variables and initialize with empty values
$min_age = $max_age = $vaccination_date = "";
$min_age_err = "";

// Processing form data when form is submitted
if (isset($_POST["age_group_id"]) && !empty($_POST["age_group_id"])) {
  // Get hidden input value
  $age_group_id = $_POST["age_group_id"];

  // Validate age
  $input_min_age = (int)(trim($_POST["min_age"]));
  $input_max_age = (int)(trim($_POST["max_age"]));

  if ($input_max_age != 0 && $input_min_age != 0 && $input_min_age > $input_max_age) {
    $min_age_err = "Min age is bigger than Max age";
  }

  if (empty(trim($_POST["min_age"]))) {
    $input_min_age = null;
  }
  if (empty(trim($_POST["max_age"]))) {
    $input_max_age = null;
  }
  if (empty(trim($_POST["vaccination_date"]))) {
    $input_vaccination_date = null;
  } else {
    $input_vaccination_date = trim($_POST["vaccination_date"]);
  }

  // Check input errors before inserting in database
  if (empty($min_age_err)) {
    // Prepare an update statement
    $sql = "UPDATE age_group SET min_age=?, max_age=?, vaccination_date=? WHERE age_group_id=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iisi", $input_min_age, $input_max_age, $input_vaccination_date, $age_group_id);

    if ($stmt) {
      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records updated successfully. Redirect to landing page
        header("location: age_group.php");
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
  if (isset($_GET["age_group_id"]) && !empty(trim($_GET["age_group_id"]))) {
    // Get URL parameter
    $age_group_id =  trim($_GET["age_group_id"]);

    // Prepare a select statement
    $sql = "SELECT * FROM age_group WHERE age_group_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "i", $param_id);

      // Set parameters
      $param_id = $age_group_id;

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
          /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

          // Retrieve individual field value
          $min_age = $row["min_age"];
          $max_age = $row["max_age"];
          $vaccination_date = $row["vaccination_date"];
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
          <h2 class="mt-5">Update Record</h2>
          <p>Please edit the input values and submit to update the age group record.</p>
          <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group">
              <label>Min Age</label>
              <input type="number" name="min_age" class="form-control" value="<?php echo $min_age; ?>">
              <span class="invalid-feedback"><?php echo $min_age; ?></span>
            </div>
            <div class="form-group">
              <label>Max Age</label>
              <input type="number" name="max_age" class="form-control" value="<?php echo $max_age; ?>">
              <span class="invalid-feedback"><?php echo $max_age; ?></span>
            </div>
            <div class="form-group">
              <label>Vaccination Date</label>
              <input type="date" name="vaccination_date" class="form-control" value="<?php echo $vaccination_date; ?>"></input>
              <span class="invalid-feedback"><?php echo $vaccination_date; ?></span>
            </div>
            <input type="hidden" name="age_group_id" value="<?php echo $age_group_id; ?>" />
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="age_group.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>