<?php
// Include config file
require_once "../config.php";

$link = connect();

// Define variables and initialize with empty values
$person_id = $facility_name = $start_date = $end_date = $role = $vaccine_name = $dose = $lot = "";
$start_date_error = $end_date_error = $role_error = $lot_error = $availability_error = "";

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


// Processing form data when form is submitted
if (isset($_POST["person_id"]) && !empty(trim($_POST["person_id"])) && isset($_POST["facility_name"]) && !empty(trim($_POST["facility_name"])) && isset($_POST["role"]) && !empty(trim($_POST["role"])) && isset($_POST["start_date"]) && !empty(trim($_POST["start_date"])) && isset($_POST["end_date"]) && !empty(trim($_POST["end_date"]))) {

    $person_id = (int)trim($_POST["person_id"]);
    $facility_name = trim($_POST["facility_name"]);
    $start_date = trim($_POST["start_date"]);
    $end_date = trim($_POST["end_date"]);
    $role = trim($_POST["role"]);
//    $vaccine_name = trim($_POST["vaccine_name"]);
//    $dose = trim($_POST["dose_given"]);
//    $lot = trim($_POST["lot"]);

//        echo "<pre>";
//        echo $person_id;
//        echo $facility_name;
//        echo $start_date;
//        echo $end_date;
//        echo $role;
//        echo $vaccine_name;
//        echo $dose;
//        echo $lot;
//        echo "</pre>";
//        exit;


    if ($role == "nurse") {
        // Check for facility availability
        // Find how many nurses working on given date
        $number_of_nurses = 0;
        $link = connect();
        $sql_check2 = "SELECT count(*) AS nurses
    FROM healthcare_worker_assignment
    WHERE start_date >= ? AND end_date <= ? AND role='nurse' AND facility_name=?";

        if ($stmt = mysqli_prepare($link, $sql_check2)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sss", $start_date, $end_date, $facility_name);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    $number_of_nurses = (int)($row["nurses"]);
                    echo var_dump($number_of_nurses);
                }
            } else {
                $error = mysqli_stmt_error($stmt);
                echo '<script> alert("' . $error . '")</script>';
            }
        } else {
            echo "<script>alert('Oooooooooops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
        }
        mysqli_stmt_close($stmt);
        mysqli_close($link);

        if ($number_of_nurses == 0) {
            $availability_error = "No availability on given date!";
            //            echo '<script> if(!alert("' . $availability_error . '")){
            //                document.location = \'check_availability.php\';
            //            }
            //            </script>';
        }


        $link = connect();
        // Prepare an insert statement
        $sql = "UPDATE healthcare_worker_assignment SET start_date =?, end_date =?, role =?, vaccine_name =?, dose_given =?, lot =? WHERE person_id =? AND facility_name =?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssis", $start_date, $end_date, $role, $vaccine_name, $dose, $lot, $person_id, $facility_name);


            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: assignment.php");
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
        mysqli_close($link);
    } else {

        $link = connect();

        //all roles except nurse
        // Prepare an insert statement
        $sql = "UPDATE healthcare_worker_assignment SET start_date =?, end_date=?, role=?, vaccine_name = NULL, dose_given =NULL, lot =NULL WHERE person_id = ? AND facility_name =?";

        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssis", $start_date, $end_date, $role, $person_id, $facility_name);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records created successfully. Redirect to landing page
                header("location: assignment.php");
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
        mysqli_close($link);
    }
} else {
    $link = connect();

    // Check existence of id parameter before processing further
    if (isset($_GET["person_id"]) && !empty(trim($_GET["person_id"])) && isset($_GET["facility_name"]) && !empty(trim($_GET["facility_name"])) && isset($_GET["role"]) && !empty(trim($_GET["role"])) && isset($_GET["start_date"]) && !empty(trim($_GET["start_date"])) && isset($_GET["end_date"]) && !empty(trim($_GET["end_date"]))) {
        // Get URL parameter
        $person_id = trim($_GET["person_id"]);
        $facility_name = $_GET['facility_name'];
        $role = $_GET['role'];
        $start_date = $_GET['start_date'];
        $end_date =$_GET['end_date'];

        // Prepare a select statement
        $sql = "SELECT * FROM healthcare_worker_assignment WHERE person_id = ? AND facility_name =? AND role =? AND start_date =? AND end_date =?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "issss", $person_id, $facility_name, $role, $start_date, $end_date);

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    // Retrieve individual field value
                    $role = $row['role'];
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
    }
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
                <h2 class="mt-5">Update Public Health Worker Assignment</h2>
                <p>Please fill this form and submit to update Public Health Worker Assignment record to the database</p>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" onsubmit="removeDisable()">
                    <div class="form-group">
                        <label>Person ID</label>
                        <input type="number" id="person_id" name="person_id" class="form-control"
                               value="<?php echo $person_id; ?>" readonly>
                    </div>

                    <div class="form-group">
                        <label>Facility name</label>
                        <select class="custom-select" id="inputGroupSelectFacility" name="facility_name" disabled>
                            <?php
                            foreach ($all_facility as $facility) {
                                echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Start Date</label>
                        <input type="date" name="start_date"
                               class="form-control <?php echo (!empty($start_rate_error)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $start_date; ?>">
                        <span class="invalid-feedback"><?php echo $start_date_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>End Date</label>
                        <input type="date" name="end_date"
                               class="form-control <?php echo (!empty($end_date_error)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $end_date; ?>">
                        <span class="invalid-feedback"><?php echo $end_date_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="custom-select" id="inputGroupSelectRole" name="role" onchange="readOnly()">
                            <option value="nurse">Nurse</option>
                            <option value="manager">Manager</option>
                            <option value="security">Security</option>
                            <option value="secretary">Secretary</option>
                            <option value="regular">Regular</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Vaccine Name</label>
                        <select class="custom-select" id="inputGroupSelectVaccine" name="vaccine_name">
                            <?php
                            foreach ($all_vaccines as $vaccine) {
                                echo '<option values=\"' . $vaccine['vaccine_name'] . '\">' . $vaccine['vaccine_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Dose Given</label>
                        <input type="number" id="dose" name="dose_given" class="form-control dose"
                               value="<?php echo $dose; ?>">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lot ID</label>
                        <input type="text" id="lot" name="lot" class="form-control" value="<?php echo $lot; ?>">
                    </div>

                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="assignment.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
<script>
    function readOnly() {
        let role = document.getElementById("inputGroupSelectRole").value;
        if (role != "nurse") {
            document.getElementById("dose").readOnly = true;
            document.getElementById("lot").readOnly = true;
            var selectElement = document.getElementById("inputGroupSelectVaccine");
            selectElement.value = "";
            selectElement.setAttribute("disabled", "disabled");
        } else {
            document.getElementById("dose").readOnly = false;
            document.getElementById("lot").readOnly = false;
            document.getElementById("inputGroupSelectVaccine").disabled = false;
        }
    }
    function removeDisable(){
        document.getElementById("inputGroupSelectFacility").disabled =false;
    }



</script>

</html>