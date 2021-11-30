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
            <h2 class="pull-left">Infection</h2>
            <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New INFECTION</a>
          </div>
          <?php
          // Include config file
          require_once "../config.php";
          $link = connect();
          // Attempt select query execution
          $sql = "SELECT person.person_id, infection.covid_id, first_name, last_name, date, variant FROM infection INNER JOIN person ON infection.person_id = person.person_id
          INNER JOIN covid ON infection.covid_id = covid.covid_id ORDER BY person.person_id ASC";
          if ($result = mysqli_query($link, $sql)) {
            if (mysqli_num_rows($result) > 0) {
              echo '<table class="table table-bordered table-striped">';
              echo "<thead>";
              echo "<tr>";
              echo "<th>Person ID</th>";
              echo "<th>First Name</th>";
              echo "<th>Last Name</th>";
              echo "<th>Variant</th>";
              echo "<th>Infection Date</th>";
              echo "<th>Action</th>";
              echo "</tr>";
              echo "</thead>";
              echo "<tbody>";
              while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row['person_id'] . "</td>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                echo "<td>" . $row['variant'] . "</td>";
                echo "<td>" . $row['date'] . "</td>";
                echo "<td>";
                echo '<a href="update.php?person_id=' . $row['person_id'] . '&date=' . $row['date'] . '&covid_id=' . $row['covid_id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                echo '<a href="delete.php?person_id=' . $row['person_id'] . '&date=' . $row['date'] . '&covid_id=' . $row['covid_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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