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
                        <h2 class="pull-left">Age Group</h2>
                        <a href="create.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Age Group</a>
                    </div>
                    <?php
                    // Include config file
                    require_once "../config.php";
                    $link = connect();
                    // Attempt select query execution
                    $sql = "SELECT * FROM age_group ORDER BY age_group_id ASC";
                    if ($result = mysqli_query($link, $sql)) {
                        if (mysqli_num_rows($result) > 0) {
                            echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                            echo "<tr>";
                            echo "<th>Age Goup ID</th>";
                            echo "<th>Min Age</th>";
                            echo "<th>Max Age</th>";
                            echo "<th>Vaccination Date</th>";
                            echo "<th>Action</th>";
                            echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while ($row = mysqli_fetch_array($result)) {
                                echo "<tr>";
                                echo "<td>" . $row['age_group_id'] . "</td>";
                                echo "<td>" . $row['min_age'] . "</td>";
                                echo "<td>" . $row['max_age'] . "</td>";
                                echo "<td>" . $row['vaccination_date'] . "</td>";
                                echo "<td>";
                                echo '<a href="read.php?age_group_id=' . $row['age_group_id'] . '" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                echo '<a href="update.php?age_group_id=' . $row['age_group_id'] . '" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                echo '<a href="delete.php?age_group_id=' . $row['age_group_id'] . '" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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