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
?><link rel="stylesheet" href="<?php echo $cdnpublic;?>select2/4.0.10/css/select2.min.css">
<script src="<?php echo $cdnpublic;?>select2/4.0.10/js/select2.min.js"></script>
<style>
	.select2-selection.select2-selection--single {
		height: 32px;
	}

	.select2-container--default.select2-selection--single {
		padding: 5px;
	}
#GoodsInfo img{max-width:100%}
</style>
<div class="modal" align="left" id="inputabout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">输入框标题说明</h4>
      </div>
      <div class="modal-body">
	  使用以下输入框标题可实现特殊的转换功能<br/>
	  自动从链接和文字取出链接：<a href="javascript:changeinput('作品链接')">作品链接</a>、<a href="javascript:changeinput('视频链接')">视频链接</a>、<a href="javascript:changeinput('分享链接')">分享链接</a>、<a href="javascript:changeinput('自定义[shareurl]')">自定义[shareurl]</a><br/>
	  自动获取音乐/视频ID：<a href="javascript:changeinput('作品ID')">作品ID</a>、<a href="javascript:changeinput('帖子ID')">帖子ID</a>、<a href="javascript:changeinput('用户ID')">用户ID</a>、<a href="javascript:changeinput('自定义[shareid]')">自定义[shareid]</a><br/><hr/>
	  注：在输入框名称后面加[shareid]、[shareurl]可以分别有获取ID、获取URL功能
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="modal" align="left" id="inputsabout" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">更多输入框标题说明</h4>
      </div>
      <div class="modal-body">
	  使用以下输入框标题可实现特殊的转换功能<br/>
	  获取空间说说列表：<a href="javascript:changeinputs('说说ID')">说说ID</a>、<a href="javascript:changeinputs('说说ＩＤ')">说说ＩＤ</a>、<a href="javascript:changeinputs('自定义[ssid]')">自定义[ssid]</a><br/>
	  获取空间日志列表：<a href="javascript:changeinputs('日志ID')">日志ID</a>、<a href="javascript:changeinputs('日志ＩＤ')">日志ＩＤ</a>、<a href="javascript:changeinputs('自定义[rzid]')">自定义[rzid]</a><br/>
	  作品地址获取：<a href="javascript:changeinputs('自定义[zpid]')">自定义[zpid]</a><br/>
	  收货地址获取：<a href="javascript:changeinputs('收货地址')">收货地址</a>、<a href="javascript:changeinputs('收货人地址')">收货人地址</a>、<a href="javascript:changeinputs('自定义[address]')">自定义[address]</a><br/><hr/>
	  显示选择框，在名称后面加{选择1,选择2}，例如：<a href="javascript:changeinputs('分类名{普通,音乐,宠物}')">分类名{普通,音乐,宠物}</a>
	  </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<div class="wrapper">
<?php 
$my = isset($_GET["my"]) ? $_GET["my"] : null;
$rs = $DB->query("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$select = "<option value=\"0\">未分类</option>";
$shua_class[0] = "未分类";
while ($res = $rs->fetch()) {
	$shua_class[$res["cid"]] = $res["name"];
	$select .= "<option value=\"" . $res["cid"] . "\">" . $res["name"] . "</option>";
}
if ($my == "add") {
	?><form action="./shopedit.php?my=add_submit" method="POST" onsubmit="return checkinput()">
<div class="col-sm-12 col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><b>上架商品</b></h3></div>
        <div class="panel-body">
<div class="form-group">
<label>*商品分类:</label><br>
<select name="cid" class="form-control" default="<?php echo $_GET["cid"];?>"><?php echo $select;?></select>
</div>
<div class="form-group">
<label>*商品名称:</label><br>
<input type="text" class="form-control" name="name" value="" required>
</div>
    <div class="form-group" id="show_value">
        <label>默认数量信息:</label><br>
        <input type="number" class="form-control" name="value" id="value" value="" placeholder="填写1默认下单1个" onkeyup="changeNum()">
        <input type="hidden" id="price" value="">
    </div>
<div class="form-group" id="prid1">
<label>*结算价格:</label><br>
<input type="text" class="form-control" name="price" value="">
</div>
    <div class="form-group">
        <label>批发价格优惠设置:</label><br>
        <input type="text" class="form-control" name="prices" value="">
        <pre><font color="green">填写格式：购满x个|减少x元单价,购满x个|减少x元单价  例如10|0.1,20|0.3,30|0.5</font></pre>
    </div>
<div class="form-group">
<label>第一个输入框标题:</label><br>
<div class="input-group">
<input type="text" class="form-control" name="input" value="" placeholder="留空默认为“下单账号”"><span class="input-group-btn"><a href="#inputabout" data-toggle="modal" class="btn btn-info" title="说明"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
</div>
</div>
<div class="form-group">
<label>更多输入框标题:</label><br>
<div class="input-group">
<input type="text" class="form-control" name="inputs" value="" placeholder="留空则不显示更多输入框"><span class="input-group-btn"><a href="#inputsabout" data-toggle="modal" class="btn btn-info" title="说明"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
</div>
<pre><font color="green">多个输入框请用|隔开(不能超过4个)</font></pre>
</div>
<div class="form-group">
<label>商品简介:</label>(没有请留空)<br>
<textarea class="form-control" id="editor_id" name="desc" rows="3" style="width:100%" placeholder="当选择该商品时自动显示，支持HTML代码"></textarea>
</div>
<div class="form-group">
<label>提示内容:</label>(没有请留空)<br>
<input type="text" class="form-control" name="alert" value="" placeholder="当选择该商品时自动弹出提示，不支持HTML代码">
</div>
<div class="form-group">
<label>商品图片:</label><br>
<input type="file" id="file" onchange="fileUpload()" style="display:none;"/>
<div class="input-group">
<input type="text" class="form-control" id="shopimg" name="shopimg" value="" placeholder="填写图片URL，没有请留空"><span class="input-group-btn"><a href="javascript:fileSelect()" class="btn btn-success" title="上传图片"><i class="glyphicon glyphicon-upload"></i></a><a href="javascript:fileView()" class="btn btn-warning" title="查看图片"><i class="glyphicon glyphicon-picture"></i></a></span>
</div>
</div>
<div class="form-group">
<label>*显示数量选择框:</label><br>
<select class="form-control" name="multi"><option value="1">1_是</option><option value="0">0_否</option></select>
</div>
<table class="table table-striped table-bordered table-condensed" id="multi0" style="display:none;">
<tbody>
<tr align="center"><td>最小下单数量</td><td>最大下单数量</td></tr>
<tr>
<td><input type="text" name="min" value="" class="form-control input-sm" placeholder="留空则默认为1"/></td>
<td><input type="text" name="max" value="" class="form-control input-sm" placeholder="留空则不限数量"/></td>
</tr>
</table>
<div class="form-group">
<label>允许重复下单:</label><br>
<div class="input-group">
<select class="form-control" name="repeat"><option value="0">0_否</option><option value="1">1_是</option></select>
<a tabindex="0" class="input-group-addon" role="button" data-toggle="popover" data-trigger="focus" title="" data-placement="bottom" data-content="是指相同下单输入内容（非同一用户）当天只能下单一次，或上一条订单未处理的情况下不能重复下单"><span class="glyphicon glyphicon-info-sign"></span></a>
</div>
</div>
<div class="form-group">
<label>验证操作:</label><br>
<select class="form-control" name="validate"><option value="0">不开启验证</option><option value="1">验证QQ空间是否有访问权限</option><option value="2">验证已开通服务(符合则禁止下单)</option><option value="3">验证已开通服务(符合则不对接社区)</option></select>
</div>
<div class="form-group" id="valiserv" style="display:none;">
<label>需要验证的已开通服务:</label><br>
<select class="form-control" name="valiserv"><option value="vip">QQ会员</option><option value="svip">超级会员</option><option value="red">红钻贵族</option><option value="green">绿钻贵族</option><option value="sgreen">绿钻豪华版</option><option value="yellow">黄钻贵族</option><option value="syellow">豪华黄钻</option><option value="hollywood">腾讯视频VIP</option><option value="qqmsey">付费音乐包</option><option value="qqmstw">豪华付费音乐包</option><option value="weiyun">微云会员</option><option value="sweiyun">微云超级会员</option></select>
</div>
<input type="submit" class="btn btn-primary btn-block" value="确定添加">
<br/><a href="shoplist.php">>>返回商品列表</a>
</div></div>
</div>
</form>
</div>
<?php 
} elseif ($my == "edit") {
	$tid = $_GET["tid"];
	$row = $DB->getRow("select * from pre_tools where tid='" . $tid . "' limit 1");
	?><form action="./shopedit.php?my=edit_submit&tid=<?php echo $tid;?>" method="POST" onsubmit="return checkinput()">
<div class="col-sm-12 col-md-12">
    <div class="panel panel-default">
        <div class="panel-heading"><h3 class="panel-title"><b>编辑商品</b></h3></div>
        <div class="panel-body">
<div class="form-group">
<label>商品分类:</label><br>
<select name="cid" class="form-control" default="<?php echo $row["cid"];?>"><?php echo $select;?></select>
</div>
<div class="form-group">
<label>*商品名称:</label><br>
<input type="text" class="form-control" name="name" value="<?php echo $row["name"];?>" required>
</div>
<div class="form-group" id="show_value">
    <label>默认数量信息:</label><br>
    <input type="number" class="form-control" name="value" id="value" value="<?php echo $row["value"];?>" placeholder="用于对接社区使用或导出时显示" onkeyup="changeNum()">
    <input type="hidden" id="price" value="">
</div>
<div class="form-group" id="prid1" >
<label>*结算价格:</label><br>
<input type="text" class="form-control" name="price" value="<?php echo $row["sup_price"];?>">
</div>
    <div class="form-group">
        <label>批发价格优惠设置:</label><br>
        <input type="text" class="form-control" name="prices" value="<?php echo $row["prices"];?>">
        <pre><font color="green">填写格式：购满x个|减少x元单价,购满x个|减少x元单价  例如10|0.1,20|0.3,30|0.5</font></pre>
    </div>
<div class="form-group">
<label>第一个输入框标题:</label><br>
<div class="input-group">
<input type="text" class="form-control" name="input" value="<?php echo $row["input"];?>" placeholder="留空默认为“下单账号”"><span class="input-group-btn"><a href="#inputabout" data-toggle="modal" class="btn btn-info" title="说明"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
</div>
</div>
<div class="form-group">
<label>更多输入框标题:</label><br>
<div class="input-group">
<input type="text" class="form-control" name="inputs" value="<?php echo $row["inputs"];?>" placeholder="留空则不显示更多输入框"><span class="input-group-btn"><a href="#inputsabout" data-toggle="modal" class="btn btn-info" title="说明"><i class="glyphicon glyphicon-exclamation-sign"></i></a></span>
</div>
<pre><font color="green">多个输入框请用|隔开(不能超过4个)</font></pre>
</div>
<div class="form-group">
<label>商品简介:</label>(没有请留空)<br>
<textarea class="form-control" id="editor_id" name="desc" rows="3" style="width:100%" placeholder="当选择该商品时自动显示，支持HTML代码"></textarea>
</div>
<div class="form-group">
<label>提示内容:</label>(没有请留空)<br>
<input type="text" class="form-control" name="alert" value="<?php echo htmlspecialchars($row["alert"]);?>" placeholder="当选择该商品时自动弹出提示，不支持HTML代码">
</div>
<div class="form-group">
<label>商品图片:</label><br>
<input type="file" id="file" onchange="fileUpload()" style="display:none;"/>
<div class="input-group">
<input type="text" class="form-control" id="shopimg" name="shopimg" value="<?php echo $row["shopimg"];?>" placeholder="填写图片URL，没有请留空"><span class="input-group-btn"><a href="javascript:fileSelect()" class="btn btn-success" title="上传图片"><i class="glyphicon glyphicon-upload"></i></a><a href="javascript:fileView()" class="btn btn-warning" title="查看图片"><i class="glyphicon glyphicon-picture"></i></a></span>
</div>
</div>
<div class="form-group">
<label>显示数量选择框:</label><br>
<select class="form-control" name="multi" default="<?php echo $row["multi"];?>"><option value="1">1_是</option><option value="0">0_否</option></select>
</div>
<table class="table table-striped table-bordered table-condensed" id="multi0" style="display:none;">
<tbody>
<tr align="center"><td>最小下单数量</td><td>最大下单数量</td></tr>
<tr>
<td><input type="text" name="min" class="form-control input-sm" value="<?php echo $row["min"];?>" placeholder="留空则默认为1"/></td>
<td><input type="text" name="max" class="form-control input-sm" value="<?php echo $row["max"];?>" placeholder="留空则不限数量"/></td>
</tr>
</table>
<div class="form-group">
<label>允许重复下单:</label><br>
<div class="input-group">
<select class="form-control" name="repeat" default="<?php echo $row["repeat"];?>"><option value="0">0_否</option><option value="1">1_是</option></select>
<a tabindex="0" class="input-group-addon" role="button" data-toggle="popover" data-trigger="focus" title="" data-placement="bottom" data-content="是指相同下单输入内容（非同一用户）当天只能下单一次，或上一条订单未处理的情况下不能重复下单"><span class="glyphicon glyphicon-info-sign"></span></a>
</div>
</div>
<div class="form-group">
<label>验证操作:</label><br>
<select class="form-control" name="validate" default="<?php echo $row["validate"];?>"><option value="0">不开启验证</option><option value="1">验证QQ空间是否有访问权限</option><option value="2">验证已开通服务(符合则禁止下单)</option><option value="3">验证已开通服务(符合则不对接社区)</option></select>
</div>
<div class="form-group" id="valiserv" style="display:none;">
<label>需要验证的已开通服务:</label><br>
<select class="form-control" name="valiserv" default="<?php echo $row["valiserv"];?>"><option value="vip">QQ会员</option><option value="svip">超级会员</option><option value="red">红钻贵族</option><option value="green">绿钻贵族</option><option value="sgreen">绿钻豪华版</option><option value="yellow">黄钻贵族</option><option value="syellow">豪华黄钻</option><option value="hollywood">腾讯视频VIP</option><option value="qqmsey">付费音乐包</option><option value="qqmstw">豪华付费音乐包</option><option value="weiyun">微云会员</option><option value="sweiyun">微云超级会员</option></select>
</div>
<input type="submit" class="btn btn-primary btn-block" value="确定修改">
<br/><a href="shoplist.php">>>返回商品列表</a>
</div></div>
</div>
</form>
<?php 
} elseif ($my == "add_submit") {
	$cid = $_POST["cid"];
	$name = $_POST["name"];
	$price = $_POST["price"];
	$prices = $_POST["prices"];
	$input = $_POST["input"];
	$inputs = $_POST["inputs"];
	$desc = $_POST["desc"];
	$alert = $_POST["alert"];
	$shopimg = $_POST["shopimg"];
	$value = $_POST["value"] ?? 1;
	$multi = $_POST["multi"];
	$min = $_POST["min"];
	$max = $_POST["max"];
	$validate = $_POST["validate"];
	$valiserv = $_POST["valiserv"];
	$repeat = $_POST["repeat"];
	if ($name == NULL || $price == NULL) {
		showmsg("保存错误，商品名称和价格不能为空！", 3);
	} else {
		if ($conf["sup_audit_free"] == 1) {
			if ($suprow["bond"] >= $conf["pass_sup_bond"]) {
				$audit_status = 1;
				$active = 1;
			} else {
				$audit_status = 0;
				$active = 0;
			}
		} else {
			$audit_status = 0;
			$active = 0;
		}
		$sort = $DB->getColumn("select sort from pre_tools order by sort desc limit 1");
		$sql = "INSERT INTO `pre_tools` (`cid`,`name`,`sup_price`,`input`,`inputs`,`desc`,`alert`,`shopimg`,`value`,`repeat`,`multi`,`min`,`max`,`validate`,`valiserv`,`active`,`goods_sid`,`stock`,`sort`,`is_curl`,`prices`,`audit_status`) VALUES ('" . $cid . "','" . $name . "','" . $price . "','" . $input . "','" . $inputs . "','" . addslashes($desc) . "','" . addslashes($alert) . "','" . $shopimg . "','" . $value . "','" . $repeat . "','" . $multi . "','" . $min . "','" . $max . "','" . $validate . "','" . $valiserv . "','" . $active . "','" . $suprow["sid"] . "','" . $stock . "','" . $sort . "','4','" . $prices . "','" . $audit_status . "')";
		if ($DB->exec($sql) !== false) {
			$tid = $DB->lastInsertId();
			showmsg("添加商品成功，请等待管理员审核！<br/><br/><a href=\"./shoplist.php\">>>返回商品列表</a>", 1);
		} else {
			showmsg("添加商品失败！" . $DB->error(), 4);
		}
	}
} elseif ($my == "edit_submit") {
	$tid = $_GET["tid"];
	$rows = $DB->getRow("select * from pre_tools where tid='" . $tid . "' and goods_sid = '" . $suprow["sid"] . "' limit 1");
	if (!$rows) {
		showmsg("当前记录不存在！", 3);
	}
	$cid = $_POST["cid"];
	$name = $_POST["name"];
	$price = $_POST["price"];
	$prices = $_POST["prices"];
	$input = $_POST["input"];
	$inputs = $_POST["inputs"];
	$desc = $_POST["desc"];
	$alert = $_POST["alert"];
	$shopimg = $_POST["shopimg"];
	$value = $_POST["value"] ?? 1;
	$multi = $_POST["multi"];
	$min = $_POST["min"];
	$max = $_POST["max"];
	$validate = $_POST["validate"];
	$valiserv = $_POST["valiserv"];
	$repeat = $_POST["repeat"];
	if ($name == NULL || $price == NULL) {
		showmsg("保存错误，商品名称和价格不能为空！", 3);
	} else {
		if ($conf["sup_audit_free"] == 1) {
			if ($suprow["bond"] >= $conf["pass_sup_bond"]) {
				$audit_status = 1;
				$active = 1;
			} else {
				$audit_status = 0;
				$active = 0;
			}
		} else {
			$audit_status = 0;
			$active = 0;
		}
		if ($DB->exec("UPDATE `pre_tools` SET `cid`='" . $cid . "',`name`='" . $name . "',`sup_price`='" . $price . "',`input`='" . $input . "',`inputs`='" . $inputs . "',`desc`='" . addslashes($desc) . "',`alert`='" . addslashes($alert) . "',`shopimg`='" . $shopimg . "',`value`='" . $value . "',`repeat`='" . $repeat . "',`multi`='" . $multi . "',`min`='" . $min . "',`max`='" . $max . "',`validate`='" . $validate . "',`valiserv`='" . $valiserv . "',`active`='" . $active . "',`audit_status`='" . $audit_status . "',`is_curl`='4',`prices`='" . $prices . "' WHERE `tid`='" . $tid . "'") !== false) {
			showmsg("修改商品成功！<br/><br/><a href=\"./shoplist.php\">>>返回商品列表</a>", 1);
		} else {
			showmsg("修改商品失败！" . $DB->error(), 4);
		}
	}
}
?><script>
var isAdd = true;
</script>
<script src="<?php echo $cdnpublic;?>layer/3.1.1/layer.js"></script>
<script>
    var shoplist;
    function checkinput(){
        if($("input[name='name']").val()==''){
            layer.alert("商品名称不能为空");
            return false;
        }
        if($("select[name='prid']").val()=='0' && $("input[name='price']").val()=='' || $("select[name='prid']").val()!='0' && $("input[name='price1']").val()==''){
            layer.alert("商品价格不能为空");
            return false;
        }
        return true;
    }
    function setDesc(str){
        $("textarea[name='desc']").val(str);
        window.editor !== undefined && window.editor.html(str);
    }
    function changeinput(str){
        $("input[name='input']").val(str);
    }
    function changeinputs(str){
        $("input[name='inputs']").val(str);
    }
    function getFloat(number, n) {
        n = n ? parseInt(n) : 0;
        if (n <= 0) return Math.ceil(number);
        number = Math.round(number * Math.pow(10, n)) / Math.pow(10, n);
        return number;
    }
    function changeNum(){
        var num = parseInt($("#value").val());
        var price = parseFloat($("#price").val());
        var min = parseInt($("#value").attr('min'));
        var max = parseInt($("#value").attr('max'));
        if(num == 0 || isNaN(price))return false;
        $("input[name='price1']").val(getFloat(num * price, 2));
        $("input[name='price']").val(getFloat(num * price, 2));
        if(min == max || num >= max){
            $("select[name='multi']").val(0);
            $("input[name='min']").val('');
            $("input[name='max']").val('');
        }else{
            $("select[name='multi']").val(1);
            $("input[name='min']").val('');
            $("input[name='max']").val(Math.floor(max/num));
        }
        $("select[name='multi']").change();
    }
    function fileSelect(){
        $("#file").trigger("click");
    }
    function fileView(){
        var shopimg = $("#shopimg").val();
        if(shopimg=='') {
            layer.alert("请先上传图片，才能预览");
            return;
        }
        if(shopimg.indexOf('http') == -1)shopimg = '../'+shopimg;
        layer.open({
            type: 1,
            area: ['360px', '400px'],
            title: '商品图片查看',
            shade: 0.3,
            anim: 1,
            shadeClose: true,
            content: '<center><img width="300px" src="'+shopimg+'"></center>'
        });
    }
    function fileUpload(){
        var fileObj = $("#file")[0].files[0];
        if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
            return;
        }
        var formData = new FormData();
        formData.append("do","upload");
        formData.append("type","shop");
        formData.append("file",fileObj);
        var ii = layer.load(2, {shade:[0.1,'#fff']});
        $.ajax({
            url: "ajax.php?act=uploadimg",
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
                    $("#shopimg").val(data.url);
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
    function Addstr(id, str) {
        $("#"+id).val($("#"+id).val()+str);
    }
    $(document).ready(function(){
        $("select[name='multi']").change(function(){
            if($(this).val() == 1){
                $("#multi0").show();
            }else{
                $("#multi0").hide();
            }
        });
        $("select[name='validate']").change(function(){
            if($(this).val() >= 2){
                $("#valiserv").show();
            }else{
                $("#valiserv").hide();
            }
        });
        var items = $("select[default]");
        for (i = 0; i < items.length; i++) {
            $(items[i]).val($(items[i]).attr("default")||0);
        }
        $("select[name='shequ']").change();
        $("select[name='prid']").change();
        $("select[name='multi']").change();
        $("select[name='validate']").change();
        $("input[name='goods_id']").blur();

    });
</script>
<?php 
if ($conf["shopdesc_editor"]) {
	?><script charset="utf-8" src="../assets/kindeditor/kindeditor-all-min.js"></script>
<script charset="utf-8" src="../assets/kindeditor/zh-CN.js"></script>
<script>
KindEditor.ready(function(K) {
	window.editor = K.create('#editor_id', {
		resizeType : 1,
		allowUpload : false,
		allowPreviewEmoticons : false,
		uploadJson : './ajax.php?act=article_upload',
		items : [
			'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic', 'underline',
			'removeformat','formatblock','hr', '|', 'justifyleft', 'justifycenter', 'justifyright', 'insertorderedlist',
			'insertunorderedlist', '|', 'image', 'link','unlink', 'code', '|','fullscreen','source','preview']
	});
});
</script>
<?php 
}
?><script>
<?php 
\lib\Plugin::showThirdPluginsEditJs();
?></script>
</body>
</html>