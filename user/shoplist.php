<?php
/**
 * 商品管理
**/
include("../includes/common.php");
$title='商品管理';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

unset($islogin2);
?>
<div class="wrapper">
  <div class="col-sm-12">
<?php
if($userrow['power']==0){
	showmsg('你没有权限使用此功能！',3);
}

$my=isset($_GET['my'])?$_GET['my']:null;

$rs=$DB->query("SELECT * FROM pre_class WHERE active=1 ORDER BY sort ASC");
$select='<option value="0">请选择分类</option>';
$shua_class[0]='未分类';
while($res = $rs->fetch()){
	$shua_class[$res['cid']]=$res['name'];
	$select.='<option value="'.$res['cid'].'">'.$res['name'].'</option>';
}
?>
<div class="modal fade" align="left" id="search2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">商品分类</h4>
      </div>
      <div class="modal-body">
      <form action="shoplist.php" method="GET">
      <select name="cid" class="form-control">
		<?php echo $select?>
		</select><br/>
      <input type="submit" class="btn btn-primary btn-block" value="查看"></form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<?php
$price_obj = new \lib\Price($userrow['zid'],$userrow);

if($my=='edit')
{
$tid=intval($_GET['tid']);
$row=$DB->getRow("SELECT * FROM pre_tools WHERE tid='$tid' LIMIT 1");
$price_obj->setToolInfo($tid,$row);
echo '<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">修改商品价格 <a href="./shoplist.php" style="color:#fff00f"> 返回 </a></div>';
echo '<div class="panel-body">';
echo '<form action="./shoplist.php?my=edit_submit&tid='.$tid.'" method="POST">
<div class="form-group">
<label>商品名称:</label><br>
<input type="text" class="form-control" name="name" value="'.$row['name'].'" disabled>
</div>';
if($userrow['power']==2)echo '
<div class="form-group">
<label>成本价格:</label><br>
<input type="text" class="form-control" name="cost2" value="'.$price_obj->getToolCost2($tid).'" disabled>
</div>
<div class="form-group">
<label>下级分站代理价格:</label><br>
<input type="text" class="form-control" name="cost" value="'.$price_obj->getToolCost($tid).'">
</div>';
else echo '
<div class="form-group">
<label>成本价格:</label><br>
<input type="text" class="form-control" name="cost" value="'.$price_obj->getToolCost($tid).'" disabled>
</div>';
echo '<div class="form-group">
<label>销售价格:</label><br>
<input type="text" class="form-control" name="price" value="'.$price_obj->getToolPrice($tid).'">
</div>
<div class="form-group">
<label>是否上架:</label><br>
<select class="form-control" name="del" default="'.$price_obj->getToolDel($tid).'"><option value="0">1_是</option><option value="1">0_否</option></select>
</div>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form>
</div>
</div>';
echo '
<script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
</script>';
}
elseif($my=='edit_submit')
{
$tid=intval($_GET['tid']);
$rows=$DB->getRow("SELECT * FROM pre_tools WHERE tid='$tid' LIMIT 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
$price_obj->setToolInfo($tid,$rows);
$price=round(daddslashes($_POST['price']),2);
$mainprice = $price_obj->getMainPrice();//主站商品售价
$maincost = $price_obj->getMainCost();//主站商品成本价
$del=intval($_POST['del']);
if(!is_numeric($price) || !preg_match('/^[0-9.]+$/', $price))showmsg('价格输入不规范',3);
if($userrow['power']==2){
	$cost=round(daddslashes($_POST['cost']),2);
	if(!is_numeric($cost) || !preg_match('/^[0-9.]+$/', $cost))showmsg('价格输入不规范',3);
	if($cost<$price_obj->getToolCost2($tid)){
		showmsg('下级代理价格不能低于成本价格！',3);
	}
	if($price<$cost){
		showmsg('销售价格不能低于下级代理价格！',3);
	}
	if($conf['fenzhan_pricelimit']==1 && $maincost>0 && ($maincost>1 && $cost>$maincost*2 || $maincost<=1 && $price>2))
		showmsg('下级代理价格最高不能超过原代理价格的2倍（低于1元的商品，代理价格最高不能超过2元）',3);
}else{
	if($price<$price_obj->getToolCost($tid)){
		showmsg('销售价格不能低于成本价格！',3);
	}
	$cost=0;
}
if($conf['fenzhan_pricelimit']==1 && ($mainprice>1 && $price>$mainprice*2 || $mainprice<=1 && $price>2))
	showmsg('商品售价最高不能超过原售价的2倍（低于1元的商品，售价最高不能超过2元）',3);
if($price_obj->setPriceInfo($tid,$del,$price,$cost))
	showmsg('修改商品成功！<br/><br/><a href="./shoplist.php">>>返回商品列表</a>',1);
else
	showmsg('修改商品失败！'.$DB->error(),4);
}
elseif($my=='reset')
{
if($DB->exec("UPDATE pre_site SET price=NULL WHERE zid='{$userrow['zid']}'"))
	showmsg('重置成功！<br/><br/><a href="./shoplist.php">>>返回商品列表</a>',1);
else
	showmsg('重置失败！'.$DB->error(),4);
}
else
{
if(isset($_GET['cid'])){
	$cid = intval($_GET['cid']);
	$numrows=$DB->getColumn("SELECT count(*) FROM pre_tools WHERE cid='$cid' AND active=1");
	$sql=" cid='$cid' AND active=1";
	$con='
	<div class="panel panel-default"><div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">'.$shua_class[$cid].'分类 - [<a href="shoplist.php" style="color:#fff00f">查看全部</a>]</div>
	<div class="well well-sm" style="margin: 0;">分类 '.$shua_class[$cid].' 共有 <b>'.$numrows.'</b> 个商品</div>
	<div class="wrapper">
    <a href="#" data-toggle="modal" data-target="#search2" id="search2" class="btn btn-primary"><i class="fa fa-navicon"></i>&nbsp;分类查看</a>&nbsp;<a class="btn btn-info" href="javascript:void(0)" onclick="up_price('.$cid.')"><i class="fa fa-plus-circle"></i>&nbsp;提升售价</a></div>';
	$link='&cid='.$cid;
}else{
	$numrows=$DB->getColumn("SELECT count(*) FROM pre_tools WHERE active=1");
	$sql=" active=1";
	$con='
	<div class="panel panel-default"><div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">商品列表</div>
	<div class="well well-sm" style="margin: 0;">系统共有 <b>'.$numrows.'</b> 个商品 - 提升价格赚的更多哦！提高价格最好不要太贵了否则没人买的哦！</div>
    <div class="wrapper">
    <a href="#" data-toggle="modal" data-target="#search2" id="search2" class="btn btn-primary"><i class="fa fa-navicon"></i>&nbsp;分类查看</a>&nbsp;<a class="btn btn-success" onclick="reset_price(0)" href="javascript:void(0)"><i class="fa fa-refresh"></i>&nbsp;恢复价格</a>&nbsp;<a class="btn btn-info" href="javascript:void(0)" onclick="up_price(0)"><i class="fa fa-plus-circle"></i>&nbsp;提升售价</a></div>';
}

echo $con;

?>
      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead><tr><th>操作</th><th>商品名称</th><th>成本价格</th><?php if($userrow['power']==2){?><th style="font-size:14px">下级价格</th><?php }?><th>销售价格</th><th>状态</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pre_tools WHERE{$sql} ORDER BY sort ASC LIMIT $offset,$pagesize");
while($res = $rs->fetch())
{
	$price_obj->setToolInfo($res['tid'],$res);
echo '<tr>
			<td>
				<a href="./shoplist.php?my=edit&tid='.$res['tid'].'" class="btn btn-info btn-xs">编辑</a>
			</td>
			<td><b><a title="点此下单" style="color:#000" href="./shop.php?cid='.$res['cid'].'&tid='.$res['tid'].'">'.$res['name'].'</a></b></td>
			<td><font color="#FF0000">'.($userrow['power']==2?$price_obj->getToolCost2($res['tid']).'元</font> </td>
			<td><font color="#9400D3">'.$price_obj->getToolCost($res['tid']):$price_obj->getToolCost($res['tid'])).'元</font></td><td><font color="#FF0ff0">'.$price_obj->getToolPrice($res['tid']).'元</font> </td>
			<td>'.($price_obj->getToolDel($res['tid'])==1 || $res['close']==1?'<font color=red>已下架</font>':'<font color=green>上架中</font>').'</td>
		</tr>';}
?>
		          
          </tbody>
        </table>
<?php
echo'<ul class="pagination"  style="margin-left:1em">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="shoplist.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="shoplist.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="shoplist.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="shoplist.php?page='.$i.$link.'">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="shoplist.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="shoplist.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}?></div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
修改价格之后首页价格没变化？退出当前登录的账号后首页才能看到你设定的售价，否则看到的都是成本价。
</div>
</div>

</div>
<?php include './foot.php';?>
<script>
function reset_price(cid){
	if(confirm('是否要重置所有商品价格设定，恢复到最初状态？')){
		$.ajax({
			type:"post",
			url:"ajax_user.php?act=reset_price",
			data:{
				cid:cid
			},
			dataType:"json",
			success:function(data){
				if(data.code==0){
					layer.alert('恢复价格成功！',{icon:1},function(){
                      window.location.reload();
                    });
				}else{
					layer.alert(data.msg);
				}
			}
		});
	}
}
function up_price(cid){
    layer.prompt({title: '价格提升百分比 例如5 最好不要超过10', formType: 0}, function(text, index){
		layer.close(index);
		$.ajax({
			type:"post",
			url:"ajax_user.php?act=up_price",
			data:{
				up:text,cid:cid
			},
			dataType:"json",
			success:function(data){
				if(data.code==0){
					layer.alert('价格提升成功！刷新即可看到效果',{icon:1},function(){
                      window.location.reload();
                    });
				}else{
					layer.alert(data.msg);
				}
			}
		});
	});
}
</script>
</body>
</html>