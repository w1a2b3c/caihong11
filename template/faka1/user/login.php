<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');

$hometitle = '用户登录 - '.$conf['sitename'];

if(checkmobile()){
include_once TEMPLATE_ROOT.'faka/user/waphead.php';
?>
<div style="height: 50px"></div>

<div class="menux">
   <div align="center">用户登录</div></div>
   
   
   <div class="main" style="margin:30px;margin-top:65px;">
  <div class="ui-btn-wrap">

        <div class="item item-username">
          <input id="yyb_username" class="txt-input txt-username" type="text" name="user" value=""  required="required" placeholder="&#12288;用户名" style="">
        </div>
          
        <div class="item item-password" style="margin-top:12px;">
          <input id="yyb_password" class="txt-input txt-password ciphertext" type="password"  name="pass"  required="required" placeholder="&#12288;密码"  style="display: inline;">
        </div>

		
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
		

        
        <div style="margin-top:15px;">
		<button class="ui-btn-lg ui-btn-danger" id="submit_login" type="button" value="立即登录" style="">立即登录</button>
        </div>
		<br>
        <div><a class="ui-btn-lg ui-btn-primary" href="reg.php" style="">注册账号</a></div>
		<br>
        <div><a class="ui-btn-lg" href="findpwd.php" style="">找回密码</a></div>
		<br>
		<?php if($conf['login_qq']>=1 || $conf['login_wx']>=1){?>
		<hr/>
		<div class="item item-login-other">
          <br/>其它登录方式<br/><br/>
            <?php if($conf['login_qq']>=1){?><a class="qq" href="javascript:connect('qq')"><span><img alt="" src="<?php echo $cdnserver?>assets/faka/images/login_qq.png" height="45"></span></a>&nbsp;&nbsp;<?php }?><?php if($conf['login_wx']>=1){?>&nbsp;&nbsp;<a class="qq" href="javascript:connect('wx')"><span><img alt="" src="<?php echo $cdnserver?>assets/img/wx.png" height="45"></span></a><?php }?>
        </div><?php }?>
      </div>
  </div>
<?php
include_once TEMPLATE_ROOT.'faka/user/wapfoot.php';
}else{
include_once TEMPLATE_ROOT.'faka/user/head.php';
?>
    <div style="margain-bottom：20px;margin: 0 auto;width: 1180px;border-radius: 3px;">
    <div class="ziti" style="font-size: 16px;color: #7a7a7a;padding-top: 12px;padding-right: 6px;padding-bottom: 6px;padding-left: 4px;border-radius: 3px;">
        当前位置  -&gt;  <a href="../">网站首页</a>  -&gt;  <a href="./">用户中心</a>  -&gt;  用户登录
    </div>
</div>



<div id="logins">

    <div class="registerform">
        <div class="wodetitle ziti" style="font-size: 28px">用户登录<div><a class="require" id="msg"></a></div></div>
        <div class="reg">

            <div class="from">

                <div class="from_wz_3 ziti" style="font-size: 16px;">用户名：</div>

                <div class="from_in_6" style="width:250px"><input type="text" name="user" value=""  required="required" placeholder="用户名"/></div>

            </div>

            <div class="from">

                <div class="from_wz_3 ziti" style="font-size: 16px;">密码：</div>

                <div class="from_in_6" style="width:250px"><input type="password"  name="pass"  required="required" placeholder="密码"/></div>

            </div>

            <?php if($conf['captcha_open_login']==1 && $conf['captcha_open']>=1){?>
			<input type="hidden" name="captcha_type" value="<?php echo $conf['captcha_open']?>"/>
			<?php if($conf['captcha_open']>=2){?><input type="hidden" name="appid" value="<?php echo $conf['captcha_id']?>"/><?php }?>
			<div class="from"><div class="from_off_3"></div>
			<div id="captcha" style="margin: auto;width:250px" class="from_in_6"><div id="captcha_text">
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
			</div>
			<?php }?>

        <div class="wu"></div>

        <div class="from">

            <div class="from_off_3"></div>
            <div class="from_in_6" style="width:100px">
                <button class="button button-3d button-rounded button-primary button-small ziti" type="button" value="立即登陆"   id="submit_login" style="font-size: 16px;">登录</button>
            </div>

            <div class="from_in_2 yanzheng" style="width:100px"><a href="reg.php" class="button button-3d button-rounded button-caution button-small ziti" style="font-size: 16px;">注册</a></div>
        </div>



        <div class="wu"></div>

        <div class="from">
            <div class="from_wz_3"> &nbsp;</div>
            <div class="from_in_6">
                <?php if($conf['login_qq']>=1){?><a href="javascript:connect('qq')"><img src="../assets/img/social/qq.png"></a><?php }?>
				<?php if($conf['login_wx']>=1){?><a href="javascript:connect('wx')"><img src="../assets/img/social/wx.png"></a><?php }?>
            </div>
        </div>
		<div class="from">
            <div class="from_wz_3"> &nbsp;</div>
            <div class="from_in_6"><a href="findpwd.php" class="ziti" style="font-size: 16px;">找回密码</a>
			</div>
		</div>
    </div>
</div></div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<?php }?>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="../assets/js/login.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>