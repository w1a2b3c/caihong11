<?php
if(!defined('IN_CRONLITE'))exit();

if($is_fenzhan==true && file_exists(ROOT.'assets/img/logo_'.$conf['zid'].'.png')){
	$logo = '../assets/img/logo_'.$conf['zid'].'.png';
}else{
	$logo = '../assets/img/logo.png';
}
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
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/faka/css/css<?php echo $conf['template_style']?$conf['template_style']:7?>.css"/>
	<link rel="stylesheet" href="<?php echo $cdnpublic?>Buttons/2.0.0/css/buttons.min.css" />
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css">
<style>
.info.denglu {
	line-height: 55px;
}
#head .top .logo_img {
	float: left;
	height: 80px;
	width: 400px;
	margin-top: 10px;
}
td.stitle{overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width:580px;text-align:left;}
.sinput{text-indent:10px;float:left;width:270px;height:35px;line-height:28px;padding:5px 5px 5px 5px;color:#31302e;border-radius:0;background-color:#fff;font-size:16px}.sbtn{float:left;width:100px;height:47px;cursor:pointer;display:inline-block;font-size:16px;vertical-align:middle;color:#31302e}
</style>
<?php if($conf['template_bgopen']==0){?><style>.g-body{background-image: none;}</style><?php }?>
</head>
<body>
    <div id="head">

   	  <div class="top">

       	<!--<div class="logo" onClick="location.href=''"></div>-->
        <div class="logo_img"><a href="../"><img src="<?php echo $logo?>" height="80"></a></div>

        			
              
		  <?php if($islogin2==1){?>
			<div class="info">
				<span class="welcome">亲爱的：<span class="lv"><b><?php echo $userrow['user']?> </b></span>欢迎您！&nbsp;<span></span><br>
            <a href="./" class="button button-primary button-rounded button-small">会员中心</a>  
			<a href="./login.php?logout" class="button button-giant button-rounded button-small" onclick="return confirm('确定要退出吗？')">安全退出</a>
			</div>
			<?php }else{?>
			<div class="info denglu">
				<a class="button button-3d button-primary button-small" href="./login.php">登录</a>    <a class="button button-3d button-caution button-small" href="./reg.php"><i class="fa fa-tag"></i>注册</a>&nbsp;&nbsp;
			</div>
			<?php }?>
		</div>
      

      <div class="dh">

        <ul id="nav"> 

            <li><a <?php echo $mod=='index'?'class="a2"':null;?> href="../">商品首页</a></li>
			<li><a <?php echo $mod=='fenlei'?'class="a2"':null;?> href="../?mod=fenlei">商品分类</a></li>
			<li><a <?php echo $mod=='query'?'class="a2"':null;?> href="../?mod=query">订单查询</a></li>
             <?php if(!empty($conf['template_about'])){?><li><a <?php echo $mod=='about'?'class="a2"':null;?> href="../?mod=about">关于我们</a></li><?php }?>	
           
              <?php if(!empty($conf['template_help'])){?><li><a <?php echo $mod=='help'?'class="a2"':null;?> href="../?mod=help">帮助中心</a></li><?php }?>
			  <?php if($conf['articlenum']>0){?>
			   <li><a target="_blank" href="../<?php echo article_url()?>">文章列表</a></li>
			   <?php }?>
			   <?php if(!empty($conf['appurl'])){?>
			   <li><a target="_blank" href="<?php echo $conf['appurl']; ?>">APP下载</a></li>
			   <?php }?>
			   <?php if($conf['search_open']==1){?>
				<div style="float:right;width:383px;padding:6px 6px 6px 6px;">
                <div style="border-radius:5px ;width:383px;height: 40px;">
					<form action="../?" method="get"><input type="hidden" name="mod" value="so"/>
                    <input type="text" name="kw" value="" class="sinput" placeholder="请输入商品关键词">
					<input type="submit" class="sbtn" value="商品搜索">
					</form>
				</div>
            </div>
			<?php }?>
        </ul> 

        </div>

  
    </div>
