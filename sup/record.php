<?php


include "../includes/common.php";
$title = "收支明细";
include "./head.php";
if ($islogin3 == 1) {
} else {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
$thtime = date("Y-m-d") . " 00:00:00";
$lastday = date("Y-m-d", strtotime("-1 day")) . " 00:00:00";
$income_today = $DB->getColumn("SELECT sum(point) FROM pre_suppoints WHERE sid='" . $suprow["sid"] . "' AND action='提成' AND addtime>'" . $thtime . "'");
$outcome_today = $DB->getColumn("SELECT sum(point) FROM pre_suppoints WHERE sid='" . $suprow["sid"] . "' AND action='消费' AND addtime>'" . $thtime . "'");
$income_lastday = $DB->getColumn("SELECT sum(point) FROM pre_suppoints WHERE sid='" . $suprow["sid"] . "' AND action='提成' AND addtime<'" . $thtime . "' AND addtime>'" . $lastday . "'");
$outcome_lastday = $DB->getColumn("SELECT sum(point) FROM pre_suppoints WHERE sid='" . $suprow["sid"] . "' AND action='消费' AND addtime<'" . $thtime . "' AND addtime>'" . $lastday . "'");
?><div class="wrapper">
	<div class="col-sm-12">
		   <div class="panel panel-default">
		   <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">收支明细</div>
<table class="table table-bordered">
<tbody>
<tr height="25">
<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-tint"></span>今日收益</b></br><?php echo round($income_today, 2);?>元</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-check"></i>今日消费</b></br></span><?php echo round($outcome_today, 2);?>元</font></td>
<td align="center"><font color="#808080"><b><span class="glyphicon glyphicon-tint"></span>昨日收益</b></br><?php echo round($income_lastday, 2);?>元</font></td>
<td align="center"><font color="#808080"><b><i class="glyphicon glyphicon-check"></i>昨日消费</b></br></span><?php echo round($outcome_lastday, 2);?>元</font></td>
</tr>
</tbody>
</table>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ID</th><th>类型</th><th>金额</th><th>详情</th><th>时间</th></tr></thead>
          <tbody>
<?php 
$numrows = $DB->getColumn("SELECT count(*) from pre_suppoints WHERE sid='" . $suprow["sid"] . "'");
$pagesize = 30;
$pages = ceil($numrows / $pagesize);
$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
$offset = $pagesize * ($page - 1);
$rs = $DB->query("SELECT * FROM pre_suppoints WHERE sid='" . $suprow["sid"] . "' order by id desc limit " . $offset . "," . $pagesize);
while ($res = $rs->fetch()) {
	echo "<tr><td><b>" . $res["id"] . "</b></td><td>" . $res["action"] . "</td><td><font color=\"" . (in_array($res["action"], array("提成", "奖励", "赠送", "退款", "退回", "充值", "加款")) ? "red" : "green") . "\">" . $res["point"] . "</font></td><td>" . $res["bz"] . "</td><td>" . $res["addtime"] . "</td></tr>";
}
?>

          </tbody>
        </table>
      </div>
<center><ul class="pagination"><?php 
$first = 1;
$prev = $page - 1;
$next = $page + 1;
$last = $pages;
if ($page > 1) {
	echo "<li><a href=\"record.php?page=" . $first . $link . "\">首页</a></li>";
	echo "<li><a href=\"record.php?page=" . $prev . $link . "\">&laquo;</a></li>";
} else {
	echo "<li class=\"disabled\"><a>首页</a></li>";
	echo "<li class=\"disabled\"><a>&laquo;</a></li>";
}
$start = $page - 10 > 1 ? $page - 10 : 1;
$end = $page + 10 < $pages ? $page + 10 : $pages;
for ($i = $start; $i < $page; $i++) {
	echo "<li><a href=\"record.php?page=" . $i . $link . "\">" . $i . "</a></li>";
}
echo "<li class=\"disabled\"><a>" . $page . "</a></li>";
for ($i = $page + 1; $i <= $end; $i++) {
	echo "<li><a href=\"record.php?page=" . $i . $link . "\">" . $i . "</a></li>";
}
if ($page < $pages) {
	echo "<li><a href=\"record.php?page=" . $next . $link . "\">&raquo;</a></li>";
	echo "<li><a href=\"record.php?page=" . $last . $link . "\">尾页</a></li>";
} else {
	echo "<li class=\"disabled\"><a>&raquo;</a></li>";
	echo "<li class=\"disabled\"><a>尾页</a></li>";
}
?></ul></center>   </div>
  </div>
 </div>
</div>
<?php 
include "./foot.php";
?></body>
</html>