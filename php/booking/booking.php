<?php
require_once "../config.php";

$result_rows = [];

if (isset($_GET["booking_id"]) && !empty(trim($_GET["booking_id"]))) {
  $booking_id = (int)(trim($_GET["booking_id"]));
  $link = connect();
  // Attempt select query execution
  $sql = "SELECT booking_id, first_name, last_name, facility_name, date, time, booking.status FROM booking INNER JOIN person ON booking.person_id = person.person_id WHERE booking_id=?";
  if ($stmt = mysqli_prepare($link, $sql)) {
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "i", $booking_id);

    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      $result = mysqli_stmt_get_result($stmt);

      if (mysqli_num_rows($result) == 1) {
        /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
        $result_rows[] = mysqli_fetch_array($result, MYSQLI_ASSOC);
      } else {
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
  $link = connect();
  // Attempt select query execution
  $sql = "SELECT booking_id, first_name, last_name, facility_name, date, time, booking.status FROM booking INNER JOIN person ON booking.person_id = person.person_id ORDER BY booking_id ASC";
  if ($result = mysqli_query($link, $sql)) {
    while ($row = mysqli_fetch_array($result)) {
      $result_rows[] = $row;
    }
    // Free result set
    mysqli_free_result($result);
  } else {
    echo "SQL query error: " . $link->error;
  }

  // Close connection
  mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <?php include($_SERVER['DOCUMENT_ROOT'] . "/html/head.html") ?>
  <style>
    .has-search .form-control {
      padding-left: 2.375rem;
    }

    .has-search .form-control-feedback {
      position: absolute;
      z-index: 2;
      display: block;
      width: 2.375rem;
      height: 2.375rem;
      line-height: 2.375rem;
      text-align: center;
      pointer-events: none;
      color: #aaa;
    }
  </style>
  <script>
    function searchKeyPress(e) {
      // look for window.event in case event isn't passed in
      e = e || window.event;
      if (e.keyCode == 13) {
        document.getElementById('searchBookingId').submit();
        return false;
      }
      return true;
    }
  </script>
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <div class="mt-5 mb-3 clearfix">
            <h4>Look for a specific booking?</h4>
            <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="get">
              <div class="form-group has-search pull-left">
                <span class="fa fa-search form-control-feedback"></span>
                <input id="searchBookingId" class="form-control" name="booking_id" type="number" onkeypress="return searchKeyPress(event);" placeholder="Search Booking ID">
              </div>
            </form>
          </div>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>Booking ID</th>
                <th>Frist Name</th>
                <th>Last Name</th>
                <th>Facility</th>
                <th>Date</th>
                <th>Time</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach ($result_rows as $row) {
                echo "<tr>";
                echo "<td>" . $row['booking_id'] . "</td>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                echo "<td>" . $row['facility_name'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>" . $row['time'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>";
                echo '<a href="update.php?booking_id=' . $row['booking_id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                echo '<a href="delete.php?booking_id=' . $row['booking_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                echo "</td>";
                echo "</tr>";
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</body>

</html>