<?php
$is_defend=true;
require '../includes/common.php';
if($islogin3==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if($_GET['mod']=='faka'){
	exit("<script language='javascript'>window.location.href='../?mod=faka&&id={$_GET['id']}&skey={$_GET['skey']}';</script>");
}
$title = '平台首页';
include 'head.php';

$scriptpath = str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
$scriptpath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$scriptpath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
?>
<link rel="stylesheet" href="<?php echo $cdnpublic?>toastr.js/latest/css/toastr.min.css">
<style>
img.logo{width:14px;height:14px;margin:0 5px 0 3px;}
.span_position{display:inline;background:red;border-radius:50%;width:10px;height:10px;position:absolute}
.nickname{overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width:100px;}
</style>
<div class="wrapper">
<div class="col-sm-12">
<?php
if($suprow['rmb']>4){
if(strlen($suprow['pwd'])<6 || is_numeric($suprow['pwd']) && strlen($suprow['pwd'])<=10 || $suprow['pwd']===$suprow['qq']){
	echo '<div class="alert alert-danger"><span class="btn-sm btn-danger">重要</span>&nbsp;你的密码过于简单，请不要使用较短的纯数字或自己的QQ号当做密码，以免造成资金损失！ <a href="uset.php?mod=user">点此修改密码</a></div>';
}elseif($suprow['user']===$suprow['pwd']){
	echo '<div class="alert alert-danger"><span class="btn-sm btn-danger">重要</span>&nbsp;你的用户名与密码相同，极易被黑客破解，请及时修改密码 <a href="uset.php?mod=user">点此修改密码</a></div>';
}
}
?>
</div>
	<div class="col-lg-4 col-md-6 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading font-bold" style="background: linear-gradient(to right,#14b7ff,#b221ff);padding: 15px;color: white;">
				<div class="widget-content text-right clearfix">
					<img src="<?php echo $faceimg ?>" alt="Avatar"
					width="66" class="img-circle img-thumbnail img-thumbnail-avatar pull-left">
					<h4><b>余额：<?php echo $suprow['rmb']?>元</b></h4>
					<span class="text-muted">
						<a href="recharge.php" class="btn btn-xs btn-success"><b>充值余额</b></a>&nbsp;<a href="tixian.php" class="btn btn-xs btn-info">申请提现</a>
					</span>
				</div>
			</div>
<table class="table">
	<tbody>
		<tr>
			<th class="text-center nickname">
				<font color="#a9a9a9">用户名</font><br><font size="4"><?php echo $nickname?></font>
			</th>
			<th class="text-center">
				<font color="#a9a9a9">UID</font><br><font size="4"><?php echo $suprow['sid']?></font>
			</th>
			<th class="text-center">
				<font color="#a9a9a9">今日收益</font><br><font size="4" id="income_today">0元</font>
			</th>
		</tr>
		<tr>
			<td><a href="shoplist.php" class="btn btn-primary btn-block"><i class="fa fa-shopping-cart"></i><br/><b>商品管理</b></a></td>
			<td><a href="fakalist.php" class="btn btn-success btn-block"><i class="fa fa-money"></i><br/><b>卡密管理</b></a></td>
            <td><a href="record.php" class="btn btn-info btn-block"><i class="fa fa-hashtag"></i><br/><b>收支明细</b></a></td>
		</tr>

	</tbody>
</table>
	</div>
</div>
    <div class="col-lg-4 col-md-6 col-sm-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">公告</h3>
            </div>
            <div class="panel-body">
                <?=$conf['sup_notice']??'暂无公告'?>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<?php include './foot.php';?>
<script src="<?php echo $cdnpublic?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
$.ajax({
	type : "GET",
	url : "ajax_user.php?act=msg",
	dataType : 'json',
	success : function(data) {
		if(data.code=='0'){
			$("#income_today").html(data.income_today+'元');
		}
	}
});
if(window.location.hash=='#chongzhi'){
	$("#userjs").modal('show');
}
	
</script>
</body>
</html>