<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<title>查询信息</title>
<link href="tongy.css" rel="stylesheet" type="text/css" /> 
<style type="text/css"> 
.div1{
	width:900px;
	margin:0 auto;
}
.STYLE1 {font-size: 12px}
a:link {
color: #FFFFFF;
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
  float:left;width:450px;  
  }
-->
</style>

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

function show_1(pid) { 
<!--  
window.open('updatet.php?new='+pid,'修改数据','height=500,width=1000,top=200,left=200,channelmode=1,"",directories=no, toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no' )  //写成一行  
-->  
}
function show_2(pid) { 
<!--  
window.open('huifu.php?new='+pid,'回复留言','height=500,width=800,top=200,left=200,channelmode=1,"",directories=no, toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no' )  //写成一行  
-->  
}
function Checkshan(){
     alert("你无权删除！");
 }
function del(id){
     if (false === confirm('是否真的要删除当前记录?') )return;
     location.href = 'deletet.php?new='+id;
 }
</script>
<?php
if(isset($_POST['button'])){//
include("config.php"); //引入数据库连接文件
include("page.php");  //引入数据库连接文件
$strx="";

// 以下查询表达式 可以 根据需求和数据类型参考修改
$s0= $_POST["name0"];
$s0= $_POST["name0"];
if($s0!=""){$strx="and pid='$s0' ";}
$s1= $_POST["name1"];
if($s1!=""){$strx="and str_1x='$s1' ";}
$s2= $_POST["name2"];
if($s2!=""){$strx="and str_2x='$s2' ";}
$s3= $_POST["name3"];
if($s3!=""){$strx="and int_3x='$s3' ";}
$s4= $_POST["name4"];
if($s4!=""){$strx="and int_4x='$s4' ";}
$s5= $_POST["name5"];
if($s5!=""){$strx="and int_5x='$s5' ";}
$s6= $_POST["name6"];
if($s6!=""){$strx="and tim_6t='$s6' ";}

if($strx!=""){$strx="where ".$strx;
$strx=str_replace("where and","where ",$strx);
}
$sql ="select * from sort ".$strx." order by pid desc ";
echo $sql;
  //循环显示数据查询结果
$result = $con->query($sql);
 echo '<table class="gridtable" width=60% align="center">';	
 echo '<tr bgcolor="#66CCFF">';
  $strx="pid,姓名,班级,语文,数学,英语,时间";
  $stry=explode(",",$strx);
for($si=0;$si<count($stry);$si++){
	
echo '<th width="100" align="center">'.$stry[$si].'</th>';
}
echo '<th width="100" align="center">修改</th>';
echo '<th width="100" align="center">删除</th>';
echo '</tr>';
$resultall = $con->query($sql);
	$arr1 = $resultall->fetch_row();//获取一个数组  只有一个值的数组
	$c = $arr1[0];//用一个变量获取这个数组的值
	$page = new page($c,5);//一共多少条 每页显示多少条
	$sql = $sql.$page->limit;//拼接sql语句  分页显示
	$result = $con->query($sql);
    $cols=$result->field_count;
	if($result){
		$arr = $result->fetch_all();
		foreach($arr as $v){	
			echo "<tr>";
		for($i=0;$i<$cols;$i++){
	echo "<td>{$v[$i]}</td>";
	}
echo "<td width='60' align='center' bgcolor='#66CCFF'><input name='xugai' type='button' value='修改&nbsp;' onclick=\"show_1(".$v[0].")\" /></td>"."<td width='60' align='center' bgcolor='#66CCFF'><input name='del' type='button' value='删除&nbsp;' onclick='del(".$v[0].")' /></td></tr>";
		}
	}
?>
    </table>
    <br />
<?php
	echo "<div align='center'>{$page->fpage()}</div>";//显示分页信息

}
else{
?>
<table class="gridtable" width=60% align="center">
<tr>

<td colspan="5" align="center"><h1>查询数据</h1></td>
</tr>
<tr>
<td colspan="5" align="center"><a href="indext.php">查看数据</a> | <a href="insertt.php">添加数据</a></td>
</tr>

<tr>
<td>
<div class="div1">

<form name="form1" method="post" action=""  onsubmit="return CheckForm();">
<p><span>pid<input name="name0" type="text" id="name0" size="40" value=""></span>姓名<input name="name1" type="text" id="name1" size="40" value=""></p>
<p><span>班级<input name="name2" type="text" id="name2" size="40" value=""></span>语文<input name="name3" type="text" id="name3" size="40" value=""></p>
<p><span>数学<input name="name4" type="text" id="name4" size="40" value=""></span>英语<input name="name5" type="text" id="name5" size="40" value=""></p>
<p><span>时间<input name="name6" type="text" id="name6" size="40" value=""></span>

<p><input type="submit" name="button" id="button" value="提&nbsp;&nbsp;交&nbsp;" >                 
<input type="reset" name="button2" id="button2" value="重&nbsp;&nbsp;置&nbsp;">
</p>
</form>

</td>
</tr>
</div>
</table>


<?php
}
?>
</body>
</html>
