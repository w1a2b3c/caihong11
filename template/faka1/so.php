<?php
if(!defined('IN_CRONLITE'))exit();
if(checkmobile() && !$_GET['pc'] || $_GET['mobile']){include_once TEMPLATE_ROOT.'faka/wapso.php';exit;}

include_once TEMPLATE_ROOT.'faka/inc/head.php';

if($islogin2==1){
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
}elseif($is_fenzhan == true){
	$price_obj = new \lib\Price($siterow['zid'],$siterow);
}else{
	$price_obj = new \lib\Price(1);
}

$classhide = explode(',',$siterow['class']);
$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$shua_class=array();
while($res = $rs->fetch()){
	if($is_fenzhan && in_array($res['cid'], $classhide))continue;
	$shua_class[$res['cid']]=$res['name'];
}

$template_label_auto = $conf['template_label_auto']?$conf['template_label_auto']:'自动';
$template_label_manual = $conf['template_label_manual']?$conf['template_label_manual']:'手动';

$kw=trim(daddslashes($_GET['kw']));

$total = $DB->getColumn("SELECT count(*) FROM pre_tools WHERE name LIKE '%$kw%' and active=1");

?>
<div class="g-body">
<br/>
<br/>
<div class="topliucheng"><img src="<?php echo $cdnserver?>assets/faka/images/goumaizn01.png" title="">
</div>
<div style="margain-bottom：20px;margin: 0 auto;width: 1180px;border-radius: 3px;">
    <div class="ziti" style="font-size: 16px;color: #7a7a7a;padding-top: 12px;padding-right: 6px;padding-bottom: 6px;padding-left: 4px;border-radius: 3px;">
        当前位置 -&gt; <a href="./">网站首页</a> -&gt; 商品搜索
    </div>
</div>
<div id="bd">

<div id="bar">
<div class="bar_top">客户服务</div>
<div class="from_wz_41">
<div class="kefu"><a target="blank" href="http://wpa.qq.com/msgrd?v=3&amp;uin=<?php echo $conf['kfqq']?>&amp;Site=qq&amp;Menu=yes">QQ:<?php echo $conf['kfqq']?></a></div>
<?php if(!empty($conf['kfwx'])){?><div class="kefu">微信：<?php echo $conf['kfwx']?></div><?php }?>
</div>

<div class="bar_top">商品分类</div>

<div>
	<table style="width:100%" border="0" cellpadding="10" cellspacing="0">
		<tbody><tr>
			<td class="ziti" style="font-size: 16px;cursor:pointer" onclick="window.location.href = './'"><b>全部商品</b></td>
		</tr>
		<?php foreach($shua_class as $cid=>$classname){?>
		<tr class="cid<?php echo $row['cid']?>">
			<td class="ziti" style="font-size: 16px;cursor:pointer" onclick="window.location.href = './?cid=<?php echo $cid?>'"><?php echo $classname?></td>
		</tr>
		<?php }?>
	</tbody></table>
</div>

<div class="bar_top">手机浏览</div><div class="erweima">
<img src="//api.qrserver.com/v1/create-qr-code/?size=150x150&margin=10&data=<?php echo $siteurl?>"> </div>
</div>

<div id="bar_r">
<div id="body_xiao">
<table style="width:100%" border="0" cellpadding="10" cellspacing="0"  >
			<thead>
				<tr >
  				    <th class="indexlistlb ziti" colspan="5" style="font-size: 18px">商品搜索【<?php echo htmlspecialchars($kw)?>】共<b><?php echo $total?></b>条记录</th>
				</tr>
			</thead>
			<tbody>
<tr class="cid"><th class="tableth1 ziti">商品名称</th>
					 <th class="tableth1 ziti">售价</th>
					 <th class="tableth1 ziti">库存</th>
					 <th class="tableth1 ziti">操作</th>
</tr>
<?php
$rs=$DB->query("SELECT * FROM pre_tools WHERE name LIKE '%$kw%' and active=1 order by sort asc");
while($res = $rs->fetch()){
	if(isset($price_obj)){
		$price_obj->setToolInfo($res['tid'],$res);
		if($price_obj->getToolDel($res['tid'])==1)continue;
		$price=$price_obj->getToolPrice($res['tid']);
	}else $price=$res['price'];
	if($res['is_curl']==4){
		$count = $DB->getColumn("SELECT count(*) FROM pre_faka WHERE tid='{$res['tid']}' and orderid=0");
		if($count>0&&$conf['faka_showleft']==0)$status = '<font color="#000CF9" size="3" title="商品库存">'.$count.'个</font>';
		elseif($count>0)$status = '<font color="#000CF9" size="3" title="商品库存">充足</font>';
		else $status = '<font color="#CCCCCC" title="商品库存">缺货</font>';
	}elseif($res['stock']!==null){
		$count = $res['stock'];
		if($count>0&&$conf['faka_showleft']==0)$status = '<font color="#000CF9" size="3" title="商品库存">'.$count.'个</font>';
		elseif($count>0)$status = '<font color="#000CF9" size="3" title="商品库存">充足</font>';
		else $status = '<font color="#CCCCCC" title="商品库存">缺货</font>';
	}else{
		if($res['close']==1)$status = '<font color="#CCCCCC" title="商品状态">已下架</font>';
		else $status = '<font color="#000CF9" size="3" title="商品状态">正常</font>';
	}
	if($res['is_curl']==1||$res['is_curl']==2||$res['is_curl']==4||$res['is_curl']==5){
		$isauto = true;
	}else{
		$isauto = false;
	}
	echo '<tr class="cid'.$cid.'"><td align="left" class="stitle tableth2 ziti">'.($isauto?'<a class="button button-action button-circle button-small" title="自动发货商品，购买后自动发货">'.$template_label_auto.'</a>':'<a class="button button-highlight button-circle button-small" title="手动发货商品，购买后客服手动发货给你">'.$template_label_manual.'</a>').' <font size="3" title="'.$res['name'].'">'.$res['name'].'</font></td><td><font color="#FF5400" size="2" title="商品售价">'.$price.'元</font>'.($conf['template_showsales']==1?'<br><font color="#bbb" size="1">销量:'.$res['sales'].'</font>':'').'</td><td>'.$status.'</td><td>'.($isfaka&&$count==0||$res['close']==1?'<a class="button button-default button-rounded button-small" href="./?mod=buy&cid='.$cid.'&tid='.$res['tid'].'">缺货</a>':'<a class="button button-primary button-rounded button-small" href="./?mod=buy&cid='.$cid.'&tid='.$res['tid'].'">购买</a>').'</td></tr>';
}
?>
			</tbody>
</table>

</div>
</div></div>
</div>

<?php include_once TEMPLATE_ROOT.'faka/inc/foot.php';?>
</body>
</html>