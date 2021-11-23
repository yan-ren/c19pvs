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
          <div class="mt-5 mb-3 clearfix">
            <h2 class="pull-left">Public Health Worker Assignment</h2>
            <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New
              Assignment</a>
          </div>
          <?php
          // Include config file
          require_once "../config.php";
          $link = connect();
          // Attempt select query execution
          $sql = "SELECT * FROM healthcare_worker_assignment ORDER BY person_id ASC";
          if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
              echo '<table class="table table-bordered table-striped">';
              echo "<thead>";
              echo "<tr>";
              echo "<th>Person_id</th>";
              echo "<th>Facility_name</th>";
              echo "<th>Start Date</th>";
              echo "<th>End Date</th>";
              echo "<th>Role</th>";
              echo "<th>Vaccine Name</th>";
              echo "<th>Dose Given</th>";
              echo "<th>Lot</th>";
              echo "<th>Action</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['person_id'] . "</td>";
                echo "<td>" . $row['facility_name'] . "</td>";
                echo "<td>" . $row['start_date'] . "</td>";
                echo "<td>" . $row['end_date'] . "</td>";
                echo "<td>" . $row['role'] . "</td>";
                echo "<td>" . $row['vaccine_name'] . "</td>";
                echo "<td>" . $row['dose_given'] . "</td>";
                echo "<td>" . $row['lot'] . "</td>";
                echo "<td>";
                echo '<a href="read.php?person_id=' . $row['person_id'] . '&facility_name=' . $row['facility_name'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                echo '<a href="update.php?person_id=' . $row['person_id'] . '&facility_name=' . $row['facility_name'] . '&role=' . $row['role'] . '&start_date=' . $row['start_date'] . '&end_date=' . $row['end_date'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                echo '<a href="delete.php?person_id=' . $row['person_id'] . '&facility_name=' . $row['facility_name'] . '"class="mr-3" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                echo "</td>";
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