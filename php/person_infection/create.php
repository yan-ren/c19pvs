<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$person_id = $date = $covid_id = "";

// Define error variables and initialize with empty values
$person_id_err = $date_err = "";

// Get variant values
$sql = "SELECT covid_id, variant FROM covid";
$result = mysqli_query($link, $sql);
$all_variants = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $person_id = trim($_POST["person_id"]);
  $date = trim($_POST["date"]);
  $covid_id = trim($_POST["covid_id"]);

  if (empty($date)) {
    $date_err = "infection date cannot be empty";
  }
  // Validate person info
  $sql = "SELECT person_id FROM person WHERE person_id=?";
  $stmt = mysqli_prepare($link, $sql);

  if ($stmt) {
    mysqli_stmt_bind_param($stmt, "i", $person_id);
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);
      if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
      } else {
        $person_id_err = "No person found for id: " . $person_id;
        // echo '<div class="alert alert-danger"><em>Find multiple open hour on day ' . $i->format("Y-m-d") . ' for facility: ' . $facility_name . '</em></div>';
      }
    } else {
      echo "SQL query error: " . mysqli_stmt_errno($stmt);
    }
  } else {
    echo "Prepare SQL error: " . $link->error;
  }

  // Check input errors before inserting in database
  if (empty($person_id_err) && empty($date_err)) {
    // Prepare an insert statement
    $sql = "INSERT INTO infection (person_id, date, covid_id) VALUES (?, ?, ?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param(
        $stmt,
        "isi",
        $person_id,
        $date,
        $covid_id
      );
      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records created successfully. Redirect to landing page
        echo "<script>alert('Adding Person Infection Record Successful!');location='person_infection.php';</script>";
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
          <h2 class="mt-5">Create Person Infection Record</h2>
          <p>Please fill this form and submit to add person infection record to the database</p>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
              <label>Person ID</label>
              <input type="number" name="person_id" class="form-control <?php echo (!empty($person_id_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $person_id; ?>">
              <span class="invalid-feedback"><?php echo $person_id_err; ?></span>
            </div>
            <div class="form-group">
              <label>Date Of Infection</label>
              <input type="date" name="date" class="form-control <?php echo (!empty($date_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $date; ?>">
              <span class="invalid-feedback"><?php echo $date_err; ?></span>
            </div>
            <div class="form-group">
              <label>Variant</label>
              <select class="custom-select" id="inputGroupSelect01" name="covid_id">
                <?php
                foreach ($all_variants as $variant) {
                  echo '<option values=\"' . $variant['covid_id'] . '\">' . $variant['covid_id'] . ': ' . $variant['variant'] . '</option>';
                }
                ?>
              </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="person_infection.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>