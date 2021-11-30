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
        iFrameID.style.display = "block";
      }
    }

    function action() {
      document.getElementById("mainImg").style.display = "none";
    }
  </script>
</head>

<body>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a id="logo" class="navbar-brand" href="index.php">C19PVS</a>
      <div class="collapse navbar-collapse " id="navbarText">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="booking.php">Booking</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="vaccine.php">Vaccine</a>
          </li>
          <li class="nav-item">
            <div class="dropdown">
              <a class="nav-link" href="#" data-toggle="dropdown">
                Report <i class="fa fa-chevron-down"></i>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="/php/report/nurse.php">Nurse Report</a>
                <a class="dropdown-item" href="/php/report/facility.php">Facility Report</a>
                <a class="dropdown-item" href="/php/report/person.php">Person Report</a>
              </div>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="search.php">Search</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">|</a>
          </li>
          <li class="nav-item">
            <div class="dropdown">
              <a class="nav-link" href="#" data-toggle="dropdown">
                Create/Delete/Edit/Display a Table <i class="fa fa-chevron-down"></i>
              </a>
              <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="/php/person/person.php" target="ifbox" onclick="action()">Person</a>
                <a class="dropdown-item" href="/php/person_infection/person_infection.php" target="ifbox" onclick="action()">Person Infection</a>
                <a class="dropdown-item" href="/php/public_health_worker/public_health_worker.php" target="ifbox" onclick="action()">Public Health Worker</a>
                <a class="dropdown-item" href="/php/health_worker_assignment/assignment.php" target="ifbox" onclick="action()">Public Health Worker Assignment</a>
                <a class="dropdown-item" href="/php/facility/facility.php" target="ifbox" onclick="action()">Public Health Facility</a>
                <a class="dropdown-item" href="/php/vaccine_type/vaccine_type.php" target="ifbox" onclick="action()">Vaccine Type</a>
                <a class="dropdown-item" href="/php/variants/variants.php" target="ifbox" onclick="action()">Covid-19 Infection Variant</a>
                <a class="dropdown-item" href="/php/age_group/age_group.php" target="ifbox" onclick="action()">Age Group</a>
                <a class="dropdown-item" href="/php/province/province.php" target="ifbox" onclick="action()">Province</a>
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav><!-- Fixed navbar end-->

  <!-- iframe -->
  <div class="container-fluid">
    <div class="">
      <iframe name="ifbox" id="ifboxId" width="100%" onload="iframeLoaded()" style="display:none;" frameborder="0" scrolling="auto">
      </iframe>
    </div>
  </div><!-- iframe end -->

  <!-- ======= Hero Section ======= -->
  <div id="mainImg">
    <section id="hero" class="d-flex align-items-center">
      <div class="container">
        <h1>Welcome to C19PVS</h1>
        <h2>COVID-19 Public Health Care Population Vaccination System</h2>
        <a href="index.php" class="btn-get-started scrollto">Get Started</a>
      </div>
    </section><!-- End Hero -->
  </div>
  <!-- <?php include "./html/footer.html" ?> -->
</body>

</html>