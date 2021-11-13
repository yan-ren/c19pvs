<!DOCTYPE html>
<html>

<head>
  <?php include($_SERVER['DOCUMENT_ROOT'] . "/html/head.html") ?>
  <script type="text/javascript">
    function iframeLoaded() {
      var iFrameID = document.getElementById('ifboxId');
      if (iFrameID) {
        // clear the current height, and set height based on content
        iFrameID.height = "";
        iFrameID.height = iFrameID.contentWindow.document.body.scrollHeight + "px";
      }
    }
  </script>
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
          <a class="nav-link" href="booking.php">Booking</a>
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
  <div class="container-fluid">
    <h4>For System Manager</h2>
      <!-- dropdown -->
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle aqua-gradient" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Click here for Create/Delete/Edit/Display a Table
        </button>
        <div class="dropdown-menu aqua-gradient" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" href="#">Person</a>
          <a class="dropdown-item" href="#">Public Health Worker</a>
          <a class="dropdown-item" href="#">Public Health Facility</a>
          <a class="dropdown-item" href="#">Vaccination Type</a>
          <a class="dropdown-item" href="#">Covid-19 Infection Variant</a>
          <a class="dropdown-item" href="/php/age_group/age_group.php" target="ifbox">Age Group</a>
          <a class="dropdown-item" href="#">Province</a>
        </div>
      </div>
      <!-- ./dropdown -->
      <!-- iframe -->
      <div class="">
        <iframe name="ifbox" id="ifboxId" width="100%" onload="iframeLoaded()" background-color:#b0c4de; frameborder="0" scrolling="auto">
        </iframe>
      </div>
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
            echo "<table class=\"table table-responsive table-striped\">";
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