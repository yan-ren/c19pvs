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
      if (x["date"] && x["date"].value == "") {
        alert("Date cannot be empty");
        return false;
      }
    }
  </script>
</head>

<body>
  <!-- Fixed navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container">
      <a class="navbar-brand" href="index.php">C19PVS</a>
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
        </ul>
      </div>
    </div>
  </nav><!-- Fixed navbar end-->

  <div class="container">
    <!-- display nurses for facility -->
    <table class="table table-bordered table-striped">
      <thead data-toggle="collapse" href="#collapseTableFacilityDateNurse">
        <tr>
          <th>For a given facility and on a given date, display the Nurses that work for the facility but are not assigned to the facility on the specified date. <i class="fa fa-chevron-down"></i></th>
        </tr>
      </thead>
      <tbody class="collapse" id="collapseTableFacilityDateNurse">
        <tr>
          <td>
            <form name="display_facility_date_nurse" action="./php/search/facility_date_nurse.php" target="ifFacilityDateNurse" onsubmit="return validateForm(this.name)" method="get">
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
                <label>Date</label>
                <input type="date" class="form-control" placeholder="" name="date">
              </div>
              <button type="submit" class="btn aqua-gradient">Search</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
    <a href='#' onclick='return clearIframe("ifFacilityDateNurse")'>clear</a>
    <!-- iframe ifFacilityDateNurse-->
    <div class="">
      <iframe name="ifFacilityDateNurse" id="ifFacilityDateNurseId" width="100%" onload="iframeLoaded(this.getAttribute('id'))" frameborder="0" scrolling="auto">
      </iframe>
    </div>
    <hr>

    <!-- display facility has no nurse-->
    <table class="table table-bordered table-striped">
      <thead data-toggle="collapse" href="#collapseTableDateFacilityNoNurse">
        <tr>
          <th>For a given date, display all the facilities that do not have any nurse scheduled to work at the facility. <i class="fa fa-chevron-down"></i></th>
        </tr>
      </thead>
      <tbody class="collapse" id="collapseTableDateFacilityNoNurse">
        <tr>
          <td>
            <form name="display_date_facility_no_nurse" action="./php/search/date_facility_no_nurse.php" target="ifDateFacilityNoNurse" onsubmit="return validateForm(this.name)" method="get">
              <div class="form-group col-sm-6">
                <label>Date</label>
                <input type="date" class="form-control" placeholder="" name="date">
              </div>
              <button type="submit" class="btn aqua-gradient">Search</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
    <a href='#' onclick='return clearIframe("ifDateFacilityNoNurse")'>clear</a>
    <!-- iframe ifDateFacilityNoNurse-->
    <div class="">
      <iframe name="ifDateFacilityNoNurse" id="ifDateFacilityNoNurseId" width="100%" onload="iframeLoaded(this.getAttribute('id'))" frameborder="0" scrolling="auto">
      </iframe>
    </div>
  </div>
</body>

</html>