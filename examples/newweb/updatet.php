<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<title>修改数据</title>
<link href="tongy.css" rel="stylesheet" type="text/css" /> 
<style type="text/css"> 
  <!--
  .STYLE1 {font-size: 12px}
  a:link {
  color: #000;
  text-decoration: none;
  }
  a:visited {
  text-decoration: none;
  }
  a:hover {
  text-decoration: none;
  }
  a:active {
  text-decoration: none;
  }
   span{
  float:left;width:300px;  
  }
  -->
  </style>
  
<script language="javascript">   
function CloseWin() //这个不会提示是否关闭浏览器     
{     
window.opener.location.reload(); //刷新父窗口中的网页（用于框架
window.close();//关闭当前窗窗口      
}
function yanchi()
{
setTimeout("window.location.href='index.php'",2000);
}
</script>

<?php
include("config.php"); //引入数据库连接文件
if(isset($_POST["button1"])){
	$id= $_POST['shuj0'];
$name1= $_POST["name1"];
$name2= $_POST["name2"];
$name3= $_POST["name3"];
$name4= $_POST["name4"];
$name5= $_POST["name5"];
$name6= $_POST["name6"];



  //返回post值；

$showtime=date("Y-m-d H:i:s");
echo $showtime=date("Y-m-d H:i:s");
echo "<br>";

$sql="update sort set str_1x='$name1',str_2x='$name2',int_3x='$name3',int_4x='$name4',int_5x='$name5',tim_6t='$name6' where pid=".$id;

 
echo $sql;
$pan=$con->query($sql);
if(!$pan){
echo "<script>alert('提交不成功！返回。');window.history.back(-1);</script>";
}
else{

 echo "<script>alert('提交成功！返回首页。');CloseWin();</script>";
}
}

$id= $_GET['new'];
//echo  $id."--8888";
$sql ="select * from sort where pid=".$id; //3：数据表名；
//mysql_query("SET NAMES 'utf8'");
$result = $con->query($sql);
?>
</head>
<body>

<h1 align="center">修 改 数 据</h1>
<form id="form1" name="form1" method="post" action="">
<table class="gridtable" width='600' border='0' align='center' cellpadding='1'  bgcolor="#00FFCC" >
<tr><td>
<?php
if ($result->num_rows > 0) {
    // 输出数据
   while($row =$result->fetch_array()) {	
?>
<input type="hidden"  name="shuj0"  value="<?php echo  $row['pid']; ?>" />
<p><span>姓名&nbsp;:<input type="text" name="name1"  value="<?php echo  $row[1]; ?>" ></span>
班级&nbsp;:<input type="text" name="name2"  value="<?php echo  $row[2]; ?>" ></p><p><span>语文&nbsp;:<input type="text" name="name3"  value="<?php echo  $row[3]; ?>" ></span>
数学&nbsp;:<input type="text" name="name4"  value="<?php echo  $row[4]; ?>" ></p><p><span>英语&nbsp;:<input type="text" name="name5"  value="<?php echo  $row[5]; ?>" ></span>
时间&nbsp;:<input type="text" name="name6"  value="<?php echo  $row[6]; ?>" ></p>

 

<?php
}
}
?>
</td>
</tr>
  <tr>
  <td>
  <input type="submit" name="button1" id="button1" value="提&nbsp;&nbsp;交&nbsp;" >
  </td>
  </tr>
  </table>
</form>
</body>
</html>

