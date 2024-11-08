<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');

$hometitle = '找回密码 - '.$conf['sitename'];

if(checkmobile()){
include_once TEMPLATE_ROOT.'faka/user/waphead.php';
?>
<div style="height: 50px"></div>

<div class="menux">
   <div align="center">找回密码</div></div>
   
   
   <div class="main" style="margin:30px;margin-top:65px;">
  <div class="ui-btn-wrap">

       
			<div class="form-group" style="text-align: center;">
				<div class="list-group-item list-group-item-info" style="font-weight: bold;" id="login">
					<span id="loginmsg">请使用QQ手机版扫描二维码</span><span id="loginload" style="padding-left: 10px;color: #790909;">.</span>
				</div>
				<div id="qrimg">
				</div>
			</div>

        <div style="padding-left:10px;margin-top:20px;">
	<div class="list-group-item" id="mobile" style="display:none;"><button type="button" id="mlogin" onclick="mloginurl()" class="ui-btn-lg ui-btn-danger">跳转QQ快捷登录</button><br/><button type="button" onclick="qrlogin()" class="ui-btn-lg ui-btn-primary">我已完成登录</button></div>
			</div>
<?php if($conf['login_qq']==1){?><div style="padding: 20px 30px;">提示：只能找回注册时填写了QQ号码的帐号密码，QQ快捷登录的暂不持支该方式找回密码。</div><?php }?>

      </div>
  </div>
<?php
include_once TEMPLATE_ROOT.'faka/user/wapfoot.php';
}else{
include_once TEMPLATE_ROOT.'faka/user/head.php';
?>
    
    <div style="margain-bottom：20px;margin: 0 auto;width: 1180px;border-radius: 3px;">
    <div class="ziti" style="font-size: 16px;color: #7a7a7a;padding-top: 12px;padding-right: 6px;padding-bottom: 6px;padding-left: 4px;border-radius: 3px;">
        当前位置  -&gt;  <a href="../">网站首页</a>  -&gt;  <a href="./">用户中心</a>  -&gt;  找回密码
    </div>
</div>



<div id="logins">

    <div class="registerform">
        <div class="wodetitle ziti" style="font-size: 28px">找回密码<div><a class="require" id="msg"></a></div></div>
        <div class="reg">

           
			<div class="form-group" style="text-align: center;">
                <div class="list-group-item list-group-item-info" style="font-weight: bold;font-size: 16px;padding: 10px 0;" id="login">
                    <span id="loginmsg">请使用QQ手机版扫描二维码</span><span id="loginload" style="padding-left: 10px;color: #790909;">.</span>
                </div>
                <div id="qrimg">
                </div>
                <div class="list-group-item" id="mobile" style="display:none;"><button type="button" id="mlogin" onclick="mloginurl()" class="ui-btn-lg ui-btn-danger">跳转QQ快捷登录</button><br/><button type="button" onclick="loadScript()" class="ui-btn-lg ui-btn-primary">我已完成登录</button></div>
            </div>

			<?php if($conf['login_qq']==1){?><div style="padding: 10px 60px;">提示：只能找回注册时填写了QQ号码的帐号密码，QQ快捷登录的暂不持支该方式找回密码。</div><?php }?>

        <div class="wu"></div>

        <div class="from">

            <div class="from_off_2"></div>
            <div class="from_in_6" style="text-align: center;">
                <a class="ziti" type="button" id="mlogin" href="login.php" style="font-size: 16px;">返回登录</a>
            </div>
        </div>



        <div class="wu"></div>

    </div>
</div></div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<?php }?>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="../assets/js/qrlogin.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>