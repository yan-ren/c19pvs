<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="./css/style.css" />
  <title>COVID-19 Public Health Care Population Vaccination System</title>
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="./css/bootstrap.theme.min.css">
  <script src="./js/jquery.min.js"></script>
  <script src="./js/bootstrap.min.js"></script>
</head>

<body>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a class="navbar-brand" href="index.php">C19PVS</a>
      </div>
      <div id="navbar" class="navbar-collapse collapse">
        <ul class="nav navbar-nav navbar-right">
          <li class="active"><a href="#">Home</a></li>
          <li><a href="">Appointment</a></li>
          <li><a href="">Report</a></li>
          <li><a href="">Search</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container">
    <div class="row content">
      <h2>For System Manager</h2>
      <h4>Click here for <a href="student.php">Create/Delete/Edit/Display a Table</a></h4>
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
    </div><!-- row -->
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