<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>回复留言</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
 <link href="tongy.css" rel="stylesheet" type="text/css" /> 
   
<style type="text/css"> 
<!--
.STYLE1 {font-size: 12px}
a:link {
color: #000FF;
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
.div1{
	width:1000px;
	height:inherit;
    margin:0 auto;
	background-color:#c3c3c3;
	border-style:solid;
	border-width:3px;
	border-color:#660066 #006666;
}
.div2{
	width:100px;
	heght:20px;
}
.divliu{
	margin:0 auto;
	width:900px;
	line-height:100%;
	background-color:#e3e3e3;
    text-align:left;
	color:# 9C0;
	margin:1px,50px;
	}
.divliu1{
	margin:0 auto;
	width:900px;
	line-height:100%;
	background-color:#e4e3e3;
    text-align:left;
	color:# 9C0;
	padding:1px,100px;
	}-->
</style>
 <script type="text/javascript" src="Js/jquery.min.js"></script>
 <!--/*<script type="text/javascript" src="Js/jquery.1.4.2.min.js"></script>*/-->
<script language="javascript">   
function    locking(){
document.all.ly.style.display="block";
document.all.ly.style.width=document.body.clientWidth;
document.all.ly.style.height=document.body.clientHeight;
document.all.Layer2.style.display='block';
}
function    Lock_CheckForm(theForm){
document.all.ly.style.display='none';document.all.Layer2.style.display='none';
return   false;
}

function CloseWin() //这个不会提示是否关闭浏览器     
{     
window.opener=null;     
//window.opener=top;     
window.open("","_self");     
window.close();     
}
function yanchi()
{
setTimeout("window.location.href='ajaxti.php'",2000);
}

function show_1(pid) { 
<!--  
window.open('update1.php?new='+pid,'修改数据','height=500,width=1000,top=200,left=200,channelmode=1,"",directories=no, toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no' )  //写成一行  
-->  
}
function show_2(pid) { 
<!--  
window.open('huifu.php?new='+pid,'回复留言','height=500,width=800,top=200,left=200,channelmode=1,"",directories=no, toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no' )  //写成一行  
-->  
}

function saveUserInfo()
{
//获取表单对象和用户信息值
//var userName =document.getElementById("pid1").value;
var pid =document.getElementById("pid").value;
var usertext =document.getElementById("name6").value;
var   user_imag=document.getElementById("pid1").value;
 //alert(userName);
//接收表单的URL地址
var url = "ajaxinsert0.php"; 
//需要POST的值，把每个变量都通过&来联接
var postStr  ="usertext="+ usertext+"&pid="+pid+"&user_imag="+pid1;
    //alert('123');
	 var ajax = false;
     if(window.XMLHttpRequest) { //Mozilla 浏览器
         ajax = new XMLHttpRequest();
         if (ajax.overrideMimeType) {//设置MiME类别
             ajax.overrideMimeType("text/xml");
         }
     }
     else if (window.ActiveXObject) { // IE浏览器
         try {
             ajax = new ActiveXObject("Msxml2.XMLHTTP");
         } catch (e) {
             try {
                 ajax = new ActiveXObject("Microsoft.XMLHTTP");
             } catch (e) {}
         }
     }
     if (!ajax) { // 异常，创建对象实例失败
         window.alert("不能创建XMLHttpRequest对象实例.");
         return false;
     }
ajax.open("POST", url, true); 
ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
ajax.send(postStr);

alert('提交shuju成0功。'); 
//获取执行状态
ajax.onreadystatechange = function() {
  if (ajax.readyState == 4 && ajax.status == 200) {
 //alert('提交成0功。');
   var strs=ajax.responseText//字符分割
   reslut.innerHTML +=strs;  
  }
 /* window.location.href = "allliuyan.php";
setTimeout('fresh_page()',1000); */
}
  
}
function fresh_page()   
{
window.location.reload();
}
</script>
<?php
require("../hout/stxinxi/config.php"); //引入数据库连接文件
$id=$_GET['new'];
$sql ="select * from %%strx_1 where pid=".$id; //查询语句倒序排列
//mysql_query("SET NAMES 'utf8'");
mysql_query("SET CHARACTER_SET_RESULTS=UTF8"); 
$resule = mysql_query($sql);
?>
</head>
<body>
<div class="div1">
<h1 align="center">回 复 留 言</h1>
<p align="center"><a href="allliuyan.php">查看留言</a> | <a href="select0.php">查询留言</a></p>
<div class="divliu">
<?php  //循环显示数据查询结果
while($row=mysql_fetch_array($resule))
{
?>
<div class="divliu">
<!--<div class="div2" id="pid">： <?php  echo  $row[0]; ?></div>	-->
<input  type="hidden" name="pid" id="pid" value=" <?php  echo  $row[0]; ?>" />
<input  type="hidden" name="pid1" id="pid1" value=" <?php  echo  $row[1]; ?>" />
<!--//pid,usreid,昵称,头像代号,标题,留言,留言时间,备注-->
用户名： <?php  echo  $row[3]; ?>//se tion
昵 称： <?php  echo  $row[2]; ?>
头像：<img src="face/25.gif" width="20" height="18" alt="222" /><?php  echo  $row[1]; ?>

留言时间：<?php  echo  $row[11]; ?>
</div>
<div class="divliu"><p>标题:<?php echo $row[7]; ?></P>
<P>副标题:<?php echo $row[8]; ?></P>
<p><?php echo $row[9]; ?></p></div>
<div class="divliu1" id="reslut" ><?php echo $row[9]; ?>"<br /><br />

<?php
   	 $uid=$row[0];
     $sql2 ="select * from %%strx_2 where str_usreid=".$uid; //查询语句倒序排列
     mysql_query("SET CHARACTER_SET_RESULTS=UTF8"); 
     $resule2 = mysql_query($sql2);
    
	if($resule2){
	while($row1=mysql_fetch_array($resule2)){
         echo  "<p align='left' >留言者 :".$row1[2]."<img src='you.png' width='20' height='18' alt='222' /></p>";  
		 echo  "<p align='left' style='line-height:200%;' >".$row1[5]."</p>"; 
		 echo  "<p align='right' >回复留言时间：".$row1[6]."<p>"; 
    }
	}

?>
</div>
</div>

<?php
}
?>


<divclass="divliu1">
<form name="form1" >
<p align="center"><textarea  name="name6" id="name6" style="  background-color:#c121212;font-size:20px"  rows="5" cols="88"></textarea></p>
<p align="center"><input type="button" name="button" id="button" value="&nbsp;提&nbsp;&nbsp;交&nbsp;&nbsp;" onclick="saveUserInfo();">                 
<input type="reset" name="button2" id="button2" value="&nbsp;重&nbsp;&nbsp;置&nbsp;">
<a href="allliuyan.php">返回查看</a></samp>>
</form>
</div1>

</body>
</html>

