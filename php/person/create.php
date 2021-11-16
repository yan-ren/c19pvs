<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$person_id = $first_name = $middle_name = $last_name = $date_of_birth = $medicare_card_number = $date_of_issue_of_medicare_card =
$date_of_expiry_of_the_medicare_card = $phone = $address = $city = $province = $postal_code = $citizenship = $email =
$passport_number = $age_group_id = $registered = $status = "";

// Define error variables and initialize with empty values
$first_name_err = $last_name_err = $date_of_birth_err = $phone_err = $address_err = $city_err = $province_err =
$postal_code_err = $citizenship_err = $email_err = $passport_number_err = $status_err =
$date_of_issue_of_medicare_card_err = $date_of_expiry_of_the_medicare_card_err = $age_group_id_err = $registered_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = trim($_POST["person_id"]);
    $first_name = trim($_POST["first_name"]);
    $middle_name = trim($_POST["middle_name"]);
    $last_name = trim($_POST["last_name"]);
    $date_of_birth = trim($_POST["date_of_birth"]);
    $medicare_card_number = trim($_POST["medicare_card_number"]);
    $date_of_issue_of_medicare_card = trim($_POST["date_of_issue_of_medicare_card"]);
    $date_of_expiry_of_the_medicare_card = trim($_POST["date_of_expiry_of_the_medicare_card"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $city = trim($_POST["city"]);
    $province = trim($_POST["province"]);
    $postal_code = trim($_POST["postal_code"]);
    $citizenship = trim($_POST["citizenship"]);
    $email = trim($_POST["email"]);
    $passport_number = trim($_POST["passport_number"]);
    $age_group_id = trim($_POST["age_group_id"]);
    $registered = trim($_POST["registered"]);
    $status = trim($_POST["status"]);

    // Validate person info
    if (empty($first_name)) {
        $first_name_err = "Please enter the first name";
    }
    if (empty($last_name)) {
        $last_name_err = "Please enter the last name";
    }
    if (empty($date_of_birth)) {
        $date_of_birth_err = "Please enter the birthday";
    }
    if (empty($phone)) {
        $phone_err = "Please enter the phone number";
    }
    if (empty($address)) {
        $address_err = "Please enter the address";
    }
    if (empty($city)) {
        $city_err = "Please enter the city";
    }
    if (empty($province)) {
        $province_err = "Please enter the province";
    }
    if (empty($postal_code)) {
        $postal_code_err = "Please enter the postal code";
    }
    if (empty($citizenship)) {
        $citizenship_err = "Please enter the citizenship";
    }
    if (empty($email)) {
        $email_err = "Please enter the email";
    }
    if (empty($passport_number)) {
        $passport_number_err = "Please enter the passport number";
    }
    if (empty($status)) {
        $status_err = "Please enter the status";
    }

    //Validate date
    if (!validateMysqlDate($date_of_birth)) {
        $date_of_birth_err = "Invalid date format, please use format yyyy-mm-dd";
    }

    if (!validateMysqlDate($date_of_issue_of_medicare_card)) {
        $date_of_issue_of_medicare_card_err = "Invalid date format, please use format yyyy-mm-dd";
    }

    if (!validateMysqlDate($date_of_expiry_of_the_medicare_card)) {
        $date_of_expiry_of_the_medicare_card_err = "Invalid date format, please use format yyyy-mm-dd";
    }

    // Check input errors before inserting in database
    if (empty($first_name_err) && empty($last_name_err) && empty($date_of_birth_err) && empty($date_of_expiry_of_the_medicare_card_err) &&
        empty($date_of_issue_of_the_medicare_card_err) && empty($phone_err) && empty($address_err) && empty($city_err) &&
        empty($province_err) && empty($postal_code_err) && empty($citizenship_err) && empty($email_err) &&
        empty($passport_number_err) && empty($status_err)) {


        // Prepare an insert statement
        $sql = "INSERT INTO person (person_id, first_name, middle_name, last_name, date_of_birth, medicare_card_number, 
                      date_of_issue_of_medicare_card, date_of_expiry_of_the_medicare_card, phone, address, city, province, 
                      postal_code, citizenship, email, passport_number, age_group_id, registered, status) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isssssssssssssssiis", $param_person_id, $param_first_name,
                $param_middle_name, $param_last_name, $param_date_of_birth, $param_medicare_card_number,
                $param_date_of_issue_of_medicare_card, $param_date_of_expiry_of_the_medicare_card, $param_phone, $param_address,
                $param_city, $param_province, $param_postal_code, $param_citizenship, $param_email, $param_passport_number,
                $param_age_group_id, $param_registered, $param_status);

            $param_person_id = (int)($person_id);
            $param_first_name = ($first_name);
            $param_middle_name = ($middle_name);
            $param_last_name = ($last_name);
            $param_date_of_birth = ($date_of_birth);
            $param_medicare_card_number = ($medicare_card_number);
            $param_date_of_issue_of_medicare_card = ($date_of_issue_of_medicare_card);
            $param_date_of_expiry_of_the_medicare_card = ($date_of_expiry_of_the_medicare_card);
            $param_phone = ($phone);
            $param_address = ($address);
            $param_city = ($city);
            $param_province = ($province);
            $param_postal_code = ($postal_code);
            $param_citizenship = ($citizenship);
            $param_email = ($email);
            $param_passport_number = ($passport_number);
            $param_age_group_id = (int)($age_group_id);
            $param_registered = (int)($registered);
            $param_status = ($status);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: person.php");
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
                <h2 class="mt-5">Create Person Record</h2>
                <p>Please fill this form and submit to add person record to the database</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Person ID</label>
                        <input type="number" name="person_id"
                               class="form-control <?php echo $person_id; ?>"
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="string" name="first_name"
                               class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $first_name; ?>">
                        <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="string" name="middle_name"
                               class="form-control value="<?php echo $middle_name; ?>">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="string" name="last_name"
                               class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $last_name; ?>">
                        <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Date Of Birth</label>
                        <input type="date" name="date_of_birth"
                               class="form-control <?php echo (!empty($date_of_birth_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $date_of_birth; ?>">
                        <span class="invalid-feedback"><?php echo $date_of_birth_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Medicare Card Number</label>
                        <input type="string" name="medicare_card_number"
                               class="form-control value="<?php echo $medicare_card_number; ?>">
                    </div>
                    <div class="form-group">
                        <label>Date Of Issue Of Medicare Card</label>
                        <input type="date" name="date_of_issue_of_medicare_card"
                               class="form-control <?php echo (!empty($date_of_issue_of_medicare_card_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $date_of_issue_of_medicare_card; ?>">
                        <span class="invalid-feedback"><?php echo $date_of_issue_of_medicare_card_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Date Of Expiry Of The Medicare Card</label>
                        <input type="date" name="date_of_expiry_of_the_medicare_card"
                               class="form-control <?php echo (!empty($date_of_expiry_of_the_medicare_card_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $date_of_expiry_of_the_medicare_card; ?>">
                        <span class="invalid-feedback"><?php echo $date_of_expiry_of_the_medicare_card_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="string" name="phone"
                               class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $phone; ?>">
                        <span class="invalid-feedback"><?php echo $phone_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="string" name="address"
                               class="form-control <?php echo (!empty($address_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $address; ?>">
                        <span class="invalid-feedback"><?php echo $address_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="string" name="city"
                               class="form-control <?php echo (!empty($city_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $city; ?>">
                        <span class="invalid-feedback"><?php echo $city_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Province</label>
                        <input type="string" name="province"
                               class="form-control <?php echo (!empty($province_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $province; ?>">
                        <span class="invalid-feedback"><?php echo $province_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Postal Code</label>
                        <input type="string" name="postal_code"
                               class="form-control <?php echo (!empty($postal_code_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $postal_code; ?>">
                        <span class="invalid-feedback"><?php echo $postal_code_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Citizenship</label>
                        <input type="string" name="citizenship"
                               class="form-control <?php echo (!empty($citizenship_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $citizenship; ?>">
                        <span class="invalid-feedback"><?php echo $citizenship_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="string" name="email"
                               class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $email; ?>">
                        <span class="invalid-feedback"><?php echo $email_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Passport Number</label>
                        <input type="string" name="passport_number"
                               class="form-control <?php echo (!empty($passport_number_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $passport_number; ?>">
                        <span class="invalid-feedback"><?php echo $passport_number_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Age Group ID</label>
                        <input type="number" name="age_group_id"
                               class="form-control <?php echo (!empty($age_group_id_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $age_group_id; ?>">
                        <span class="invalid-feedback"><?php echo $age_group_id_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Registered</label>
                        <input type="number" name="registered"
                               class="form-control <?php echo (!empty($registered_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $registered; ?>">
                        <span class="invalid-feedback"><?php echo $registered_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="string" name="status"
                               class="form-control <?php echo (!empty($status_err)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $status; ?>">
                        <span class="invalid-feedback"><?php echo $status_err; ?></span>
                    </div>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="person.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>