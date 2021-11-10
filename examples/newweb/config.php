<?php
$db = "knc353_2";
$con = mysqli_connect("127.0.0.1:3306", "test", "password", $db);
// 检查连接 
if (!$con) {
    die("连接错误: " . mysqli_connect_error());
}
mysqli_query($con, 'set names utf8');
