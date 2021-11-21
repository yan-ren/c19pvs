<?php
// Check existence of id parameter before processing further
if (isset($_GET["person_id"]) && !empty(trim($_GET["person_id"]))) {
    // Include config file
    require_once "../config.php";
    $link = connect();

    // Prepare a select statement
    $sql = "SELECT * FROM person WHERE person_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);

        // Set parameters
        $param_id = trim($_GET["person_id"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $first_name = $row["first_name"];
                $middle_name = $row['middle_name'];
                $last_name = $row['last_name'];
                $date_of_birth = $row['date_of_birth'];
                $medicare_card_number = $row['medicare_card_number'];
                $date_of_issue_of_medicare_card = $row['date_of_issue_of_medicare_card'];
                $date_of_expiry_of_the_medicare_card = $row['date_of_expiry_of_the_medicare_card'];
                $phone = $row['phone'];
                $address = $row['address'];
                $city = $row['city'];
                $province = $row['province'];
                $postal_code = $row['postal_code'];
                $citizenship = $row['citizenship'];
                $email = $row['email'];
                $passport_number = $row['passport_number'];
                $age_group_id = $row['age_group_id'];
                $registered = $row['registered'];
                $status = $row['status'];
//                $vaccination_date = $row["vaccination_date"];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Record</title>
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
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <div class="form-group">
                        <label>Person ID</label>
                        <p><b><?php echo $row["person_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>First Name</label>
                        <p><b><?php echo $row["first_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Middle Name</label>
                        <p><b><?php echo $row["middle_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Last Name</label>
                        <p><b><?php echo $row["last_name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Date Of Birth</label>
                        <p><b><?php echo $row["date_of_birth"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Medicare Card Number</label>
                        <p><b><?php echo $row["medicare_card_number"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Date Of Issue Of Medicare Card</label>
                        <p><b><?php echo $row["date_of_issue_of_medicare_card"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Date Of Expiry Of The Medicare Card</label>
                        <p><b><?php echo $row["date_of_expiry_of_the_medicare_card"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <p><b><?php echo $row["phone"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Address</label>
                        <p><b><?php echo $row["address"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <p><b><?php echo $row["city"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Province</label>
                        <p><b><?php echo $row["province"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Postal Code</label>
                        <p><b><?php echo $row["postal_code"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Citizenship</label>
                        <p><b><?php echo $row["citizenship"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <p><b><?php echo $row["email"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Passport Number</label>
                        <p><b><?php echo $row["passport_number"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Age Group ID</label>
                        <p><b><?php echo $row["age_group_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Registered</label>
                        <p><b><?php echo $row["registered"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <p><b><?php echo $row["status"]; ?></b></p>
                    </div>
                    <p><a href="person.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>