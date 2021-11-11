<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <title>首页</title>

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
            window.open('updatee.php?new=' + pid, '修改数据', 'height=500,width=1000,top=200,left=200,channelmode=1,"",directories=no, toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') //写成一行  
        }

        function show_2(pid) {
            window.open('huifu.php?new=' + pid, '回复留言', 'height=500,width=800,top=200,left=200,channelmode=1,"",directories=no, toolbar=no,menubar=no,scrollbars=no, resizable=no,location=no, status=no') //写成一行  
        }

        function Checkshan() {
            alert("你无权删除！");
        }

        function del(id) {
            if (false === confirm('是否真的要删除id=' + id + '记录?')) return;
            location.href = 'deletee.php?new=' + id;
        }
    </script>
    ﻿
</head>

<body>
    <table class="gridtable" width="1200" align="center" bgcolor="#66FFFF" border="1" bordercolor="#000000">
        <tr>
            <td colspan="12" align="center">
                <h1>查询数据</h1>
            </td>
        </tr>
        <tr>
            <td colspan="12" align="center"><a href="indexe.php">查看数据</a> | <a href="inserte.php">添加数据</a> </a> | <a href="indexfe.php">分页显示</a>| <a href="selecte.php">查询数据</a></td>
        </tr>

        <tr bgcolor="#66CCFF">
            <th width="100" align="center">pid</th>

            <th width="100" align="center">单位名称</th>

            <th width="100" align="center">单位地址</th>

            <th width="100" align="center">单位电话</th>

            <th width="100" align="center">单位网址</th>

            <th width="100" align="center">类别</th>

            <th width="100" align="center">最多位置</th>

            <th width="100" align="center">服务时间</th>

            <th width="100" align="center">公共卫生工作者</th>

            <th width="100" align="center">负责经理</th>

            <th width="100" align="center">备注
            </th>

            <th width="100" align="center">修改</th>
            <th width="100" align="center">删除</th>
        </tr>
        <tr>
        <tr>
            <td width="100" bgcolor="#66CCFF" align="center">2</td>
            <td width="100" bgcolor="#66CCFF" align="center">zhang</td>
            <td width="100" bgcolor="#66CCFF" align="center">666</td>
            <td width="100" bgcolor="#66CCFF" align="center">11</td>
            <td width="100" bgcolor="#66CCFF" align="center">1356868</td>
            <td width="100" bgcolor="#66CCFF" align="center">666</td>
            <td width="100" bgcolor="#66CCFF" align="center">500</td>
            <td width="100" bgcolor="#66CCFF" align="center">周一至周五 8:00 至 20:00，周六至周日：8:00 至 17:00 </td>
            <td width="100" bgcolor="#66CCFF" align="center"></td>
            <td width="100" bgcolor="#66CCFF" align="center"></td>
            <td width="100" bgcolor="#66CCFF" align="center"></td>
            <td width="60" align="center" bgcolor="#66CCFF"><input name="xugai" type="button" value="修改&nbsp;" onclick="show_1(2)" /></td>
            <td width="60" align="center" bgcolor="#66CCFF"><input name="del" type="button" value="删除&nbsp;" onclick="del(2)" /></td>
        <tr>
            <td width="100" bgcolor="#66CCFF" align="center">1</td>
            <td width="100" bgcolor="#66CCFF" align="center">zhang</td>
            <td width="100" bgcolor="#66CCFF" align="center">666</td>
            <td width="100" bgcolor="#66CCFF" align="center">1</td>
            <td width="100" bgcolor="#66CCFF" align="center">1356868</td>
            <td width="100" bgcolor="#66CCFF" align="center">人民教育出版社</td>
            <td width="100" bgcolor="#66CCFF" align="center">500</td>
            <td width="100" bgcolor="#66CCFF" align="center">周一至周五 8:00 至 20:00，周六至周日：8:00 至 17:00</td>
            <td width="100" bgcolor="#66CCFF" align="center">123</td>
            <td width="100" bgcolor="#66CCFF" align="center">张志</td>
            <td width="100" bgcolor="#66CCFF" align="center"></td>
            <td width="60" align="center" bgcolor="#66CCFF"><input name="xugai" type="button" value="修改&nbsp;" onclick="show_1(1)" /></td>
            <td width="60" align="center" bgcolor="#66CCFF"><input name="del" type="button" value="删除&nbsp;" onclick="del(1)" /></td>
        </tr>
    </table>
</body>

</html>