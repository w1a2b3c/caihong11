<?php
if($conf['cdnpublic']==1){
	$cdnpublic = '//lib.baomitu.com/';
}elseif($conf['cdnpublic']==2){
	$cdnpublic = 'https://cdn.bootcdn.net/ajax/libs/';
}elseif($conf['cdnpublic']==4){
	$cdnpublic = '//s1.pstatp.com/cdn/expire-1-M/';
}else{
	$cdnpublic = '//cdn.staticfile.org/';
}
if(!empty($conf['staticurl'])){
	$cdnserver = '//'.$conf['staticurl'].'/';
}else{
	$cdnserver = '../';
}
if($conf['ui_user']==1){
	$ui_user = array('bg-dark','bg-white-only','bg-dark');
}else{
	$ui_user = array('bg-primary','bg-primary','bg-light dker');
}

if(substr($suprow['user'],0,3)=='qq_' && !empty($suprow['nickname'])){
	$nickname = htmlspecialchars($suprow['nickname']);
}else{
	$nickname = $suprow['user'];
}
if(empty($suprow['qq']) && !empty($suprow['faceimg'])){
	$faceimg = htmlspecialchars($suprow['faceimg']);
}elseif(!empty($suprow['qq'])){
	$faceimg = '//q4.qlogo.cn/headimg_dl?dst_uin='.$suprow['qq'].'&spec=100';
}else{
	$faceimg = '../assets/img/user.png';
}

$newuserhead=null;
$newuserfoot=null;
$template_route = \lib\Template::loadRoute();
if($template_route){
	$newuserhead = $template_route['userhead'];
	$newuserfoot = $template_route['userfoot'];
	if($template_route['userindex'] && checkIfActive(',index')){
		include($template_route['userindex']);exit;
	}
}
if($newuserhead){
	include($newuserhead);
	return;
}

@header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8" />
  <title><?php echo $title ?></title>
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <link href="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="<?php echo $cdnpublic?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/user/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="<?php echo $cdnserver?>assets/user/css/app.css" type="text/css" />
    <script src="<?php echo $cdnpublic?>jquery/2.1.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="<?php echo $cdnpublic?>layer/3.1.1/layer.js"></script>
    <script src="<?php echo $cdnserver?>assets/user/js/app.js"></script>
  <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<?php if($islogin3==1){
if($suprow['status']==0){
	sysmsg('你的账号已被封禁！',true);exit;
}
?>
<div class="app app-header-fixed  ">
  <header id="header" class="app-header navbar ng-scope" role="menu">
      <div class="navbar-header <?php echo $ui_user[0]?>">
        <button class="pull-right visible-xs" ui-toggle="off-screen" target=".app-aside" ui-scroll="app">
          <i class="glyphicon glyphicon-align-justify"></i>
        </button>
        <a href="./" class="navbar-brand text-lt">
          <i class="fa fa-desktop hidden-xs"></i>
          <span class="hidden-folded m-l-xs">供货管理中心</span>
        </a>
      </div>

      <div class="collapse pos-rlt navbar-collapse box-shadow <?php echo $ui_user[1]?>">
        <!-- buttons -->
        <div class="nav navbar-nav hidden-xs">
          <a href="#" class="btn no-shadow navbar-btn" ui-toggle="app-aside-folded" target=".app">
            <i class="fa fa-dedent fa-fw text"> 菜单</i>
            <i class="fa fa-indent fa-fw text-active">菜单</i>
          </a>
        </div>
        <!-- / buttons -->

        <!-- nabar right -->
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" data-toggle="dropdown" class="dropdown-toggle clear" data-toggle="dropdown">
              <span class="thumb-sm avatar pull-right m-t-n-sm m-b-n-sm m-l-sm">
                <img src="<?php echo $faceimg ?>">
                <i class="on md b-white bottom"></i>
              </span>
              <span class="hidden-sm hidden-md"><?php echo $nickname ?></span> <b class="caret"></b>
            </a>
            <!-- dropdown -->
            <ul class="dropdown-menu animated fadeInRight w">
              <li>
                <a href="./">
                  <span>用户中心</span>
                </a>
              </li>
              <li>
                <a href="./uset.php?mod=user">
                  <span>修改资料</span>
                </a>
              </li>
			  <li>
                <a href="../">
                  <span>返回首页</span>
                </a>
              </li>
              <li class="divider"></li>
              <li>
                <a ui-sref="access.signin" href="login.php?logout">退出登录</a>
              </li>
            </ul>
            <!-- / dropdown -->
          </li>
        </ul>
        <!-- / navbar right -->
      </div>
      <!-- / navbar collapse -->
  </header>
  <!-- / header -->
  <!-- aside -->
  <aside id="aside" class="app-aside hidden-xs <?php echo $ui_user[2]?>">
      <div class="aside-wrap">
        <div class="navi-wrap">

          <!-- nav -->
          <nav ui-nav class="navi">
            <ul class="nav">
              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>导航</span>
              </li>
              <li class="<?php echo checkIfActive(',index')?>">
                <a href="./">
                  <i class="fa fa-user"></i>
                  <span>用户中心</span>
                </a>
              </li>
			  <li class="">
                <a href="../">
                  <i class="fa fa-home"></i>
                  <span>返回首页</span>
                </a>
              </li>
                <li class="<?php echo checkIfActive('bond')?>">
                    <a href class="auto">
                  <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                  </span>
                        <i class="fa fa-resistance"></i>
                        <span>保证金管理</span>
                    </a>
                    <ul class="nav nav-sub dk">

                        <li class="<?php echo checkIfActive('bond')?>">
                            <a href="./bond.php">
                                <span>缴纳保证金</span>
                            </a>
                        </li>
                        <li class="<?php echo checkIfActive('bond')?>">
                            <a href="./bond.php?act=thaw">
                                <span>解冻保证金</span>
                            </a>
                        </li>

                    </ul>
                </li>
<!--			  --><?php //if($conf['workorder_open']==1){?>
<!--			  <li class="--><?php //echo checkIfActive('workorder')?><!--">-->
<!--                <a href="./workorder.php">-->
<!--                  <i class="fa fa-check-square-o"></i>-->
<!--                  <span>我的工单</span>-->
<!--                </a>-->
<!--              </li>-->
<!--			  --><?php //}?>
                <li class="<?php echo checkIfActive('shoplist,shopedit')?>">
                    <a href class="auto">
                  <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                  </span>
                        <i class="fa fa-resistance"></i>
                        <span>供货管理</span>
                    </a>
                    <ul class="nav nav-sub dk">

                        <li class="<?php echo checkIfActive('shoplist')?>">
                            <a href="./shoplist.php">
                                <span>商品管理</span>
                            </a>
                        </li>
                        <li class="<?php echo checkIfActive('fakalist')?>">
                            <a href="./fakalist.php">
                                <span>卡密管理</span>
                            </a>
                        </li>

                    </ul>
                </li>
			  <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                <span>查询</span>
              </li>
              <li class="<?php echo checkIfActive('record')?>">
                <a href="./record.php">                      
                  <i class="fa fa-hashtag"></i>
                  <span>收支明细</span>
                </a>
              </li>
              <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">          
                <span>其他</span>
              </li>
              <li class="<?php echo checkIfActive('uset')?>">
                <a href class="auto">      
                  <span class="pull-right text-muted">
                    <i class="fa fa-fw fa-angle-right text"></i>
                    <i class="fa fa-fw fa-angle-down text-active"></i>
                  </span>
                  <i class="fa fa-resistance"></i>
                  <span>系统设置</span>
                </a>
                <ul class="nav nav-sub dk">
				  <li class="<?php echo checkIfActive('user')?>">
                    <a href="./uset.php?mod=user">
                      <span>用户资料设置</span>
                    </a>
                  </li>
                  <li class="<?php echo checkIfActive('skimg')?>">
                    <a href="./uset.php?mod=skimg">
                      <span>收款图设置</span>
                    </a>
                  </li>

                </ul>
              </li>
<!--              <li class="--><?php //echo checkIfActive('message')?><!--">-->
<!--                <a href="./message.php">-->
<!--                  <i class="fa fa-bullhorn"></i>-->
<!--                  <span>消息通知</span>-->
<!--                </a>-->
<!--              </li>-->
              <li>
                <a ui-sref="access.signin" href="login.php?logout">
                  <i class="fa fa-power-off"></i>
                  <span>退出登录</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>
  </aside>
<div id="content" class="app-content" role="main">
    <div class="app-content-body ">
				<div class="bg-light lter b-b wrapper-sm ng-scope">
					<ul class="breadcrumb" style="padding: 0;margin: 0;">
						<li><i class="fa fa-home"></i><a href="./">管理中心</a></li>
						<li><?php echo $title ?></li>
					</ul>
				</div>
  <!-- / aside -->
<?php }?>
