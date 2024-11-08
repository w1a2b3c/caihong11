<?php
$is_defend=true;
require '../includes/common.php';
if($islogin3==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if($suprow['power']==2){
	$type = '0,2,4';
}elseif($suprow['power']==1){
	$type = '0,2,3';
}else{
	$type = '0,1';
}
$msgcount=$DB->getColumn("SELECT count(*) FROM pre_message WHERE type IN ($type) AND active=1");
$msgread = explode(',',$suprow['msgread']);
$limit=isset($_GET['limit'])?intval($_GET['limit']):10;
$rs=$DB->query("SELECT * FROM pre_message WHERE type IN ($type) AND active=1 ORDER BY id DESC LIMIT 0,$limit");
$msgrow=array();
while($res = $rs->fetch()){
	if(in_array($res['id'],$msgread))$res['read']=true;
	else $res['read']=false;
	$msgrow[]=$res;
}

$title = '消息列表';
include 'head.php';
?>
<style>
.msg-head{text-align: center;min-width: 360px;padding: 7px;background-color: #f9f9f9 !important;}
.msg-body{padding: 15px;margin-bottom: 20px;}
</style>
<div class="wrapper">
<div class="col-sm-12">	
<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">消息列表</div>
<div class="panel-body"><a href="javascript:msg_read_all();" class="btn btn-primary">一键已读</a></div>
<div class="well well-sm" style="margin: 0;">我共收到 <b><?php echo $msgcount?></b> 个消息</div>      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead><th>操作</th><th>通知标题</th><th>接收时间</th><th>阅读状态</th></tr></thead>
<?php
foreach($msgrow as $row){
echo '
	<tr class="onclick '.($row['read']?'':'warning').'"  >
	<td><a class="btn btn-info btn-xs" onclick="show('.$row['id'].')">查看</a></td>
	<td>'.$row['title'].'</td>
	<td>'.$row['addtime'].'</td>
	<td>'.($row['read']?'<span class="label label-success">已读</span>':'<span class="label label-warning">未读</span>').'</td>
</tr>';
}
if($msgcount==0){
	echo '<tr><td class="text-center"><font color="grey">消息列表空空如也</font></td></tr>';
}
?>	
          <tbody>
          </tbody>
        </table>
		<?php if($msgcount>$limit){?>
		<div class="list-group-item"><center><a href="?limit=<?php echo $limit+10;?>" id="btnload">加载更多</a></center></div>
		<?php }?>
      </div>
</div>
</div>
</div>
<?php include './foot.php';?>
<script>
function msg_read_all()
{
	$.ajax({
		type : 'GET',
		url : 'ajax_user.php?act=msg_read_all',
		dataType : 'json',
		success : function(data) {
			if(data.code==0){
				window.location.reload();
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});        	
}
function show(id) {
	$.ajax({
		type : 'GET',
		url : 'ajax_user.php?act=msginfo&id='+id,
		dataType : 'json',
		success : function(data) {
			if(data.code==0){
				layer.open({
				  type: 1,
				  skin: 'layui-layer-lan',
				  anim: 2,
				  btn: ['关闭窗口'],
				  btnAlign:'c',
				  shadeClose: true,
				  title: '查看消息内容',
				  content: '<div class="msg-head"><h4><b>'+data.title+'</b></h4><small><font color="grey">管理员  '+data.date+'</font></small></div><div class="msg-body">'+data.content+'</div>',
				  end: function(){
					  window.location.reload()
				  }
				});
			}else{
				layer.alert(data.msg);
			}
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