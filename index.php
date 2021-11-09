<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="./css/style.css" />
  <title>COVID-19 Public Health Care Population Vaccination System</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
</head>

<body>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <a class="navbar-brand" href="index.php">C19PVS</a>
    <div class="collapse navbar-collapse justify-content-end" id="navbarText">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Home <span class="sr-only">(current)</span></a>
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
  <!-- footer -->
  <footer id="footer" class="footer-1">
    <div class="footer-copyright">
      <div class="container">
        <div class="row">
          <div class="col-md-12 text-center">
            <p>Copyright <a href="https://github.com/yan-ren/c19pvs">Yan</a>© 2021. All rights reserved.</p>
          </div>
        </div>
      </div>
    </div>
  </footer>
</body>

</html>