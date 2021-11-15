
<?php
// Include config file
require_once "../config.php";

$link = connect();

// Define variables and initialize with empty values
$covid_id = $status = $variant = "";
$covid_id_error = $status_error = $variant_error = "";

//get status values
$sql = "SELECT DISTINCT `status` FROM covid;";
$result = mysqli_query($link, $sql);
$all_covid = mysqli_fetch_all($result, MYSQLI_ASSOC);


// Processing form data when form is submitted
if (isset($_POST["covid_id"]) && !empty($_POST["covid_id"])) {
    // Get hidden input value
    $covid_id = (int)trim($_POST["covid_id"]);
    $status = trim($_POST["status"]);
    $variant = trim($_POST['suspension']);


    // Validate vaccine name
    if (empty($covid_id) && $covid_id === 0) {
        $covid_id_error = "Please enter a valid Covid ID";
    }

    if (empty($variant)) {
        $variant = "UNKNOWN";
    }

    // Check input errors before inserting in database
    if (empty($covid_id_error)) {
        // Prepare an update statement
        $sql = "UPDATE covid SET covid_id=?, variant=?, status =? WHERE covid_id=?";
        $stmt = mysqli_prepare($link, $sql);
        mysqli_stmt_bind_param($stmt, "issi", $covid_id,$variant, $status,$covid_id );


        if ($stmt) {
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                // Records updated successfully. Redirect to landing page
                header("location: variants.php");
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
    if (isset($_GET["covid_id"]) && !empty(trim($_GET["covid_id"]))) {
        // Get URL parameter
        $covid_id = trim($_GET["covid_id"]);

        // Prepare a select statement
        $sql = "SELECT * FROM covid WHERE covid_id = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $covid_id);

            // Set parameters

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $covid_id = $row["covid_id"];
                    $variant = $row["variant"];
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
                <h2 class="mt-5">Update Variant Type Record</h2>
                <p>Please edit the input values and submit to update Variant Type record.</p>
                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                    <div class="form-group">
                        <label>Covid ID</label>
                        <input type="number" name="covid_id"
                               class="form-control <?php echo (!empty($covid_id_error)) ? 'is-invalid' : '' ?>"
                               value="<?php echo $covid_id; ?>">
                        <span class="invalid-feedback"><?php echo $covid_id_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Variant Name</label>
                        <input type="text" name="variant"
                               class="form-control <?php echo (!empty($variant_error)) ? 'is-invalid' : '' ?>"
                               value="<?php echo $variant; ?>">
                        <span class="invalid-feedback"><?php echo $variant_error; ?></span>
                    </div>
                    <div class="form-group">
                        <label>Status</label>
                        <select class="custom-select" id="inputGroupSelect01" name="status">
                            <?php
                            foreach ($all_covid as $cov) {
                                echo '<option values=\"' . $cov['status'] . '\">' . $cov['status'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>



                    <input type="hidden" name="covid_id" value="<?php echo $covid_id; ?>"/>

                    <input type="submit" class="btn btn-primary" value="Submit">
                    <a href="variants.php" class="btn btn-secondary ml-2">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>