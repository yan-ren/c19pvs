<?php
// Include config file
require_once "../config.php";
$link = connect();

// Define variables and initialize with empty values
$name = $age_group = "";

// Processing form data when form is submitted
if (isset($_POST["name"]) && !empty($_POST["name"])) {
    // Get hidden input value
    $name = $_POST["name"];
    $input_age_group = (int)(trim($_POST["age_group"]));

    // Prepare an update statement
    $sql = "UPDATE province SET age_group=? WHERE name =?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "is",  $input_age_group, $name);

    if ($stmt) {
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records updated successfully. Redirect to landing page
            header("location: province.php");
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
    if (isset($_GET["name"]) && !empty(trim($_GET["name"]))) {
        // Get URL parameter
        $name =  trim($_GET["name"]);

        // Prepare a select statement
        $sql = "SELECT * FROM province WHERE name = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_id);

            // Set parameters
            $param_id = $name;

            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $age_group = $row["age_group"];
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
                    <p>Please edit the input values and submit to update the province record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Province</label>
                            <input type="text" name="name" class="form-control" value="<?php echo $name; ?>" readonly>
                            <span class="invalid-feedback"><?php echo $name; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Vaccines Available Under Age Group</label>
                            <input type="number" name="age_group" class="form-control" value="<?php echo $age_group; ?>">
                            <span class="invalid-feedback"><?php echo $age_group; ?></span>
                        </div>

                        <input type="hidden" name="name" value="<?php echo $name; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="province.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>