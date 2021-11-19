<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$covid_id = $status = $variant = "";
$covid_id_error = $variant_error = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $covid_id = (int)trim($_POST["covid_id"]);
  $variant = trim($_POST["variant"]);
  $status = trim($_POST["status"]);

  // Validate
  if (empty($covid_id) && $covid_id === 0) {
    $covid_id_error = "Please enter a valid Covid ID";
  }
  if (empty($variant)) {
    $variant_error = "Please enter a variant name";
  }

  // Check input errors before inserting in database
  if (empty($covid_id_error) && empty($variant_error)) {
    // Prepare an insert statement
    $sql = "INSERT INTO covid (covid_id, variant, status) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "iss", $covid_id, $variant, $status);

      $param_covid_id = (int)($covid_id);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records created successfully. Redirect to landing page
        header("location: variants.php");
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
          <h2 class="mt-5">Create Variant Type Record</h2>
          <p>Please fill this form and submit to add Variant Type Record to the database</p>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
              <label>Covid ID </label>
              <input type="number" name="covid_id" class="form-control <?php echo (!empty($covid_id_error)) ? 'is-invalid' : '' ?>" value="<?php echo $covid_id; ?>">
              <span class="invalid-feedback"><?php echo $covid_id_error; ?></span>
            </div>
            <div class="form-group">
              <label>Vaccine Name</label>
              <input type="text" name="variant" class="form-control <?php echo (!empty($variant_error)) ? 'is-invalid' : '' ?>" value="<?php echo $variant; ?>">
              <span class="invalid-feedback"><?php echo $variant_error; ?></span>
            </div>
            <div class="form-group">
              <label>Status</label>
              <select class="custom-select" id="inputGroupSelect01" name="status">
                <option values="A">A</option>
                <option values="D">D</option>
              </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="variants.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>