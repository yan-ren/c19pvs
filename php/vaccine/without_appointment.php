<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";
$link = connect();

$first_name = $last_name = $facility_name = $date = "";
$person_id = "";
// Handle POST
if (isset($_POST["vaccine"])) {
    $sql = "INSERT INTO vaccination (person_id, vaccine_name, dose, date, location)
            VALUES (?,?,?,?,?)";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "isiss", $param_person_id, $param_vaccine_name, $param_dose, $param_date, $param_location);

        $param_person_id = (int)(trim($_POST['person_id']));
        $param_vaccine_name = trim($_POST['vaccine']);
        $param_dose = (int)(trim($_POST['dose']));
        $param_date = trim($_POST['date']);
        $param_location = trim($_POST['facility_name']);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            echo "<script>alert('Vaccination Successful!');location.href='../../vaccine.php'</script>";
        } else {
            $error = mysqli_stmt_error($stmt);
            echo '<script> alert("' . $error . '")</script>';
        }
    } else {
        echo "<script>alert('Oops! Something went wrong. Please try again later');location.href='../../vaccine.php';</script>";
    }
}
// Handle GET
else {
    $first_name = trim($_GET['first_name']);
    $last_name = trim($_GET['last_name']);
    $facility_name = trim($_GET['preferred_location']);
    $date = trim($_GET['date']);

    // Check if person is registered in the system
    $sql = "SELECT person_id, first_name, last_name FROM person WHERE first_name=? AND last_name=?";


    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "ss", $first_name, $last_name);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                // Retrieve individual field value
                $person_id = (int) ($row['person_id']);
            } else {
                // person not found
                echo '<script>alert("Name: ' . $first_name . ' ' . $last_name . ' not found in system. Please check the name!");location.href=\'../../vaccine.php\'</script>';
                exit();
            }
        } else {
            echo '<script> alert("' . mysqli_stmt_error($stmt) . '")</script>';
        }
    } else {
        echo '<script>alert("Oops! Something went wrong. ' . $link->error . '");location.href=\'../../vaccine.php\';</script>';
    }

    // Fetch vaccine table
    $sql = "SELECT * FROM vaccine WHERE status='safe'";
    $result = mysqli_query($link, $sql);
    $vaccine_json = mysqli_fetch_all($result, MYSQLI_ASSOC);
}

// Close statement
mysqli_stmt_close($stmt);

// Close connection
mysqli_close($link);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>COVID-19 Public Health Care Population Vaccination System</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
</head>

<body>
    <div class="container">
        <div class="container-fluid">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Availiable Vaccine</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <?php
                            $obj;
                            foreach ($vaccine_json as $obj) {
                                echo '<div class="form-group">';
                                echo '<p><b>' . $obj['vaccine_name'] . ', Available Dose: ' . $obj['dose'] . '</b></p>';
                                echo '</div>';
                            }
                            ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Take Vaccination</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <form id="vaccine" action="/php/vaccine/without_appointment.php?vaccine=1 ?>" method="post">
                                <div class="form-group">
                                    <label>Person ID</label>
                                    <input class="form-control" placeholder="<?php echo $person_id; ?>" name="person_id" value="<?php echo htmlspecialchars($person_id); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>First Name</label>
                                    <input class="form-control" placeholder="<?php echo $first_name; ?>" name="first_name" value="<?php echo htmlspecialchars($first_name); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Last Name</label>
                                    <input class="form-control" placeholder="<?php echo $last_name; ?>" name="last_name" value="<?php echo htmlspecialchars($last_name); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Vaccine</label>
                                    <input class="form-control" placeholder="" name="vaccine">
                                </div>
                                <div class="form-group">
                                    <label>Dose</label>
                                    <input class="form-control" placeholder="" name="dose">
                                </div>
                                <div class="form-group">
                                    <label>Appointment Location</label>
                                    <input class="form-control" placeholder="<?php echo $facility_name; ?>" name="facility_name" value="<?php echo htmlspecialchars($facility_name); ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label>Date</label>
                                    <input class="form-control" placeholder="<?php echo $date; ?>" name="date" value="<?php echo htmlspecialchars($date); ?>" readonly>
                                </div>
                                <button type="submit" class="btn aqua-gradient">Vaccine</button>
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p class="pull-right"><a href="../../vaccine.php">Back</a></p>
        </div>
    </div>
</body>

</html>