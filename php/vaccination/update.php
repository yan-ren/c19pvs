<?php
// Include config file
require_once "../config.php";
require_once "../util.php";
$link = connect();

// Define variables and initialize with empty values
$person_id = $vaccine_name = $lot = $location = $city = $province = $dose = $date = $country = "";
$person_id_error = $date_error = $lot_error = $location_error = $city_error = $province_error = $dose_error = $country_error = "";

//all locations
$sql = "SELECT * FROM facility";
$result = mysqli_query($link, $sql);
$all_facility = mysqli_fetch_all($result, MYSQLI_ASSOC);

//all safe vaccines
$sql2 = "SELECT * From vaccine where status = 'safe'";
$result2 = mysqli_query($link, $sql2);
$all_vaccines = mysqli_fetch_all($result2, MYSQLI_ASSOC);

// Processing form data when form is submitted
if (isset($_POST["person_id"]) && !empty($_POST["person_id"])) {
    // Get hidden input value
    $person_id = (int)trim($_POST["person_id"]);
    $vaccine_name = trim($_POST["vaccine_name"]);
    $lot = trim($_POST["lot"]);
    $location = trim($_POST["location"]);
    $city = trim($_POST['city']);
    $country = trim($_POST['country']);
    $province = trim($_POST['province']);
    $date = trim($_POST['date']);
    $dose = (int)trim($_POST['dose']);

    // Validate inputs
    if (empty($person_id) && $person_id !== '0') {
        $person_id_error = "Please enter a valid person id";
    }
    if (empty($vaccine_name) && $vaccine_name !== 'NULL') {
        $vaccine_name_error = "Please enter a valid vaccine name";
    }
    if (empty($location) && $location !== 'NULL') {
        $location_error = "Please enter a valid location name";
    }
    if (empty($dose) && $dose !== '0') {
        $dose_error = "Please enter a valid hourly rate";
    }
    if (empty($lot) && $lot !== 'NULL') {
        $status_error = "Please enter a valid lot";
    }

    // Validate date
    if (empty($date)) {
        $date = null;
    } elseif (!validateMysqlDate($date)) {
        $date_error = "Invalid date format, please use format yyyy-mm-dd";
    }

    if (empty($city) && $city !== 'NULL') {
        $city_error = "Please enter a valid city name";
    }
    if (empty($province) && $province !== 'NULL') {
        $province_error = "Please enter a valid province name";
    }
    if (empty($country) && $country !== 'NULL') {
        $country_error = "Please enter a valid country name";
    }


    // Check input errors before inserting in database
    if (empty($person_id_error) && empty($country_error) && empty($province_error) && empty($date_error) && empty($dose_error) && empty($vaccine_name_error) && empty($city_error) && empty($location_error) && empty($lot_error)) {
        // Prepare an update statement
        $sql = "UPDATE vaccination SET vaccine_name=?, dose=?, date =?, lot =?, location =?, city=?, province=?,country=?  WHERE person_id=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "sissssssi", $vaccine_name, $param_dose_id, $date, $lot, $location, $city, $province, $country, $param_person_id);

        $param_person_id = (int)($person_id);
        $param_dose_id = (int)($dose);

        if ($stmt) {
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: vaccination.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

        // Close statement
        mysqli_stmt_close($stmt);
    }

    // Close connection
    mysqli_close($link);
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["person_id"]) && !empty(trim($_GET["person_id"]))) {
        // Get URL parameter
        $person_id =  trim($_GET["person_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM vaccination WHERE person_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);

            // Set parameters
            $param_id = $person_id;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $vaccine_name = $row["vaccine_name"];
                    $dose = $row["dose"];
                    $date = $row["date"];
                    $lot = $row["lot"];
                    $location = $row["location"];
                    $city = $row["city"];
                    $province = $row["province"];
                    $country = $row["country"];

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
                <h2 class="mt-5">Update Vaccination Record</h2>
                <p>Please edit the input values and submit to update Vaccination record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Person ID</label>
                        <input type="number" name="person_id" class="form-control" value="<?php echo $person_id; ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>Vaccine Name</label>
                        <select class="custom-select" id="inputGroupSelect01" name="vaccine_name">
                            <?php
                            foreach ($all_vaccines as $vaccines) {
                                echo '<option values=\"' . $vaccines['vaccine_name'] . '\">' . $vaccines['vaccine_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Dose</label>
                        <input type="number" name="dose"
                               class="form-control <?php echo (!empty($dose_error)) ? 'is-invalid' : '' ?>"
                               value="<?php echo $dose; ?>">
                        <span class="invalid-feedback"><?php echo $dose_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Date</label>
                        <input type="date" name="date"
                               class="form-control <?php echo (!empty($date_error)) ? 'is-invalid' : '' ?>"
                               value="<?php echo $date; ?>">
                        <span class="invalid-feedback"><?php echo $date_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Lot</label>
                        <input type="text" name="lot"
                               class="form-control <?php echo (!empty($lot_error)) ? 'is-invalid' : '' ?>"
                               value="<?php echo $lot; ?>">
                        <span class="invalid-feedback"><?php echo $lot_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Location</label>
                        <select class="custom-select" id="inputGroupSelect01" name="location">
                            <?php
                            foreach ($all_facility as $facility) {
                                echo '<option values=\"' . $facility['name'] . '\">' . $facility['name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>City</label>
                        <input type="text" name="city"
                               class="form-control <?php echo (!empty($city_error)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $city; ?>">
                        <span class="invalid-feedback"><?php echo $city_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Province</label>
                        <input type="text" name="province"
                               class="form-control <?php echo (!empty($province_error)) ? 'is-invalid' : ''; ?>"
                               value="<?php echo $province; ?>">
                        <span class="invalid-feedback"><?php echo $province_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <input type="text" name="country"
                               class="form-control <?php echo (!empty($country_error)) ? 'is-invalid' : '' ?>"
                               value="<?php echo $country; ?>">
                        <span class="invalid-feedback"><?php echo $country_error; ?></span>
                    </div>
                    <input type="hidden" name="person_id" value="<?php echo $person_id; ?>" />
                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="vaccination.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>