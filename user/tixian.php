<?php
/**
 * 余额提现
**/
include("../includes/common.php");
$title='余额提现';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<div class="wrapper">
<div class="col-xs-12">
<?php

if($conf['fenzhan_tixian']==0)showmsg('当前站点未开放提现功能！');
if($userrow['power']==0){
	showmsg('你没有权限使用此功能！',3);
}

function display_zt($zt,$id){
	if($zt==2)
		return '<a onclick="getResult('.$id.')"><font color=red>查看失败原因</font></a>';
	elseif($zt==1)
		return '<font color=green>已完成</font>';
	else
		return '<font color=blue>未完成</font>';
}
function display_type($type){
	if($type==1)
		return '微信';
	elseif($type==2)
		return 'QQ钱包';
	else
		return '支付宝';
}


if(isset($_POST['money']))
{
if(!checkRefererHost())exit();
$money=daddslashes(strip_tags($_POST['money']));
if(!is_numeric($money) || !preg_match('/^[0-9.]+$/', $money))showmsg('提现金额输入不规范',3);
$realmoney = round($money*$conf['tixian_rate']/100,2);
if($conf['fenzhan_skimg']==1 && !file_exists(ROOT.'assets/img/skimg/sk_'.$userrow['zid'].'.png')){
	exit("<script language='javascript'>alert('您还未上传收款图！');window.location.href='uset.php?mod=skimg';</script>");
}elseif(empty($userrow['pay_account']) || empty($userrow['pay_name'])){
	exit("<script language='javascript'>alert('您还未设置收款账号！');history.go(-1);</script>");
}

$DB->beginTransaction();
$userrow = $DB->getRow("SELECT * FROM pre_site WHERE zid='{$userrow['zid']}' LIMIT 1 FOR UPDATE");
if($conf['tixian_limit']==1 && $money>$userrow['rmbtc'] || $money>$userrow['rmb'] || $money<=0){
	exit("<script language='javascript'>alert('所输入的提现金额大于你所拥有的余额！');history.go(-1);</script>");
}
if($money<$conf['tixian_min']){
	exit("<script language='javascript'>alert('单笔提现金额不能低于{$conf['tixian_min']}元！');history.go(-1);</script>");
}
$sql = "INSERT INTO `pre_tixian` (`zid`, `money`, `realmoney`, `pay_type`, `pay_account`, `pay_name`, `status`, `addtime`) VALUES (:zid, :money, :realmoney, :pay_type, :pay_account, :pay_name, 0, NOW())";
$data = [':zid'=>$userrow['zid'], ':money'=>$money, ':realmoney'=>$realmoney, ':pay_type'=>$userrow['pay_type'], ':pay_account'=>$userrow['pay_account'], ':pay_name'=>$userrow['pay_name']];
if($DB->exec($sql, $data)){
	if($DB->exec("UPDATE pre_site SET rmb=rmb-:money,rmbtc=rmbtc-:money WHERE zid=:zid", [':money'=>$money, ':zid'=>$userrow['zid']])){
		addPointRecord($userrow['zid'], $money, '提现', '站点余额提现'.$money.'元');
		$DB->commit();
		exit("<script language='javascript'>alert('提现操作成功，本次实际到账金额:{$realmoney}元，请等待管理员人工转账！');window.location.href='tixian.php';</script>");
	}else{
		$DB->rollBack();
		exit("<script>alert('提现操作失败！');location.href='tixian.php';</script>");
	}
}else{
	exit("<script language='javascript'>alert('提现失败！');history.go(-1);</script>");
}
}

$numrows=$DB->getColumn("SELECT count(*) FROM pre_tixian WHERE zid='{$userrow['zid']}'");

?>
<div class="panel panel-default">
    <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">
		余额提现
	</div>
	<div class="list-group-item list-group-item-info">
	<?php if($conf['fenzhan_skimg']==1 && file_exists(ROOT.'assets/img/skimg/sk_'.$userrow['zid'].'.png')){?>
		已绑定结算账号信息：结算方式：<?php echo display_type($userrow['pay_type']); ?>
		<br>
		当前收款图：<img onclick="img('<?php echo $userrow['zid'] ?>')"  width="100" src="<?php echo '../assets/img/skimg/sk_'.$userrow['zid'].'.png' ?>">
		<hr>
		<a href="uset.php?mod=skimg" class="btn btn-warning btn-sm">修改收款图</a>&nbsp;&nbsp;<a href="uset.php?mod=user" class="btn btn-info btn-sm">修改提现方式</a>
	<?php }elseif($conf['fenzhan_skimg']==1){?>
		请先上传收款图！ <a href="uset.php?mod=skimg" class="btn btn-warning btn-sm">点此上传</a>
	<?php }elseif(!empty($userrow['pay_account']) && !empty($userrow['pay_name'])){?>
		已绑定结算账号信息：结算方式：<?php echo display_type($userrow['pay_type']); ?> 账号：<?php echo $userrow['pay_account']; ?> 姓名：<?php echo $userrow['pay_name']; ?> <a href="uset.php?mod=user" class="btn btn-warning btn-sm">修改绑定</a>
	<?php }else{?>
		请先绑定收款账号！ <a href="uset.php?mod=user" class="btn btn-warning btn-sm">点此设置</a>
	<?php }?>
	</div>
	<div class="list-group-item list-group-item-warning">
		单笔提现金额最低<?php echo $conf['tixian_min']; ?>元。提现手续费<?php echo (100-$conf['tixian_rate'])?>%。
	</div>
	<div class="panel-body">
		<form method="post" class="form-horizontal">
			<input type="hidden" name="action" value="tixian">
			<?php if($conf['tixian_limit']==1){
			$notmoney = $userrow['rmb']-$userrow['rmbtc'];
			if($notmoney<0)$notmoney=0;
			$enmoney = $userrow['rmbtc'];
			if($enmoney>$userrow['rmb'])$enmoney = $userrow['rmb'];
			?>
			<div class="form-group">
				<label class="col-lg-3 control-label">不可提现余额</label>
				<div class="col-lg-8">
					<input type="text" class="form-control" value="<?php echo $notmoney?> 元" disabled>
					<pre>注：充值、签到等所得余额无法进行提现，但是可以进行消费~</pre>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-3 control-label">可提现余额</label>
				<div class="col-lg-8">
					<input type="text" name="tmoney" class="form-control" value="<?php echo $enmoney?> 元" disabled>
					<pre>注：只有网站用户消费获得的提成才可提现<?php echo $conf['tixian_days']>0?'，订单'.$conf['tixian_days'].'天后会自动加入可提现余额':null;?>~</pre>
				</div>
			</div>
			<?php }else{?>
			<div class="form-group">
				<label class="col-lg-3 control-label">账户余额</label>
				<div class="col-lg-8">
					<input type="text" name="tmoney" class="form-control" value="<?php echo $userrow['rmb']?> 元" disabled>
				</div>
			</div>
			<?php }?>
			<div class="form-group">
				<label class="col-lg-3 control-label">提现金额</label>
				<div class="col-lg-8">
					<div class="input-group"><input type="text" name="money" class="form-control" placeholder="输入要提现金额" required><a href="javascript:inputMoney()" class="input-group-addon">全部</a></div>
				</div>
			</div>

			<div class="form-group">
				<div class="col-lg-offset-3 col-lg-8">
					<button class="btn btn-primary" type="submit">确认提现</button>
				</div>
			</div>
		</form>
	</div>
</div>
</div>
<div class="col-xs-12">
	<div class="panel panel-default">
    <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">提现记录</div>
		  <div class="panel-body">

      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>金额</th><th>实际到账</th><th>提现方式</th><th>提现账号</th><th>姓名</th><th>申请时间</th><th>完成时间</th><th>状态</th></tr></thead>
          <tbody>
<?php

$rs=$DB->query("SELECT * FROM pre_tixian WHERE zid='{$userrow['zid']}' ORDER BY id DESC LIMIT 10");
while($res = $rs->fetch())
{
echo '<tr><td><b>'.$res['id'].'</b></td><td>'.$res['money'].'</td><td>'.$res['realmoney'].'</td><td>'.display_type($res['pay_type']).'</td><td>'.$res['pay_account'].'</td><td>'.$res['pay_name'].'</td><td>'.$res['addtime'].'</td><td>'.($res['status']==1?$res['endtime']:null).'</td><td>'.display_zt($res['status'],$res['id']).'</td></tr>';
}
?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
 </div>
</div>
<?php include './foot.php';?>
<script>
function inputMoney(){
	var tmoney = $("input[name='tmoney']").val();
	$("input[name='money']").val(tmoney.split(' ')[0]);
}
function getResult(id) {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : 'POST',
		url : 'ajax_user.php?act=tixian_note',
		data : {id:id},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.result!=null)
				layer.alert(data.result);
			else
				layer.alert('提现失败，如有疑问请联系主站站长');
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
</script>
</body>
</html>