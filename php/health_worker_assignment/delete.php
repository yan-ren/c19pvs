<?php
error_reporting(-1);
ini_set('display_errors', 'On');

// Process delete operation after confirmation
if (isset($_POST["assignment_id"]) && !empty($_POST["assignment_id"])) {
  // Include config file
  require_once "../config.php";
  $link = connect();

  $assignment_id = trim($_POST["assignment_id"]);
  // Prepare a delete statement
  $sql = "DELETE FROM healthcare_worker_assignment WHERE assignment_id = ?";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters, i stands for integer,s stands for string
    mysqli_stmt_bind_param($stmt, "i", $assignment_id);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Records deleted successfully. Redirect to landing page
      header("location: assignment.php");
      exit();
    } else {
      echo "Oops! Something went wrong. Please try again later.";
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
  <title>Delete Record</title>
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
          <h2 class="mt-5 mb-3">Delete Public Health Worker Record</h2>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="alert alert-danger">
              <input type="hidden" name="assignment_id" value="<?php echo trim($_GET["assignment_id"]); ?>" />
              <p>Are you sure you want to delete this healthcare worker assignment?</p>
              <p>
                <input type="submit" value="Yes" class="btn btn-danger">
                <a href="delete.php" class="btn btn-secondary">No</a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>