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
        <li class="nav-item">
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
    <div class="mdb-color white-text">
      DB manage has access to following tables, use with caution!
    </div>
    <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
      <a href=""><button type="button" class="btn aqua-gradient">Person</button></a>
      <a href=""><button type="button" class="btn aqua-gradient">Public Health Worker</button></a>
      <a href=""><button type="button" class="btn aqua-gradient">Public Health Facility</button></a>
      <a href=""><button type="button" class="btn aqua-gradient">Vaccination Type</button></a>
      <a href=""><button type="button" class="btn aqua-gradient">Covid-19 Infection Variant Type</button></a>
      <a href="/php/age_group/age_group.php"><button type="button" class="btn aqua-gradient">Age Group</button></a>
      <a href=""><button type="button" class="btn aqua-gradient">Province</button></a>
    </div>
  </div>
  <?php include "./html/footer.html" ?>
</body>

</html>