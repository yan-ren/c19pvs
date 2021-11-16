<?php
// Include config file
require_once "../config.php";
$link = connect();

// Define variables and initialize with empty values
$first_name = $middle_name = $last_name = $date_of_birth = $medicare_card_number = $date_of_issue_of_medicare_card =
$date_of_expiry_of_the_medicare_card = $phone = $address = $city = $province = $postal_code = $citizenship = $email =
$passport_number = $age_group_id = $registered = $status = "";

//Define error variables and initialize with empty values
$first_name_err = $last_name_err = $date_of_birth_err = $phone_err = $address_err = $city_err = $province_err =
$postal_code_err = $citizenship_err = $email_err = $passport_number_err = $status_err =
$date_of_issue_of_medicare_card_err = $date_of_expiry_of_the_medicare_card_err = $age_group_id_err = $registered = "";

// Processing form data when form is submitted
if (isset($_POST["person_id"]) && !empty($_POST["person_id"])) {
    // Get hidden input value
    $person_id = $_POST["person_id"];

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

    // Prepare an update statement
    $sql = "UPDATE person SET first_name = ?, middle_name = ?, last_name = ?, date_of_birth = ?, medicare_card_number = ?, 
                      date_of_issue_of_medicare_card = ?, date_of_expiry_of_the_medicare_card = ?, phone = ?, address = ?, 
                      city = ?, province = ?, postal_code = ?, citizenship = ?, email = ?, passport_number = ?, age_group_id = ?, 
                      registered = ?, status =? WHERE person_id = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssssssssssiisi", $input_first_name,
        $input_middle_name, $input_last_name, $input_date_of_birth, $input_medicare_card_number,
        $input_date_of_issue_of_medicare_card, $input_date_of_expiry_of_the_medicare_card, $input_phone, $input_address,
        $input_city, $input_province, $input_postal_code, $input_citizenship, $input_email, $input_passport_number,
        $input_age_group_id, $input_registered, $input_status, $person_id);

    if ($stmt) {
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records updated successfully. Redirect to landing page
            header("location: person.php");
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
    if (isset($_GET["person_id"]) && !empty(trim($_GET["person_id"]))) {
        // Get URL parameter
        $person_id = trim($_GET["person_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM person WHERE person_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $person_id;

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
                    $status = $row["status"];
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
                <p>Please edit the input values and submit to update the person record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="string" name="first_name"
                               class="form-control"
                               value="<?php echo $first_name; ?>">
                        <span class="invalid-feedback"><?php echo $first_name; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Middle Name</label>
                        <input type="string" name="middle_name"
                               class="form-control value="<?php echo $middle_name; ?>">
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="string" name="last_name"
                               class="form-control" value="<?php echo $last_name; ?>">
                        <span class="invalid-feedback"><?php echo $last_name; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Date Of Birth</label>
                        <input type="date" name="date_of_birth"
                               class="form-control" value="<?php echo $date_of_birth; ?>">
                        <span class="invalid-feedback"><?php echo $date_of_birth; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Medicare Card Number</label>
                        <input type="string" name="medicare_card_number"
                               class="form-control value="<?php echo $medicare_card_number; ?>">
                    </div>
                    <div class="form-group">
                        <label>Date Of Issue Of Medicare Card</label>
                        <input type="date" name="date_of_issue_of_medicare_card"
                               class="form-control" value="<?php echo $date_of_issue_of_medicare_card; ?>">
                        <span class="invalid-feedback"><?php echo $date_of_issue_of_medicare_card; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Date Of Expiry Of The Medicare Card</label>
                        <input type="date" name="date_of_expiry_of_the_medicare_card"
                               class="form-control" value="<?php echo $date_of_expiry_of_the_medicare_card; ?>">
                        <span class="invalid-feedback"><?php echo $date_of_expiry_of_the_medicare_card; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input type="string" name="phone"
                               class="form-control" value="<?php echo $phone; ?>">
                        <span class="invalid-feedback"><?php echo $phone; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <input type="string" name="address"
                               class="form-control"
                               value="<?php echo $address; ?>">
                        <span class="invalid-feedback"><?php echo $address; ?></span>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="string" name="city"
                               class="form-control" value="<?php echo $city; ?>">
                        <span class="invalid-feedback"><?php echo $city; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Province</label>
                        <input type="string" name="province"
                               class="form-control" value="<?php echo $province; ?>">
                        <span class="invalid-feedback"><?php echo $province; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Postal Code</label>
                        <input type="string" name="postal_code"
                               class="form-control" value="<?php echo $postal_code; ?>">
                        <span class="invalid-feedback"><?php echo $postal_code; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Citizenship</label>
                        <input type="string" name="citizenship"
                               class="form-control" value="<?php echo $citizenship; ?>">
                        <span class="invalid-feedback"><?php echo $citizenship; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="string" name="email"
                               class="form-control" value="<?php echo $email; ?>">
                        <span class="invalid-feedback"><?php echo $email; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Passport Number</label>
                        <input type="string" name="passport_number"
                               class="form-control" value="<?php echo $passport_number; ?>">
                        <span class="invalid-feedback"><?php echo $passport_number; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Age Group ID</label>
                        <input type="number" name="age_group_id"
                               class="form-control" value="<?php echo $age_group_id; ?>">
                        <span class="invalid-feedback"><?php echo $age_group_id; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Registered</label>
                        <input type="number" name="registered"
                               class="form-control" value="<?php echo $registered; ?>">
                        <span class="invalid-feedback"><?php echo $registered; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <input type="enum" name="status"
                               class="form-control"
                               value="<?php echo $status; ?>">
                        <span class="invalid-feedback"><?php echo $status; ?></span>
                    </div>
                    <input type="hidden" name="person_id" value="<?php echo $person_id; ?>"/>
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="person.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>