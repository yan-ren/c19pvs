<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="UTF-8">
<title>分页显示</title>
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

</head>
<body>
<table class="gridtable" width="1100" align="center" bgcolor="#66FFFF" border="1" bordercolor="#000000">
<tr>
<td colspan="25" align="center"><h1>查询数据</h1></td>
</tr>
<tr>
<td  colspan="25" align="center"><a href="indexft.php">查看数据</a> | <a href="insertt.php">添加数据</a> </a> | <a href="selectt.php">查询数据</a></td>
</tr>
<?php
echo '<tr bgcolor="#66CCFF">';
  $strx="pid,姓名,班级,语文,数学,英语,时间";
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
	//require_once "page.class.php";//加载分页工具
	include("config.php"); 
	include("page.php"); 
	$sqlall = "select count(*) from sort";//获取总条数
	$resultall = $con->query($sqlall);
	$arr1 = $resultall->fetch_row();//获取一个数组  只有一个值的数组
	$c = $arr1[0];//用一个变量获取这个数组的值
	$page = new page($c,5);//一共多少条 每页显示多少条
	$sql = "select * from sort " .$page->limit;//拼接sql语句  分页显示
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
?>
    </div>
</body>
 