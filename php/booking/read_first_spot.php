<?php
// Include config file
require_once "../config.php";
$link = connect();
$facility_name = $start_date = "";

$facility_name = trim($_GET['facility_name']);
$start_date = trim($_GET['start_date']);

/*
find on which day there are nurses working, for each day that has nurse working, find slot is not booked yet
get all nurses in facility whose finish time is after start day
1. query heathcare_work_assignment role = 'nurse' whose end date > given start day, get list of worker assignment
2. in the list, calculate the min date and max date
3. from min date to max date, query how many nurses on each day, which is the capacity for each slots
3.1 query open hour on that day, calculate slots
3.2 for each slots query booking if not exceed max, that's the first available booking
*/

// Close statement
mysqli_stmt_close($stmt);
// Close connection
mysqli_close($link);
