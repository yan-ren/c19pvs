<?php
require_once "./php/config.php";
$link = connect();

// Fetch facility table
$sql = "SELECT * FROM facility";
$result = mysqli_query($link, $sql);
$all_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close connection
mysqli_close($link);
?>
<!DOCTYPE html>
<html>

<head>
  <?php include($_SERVER['DOCUMENT_ROOT'] . "/html/head.html") ?>
  <script>
    function iframeLoaded(iframeId) {
      var iframe = document.getElementById(iframeId);
      if (iframe) {
        // clear the current height, and set height based on content
        iframe.height = "";
        iframe.height = iframe.contentWindow.document.body.scrollHeight + "px";
      }
    }

    function clearIframe(iframeName) {
      var iframe = window.frames[iframeName];
      iframe.document.open();
      iframe.document.close();
    }

    function validateForm(formName) {
      var x = document.forms[formName];
      if (x["start_date"] && x["start_date"].value == "") {
        alert("Start Date cannot be empty");
        return false;
      }
      if (x["end_date"] && x["end_date"].value == "") {
        alert("End Date cannot be empty");
        return false;
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
        </ul>
      </div>
    </div>
  </nav><!-- Fixed navbar end-->

  <div class="container">
    <!-- display booking for facility -->
    <table class="table table-bordered table-striped">
      <thead data-toggle="collapse" href="#collapseTableFacility">
        <tr>
          <th>Display Booking And Total Available Spots For Facility <i class="fa fa-chevron-down"></i></th>
        </tr>
      </thead>
      <tbody class="collapse" id="collapseTableFacility">
        <tr>
          <td>
            <form name="display_facility" action="./php/booking/read_facility.php" target="ifFacilityBooking" onsubmit="return validateForm(this.name)" method="get">
              <div class="form-group col-sm-6">
                <label>Facility</label>
                <select class="form-select browser-default custom-select" name="facility_name">
                  <?php
                  foreach ($all_facility as $facility) {
                    echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="form-group col-sm-6">
                <label>Start Date</label>
                <input type="date" class="form-control" placeholder="" name="start_date">
              </div>
              <div class="form-group col-sm-6">
                <label>End Date</label>
                <input type="date" class="form-control" placeholder="" name="end_date">
              </div>
              <button type="submit" class="btn aqua-gradient">Search</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
    <a href='#' onclick='return clearIframe("ifFacilityBooking")'>clear</a>
    <!-- iframe ifFacilityBooking-->
    <div class="">
      <iframe name="ifFacilityBooking" id="ifFacilityBookingId" width="100%" onload="iframeLoaded(this.getAttribute('id'))" background-color:#b0c4de; frameborder="0" scrolling="auto">
      </iframe>
    </div>
    <hr>
    <!-- display the first available spot -->
    <table class="table table-bordered table-striped">
      <thead data-toggle="collapse" href="#collapseTableFirstSpot">
        <tr>
          <th>Quick Search For Facility First Available Spot <i class="fa fa-chevron-down"></i></th>
        </tr>
      </thead>
      <tbody class="collapse" id="collapseTableFirstSpot">
        <tr>
          <td>
            <form name="first_spot" action="./php/booking/read_first_spot.php" target="ifFirstSpot" onsubmit="return validateForm(this.name)" method="get">
              <div class="form-group">
                <label>Facility</label>
                <select class="form-select browser-default custom-select" name="facility_name">
                  <?php
                  foreach ($all_facility as $facility) {
                    echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label>Start Date</label>
                <input type="date" class="form-control" placeholder="" name="start_date">
              </div>
              <button type="submit" class="btn aqua-gradient">Search</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
    <a href='#' onclick='return clearIframe("ifFirstSpot")'>clear</a>
    <!-- iframe ifFirstSpot-->
    <div class="">
      <iframe name="ifFirstSpot" id="ifFirstSpotId" width="100%" onload="iframeLoaded(this.getAttribute('id'))" background-color:#b0c4de; frameborder="0" scrolling="auto">
      </iframe>
    </div>
    <hr>

    <!-- Booking Management iframe-->
    <div class="container">
      <div class="row">
        <div class="col-sm">
          <h2>Booking Management</h2>
        </div>
        <div class="col-sm">
          <a href="/php/booking/check_avaliability.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Create Booking</a>
        </div>
      </div>
      <iframe src="/php/booking/booking.php" name="ifBookingManagement" id="ifBookingManagementId" width="100%" onload="iframeLoaded(this.getAttribute('id'))" background-color:#b0c4de; frameborder="0" scrolling="auto">
      </iframe>
    </div>
  </div>
</body>

</html>