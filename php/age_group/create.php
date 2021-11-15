<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$min_age = $max_age = $vaccination_date = $age_group_id = "";
$age_error = $vaccination_date_error = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $age_group_id = trim($_POST["age_group_id"]);
    $min_age = trim($_POST["min_age"]);
    $max_age = trim($_POST["max_age"]);
    $vaccination_date = trim($_POST["vaccination_date"]);

    // Validate age
    if (empty($min_age) && $min_age !== '0') {
        $age_err = "Please enter an min age";
    }
    if (empty($max_age) && $max_age !== '0') {
        $age_err = "Please enter an max age";
    }

    // Validate date
    if (empty($vaccination_date)) {
        $vaccination_date = null;
    } elseif (!validateMysqlDate($vaccination_date)) {
        $vaccination_date_error = "Invalid date format, please use formate yyyy-mm-dd";
    }

    // Check input errors before inserting in database
    if (empty($age_error) && empty($vaccination_date_error)) {
        // Prepare an insert statement
        $sql = "INSERT INTO age_group (age_group_id, min_age, max_age, vaccination_date) VALUES (?, ?, ?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iiis", $param_age_group_id, $param_min_age, $param_max_age, $vaccination_date);

            $param_age_group_id = (int)($age_group_id);
            $param_min_age = (int)($min_age);
            $param_max_age = (int)($max_age);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: age_group.php");
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
                    <h2 class="mt-5">Create Age Group Record</h2>
                    <p>Please fill this form and submit to add age group record to the database</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Age Group ID</label>
                            <input type="number" name="age_group_id" class="form-control" value="<?php echo $age_group_id; ?>">
                        </div>
                        <div class="form-group">
                            <label>Min Age</label>
                            <input type="number" name="min_age" class="form-control <?php echo (!empty($age_error)) ? 'is-invalid' : '' ?>" value="<?php echo $min_age; ?>">
                            <span class="invalid-feedback"><?php echo $age_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Max Age</label>
                            <input type="number" name="max_age" class="form-control <?php echo (!empty($age_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $max_age; ?>">
                            <span class="invalid-feedback"><?php echo $age_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Vaccination Date</label>
                            <input type="date" name="vaccination_date" class="form-control <?php echo (!empty($vaccination_date_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $vaccination_date; ?>">
                            <span class="invalid-feedback"><?php echo $vaccination_date_error; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="age_group.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>