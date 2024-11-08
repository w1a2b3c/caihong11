<?php


include "../includes/common.php";
$title = "卡密列表";
include "./head.php";
if ($islogin3 == 1) {
} else {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
if ($suprow["bond"] < $conf["sup_bond"]) {
	exit("<script>alert(\"您当前未缴纳保证金，正在为您跳转...\");window.location.href=\"./bond.php\";</script>");
}
?><style>
td{overflow: hidden;text-overflow: ellipsis;white-space: nowrap;max-width:360px;}
</style>
    <div class="col-sm-12 col-md-10 center-panel panel-default" style="float: none;">
<div class="modal" align="left" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索卡密</h4>
      </div>
      <div class="modal-body">
      <form action="fakakms.php" method="GET">
<input type="hidden" name="tid" value="<?php echo @$_GET["tid"];?>"><br/>
<input type="text" class="form-control" name="kw" placeholder="请输入卡号或密码"><br/>
<input type="submit" class="btn btn-primary btn-panel panel-default" value="搜索"></form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php 
$rs = $DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select = "<option value=\"0\">请选择商品分类</option>";
while ($res = $rs->fetch()) {
	$select .= "<option value=\"" . $res["cid"] . "\">" . $res["name"] . "</option>";
}
$my = isset($_GET["my"]) ? $_GET["my"] : null;
if ($my == "add") {
	if (isset($_GET["tid"])) {
		$tid = intval($_GET["tid"]);
		$row = $DB->getRow("select cid,name from pre_tools where tid='" . $tid . "' limit 1");
		$shopname = "<option value=\"" . $tid . "\">" . $row["name"] . "</option>";
		$cid = $row["cid"];
	} else {
		$cid = 0;
	}
	?><div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">添加卡密</h3></div>
<div class="panel-body">
<form action="./fakakms.php?my=add_submit" method="POST" onsubmit="return checkAdd()">
<input type="hidden" name="backurl" value="<?php echo $_SERVER["HTTP_REFERER"];?>"/>
<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			选择商品
		</span>
		<select id="cid" class="form-control" default="<?php echo $cid;?>"><?php echo $select;?></select>
		<select id="tid" name="tid" class="form-control" default="<?php echo $tid;?>"><?php echo $shopname;?></select>
	</div>
</div>
<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			卡密列表
		</span>
		<textarea class="form-control" id="kms" name="kms" rows="8" placeholder="一行一张卡"></textarea>
	</div>
</div>
<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon">
			分隔符
		</span>
		<input type="text" name="split" value="" class="form-control" placeholder="可自定义卡号和密码之间的分隔符，默认留空为空格"/>
	</div>
</div>
<div class="form-group">
	<div class="input-group">
		<span class="input-group-addon"><label><input id="is_check_repeat" name="is_check_repeat" type="checkbox" value="1">检查重复的卡密</label></span>
	</div>
</div>
<div class="form-group">
	<button type="submit" class="btn btn-primary btn-panel panel-default">确认提交</button>
	<button type="reset" class="btn btn-default btn-panel panel-default">重新填写</button>
</div>
</form>
</div>
<div class="panel-footer">
<span class="glyphicon glyphicon-info-sign"></span>
注意：卡密格式：卡号+空格+密码，一行一张卡，如：ABCDEFG 123456789<br/>
只有商品设置里面购买成功后的动作选择自动发卡，该商品才会显示在当前列表
</div>
</div>
<a href="<?php echo isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "fakalist.php";?>" class="btn btn-default btn-panel panel-default">>>返回发卡库存列表</a>
<?php 
} elseif ($my == "add_submit") {
	if (!checkRefererHost()) {
		exit;
	}
	$tid = intval($_POST["tid"]);
	$row = $DB->getRow("select * from pre_tools where tid='" . $tid . "' and goods_sid='" . $suprow["sid"] . "' and audit_status='1' limit 1");
	if (!$row) {
		showmsg("不存在此商品！", 3);
	}
	$kms = $_POST["kms"];
	$split = $_POST["split"];
	$is_check_repeat = $_POST["is_check_repeat"];
	if ($tid == NULL || $kms == NULL) {
		showmsg("请确保各项不能为空！", 3);
	} else {
		$kms = str_replace(array("\r\n", "\r", "\n"), "[br]", $kms);
		$match = explode("[br]", $kms);
		$c = 0;
		foreach ($match as $val) {
			if (empty($split)) {
				$km_arr = explode(" ", $val);
			} else {
				$km_arr = explode($split, $val);
			}
			$km = trim(daddslashes($km_arr[0]));
			$pw = trim(daddslashes($km_arr[1]));
			if ($km == "") {
				continue;
			}
			if ($is_check_repeat == 1) {
				if ($DB->getRow("select * from pre_faka where km='" . $km . "' limit 1")) {
					continue;
				}
			}
			$sql = $DB->exec("INSERT INTO `pre_faka` (`tid`,`sid`,`km`,`pw`,`addtime`) VALUES ('" . $tid . "','" . $suprow["sid"] . "','" . $km . "','" . $pw . "',NOW())");
			if ($sql) {
				$c++;
			} else {
				showmsg("添加卡密失败！" . $DB->error());
			}
		}
		showmsg("成功添加<b>" . $c . "</b>张卡密！<br/><br/><a href=\"" . $_POST["backurl"] . "\">>>返回发卡库存列表</a>", 1);
	}
} elseif ($my == "del") {
	if (!checkRefererHost()) {
		exit;
	}
	$id = intval($_GET["id"]);
	$sql = $DB->exec("DELETE FROM pre_faka WHERE kid='" . $id . "' and sid='" . $suprow["sid"] . "'");
	exit("<script language='javascript'>history.go(-1);</script>");
} elseif ($my == "qk") {
	if (!checkRefererHost()) {
		exit;
	}
	$tid = intval($_GET["tid"]);
	echo "<div class=\"panel panel-default\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">清空卡密</h3></div>\r\n<div class=\"panel-body\">\r\n您确认要清空该商品的所有卡密吗？清空后无法恢复！<br><a href=\"./fakakms.php?my=qk2&tid=" . $tid . "\">确认</a> | <a href=\"javascript:history.back();\">返回</a></div></div>";
} elseif ($my == "qk2") {
	if (!checkRefererHost()) {
		exit;
	}
	$tid = intval($_GET["tid"]);
	?><div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">清空卡密</h3></div>
<div class="panel-body"><?php 
	if ($DB->exec("DELETE FROM pre_faka WHERE tid='" . $tid . "' and sid='" . $suprow["sid"] . "'") !== false) {
		echo "<div class=\"panel-body\">清空成功.</div>";
	} else {
		echo "<div class=\"panel-body\">清空失败.</div>";
	}
	echo "<hr/><a href=\"./fakakms.php?tid=" . $tid . "\">>>返回卡密列表</a></div></div>";
} elseif ($my == "qkuse") {
	if (!checkRefererHost()) {
		exit;
	}
	$tid = intval($_GET["tid"]);
	echo "<div class=\"panel panel-default\">\r\n<div class=\"panel-heading\"><h3 class=\"panel-title\">清空卡密</h3></div>\r\n<div class=\"\">\r\n您确认要清空所有卡密吗？清空后无法恢复！<br><a href=\"./fakakms.php?my=qkuse2&tid=" . $tid . "\">确认</a> | <a href=\"javascript:history.back();\">返回</a></div></div>";
} elseif ($my == "qkuse2") {
	if (!checkRefererHost()) {
		exit;
	}
	$tid = intval($_GET["tid"]);
	?><div class="panel panel-default">
<div class="panel-heading"><h3 class="panel-title">清空卡密</h3></div>
<div class="panel-body"><?php 
	if ($DB->exec("DELETE FROM pre_faka WHERE tid='" . $tid . "' and sid='" . $suprow["sid"] . "' and orderid!=0") !== false) {
		echo "<div class=\"panel-body\">清空成功.</div>";
	} else {
		echo "<div class=\"panel-body\">清空失败.</div>";
	}
	echo "<hr/><a href=\"./fakakms.php?tid=" . $tid . "\">>>返回卡密列表</a></div></div>";
} elseif ($my == "del2") {
	if (!checkRefererHost()) {
		exit;
	}
	$checkbox = $_POST["checkbox"];
	$i = 0;
	foreach ($checkbox as $kid) {
		$DB->exec("DELETE FROM pre_faka WHERE kid='" . $kid . "' and sid='" . $suprow["sid"] . "' limit 1");
		$i++;
	}
	exit("<script language='javascript'>alert('成功删除" . $i . "张卡密');history.go(-1);</script>");
} else {
	if (isset($_GET["kw"])) {
		$tid = intval($_GET["tid"]);
		$sql = " `tid`='" . $tid . "' and `sid`='" . $suprow["sid"] . "' and (`km`='" . str_replace("'"," ",$_GET["kw"]) . "' or `pw`='" . str_replace("'"," ",$_GET["kw"]) . "')";
		$link = "&tid=" . $tid . "&kw=" . str_replace("'"," ",$_GET["kw"]);
	} elseif (isset($_GET["kid"])) {
		$sql = " `kid`='" . intval($_GET["kid"]) . "' and `sid`='" . $suprow["sid"] . "'";
		$link = "&kid=" . intval($_GET["kid"]);
	} elseif (isset($_GET["orderid"])) {
		$sql = " `orderid`='" . intval($_GET["orderid"]) . "' and `sid`='" . $suprow["sid"] . "'";
		$link = "&orderid=" . intval($_GET["orderid"]);
	} elseif (isset($_GET["tid"])) {
		$tid = intval($_GET["tid"]);
		$row = $DB->getRow("select * from pre_tools where tid='" . $tid . "' limit 1");
		if (!$row) {
			showmsg("商品不存在", 3);
		}
		$sql = " `tid`='" . $tid . "' and `sid`='" . $suprow["sid"] . "'";
		$link = "&tid=" . $tid;
	} else {
		showmsg("商品不存在", 3);
	}
	$numrows = $DB->getColumn("SELECT count(*) from pre_faka WHERE" . $sql);
	?><div class="modal" align="left" id="output" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">导出未使用的卡密</h4>
      </div>
      <div class="modal-body">
      <form action="download.php" method="GET">
<input type="hidden" name="act" value="kms">
<input type="hidden" name="tid" value="<?php echo $tid;?>">
<input type="hidden" name="use" value="0">
<div class="form-group">
	<div class="input-group">
		<input type="number" class="form-control" name="num" placeholder="请输入要导出的数量">
		<span class="input-group-btn">
			<select name="isuse" class="form-control" style="width:140px">
				<option value="0">不改为已使用</option>
				<option value="1">同时改为已使用</option>
			</select>
		</span>
		
	</div>
</div>
<input type="submit" class="btn btn-primary btn-panel panel-default" value="导出"></form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">
		<h3 class="panel-title"><?php echo $row["name"];?> - 卡密库存列表</h3>
	</div>
	<div class="panel-body">
	<a href="fakakms.php?my=add&tid=<?php echo $tid;?>" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;加卡</a>
  <a href="fakakms.php?my=qk&tid=<?php echo $tid;?>" class="btn btn-danger">清空</a>
  <a href="fakakms.php?my=qkuse&tid=<?php echo $tid;?>" class="btn btn-danger">清空已使用</a>
  <div class="btn-group">
  <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    导出 <span class="caret"></span>
  </button>
  <ul class="dropdown-menu">
    <li><a href="download.php?act=kms<?php echo $link;?>&use=0">未使用</a></li>
    <li><a href="download.php?act=kms<?php echo $link;?>&use=1">已使用</a></li>
    <li><a href="download.php?act=kms<?php echo $link;?>">全部</a></li>
	<li><a href="#" data-toggle="modal" data-target="#output" id="output">指定数量</a></li>
  </ul>
</div>
  <a href="#" data-toggle="modal" data-target="#search" id="search" class="btn btn-primary">搜索</a>
  </div>
    <div class="panel-body">
	<form name="form1" method="post" action="fakakms.php?my=del2">
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>卡号</th><th>密码</th><th>状态</th><th>添加时间</th><th>使用时间</th><th>操作</th></tr></thead>
          <tbody>
<?php 
	$pagesize = 30;
	$pages = ceil($numrows / $pagesize);
	$page = isset($_GET["page"]) ? intval($_GET["page"]) : 1;
	$offset = $pagesize * ($page - 1);
	$rs = $DB->query("SELECT * FROM pre_faka WHERE" . $sql . " order by kid desc limit " . $offset . "," . $pagesize);
	while ($res = $rs->fetch()) {
		if ($res["usetime"] == null) {
			$isuse = "<font color=\"green\">未使用</font>";
		} else {
			$isuse = "<font color=\"red\">已使用</font>(<a href=\"./list.php?id=" . $res["orderid"] . "\" target=\"_blank\">" . $res["orderid"] . "</a>)";
		}
		echo "<tr><td onclick=\"showkms(this)\"><input type=\"checkbox\" name=\"checkbox[]\" id=\"list1\" value=\"" . $res["kid"] . "\" onClick=\"unselectall1()\"><b>" . $res["km"] . "</b></td><td>" . $res["pw"] . "</td><td>" . $isuse . "</td><td>" . $res["addtime"] . "</td><td>" . $res["usetime"] . "</td><td><a href=\"./fakakms.php?my=del&id=" . $res["kid"] . "\" class=\"btn btn-xs btn-danger\" onclick=\"return confirm('你确实要删除此卡密吗？');\">删除</a></td></tr>";
	}
	?>          </tbody>
        </table>
<input name="chkAll1" type="checkbox" id="chkAll1" onClick="this.value=check1(this.form.list1)" value="checkbox">&nbsp;全选&nbsp;
<input type="submit" name="Submit" value="删除选中">
</div>
</form>
<ul class="pagination"><?php 
	$first = 1;
	$prev = $page - 1;
	$next = $page + 1;
	$last = $pages;
	if ($page > 1) {
		echo "<li><a href=\"fakakms.php?page=" . $first . $link . "\">首页</a></li>";
		echo "<li><a href=\"fakakms.php?page=" . $prev . $link . "\">&laquo;</a></li>";
	} else {
		echo "<li class=\"disabled\"><a>首页</a></li>";
		echo "<li class=\"disabled\"><a>&laquo;</a></li>";
	}
	$start = $page - 10 > 1 ? $page - 10 : 1;
	$end = $page + 10 < $pages ? $page + 10 : $pages;
	for ($i = $start; $i < $page; $i++) {
		echo "<li><a href=\"fakakms.php?page=" . $i . $link . "\">" . $i . "</a></li>";
	}
	echo "<li class=\"disabled\"><a>" . $page . "</a></li>";
	for ($i = $page + 1; $i <= $end; $i++) {
		echo "<li><a href=\"fakakms.php?page=" . $i . $link . "\">" . $i . "</a></li>";
	}
	if ($page < $pages) {
		echo "<li><a href=\"fakakms.php?page=" . $next . $link . "\">&raquo;</a></li>";
		echo "<li><a href=\"fakakms.php?page=" . $last . $link . "\">尾页</a></li>";
	} else {
		echo "<li class=\"disabled\"><a>&raquo;</a></li>";
		echo "<li class=\"disabled\"><a>尾页</a></li>";
	}
	?></ul><?php 
}
?></div>
    </div>
  </div>
</div>
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

    function showkms(obj) {
        $(obj).css("white-space","normal");
        $(obj).css("word-break","break-all");
    }

    function checkAdd(){
        if($("#tid").val()==0||$("#tid").val()==null){
            layer.alert('请先选择商品');return false;
        }
        if($("#kms").val()==''){
            layer.alert('卡密列表不能为空');return false;
        }
    }
    $(document).ready(function(){
        $("#cid").change(function () {
            var cid = $(this).val();
            var ii = layer.load(2, {shade:[0.1,'#fff']});
            $("#tid").empty();
            $("#tid").append('<option value="0">请选择商品</option>');
            $.ajax({
                type : "GET",
                url : "./ajax_user.php?act=getfakatool&cid="+cid,
                dataType : 'json',
                success : function(data) {
                    layer.close(ii);
                    if(data.code == 0){
                        var num = 0;
                        $.each(data.data, function (i, res) {
                            $("#tid").append('<option value="'+res.tid+'">'+res.name+'</option>');
                            num++;
                        });
                        $("#tid").val(0);
                        if(num==0 && cid!=0)$("#tid").html('<option value="0">该分类下没有发卡类商品</option>');
                    }else{
                        layer.alert(data.msg);
                    }
                },
                error:function(data){
                    layer.msg('服务器错误');
                    return false;
                }
            });
        });
        var items = $("select[default]");
        for (i = 0; i < items.length; i++) {
            $(items[i]).val($(items[i]).attr("default")||0);
        }
    });
</script>
</body>
</html>