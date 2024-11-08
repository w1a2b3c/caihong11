<?php
if(!defined('IN_CRONLITE'))exit();
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>QQ等级代挂 - <?php echo $conf['sitename']?></title>
    <link rel="stylesheet" href="assets/simple/css/oneui.css">
	<link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
	<link rel="stylesheet" href="<?php echo $cdnserver?>assets/css/common.css?ver=<?php echo VERSION ?>">
    <script src="<?php echo $cdnpublic?>modernizr/2.8.3/modernizr.min.js"></script>
    <!--[if lt IE 9]>
      <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<?php echo $background_css?>
</head>
<body>
<?php if($background_image){?>
<img src="<?php echo $background_image;?>" alt="Full Background" class="full-bg full-bg-bottom animated pulse " ondragstart="return false;" oncontextmenu="return false;">
<?php }?>
<br/>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-5 center-block" style="float: none;">
<div class="block" style="box-shadow:0px 5px 10px 0 rgba(0, 0, 0, 0.25);">
<a class="btn btn-block" href="./"><i class="fa fa-mail-reply-all"></i>  返回网站首页</a>
</div>

<div class="block">
            <ul class="nav nav-tabs nav-tabs-alt">
                <li style="width: 50%;" class="active" align="center">
                    <a href="#shop" data-toggle="tab"><i class="fa fa-exclamation-circle"></i>&nbsp;代挂使用教程</a>
                </li>
                <li style="width: 50%;" align="center">
                    <a href="#search" data-toggle="tab"><i class="fa fa-wrench"></i>&nbsp;开通登录代挂</a>
                </li>
            </ul>
				<div class="tab-content">
                    <div class="tab-pane active" id="shop">
	<h3>购买代挂卡密</h3>
	<h5>填写你的邮箱 ，比如：<small>123456@qq.com</small></h5>
        <img src="//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rFXpySk8esSPAsMnoAqvU3xYRPjslqB7ow/0" class="img-responsive" >
        <img src="//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rDZgoibFXXdibsPRAFu7CZEUnRDloPEib9RrA/0" class="img-responsive" >
	<hr>
	<h3>开通新的代挂</h3>
	<h5>开通后，请在第二天10点后补挂</h5>
        <img src="//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rOyibAYjSCyIhrNB4UprUyvxD7Xh4UHlHXA/0" class="img-responsive" >
	<hr>
	<h3>开启QQ等级代挂</h3>
	<h5> 如开启了手机代挂请中午12点前不要登录手机QQ。每日代挂时间：0点到10点(极少数新用户12点前完成。) </h5>
        <img src="//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rJibrRHfZRqcia3MZF004MWSYWgEU5icxmC8w/0" class="img-responsive" >
	<hr>
	<h3>更新代挂QQ密码</h3>
        <img src="//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rNZR9lQJb850NknrAWoVDZqqh5UZJl1nqw/0" class="img-responsive" >
	<hr>
	<h3>漏挂补挂</h3>
	<h5>每日补挂时间：16点到0点 </h5>
        <img src="//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rIq6bycZtkGicnCbXibia7YQlv6gfywjgIlqQ/0" class="img-responsive" >
	<hr>
	<h3>代挂需要注意一些什么：</h3>
	<div class="alert alert-warning alert-dismissable">
        <h4><strong>注意事项：</strong></h4>
        <p>使用本站的代挂之前，请提前把QQ的<b>“设备锁”</b>和<b>“网页登录保护”</b>关闭，代挂期间修改Q密后请重新到这里来更新，否则会因为登录不成功而无法代挂！</p>
    </div>
    <div class="alert alert-danger alert-dismissable">
        <h4><strong>温馨提示：</strong></h4>
        <p>本站不盗号，但是为了免除不必要的误解和麻烦，请提前绑定QQ密保、安全手机、安全中心等其他相关保护措施，防止出现意外！</p>
    </div>
        <img src="//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rEdicCGtNYWroGwsSZP1mAicdC5yzCHIe0PQ/0" class="img-responsive" >
	<hr>
	<h3>教程到这里结束，不懂的认真多看看几遍！</h3>
  	</div>
<!--使用教程结束-->

<!--开通代挂开始-->
    <div class="tab-pane" id="search">
    <iframe src="<?php echo $conf['daiguaurl']; ?>" style="width:100%;height:600px;"></iframe>
   </div>
<!--开通代挂结束-->
    </div>
</div>

</div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
</body>
</html>