<script>
function jumpurl(){  
   location='index.php';   //执行跳转页面函数
}  
setTimeout('jumpurl()',3000);  //延时3秒执行跳转页面
</script>
<?php
$str=$_GET["new"];
include("config.php"); //包含数据库连接文件
$sql = "delete from sort where pid=".$str; //搜索数据表
echo $sql;  //显示sql语句以便查找错误
$result=$con->query($sql); //执行查询语句
if(!$result){
echo "delete not!".mysql_error(); //如果出错显示错误
}
else{
echo "<script>alert('删除id= ".$str." 的记录成功！返回查看。');";
echo "</script> "; 
echo "<a href=\"indext.php\">3秒后系统自动跳转，点击本处直接跳转</a> ";   
}
?>
