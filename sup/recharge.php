<?php

include "../includes/common.php";
$title = "充值余额";
include "./head.php";
if ($islogin3 == 1) {
} else {
	exit("<script language='javascript'>window.location.href='./login.php?back=recharge';</script>");
}
?><style>
img.logo{width: 20px;margin: -2px 5px 0 5px;}
</style>
<div class="wrapper">
<div class="col-md-6">
<div class="panel panel-default">
    <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">
		充值余额
	</div>
	<div class="panel-body text-center">
			<b>我当前的账户余额：<span style="font-size:16px; color:#FF6133;"><?php echo $suprow["rmb"];?></span> 元</b>
			<hr>
			<input type="text" class="form-control" name="value" autocomplete="off" placeholder="输入要充值的余额"><br>
<?php 
if ($conf["alipay_api"]) {
	?><button type="submit" class="btn btn-default" id="buy_alipay"><img src="../assets/img/alipay.png" class="logo">支付宝</button>&nbsp;<?php 
}
if ($conf["qqpay_api"]) {
	?><button type="submit" class="btn btn-default" id="buy_qqpay"><img src="../assets/img/qqpay.png" class="logo">QQ钱包</button>&nbsp;<?php 
}
if ($conf["wxpay_api"]) {
	?><button type="submit" class="btn btn-default" id="buy_wxpay"><img src="../assets/img/wxpay.png" class="logo">微信支付</button>&nbsp;<?php 
}
?><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModa4" id="alink" style="visibility: hidden;"></button>
<hr>

	</div>
</div>
</div>
<div class="col-md-6">
	<div class="panel panel-default">
    <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">充值记录</div>
		  <div class="panel-body">

      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>充值金额</th><th>充值时间</th><th>状态</th></tr></thead>
          <tbody>
<?php 
$flag = false;
$rs = $DB->query("SELECT * FROM pre_suppoints WHERE sid='" . $suprow["sid"] . "' AND action='1' ORDER BY id DESC LIMIT 10");
while ($res = $rs->fetch()) {
	$flag = true;
	echo "<tr><td><b>" . $res["point"] . "</b></td><td>" . $res["addtime"] . "</td><td><font color=\"green\">已完成</font></td></tr>";
}
if (!$flag) {
	echo "<tr class=\"no-records-found\"><td colspan=\"99\">暂无充值记录</td></tr>";
}
?>          </tbody>
        </table>
      </div>
    </div>
  </div>
 </div>
</div>
<?php 
include "./foot.php";
?><script>
function dopay(type){
	var value=$("input[name='value']").val();
	if(value=='' || value==0){layer.alert('充值金额不能为空');return false;}
	$.get("ajax_user.php?act=recharge&type="+type+"&value="+value, function(data) {
		if(data.code == 0){
			window.location.href='../other/submit.php?type='+type+'&orderid='+data.trade_no;
		}else{
			layer.alert(data.msg);
		}
	}, 'json');
}
$(document).ready(function(){
$("#buy_alipay").click(function(){
	dopay('alipay')
});
$("#buy_qqpay").click(function(){
	dopay('qqpay')
});
$("#buy_wxpay").click(function(){
	dopay('wxpay')
});
})
</script>