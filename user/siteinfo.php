<?php
/**
 * 站点信息
**/
include("../includes/common.php");
$title='站点信息';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<div class="wrapper">
<?php
if($userrow['power']==0){
	showmsg('你没有权限使用此功能！',3);
}
?>
<div class="col-md-6 col-sm-12">
	<div class="panel panel-default">
		<div class="panel-heading font-bold text-center" style="background: linear-gradient(to right,#14b7ff,#b221ff);"><h3 class="panel-title"><font color="#fff"><i class="fa fa-globe"></i>&nbsp;&nbsp;<b>我的站点信息</b></font></h3></div>
		<ul class="list-group no-radius">
		<?php if($userrow['power']>0){?>
			<li class="list-group-item"><b>通知提醒：</b>你当前有<font color="orange"><b id="tiaosu">0</b></font>条信息未阅读<a href="./message.php" class="btn btn-primary btn-xs pull-right">立即查看</a></li>
			<li style="font-weight:bold" class="list-group-item">我的域名：<a href="http://<?php echo $userrow['domain']?>/" target="_blank" rel="noreferrer"><?php echo $userrow['domain']?></a><a href="uset.php?mod=site" class="btn btn-info btn-xs pull-right">编辑信息</a></li>
			<?php if($conf['fanghong_api']){?>
			<li style="font-weight:bold;overflow: hidden;" class="list-group-item">防红链接：<a href="javascript:;" id="copy-btn" data-clipboard-text="" >Loading...</a>&nbsp;&nbsp;&nbsp;<span class="pull-right"><button class="btn btn-default btn-xs" id="recreate_url">重新生成</button>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="layer.alert('防红链接：该链接可以在QQ直接打开的您的网站，方便推广！<br />Tips：点击短网址即可复制哦~',{icon: 3,title: '小提示',skin: 'layui-layer-molv layui-layer-wxd'});" class="btn btn-info btn-xs">说明</a></span></li>
			<?php }?>
			<li style="font-weight:bold" class="list-group-item">网站名称：<font color="blue"><?php echo $userrow['sitename']?></font></li>
			<li style="font-weight:bold" class="list-group-item">站点类型：<?php echo ($userrow['power']==2?'<font color=red>专业版</font>':'<font color=red>普及版</font>')?>&nbsp;<?php if($conf['fenzhan_upgrade']>0 && $userrow['power']==1){echo '<a href="upsite.php" class="btn btn-danger btn-xs pull-right">升级站点</a>';}else{echo '<a href="./sitelist.php" class="btn btn-danger btn-xs pull-right">下级管理</a>';}?></li>
			<?php if($conf['fenzhan_expiry']>0){?>
			<li style="font-weight:bold" class="list-group-item">到期时间：<font color="orange"><?php echo $userrow['endtime']?></font> <a href="renew.php" class="btn btn-primary btn-xs pull-right">立即续期</a></li>
			<?php }?>
			<?php if($conf['appcreate_open']==1){?><li style="font-weight:bold" class="list-group-item">客户端APP：<?php echo ($userrow['appurl']?'<a href="'.$userrow['appurl'].'" target="_blank" style="color:#337ab7;">点击下载</a>':'<font color="grey">未生成</font>');?><a href="appCreate.php" class="btn btn-warning btn-xs pull-right">在线生成</a></li><?php }?>
	<?php }else{?>
	<li style="font-weight:bold" class="list-group-item">你还未开通分站<br/><a href="regsite.php" class="btn btn-primary btn-sm btn-block">点此开通分站</a></li>
	<?php }?>
	</ul>
	</div>
</div>

<?php if($userrow['power']>0 || $conf['user_level']==1){?>
	<div class="col-md-6 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading font-bold text-center" style="background: linear-gradient(to right,#14b7ff,#b221ff);">
				<h3 class="panel-title"><font color="#fff"><i class="fa fa-volume-up"></i>&nbsp;&nbsp;<b>站点公告</b></font></h3>
			</div>
			<?php echo $conf['gg_panel']?>
		</div>
	</div>
<?php }?>
<?php include './foot.php';?>
<script src="<?php echo $cdnpublic?>clipboard.js/1.7.1/clipboard.min.js"></script>
<script>
$(document).ready(function(){
var clipboard = new Clipboard('#copy-btn');
clipboard.on('success', function (e) {
	layer.msg('复制成功！', {icon: 1});
});
clipboard.on('error', function (e) {
	layer.msg('复制失败，请长按链接后手动复制', {icon: 2});
});

$("#recreate_url").click(function(){
	var self = $(this);
	if (self.attr("data-lock") === "true") return;
	else self.attr("data-lock", "true");
	var ii = layer.load(1, {shade: [0.1, '#fff']});
	$.get("ajax_user.php?act=create_url&force=1", function(data) {
		layer.close(ii);
		if(data.code == 0){
			layer.msg('生成链接成功');
			$("#copy-btn").html(data.url);
			$("#copy-btn").attr('data-clipboard-text',data.url);
		}else{
			layer.alert(data.msg);
		}
		self.attr("data-lock", "false");
	}, 'json');
});
	$.ajax({
		type : "GET",
		url : "ajax_user.php?act=create_url",
		dataType : 'json',
		async: true,
		success : function(data) {
			if(data.code == 0){
				$("#copy-btn").html(data.url);
				$("#copy-btn").attr('data-clipboard-text',data.url);
			}else{
				$("#copy-btn").html(data.msg);
			}
		}
	});
});
</script>
</body>
</html>