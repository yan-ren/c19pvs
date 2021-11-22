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
            <h2 class="pull-left">Nurses Record</h2>
          </div>
          <p><i>Nurses that have performed at least 20 vaccinations in total</i></p>
          <?php
          // Include config file
          require_once "../config.php";

          $link = connect();
          $sql = "SELECT first_name,  middle_name, last_name, phone, SUM(dose_given) AS dose
                  FROM healthcare_worker_assignment
                  INNER JOIN person on person.person_id = healthcare_worker_assignment.person_id
                  WHERE role='nurse'
                  GROUP BY healthcare_worker_assignment.person_id
                  HAVING SUM(dose_given) >= 20
                  ORDER BY dose DESC";
          // Attempt select query execution
          if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
              echo '<table class="table table-bordered table-striped">';
              echo "<thead>";
              echo "<tr>";
              echo "<th>First Name</th>";
              echo "<th>Middle Name</th>";
              echo "<th>Last Name</th>";
              echo "<th>Phone</th>";
              echo "<th>Total Doses Given</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['middle_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                echo "<td>" . $row['phone'] . "</td>";
                echo "<td>" . $row['dose'] . "</td>";
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