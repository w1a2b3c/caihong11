<?php
/**
 * 自助下单
**/
include("../includes/common.php");
$title='自助下单';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$usershop = true;
$addsalt=md5(mt_rand(0,999).time());
$_SESSION['addsalt']=$addsalt;
$x = new \lib\hieroglyphy();
$addsalt_js = $x->hieroglyphyString($addsalt);
?>
<style>
img.logo{width: 22px;margin: -2px 5px 0 5px;}
.onclick{cursor: pointer;touch-action: manipulation;}
.border-t{border-top: 1px solid #e9e9e9;}
.border-b{border-bottom: 1px solid #e9e9e9;}
.layui-fixbar{position:fixed;right:15px;bottom:15px;z-index:999999;margin:0;padding:0}
.layui-fixbar li{list-style:none;width:50px;height:50px;line-height:50px;margin-bottom:1px;text-align:center;cursor:pointer;font-size:30px;background-color:#9F9F9F;color:#fff;border-radius:2px;opacity:.95}
.nav-counter{position:absolute;font-size:16px;top:-1px;right:1px;height:20px;width:20px;line-height:20px;padding:0 6px;color:#fff;text-align:center;background:#e23442;border-radius:50%;background-image:-webkit-linear-gradient(top,#e8616c,#dd202f);background-image:-moz-linear-gradient(top,#e8616c,#dd202f);background-image:-o-linear-gradient(top,#e8616c,#dd202f);background-image:linear-gradient(to bottom,#e8616c,#dd202f);-webkit-box-shadow:inset 0 0 1px 1px rgba(255,255,255,.1),0 1px rgba(0,0,0,.12);box-shadow:inset 0 0 1px 1px rgba(255,255,255,.1),0 1px rgba(0,0,0,.12)}
#list td{max-width:280px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;}
#list2 td{max-width:280px;text-overflow: ellipsis;overflow: hidden;white-space: nowrap;}
#orderItem .orderTitle{word-break:keep-all;}
#orderItem .orderContent{word-break:break-all;}
#orderItem .orderContent img{max-width:100%}
#alert_frame img{max-width:100%}
</style>
<div class="wrapper">
	<div class="col-sm-12 col-md-8 col-lg-6 center-block" style="float: none;">
		<div class="panel panel">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);color: white;">
				自助下单
				<span class="pull-right">
					余额：<?php echo $userrow['rmb']?>元
				</span>
			</div>
			<div class="nav-tabs-alt">
				<ul class="nav nav-tabs nav-justified">
					<li class="active">
						<a href="#onlinebuy" data-toggle="tab">
							在线下单
						</a>
					</li>
					<li>
						<a href="#query" data-toggle="tab" id="tab-query">
							查询订单
						</a>
					</li>
				</ul>
				<div class="modal-body">
					<div id="myTabContent" class="tab-content">
						<div class="tab-pane fade in active" id="onlinebuy">
							<?php include TEMPLATE_ROOT. 'default/shop.inc.f.g.php'; ?>
						</div>
						<div class="tab-pane fade in" id="query">
							<ul class="list-group animated bounceIn">
								<li class="list-group-item">
									<?php echo $conf['gg_search']?>
								</li>
							</ul>
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon">
										查询内容
									</div>
									<input type="text" name="qq" id="qq3" value="" class="form-control" placeholder="请输入下单账号（留空则根据浏览器缓存查询）"
									required="">
								</div>
							</div>
							<input type="submit" id="submit_query" class="btn btn-primary btn-block"
							value="立即查询">
							<div id="result2" style="display:none;">
							<center><small><font color="#ff0000">手机用户可以左右滑动</font></small></center>
								<div class="table-responsive">
									<table class="table table-striped">
										<thead>
											<tr>
												<th>
													账号
												</th>
												<th>
													名称
												</th>
												<th>
													份数
												</th>
												<th class="hidden-xs">
													时间
												</th>
												<th>
													状态
												</th>
												<th>
													操作
												</th>
											</tr>
										</thead>
										<tbody id="list">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include './foot.php';?>
<script src="<?php echo $cdnpublic?>jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
<script src="<?php echo $cdnpublic?>jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script type="text/javascript">
var isModal=false;
var homepage=false;
var hashsalt=<?php echo $addsalt_js?>;
$(function() {
	$("img.lazy").lazyload({effect: "fadeIn"});
<?php if($conf['shoppingcart']==1){?>
$.ajax({
	type : "GET",
	url : "../ajax.php?act=cart_info",
	dataType : 'json',
	async: true,
	success : function(data) {
		if(data.count != null && data.count>0){
			$('#cart_count').html(data.count);
			$('#alert_cart').show();
		}
	}
});
<?php }?>
});
</script>
<script src="../assets/js/usershop.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>