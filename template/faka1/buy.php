<?php
if(!defined('IN_CRONLITE'))exit();
if(checkmobile() && !$_GET['pc'] || $_GET['mobile']){include_once TEMPLATE_ROOT.'faka/wapbuy.php';exit;}

function escape($string, $in_encoding = 'UTF-8',$out_encoding = 'UCS-2') { 
    $return = ''; 
    if (function_exists('mb_get_info')) { 
        for($x = 0; $x < mb_strlen ( $string, $in_encoding ); $x ++) { 
            $str = mb_substr ( $string, $x, 1, $in_encoding ); 
            if (strlen ( $str ) > 1) { // 多字节字符 
                $return .= '%u' . strtoupper ( bin2hex ( mb_convert_encoding ( $str, $out_encoding, $in_encoding ) ) ); 
            } else { 
                $return .= '%' . strtoupper ( bin2hex ( $str ) ); 
            } 
        } 
    } 
    return $return; 
}

if($islogin2==1){
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
}elseif($is_fenzhan == true){
	$price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
	$price_obj = new \lib\Price(1);
}

$tid=intval($_GET['tid']);
$tool=$DB->getRow("select * from pre_tools where tid='$tid' limit 1");
if(!$tool || $tool['active']!=1)sysmsg('当前商品不存在');
if($tool['close']==1)sysmsg('当前商品维护中，停止下单！');

if(isset($price_obj)){
	$price_obj->setToolInfo($tool['tid'],$tool);
	if($price_obj->getToolDel($tool['tid'])==1)sysmsg('商品已下架');
	$price=$price_obj->getToolPrice($tool['tid']);
}else $price=$tool['price'];


if($tool['is_curl']==4){
	$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$tool['tid']}' and orderid=0");
	$fakainput = getFakaInput();
	$tool['input']=$fakainput;
	$isfaka = 1;
}elseif($tool['stock']!==null){
	$count = $tool['stock'];
	$isfaka = 1;
}else{
	$isfaka = 0;
}
if($tool['is_curl']==1||$tool['is_curl']==2||$tool['is_curl']==4){
	$isauto = true;
}else{
	$isauto = false;
}
if($tool['prices']){
	$arr = explode(',',$tool['prices']);
	if($arr[0]){
		$arr = explode('|',$tool['prices']);
		$view_mall = '<font color="#ff0000" size="2">购买'.$arr[0].'个以上按批发价￥'.($price-$arr[1]).'计算</font>';
	}
}
$classname = $DB->getColumn("select name from pre_class where cid='{$tool['cid']}' limit 1");

$hometitle = $tool['name'].' - '.$conf['sitename'];
include_once TEMPLATE_ROOT.'faka/inc/head.php';

?>
<link rel="stylesheet" href="assets/faka/css/buy.css" />
<div class="g-body">
<br/>
<br/>
<div class="topliucheng"><img src="<?php echo $cdnserver?>assets/faka/images/goumaizn02.png" title=""></div>
<div style="margain-bottom：20px;margin: 0 auto;width: 1180px;border-radius: 3px;">
    <div class="ziti" style="font-size: 16px;color: #7a7a7a;padding-top: 12px;padding-right: 6px;padding-bottom: 6px;padding-left: 4px;border-radius: 3px;">
        当前位置 -&gt; <a href="./">网站首页</a> -&gt; <a href="./?cid=<?php echo $tool['cid']?>"><?php echo $classname?></a> -&gt; <?php echo $tool['name']?>
    </div>
</div>

<div id="body">

<div class="bobo">

<div class="left">

<div class="from_wz_xx">
<div class="kefucss ziti"><b>商品编号：NO.<?php echo $tid?></b></div>
<img src="<?php echo $tool['shopimg']?$tool['shopimg']:'assets/faka/images/default.jpg';?>" width="260" alt="<?php echo $tool['name']?>">
</div>
<div class="from_wz_xx">
<div class="kefucss ziti"><b>商家联系方式</b></div>

<div class="kefu"><a target="blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $conf['kfqq']?>&amp;Site=qq&amp;Menu=yes">QQ:<?php echo $conf['kfqq']?></a> </div>

<div align="center">
<br>
<img src="//api.qrserver.com/v1/create-qr-code/?size=150x150&margin=10&data=<?php echo $siteurl?>">
<div class="ziti"><font size="3"><b>手机扫码购买</b></font><br><br></div></div>
</div>


</div>
<div class="rigth">
<div class="trade-goodinfo">
<?php echo $tool['name']?></div>
<div class="trade-goodinfo2">
		<span  style="color:#080808">售价</span>
		<span class="trade-price">¥<?php echo $price?></span>
		<?php echo $view_mall?>
														

		<span style="float:right"><?php echo $isauto?'<img src="assets/faka/images/zdfh.png">':'<img src="assets/faka/images/sdfh.png">';?>

		</span>
</div><br/>

<input type="hidden" id="tid" value="<?php echo $tid?>" cid="<?php echo $tool['cid']?>" price="<?php echo $tool['price']?>" alert="<?php echo escape($tool['alert'])?>" inputname="<?php echo $tool['input']?>" inputsname="<?php echo $tool['inputs']?>" multi="<?php echo $tool['multi']?>" isfaka="<?php echo $isfaka?>" count="<?php echo $tool['value']?>" close="<?php echo $tool['close']?>" prices="<?php echo $tool['prices']?>" max="<?php echo $tool['max']?>" min="<?php echo $tool['min']?>">
<input type="hidden" id="leftcount" value="<?php echo $isfaka==1?$count:100?>">

<div id="inputsname"></div>
<?php if($tool['multi']==1){?>
<div class="from">
 <div class="from_wz_3">购买数量：</div>
 <div class="from_in_2">
 <input id="num" name="num" class="z" type="number" value="1" placeholder="请输入购买数量" required min="1" <?php echo $isfaka==1?'max="'.$count.'"':null?>>
 </div>
 <?php if($isfaka){?>
 <div class="from_in_2 yanzheng" style="width:200px"> <font size="2" color="#FF7200"> 库存<?php echo $isfaka==1?$count.'个':'充足'?></span> </div>
 <?php }?>
 </div>
<?php }?>


      <div class="from">

        <div class="from_off_4"></div>
		<div class="from_in_4" style="width:100px">
		
		<input type="button" style="cursor:pointer;" class="button button-3d button-primary button-small" value="立即购买" id="submit_buy"/>
		
	
		</div> <div class="from_in_2 yanzheng" style="width:100px">
	 <a href="#" onclick="javascript:history.go(-1);" class="button button-3d button-highlight button-rounded button-small">返回</a></div>
        </div>


		
 <div class="trade-goodinfo2">
	  商品介绍：
      </div>
      <div class="xiangqing">
	  <p>
	  <?php echo $tool['desc']?></p>      </div>

</div>

</div>

</div>
<?php include_once TEMPLATE_ROOT.'faka/inc/foot.php';?>

<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
var hashsalt=<?php echo $addsalt_js?>;
</script>
<script src="assets/faka/js/faka.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>