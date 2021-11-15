<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$person_id = $employee_id = $facility_name = $hourly_rate = "";
$person_id_error = $employee_id_error = $facility_name_error = $hourly_rate_error = "";

$sql = "SELECT * FROM facility";
$result = mysqli_query($link, $sql);
$all_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = trim($_POST["person_id"]);
    $employee_id = trim($_POST["employee_id"]);
    $facility_name = trim($_POST["facility_name"]);
    $hourly_rate = trim($_POST["hourly_rate"]);

    // Validate person_id employee_id
    if (empty($person_id) && $person_id !== '0') {
        $person_id_error = "Please enter a valid person id";
    }
    if (empty($employee_id) && $employee_id !== '0') {
        $employee_id_error = "Please enter a valid employee id";
    }
    if (empty($facility_name) && $facility_name !== 'NULL') {
        $facility_name_error = "Please enter a valid facility_name";
    }
    if (empty($hourly_rate) && $hourly_rate !== '0') {
        $hourly_rate_error = "Please enter a valid hourly rate";
    }

    // Check input errors before inserting in database
    if (empty($person_id_error) && empty($employee_id_error) && empty($facility_name_error) && empty($hourly_rate_error)) {
        // Prepare an insert statement
        $sql = "INSERT INTO healthcare_worker (person_id, employee_id, facility_name, hourly_rate, status) VALUES (?, ?, ?, ?, 'A')";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iisi", $param_person_id, $param_employee_id, $facility_name, $param_hourly_rate);

            $param_person_id = (int)($person_id);
            $param_employee_id = (int)($employee_id);
            $param_hourly_rate = (int)($hourly_rate);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: public_health_worker.php");
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
                    <h2 class="mt-5">Create Public Health Worker</h2>
                    <p>Please fill this form and submit to add Public Health Worker record to the database</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Person ID</label>
                            <input type="number" name="person_id" class="form-control" value="<?php echo $person_id; ?>">
                        </div>
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input type="number" name="employee_id" class="form-control <?php echo (!empty($employee_id_error)) ? 'is-invalid' : '' ?>" value="<?php echo $employee_id; ?>">
                            <span class="invalid-feedback"><?php echo $employee_id_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Facility name</label>
                            <select class="custom-select" id="inputGroupSelect01" name="facility_name">
                                <?php
                                foreach ($all_facility as $facility) {
                                    echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Hourly Rate</label>
                            <input type="number" name="hourly_rate" class="form-control <?php echo (!empty($hourly_rate_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $hourly_rate; ?>">
                            <span class="invalid-feedback"><?php echo $hourly_rate_error; ?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="public_health_worker.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>