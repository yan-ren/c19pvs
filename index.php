<!DOCTYPE html>
<html>

<head>
  <?php include($_SERVER['DOCUMENT_ROOT'] . "/html/head.html") ?>
</head>

<body>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="index.php">C19PVS</a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarText">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="appointment.php">Appointment</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="vaccine.php">Vaccine</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="report.php">Report</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="search.php">Search</a>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container">
    <h2>For System Manager</h2>
    <h4>Click here for <a href="manage.php">Create/Delete/Edit/Display a Table</a></h4>
    <hr>
    <h4>Database table at a glance</h4>
    <?php
    require_once('./php/config.php');

    $con = connect();
    showTables($con);

    function showTables($link)
    {
      $sql = "SHOW TABLES";
      if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
          echo "<table class=\"table table-bordered table-striped\">";
          echo "<tr>";
          echo "<th>" . "Table Name" . "</td>";
          echo "</tr>";
          while ($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row[0] . "</td>";
            echo "</tr>";
          }
          echo "</table>";
          // Free result set
          mysqli_free_result($result);
        } else {
          echo "No records matching your query were found.";
        }
      } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
      }
    }
    ?>
  </div><!-- /.container -->
  <?php include "./html/footer.html" ?>
</body>

</html>