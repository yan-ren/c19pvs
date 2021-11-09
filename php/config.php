<?php
require_once('db_constant.php');

function connect()
{
    $link = false;
    if ($_SERVER["REMOTE_ADDR"] == "127.0.0.1") {
        /* Attempt to connect to local MySQL database */
        $link = mysqli_connect(DB_SERVER_LOCAL, DB_USERNAME_LOCAL, DB_PASSWORD_LOCAL, DB_NAME_LOCAL);
    } else {
        /* Attempt to connect to remote MySQL database */
        $link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
    }

    // Check connection
    if ($link === false) {
        die("ERROR: Could not connect. " . mysqli_connect_error());
    }

    return $link;
}
