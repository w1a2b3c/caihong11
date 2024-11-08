<?php
/**
 * 我的工单
**/
include("../includes/common.php");
$title='我的工单';
include './head.php';
if($islogin3==1){}else exit("<script language='javascript'>window.location.href='./login.php?back=workorder';</script>");
?>
<style>
.gdan_gout{width:100%;height:auto;background-color:#fff;padding-bottom:1em}
.gdan_txt{height:3em;line-height:3em;text-indent:1em;font-family:"微软雅黑";font-weight:800;}
.gdan_txt>span{position:absolute;right:3em;}
.gdan_zhugan{width:96%;height:auto;padding-top:1em;margin-left:2%;padding-left:.5em;padding-right:1em;margin-bottom:1em;border-top:dashed 1px #a9a9a9}
.gdan_kjia1{width:auto;margin-left:4em;margin-top:-3em}
.gdan_xiaozhi{width:100%;height:1em;color:#a9a9a9;margin-bottom:1em}
.gdan_xiaozhi>span{position:absolute;right:3em;}
.gdan_huifu{width:100%;height:auto;margin-top:1em;border-top:solid #ccc 1px}
.gdan_srk{width:98%;height:8em;margin-left:1%;margin-top:1em;border-color:#6495ed}
.gdan_huifu1{width:6em;height:2.5em;border:none;background-color:#1e90ff;color:#fff;margin:.5em 0 .5em 1%}
.gdan_jied{width:100%;height:3em;line-height:3em;text-align:center;color:#129DDE}
</style>
  <div class="wrapper">
    <div class="col-md-12 center-block" style="float: none;">
<?php

if($conf['workorder_open']==0){
	showmsg('当前站点未开启工单功能',3);
}

function display_type($type){
	global $conf;
	$types = explode('|', $conf['workorder_type']);
	if($type==0 || !array_key_exists($type-1,$types))
		return '其它问题';
	else
		return $types[$type-1];
}

function display_status($status){
	if($status==1)
		return '<font color="red">待补充</font>';
	elseif($status==2)
		return '<font color="green">已结单</font>';
	else
		return '<font color="blue">待处理</font>';
}

$count1=$DB->getColumn("SELECT count(*) FROM pre_workorder WHERE sid='{$suprow['sid']}' AND status=1");
$count2=$DB->getColumn("SELECT count(*) FROM pre_workorder WHERE sid='{$suprow['sid']}' AND status=0");
$count3=$DB->getColumn("SELECT count(*) FROM pre_workorder WHERE sid='{$suprow['sid']}'");

$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='add')
{
?>
<div class="panel panel-default">
<div class="panel-heading"><div class="pull-right"><a href="./workorder.php"><i class="fa fa-times"></i></a></div><h3 class="panel-title"><i class="fa fa-plus"></i>&nbsp;&nbsp;<b>提交工单</b></h3></div>
<div class="panel-body">
<form action="./workorder.php?my=add_submit" method="POST">
<div class="form-group">
<div class="input-group"><div class="input-group-addon">订单编号</div>
<?php
if(isset($_GET['orderid']) && $_GET['orderid'] && md5($_GET['orderid'].SYS_KEY.$_GET['orderid'])===$_GET['skey']){
	$orderid = intval($_GET['orderid']);
	$res=$DB->getRow("SELECT id,tid,input FROM pre_orders WHERE id='{$orderid}' LIMIT 1");
	$toolname=$DB->getColumn("SELECT name FROM pre_tools WHERE tid='{$res['tid']}' LIMIT 1");
	echo '<input type="text" name="orderid" value="'.$orderid.'_'.$toolname.'_'.$res['input'].'" class="form-control" disabled/><input type="hidden" name="orderid" value="'.$orderid.'"/><input type="hidden" name="skey" value="'.$_GET['skey'].'"/>';
}else{
	echo '<select name="orderid" class="form-control"><option value="0">选择异常的订单（非订单问题不用选）</option>';
	$rs=$DB->query("SELECT id,tid,input FROM pre_orders WHERE userid='{$suprow['sid']}' ORDER BY id DESC LIMIT 20");
	while($res = $rs->fetch()){
		$toolname=$DB->getColumn("SELECT name FROM pre_tools WHERE tid='{$res['tid']}' LIMIT 1");
		echo '<option value="'.$res['id'].'">'.$res['id'].'_'.$toolname.'_'.$res['input'].'</option>';
	}
	echo '</select>';
}
?>
</div>
</div>
<div class="form-group">
<div class="input-group"><div class="input-group-addon">问题类型</div>
	<select name="type" class="form-control">
	<?php foreach(explode('|', $conf['workorder_type']) as $key=>$value){
	echo '<option value="'.($key+1).'">'.$value.'</option>';
}?>
		<option value="0">其它问题</option>
	</select>
</div>
</div>
<div class="form-group">
<textarea class="form-control" name="content" rows="5" placeholder="填写描述信息" required></textarea>
</div>
<?php if($conf['workorder_pic']==1){?>
<div class="form-group">
<div class="input-group"><div class="input-group-addon">问题图片</div>
<input type="file" id="file" onchange="fileUpload()" style="display:none;"/>
<input type="text" class="form-control" id="picurl" name="picurl" value="" readonly onclick="fileView()"><span class="input-group-btn"><a href="javascript:fileSelect()" class="btn btn-success" title="上传图片"><i class="glyphicon glyphicon-upload"></i></a></span>
</div>
</div>
<?php }?>
<div class="form-group">
<div class="input-group">
<span class="input-group-addon" style="padding: 0">
<img id="codeimg" src="./code.php?r=<?php echo time();?>" height="43" onclick="this.src='./code.php?r='+Math.random();" title="点击更换验证码">
</span>
<input type="text" name="code" class="form-control input-lg" required="required" placeholder="输入验证码"/>
</div>
</div>
<input type="submit" class="btn btn-primary btn-block" value="提交"></form>
<br/><a href="./workorder.php">>>返回工单列表</a>
</div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
找不到要提交的订单？<a href="../?chadan=1">点击进入查询订单</a>，在订单详情页面点击【投诉订单】可以直接提交工单。
</div>
</div>
<?php
}
elseif($my=='view')
{
$id=intval($_GET['id']);
$rows=$DB->getRow("SELECT * FROM pre_workorder WHERE id='$id' AND sid='{$suprow['sid']}' LIMIT 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
$contents = explode('*',$rows['content']);
$myimg = $suprow['qq']?'//q2.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$suprow['qq'].'&src_uin='.$suprow['qq'].'&fid='.$suprow['qq'].'&spec=100&url_enc=0&referer=bu_interface&term_type=PC':'../assets/img/user.png';
$kfimg = 'https://imgcache.qq.com/open_proj/proj_qcloud_v2/mc_2014/work-order/css/img/custom-service-avatar.svg';
?>
<div class="panel panel-default">
<div class="panel-heading"><div class="pull-right"><a href="./workorder.php"><i class="fa fa-times"></i></a></div><h3 class="panel-title"><i class="fa fa-sticky-note-o"></i>&nbsp;&nbsp;<b>工单详情</b></h3></div>

<div class="gdan_gout">
	<div class="gdan_txt">沟通记录 - <?php echo count($contents)?><span>状态：<?php echo display_status($rows['status'])?></span></div>
	<!------------------开始沟通------------------------>
	<div class="gdan_zhugan" style="border: none;">
		<img src="<?php echo $myimg?>" class="img-circle" width="40"/>
		<div class="gdan_kjia1">
			<div class="gdan_xiaozhi">问题描述<span><?php echo $rows['addtime']?></span></div>
			<p><?php echo str_replace(array("\r\n", "\r", "\n"), "<br/>",htmlspecialchars($content[0]))?></p><br/>
			<p>订单编号：<?php echo $rows['orderid']?$rows['orderid']:'无订单号';?></p>
			<p>问题类型：<?php echo display_type($rows['type'])?></p>
			<?php echo $rows['picurl']?'<p>问题图片：[<a href="../'.$rows['picurl'].'" target="_blank">点此查看</a>]':null;?>
		</div>
	</div>
<?php
for($i=1;$i<count($contents);$i++){
	$content = explode('^',$contents[$i]);
	if(count($content)==3){
		echo '<div class="gdan_zhugan">
	<img src="'.($content[0]==1?$kfimg:$myimg).'" class="img-circle" width="40"/>
	<div class="gdan_kjia1">
	<div class="gdan_xiaozhi">'.($content[0]==1?'官方客服':$suprow['user']).'<span>'.$content[1].'</span></div>
	'.str_replace(array("\r\n", "\r", "\n"), "<br/>",htmlspecialchars($content[2])).'
	</div>
</div>';
	}
}
if($rows['status']==0){
?>
<div class="gdan_jied">请耐心等待客服处理</div>
<?php
}elseif($rows['status']==2){
?>
<div class="gdan_jied">此工单已经结单</div>
<?php
}elseif($rows['status']==1){
?>
<div class="gdan_huifu">
<form action="./workorder.php?my=reply&id=<?php echo $id?>" method="POST">
	<textarea class="gdan_srk" name="content" placeholder="可输入需要补充的内容，回复后官方客服将会收到你的消息！" required></textarea>
	<input type="submit" name="submit" value="提交回复" class="gdan_huifu1" />
	<input type="button" name="submit" value="完结工单" class="gdan_huifu1" style="background-color: mediumseagreen;" onclick="window.location.href='./workorder.php?my=complete&id=<?php echo $id?>'"/>
</form>
</div>
<?php
}
?>
</div>
<div class="gdan_txt"><a href="./workorder.php">>>返回工单列表</a></div>
</div>
<?php
}
elseif($my=='add_submit')
{
$orderid=intval($_POST['orderid']);
$type=intval($_POST['type']);
$content=str_replace(array('*','^','|'),'',trim(strip_tags(daddslashes($_POST['content']))));
$picurl=strip_tags(daddslashes($_POST['picurl']));
$code = isset($_POST['code'])?$_POST['code']:null;
if (!$code || strtolower($code) != $_SESSION['vc_code']) {
	unset($_SESSION['vc_code']);
	showmsg('验证码错误！');
}
if (empty($content)) {
	showmsg('描述信息不能为空！');
} elseif ($orderid>0 && !$DB->getRow("SELECT id FROM pre_orders WHERE id='$orderid' AND userid='{$suprow['sid']}' LIMIT 1") && md5($orderid.SYS_KEY.$orderid)!==$_POST['skey']) {
	showmsg('你只能选择自己的订单');
} elseif ($orderid>0 && $DB->getRow("SELECT id FROM pre_workorder WHERE orderid='$orderid' AND status<2 ORDER BY id DESC LIMIT 1")) {
	showmsg('请勿重复提交工单！');
} else {
	/*$res=$DB->getRow("select id,tid,addtime from pre_orders where id='{$orderid}' limit 1");
	$toolname=$DB->getColumn("select name from pre_tools where tid='{$res['tid']}' limit 1");
	if(strpos($toolname,'钻')!==false && time()-strtotime($res['addtime'])<48*3600){
		showmsg('当前商品处理需要一定的时间，请耐心等待！如果48小时以后还未到账请再提交工单！');
	}elseif(time()-strtotime($res['addtime'])<24*3600){
		showmsg('当前商品处理需要一定的时间，请耐心等待！如果24小时以后还未到账请再提交工单！');
	}*/
$sql="INSERT INTO `pre_workorder` (`sid`,`type`,`orderid`,`content`,`picurl`,`addtime`,`status`) VALUES (:sid, :type, :orderid, :content, :picurl, NOW(), 0)";
$data = [':sid'=>$suprow['sid'], ':type'=>$type, ':orderid'=>$orderid, ':content'=>$content, ':picurl'=>$picurl];
if($DB->exec($sql, $data)){
	$id = $DB->lastInsertId();
	if($conf['message_workorder']==1){
		\lib\MessageSend::workorder_new($id, $suprow['user'], $suprow['sid'], display_type($type), $content);
	}
	unset($_SESSION['vc_code']);
	showmsg('提交工单成功！请等待管理员处理。<br/><br/><a href="./workorder.php">>>返回工单列表</a>',1);
}else{
	showmsg('提交工单失败！'.$DB->error(),4);
}
}
}
elseif($my=='reply')
{
$id=intval($_GET['id']);
$rows=$DB->getRow("SELECT * FROM pre_workorder WHERE id='$id' AND sid='{$suprow['sid']}' LIMIT 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
elseif($rows['status']==2)
	showmsg('此工单已经结单',3);
elseif($rows['status']==0)
	showmsg('请耐心等待客服处理',3);
$content=str_replace(array('*','^','|'),'',trim(strip_tags(daddslashes($_POST['content']))));
if (empty($content)) {
	showmsg('补充信息不能为空！');
} else {
$contents = addslashes($rows['content']).'*0^'.$date.'^'.$content;
if($DB->exec("update pre_workorder set content=:content,status=0 where id=:id", [':content'=>$contents, ':id'=>$id])!==false){
	if($conf['message_workorder']==1){
		\lib\MessageSend::workorder_reply($id, $suprow['user'], $suprow['sid'], display_type($rows['type']), $content);
	}
	showmsg('回复工单成功！请等待管理员处理。<br/><br/><a href="./workorder.php">>>返回工单列表</a>',1);
}else{
	showmsg('回复工单失败！'.$DB->error(),4);
}
}
}
elseif($my=='complete')
{
$id=intval($_GET['id']);
$rows=$DB->getRow("SELECT * FROM pre_workorder WHERE id='$id' AND sid='{$suprow['sid']}' LIMIT 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
elseif($rows['status']==2)
	showmsg('此工单已经结单',3);
if($DB->exec("UPDATE pre_workorder SET status=2 WHERE id='{$id}'")!==false)
	exit("<script language='javascript'>alert('完结工单成功！');history.go(-1);</script>");
else
	showmsg('完结工单失败！'.$DB->error(),4);
}
elseif($my=='delete')
{
$id=intval($_GET['id']);
$sql="DELETE FROM pre_workorder WHERE id='$id' AND sid='{$suprow['sid']}'";
if($DB->exec($sql)!==false)
	exit("<script language='javascript'>alert('删除成功！');history.go(-1);</script>");
else
	showmsg('删除失败！'.$DB->error(),4);
}
else
{
?>
<div class="panel panel-default">
<table class="table table-bordered">
<tbody>
<tr height="25">
<td align="center"><font color="#808080"><b><i class="fa fa-exclamation-circle"></i>待我处理</b></br><b><?php echo $count1?></b></font></td>
<td align="center"><font color="#808080"><b><i class="fa fa-clock-o"></i>处理中</b></br></span><b><?php echo $count2?></b></font></td>
<td align="center"><font color="#808080"><b><i class="fa fa-check-circle"></i>全部工单</b></br><b><?php echo $count3?></b></font></td>
</tr>
</tbody>
</table>
</div>

<div class="panel panel-info" id="workorder_list">
     <div class="panel-heading"><h3 class="panel-title"><i class="fa fa-reorder"></i>&nbsp;&nbsp;<b>我的工单</b></h3></div>
	 <div class="panel-body"><a href="./workorder.php?my=add" class="btn btn-primary"><i class="fa fa-plus"></i>&nbsp;提交工单</a></div>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>类型</th><th>订单号</th><th>问题描述</th><th>状态</th><th>提交时间</th><th>操作</th></tr></thead>
          <tbody>
<?php
$numrows=$DB->getColumn("SELECT count(*) from pre_workorder WHERE sid='{$suprow['sid']}'");

$pagesize=30;
$pages=intval($numrows/$pagesize);
if ($numrows%$pagesize)
{
 $pages++;
 }
if (isset($_GET['page'])){
$page=intval($_GET['page']);
}
else{
$page=1;
}
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pre_workorder WHERE sid='{$suprow['sid']}' ORDER BY id DESC LIMIT $offset,$pagesize");
while($res = $rs->fetch())
{
$content=explode('*',$res['content']);
$content=mb_substr($content[0], 0, 16, 'utf-8');
echo '<tr><td><b>'.$res['id'].'</b></td><td>'.display_type($res['type']).'</td><td><a href="javascript:showOrder('.$res['orderid'].',\''.md5($res['orderid'].SYS_KEY.$res['orderid']).'\')" title="查询订单详情">'.$res['orderid'].'</a></td><td><a href="./workorder.php?my=view&id='.$res['id'].'">'.htmlspecialchars($content).'</a></td><td>'.display_status($res['status']).'</td><td>'.$res['addtime'].'</td><td><a href="./workorder.php?my=view&id='.$res['id'].'" class="btn btn-info btn-xs">查看</a>&nbsp;<a href="./workorder.php?my=delete&id='.$res['id'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此工单吗？\');">删除</a></td></tr>';
}
?>
          </tbody>
        </table>
      </div>
<?php
echo'<div class="text-center"><ul class="pagination">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="workorder.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="workorder.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
for ($i=1;$i<$page;$i++)
echo '<li><a href="workorder.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
if($pages>=10)$s=10;
else $s=$pages;
for ($i=$page+1;$i<=$s;$i++)
echo '<li><a href="workorder.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '';
if ($page<$pages)
{
echo '<li><a href="workorder.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="workorder.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul></div>';
#分页
}
?>
    </div>
  </div>
</div>
<?php include './foot.php';?>
<script>
function fileSelect(){
	$("#file").trigger("click");
}
function fileView(){
	var picurl = $("#picurl").val();
	if(picurl=='') {
		return;
	}
	if(picurl.indexOf('http') == -1)picurl = '../'+picurl;
	layer.open({
		type: 1,
		area: ['360px', '400px'],
		title: '图片查看',
		shade: 0.3,
		anim: 1,
		shadeClose: true,
		content: '<center><img width="300px" src="'+picurl+'"></center>'
	});
}
function fileUpload(){
	var fileObj = $("#file")[0].files[0];
	if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
		return;
	}
	var formData = new FormData();
	formData.append("do","upload");
	formData.append("type","user");
	formData.append("file",fileObj);
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		url: "ajax_user.php?act=uploadimg",
		data: formData,
		type: "POST",
		dataType: "json",
		cache: false,
		processData: false,
		contentType: false,
		success: function (data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('上传图片成功');
				$("#picurl").val(data.url);
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	})
}
function showOrder(id,skey){
	if(id==0)return false;
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	var status = ['<span class="label label-primary">待处理</span>','<span class="label label-success">已完成</span>','<span class="label label-warning">处理中</span>','<span class="label label-danger">异常</span>','<font color=red>已退款</font>'];
	$.ajax({
		type : "POST",
		url : "../ajax.php?act=order",
		data : {id:id,skey:skey},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var item = '<table class="table table-condensed table-hover">';
				item += '<tr><td colspan="6" style="text-align:center"><b>订单基本信息</b></td></tr><tr><td class="info">订单编号</td><td colspan="5">'+id+'</td></tr><tr><td class="info">商品名称</td><td colspan="5">'+data.name+'</td></tr><tr><td class="info">订单金额</td><td colspan="5">'+data.money+'元</td></tr><tr><td class="info">购买时间</td><td colspan="5">'+data.date+'</td></tr><tr><td class="info">下单信息</td><td colspan="5">'+data.inputs+'</td></tr><tr><td class="info">订单状态</td><td colspan="5">'+status[data.status]+'</td></tr>';
				if(data.list && data.list.order_state){
					item += '<tr><td colspan="6" style="text-align:center"><b>订单实时状态</b></td><tr><td class="warning">下单数量</td><td>'+data.list.num+'</td><td class="warning">下单时间</td><td colspan="3">'+data.list.add_time+'</td></tr><tr><td class="warning">初始数量</td><td>'+data.list.start_num+'</td><td class="warning">当前数量</td><td>'+data.list.now_num+'</td><td class="warning">订单状态</td><td><font color=blue>'+data.list.order_state+'</font></td></tr>';
				}else if(data.kminfo){
					item += '<tr><td colspan="6" style="text-align:center"><b>以下是你的卡密信息</b></td><tr><td colspan="6">'+data.kminfo+'</td></tr>';
				}else if(data.result){
					item += '<tr><td colspan="6" style="text-align:center"><b>处理结果</b></td><tr><td colspan="6">'+data.result+'</td></tr>';
				}
				if(data.alert){
					item += '<tr><td colspan="6" style="text-align:center"><b>商品简介</b></td><tr><td colspan="6">'+data.desc+'</td></tr>';
				}
				item += '</table>';
				layer.open({
				  type: 1,
				  title: '订单详细信息',
				  skin: 'layui-layer-rim',
				  content: item
				});
			}else{
				layer.alert(data.msg);
			}
		}
	});
}
</script>
</body>
</html>