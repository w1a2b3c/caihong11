<?php
/**
 * 登录
**/
$is_defend=true;
include("../includes/common.php");
if(isset($_GET['logout'])){
	if(!checkRefererHost())exit();
	setcookie("sup_token", "", time() - 604800, '/');
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已成功注销本次登录！');window.location.href='./login.php';</script>");
}elseif($islogin3==1){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已登录！');window.location.href='./';</script>");
}
$title='供货商登录';
include './head2.php';
?>
<?php if($background_image){?>
<img src="<?php echo $background_image;?>" alt="Full Background" class="full-bg full-bg-bottom animation-pulseSlow" ondragstart="return false;" oncontextmenu="return false;">
<?php }?>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-4 center-block " style="float: none;">
  <br /><br /><br />
    <div class="widget">
    <div class="widget-content themed-background-flat text-center"  style="background-image: url(<?php echo $cdnserver?>assets/simple/img/head3.jpg);background-size: 100% 100%;" >
<img  class="img-circle"src="//q4.qlogo.cn/headimg_dl?dst_uin=<?php echo $conf['kfqq'];?>&spec=100" alt="Avatar" alt="avatar" height="60" width="60" />
<p></p>
    </div>

    <div class="block">
        <div class="block-title">
            <div class="block-options pull-right">
            <a href="../" class="btn btn-effect-ripple btn-default toggle-bordered enable-tooltip">返回首页</a>
            </div>
            <h2><i class="fa fa-user"></i>&nbsp;&nbsp;<b>供货商登录</b></h2>
        </div>
          <form>
            <div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-user"></span></div>
              <input type="text" name="user" value="" class="form-control" required="required" placeholder="用户名"/>
            </div><br/>
            <div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></div>
              <input type="password" name="pass" class="form-control" required="required" placeholder="密码"/>
            </div><br/>
			<?php if($conf['captcha_open_login']==1 && $conf['captcha_open']>=1){?>
			<input type="hidden" name="captcha_type" value="<?php echo $conf['captcha_open']?>"/>
			<?php if($conf['captcha_open']>=2){?><input type="hidden" name="appid" value="<?php echo $conf['captcha_id']?>"/><?php }?>
			<div id="captcha" style="margin: auto;"><div id="captcha_text">
                正在加载验证码
            </div>
            <div id="captcha_wait">
                <div class="loading">
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                    <div class="loading-dot"></div>
                </div>
            </div></div>
			<div id="captchaform"></div>
			<br/>
			<?php }?>
            <div class="form-group">
			  <input type="button" value="立即登录" id="submit_login" class="btn btn-primary btn-block"/>
            </div>
			<hr>
			<?php if($conf['login_qq']>=1 || $conf['login_wx']>=1){?>
			<div class="form-group text-center">
			  <?php if($conf['login_qq']>=1){?><a href="javascript:connect('qq')"><img src="../assets/img/social/qq.png"></a>&nbsp;<?php }?>
			  <?php if($conf['login_wx']>=1){?><a href="javascript:connect('wx')"><img src="../assets/img/social/wx.png"></a>&nbsp;<?php }?>
            </div>
			<?php }?>
			<div class="form-group">
			<a href="findpwd.php" class="btn btn-info btn-rounded"><i class="fa fa-unlock"></i>&nbsp;找回密码</a>
			<?php if($conf['user_open']==1){?>
			<a href="reg.php" class="btn btn-danger btn-rounded" style="float:right;"><i class="fa fa-user-plus"></i>&nbsp;注册账号</a>
			<?php }else{?>
			<a href="regsite.php" class="btn btn-danger btn-rounded" style="float:right;"><i class="fa fa-user-plus"></i>&nbsp;开通分站</a>
			<?php }?>
			<?php if($conf['forceloginhome']==1){ echo '<hr/>'.$conf['footer'];}?>
			</div>
          </form>
    </div>
  </div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="../assets/js/login.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>