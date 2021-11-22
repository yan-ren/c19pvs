<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";
$link = connect();

// Define variables and initialize with empty values
$person_id = $employee_id = $facility_name = $hourly_rate = $status = "";
$status_error = "";

$sql = "SELECT * FROM facility";
$result = mysqli_query($link, $sql);
$all_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if (isset($_POST["person_id"]) && !empty($_POST["person_id"])) {
    // Get hidden input value
    $person_id = (int)(trim($_POST["person_id"]));
    $employee_id = (int)(trim($_GET["employee_id"]));
    $facility_name = trim($_GET["facility_name"]);

    // Get input values
    $new_employee_id = (int)(trim($_POST["new_employee_id"]));
    $new_facility_name = $_POST["new_facility_name"];
    $new_hourly_rate = (int)(trim($_POST["new_hourly_rate"]));
    $new_status = $_POST['new_status'];

    if (empty($new_status) || ($new_status !== 'A' && $new_status !== 'D')) {
        $status_error = "Please enter a valid status A or D";
    }

    // Prepare an update statement
    $sql = "UPDATE healthcare_worker SET employee_id =?, facility_name = ?, hourly_rate = ?, status=? WHERE person_id=? AND employee_id=? AND facility_name=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "isisiis", $new_employee_id, $new_facility_name, $new_hourly_rate, $new_status, $person_id, $employee_id, $facility_name);

    if ($stmt) {
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records updated successfully. Redirect to landing page
            header("location: public_health_worker.php");
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
        $employee_id = trim($_GET["employee_id"]);
        $facility_name = trim($_GET["facility_name"]);

        // Prepare a select statement
        $sql = "SELECT * FROM healthcare_worker WHERE person_id = ? AND employee_id=? AND facility_name=?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "iis", $person_id, $employee_id, $facility_name);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $employee_id = $row["employee_id"];
                    $facility_name = $row["facility_name"];
                    $hourly_rate = $row["hourly_rate"];
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
                    <p>Please edit the input values and submit to update the Public Health Worker record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Employee ID</label>
                            <input type="number" name="new_employee_id" class="form-control" value="<?php echo $employee_id; ?>">
                        </div>
                        <div class="form-group">
                            <label>Facility Name</label>
                            <select class="custom-select" id="inputGroupSelect01" name="new_facility_name">
                                <?php
                                foreach ($all_facility as $facility) {
                                    if ($facility['name'] == $facility_name) {
                                        echo '<option selected values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                                    } else {
                                        echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                                    }
                                } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Hourly Rate</label>
                            <input type="number" name="new_hourly_rate" class="form-control" value="<?php echo $hourly_rate; ?>">
                        </div>

                        <div class="form-group">
                            <label>Status</label>
                            <input type="text" name="new_status" class="form-control" value="<?php echo $status; ?>">
                            <span class="invalid-feedback"><?php echo $status_error; ?></span>
                            </select>
                        </div>

                        <input type="hidden" name="person_id" value="<?php echo $person_id; ?>" />
                        <input type="hidden" name="employee_id" value="<?php echo $employee_id; ?>" />
                        <input type="hidden" name="facility_name" value="<?php echo $facility_name; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="public_health_worker.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>