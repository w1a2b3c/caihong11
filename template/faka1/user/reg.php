<?php
if (!defined('IN_CRONLITE')) die();
@header('Content-Type: text/html; charset=UTF-8');

$addsalt=md5(mt_rand(0,999).time());
$_SESSION['addsalt']=$addsalt;
$x = new \lib\hieroglyphy();
$addsalt_js = $x->hieroglyphyString($addsalt);

$hometitle = '注册账号 - '.$conf['sitename'];

if(checkmobile()){
include_once TEMPLATE_ROOT.'faka/user/waphead.php';
?>

<div style="height: 50px"></div>

<div class="menux">
   <div align="center">注册账号</div></div>
   
   
    <div class="main" style="margin:30px;margin-top:65px;">
  <div class="ui-btn-wrap">

        <div class="item item-username">
          <input  class="txt-input txt-username" type="text" name="user" value="" required="required" placeholder="&#12288;输入登录用户名" style="">
        </div>
          
		<div class="item item-password" style="margin-top:12px;">
          <input id="yyb_password" class="txt-input txt-password ciphertext" type="text" name="pwd"   class="layui-input" required="required" placeholder="&#12288;输入6位以上密码"/  style="display: inline;">
          <b class="tp-btn btn-off"></b>
        </div>

        <div class="item item-username" style="margin-top:12px;">
          <input  class="txt-input txt-username" type="text" name="qq" required="required" placeholder="&#12288;输入QQ号，用于找回密码" style="">
        </div>

	
<?php if($conf['captcha_open']>=1){?>
<input type="hidden" name="captcha_type" value="<?php echo $conf['captcha_open']?>"/>
			<?php if($conf['captcha_open']>=2){?><input type="hidden" name="appid" value="<?php echo $conf['captcha_id']?>"/><?php }?>
			<div id="captcha" style="margin: auto;">
				<div id="captcha_text">
					正在加载验证码
				</div>
				<div id="captcha_wait">
					<div class="loading">
						<div class="loading-dot"></div>
						<div class="loading-dot"></div>
						<div class="loading-dot"></div>
						<div class="loading-dot"></div>
					</div>
				</div>
			</div>
			<div id="captchaform"></div>
			<?php }else{?>
			<div class="item item-captcha" style="margin-top:12px;">
          <div class="input-info" style="">
            <input style="" class="txt-input txt-captcha" type="text"  lay-verify="required|captcha"  autocomplete="off" name="code" type="tel"  id="code" placeholder="&#12288;输入验证码">
            <b id="validateCodeclose" class="input-close" style="display: none; margin-right: 5px;"></b>
            <span id="captcha-img">
              <img style="width:73px;height:30px;" id="codeimg" src="./code.php?r=<?php echo time();?>"  onclick="this.src='./code.php?r='+Math.random();" title="点击更换验证码" width="90" height="35"></span>
          </div>
        </div>
<?php }?>


        
        <div class="" style="margin-top:15px;">

            <button class="ui-btn-lg ui-btn-danger" type="button" value="立即注册" id="submit_reg" value="立即注册" style="">立即注册</button>
        </div>
		<br>
		 
        <div class="" style=""> <a class="ui-btn-lg ui-btn-primary" href="login.php" style="">已有账号，登录</a> </div>
   
      </div>
  </div>
<?php
include_once TEMPLATE_ROOT.'faka/user/wapfoot.php';
}else{
include_once TEMPLATE_ROOT.'faka/user/head.php';
?>
    <div style="margain-bottom：20px;margin: 0 auto;width: 1180px;border-radius: 3px;">
    <div class="ziti" style="font-size: 16px;color: #7a7a7a;padding-top: 12px;padding-right: 6px;padding-bottom: 6px;padding-left: 4px;border-radius: 3px;">
        当前位置  -&gt;  <a href="../">网站首页</a>  -&gt;  <a href="./">用户中心</a>  -&gt;  注册账号
    </div>
</div>


<div id="logins">

    <div class="registerform">
        <div class="wodetitle ziti" style="font-size: 28px">注册账号<div><a class="require" id="msg"></a></div></div>
        <div class="reg">

            <div class="from">

                <div class="from_wz_3 ziti" style="font-size: 16px;">用户名：</div>

                <div class="from_in_6" style="width:250px"><input id="yyb_username" type="text" name="user" value="" required="required" placeholder="输入登录用户名"/></div>

            </div>

			<div class="from">

                <div class="from_wz_3 ziti" style="font-size: 16px;">密码：</div>

                <div class="from_in_6" style="width:250px"><input id="yyb_password"  type="text" name="pwd"   class="layui-input" required="required" placeholder="输入6位以上密码"/></div>

            </div>
            
            <div class="from">

                <div class="from_wz_3 ziti" style="font-size: 16px;">QQ号：</div>

                <div class="from_in_6" style="width:250px"><input id="yyb_username" type="text" name="qq" required="required" placeholder="输入QQ号，用于找回密码"/></div>

            </div>

           <?php if($conf['captcha_open']>=1){?>
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
            <?php }else{?>
          <div class="from">
          <div class="from_wz_3 ziti" style="font-size: 16px;">验证码：</div>
          <div class="from_in_4" style="width:90px">
            <input type="text"  lay-verify="required|captcha"  autocomplete="off" name="code" type="text" id="code" placeholder="输入验证码"></div>
          <div class="from_in_2 yanzheng">
            <img style="cursor:pointer;margin-left:5px;" id="codeimg" src="./code.php?r=<?php echo time();?>"  onclick="this.src='./code.php?r='+Math.random();" title="点击更换验证码" width="90" height="35"></div>
        </div>
            <?php }?>

        <div class="wu"></div>

        <div class="from">

            <div class="from_off_3"></div>
            <div class="from_in_6" style="width:100px">
                <button class="button button-3d button-rounded button-primary button-small ziti" type="button" value="立即注册" id="submit_reg" style="font-size: 16px;">注册</button>
            </div>

            <div class="from_in_2 yanzheng" style="width:100px"><a href="login.php" class="button button-3d button-rounded button-caution button-small ziti" style="font-size: 16px;">登录</a></div>
        </div>



        <div class="wu"></div>

        <div class="from">
            <div class="from_wz_3"> &nbsp;</div>
            <div class="from_in_6" style="width:250px">
                
                <a href="findpwd.php" class="ziti" style="font-size: 16px;">找回密码？</a></div>

        </div>
    </div>
</div></div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<?php }?>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script>var hashsalt=<?php echo $addsalt_js?>;</script>
<script src="../assets/js/reguser.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>