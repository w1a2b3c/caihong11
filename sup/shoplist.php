<?php


include "../includes/common.php";
$title = "商品管理";
include "./head.php";
if ($islogin3 == 1) {
} else {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
if ($suprow["bond"] < $conf["sup_bond"]) {
	exit("<script>alert(\"您当前未缴纳保证金，正在为您跳转...\");window.location.href=\"./bond.php\";</script>");
}
?><div class="wrapper">
  <div class="col-sm-12">
<?php 
$my = isset($_GET["my"]) ? $_GET["my"] : null;
$rs = $DB->query("SELECT * FROM pre_class WHERE active=1 ORDER BY sort ASC");
$select = "<option value=\"0\">请选择分类</option>";
$shua_class[0] = "未分类";
while ($res = $rs->fetch()) {
	$shua_class[$res["cid"]] = $res["name"];
	$select .= "<option value=\"" . $res["cid"] . "\">" . $res["name"] . "</option>";
}
?><div class="modal fade" align="left" id="search2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">商品分类</h4>
      </div>
      <div class="modal-body">
      <form action="shoplist.php" method="GET">
      <select name="cid" class="form-control">
		<?php echo $select;?>		</select><br/>
      <input type="submit" class="btn btn-primary btn-block" value="查看"></form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
      </div>
    </div>
  </div>
</div>
<?php 
if (isset($_GET["cid"])) {
	$cid = intval($_GET["cid"]);
	$numrows = $DB->getColumn("SELECT count(*) FROM pre_tools WHERE cid='" . $cid . "' AND active=1 and goods_sid='" . $suprow["sid"] . "'");
	$sql = " cid=" . $cid . " AND goods_sid=" . $suprow["sid"] . "";
	$con = "\r\n\t<div class=\"panel panel-default\"><div class=\"panel-heading font-bold\" style=\"background-color: #9999CC;color: white;\">" . $shua_class[$cid] . "分类 - [<a href=\"shoplist.php\" style=\"color:#fff00f\">查看全部</a>]</div>\r\n\t<div class=\"well well-sm\" style=\"margin: 0;\">分类 " . $shua_class[$cid] . " 共有 <b>" . $numrows . "</b> 个商品</div>\r\n\t<div class=\"wrapper\">\r\n    <a href=\"#\" data-toggle=\"modal\" data-target=\"#search2\" id=\"search2\" class=\"btn btn-primary\"><i class=\"fa fa-navicon\"></i>&nbsp;分类查看</a>&nbsp;<a class=\"btn btn-danger\" href=\"./shopedit.php?my=add&cid=" . $cid . "\"><i class=\"fa fa-plus-circle\"></i>&nbsp;上架商品</a></div>";
	$link = "&cid=" . $cid;
} else {
	$numrows = $DB->getColumn("SELECT count(*) FROM pre_tools WHERE active=1 and goods_sid='" . $suprow["sid"] . "'");
	$sql = " goods_sid=" . $suprow["sid"] . "";
	$con = "\r\n\t<div class=\"panel panel-default\"><div class=\"panel-heading font-bold\" style=\"background-color: #9999CC;color: white;\">商品列表</div>\r\n\t<div class=\"well well-sm\" style=\"margin: 0;\">系统共有 <b>" . $numrows . "</b> 个商品 - 提升价格赚的更多哦！提高价格最好不要太贵了否则没人买的哦！</div>\r\n    <div class=\"wrapper\">\r\n    <a href=\"#\" data-toggle=\"modal\" data-target=\"#search2\" id=\"search2\" class=\"btn btn-primary\"><i class=\"fa fa-navicon\"></i>&nbsp;分类查看</a>&nbsp;<a class=\"btn btn-danger\" href=\"./shopedit.php?my=add\"><i class=\"fa fa-plus-circle\"></i>&nbsp;上架商品</a></div>";
}
echo $con;
?>      <div class="table-responsive">
        <table class="table table-striped b-t b-light">
          <thead><tr><th>操作</th><th>商品名称</th><th>销售价格</th><th>审核状态</th><th>商品状态</th></tr></thead>
          <tbody>
<?php 
$pagesize = 30;
$pages = ceil($numrows / $pagesize);
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$offset = $pagesize * ($page - 1);
$rs = $DB->query("SELECT * FROM pre_tools WHERE" . $sql . " ORDER BY sort ASC LIMIT " . $offset . "," . $pagesize);
while ($res = $rs->fetch()) {
	echo "<tr>\r\n\t\t\t<td>\r\n\t\t\t\t<a href=\"./shopedit.php?my=edit&tid=" . $res["tid"] . "\" class=\"btn btn-info btn-xs\">编辑</a>\r\n\t\t\t</td>\r\n\t\t\t<td><b><a title=\"点此下单\" style=\"color:#000\" href=\"./shop.php?cid=" . $res["cid"] . "&tid=" . $res["tid"] . "\">" . $res["name"] . "</a></b></td>\r\n\t\t\t<td><font color=\"#FF0ff0\">" . $res["sup_price"] . "元</font> </td>\r\n\t\t\t<td>" . ($res["audit_status"] == 0 ? "<font color=red>未通过</font>" : "<font color=green>已通过</font>") . "</td>\r\n\t\t\t<td>" . ($res["close"] == 1 ? "<font color=red>已下架</font>" : "<font color=green>上架中</font>") . "</td>\r\n\t\t</tr>";
}
?>		          
          </tbody>
        </table>
<ul class="pagination"  style="margin-left:1em"><?php 
$first = 1;
$prev = $page - 1;
$next = $page + 1;
$last = $pages;
if ($page > 1) {
	echo "<li><a href=\"shoplist.php?page=" . $first . $link . "\">首页</a></li>";
	echo "<li><a href=\"shoplist.php?page=" . $prev . $link . "\">&laquo;</a></li>";
} else {
	echo "<li class=\"disabled\"><a>首页</a></li>";
	echo "<li class=\"disabled\"><a>&laquo;</a></li>";
}
$start = $page - 10 > 1 ? $page - 10 : 1;
$end = $page + 10 < $pages ? $page + 10 : $pages;
for ($i = $start; $i < $page; $i++) {
	echo "<li><a href=\"shoplist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
}
echo "<li class=\"disabled\"><a>" . $page . "</a></li>";
for ($i = $page + 1; $i <= $end; $i++) {
	echo "<li><a href=\"shoplist.php?page=" . $i . $link . "\">" . $i . "</a></li>";
}
if ($page < $pages) {
	echo "<li><a href=\"shoplist.php?page=" . $next . $link . "\">&raquo;</a></li>";
	echo "<li><a href=\"shoplist.php?page=" . $last . $link . "\">尾页</a></li>";
} else {
	echo "<li class=\"disabled\"><a>&raquo;</a></li>";
	echo "<li class=\"disabled\"><a>尾页</a></li>";
}
?></ul></div>
</div>

</div>
<?php 
include "./foot.php";
?></body>
</html>