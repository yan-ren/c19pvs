<?php
error_reporting(-1);
ini_set('display_errors', 'On');
// Include config file
require_once "../config.php";
$link = connect();

// Define variables and initialize with empty values
$name = $address = $phone = $website = "";
$type = $capacity =$manager=$province=$category= "";

$sql = "SELECT * FROM province";
$result = mysqli_query($link, $sql);
$all_province = mysqli_fetch_all($result, MYSQLI_ASSOC);

// Processing form data when form is submitted
if (isset($_POST["name"]) && !empty($_POST["name"])) {

    $name = trim($_POST["name"]);
    $address = trim($_POST["address"]);
    $phone = trim($_POST["phone"]);
    $website = trim($_POST["website"]);
    $type = trim($_POST["type"]);
    $capacity = (int)trim($_POST["capacity"]);
    $manager = (int)trim($_POST["manager"]);
    $province = trim($_POST["province"]);
    $category = trim($_POST["category"]);

    // Get input values
    $new_name = trim($_POST["name"]);
    $new_address = trim($_POST["address"]);
    $new_phone = trim($_POST["phone"]);
    $new_website = trim($_POST["website"]);
    $new_type = trim($_POST["type"]);
    $new_capacity = (int)trim($_POST["capacity"]);
    $new_manager = (int)trim($_POST["manager"]);
    $new_province = trim($_POST["province"]);
    $new_category = trim($_POST["category"]);

    // Prepare an update statement
    $sql = "UPDATE facility SET address = ?, phone = ?, website=?, type=?, capacity=?,  manager=?, province=?, category=?, where name=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "sssssiiss", $new_name, $new_address, $new_phone, $new_website,$new_type,$new_capacity,$new_manager, $new_province,$new_category);

    if ($stmt) {
        // Attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)) {
            // Records updated successfully. Redirect to landing page
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
}
 else {
    // Check existence of id parameter before processing further
    if (isset($_GET["name"]) && !empty(trim($_GET["name"]))) {
        // Get URL parameter
        $name = trim($_GET["name"]);
        // Prepare a select statement
        $sql = "SELECT * FROM facility WHERE name = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_id);
// Set parameters
            $param_id = $name;
            // Attempt to execute the prepared statement
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);

                if (mysqli_num_rows($result) == 1) {

                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $name = $row["name"];
                    $address = $row["address"];
                    $phone = $row["phone"];
                    $website = $row["website"];
                    $type = $row["type"];
                    $capacity = $row["capacity"];
                    $manager = $row["manager"];
                    $province = $row["province"];
                    $category = $row["category"];

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
                    <p>Please edit the input values and submit to update the Facility record.</p>
                    <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">
                        <div class="form-group">
                            <label>Facility Name</label>
                            <input type="test" name="name" class="form-control" value="<?php echo $name; ?>"readonly>
                            <span class="invalid-feedback"><?php echo $name; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="test" name="address" class="form-control " value="<?php echo $address; ?>">
                            <span class="invalid-feedback"><?php echo $address; ?></span>

                        </div>
                        <div class="form-group">
                            <label>Phone Number</label>
                            <input type="test" name="phone" class="form-control " value="<?php echo $phone; ?>">
                            <span class="invalid-feedback"><?php echo $phone; ?></span>

                        </div>
                        <div class="form-group">
                            <label>Website</label>
                            <input type="test" name="website" class="form-control " value="<?php echo $website; ?>">
                            <span class="invalid-feedback"><?php echo $website; ?></span>


                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select select class="custom-select" name="type" id="inputGroupSelect01">
                                <option value="hospital">Hospital</option>
                                <option value="clinic">Clinic</option>
                                <option value="special_installment">Special Installment</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $type; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Daily Capacity</label>
                            <input type="numnber" name="capacity" class="form-control " value="<?php echo $capacity; ?>">
                            <span class="invalid-feedback"><?php echo $capacity; ?></span>
                        </div>

                        <div class="form-group">
                            <label>Manager</label>
                            <input type="number" name="manager" class="form-control " value="<?php echo $manager; ?>">
                            <span class="invalid-feedback"><?php echo $manager; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Province</label>
                            <select class="custom-select" id="inputGroupSelect01" name="province">
                                <?php
                                foreach ($all_province as $province) {
                                    echo '<option values=\"' . $province['name'] . '\">' . $province['name'] . '</option>';
                                }
                                ?>
                            </select>
                            <span class="invalid-feedback"><?php echo $province; ?></span>
                        </div>
                        <div class="form-group">
                            <label>Category</label>

                            <select select class="custom-select" name="category" id="inputGroupSelect01">
                                <option value="by_appointment">By Appointment</option>
                                <option value="walk_in">Walk In</option>
                            </select>
                            <span class="invalid-feedback"><?php echo $category; ?></span>
                        </div>

                        <input type="hidden" name="name" value="<?php echo $name; ?>" />
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="facility.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>