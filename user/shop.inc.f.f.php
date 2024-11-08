<?php
if(!defined('IN_CRONLITE'))exit();
$classhide = explode(',',$siterow['class']);
?>
<?php
if($conf['ui_shop']>0){
//分类图片宫格
?>
	<div id="goodType" <?php if(isset($_GET['cid'])){?>style="display: none"<?php }?>>
<?php if($conf['ui_shop']==1){?>
	<div class="row">
<?php
$rs=$DB->query("select * from pre_class where active=1 order by sort asc");
while($row = $rs->fetch()){
	if($is_fenzhan && in_array($row['cid'], $classhide))continue;
	if(!empty($row["shopimg"])){
		$productimg = $row["shopimg"];
	}else{
		$productimg = 'assets/img/Product/default.png';
	}
	if($usershop)$productimg='../'.$productimg;
	$count=$DB->getColumn("SELECT count(*) from pre_tools where cid={$row['cid']} and active=1");
?>
		<div class="col-lg-4 col-xs-6">
			<a class="widget animation-fadeInQuick goodTypeChange onclick" data-id="<?php echo $row["cid"]?>">
				<img class="lazy" width="100%" data-original="<?php echo $productimg?>">
				<div class="widget-content text-center">
					<strong><?php echo $row["name"]?></strong>
					<p class="text-muted" style="margin-bottom:10px;text-align:center;">分类<?php echo $count?>个商品</p>
					<button type="button" data-id="<?php echo $row["cid"]?>" class="btn btn-rounded btn-info btn-block goodTypeChange">点击进入</button>
				</div>
			</a>
		</div>
<?php }?>
	</div>
<?php }elseif($conf['ui_shop']==2){?>
<style type="text/css">
	.table>tbody>tr>td{vertical-align: baseline;}
</style>
	<table class="table table-striped table-borderless table-vcenter table-hover">
         <tbody>
<?php
$rs=$DB->query("select * from pre_class where active=1 order by sort asc");
while($row = $rs->fetch()){
	if($is_fenzhan && in_array($row['cid'], $classhide))continue;
	if(!empty($row["shopimg"])){
		$productimg = $row["shopimg"];
	}else{
		$productimg = 'assets/img/Product/default.png';
	}
	if($usershop)$productimg='../'.$productimg;
	$count=$DB->getColumn("SELECT count(*) from pre_tools where cid={$row['cid']} and active=1");
?>
			<tr class="widget animation-fadeInQuick onclick goodTypeChange" data-id="<?php echo $row["cid"]?>">
                <td class="text-center" style="width: 100px;">
                    <img data-original="<?php echo $productimg?>" width="50" style="height:50px" alt="avatar" class="lazy img-circle img-thumbnail img-thumbnail-avatar">
                </td>
                <td>
                    <h3 class="widget-heading h4"><strong><?php echo $row["name"]?></strong></h3>
					<span class="text-muted">分类<?php echo $count?>个商品</span>
                </td>
                <td class="text-right">
                    <button type="button" data-id="<?php echo $row["cid"]?>" class="btn btn-rounded btn-info goodTypeChange">点击进入</button>
                </td>
            </tr>
<?php
}
?>
		   </tbody>
        </table>
<?php }elseif($conf['ui_shop']==3){?>
	<div class="row">
<?php
$rs=$DB->query("select * from pre_class where active=1 order by sort asc");
while($row = $rs->fetch()){
	if($is_fenzhan && in_array($row['cid'], $classhide))continue;
	if(!empty($row["shopimg"])){
		$productimg = $row["shopimg"];
	}else{
		$productimg = 'assets/img/Product/default.png';
	}
	if($usershop)$productimg='../'.$productimg;
?>
		<div class="col-lg-3 col-xs-4" style="padding:0px">
		<div class="thumbnail" style="margin-bottom:3px;width:95%;margin: 2px auto;">
			<a class="widget animation-fadeInQuick goodTypeChange onclick" data-id="<?php echo $row["cid"]?>">
			<center style="margin-top:0;">
				<img class="lazy" data-original="<?php echo $productimg?>" style="height: 88px;">
				<strong style="white-space:nowrap"><?php echo $row["name"]?></strong>
				<span type="button" data-id="<?php echo $row["cid"]?>" class="btn btn-sm btn-info btn-block goodTypeChange">点击进入</span>
			</center>
			</a>
		</div>
		</div>
<?php }?>
	</div>
<?php }?>
	</div>
	<div id="goodTypeContent" <?php if(!isset($_GET['cid'])){?>style="display: none"<?php }?>>
		<div style="text-align: center;">
			<h3><span id="className"></span></h3>
			<img src="" id="classImg" width="50%" >
		</div>
		<br>
		<input type="hidden" name="cid" id="cid" value="0"/>
		<div class="form-group">
			<div class="input-group"><div class="input-group-addon">选择商品</div>
			<select name="tid" id="tid" class="form-control" onchange="getPoint();"><option value="0">请选择商品</option></select>
		</div></div>
		<div class="form-group" id="display_price" style="display:none;">
			<div class="input-group"><div class="input-group-addon">商品价格</div>
			<input type="text" name="need" id="need" class="form-control" style="center;color:#4169E1;font-weight:bold" disabled/>
		</div></div>
		<div class="form-group" id="display_left" style="display:none;">
			<div class="input-group"><div class="input-group-addon">库存数量</div>
			<input type="text" name="leftcount" id="leftcount" class="form-control" disabled/>
		</div></div>
		<div class="form-group" id="display_num" style="display:none;">
			<div class="input-group">
			<div class="input-group-addon">下单份数</div>
			<span class="input-group-btn"><input id="num_min" type="button" class="btn btn-info" style="border-radius: 0px;" value="━"></span>
			<input id="num" name="num" class="form-control" type="number" min="1" value="1"/>
			<span class="input-group-btn"><input id="num_add" type="button" class="btn btn-info" style="border-radius: 0px;" value="✚"></span>
		</div></div>
		<div id="inputsname"></div>
		<div id="alert_frame" class="alert alert-success animated rubberBand" style="display:none;background: linear-gradient(to right,#FF0000,#FF0000);font-weight: bold;color:white;"></div>
		<?php if($conf['shoppingcart']==1){?>
		<div class="btn-group btn-group-justified form-group">

			<a type="submit" id="submit_buy" class="btn btn-block btn-primary">立即购买</a>
		</div>
		<?php }else{?>
		<div class="form-group">
			<input type="submit" id="submit_buy" class="btn btn-primary btn-block" value="立即购买">
		</div>
		<?php }?>
		<div class="form-group"><button type="button" class="btn btn-default btn-block btn-sm backType">返回重选分类</button></div>
	</div>
	<ul class="layui-fixbar" id="alert_cart" style="display:none;">
	  <li class="layui-icon" style="background-color:#3e4425db" onclick="openCart()"><i class="fa fa-shopping-cart"></i><div class="nav-counter" id="cart_count"></div></li>
	</ul>
<?php
}else{
//经典模式
$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select='<option value="0">请选择分类</option>';
$select_count=0;
while($res = $rs->fetch()){
	if($is_fenzhan && in_array($res['cid'], $classhide))continue;
	$select_count++;
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}
if($select_count==0)$hideclass = true;
?>
		<div id="goodTypeContents">
			<?php echo $conf['alert']?>
			<?php if($conf['search_open']==1){?>
			<div class="form-group" id="display_searchBar">
				<div class="input-group"><div class="input-group-addon"><font color="#f200ff">搜索商品</div></font>
				<input type="text" id="searchkw" class="form-control" placeholder="搜索商品" onkeydown="if(event.keyCode==13){$('#doSearch').click()}"/>
				<div class="input-group-addon"><span class="glyphicon glyphicon-search onclick" title="搜索" id="doSearch"></span></div>
			</div></div>
			<?php }?>
			<div class="form-group" id="display_selectclass"<?php if($hideclass){?> style="display:none;"<?php }?>>
				<div class="input-group"><div class="input-group-addon"><font color="#00ff00 ">选择分类</div></font>
				<select name="tid" id="cid" class="form-control"><?php echo $select?></select>
			</div></div>
			<div class="form-group">
				<div class="input-group"><div class="input-group-addon"><font color="#00ffff">选择商品</div></font>
				<select name="tid" id="tid" class="form-control" onchange="getPoint();"><option value="0">请选择商品</option></select>
			</div></div>
			<div class="form-group" id="display_price" style="display:none;center;color:#4169E1;font-weight:bold">
				<div class="input-group"><div class="input-group-addon">商品价格</div>
				<input type="text" name="need" id="need" class="form-control" style="center;color:#ff0000;font-weight:bold" disabled/>
			</div></div>
			<div class="form-group" id="display_left" style="display:none;">
				<div class="input-group"><div class="input-group-addon">库存数量</div>
				<input type="text" name="leftcount" id="leftcount" class="form-control" disabled/>
			</div></div>
			<div class="form-group" id="display_num" style="display:none;">
                <div class="input-group">
                <div class="input-group-addon">下单份数</div>
                <span class="input-group-btn"><input id="num_min" type="button" class="btn btn-info" style="border-radius: 0px;" value="━"></span>
				<input id="num" name="num" class="form-control" type="number" min="1" value="1"/>
				<span class="input-group-btn"><input id="num_add" type="button" class="btn btn-info" style="border-radius: 0px;" value="✚"></span>
			</div></div>
			<div id="inputsname"></div>
			<div id="alert_frame" class="alert alert-success animated rubberBand" style="display:none;background: linear-gradient(to right,#FF0000,#ff0000);font-weight: bold;color:white;"></div>
			<?php if($conf['shoppingcart']==1){?>
			<div class="btn-group btn-group-justified form-group">

				<a type="submit" id="submit_buy" class="btn btn-block btn-primary">立即购买</a>
            </div>
			<?php }else{?>
			<div class="form-group">
				<input type="submit" id="submit_buy" class="btn btn-primary btn-block" value="立即购买">
			</div>
			<?php }?>

		</div>

		<div class="block animated bounceInDown btn-rounded" style="border:1px solid #FF0000;margin-top:15px;font-size:15px;padding:15px;border-radius:15px;background-color: white;"><div class="panel-heading"><h3 class="panel-title" types=""><font color="#0000FF"><span class="glyphicon glyphicon-stats"></span>&nbsp;&nbsp;<b><font color="#0000FF">今日订单详细<img src="26.png"></font></b></font></h3></div>


<div class="btn-group btn-group-justified">
		<a target="_blank" class="btn btn-effect-ripple btn-default collapsed" style="overflow: hidden; position: relative;"><b><font color="modal-title">购买用户</font></b></a>
		<a target="_blank" class="btn btn-effect-ripple btn-default collapsed" style="overflow: hidden; position: relative;"><b><font color="modal-title">下单日期</font></b></a>
		<a target="_blank" class="btn btn-effect-ripple btn-default collapsed" style="overflow: hidden; position: relative;"><b><font color="modal-title">物品名称</font></b></a>
		</div>  
		
	<marquee class="zmd" behavior="scroll" direction="UP" onmouseover="this.stop()" onmouseout="this.start()"
                     scrollamount="5" style="height:16em">
                <table class="table table-hover table-striped" style="text-align:center">
                    <thead>
                    <?php
                    $c = 80;
                    for ($a = 0; $a < $c; $a++) {
                        $sim = rand(1, 10); #随机数
                        $a1 = ''; #超级会员
                        $a2 = ''; #视频会员
                        $a3 = ''; #豪华黄钻
                        $a4 = ''; #豪华绿钻
                        $a5 = ''; #名片赞
                        $e = 'a' . $sim;
                        if ($sim == '1') {
                            $name = '安卓和平直装【柚子/原果冻】周卡【7X24H】';
                        } else if ($sim == '2') {
                            $name = '安卓和平直装【CoCo】天卡【1X24H】';
                        } else if ($sim == '3') {
                            $name = '安卓和平直装【战神】天卡【1X24H】';
                        } else if ($sim == '4') {
                            $name = '安卓和平直装【梅西】天卡【1X24H】';
                        } else if ($sim == '5') {
                            $name = '安卓和平ROOT【火花】天卡【1X24H】';
                        } else if ($sim == '6') {
                            $name = '苹果王者直装免越狱【Tips】天卡【1X24H】免签';
                        } else if ($sim == '7') {
                            $name = '下载码（超级签名）【妖月/Tips】';
                        } else if ($sim == '8') {
                            $name = '安卓和平直装【小黄鸡】天卡【1X24H】';
                        } else if ($sim == '10') {
                            $name = '苹果和平直装免越狱【AH】天卡【1X24H】免签';
                        } else if ($sim == '11') {
                            $name = rand(1000, 100000) . '和平雷电模拟器【牧歌原锦鲤】天卡【24H】';
                        }
                        $date = date('Y-m-d'); #今日
                        $time = date("Y-m-d", strtotime("-1 day"));
                        if ($a > 50) {
                            $date = $time;
                        } else {
                            if (date('H') == 0 || date('H') == 1 || date('H') == 2) {
                                if ($a > 8) {
                                    $date = $time;
                                }
                            }
                        }
                        echo '<tr></tr><tr><td>本站用户' . rand(10, 999) . '**' . rand(100, 999) . '**</td><td>于' . $date . '日下单成功</td><td><font color="salmon"><img src="' . $$e . '" width="15">' . $name . '</font></td></tr>';
                    }
                    ?>
                    </thead>
                </table>
            </marquee>
        </div>
<?php if($conf['articlenum']>0){
$limit = intval($conf['articlenum']);
$rs=$DB->query("SELECT id,title FROM pre_article WHERE active=1 ORDER BY top DESC,id DESC LIMIT {$limit}");
$msgrow=array();
while($res = $rs->fetch()){
	$msgrow[]=$res;
}
$class_arr = ['danger','warning','primary','success','info'];
$i=0;
?>

<!--文章列表-->
<div class="panel panel-info">
<div class="panel-heading" style="background: linear-gradient(to right,#91ff91,#cc6bff,#14b7ff);"><font color="#000000"><i class="fa fa-newspaper-o"></i>&nbsp;&nbsp;<b>文章列表</b></font></h3></div>
	<?php foreach($msgrow as $row){
	echo '<a target="_blank" class="list-group-item" href="'.article_url($row['id']).'"><span class="btn btn-'.$class_arr[($i++)%5].' btn-xs">'.$i.'</span>&nbsp;'.$row['title'].'</a>';
	}?>
	<a href="<?php echo article_url()?>" title="查看全部文章" class="btn-default btn btn-block" target="_blank">查看全部文章</a>
</div>
<!--文章列表-->

<?php }?>
<div class="panel panel-primary">
<div class="panel-heading"><h3 class="panel-title"><font color="#0000FF"><i class="fa fa-bar-chart-o"></i>&nbsp;&nbsp;<b>近30天数据统计</b></font></h3></div>
<table class="table table-bordered">
<tbody>
<tr>
<td align="center"><font size="2"><b><font color=#0000FF>30<span id="count_yxts"></span>关键词</font><b/><br><font color="#65b1c9"><img src="kjqd.ico"/></i></font><br>百度收录</font></td>
<td align="center"><font size="2"><b><font color="#DC143C">0<span id="cou1nt_yxts"></span>元</font><b/><br><font color="#65b1c9"><img src="gift.ico"/></i></font><br>累计退款</font></td>
<td align="center"><font size="2"><b><font color=#8B4513>0<span id="co1unt_yxts"></span>次</font><b/><br><font color="#65b1c9"><img src="zan.ico"/></i></font><br>交易投诉</font>
</tbody>
</table>
<div class="" style="box-shadow:0px 5px 10px 0 rgba(0, 0, 0, 0.26);">
</div>
</div>
<div style=" z-index:9999; text-decoration:none; font-weight:bold; position: fixed; z-index: 999; Left: -6px; bottom: 250px; display: inline-block; width: 20px; border-top-left-radius: 10px; border-top-Left-radius: 5px; border-bottom-Left-radius: 5px; border-bottom-left-radius: 10px; color: white; font-size: 17px; line-height: 17px; box-shadow: rgb(100 149 237) 0px 0px 5px; word-wrap: break-word; padding: 8px 13px; border: 2px solid white; background: rgb(100 149 237);"><a href="./toollogs.php" target="_blank" style="position: relative;left: -7px;top: 2px; color:#28FF28;">商品上架通知<!-- a--></a></div>
<?php } ?>