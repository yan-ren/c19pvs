<!DOCTYPE html>
<html lang="en">

<head>
  <?php include($_SERVER['DOCUMENT_ROOT'] . "/html/head.html") ?>
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <a href="../../index.php">Back to Home</a>
          <div class="mt-5 mb-3 clearfix">
            <h2 class="pull-left">Montréal Facilities Record</h2>
          </div>
          <p><i>A detailed report of all the facilities in the city of Montréal</i></p>
          <?php
          // Include config file
          require_once "../config.php";

          $link = connect();
          // Attempt select query execution
          $sql = "SELECT `name`, address, phone, type, capacity, 
                  ( SELECT COUNT(person_id)
                    FROM healthcare_worker
                    WHERE fac.`name`= healthcare_worker.facility_name
                  ) AS totalWorkers, 
                  ( SELECT SUM(dose)
                    FROM vaccination
                    WHERE fac.`name`= vaccination.location
                  ) AS totalDose, 
                  ( SELECT COUNT(booking_id) 
                    FROM booking
                    WHERE fac.`name`= booking.facility_name
                  ) AS totalBookings
                  FROM facility AS fac
                  WHERE city='Montreal'
                  ORDER BY totalDose ASC  ";

          if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
              echo '<table class="table table-bordered table-striped">';
              echo "<thead>";
              echo "<tr>";
              echo "<th>Facility</th>";
              echo "<th>Address</th>";
              echo "<th>Phone</th>";
              echo "<th>Type</th>";
              echo "<th>Capacity</th>";
              echo "<th>Total Health Workers</th>";
              echo "<th>Total Doses Given</th>";
              echo "<th>Total Booked Doses</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $row['address'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
                echo "<td>" . $row['type'] . "</td>";
                echo "<td>" . $row['capacity'] . "</td>";
                echo "<td>" . $row['totalWorkers'] . "</td>";
                echo "<td>" . $row['totalDose'] . "</td>";
                echo "<td>" . $row['totalBookings'] . "</td>";
                echo "</tr>";
              }
              echo "</tbody>";
              echo "</table>";
              // Free result set
              mysqli_free_result($result);
            } else {
              echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
            }
          } else {
            echo "Oops! Something went wrong. Please try again later.";
          }

          // Close connection
          mysqli_close($link);
          ?>
        </div>
      </div>
    </div>
  </div>
</body>

</html>