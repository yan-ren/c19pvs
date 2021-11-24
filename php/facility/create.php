<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$name = $address = $phone = $website = "";
$type = $capacity = $manager = $province = $category = "";

$name_err = $address_err = $phone_err = $website_err = $capacity_err = $manager_err = $province_err = "";

$sql = "SELECT * FROM province";
$result = mysqli_query($link, $sql);
$all_province = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

  $name = trim($_POST["name"]);
  $address = trim($_POST["address"]);
  $phone = trim($_POST["phone"]);
  $website = trim($_POST["website"]);
  $type = trim($_POST["type"]);
  $capacity = (int)trim($_POST["capacity"]);
  $manager = trim($_POST["manager"]);
  $city = trim($_POST["city"]);
  $province = trim($_POST["province"]);
  $category = trim($_POST["category"]);

  // Validate
  if (empty($name) && $name !== 'NULL') {
    $name_err = "Please enter a facility name";
  }
  if (empty($address) && $address !== 'NULL') {
    $address_err = "Please enter an address";
  }
  if (empty($phone) && $phone !== 'NULL') {
    $phone_err = "Please enter phone number";
  }
  if (empty($website) && $website !== 'NULL') {
    $website_err = "Please enter a web address";
  }
  if (empty($type) && $type !== 'NULL') {
    $website_err = "Please enter a web address";
  }
  if (empty($capacity) && $capacity !== 0) {
    $capacity_err = "Please enter a capacity";
  }
  if (empty($manager)) {
    // $manager_err = "Please enter a manager ID";
    $manager = null;
  }
  if (empty($province) && $province !== '0') {
    $province_err = "Please enter a province";
  }
  if (empty($city) && $province !== '0') {
        $city_err = "Please enter a city";
    }

  // Check input errors before inserting in database
  if (
    empty($name_err) && empty($address_err) && empty($phone_err) && empty($website_err) && empty($capacity_err)
    && empty($manager_err) && empty($province_err) && empty($city_err)
  ) {
    // Prepare an insert statement
    $sql = "INSERT INTO facility (name, address, phone, website,type,capacity,manager,city, province,category) VALUES (?, ?, ?, ?,?,?,?,?,?,?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param(
        $stmt,
        "sssssiisss",
        $param_name,
        $param_address,
        $param_phone,
        $param_website,
        $param_type,
        $param_capacity,
        $param_manager,
        $param_city,
        $param_province,
        $param_category
      );

      $param_name = ($name);
      $param_address = ($address);
      $param_phone = ($phone);
      $param_website = ($website);
      $param_type = ($type);
      $param_capacity = ($capacity);
      $param_manager = ($manager);
      $param_city = ($city);
      $param_province = ($province);
      $param_category = ($category);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records created successfully. Redirect to landing page
        header("location: facility.php");
        exit();
      } else {
        $error = mysqli_stmt_error($stmt);
        echo '<script> alert("' . $error . '")</script>';
      }
    } else {
      echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
    }

    // Close statement
    mysqli_stmt_close($stmt);
  }

  // Close connection
  mysqli_close($link);
}
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
</head>

<body>
  <div class="wrapper">
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-12">
          <h2 class="mt-5">Create Facility Record</h2>
          <p>Please fill this form and submit to add a facility record to the database</p>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
              <label>Facility Name</label>
              <input type="string" name="name" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : '' ?>" value="<?php echo $name; ?>">
              <span class="invalid-feedback"><?php echo $name_err; ?></span>
            </div>
            <div class="form-group">
              <label>Address</label>
              <input type="string" name="address" class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : '' ?>" value="<?php echo $address; ?>">
              <span class="invalid-feedback"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group">
              <label>Phone Number</label>
              <input type="string" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
              <span class="invalid-feedback"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
              <label>Website</label>
              <input type="string" name="website" class="form-control <?php echo (!empty($website_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $website; ?>">
              <span class="invalid-feedback"><?php echo $website_err; ?></span>
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
              <input type="number" name="capacity" class="form-control <?php echo (!empty($capacity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $capacity; ?>">
              <span class="invalid-feedback"><?php echo $capacity_err; ?></span>
            </div>

            <div class="form-group">
              <label>Manager</label>
              <input type="number" name="manager" class="form-control <?php echo (!empty($manager_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $manager; ?>">
              <span class="invalid-feedback"><?php echo $manager_err; ?></span>
            </div>
              <div class="form-group">
                  <label>City</label>
                  <input type="string" name="city" class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $city; ?>">
                  <span class="invalid-feedback"><?php echo $city_err; ?></span>
              </div> 
            <div class="form-group">
              <label>Province</label>
              <select class="custom-select" id="inputGroupSelect01" name="province">
                <?php
                foreach ($all_province as $province) {
                  echo '<option values=\"' . $province['name'] . '\">' . $province['name'] . '</option>';
                }
                ?>
              </select>
            </div>
            <div class="form-group">
              <label>Category</label>
              <select select class="custom-select" name="category" id="inputGroupSelect01">
                <option value="by_appointment">By Appointment</option>
                <option value="walk_in">Walk In</option>
              </select>
            </div>
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="facility.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>