<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<title>首页</title>

 <link href="tongy.css" rel="stylesheet" type="text/css" /> 
   
<style type="text/css"> 
<!--
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
     if (false === confirm('是否真的要删除id='+id+'记录?') )return;
     location.href = 'deletet.php?new='+id;
 }
</script>
<?php
include("config.php"); //引入数据库连接文件
$sql ="select * from sort order by pid desc"; //查询语句倒序排列
?>
</head>
<body>
<table class="gridtable" width="1200" align="center" bgcolor="#66FFFF" border="1" bordercolor="#000000">
<tr>
<td colspan="12" align="center"><h1>查询数据</h1></td>
</tr>
<tr>
<td  colspan="12" align="center"><a href="indext.php">查看数据</a> | <a href="insertt.php">添加数据</a> </a> | <a href="indexft.php">分页显示</a>| <a href="selectt.php">查询数据</a></td>
</tr>

<?php

echo '<tr bgcolor="#66CCFF">';
  $strx="pid,姓名,班级,语文,数学,英语,时间";    //表格中文字段名
  $stry=explode(",",$strx);
for($si=0;$si<count($stry);$si++){
?>	
<th width="100" align="center"><?php echo $stry[$si];?></th>
<?php
}
?>

<th width="100" align="center">修改</th>
<th width="100" align="center">删除</th>
</tr><tr>
<?php  //循环显示数据查询结果

$result = $con->query($sql);
if($result){
$cols=$result->field_count;		
while($row =$result->fetch_array()) {
	echo '<tr>';	
     for($i=0;$i<$cols;$i++){
	 echo '<td width="100" bgcolor="#66CCFF" align="center">'; 
     echo $row[$i]."</td>";
	 }
echo '<td width="60" align="center" bgcolor="#66CCFF"><input name="xugai" type="button" value="修改&nbsp;" onclick="show_1('.$row[0].')" /></td>';  
echo '<td width="60" align="center" bgcolor="#66CCFF"><input name="del" type="button" value="删除&nbsp;" onclick="del('.$row[0].')"/></td>';

}
echo "</tr>";
}

?>
</table>
</body>
</html>