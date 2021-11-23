<?php
require_once "./php/config.php";
$link = connect();

// Fetch facility table
$sql = "SELECT * FROM facility WHERE category='walk_in'";
$result = mysqli_query($link, $sql);
$walkin_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

  <!-- container -->
  <div class="container">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th class="col-md-6">With Booking</th>
          <th class="col-md-6">Without Booking</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <form id="with_appointment" action="/php/vaccine/with_appointment.php" method="get">
              <div class="form-group">
                <label>First Name</label>
                <input class="form-control" placeholder="" name="first_name">
              </div>
              <div class="form-group">
                <label>Middle Name</label>
                <input class="form-control" placeholder="" name="middle_name">
              </div>
              <div class="form-group">
                <label>Last Name</label>
                <input class="form-control" placeholder="" name="last_name">
              </div>
              <div class="form-group">
                <label>Booking Location</label>
                <select class="form-select browser-default custom-select" name="facility_name">
                  <?php
                  foreach ($all_facility as $facility) {
                    echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                  }
                  ?>
                </select>
              </div>
              <button type="submit" class="btn aqua-gradient">Check</button>
            </form>
          </td>
          <td>
            <form id="without_appointment" action="/php/vaccine/without_appointment.php?vaccine=1" method="get">
              <div class="form-group">
                <label>First Name</label>
                <input class="form-control" placeholder="" name="first_name">
              </div>
              <div class="form-group">
                <label>Middle Name</label>
                <input class="form-control" placeholder="" name="middle_name">
              </div>
              <div class="form-group">
                <label>Last Name</label>
                <input class="form-control" placeholder="" name="last_name">
              </div>
              <div class="form-group">
                <label>Preferred Location. Note: only avaialbe for walk-in facility</label>
                <select class="form-select browser-default custom-select" name="preferred_location">
                  <?php
                  foreach ($walkin_facility as $facility) {
                    echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                  }
                  ?>
                </select>
              </div>
              <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" placeholder="" name="date">
              </div>
              <button type="submit" class="btn aqua-gradient">Check</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</body>

</html>