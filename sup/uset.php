<?php


require "../includes/common.php";
if ($islogin3 == 1) {
} else {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
$title = "网站设置";
include "head.php";
if ($conf["sup_cost2"] <= 0) {
	$conf["sup_cost2"] = $conf["sup_price2"];
}
?><div class="wrapper">
<div class="col-sm-12">
<?php 
$mod = isset($_GET["mod"]) ? $_GET["mod"] : null;
if ($mod == "user_n") {
	if (!checkRefererHost()) {
		exit;
	}
	$qq = daddslashes(htmlspecialchars(strip_tags($_POST["qq"])));
	$pay_type = daddslashes(intval($_POST["pay_type"]));
	$pay_account = daddslashes(htmlspecialchars(strip_tags($_POST["pay_account"])));
	$pay_name = daddslashes(htmlspecialchars(strip_tags($_POST["pay_name"])));
	$pwd = daddslashes(htmlspecialchars(strip_tags($_POST["pwd"])));
	if (!empty($pwd) && !preg_match("/^[a-zA-Z0-9\\_\\!\\@\\#\\\$~\\%\\^\\&\\*.,]+\$/", $pwd)) {
		exit("<script language='javascript'>alert('密码只能为英文与数字！');history.go(-1);</script>");
	} elseif (!preg_match("/^[0-9]{5,11}+\$/", $qq)) {
		exit("<script language='javascript'>alert('QQ格式不正确！');history.go(-1);</script>");
	} else {
		$DB->exec("UPDATE pre_supplier SET qq=:qq,pay_type=:pay_type,pay_account=:pay_account,pay_name=:pay_name WHERE sid=:sid", array(":qq" => $qq, ":pay_type" => $pay_type, ":pay_account" => $pay_account, ":pay_name" => $pay_name, ":sid" => $suprow["sid"]));
		if (!empty($pwd)) {
			$DB->exec("update pre_supplier set pwd=:pwd where sid=:sid", array(":pwd" => $pwd, ":sid" => $suprow["sid"]));
		}
		exit("<script language='javascript'>alert('修改保存成功！');history.go(-1);</script>");
	}
} elseif ($mod == "user") {
	$url = "https://api.fcypay.com/";
	$m = md5(rand(1000000, 9999999) . date("YmdHis") . uniqid());
	$code_url = $url . "get_openid_qrcode?mark=" . $m;
	$cron_url = $url . "get_openid_status?mark=" . $m;
	?><div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >用户资料设置</div>
<div class="panel-body">
  <form action="./uset.php?mod=user_n" method="post" role="form">
  <?php 
	if ($conf["login_qq"] == 1) {
		?>  <div class="form-group">
    <label><img src="https://qzonestyle.gtimg.cn/qzone/vas/opensns/res/img/bt_blue_24X24.png">&nbsp;QQ快捷登录：</label><?php 
		if ($suprow["qq_openid"]) {
			?><font color="green">已绑定</font>&nbsp;<a class="btn btn-xs btn-default" href="javascript:unbind('qq')">解绑</a><?php 
		} else {
			?><font color="red">未绑定</font>&nbsp;<a class="btn btn-xs btn-success" href="javascript:connect('qq')">立即绑定</a><?php 
		}
		?>  </div>
  <?php 
	}
	?>	<div class="form-group">
	  <label>登录用户名:</label><br/>
	  <input type="text" value="<?php echo $suprow["user"];?>" class="form-control" disabled/>
	</div>
	<div class="form-group">
	  <label>联系ＱＱ:</label><br/>
	  <input type="text" name="qq" value="<?php echo $suprow["qq"];?>" class="form-control" placeholder="用于联系与找回密码" required/>
	</div>

	<div class="form-group">
	  <label>提现方式:</label><br/>
	  <select class="form-control" name="pay_type" default="<?php echo $suprow["pay_type"];?>"><?php 
	if ($conf["sup_tixian_alipay"] == 1) {
		?><option value="0">支付宝</option><?php 
	}
	if ($conf["sup_tixian_wx"] == 1) {
		?><option value="1">微信</option><?php 
	}
	if ($conf["sup_tixian_qq"] == 1) {
		?><option value="2">QQ钱包</option><?php 
	}
	?></select>
	</div>
	<div class="form-group">
	  <label>提现账号:</label><br/>
	  <input type="text" name="pay_account" value="<?php echo $suprow["pay_account"];?>" class="form-control"/>
      <a href="javascript:getopenid()" class="btn btn-info" style="display:none" id="getopenid">自动获取</a>
	</div>
	<div class="form-group">
	  <label>提现姓名:</label><br/>
	  <input type="text" name="pay_name" value="<?php echo $suprow["pay_name"];?>" class="form-control"/>
	</div>

	<?php 
	if (substr($suprow["user"], 0, 3) != "qq_") {
		?>	<div class="form-group">
	  <label>重置密码:</label><br/>
	  <input type="text" name="pwd" value="" class="form-control" placeholder="不修改请留空"/>
	</div>
	<?php 
	}
	?>	<div class="form-group">
	  <input type="submit" name="submit" value="修改" class="btn btn-primary form-control"/>
	</div>
  </form>
  </div>
</div>
<?php 
	if (substr($suprow["user"], 0, 3) == "qq_") {
		?><div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >登录用户名与密码设置</div>
<div class="panel-body">
<div class="alert alert-info">设置登录用户名与密码之后，就可以使用对接与用户名密码登录了，不会影响到QQ快捷登录</div>
  <form onsubmit="return setpwd()" method="post" role="form">
	<div class="form-group">
	  <label>登录用户名:</label><br/>
	  <input type="text" name="user" placeholder="输入登录用户名" class="form-control" required/><font color="green">登录用户名一经设置无法修改</font>
	</div>
	<div class="form-group">
	  <label>登录密码:</label><br/>
	  <input type="text" name="pwd" placeholder="输入6位以上密码" class="form-control" required/>
	</div>
	<div class="form-group">
	  <input type="submit" name="submit" value="保存" class="btn btn-primary form-control"/>
	</div>	
  </form>
  </div>
</div>
<?php 
	}
} elseif ($mod == "skimg") {
	?><div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >提现收款图设置</div>
<div class="panel-body"><?php 
	if ($_POST["s"] == 1) {
		if (!checkRefererHost()) {
			exit;
		}
		$extension = explode(".", $_FILES["shoukuan"]["name"]);
		if (($length = count($extension)) > 1) {
			$ext = strtolower($extension[$length - 1]);
		}
		copy($_FILES["shoukuan"]["tmp_name"], ROOT . "assets/img/skimg/sk_sup" . $suprow["sid"] . ".png");
		echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果，按Ctrl+F5即可一键刷新缓存）";
	}
	if (file_exists(ROOT . "assets/img/skimg/sk_sup" . $suprow["sid"] . ".png")) {
		$logo = "../assets/img/skimg/sk_sup" . $suprow["sid"] . ".png";
	} else {
		$logo = "../assets/img/skimg/sk.png";
	}
	echo "<form action=\"uset.php?mod=skimg\" method=\"POST\" enctype=\"multipart/form-data\"><label for=\"file\"></label><input type=\"file\" name=\"shoukuan\" id=\"shoukuan\" /><input type=\"hidden\" name=\"s\" value=\"1\" /><br><input type=\"submit\" class=\"btn btn-primary form-control\" value=\"确认上传\" /></form><br>现在的收款图：<br><img src=\"" . $logo . "\" style=\"max-width:30%\">";
	?></div></div><?php 
}
?>	</div>
</div>
<?php 
include "./foot.php";
?><script src="<?php echo $cdnpublic;?>jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<?php 
if ($mod == "user") {
	?><script>
var items = $("select[default]");
for (i = 0; i < items.length; i++) {
	$(items[i]).val($(items[i]).attr("default")||0);
}
<?php 
	if ($conf["sup_daifu"] == 1) {
		?>var getopenid = function () {
    var open = layer.open({
        type:1,
        title:'',
        content:'<div class="layui-card-body"><h3 style="text-align:center">请使用微信扫一扫</h3><div><div id="qrcode" style="padding:15px;"></div></div></div>',
        cancel: function(index, layero){ 
            layer.close(open);
            window.clearInterval(cron); 
        },success: function(){ 
			var code_url = '<?php echo $code_url;?>';
			$('#qrcode').qrcode({
				text: code_url,
				width: 230,
				height: 230,
				foreground: "#000000",
				background: "#ffffff",
				typeNumber: -1
			});
        }
    });
    var cron = setInterval(function(){
        $.ajax({
            type: "GET",
            url: '<?php echo $cron_url;?>'+'&r='+Math.random(),
            dataType: "json",
            success: function(data){
                if (data.code) {
                    $("input[name=pay_account]").val(data.data);
                    layer.close(open);
                    window.clearInterval(cron); 
                }
            }
        });
    },3000);
}
$("select[name='pay_type']").change(function(){
	if($(this).val() == 1){
		$("#getopenid").show();
		$("input[name=pay_account]").attr("readOnly","readOnly");
	}else{
		$("#getopenid").hide();
		$("input[name=pay_account]").removeAttr("readOnly");
	}
});
$("select[name='pay_type']").change();
<?php 
	}
	?>
function setpwd(){
	var user = $("input[name='user']").val();
	var pwd = $("input[name='pwd']").val();
	if(user=='' || pwd==''){layer.alert('请确保每项不能为空！');return false;}
	if(user.length<3){
		layer.alert('用户名太短'); return false;
	}else if(user.length>20){
		layer.alert('用户名太长'); return false;
	}else if(pwd.length<6){
		layer.alert('密码不能低于6位'); return false;
	}else if(pwd.length>30){
		layer.alert('密码太长'); return false;
	}
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax_user.php?act=setpwd",
		data : {user:user, pwd:pwd},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				layer.alert(data.msg,{
				  closeBtn: 0
				}, function(){
				  window.location.reload();
				});
			}else{
				layer.alert(data.msg, {icon:0});
			}
		} 
	});
	return false;
}
function connect(type){
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		type : "POST",
		url : "ajax.php?act=connect",
		data : {type:type},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				window.location.href = data.url;
			}else{
				layer.alert(data.msg, {icon: 7});
			}
		} 
	});
}
function unbind(type){
	var confirmobj = layer.confirm('解绑后将无法通过QQ一键登录，是否确定解绑？', function () {
	var ii = layer.load(2, {shade:[0.1,'#fff']});
		$.ajax({
			type : "POST",
			url : "ajax.php?act=unbind",
			data : {type:type},
			dataType : 'json',
			success : function(data) {
				layer.close(ii);
				if(data.code == 0){
					layer.alert(data.msg, {icon: 1}, function(){ window.location.reload();});
				}else{
					layer.alert(data.msg, {icon: 0});
				}
			} 
		});
	}, function(){
	  layer.close(confirmobj);
	});
}
</script>
<?php 
}
?></body>
</html>