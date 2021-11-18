<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$first_name = $last_name = $facility_name = $date = "";
$person_id = "";
$avaliability_error = "";

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['check'])) {

    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $facility_name = trim($_POST["facility_name"]);
    $date = trim($_POST["date"]);

    // Check if person is healthcare worker


    // Check for person age group
    $sql = "SELECT first_name, last_name
    FROM person
    INNER JOIN age_group ON person.age_group_id=age_group.age_group_id
    WHERE first_name=? AND last_name=? AND vaccination_date <= ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "sss", $first_name, $last_name, $date);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);
            if (mysqli_num_rows($result) != 1) {
                $avaliability_error = "person not found, or doesn't belong to available age group";
                echo '<script> if(!alert("' . $avaliability_error . '")){
                    document.location = \'check_avaliability.php\';
                }
                </script>';
            }
        } else {
            $error = mysqli_stmt_error($stmt);
            echo '<script> alert("' . $error . '")</script>';
        }
    } else {
        echo "<script>alert('Oops! Something went wrong. Please try again later. Error:" . $link->error . " ');location='create.php';</script>";
    }

    // Close statement
    mysqli_stmt_close($stmt);
    // Close connection
    mysqli_close($link);

    // Check for facility avaliability



} else if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = trim($_POST["first_name"]);
    $last_name = trim($_POST["last_name"]);
    $facility_name = trim($_POST["facility_name"]);
    $date = trim($_POST["date"]);
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
                    <h2 class="mt-5">Create New booking</h2>
                    <form name="avaliability" action="create.php" onsubmit="return validateForm(this.name)" method="post">
                        <div class="form-group">
                            <label>First Name</label>
                            <input type="text" name="first_name" class="form-control" value="<?php echo $first_name; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Last Name</label>
                            <input type="text" name="last_name" class="form-control" value="<?php echo $last_name; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Facility</label>
                            <input type="text" name="facility_name" class="form-control" value="<?php echo $facility_name; ?>" readonly>
                        </div>
                        <div class="form-group">
                            <label>Date</label>
                            <input type="date" name="date" class="form-control" value="<?php echo $date; ?>" readonly>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Check">
                        <a href="../../booking.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>