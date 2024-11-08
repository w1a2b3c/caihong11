<?php
/**
 * 找回密码
**/
$is_defend=true;
include("../includes/common.php");
if(isset($_GET['act']) && $_GET['act']=='qrlogin'){
	if(isset($_SESSION['findpwd_qq']) && $qq=$_SESSION['findpwd_qq']){
		$row=$DB->getRow("SELECT sid,user,pwd,status FROM pre_supplier WHERE qq=:qq LIMIT 1", [':qq'=>$qq]);
		unset($_SESSION['findpwd_qq']);
		if($row['user']){
			if($row['status']==0){
				exit('{"code":-1,"msg":"当前账号已被封禁！"}');
			}
			$session=md5($row['user'].$row['pwd'].$password_hash);
			$token=authcode("{$row['sid']}\t{$session}", 'ENCODE', SYS_KEY);
			setcookie("sup_token", $token, time() + 604800, '/');
			log_result('分站找回密码', 'User:'.$row['user'].' IP:'.$clientip, null, 1);
			$DB->exec("UPDATE pre_supplier SET lasttime='$date' WHERE sid='{$row['sid']}'");
			exit('{"code":1,"msg":"登录成功，请在用户资料设置里重置密码","url":"./"}');
		}else{
			@header('Content-Type: application/json; charset=UTF-8');
			exit('{"code":-1,"msg":"当前QQ不存在，请确认你已注册过账号或开通过分站"}');
		}
	}else{
		@header('Content-Type: application/json; charset=UTF-8');
		exit('{"code":-2,"msg":"验证失败，请重新扫码"}');
	}
}elseif($islogin3==1){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已登陆！');window.location.href='./';</script>");
}
$title='找回密码';
include './head2.php';
?>
<br/><br/><br/>
<?php if($background_image){?>
<img src="<?php echo $background_image;?>" alt="Full Background" class="full-bg full-bg-bottom animation-pulseSlow" ondragstart="return false;" oncontextmenu="return false;">
<?php }?>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-4 center-block" style="float: none;">
    <div class="block">
        <div class="block-title">
            <div class="block-options pull-right">
            <a href="../" class="btn btn-effect-ripple btn-default toggle-bordered enable-tooltip">返回首页</a>
            </div>
            <h2><i class="fa fa-unlock"></i>&nbsp;&nbsp;<b>找回密码</b></h2>
        </div>
			<div class="form-group" style="text-align: center;">
				<div class="list-group-item list-group-item-info" style="font-weight: bold;" id="login">
					<span id="loginmsg">请使用QQ手机版扫描二维码</span><span id="loginload" style="padding-left: 10px;color: #790909;">.</span>
				</div>
				<div id="qrimg">
				</div>
				<button type="button" onclick="qrlogin()" class="btn btn-success btn-block">我已完成登录</button>
			</div>
			<hr>
			<?php if($conf['login_qq']==1){?><div class="alert alert-info">提示：只能找回注册时填写了QQ号码的帐号密码，QQ快捷登录的暂不持支该方式找回密码。</div><?php }?>
			<div class="form-group">
			<a href="login.php" class="btn btn-primary btn-rounded"><i class="fa fa-user"></i>&nbsp;返回登录</a>
			<a href="reg.php" class="btn btn-danger btn-rounded" style="float:right;"><i class="fa fa-user-plus"></i>&nbsp;注册用户</a>
			</div>
        </div>
      </div>
    </div>
  </div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="../assets/js/qrlogin.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>