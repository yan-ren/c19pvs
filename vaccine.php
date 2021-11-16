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
  <!-- container -->
  <div class="container">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>With Booking</th>
          <th>Without Booking</th>
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