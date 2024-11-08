<?php
/**
 * 分类管理
**/
include("../includes/common.php");
$title='分类管理';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

?>
<div class="wrapper">
  <div class="col-sm-12 col-md-10 col-lg-8 center-block" style="float: none;">

     
<?php
if($userrow['power']==0){
	showmsg('你没有权限使用此功能！',3);
}
$classhide = explode(',',$userrow['class']);
?>
<div class="panel panel-default">
    <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">
        <h3 class="panel-title"><font color="#fff">商品分类管理</font></h3>
    </div>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>分类名称</th><th>是否显示</th></tr></thead>
          <tbody><form id="classlist">
<?php

$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 ORDER BY sort ASC");
while($res = $rs->fetch())
{
	echo '<tr><td><input type="text" class="form-control input-sm" name="name'.$res['cid'].'" value="'.$res['name'].'" placeholder="分类名称" disabled></td><td>'.(in_array($res['cid'],$classhide)?'<span class="btn btn-sm btn-warning" onclick="setActive('.$res['cid'].',1)">隐藏</span>':'<span class="btn btn-sm btn-success" onclick="setActive('.$res['cid'].',0)">显示</span>').'</td></tr>';
}
?>
			</form>
          </tbody>
        </table>
      </div>
    </div>
</div>
</div>
<?php include './foot.php';?>
<script>
function setActive(cid,active) {
	$.ajax({
		type : 'GET',
		url : 'ajax_user.php?act=setClass&cid='+cid+'&active='+active,
		dataType : 'json',
		success : function(data) {
			window.location.reload();
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