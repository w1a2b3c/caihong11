<?php
if(!defined('IN_CRONLITE'))exit();

$userid = $islogin2?$userrow['zid']:$cookiesid;
$rs=$DB->query("SELECT a.*,b.name,b.input AS inputname,b.shopimg FROM pre_cart AS a LEFT JOIN pre_tools AS b ON a.tid=b.tid WHERE a.userid='$userid' AND a.status<=1 ORDER BY a.id ASC");
$data = array();
while($res = $rs->fetch())
{
	$data[] = $res;
}
$count = count($data);
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=no"/>
    <title>购物车 - <?php echo $conf['sitename']?></title>
    <link rel="stylesheet" href="<?php echo $cdnserver?>assets/simple/css/oneui.css">
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
<br/>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-5 center-block" style="float: none;">
<div class="block" style="box-shadow:0px 5px 10px 0 rgba(0, 0, 0, 0.25);" id="backHome">
<a class="btn btn-block" href="./"><i class="fa fa-mail-reply-all"></i>  返回网站首页</a>
</div>

<div class="block block-bordered" style="box-shadow:0px 5px 10px 0 rgba(0, 0, 0, 0.25);">
  <div class="block-header bg-gray-lighter">
     <ul class="block-options">
        <li>
           <button type="button"><a href="javascript:emptyCart()"><i class="fa fa-trash"></i>&nbsp;清空</a></button>
        </li>
     </ul>
   <h3 class="block-title"><i class="fa fa-shopping-cart"></i>&nbsp;购物车共计 <?php echo $count?> 件商品</h3>
  </div>
  <div class="tab-pane active" id="shop">
<?php
foreach($data as $row){
	$inputname = $row['inputname']?$row['inputname']:'下单ＱＱ';
	$inputvalue = explode('|',$row['input']);
	echo '<li class="list-group-item">
<div class="media"><span class="pull-left thumb-sm">
<img src="'.$row['shopimg'].'" alt="..." class="img-avatar" width="20" onerror="this.src=\'assets/img/Product/noimg.png\'"></span>
<div class="pull-right push-15-t">
<label class="css-input css-checkbox css-checkbox-rounded css-checkbox-lg css-checkbox-default"><input type="checkbox" name="checkbox" id="lists" value="'.$row['id'].'" money="'.$row['money'].'" checked><span></span></label>	
<a class="btn btn-sm btn-danger" href="javascript:cart_shop_del('.$row['id'].')"><i class="fa fa-trash"></i></a></div>
<div class="pull-left">
<div><b>'.$row['name'].'</b><br> × '.$row['num'].'&nbsp;<font color="red">[￥'.$row['money'].'元]</font></div> 
<div class="text-muted"><span onclick="cart_shop_edit('.$row['id'].')" title="点击修改数据"><font size="1">'.$inputname.'：'.$inputvalue[0].'</font></span></div>
</div></div></li>';
}
?>
    <li class="list-group-item">
       <font color="red" size="4"><b>共需支付：<span id="needmoney">0</span>元</b></font>
       <input class="btn btn-sm btn-info pull-right" type="submit" name="submit" id="submit_cart" value="立即支付购买">
       <div class="text-center" id="buy_cart_alert"></div>
    </li>
  </div>
</div>
</div>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script type="text/javascript">
var hashsalt=<?php echo $addsalt_js?>;
</script>
<script src="assets/js/cart.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>