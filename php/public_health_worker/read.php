<?php
// Check existence of id parameter before processing further
if (isset($_GET["person_id"]) && !empty(trim($_GET["person_id"])) && isset($_GET["employee_id"]) && !empty(trim($_GET["employee_id"]))) {
    // Include config file
    require_once "../config.php";
    $link = connect();

    // Prepare a select statement
    $sql = "SELECT * FROM healthcare_worker WHERE person_id = ? AND employee_id=? AND facility_name=?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "iis", $param_id, $param_employee_id, $param_facility_name);

        // Set parameters
        $param_id = trim($_GET["person_id"]);
        $param_employee_id = trim($_GET["employee_id"]);
        $param_facility_name = trim($_GET["facility_name"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $person_id = $row['person_id'];
                $employee_id = $row["employee_id"];
                $facility_name = $row["facility_name"];
                $hourly_rate = $row["hourly_rate"];
                $status = $row['status'];
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
                        <label>Employee ID</label>
                        <p><b><?php echo $row["employee_id"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Facility Name</label>
                        <p><b><?php echo $row["facility_name"]; ?></b></p>
                    </div>

                    <div class="form-group">
                        <label>Hourly Rate</label>
                        <p><b><?php echo $row["hourly_rate"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <p><b><?php echo $row["status"]; ?></b></p>
                    </div>
                    <p><a href="public_health_worker.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>