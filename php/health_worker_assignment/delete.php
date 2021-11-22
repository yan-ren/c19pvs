<?php
error_reporting(-1);
ini_set('display_errors', 'On');

// Process delete operation after confirmation
if (isset($_POST["person_id"]) && !empty($_POST["person_id"]) && isset($_POST["facility_name"]) && !empty($_POST["facility_name"])) {
    // Include config file
    require_once "../config.php";
    $link = connect();

    $person_id = (int)trim($_POST['person_id']);
    $facility_name = trim($_POST['facility_name']);

    // Prepare a delete statement
    $sql = "DELETE FROM healthcare_worker_assignment WHERE person_id = ? AND facility_name =?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters, i stands for integer,s stands for string
        mysqli_stmt_bind_param($stmt, "is", $person_id, $facility_name);


        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page
            header("location: assignment.php");
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
    // Check existence of id parameter
    if (empty(trim($_GET["facility_name"]) || empty(trim($_GET["person_id"])))) {
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
    <title>Delete Record</title>
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
                <h2 class="mt-5 mb-3">Delete Public Health Worker Record</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger">
                        <input type="hidden" name="person_id" value="<?php echo trim($_GET["person_id"]); ?>"/>
                        <input type="hidden" name="facility_name" value="<?php echo trim($_GET["facility_name"]); ?>"/>

                        <p>Are you sure you want to delete this Person ID =
                            <b><?php echo trim($_GET["person_id"]); ?> </b> Public Health Worker Assignment Record?</p>
                        <p>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="assignment.php" class="btn btn-secondary">No</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>