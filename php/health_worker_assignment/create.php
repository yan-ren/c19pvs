<?php
// Include config file
require_once "../config.php";

$link = connect();

// Define variables and initialize with empty values
$person_id = $facility_name = $start_date = $end_date = $role = $vaccine_name = $dose = $lot = "";
$start_date_error = $end_date_error = $role_error = $lot_error = "";

//get all facilities
$sql = "SELECT * FROM facility";
$result = mysqli_query($link, $sql);
$all_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

//get all safe vaccine for assignment
$sql2 = "SELECT * FROM vaccine where status = 'safe'";
$result2 = mysqli_query($link, $sql2);
$all_vaccines = mysqli_fetch_all($result2, MYSQLI_ASSOC);

//get all roles 
$sql3 = "SELECT DISTINCT `role` FROM healthcare_worker_assignment";
$result3 = mysqli_query($link, $sql3);
$all_roles = mysqli_fetch_all($result3, MYSQLI_ASSOC);

// get all doses
$sql4 = "SELECT DISTINCT IFNULL(dose,0) as dose FROM vaccine";
$result4 = mysqli_query($link, $sql4);
$all_doses = mysqli_fetch_all($result4, MYSQLI_ASSOC);

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $person_id = (int)trim($_POST["person_id"]);
    $facility_name = trim($_POST["facility_name"]);
    $start_date = trim($_POST["start_date"]);
    $end_date = trim($_POST["end_date"]);
    $role = trim($_POST["role"]);
    $vaccine_name = trim($_POST["vaccine_name"]);
    $dose = trim($_POST["dose_given"]);
    $lot = trim($_POST["lot"]);

    // Validate person_id employee_id

    // if (empty($employee_id) && $employee_id !== '0') {
    //     $employee_id_error = "Please enter a valid employee id";
    // }

    // if (empty($hourly_rate) && $hourly_rate !== '0') {
    //     $hourly_rate_error = "Please enter a valid hourly rate";
    // }

    // Check input errors before inserting in database
    if (empty($person_id_error) && empty($employee_id_error) && empty($facility_name_error) && empty($hourly_rate_error)) {
        // Prepare an insert statement
        $sql = "INSERT INTO healthcare_worker_assignment (person_id, facility_name, start_date, end_date, role, vaccine_name, dose_given, lot) VALUES (?, ?, ?, ?,?,?,?,?)";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "isssssss", $person_id, $facility_name, $start_date,$end_date, $role, $vaccine_name,$dose,$lot);

  
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: healthcare_worker_assignment.php");
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
                    <h2 class="mt-5">Create Public Health Worker Assignemnt</h2>
                    <p>Please fill this form and submit to add Public Health Worker Assignment record to the database</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Person ID</label>
                            <input type="number" name="person_id" class="form-control" value="<?php echo $person_id; ?>">
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
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control <?php echo (!empty($start_rate_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $start_date; ?>">
                            <span class="invalid-feedback"><?php echo $start_date_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control <?php echo (!empty($end_date_error)) ? 'is-invalid' : ''; ?>" value="<?php echo $end_date; ?>">
                            <span class="invalid-feedback"><?php echo $end_date_error; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Role</label>
                            <select class="custom-select" id="inputGroupSelect01" name="role">
                                <?php
                                foreach ($all_roles as $roles) {
                                    echo '<option values=\"' . $roles['role'] . '\">' . $roles['role'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Vaccine Name</label>
                            <select class="custom-select" id="inputGroupSelect01" name="vaccine_name">
                                <?php
                                foreach ($all_vaccines as $vaccine) {
                                    echo '<option values=\"' . $vaccine['vaccine_name'] . '\">' . $vaccine['vaccine_name'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Dose Given</label>
                            <select class="custom-select" id="inputGroupSelect01" name="dose_given">
                                <?php
                                foreach ($all_doses as $doses) {
                                    echo '<option values=\"' . $doses['dose'] . '\">' . $doses['dose'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Lot ID</label>
                            <input type="text" name="lot" class="form-control" value="<?php echo $lot; ?>">
                        </div>

<<<<<<< HEAD
=======

>>>>>>> d2de733 (small fix)
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="assignment.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>