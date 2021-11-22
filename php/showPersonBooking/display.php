<?php
// Include config file
require_once "../config.php";
$link = connect();

//sql results array
$booking_rows = $infection_rows = $vaccination_rows = array();

$person_id = "";
$person_id_error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $person_id = (int)trim($_POST["personId"]);

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
        $sql = "SELECT 
                       p.first_name, p.last_name, b.facility_name,f.address,b.date,b.time
                        
                        FROM booking as b
                        INNER JOIN person as p on p.person_id = b.person_id
                        INNER JOIN facility as f on f.`name` = b.facility_name
                        where b.person_id = ?
                        ";

        $stmt = mysqli_prepare($link, $sql);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $person_id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $booking_rows[] = mysqli_fetch_array($result);
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

        $sql2 = "SELECT 
        p.first_name,p.last_name,inf.date,covid.variant
        FROM 
        infection as inf,
        covid,
        person as p 
        WHERE p.person_id = inf.person_id AND 
        inf.covid_id = covid.covid_id
        AND p.person_id =?";

        $stmt = mysqli_prepare($link, $sql2);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $person_id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $infection_rows[] = mysqli_fetch_array($result);
                    mysqli_free_result($result);
                } else {
                    echo '<div class="alert alert-danger"><em>Infection record is not found.</em></div>';
                }
            } else {
                echo "SQL query error: " . mysqli_stmt_errno($stmt);
            }
        } else {
            echo "Prepare SQL error: " . $link->error;
        }

        $sql3 = "SELECT 
        p.first_name,p.last_name,
        v.vaccine_name,v.dose,v.date,
        v.lot,v.location,v.city,v.province,v.country
        
        FROM vaccination as v
        INNER JOIN person as p on p.person_id =v.person_id
        where p.person_id = ?;";

        $stmt = mysqli_prepare($link, $sql3);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "i", $person_id);
            if (mysqli_stmt_execute($stmt)) {
                $result = mysqli_stmt_get_result($stmt);
                if (mysqli_num_rows($result) == 1) {
                    $vaccination_rows[] = mysqli_fetch_array($result);
                    mysqli_free_result($result);
                } else {
                    echo '<div class="alert alert-danger"><em>Vaccination record is not found.</em></div>';
                }
            } else {
                echo "SQL query error: " . mysqli_stmt_errno($stmt);
            }
        } else {
            echo "Prepare SQL error: " . $link->error;
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

                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

                        <div class="input-group mb-3">
                            <input type="text" name="personId" class="form-control <?php echo (!empty($person_id_error)) ? 'is-invalid' : '' ?>" placeholder="Please enter a Person ID" aria-describedby="button-addon2" value="<?php echo $person_id ?>">
                            <span class="invalid-feedback"><?php echo $person_id_error; ?></span>

                            <button class="btn btn-sm btn-outline-secondary" type="submit">Search</button>

                        </div>
                </div>
            </div>
            </form>
            <table class="table table-bordered table-striped">
                <h4>Display Person Bookings Info</h4>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Facility Name</th>
                        <th>Facility Address</th>
                        <th>Date</th>
                        <th>Time Slot</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($booking_rows as $booking) {
                        echo "<tr>";
                        echo "<td>" . $booking['first_name'] . "</td>";
                        echo "<td>" . $booking['last_name'] . "</td>";
                        echo "<td>" . $booking['facility_name'] . "</td>";
                        echo "<td>" . $booking['address'] . "</td>";
                        echo "<td>" . $booking['date'] . "</td>";
                        echo "<td>" . $booking['time'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <table class="table table-bordered table-striped">
                <h4>Display Person Infection Info</h4>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Infection Date</th>
                        <th>Covid Variant Name</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($infection_rows as $infection) {
                        echo "<tr>";
                        echo "<td>" . $infection['first_name'] . "</td>";
                        echo "<td>" . $infection['last_name'] . "</td>";
                        echo "<td>" . $infection['date'] . "</td>";
                        echo "<td>".(empty($infection["variant"]) ? 'UNKNOWN': $infection['variant']) . "</td>";                  
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <table class="table table-bordered table-striped">
                <h4>Display Person Vaccination Info</h4>
                <thead>
                    <tr>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Vaccine Name</th>
                        <th>Dose</th>
                        <th>Date</th>
                        <th>Lot Number</th>
                        <th>Location</th>
                        <th>City</th>
                        <th>Province</th>
                        <th>Country</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($vaccination_rows as $vac) {
                        echo "<tr>";
                        echo "<td>" . $vac['first_name'] . "</td>";
                        echo "<td>" . $vac['last_name'] . "</td>";
                        echo "<td>" . $vac['vaccine_name'] . "</td>";
                        echo "<td>" . $vac['dose'] . "</td>";
                        echo "<td>" . $vac['date'] . "</td>";
                        echo "<td>" . $vac['lot'] . "</td>";
                        echo "<td>" . $vac['location'] . "</td>";
                        echo "<td>" . $vac['city'] . "</td>";
                        echo "<td>" . $vac['province'] . "</td>";
                        echo "<td>" . $vac['country'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>

        </div>
    </div>
    </div>
</body>

</html>