<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="./css/style.css" />
    <title>COVID-19 Public Health Care Population Vaccination System</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/bootstrap.theme.min.css">
    <script src="./js/jquery.min.js"></script>
    <script src="./js/bootstrap.min.js"></script>
</head>

<body>

    <!-- Fixed navbar -->
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">Online Attendance</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-right">
                    <li class="active"><a href="#">Home</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <!--/.nav-collapse -->
        </div>
    </nav>
    <div class="container">
        <h2>For Students</h2>
        <h4>Click here for <a href="student.php">Student Dashboard</a></h4>
        <hr>
        <h2>For Faculty</h2>
        <div class="alert alert-warning hidden">
            <span></span>
            <button type="button" class="close" onclick="$('.alert').addClass('hidden');">&times;</button>
        </div>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Login</th>
                    <th>Sign Up</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <form id="login">
                            <div class="form-group">
                                <label>Email ID</label>
                                <input class="form-control" placeholder="Email" type="email" name="email">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" placeholder="Password" type="password" name="password">
                            </div>
                            <button class="btn btn-primary pull-right">Login</button>
                        </form>
                    </td>
                    <td>
                        <form id="signup">
                            <div class="form-group">
                                <label>Name</label>
                                <input class="form-control" placeholder="Name" type="text" name="name">
                            </div>
                            <div class="form-group">
                                <label>Phone Number</label>
                                <input class="form-control" placeholder="Phone" type="text" name="phone">
                            </div>
                            <div class="form-group">
                                <label>Email ID</label>
                                <input class="form-control" placeholder="Email" type="email" name="email">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" placeholder="Password" type="password" name="password">
                                <span class="help-block">Password should be 6 characters long.</span>
                            </div>
                            <div class="form-group">
                                <label>Re-type Password</label>
                                <input class="form-control" placeholder="Re-type Password" type="password" name="password2">
                            </div>
                            <button class="btn btn-primary pull-right">Sign Up</button>
                        </form>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- FOOTER -->
    <footer style="height:120%;">
        <p class="pull-right"><a href="#">Back to top</a></p>
        <p>&copy; 2017 ProjectWorlds, Inc. &middot; developed by <a href="https://facebook.com/yugesh.verma.35">Yugesh Verma </a><a href="http://projectworlds.in">Privacy</a> &middot; <a href="http://projectworlds.in">Terms</a></p>
    </footer>
    </div><!-- /.container -->
</body>

</html>
<?php
require_once('./php/config.php');

$con = connect();
showTables($con);

function showTables($link)
{
    $sql = "SHOW TABLES";
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            echo "<table>";
            echo "<tr>";
            echo "<th>tables</th>";
            echo "</tr>";
            while ($row = mysqli_fetch_array($result)) {
                echo "<tr>";
                echo "<td>" . $row[0] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
            // Free result set
            mysqli_free_result($result);
        } else {
            echo "No records matching your query were found.";
        }
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
}
?>