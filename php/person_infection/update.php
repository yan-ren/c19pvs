<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$person_id = $date = $covid_id = "";
$date_err = "";

// Get variant values
$sql = "SELECT covid_id, variant FROM covid";
$result = mysqli_query($link, $sql);
$all_variants = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["person_id"]) && !empty($_POST["person_id"])) {
  $person_id = trim($_POST["person_id"]);
  $date = trim($_POST["date"]);
  $covid_id = trim($_POST["covid_id"]);

  $old_date = trim($_POST["old_date"]);
  $old_covid_id = trim($_POST["old_covid_id"]);

  if (empty($date)) {
    $date_err = "infection date cannot be empty";
  }

  if (empty($date_err)) {
    // Prepare an update statement
    $sql = "UPDATE infection SET date=?, covid_id=? WHERE person_id=? AND date=? AND covid_id=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param(
      $stmt,
      "siisi",
      $date,
      $covid_id,
      $person_id,
      $old_date,
      $old_covid_id
    );

    if ($stmt) {
      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records updated successfully. Redirect to landing page
        echo "<script>alert('Update Person Successful!');location='person_infection.php';</script>";
        exit();
      } else {
        $error = mysqli_stmt_error($stmt);
        echo '<script> alert("' . $error . '")</script>';
      }
    } else {
      echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='update.php';</script>";
    }
    // Close statement
    mysqli_stmt_close($stmt);
  }
  // Close connection
  mysqli_close($link);
} else {
  // Check existence of id parameter before processing further
  if (isset($_GET["person_id"]) && !empty(trim($_GET["person_id"]))) {
    // Get URL parameter
    $person_id = trim($_GET["person_id"]);
    $old_date = trim($_GET['date']);
    $old_covid_id = trim($_GET['covid_id']);

    // Prepare a select statement
    $sql = "SELECT * FROM person WHERE person_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "i", $person_id);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
          /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

          // Retrieve individual field value
          $first_name = $row["first_name"];
          $last_name = $row["last_name"];
        } else {
          // URL doesn't contain valid id. Redirect to error page
          header("location: error.php");
          exit();
        }
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
          <p>Please edit the input values and submit to update the person record.</p>
          <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group">
              <label>Person ID</label>
              <input type="read" name="person_id" class="form-control" value="<?php echo $person_id; ?>" readonly>
            </div>
            <div class="form-group">
              <label>First Name</label>
              <input type="string" name="first_name" class="form-control" value="<?php echo $first_name; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="string" name="last_name" class="form-control" value="<?php echo $last_name; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Covid Variant</label>
              <select class="custom-select" id="inputGroupSelect01" name="covid_id">
                <?php
                foreach ($all_variants as $variant) {
                  if ($variant['covid_id'] == $old_covid_id) {
                    echo '<option selected values=\"' . $variant['covid_id'] . '\">' . $variant['covid_id'] . ': ' . $variant['variant'] . '</option>';
                  } else {
                    echo '<option values=\"' . $variant['covid_id'] . '\">' . $variant['covid_id'] . ': ' . $variant['variant'] . '</option>';
                  }
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Date Of Infection</label>
              <input type="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $old_date; ?>">
              <span class="invalid-feedback"><?php echo $date_err; ?></span>
            </div>
            <input type="hidden" name="old_date" value="<?php echo $old_date; ?>" />
            <input type="hidden" name="old_covid_id" value="<?php echo $old_covid_id; ?>" />
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="person_infection.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>