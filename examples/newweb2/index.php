<!DOCTYPE html>
<html lang="zh">

<head>
	<meta charset="UTF-8">
	<title>后台首页</title>
	<link rel="stylesheet" type="text/css" href="css/public.css" />

	<style>
		.leftsidebar_box .line img {
			position: absolute;
			top: 10px;
			left: 10px;
		}

		.namexx {
			position: absolute;
			color: #0FF;
			font-size: 30px;
			left: 380px;
			letter-spacing: 20px;
			top: 30px;
			color: #999900;
			font-weight: 100;
			font-family: 微软雅黑;
			text-shadow: 2px 1px 2px #000;
		}

		.d22 {
			margin-top: -15px;
			/*margin-left:24px;*/
		}

		* {
			padding: 0;
			margin: 0;
			font-family: "Microsoft YaHei";
			font-size: 14px;
		}

		#hides {
			display: none;
		}

		a {
			text-decoration: none;
		}

		li {
			list-style-type: none;
		}

		/*head.css*/
		/*页面整形CSS   请勿删除和覆盖   hy*/
		#bg {
			width: 100%;
			overflow-x: hidden;
		}

		.head {
			width: 100%;
			height: 150px;
			background-color: #4390b9;
		}

		.head .headL {
			width: 440px;
			height: 100%;
			text-align: center;
			float: left;
			display: inline-block;
		}

		.head .headL img.headLogo {
			padding-top: 20px;
			/*///*/
		}

		.telephone span {
			color: #999900;
			font-size: 22px;
			font-weight: 100;
			font-family: 微软雅黑;
			text-shadow: 2px 1px 2px #000;
		}

		.head .headR {
			width: 200px;
			height: 100%;
			display: inline-block;
			float: right;
			text-align: right;
			margin-right: 120px;
		}

		.head .headR .p1 {
			padding-top: 30px;
			font-size: 18px;
			color: white;
			display: inline-block;
			cursor: pointer;
		}

		.head .headR .p2 {}

		.head .headR .p2 a {
			font-size: 14px;
			padding-top: 12px;
			display: inline-block;
			color: white;
			cursor: pointer;
		}

		/*head.html  弹出框关闭*/
		.closeOut {
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.39);
			position: fixed;
			top: 0px;
			display: none;
		}

		.closeOut .coDiv {
			width: 20%;
			margin: auto;
			background-color: #fff;
			position: relative;
			top: 30%;
			text-align: center;
		}

		.closeOut .coDiv .p1 {
			position: relative;
			top: 5px;
			left: 45%;
		}

		.closeOut .coDiv .p1 span {
			width: 30px;
			height: 30px;
			display: inline-block;
			color: #fff;
			background-color: #3695cc;
			border-radius: 15px;
			line-height: 30px;
			font-size: 20px;
			cursor: pointer;
		}

		.closeOut .coDiv .p2 {
			font-size: 18px;
			margin-top: 20px;
		}

		.closeOut .coDiv .p3 {
			margin-top: 20px;
		}

		.closeOut .coDiv .p3 .ok {
			display: inline-block;
			width: 100px;
			height: 40px;
			background-color: #fff;
			border: 1px solid #3695cc;
			line-height: 40px;
			margin-left: 25px;
			margin-right: 25px;
			color: #333;
			font-size: 16px;
			margin-bottom: 65px;
		}

		.closeOut .coDiv .p3 .no {
			background-color: #3695cc;
			color: #fff;
		}

		/*******left页面css*******/
		.container {
			width: 220px;
			height: 100%;
			margin: auto;
			position: absolute;
		}

		.leftsidebar_box {
			width: 220px;
			height: 100%;
			background-color: #f2f2f2;
		}

		.leftsidebar_box dt {
			background-color: #f9f9f9;
			color: #333;
			font-size: 14px;
			position: relative;
			line-height: 44px;
			cursor: pointer;
			border-bottom: 1px solid #dedede;
			border-right: 1px solid #dedede;
			padding-left: 40px;
		}

		.leftsidebar_box dd {
			display: none;
			position: relative;
			background-color: white;
			padding-left: 65px;
			border-bottom: 1px solid #dedede;
			border-right: 1px solid #dedede;
		}

		.leftsidebar_box dd a {
			color: #333;
			line-height: 42px;
			width: 100%;
			height: 100%;
			display: inline-block;
			cursor: pointer;
		}

		.leftsidebar_box dt img.icon1 {
			display: none;
			position: absolute;
			top: 10px;
			left: 10px;
		}

		.leftsidebar_box dt img.icon2 {
			position: absolute;
			top: 10px;
			left: 10px;
		}

		.leftsidebar_box dt img.icon3 {
			display: none;
			position: absolute;
			top: 20px;
			right: 12px;
		}

		.leftsidebar_box dt img.icon4 {
			position: absolute;
			top: 20px;
			right: 12px;
		}

		.leftsidebar_box dd img.icon5 {
			display: none;
			position: absolute;
			top: 0px;
			right: 0px;
		}

		.leftsidebar_box dd img.coin11 {
			display: none;
			position: absolute;
			top: 0px;
			left: 40px;
		}

		.leftsidebar_box dd img.coin22 {
			position: absolute;
			top: 0px;
			left: 40px;
		}

		.leftsidebar_box dl dd:last-child {
			padding-bottom: 10px;
		}

		.leftsidebar_box .line {
			background-color: #f9f9f9;
			color: #333;
			font-size: 14px;
			position: relative;
			line-height: 44px;
			cursor: pointer;
			border-bottom: 1px solid #dedede;
			border-right: 1px solid #dedede;
			padding-left: 40px
		}

		.leftsidebar_box .line img {
			position: absolute;
			top: 10px;
			left: 10px;
		}

		.leftsidebar_box .menu_chioce1 {
			color: #106ea9;
		}

		.leftsidebar_box .menu_chioce2 {
			color: #106ea9;
		}
	</style>


	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/public.js"></script>

	<script type="text/javascript">
		function changeFrameHeight() {
			var ifm = document.getElementById("mainiframe");
			ifm.height = document.documentElement.clientHeight - 56;
		}
		window.onresize = function() {
			changeFrameHeight();
		}
		$(function() {
			changeFrameHeight();
		});
	</script>
</head>

<body bgcolor="#CCCCCC">
	<!-- 头部 -->
	<div class="head">
		<div class="d22"><img class="headLogo" src="img/logo.jpg" width=16% /></div>
		<div class="namexx">保健人口疫苗接种后台管理系统</div>

	</div>
	<!-- 左边节点 -->
	<div class="container">

		<div class="leftsidebar_box">
			<a href="../index.php" target="main">
				<div class="line">
					<img src="img/coin01.png" />&nbsp;&nbsp;首页
				</div>
			</a>
			<!--///////////////////////////////////-->
			<dl class="system_log">
				<dt>
					<img class="icon1" src="img/coin07.png" /><img class="icon2" src="img/coin08.png" />接种人员基本信息管理<img class="icon3" src="img/coin19.png" /><img class="icon4" src="img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a href="yuyue/indexfi.php" target="ifbox" class="cks">预约人员信息</a><img class="icon5" src="img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a href="yuyue/inserti.php" target="ifbox" class="cks">添加预约人员信息</a><img class="icon5" src="img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a href="yuyue/indexfn.php" target="ifbox" class="cks">非预约人员信息</a><img class="icon5" src="img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a href="yuyue/insertn.php" target="ifbox" class="cks">添加非预约人员信息</a><img class="icon5" src="img/coin21.png" />
				</dd>
			</dl>

			<!--// /////////////////////////////////-->
			<dl class="system_log">
				<dt>
					<img class="icon1" src="img/coin01.png" /><img class="icon2" src="img/coin02.png" />接种单位和人员管理<img class="icon3" src="img/coin19.png" /><img class="icon4" src="img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a class="cks" href="yuyue/indexe.php" target="ifbox">接种单位信息</a><img class="icon4" src=" img/coin20.png" />
				</dd>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a class="cks" href="yuyue/inserte.php" target="ifbox">添加接种单位信息</a><img class="icon4" src=" img/coin20.png" />
				</dd>

				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a class="cks" href="tong/index.php" target="ifbox">接种单位相关人员信息</a><img class="icon4" src=" img/coin20.png" />
				</dd>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a class="cks" href="yuyue/indexe.php" target="ifbox">添加接种单位相关人员信息</a><img class="icon4" src=" img/coin20.png" />
				</dd>

			</dl>
			<dl class="system_log">
				<dt>
					<img class="icon1" src="img/coin01.png" /><img class="icon2" src="img/coin02.png" />相关基本信息管理<img class="icon3" src="img/coin19.png" /><img class="icon4" src="img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a class="cks" href="dingdan/indexsel.php" target="ifbox">接种单位信息</a><img class="icon4" src=" coin20.png" />
				</dd>

				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a class="cks" href="his_vac/indexfn.php" target="ifbox">疫苗信息</a><img class="icon4" src=" coin20.png" />
				</dd>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a class="cks" href="his_vac/indexfy.php" target="ifbox">常住人口感染新冠纪录</a><img class="icon4" src=" coin20.png" />
				</dd>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a class="cks" href="his_vac/indexfy.php" target="ifbox">常住人口信息</a><img class="icon4" src=" coin20.png" />
				</dd>
			</dl>

			<dl class="system_log">
				<dt>
					<img class="icon1" src="img/coin07.png" /><img class="icon2" src="img/coin08.png" />会员信息管理<img class="icon3" src="img/coin19.png" /><img class="icon4" src="img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a href="hyuan/index.php" target="ifbox" class="cks">会员基本信息管理</a><img class="icon5" src="img/coin21.png" />
				</dd>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a href="p1.html" target="ifbox" class="cks">vip会员信息管理</a><img class="icon5" src="img/coin21.png" />
				</dd>
			</dl>

			<dl class="system_log">
				<dt>
					<img class="icon1" src="img/coinL1.png" />
					<img class="icon2" src="img/coinL2.png" />系统管理
					<img class="icon3" src="img/coin19.png" />
					<img class="icon4" src="img/coin20.png" />
				</dt>
				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a href="admin/insert6.php" target="ifbox" class="cks">添加管理员</a><img class="icon5" src="img/coin21.png" />
				</dd>

				<dd>
					<img class="coin11" src="img/coin111.png" /><img class="coin22" src="img/coin222.png" /><a href="admin/index.php" target="ifbox" class="cks">管理员改删</a><img class="icon5" src="img/coin21.png" />
				</dd>
			</dl>

		</div>

	</div>
	<div style="position:absolute;left: 220px;top:140px;width:1200px; hieght:800px;">
		<iframe name="ifbox" id="mainiframe" width="100%" height="600" background-color:#b0c4de; frameborder="0" scrolling="auto">

		</iframe>
	</div>


</body>

</html>