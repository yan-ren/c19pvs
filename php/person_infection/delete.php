<?php
error_reporting(-1);
ini_set('display_errors', 'On');

// Process delete operation after confirmation
if (isset($_POST["person_id"]) && !empty($_POST["person_id"])) {
    // Include config file
    require_once "../config.php";
    $link = connect();

    $person_id = trim($_POST["person_id"]);
    $date = trim($_POST["date"]);
    $covid_id = trim($_POST["covid_id"]);

    // Prepare a delete statement
    $sql = "DELETE FROM infection WHERE person_id = ? AND date = ? AND covid_id = ?";

    if ($stmt = mysqli_prepare($link, $sql)) {
        // Bind variables to the prepared statement as parameters, i stands for integer
        mysqli_stmt_bind_param($stmt, "isi", $person_id, $date, $covid_id);

        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records deleted successfully. Redirect to landing page
            header("location: person_infection.php");
            exit();
        } else {
            echo "Oops! Something went wrong. Please try again later.";
        }
    }

    // Close statement
    mysqli_stmt_close($stmt);

    // Close connection
    mysqli_close($link);
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
                            <input type="hidden" name="person_id" value="<?php echo trim($_GET["person_id"]); ?>" />
                            <input type="hidden" name="date" value="<?php echo trim($_GET["date"]); ?>" />
                            <input type="hidden" name="covid_id" value="<?php echo trim($_GET["covid_id"]); ?>" />
                            <p>Are you sure you want to delete this infection record?</p>
                            <p>
                                <input type="submit" value="Yes" class="btn btn-danger">
                                <a href="person_infection.php" class="btn btn-secondary">No</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>