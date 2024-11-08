<?php
if(!defined('IN_CRONLITE'))exit();

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
		$view_mall = '<div class="bl_view_mall">批发价：'.($price-$arr[1]).' 元 ('.$arr[0].'个起按批发价)</div>';
	}
}

$hometitle = $tool['name'].' - '.$conf['sitename'];
include_once TEMPLATE_ROOT.'faka/inc/waphead.php';

?>
<link rel="stylesheet" href="assets/faka/css/buy.css" />

<div style="height: 50px"></div>

<?php if($conf['search_open']==1){?>
<div class="menux" style="background-color: #ffffff;">
  <form action="?" method="get"><input type="hidden" name="mod" value="wapso"/>
    <input name="kw" type="text" class="search_input" placeholder="请输入您要查询的商品名称关键词" required>
    <input type="submit" class="search_submit" style="background-color: #f44530" value="商品搜索">
  </form>
</div>
<?php }?>

<div class="view" style="margin:10px;margin-top:35px;">
<input type="hidden" id="tid" value="<?php echo $tid?>" cid="<?php echo $tool['cid']?>" price="<?php echo $tool['price']?>" alert="<?php echo escape($tool['alert'])?>" inputname="<?php echo $tool['input']?>" inputsname="<?php echo $tool['inputs']?>" multi="<?php echo $tool['multi']?>" isfaka="<?php echo $isfaka?>" count="<?php echo $tool['value']?>" close="<?php echo $tool['close']?>" prices="<?php echo $tool['prices']?>" max="<?php echo $tool['max']?>" min="<?php echo $tool['min']?>">
<input type="hidden" id="leftcount" value="<?php echo $tool['is_curl']==2?100:$count2?>">
  	<div class="bl_view_img"><img src="<?php echo $tool['shopimg']?$tool['shopimg']:'assets/faka/images/default.jpg';?>"  alt="<?php echo $tool['name']?>" /></div>
    <div class="bl_view_title"><?php echo $tool['name']?></div>
  <div class="bl_view_tag">
   		<div class="bl_view_price">售价：<?php echo $price?> 元</div>
		<?php echo $view_mall?>
    </div>
	<div class="bl_view_tag">
    	<div class="bl_view_user">商品类型：<?php echo $isauto?'<span class="bl_type" style="filter:alpha(opacity:50); opacity:0.7;">自动发货</span>':'<span class="bl_type" style="filter:alpha(opacity:50); opacity:0.7;">手动发货</span>';?>
</div>
    </div>
<?php if($isfaka){?>
	<div class="bl_view_title">商品库存：
 <font size="2" color="#FF7200"> 库存<?php echo $isfaka==1?$count.'个':'充足'?></span>
</div>
<?php }?>
<div id="inputsname"></div>
<?php if($tool['multi']==1){?>
	<div class="bl_view_title"> 购买数量：<input class="search_input2" id="num" name="num" type="number" value="1" min="1" max="<?php echo $count?>" placeholder="请输入购买数量" required></div>
<?php }?>
    <div class="go_buy"><input type="button" value="立即购买" id="submit_buy" /></div>
</div>

<div class="bl_view_content w">
  	<h1>商品说明<span>具体使用方法请阅读商品说明</span></h1>
    <div class="bl_view_word">
    	    <p><?php echo $tool['desc']?></p> 
    </div>
</div>

<div class="m_user" style="height:100px">
    <a href="#">返回顶部</a>
</div>

<?php include TEMPLATE_ROOT.'faka/inc/wapfoot.php';?>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
var hashsalt=<?php echo $addsalt_js?>;
</script>
<script src="assets/faka/js/faka.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>