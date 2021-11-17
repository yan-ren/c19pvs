<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Person</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
    <style>
        table tr td:last-child {
            width: 120px;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>

<body>
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="mt-5 mb-3 clearfix">
                    <h2 class="pull-left">Person</h2>
                    <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Person</a>
                </div>
                <?php
                // Include config file
                require_once "../config.php";
                $link = connect();
                // Attempt select query execution
                $sql = "SELECT * FROM person ORDER BY person_id ASC ";
                if ($result = mysqli_query($link, $sql)) {
                    if (mysqli_num_rows($result) > 0) {
                        echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                        echo "<tr>";
                        echo "<th>Person ID</th>";
                        echo "<th>First Name</th>";
                        echo "<th>Middle Name</th>";
                        echo "<th>Last Name</th>";
                        echo "<th>Date Of Birth</th>";
                        echo "<th>Medicare Card Number</th>";
                        echo "<th>Date Of Issue Of Medicare Card</th>";
                        echo "<th>Date Of Expiry Of The Medicare Card</th>";
                        echo "<th>Phone</th>";
                        echo "<th>Address</th>";
                        echo "<th>City</th>";
                        echo "<th>Province</th>";
                        echo "<th>Postal_Code</th>";
                        echo "<th>Citizenship</th>";
                        echo "<th>Email</th>";
                        echo "<th>Passport Number</th>";
                        echo "<th>Age Group ID</th>";
                        echo "<th>Registered</th>";
                        echo "<th>Status</th>";
                        echo "<th>Action</th>";
                        echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr>";
                            echo "<td>" . $row['person_id'] . "</td>";
                            echo "<td>" . $row['first_name'] . "</td>";
                            echo "<td>" . $row['middle_name'] . "</td>";
                            echo "<td>" . $row['last_name'] . "</td>";
                            echo "<td>" . $row['date_of_birth'] . "</td>";
                            echo "<td>" . $row['medicare_card_number'] . "</td>";
                            echo "<td>" . $row['date_of_issue_of_medicare_card'] . "</td>";
                            echo "<td>" . $row['date_of_expiry_of_the_medicare_card'] . "</td>";
                            echo "<td>" . $row['phone'] . "</td>";
                            echo "<td>" . $row['address'] . "</td>";
                            echo "<td>" . $row['city'] . "</td>";
                            echo "<td>" . $row['province'] . "</td>";
                            echo "<td>" . $row['postal_code'] . "</td>";
                            echo "<td>" . $row['citizenship'] . "</td>";
                            echo "<td>" . $row['email'] . "</td>";
                            echo "<td>" . $row['passport_number'] . "</td>";
                            echo "<td>" . $row['age_group_id'] . "</td>";
                            echo "<td>" . $row['registered'] . "</td>";
                            echo "<td>" . $row['status'] . "</td>";
                            echo "<td>";
                            echo '<a href="read.php?person_id=' . $row['person_id'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                            echo '<a href="update.php?person_id=' . $row['person_id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                            echo '<a href="delete.php?person_id=' . $row['person_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                            echo "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                        echo "</table>";
                        // Free result set
                        mysqli_free_result($result);
                    } else {
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else {
                    echo "Oops! Something went wrong. Please try again later.";
                }

                // Close connection
                mysqli_close($link);
                ?>
                <p class="pull-right"><a href="../../manage.php">Back</a></p>
            </div>
        </div>
    </div>
</div>
</body>

</html>