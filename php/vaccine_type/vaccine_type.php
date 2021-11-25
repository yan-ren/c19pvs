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
            <h2 class="pull-left">Vaccine Type</h2>
            <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Vaccine Type Record</a>
          </div>
          <?php
          // Include config file
          require_once "../config.php";
          $link = connect();
          // Attempt select query execution
          $sql = "SELECT * FROM vaccine ORDER BY vaccine_name ASC";
          if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
              echo '<table class="table table-bordered table-striped">';
              echo "<thead>";
              echo "<tr>";
              echo "<th>Vaccine Name</th>";
              echo "<th>Status</th>";
              echo "<th>Dose</th>";
              echo "<th>Approval</th>";
              echo "<th>Suspension</th>";
              echo "<th>Action</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['vaccine_name'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
                echo "<td>" . $row['dose'] . "</td>";
                echo "<td>" . $row['approval'] . "</td>";
                echo "<td>" . $row['suspension'] . "</td>";
                echo "<td>";
                echo '<a href="read.php?vaccine_name=' . urlencode($row['vaccine_name']) . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                echo '<a href="update.php?vaccine_name=' . urlencode($row['vaccine_name']) . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                echo '<a href="delete.php?vaccine_name=' . urlencode($row['vaccine_name']) . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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