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
  </script>
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
        <li class="nav-item active">
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

  <div class="container">
    <!-- display booking for facility -->
    <table class="table table-bordered table-striped">
      <thead data-toggle="collapse" href="#collapseTableFacility">
        <tr>
          <th>Display Booking For Facility <i class="fa fa-chevron-down"></i></th>
        </tr>
      </thead>
      <tbody class="collapse" id="collapseTableFacility">
        <tr>
          <td>
            <form id="with_appointment" action="./php/booking/read_facility.php" target="ifFacilityBooking" method="get">
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
            <form id="with_appointment" action="./php/booking/read_first_spot.php" target="ifFirstSpot" method="get">
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
    <!-- -->
    <h2>Booking Management</h2>
    <!-- iframe ifFirstSpot-->
    <div class="">
      <iframe src="/php/booking/booking.php" name="ifBookingManagement" id="ifBookingManagementId" width="100%" onload="iframeLoaded(this.getAttribute('id'))" background-color:#b0c4de; frameborder="0" scrolling="auto">
      </iframe>
    </div>
  </div>
  <?php include "./html/footer.html" ?>
</body>

</html>