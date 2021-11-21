<?php
$booking_id;

// Process delete operation after confirmation
if (isset($_POST["booking_id"]) && !empty($_POST["booking_id"])) {
  $booking_id = (int)($_POST["booking_id"]);
  // Include config file
  require_once "../config.php";
  $link = connect();

  // Prepare a delete statement
  $sql = "UPDATE booking SET status='cancel' WHERE booking_id=?";

  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters, i stands for integer
    mysqli_stmt_bind_param($stmt, "i", $booking_id);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Records deleted successfully. Redirect to landing page
      header("location: booking.php");
      exit();
    } else {
      echo '<div class="alert alert-danger"><em>' . mysqli_stmt_error($stmt) . '</em></div>';
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
          <h2 class="mt-5 mb-3">Cancel Booking</h2>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="alert alert-danger">
              <input type="hidden" name="booking_id" value="<?php echo trim($_GET["booking_id"]); ?>" />
              <p>Are you sure you want to cancel this booking? Booking ID: <?php echo trim($_GET["booking_id"]); ?></p>
              <p>
                <input type="submit" value="Yes" class="btn btn-danger">
                <a href="booking.php" class="btn btn-secondary">No</a>
              </p>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>