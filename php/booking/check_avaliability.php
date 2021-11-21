<?php
// Include config file
require_once "../config.php";
$link = connect();

// Fetch facility table
$sql = "SELECT * FROM facility";
$result = mysqli_query($link, $sql);
$all_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Close connection
mysqli_close($link);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Create Record</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .wrapper {
      width: 600px;
      margin: 0 auto;
    }
  </style>
  <script>
    function validateForm(formName) {
      var x = document.forms[formName];
      if (x["first_name"] && x["first_name"].value == "") {
        alert("First name cannot be empty");
        return false;
      }
      if (x["last_name"] && x["last_name"].value == "") {
        alert("Last name cannot be empty");
        return false;
      }
      if (x["date"] && x["date"].value == "") {
        alert("Date cannot be empty");
        return false;
      }
    }
  </script>
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <h2 class="mt-5">Create New booking</h2>
          <p>Please fill this form and submit to add record to the database</p>
          <form name="avaliability" action="create.php?check=1" onsubmit="return validateForm(this.name)" method="post">
            <div class="form-group">
              <label>First Name</label>
              <input type="text" name="first_name" class="form-control">
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="text" name="last_name" class="form-control">
            </div>
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
              <label>Date</label>
              <input type="date" name="date" class="form-control">
            </div>
            <input type="submit" class="btn btn-primary" value="Check">
            <a href="../../booking.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>