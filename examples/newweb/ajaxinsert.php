<?php
	ob_start();
	session_start();
require("../hout/stxinxi/config.php");
//if(isset($_POST["button"])){
//pid,usreid,昵称,头像代号,标题,留言,留言时间,备注
$name2="";
$name2=$_SESSION["name"];
$name1=$_POST["pid"];
$name3=$_POST["user_imag"]; 
$name4="";
$name5=nl2br($_POST["usertext"]);
$name6=date('Y-m-d H:i:s');
$name7="";


$sql="insert into liuyanhui0 values(null,'$name1','$name2','$name3','$name4','$name5','$name6','$name7')";
mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER_SET_CLIENT=utf8");
$pan=mysql_query($sql);
if($pan){
echo "回复者：".$name2."      回复留言<br>".$name5;
}
else{ 
echo "<script>alert('ajax提交不成功返回查看。');<a href='allliuyan.php'></script>";
}
//}

?>
