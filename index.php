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
            <a class="nav-link" href="report.php">Report</a>
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
                <a class="dropdown-item" href="#">Person</a>
                <a class="dropdown-item" href="/php/public_health_worker/public_health_worker.php" target="ifbox">Public Health Worker</a>
                <a class="dropdown-item" href="#">Public Health Facility</a>
                <a class="dropdown-item" href="/php/vaccine_type/vaccine_type.php" target="ifbox">Vaccination Type</a>
                <a class="dropdown-item" href="#">Covid-19 Infection Variant</a>
                <a class="dropdown-item" href="/php/age_group/age_group.php" target="ifbox">Age Group</a>
                <a class="dropdown-item" href="/php/province/province.php" target="ifbox">Province</a>
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
  <section id="hero" class="d-flex align-items-center">
    <div class="container">
      <h1>Welcome to C19PVS</h1>
      <h2>COVID-19 Public Health Care Population Vaccination System</h2>
      <a href="index.php" class="btn-get-started scrollto">Get Started</a>
    </div>
  </section><!-- End Hero -->

  <!-- <?php include "./html/footer.html" ?> -->
</body>

</html>