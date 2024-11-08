<?php


include "../includes/common.php";
$title = "发卡库存管理";
include "./head.php";
if ($islogin3 == 1) {
} else {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
if ($suprow["bond"] < $conf["sup_bond"]) {
	exit("<script>alert(\"您当前未缴纳保证金，正在为您跳转...\");window.location.href=\"./bond.php\";</script>");
}
?>    <div class="col-md-12 center-panel panel-default" style="float: none;">
<?php 
$rs = $DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select = "<option value=\"0\">所有</option>";
while ($res = $rs->fetch()) {
	$select .= "<option value=\"" . $res["cid"] . "\">" . $res["name"] . "</option>";
}
$my = isset($_GET["my"]) ? $_GET["my"] : null;
if ($my == "move") {
	$cid = $_POST["cid"];
	if (!$cid) {
		exit("<script language='javascript'>alert('请选择分类');history.go(-1);</script>");
	}
	$checkbox = $_POST["checkbox"];
	$i = 0;
	foreach ($checkbox as $tid) {
		if ($cid == -1) {
			$DB->exec("update pre_tools set active=1 where tid='" . $tid . "' limit 1");
		} elseif ($cid == -2) {
			$DB->exec("update pre_tools set active=0 where tid='" . $tid . "' limit 1");
		} elseif ($cid == -3) {
			$DB->exec("DELETE FROM pre_tools WHERE tid='" . $tid . "' limit 1");
		}
		$i++;
	}
	exit("<script language='javascript'>alert('成功移动" . $i . "个商品');history.go(-1);</script>");
} else {
	if ($_GET["cid"]) {
		$cid = intval($_GET["cid"]);
		$numrows = $DB->getColumn("SELECT count(*) from pre_tools where is_curl=4 and cid='" . $cid . "' and goods_sid='" . $suprow["sid"] . "' and audit_status='1'");
		$sql = " is_curl=4 and cid='" . $cid . "' and goods_sid='" . $suprow["sid"] . "' and audit_status='1'";
		$link = "&cid=" . $cid;
	} elseif ($_GET["tid"]) {
		$tid = intval($_GET["tid"]);
		$numrows = $DB->getColumn("SELECT count(*) from pre_tools where is_curl=4 and tid='" . $tid . "' and goods_sid='" . $suprow["sid"] . "' and audit_status='1'");
		$sql = " is_curl=4 and tid='" . $tid . "' and goods_sid='" . $suprow["sid"] . "' and audit_status='1'";
		$link = "&tid=" . $tid;
	} else {
		$numrows = $DB->getColumn("SELECT count(*) from pre_tools where is_curl=4 and goods_sid='" . $suprow["sid"] . "' and audit_status='1'");
		$sql = " is_curl=4 and goods_sid='" . $suprow["sid"] . "' and audit_status='1'";
	}
	?><div class="panel panel-default">
<div class="panel-heading">
<form action="fakalist.php" method="GET" class="form-inline">
  <div class="form-group">
    <select name="cid" class="form-control" default="<?php echo $_GET["cid"];?>"><?php echo $select;?></select>
  </div>
  <button type="submit" class="btn btn-primary">进入分类</button>&nbsp;
</form>
</div>
    <div class="panel-body">
	  <form name="form1" method="post" action="fakalist.php?my=move">
	  <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>商品名称</th><th>剩余卡密</th><th>已售出</th><th>状态</th><th>操作</th></tr></thead>
          <tbody>
<?php 
	$pagesize = 30;
	$pages = ceil($numrows / $pagesize);
	$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
	$offset = $pagesize * ($page - 1);
	$rs = $DB->query("SELECT a.*,(select count(b.tid) from pre_faka as b where b.tid=a.tid and orderid=0) as leftcount,(select count(b.tid) from pre_faka as b where b.tid=a.tid and orderid!=0) as sellcount FROM pre_tools as a WHERE" . $sql . " order by sort asc limit " . $offset . "," . $pagesize);
	while ($res = $rs->fetch()) {
		echo "<tr><td><input type=\"checkbox\" name=\"checkbox[]\" id=\"list1\" value=\"" . $res["tid"] . "\" onClick=\"unselectall1()\"><b>" . $res["tid"] . "</b></td><td>" . $res["name"] . "</td><td>" . $res["leftcount"] . "</td><td>" . $res["sellcount"] . "</td><td>" . ($res["active"] == 1 ? "<span class=\"btn btn-xs btn-success\" onclick=\"setActive(" . $res["tid"] . ",0)\">上架中</span>" : "<span class=\"btn btn-xs btn-warning\" onclick=\"setActive(" . $res["tid"] . ",1)\">已下架</span>") . "</td><td><a href=\"./fakakms.php?tid=" . $res["tid"] . "\" class=\"btn btn-info btn-xs\">查看卡密</a>&nbsp;<a href=\"./fakakms.php?my=add&tid=" . $res["tid"] . "\" class=\"btn btn-success btn-xs\">加卡</a>&nbsp;<a href=\"./list.php?tid=" . $res["tid"] . "\" class=\"btn btn-warning btn-xs\">订单</a>&nbsp;<a href=\"./shopedit.php?my=delete&tid=" . $res["tid"] . "\" class=\"btn btn-xs btn-danger\" onclick=\"return confirm('你确实要删除此商品吗？');\">删除</a></td></tr>\r\n";
	}
	?>          </tbody>
        </table>
<input name="chkAll1" type="checkbox" id="chkAll1" onClick="this.value=check1(this.form.list1)" value="checkbox">&nbsp;全选&nbsp;
<select name="cid"><option selected>批量操作</option><option value="-1">&gt;改为上架中</option><option value="-2">&gt;改为已下架</option><option value="-3">&gt;删除选中</option></select>
<input type="submit" name="Submit" value="确定">
</div>
</form>
<script src="<?php echo $cdnpublic;?>layer/3.1.1/layer.js"></script>
<script>
var checkflag1 = "false";
function check1(field) {
if (checkflag1 == "false") {
for (i = 0; i < field.length; i++) {
field[i].checked = true;}
checkflag1 = "true";
return "false"; }
else {
for (i = 0; i < field.length; i++) {
field[i].checked = false; }
checkflag1 = "false";
return "true"; }
}

function unselectall1()
{
    if(document.form1.chkAll1.checked){
	document.form1.chkAll1.checked = document.form1.chkAll1.checked&0;
	checkflag1 = "false";
    } 	
}

function setActive(tid,active) {
	$.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=setTools&tid='+tid+'&active='+active,
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
function sort(cid,tid,sort) {
	$.ajax({
		type : 'GET',
		url : 'ajax_shop.php?act=setToolSort&cid='+cid+'&tid='+tid+'&sort='+sort,
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
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
</script>

<ul class="pagination"><?php 
	$first = 1;
	$prev = $page - 1;
	$next = $page + 1;
	$last = $pages;
	if ($page > 1) {
		echo "<li><a href=\"fakalist.php?page=" . $first . $link . "\">首页</a></li>";
		echo "<li><a href=\"fakalist.php?page=" . $prev . $link . "\">&laquo;</a></li>";
	} else {
		echo "<li class=\"disabled\"><a>首页</a></li>";
		echo "<li class=\"disabled\"><a>&laquo;</a></li>";
	}
	$start = $page - 10 > 1 ? $page - 10 : 1;
	$end = $page + 10 < $pages ? $page + 10 : $pages;
	for ($i = $start; $i < $page; $i++) {
		echo "<li><a href=\"fakalist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
	}
	echo "<li class=\"disabled\"><a>" . $page . "</a></li>";
	for ($i = $page + 1; $i <= $end; $i++) {
		echo "<li><a href=\"fakalist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
	}
	if ($page < $pages) {
		echo "<li><a href=\"fakalist.php?page=" . $next . $link . "\">&raquo;</a></li>";
		echo "<li><a href=\"fakalist.php?page=" . $last . $link . "\">尾页</a></li>";
	} else {
		echo "<li class=\"disabled\"><a>&raquo;</a></li>";
		echo "<li class=\"disabled\"><a>尾页</a></li>";
	}
	?></ul><?php 
}
?></div>
    </div>
  </div>