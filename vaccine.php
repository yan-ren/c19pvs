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
        <li class="nav-item active">
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
  <!-- container -->
  <div class="container">
    <table class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>With Appointment</th>
          <th>Without Appointment</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>
            <form id="with_appointment" action="/php/vaccine/with_appointment.php" method="get">
              <div class="form-group">
                <label>First Name</label>
                <input class="form-control" placeholder="First Name" name="first_name">
              </div>
              <div class="form-group">
                <label>Middle Name</label>
                <input class="form-control" placeholder="Middle Name" name="middle_name">
              </div>
              <div class="form-group">
                <label>Last Name</label>
                <input class="form-control" placeholder="Last Name" name="last_name">
              </div>
              <div class="form-group">
                <label>Appointment Location</label>
                <input class="form-control" placeholder="Appointment Location" name="facility_name">
              </div>
              <button type="submit" class="btn aqua-gradient">Check</button>
            </form>
          </td>
          <td>
            <form id="without_appointment" action="/php/vaccine/without_appointment.php" method="get">
              <div class="form-group">
                <label>First Name</label>
                <input class="form-control" placeholder="First Name" name="first_name">
              </div>
              <div class="form-group">
                <label>Middle Name</label>
                <input class="form-control" placeholder="Middle Name" name="middle_name">
              </div>
              <div class="form-group">
                <label>Last Name</label>
                <input class="form-control" placeholder="Last Name" name="last_name">
              </div>
              <div class="form-group">
                <label>Preferred Location</label>
                <input class="form-control" placeholder="Preferred Location" name="preferred_location">
              </div>
              <div class="form-group">
                <label>Date</label>
                <input type="date" class="form-control" placeholder="Preferred Location" name="date">
              </div>
              <button type="submit" class="btn aqua-gradient">Check</button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <?php include "./html/footer.html" ?>
</body>

</html>