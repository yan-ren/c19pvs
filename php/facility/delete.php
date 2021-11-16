<?php
error_reporting(-1);
ini_set('display_errors', 'On');

// Process delete operation after confirmation
if (isset($_POST["name"]) && !empty($_POST["name"])) {
    // Include config file
    require_once "../config.php";
    $link = connect();

    // Prepare a delete statement
    $sql = "DELETE FROM facility WHERE name = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters, i stands for integer
        mysqli_stmt_bind_param($stmt, "s", $name);

        // Set parameters
        $name = (string)($_POST["name"]);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page
            header("location: facility.php");
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
    if (empty(trim($_GET["name"]))) {
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
                <h2 class="mt-5 mb-3">Delete Record</h2>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger">
                        <input type="hidden" name="name" value="<?php echo trim($_GET["name"]); ?>" />
                        <p>Are you sure you want to delete this facility record?</p>
                        <p>
                            <input type="submit" value="Yes" class="btn btn-danger">
                            <a href="facility.php" class="btn btn-secondary">No</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>