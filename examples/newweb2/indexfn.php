<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <title>分页显示</title>
    <link href="tongy.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
        <!--
        .STYLE1 {
            font-size: 12px
        }

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
        function locking() {
            document.all.ly.style.display = "block";
            document.all.ly.style.width = document.body.clientWidth;
            document.all.ly.style.height = document.body.clientHeight;
            document.all.Layer2.style.display = 'block';
        }

        function Lock_CheckForm(theForm) {
            document.all.ly.style.display = 'none';
            document.all.Layer2.style.display = 'none';
            return false;
        }

        function show_1(pid) {
            window.open('updaten.php?new=' + pid, '修改数据', 'height=500,width=1000,top=200,left=200,channelmode=1,"",directories=no, toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') //写成一行  
        }

        function show_2(pid) {
            window.open('huifu.php?new=' + pid, '回复留言', 'height=500,width=800,top=200,left=200,channelmode=1,"",directories=no, toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') //写成一行  
        }

        function Checkshan() {
            alert("你无权删除！");
        }

        function del(id) {
            if (false === confirm('是否真的要删除id=' + id + '记录?')) return;
            location.href = 'deleten.php?new=' + id;
        }
    </script>

</head>

<body>
    <table class="gridtable" width="1100" align="center" bgcolor="#66FFFF" border="1" bordercolor="#000000">
        <tr>
            <td colspan="25" align="center">
                <h1>非预约人员信息</h1>
            </td>
        </tr>
        <tr>
            <td colspan="25" align="center"><a href="indexfn.php">查看数据</a> | <a href="insertn.php">添加数据</a> </a> | <a href="selectn.php">查询数据</a></td>
        </tr>
        <tr bgcolor="#66CCFF">
            <th width="100" align="center">pid</th>

            <th width="100" align="center">其护照号码</th>

            <th width="100" align="center">名字</th>

            <th width="100" align="center">姓氏</th>

            <th width="100" align="center">中间名首字母</th>

            <th width="100" align="center">出生日期</th>

            <th width="100" align="center">电话号码</th>

            <th width="100" align="center">地址</th>

            <th width="100" align="center">城市</th>

            <th width="100" align="center">省份</th>

            <th width="100" align="center">邮政编码</th>

            <th width="100" align="center">国籍</th>

            <th width="100" align="center">电子邮件地址</th>
            <th width="100" align="center">修改</th>
            <th width="100" align="center">删除</th>
        </tr>
        <tr>
            ﻿
        <tr>
            <td>1</td>
            <td>z6789</td>
            <td>asd</td>
            <td>tyu</td>
            <td>t</td>
            <td>2021-11-09 23:13:21</td>
            <td>999789666</td>
            <td>北京教育文化有限公司</td>
            <td>10</td>
            <td>hlj</td>
            <td>123</td>
            <td></td>
            <td></td>
            <td width='60' align='center' bgcolor='#66CCFF'><input name='xugai' type='button' value='修改&nbsp;' onclick="show_1(1)" /></td>
            <td width='60' align='center' bgcolor='#66CCFF'><input name='del' type='button' value='删除&nbsp;' onclick='del(1)' /></td>
        </tr>
        <tr>
            <td>2</td>
            <td>zhang</td>
            <td>666</td>
            <td>1</td>
            <td>1356868</td>
            <td>2021-11-09 23:14:28</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td width='60' align='center' bgcolor='#66CCFF'><input name='xugai' type='button' value='修改&nbsp;' onclick="show_1(2)" /></td>
            <td width='60' align='center' bgcolor='#66CCFF'><input name='del' type='button' value='删除&nbsp;' onclick='del(2)' /></td>
        </tr>
        <tr>
            <td>3</td>
            <td>zhang</td>
            <td>666</td>
            <td>1</td>
            <td>1356868</td>
            <td>2021-11-10 21:24:51</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td width='60' align='center' bgcolor='#66CCFF'><input name='xugai' type='button' value='修改&nbsp;' onclick="show_1(3)" /></td>
            <td width='60' align='center' bgcolor='#66CCFF'><input name='del' type='button' value='删除&nbsp;' onclick='del(3)' /></td>
        </tr>
    </table>
    <br />
    <div align='center'>
        <div style="font:12px '\5B8B\4F53',san-serif;"><span class='p1'> 共<b> 3 </b>条记录 </span> 本页 <b>3</b> 条 本页从 <b>1-3</b> 条 <b>1/1</b>页 <b></b></div>
    </div>
    </div>
</body>