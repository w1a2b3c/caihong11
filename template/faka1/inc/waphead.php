<?php
if(!defined('IN_CRONLITE'))exit();
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
	<meta http-equiv="Cache-Control" content="no-transform"/>
	<title><?php echo $hometitle?></title>
	<meta name="keywords" content="<?php echo $conf['keywords']?>">
	<meta name="description" content="<?php echo $conf['description']?>">
	<meta name="applicable-device" content="mobile">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<link href="<?php echo $cdnserver?>assets/faka/css/frozen.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $cdnserver?>assets/faka/css/public.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $cdnserver?>assets/faka/css/baoliao.css" rel="stylesheet" type="text/css">
	<link href="<?php echo $cdnserver?>assets/faka/css/iconfont.css" rel="stylesheet"/>
	<link href="<?php echo $cdnserver?>assets/faka/css/component.css" rel="stylesheet" type="text/css">
	<script src="<?php echo $cdnpublic?>modernizr/2.8.3/modernizr.min.js"></script>
</head>
<body>
</head>
<body>
<header id="box" class="headerm2">
	<a href="javascript:history.back();" class="logo"><img src="<?php echo $cdnserver?>assets/faka/images/iconfont-fanhui.png"></a>
	<a href="javascript:" class="logo2"><?php echo $conf['sitename']?></a>
	<a href="javascript:" onclick="location.reload();" class="search2"><span>刷新</span></a>
		<div id="dl-menu" class="dl-menuwrapper">
		<button id="dl-menu-button">打开菜单</button>
		<ul class="dl-menu">
			<li><a href="./">网站首页</a></li>
			<li>
				<a href="./?mod=wapfenlei">商品分类</a>
			</li>
			<li>
				<a href="./?mod=wapquery">订单查询</a>
			</li>
			<?php if($islogin2==1){?>
			<li>
				<a href="Line">用户中心</a>
				<ul class="dl-submenu">
					<li class="dl-back"><a href="#">返回上级</a></li>
					<li><a href="./user/">用户中心</a></li>
					<li><a href="./user/recharge.php">充值余额</a></li>
					<li><a href="./user/record.php">消费记录</a></li>
					<li><a href="./user/login.php?logout" onclick="return confirm('确定要退出吗？')">退出登录</a></li>
				</ul>
			</li><?php }else{?><li>
				<a href="Line">登录 或 注册</a>
				<ul class="dl-submenu">
					<li class="dl-back"><a href="#">返回上级</a></li>
					<li><a href="./user/login.php">用户登录</a></li>
					<li><a href="./user/reg.php">注册账号</a></li>
					<li><a href="./user/findpwd.php">找回密码</a></li>
				</ul>
			</li><?php }?>
			<?php if(!empty($conf['template_about'])){?><li><a href="./?mod=wappage&type=0">关于我们</a></li><?php }?>
			<?php if(!empty($conf['template_help'])){?><li><a href="./?mod=wappage&type=1">帮助中心</a></li><?php }?>
			<?php if($conf['articlenum']>0){?><li><a href="<?php echo article_url()?>">文章列表</a></li><?php }?>
		</ul>
	</div>
</header>
