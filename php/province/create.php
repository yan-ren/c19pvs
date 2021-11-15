<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$name = $age_group = "";
$prov_err = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $age_group = trim($_POST["age_group"]);


    // Validate province info
    if (empty($name) ) {
        $prov_err = "Please enter a province";
    }
    if (empty($age_group) && $age_group !== '0') {
        $prov_err = "Please enter an age group";
    }

    // Validate date
//    if (empty($vaccination_date)) {
//        $vaccination_date = null;
//    } elseif (!validateMysqlDate($vaccination_date)) {
//        $vaccination_date_error = "Invalid date format, please use formate yyyy-mm-dd";
//    }

    // Check input errors before inserting in database
    if (empty($prov_err) ) {
        // Prepare an insert statement
        $sql = "INSERT INTO province (name , age_group) VALUES (?, ?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $param_name, $param_age_group);

            $param_name = ($name);
            $param_age_group = (int)($age_group);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: province.php");
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
                <h2 class="mt-5">Create province Record</h2>
                <p>Please fill this form and submit to add Province record to the database</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group">
                        <label>Province</label>
                        <input type="string" name="name" class="form-control <?php echo (!empty($prov_err)) ? 'is-invalid' : '' ?>" value="<?php echo $name; ?>">
                        <span class="invalid-feedback"><?php echo $prov_err; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Age Group</label>
                        <input type="number" name="age_group" class="form-control <?php echo (!empty($prov_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $age_group; ?>">
                        <span class="invalid-feedback"><?php echo $prov_err; ?></span>
                    </div>

                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="province.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>