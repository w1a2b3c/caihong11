<?php
if (!defined('IN_CRONLITE')) exit();
if(!$conf['iskami'])exit("<script language='javascript'>alert('当前站点未开启卡密兑换商品功能');window.location.href='./';</script>");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title><?php echo $conf['sitename'] ?> - 卡密下单页面</title>
    <link href="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/oneui.css">
	<script src="<?php echo $cdnpublic?>modernizr/2.8.3/modernizr.min.js"></script>
    <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic?>respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
<?php echo $background_css?>
<style>
.onclick{cursor: pointer;touch-action: manipulation;}
</style>
</head>
<body style="background-color:#ffffff;">
<div style="padding-top:6px;">
    <div class="col-xs-12 col-sm-10 col-md-8 col-lg-5 center-block" style="float: none;">
        
        <div class="block block-themed" style="box-shadow:0px 5px 10px 0 rgba(0, 0, 0, 0.25);">
			<div class="block-header bg-primary">
				<h3 class="block-title"><i class="fa fa-credit-card-alt"></i>&nbsp;&nbsp;卡密下单</h3>
			</div>
            <div class="block-content">

				<div class="form-group">
					<div class="hide" id="tidframe"></div>
					<div class="input-group">
						<div class="input-group-addon">输入卡密</div>
						<input type="text" name="km" id="km" class="form-control" placeholder="请输入卡密" required="required">
					</div>
				</div>
				<div class="form-group" id="submit_card_frame">
					<input type="submit" name="submit" id="submit_card" value="确定" class="btn btn-primary btn-block" style="background-color: #7266ba; border-color: #7266ba;">
				</div>
			
				<div id="km_show_frame" style="display:none;">
					<div class="form-group">
						<div class="input-group"><div class="input-group-addon">商品名称</div>
						<input type="text" name="shopname" id="shopname" class="form-control" disabled/>
					</div></div>
					<div class="form-group" id="display_left" style="display:none;">
						<div class="input-group"><div class="input-group-addon">库存数量</div>
						<input type="text" name="leftcount" id="leftcount" class="form-control" disabled/>
					</div></div>
					<div class="form-group" id="display_num" style="display:none;">
						<div class="input-group"><div class="input-group-addon">下单份数</div>
						<input type="text" name="num" id="num" class="form-control" disabled/>
					</div></div>

					<div id="inputsname"></div>

					<div id="alert_frame" class="alert alert-success" style="background: linear-gradient(to right, rgb(113, 215, 162), rgb(94, 209, 215)); font-weight: bold; color: white;display: none"></div>

					<div class="form-group">
						<input type="submit" id="submit_buy" class="btn btn-primary btn-block" style="background-color: #7266ba; border-color: #7266ba;" value="确定提交订单">
					</div>
				</div>
						
            <a class="btn btn-block" href="./" id="tlet"><i class="fa fa-mail-reply-all"></i> 返回网站首页</a>
        
			</div>
        </div>
    </div>
</div>
<script src="<?php echo $cdnpublic ?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic ?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic ?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="<?php echo $cdnpublic ?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic ?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script type="text/javascript">
var hashsalt =<?php echo $addsalt_js?>;
</script>
<script src="assets/js/cardbuy.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>