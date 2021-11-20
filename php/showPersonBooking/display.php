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
                <p> Please enter a valid name to search booking information.</p>
                <?php
                // Include config file
                require_once "../config.php";
                $link = connect();

                $firstName = $lastName = "";
                $firstName_error = $lastName_error = "";

                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $firstName = trim($_POST["firstName"]);
                    $lastName = trim($_POST["lastName"]);

//                    echo '<pre>';
//                    echo var_dump($firstName);
//                    echo var_dump($lastName);
//                    echo '</pre>';
//                    exit;
                    // Validate
                    if (!empty($firstName_error)) {
                        $firstName_error = "Please enter a valid First Name";
                    }
                    if (!empty($lastName_error)) {
                        $lastName_error = "Please enter a valid Last Name";
                    }

                    if (empty($lastName_error) && empty($firstName_error)) {
                        $sql = "SELECT first_name, last_name FROM person where first_name = ? and last_name = ?";

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
                    <div class="input-group">
                        <span class="input-group-text">First and last name</span>
                        <input type="text" name="firstName" aria-label="First name"
                               class="form-control <?php echo (!empty($firstName_error)) ? 'is-invalid' : '' ?>"
                               value="<?php echo $firstName; ?>">
                        <span class="invalid-feedback"><?php echo $firstName_error; ?></span>

                        <input type="text" name="lastName" aria-label="Last name"
                               class="form-control <?php echo (!empty($lastName_error)) ? 'is-invalid' : '' ?>"
                               value="<?php echo $lastName; ?>">
                        <span class="invalid-feedback"><?php echo $lastName_error; ?></span>

                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <button class="btn btn-primary me-md-2" type="submit">Button</button>
                    </div>





                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>