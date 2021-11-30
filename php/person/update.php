<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$first_name = $middle_name = $last_name = $date_of_birth = $medicare_card_number = $date_of_issue_of_medicare_card =
  $date_of_expiry_of_the_medicare_card = $phone = $address = $city = $province = $postal_code = $citizenship = $email =
  $passport_number = $age_group_id = $registered = $status = "";

// Define error variables and initialize with empty values
$first_name_err = $last_name_err = $date_of_birth_err = $phone_err = $address_err = $city_err = $province_err =
  $postal_code_err = $citizenship_err = $email_err = $passport_number_err = $status_err =
  $date_of_issue_of_medicare_card_err = $date_of_expiry_of_the_medicare_card_err = $age_group_id_err = $registered_err = "";

// Get province values
$sql = "SELECT * FROM province;";
$result = mysqli_query($link, $sql);
$all_province = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Get age group values
$sql = "SELECT age_group_id FROM age_group";
$result = mysqli_query($link, $sql);
$age_group_ids = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if (isset($_POST["person_id"]) && !empty($_POST["person_id"])) {
  // Get hidden input value
  $person_id = (int)(trim($_POST["person_id"]));

  // Get input values
  $input_first_name = trim($_POST["first_name"]);
  $input_middle_name = trim($_POST["middle_name"]);
  $input_last_name = trim($_POST["last_name"]);
  $input_date_of_birth = trim($_POST["date_of_birth"]);
  $input_medicare_card_number = trim($_POST["medicare_card_number"]);
  $input_date_of_issue_of_medicare_card = trim($_POST["date_of_issue_of_medicare_card"]);
  $input_date_of_expiry_of_the_medicare_card = trim($_POST["date_of_expiry_of_the_medicare_card"]);
  $input_phone = trim($_POST["phone"]);
  $input_address = trim($_POST["address"]);
  $input_city = trim($_POST["city"]);
  $input_province = trim($_POST["province"]);
  $input_postal_code = trim($_POST["postal_code"]);
  $input_citizenship = trim($_POST["citizenship"]);
  $input_email = trim($_POST["email"]);
  $input_passport_number = trim($_POST["passport_number"]);
  $input_age_group_id = (int)(trim($_POST["age_group_id"]));
  $input_registered = (int)(trim($_POST["registered"]));
  $input_status = trim($_POST["status"]);

  // Validate person info
  if (empty($input_first_name)) {
    $first_name_err = "Please enter the first name";
  }
  if (empty($input_middle_name)) {
    $input_middle_name = null;
  }
  if (empty($input_last_name)) {
    $last_name_err = "Please enter the last name";
  }
  //Validate date of birth
  if (empty($input_date_of_birth)) {
    $date_of_birth_err = "Please enter the birthday";
  } else if (!validateMysqlDate($input_date_of_birth)) {
    $date_of_birth_err = "Invalid date format, please use format yyyy-mm-dd";
  }
  if (empty($input_phone)) {
    $phone_err = "Please enter the phone number";
  }
  if (empty($input_address)) {
    $address_err = "Please enter the address";
  }
  if (empty($input_city)) {
    $city_err = "Please enter the city";
  }
  if (empty($input_province)) {
    $province_err = "Please enter the province";
  }
  if (empty($input_postal_code)) {
    $postal_code_err = "Please enter the postal code";
  }
  if (empty($input_citizenship)) {
    $citizenship_err = "Please enter the citizenship";
  }
  if (empty($input_email)) {
    $email_err = "Please enter the email";
  }
  if (empty($input_passport_number)) {
    $passport_number_err = "Please enter the passport number";
  }

  if (empty($input_medicare_card_number)) {
    $input_medicare_card_number = null;
    $input_date_of_issue_of_medicare_card = null;
    $input_date_of_expiry_of_the_medicare_card = null;
  } else {
    if (!validateMysqlDate($input_date_of_issue_of_medicare_card)) {
      $date_of_issue_of_medicare_card_err = "Invalid date format, please use format yyyy-mm-dd";
    }
    if (!validateMysqlDate($input_date_of_expiry_of_the_medicare_card)) {
      $date_of_expiry_of_the_medicare_card_err = "Invalid date format, please use format yyyy-mm-dd";
    }
  }

  // Check input errors before inserting in database
  if (
    empty($first_name_err) && empty($last_name_err) && empty($date_of_birth_err) && empty($date_of_expiry_of_the_medicare_card_err) &&
    empty($date_of_issue_of_the_medicare_card_err) && empty($phone_err) && empty($address_err) && empty($city_err) &&
    empty($province_err) && empty($postal_code_err) && empty($citizenship_err) && empty($email_err) &&
    empty($passport_number_err)
  ) {
    $sql = "UPDATE person SET first_name = ?, middle_name = ?, last_name = ?, date_of_birth = ?, medicare_card_number = ?, 
          date_of_issue_of_medicare_card = ?, date_of_expiry_of_the_medicare_card = ?, phone = ?, address = ?, 
          city = ?, province = ?, postal_code = ?, citizenship = ?, email = ?, passport_number = ?, age_group_id = ?, 
          registered = ? WHERE person_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param(
      $stmt,
      "sssssssssssssssiii",
      $input_first_name,
      $input_middle_name,
      $input_last_name,
      $input_date_of_birth,
      $input_medicare_card_number,
      $input_date_of_issue_of_medicare_card,
      $input_date_of_expiry_of_the_medicare_card,
      $input_phone,
      $input_address,
      $input_city,
      $input_province,
      $input_postal_code,
      $input_citizenship,
      $input_email,
      $input_passport_number,
      $input_age_group_id,
      $input_registered,
      $person_id
    );

    if ($stmt) {
      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        // Records updated successfully. Redirect to landing page
        echo "<script>alert('Update Person Successful!');location='person.php';</script>";
        exit();
      } else {
        $error = mysqli_stmt_error($stmt);
        echo '<script> alert("' . $error . '")</script>';
      }
    } else {
      echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='update.php';</script>";
    }
    // Close statement
    mysqli_stmt_close($stmt);
  }
  // Close connection
  mysqli_close($link);
} else {
  // Check existence of id parameter before processing further
  if (isset($_GET["person_id"]) && !empty(trim($_GET["person_id"]))) {
    // Get URL parameter
    $person_id = trim($_GET["person_id"]);

    // Prepare a select statement
    $sql = "SELECT * FROM person WHERE person_id = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
      // Bind variables to the prepared statement as parameters
      mysqli_stmt_bind_param($stmt, "i", $person_id);

      // Attempt to execute the prepared statement
      if (mysqli_stmt_execute($stmt)) {
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 1) {
          /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
          $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

          // Retrieve individual field value
          $first_name = $row["first_name"];
          $middle_name = $row["middle_name"];
          $last_name = $row["last_name"];
          $date_of_birth = $row["date_of_birth"];
          $medicare_card_number = $row["medicare_card_number"];
          $date_of_issue_of_medicare_card = $row["date_of_issue_of_medicare_card"];
          $date_of_expiry_of_the_medicare_card = $row["date_of_expiry_of_the_medicare_card"];
          $phone = $row["phone"];
          $address = $row["address"];
          $city = $row["city"];
          $province = $row["province"];
          $postal_code = $row["postal_code"];
          $citizenship = $row["citizenship"];
          $email = $row["email"];
          $passport_number = $row["passport_number"];
          $age_group_id = $row["age_group_id"];
          $registered = $row["registered"];
        } else {
          // URL doesn't contain valid id. Redirect to error page
          header("location: error.php");
          exit();
        }
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
          <p>Please edit the input values and submit to update the person record.</p>
          <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
            <div class="form-group">
              <label>Person ID</label>
              <input type="read" name="person_id" class="form-control" value="<?php echo $person_id; ?>" readonly>
            </div>
            <div class="form-group">
              <label>First Name</label>
              <input type="string" name="first_name" class="form-control" value="<?php echo $first_name; ?>">
              <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
            </div>
            <div class="form-group">
              <label>Middle Name</label>
              <input type="string" name="middle_name" class="form-control value=" <?php echo $middle_name; ?>">
            </div>
            <div class="form-group">
              <label>Last Name</label>
              <input type="string" name="last_name" class="form-control" value="<?php echo $last_name; ?>">
              <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
            </div>
            <div class="form-group">
              <label>Date Of Birth</label>
              <input type="date" name="date_of_birth" class="form-control" value="<?php echo $date_of_birth; ?>">
              <span class="invalid-feedback"><?php echo $date_of_birth_err; ?></span>
            </div>
            <div class="form-group">
              <label>Medicare Card Number</label>
              <input type="string" name="medicare_card_number" class="form-control" value="<?php echo $medicare_card_number; ?>">
            </div>
            <div class="form-group">
              <label>Date Of Issue Of Medicare Card</label>
              <input type="date" name="date_of_issue_of_medicare_card" class="form-control" value="<?php echo $date_of_issue_of_medicare_card; ?>">
              <span class="invalid-feedback"><?php echo $date_of_issue_of_medicare_card_err; ?></span>
            </div>
            <div class="form-group">
              <label>Date Of Expiry Of The Medicare Card</label>
              <input type="date" name="date_of_expiry_of_the_medicare_card" class="form-control" value="<?php echo $date_of_expiry_of_the_medicare_card; ?>">
              <span class="invalid-feedback"><?php echo $date_of_expiry_of_the_medicare_card_err; ?></span>
            </div>
            <div class="form-group">
              <label>Phone</label>
              <input type="string" name="phone" class="form-control" value="<?php echo $phone; ?>">
              <span class="invalid-feedback"><?php echo $phone_err; ?></span>
            </div>
            <div class="form-group">
              <label>Address</label>
              <input type="string" name="address" class="form-control" value="<?php echo $address; ?>">
              <span class="invalid-feedback"><?php echo $address_err; ?></span>
            </div>
            <div class="form-group">
              <label>City</label>
              <input type="string" name="city" class="form-control" value="<?php echo $city; ?>">
              <span class="invalid-feedback"><?php echo $city_err; ?></span>
            </div>
            <div class="form-group">
              <label>Province</label>
              <select class="custom-select" id="inputGroupSelect01" name="province">
                <?php
                foreach ($all_province as $provinces) {
                  if ($provinces['name'] == $province) {
                    echo '<option selected values=\"' . $provinces['name'] . '\">' . $provinces['name'] . '</option>';
                  } else {
                    echo '<option values=\"' . $provinces['name'] . '\">' . $provinces['name'] . '</option>';
                  }
                }
                ?>
              </select>
              <spance class="invalid-feedback"><?php echo $province; ?></spance>
            </div>
            <div class="form-group">
              <label>Postal Code</label>
              <input type="string" name="postal_code" class="form-control" value="<?php echo $postal_code; ?>">
              <span class="invalid-feedback"><?php echo $postal_code_err; ?></span>
            </div>
            <div class="form-group">
              <label>Citizenship</label>
              <input type="string" name="citizenship" class="form-control" value="<?php echo $citizenship; ?>">
              <span class="invalid-feedback"><?php echo $citizenship_err; ?></span>
            </div>
            <div class="form-group">
              <label>Email</label>
              <input type="string" name="email" class="form-control" value="<?php echo $email; ?>">
              <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
              <label>Passport Number</label>
              <input type="string" name="passport_number" class="form-control" value="<?php echo $passport_number; ?>">
              <span class="invalid-feedback"><?php echo $passport_number_err; ?></span>
            </div>
            <div class="form-group">
              <label>Age Group ID</label>
              <input type="number" name="age_group_id" class="form-control" value="<?php echo $age_group_id; ?>">
              <span class="invalid-feedback"><?php echo $age_group_id_err; ?></span>
            </div>
            <div class="form-group">
              <label>Registered</label>
              <input type="number" name="registered" class="form-control" value="<?php echo $registered; ?>">
              <span class="invalid-feedback"><?php echo $registered_err; ?></span>
            </div>
            <input type="hidden" name="person_id" value="<?php echo $person_id; ?>" />
            <input type="submit" class="btn btn-primary" value="Submit">
            <a href="person.php" class="btn btn-secondary ml-2">Cancel</a>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>

</html>