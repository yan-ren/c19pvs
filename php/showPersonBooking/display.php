<!DOCTYPE html>
<html lang="en">

<head>
    <?php include($_SERVER['DOCUMENT_ROOT'] . "/html/head.html") ?>
</head>

<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="mt-5 mb-3 clearfix">
                    <h2 class="pull-left">Personal Booking Information</h2>
                </div>
                <p> Please enter a valid Person ID to search booking information.</p>
                <?php
                // Include config file
                require_once "../config.php";
                $link = connect();

                $person_id = "";
                $person_id_error = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $person_id = trim($_POST["personId"]);

//                    echo '<pre>';
//                    echo var_dump($firstName);
//                    echo var_dump($lastName);
//                    echo '</pre>';
//                    exit;
                    // Validate
                    if (!empty($person_id_error)) {
                        $person_id_error = "Please enter a valid First Name";
                    }
                    
                    if (empty($person_id_error)) {
                        $sql = "";

                        $stmt = mysqli_prepare($link, $sql);
                        if ($stmt) {
                            mysqli_stmt_bind_param($stmt, "ss", $firstName, $lastName);
                            if (mysqli_stmt_execute($stmt)) {
                                $result = mysqli_stmt_get_result($stmt);
                                if (mysqli_num_rows($result) == 1) {
                                    $row = mysqli_fetch_array($result);
                                    mysqli_free_result($result);
                                } else {
                                    echo '<div class="alert alert-danger"><em>Person record is not found.</em></div>';
                                }
                            } else {
                                echo "SQL query error: " . mysqli_stmt_errno($stmt);
                            }
                        } else {
                            echo "Prepare SQL error: " . $link->error;
                        }
                        // Close statement
                        mysqli_stmt_close($stmt);
                        // Close connection
                        mysqli_close($link);
                    }
                }
                ?>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                    <div class="input-group mb-3">
                        <input type="text" name="personId"
                               class="form-control <?php echo (!empty($person_id_error)) ? 'is-invalid' : '' ?>"
                               placeholder="Please enter a Person ID" aria-describedby="button-addon2"> <?php echo $person_id ?>
                        <span class="invalid-feedback"><?php echo $person_id_error; ?></span>

                        <button class="btn btn-sm btn-outline-secondary" type="submit">Search</button>

                    </div>
            </div>


        </div>
        </form>
    </div>
</div>
</div>
</body>

</html>