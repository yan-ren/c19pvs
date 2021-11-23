<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";
$link = connect();

// Define variables and initialize with empty values
$name = $address = $phone = $website = "";
$type = $capacity = $manager = $city=$province = $category = "";

$sql = "SELECT * FROM province";
$result = mysqli_query($link, $sql);
$all_province = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if (isset($_POST["name"]) && !empty($_POST["name"])) {
  // Get input values
  $new_name = trim($_POST["name"]);
  $new_address = trim($_POST["address"]);
  $new_phone = trim($_POST["phone"]);
  $new_website = trim($_POST["website"]);
  $new_type = trim($_POST["type"]);
  $new_capacity = (int)trim($_POST["capacity"]);
  $new_manager = (int)trim($_POST["manager"]);
  $new_city = trim($_POST["city"]);
  $new_province = trim($_POST["province"]);
  $new_category = trim($_POST["category"]);
  if ($new_manager == 0) {
    $new_manager = null;
  }

  // Prepare an update statement
  $sql = "UPDATE facility SET `address`= ?, phone = ?, website=?, `type`=?, capacity=?,  manager=?, city=?, province=?, category=? where `name`=?";
  $stmt = mysqli_prepare($link, $sql);
  mysqli_stmt_bind_param($stmt, "ssssiissss", $new_address, $new_phone, $new_website, $new_type, $new_capacity, $new_manager, $new_city, $new_province, $new_category, $new_name);

  if ($stmt) {
    // Attempt to execute the prepared statement
    if (mysqli_stmt_execute($stmt)) {
      // Records updated successfully. Redirect to landing page
      header("location: facility.php");
      exit();
    } else {
      echo "Oops! Something went wrong. Please try again later.";
    }
  }

  // Close statement
  mysqli_stmt_close($stmt);

  // Close connection
  mysqli_close($link);
} else {
  // Check existence of id parameter before processing further
  if (isset($_GET["name"]) && !empty(trim($_GET["name"]))) {
    // Get URL parameter
    $name = trim($_GET["name"]);
    // Prepare a select statement
    $sql = "SELECT * FROM facility WHERE name = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "s", $name);
      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

          // Retrieve individual field value
          $name = $row["name"];
          $address = $row["address"];
          $phone = $row["phone"];
          $website = $row["website"];
          $type = $row["type"];
          $capacity = $row["capacity"];
          $manager = $row["manager"];
            $city = $row["city"];
          $province_name = $row["province"];
          $category = $row["category"];
        } else {
          // URL doesn't contain valid id. Redirect to error page
          header("location: error.php");
          exit();
        }
      } else {
        echo "Oops! Something went wrong. Please try again later.";
      }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
  } else {
    // URL doesn't contain id parameter. Redirect to error page
    header("location: error.php");
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Update Record</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    .wrapper {
      width: 600px;
      margin: 0 auto;
    }
  </style>
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <h2 class="mt-5">Update Record</h2>
          <p>Please edit the input values and submit to update the Facility record.</p>
          <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group">
              <label>Facility Name</label>
              <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" readonly>
            </div>
            <div class="form-group">
              <label>Address</label>
              <input type="text" name="address" class="form-control " value="<?php echo $address; ?>">
            </div>
            <div class="form-group">
              <label>Phone Number</label>
              <input type="text" name="phone" class="form-control " value="<?php echo $phone; ?>">
            </div>
            <div class="form-group">
              <label>Website</label>
              <input type="text" name="website" class="form-control " value="<?php echo $website; ?>">
            </div>
            <div class="form-group">
              <label>Type</label>
              <select select class="custom-select" name="type" id="inputGroupSelect01">
                <option value="hospital">Hospital</option>
                <option value="clinic">Clinic</option>
                <option value="special_installment">Special Installment</option>
              </select>
            </div>
            <div class="form-group">
              <label>Daily Capacity</label>
              <input type="number" name="capacity" class="form-control " value="<?php echo $capacity; ?>">
            </div>
            <div class="form-group">
              <label>Manager</label>
              <input type="number" name="manager" class="form-control " value="<?php echo $manager; ?>">
            </div>
              <div class="form-group">
                  <label>City</label>
                  <input type="text" name="city" class="form-control " value="<?php echo $city; ?>">
              </div>
            <div class="form-group">
              <label>Province</label>
              <select class="custom-select" id="inputGroupSelect01" name="province">
                <?php
                foreach ($all_province as $province) {
                  if ($province['name'] == $province_name) {
                    echo '<option selected values=\"' . $province['name'] . '\">' . $province['name'] . '</option>';
                  } else {
                    echo '<option values=\"' . $province['name'] . '\">' . $province['name'] . '</option>';
                  }
                }
                ?>
              </select>
              <span class="invalid-feedback"><?php echo $province; ?></span>
            </div>
            <div class="form-group">
              <label>Category</label>
              <select select class="custom-select" name="category" id="inputGroupSelect01">
                <option value="by_appointment">By Appointment</option>
                <option value="walk_in">Walk In</option>
              </select>
            </div>
            <input type="hidden" name="name" value="<?php echo $name; ?>" />
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="facility.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>