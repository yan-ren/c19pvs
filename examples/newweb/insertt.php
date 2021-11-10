<!DOCTYPE html>
<html lang="zh">

<head>
  <meta charset="UTF-8">
  <title>添加数据</title>
  <style type="text/css">
    <!--
    .div1 {
      width: 800px;
      margin: 0 auto;
    }

    .STYLE1 {
      font-size: 12px
    }

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

    span {
      float: left;
      width: 400px;
    }
    -->
  </style>
  <link href="tongy.css" rel="stylesheet" type="text/css" />
  <!--<script type="text/javascript">
 function CheckForm()
{
var name1=document.getElementById('name1').value;
if(name1=="")
{
alert('请输入姓和名！');
document.getElementById('name1').focus();
return false;
}

var name2=document.getElementById('name2').value;
if(name2=="")
{
alert('请输入Email！');
document.getElementById('name2').focus();
return false;
}
else{
	 var email=name2;
       if(email.search(/^([a-zA-Z0-9]+[_|_|.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|_|.]?)*[a-zA-Z0-9]+\.(?:com|cn)$/)!=-1){
       redflag=0;      
       }
       else{    
       alert("邮箱名填写错误，请修改！"); 
       redflag=1;
       return false;        
       }   
  }

var name3=document.getElementById('name3').value;  
if(name3=="")
{
alert('请输入电话！');
document.getElementById('name3').focus();
return false;
}
else{
    if(!(/^1[3|5][0-9]\d{4,8}$/.test(name3))){ 
        alert("手机号输入错误！"); 
        document.getElementById('name3').focus();
        return false; 
	}	
}

if(document.getElementById('name5').value=="")
{
alert('留言不能为空！');
document.getElementById('name5').focus();
return false;
}
return true;
}
 </script>-->
  <?php
  //////////////////////////////////////
  //测试步骤：1.测试数据库连接文件config.php检查数据库名称，用户名，密码
  // 2.测试表单显示是否正常；
  // 3.测试表单提交的数据是否能到后台：方法：在获取post后echo表单传来的数据；
  // 4.测试o表单传来的数据是否能保存到数据库
  //
  ////////////////////////
  include("config.php");

  $shuju = array(1, 'zhang', '666', '1', '1356868', '', '', '', '', '', '', '', '', '');

  //./include("config.php");	
  if (isset($_POST["button"])) { //post 值
    $name1 = $_POST["name1"];
    $name2 = $_POST["name2"];
    $name3 = $_POST["name3"];
    $name4 = $_POST["name4"];
    $name5 = $_POST["name5"];
    $name6 = $_POST["name6"];



    $sql = "insert into sort values(null,'$name1','$name2','$name3','$name4','$name5','$name6')";

    $r1 = mysqli_query($con, $sql);
    if ($r1) {
      echo "<script>alert('提交成功！返回查看。');location.href='indext.php';</script>";
    } else {
      echo "<script>alert('提交不成功！返回添加页。');location.href='insertt.php';</script>";
    }
  }


  ?>
</head>

<body text="#330099" style="background-color: #0FF; font-size:16px; ">
  <div style="width:1000px;margin:0 auto;text-align:center;border:5px solid green; background-color:#efefef;border-radius:25px;">
    <table class="gridtable" width=75% align="center">
      <tr>

        <td colspan="2" align="center">
          <h1>**添加数据**</h1>
        </td>
      </tr>
      <tr>
        <td width="586"><a href="index添加数据.php">查看数据</a> | <a href="insert添加数据.php">添加数据</a></td>
      </tr>

      <tr>
        <td>
          <div class="div1">
            <form name="form1" method="post" action="" size=90% onsubmit="return CheckForm();">

              <p><span>姓名<input name="name1" type="text" id="name1" size="40" value="<?php echo $shuju[1]; ?>">*</span>班级<input name="name2" type="text" id="name2" size="40" value="<?php echo $shuju[2]; ?>">*</p>
              <p><span>语文<input name="name3" type="text" id="name3" size="40" value="<?php echo $shuju[3]; ?>">*</span>数学<input name="name4" type="text" id="name4" size="40" value="<?php echo $shuju[4]; ?>">*</p>
              <p><span>英语<input name="name5" type="text" id="name5" size="40" value="<?php echo $shuju[5]; ?>">*</span>时间<input name="name6" type="text" id="name6" size="40" value="<?php echo $shuju[6]; ?>">*</p>


              <!-- <textarea name="id1" id="id1" cols="45" rows="5">这个文本域是为了修改而设置的，如果不需要可以删除</textarea>-->
              <p><input type="submit" name="button" id="button" value="提&nbsp;&nbsp;交&nbsp;">
                <input type="reset" name="button2" id="button2" value="重&nbsp;&nbsp;置&nbsp;">
              </p>
            </form>
          </div>
        </td>
      </tr>
    </table>
  </div>
</body>

</html>