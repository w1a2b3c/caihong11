<?php
include("../includes/common.php");

$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');

switch($act){
case 'login':
	$user=daddslashes($_POST['user']);
	$pass=daddslashes($_POST['pass']);
	if(!$user || !$pass){
		exit('{"code":-1,"msg":"用户名或密码不能为空"}');
	}
	if($conf['captcha_open_login']==1 && $conf['captcha_open']==1){
		if(isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
			if(!isset($_SESSION['gtserver']))exit('{"code":-1,"msg":"验证加载失败"}');
			$GtSdk = new \lib\GeetestLib($conf['captcha_id'], $conf['captcha_key']);

			$data = array(
				'user_id' => $cookiesid,
				'client_type' => "web",
				'ip_address' => $clientip
			);

			if ($_SESSION['gtserver'] == 1) {   //服务器正常
				$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
				if ($result) {
					//echo '{"status":"success"}';
				} else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}else{  //服务器宕机,走failback模式
				if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
					//echo '{"status":"success"}';
				}else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}
		}else{
			exit('{"code":2,"type":1,"msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open_login']==1 && $conf['captcha_open']==2){
		if(isset($_POST['token'])){
			$client = new \lib\CaptchaClient($conf['captcha_id'], $conf['captcha_key']);
			$client->setTimeOut(2);
			$response = $client->verifyToken($_POST['token']);
			if($response->result){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":2,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open_login']==1 && $conf['captcha_open']==3){
		if(isset($_POST['token'])){
			if(vaptcha_verify($conf['captcha_id'], $conf['captcha_key'], $_POST['token'], $clientip)){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":3,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}
	$row=$DB->getRow("SELECT sid,user,pwd,status FROM pre_supplier WHERE user=:user LIMIT 1", [':user'=>$user]);
	if($row && $user===$row['user'] && $pass===$row['pwd']) {
		if($row['status']==0){
			exit('{"code":-1,"msg":"当前账号已被封禁！"}');
		}
		$session=md5($user.$pass.$password_hash);
		$token=authcode("{$row['sid']}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("sup_token", $token, time() + 604800, '/');
		log_result('供货商登录', 'User:'.$user.' IP:'.$clientip, null, 1);
		if($_SESSION['Oauth_qq_openid'] && $_SESSION['Oauth_qq_token']){
			if($_SESSION['Oauth_qq_type']=='wx'){
				$typename = '微信';
				$typecolumn = 'wx_openid';
			}else{
				$typename = 'QQ';
				$typecolumn = 'qq_openid';
			}
			$DB->exec("UPDATE pre_supplier SET {$typecolumn}=:qq_openid,lasttime=NOW() WHERE sid=:sid", [':qq_openid'=>$_SESSION['Oauth_qq_openid'], ':sid'=>$row['sid']]);
			unset($_SESSION['Oauth_qq_type']);
			unset($_SESSION['Oauth_qq_openid']);
			unset($_SESSION['Oauth_qq_token']);
			unset($_SESSION['Oauth_qq_nickname']);
			unset($_SESSION['Oauth_qq_faceimg']);
			exit('{"code":0,"msg":"绑定QQ快捷登录成功！"}');
		}else{
			$DB->exec("UPDATE pre_supplier SET lasttime=NOW() WHERE sid=:sid", [':sid'=>$row['sid']]);
			exit('{"code":0,"msg":"登陆用户中心成功！"}');
		}
	}else {
		exit('{"code":-1,"msg":"用户名或密码不正确！"}');
	}
break;
case 'connect':
	if(!$conf['login_qq'] && !$conf['login_wx'])exit('{"code":-1,"msg":"当前站点未开启QQ或微信快捷登录"}');
	$type = isset($_POST['type'])?$_POST['type']:exit('{"code":-1,"msg":"no type"}');
	$back = isset($_POST['back'])?$_POST['back']:null;
	if($type == 'qq' && $conf['login_qq']==2){
		$result = ['code'=>0, 'url'=>'connect.php?type=qq'];
		if($back){
			$_SESSION['Oauth_back'] = $back;
		}elseif(isset($_SESSION['Oauth_back'])){
			unset($_SESSION['Oauth_back']);
		}
	}else{
		$Oauth = new \lib\Oauth($conf['login_apiurl'], $conf['login_appid'], $conf['login_appkey']);
		$res = $Oauth->login($type);
		if(isset($res['code']) && $res['code']==0){
			$result = ['code'=>0, 'url'=>$res['url']];
			if($back){
				$_SESSION['Oauth_back'] = $back;
			}elseif(isset($_SESSION['Oauth_back'])){
				unset($_SESSION['Oauth_back']);
			}
		}elseif(isset($res['code'])){
			$result = ['code'=>-1, 'msg'=>$res['msg']];
		}else{
			$result = ['code'=>-1, 'msg'=>'快捷登录接口请求失败'];
		}
	}
	exit(json_encode($result));
break;
case 'unbind':
	if(!$islogin3)exit('{"code":-1,"msg":"未登录"}');
	if(!$conf['login_qq'] && !$conf['login_wx'])exit('{"code":-1,"msg":"当前站点未开启QQ或微信快捷登录"}');
	$type = isset($_POST['type'])?$_POST['type']:exit('{"code":-1,"msg":"no type"}');
	if($type=='wx'){
		$typename = '微信';
		$typecolumn = 'wx_openid';
	}else{
		$typename = 'QQ';
		$typecolumn = 'qq_openid';
	}
	if($DB->exec("update `pre_supplier` set `{$typecolumn}`=NULL where `sid`='{$suprow['sid']}'")){
		exit('{"code":0,"msg":"您已成功解绑'.$typename.'！"}');
	}else{
		exit('{"code":-1,"msg":"解绑'.$typename.'失败！'.$DB->error().'"}');
	}
break;
case 'quickreg':
	if(!$conf['login_qq'] && !$conf['login_wx'])exit('{"code":-1,"msg":"当前站点未开启QQ或微信快捷登录"}');
	if(!$_SESSION['Oauth_qq_openid'] || !$_SESSION['Oauth_qq_token'])exit('{"code":-1,"msg":"请返回重新登录"}');
	if(!$_POST['submit'])exit('{"code":-1,"msg":"access"}');
	$type = isset($_POST['type'])?$_POST['type']:exit('{"code":-1,"msg":"no type"}');
	$user = $type.'_'.random(8);
	$pwd = $_SESSION['Oauth_qq_token'];
	$openid = $_SESSION['Oauth_qq_openid'];
	$nickname = $_SESSION['Oauth_qq_nickname'];
	if(strlen($nickname)>32) $nickname = mb_strcut($nickname, 0, 32);
	$faceimg = $_SESSION['Oauth_qq_faceimg'];
	if($type=='wx'){
		$typecolumn = 'wx_openid';
		$pwd = md5($pwd);
	}else{
		$typecolumn = 'qq_openid';
	}

	$sql="insert into `pre_supplier` (`upsid`,`power`,`domain`,`domain2`,`user`,`pwd`,`{$typecolumn}`,`nickname`,`faceimg`,`rmb`,`qq`,`sitename`,`keywords`,`description`,`addtime`,`lasttime`,`status`) values (:upsid,0,NULL,NULL,:user,:pwd,:qq_openid,:nickname,:faceimg,'0',NULL,NULL,NULL,NULL,NOW(),NOW(),'1')";
	$data = [':upsid'=>$siterow['sid']?$siterow['sid']:0, ':user'=>$user, ':pwd'=>$pwd, ':qq_openid'=>$openid, ':nickname'=>$nickname, ':faceimg'=>$faceimg];
	if($DB->exec($sql, $data)){
		$sid = $DB->lastInsertId();
		unset($_SESSION['Oauth_qq_type']);
		unset($_SESSION['Oauth_qq_openid']);
		unset($_SESSION['Oauth_qq_token']);
		unset($_SESSION['Oauth_qq_nickname']);
		unset($_SESSION['Oauth_qq_faceimg']);
		$DB->exec("UPDATE `pre_orders` SET `userid`='".$sid."' WHERE `userid`='".$cookiesid."'");
		$session=md5($user.$pwd.$password_hash);
		$token=authcode("{$sid}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("user_token", $token, time() + 604800, '/');
		log_result('分站登录', 'User:'.$user.' IP:'.$clientip, null, 1);
		exit('{"code":0,"msg":"注册用户成功","sid":"'.$sid.'"}');
	}else{
		exit('{"code":-1,"msg":"注册用户失败！'.$DB->error().'"}');
	}
break;
case 'reguser':
	if($islogin3==1)exit('{"code":-1,"msg":"您已登陆！"}');
	elseif($conf['sup_reg']!=1)exit('{"code":-1,"msg":"当前站点未开启供货商注册功能！"}');
	$user = trim(htmlspecialchars(strip_tags(daddslashes($_POST['user']))));
	$pwd = trim(htmlspecialchars(strip_tags(daddslashes($_POST['pwd']))));
	$qq = trim(daddslashes($_POST['qq']));
	$hashsalt = isset($_POST['hashsalt'])?$_POST['hashsalt']:null;
	$code = isset($_POST['code'])?$_POST['code']:null;
	if($conf['verify_open']==1 && (empty($_SESSION['addsalt']) || $hashsalt!=$_SESSION['addsalt'])){
		exit('{"code":-1,"msg":"验证失败，请刷新页面重试"}');
	}
	if (!preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$user)) {
		exit('{"code":-1,"msg":"用户名只能为英文、数字与汉字！"}');
	} elseif ($DB->getRow("SELECT sid FROM pre_supplier WHERE user=:user LIMIT 1", [':user'=>$user])) {
		exit('{"code":-1,"msg":"用户名已存在！"}');
	} elseif (strlen($pwd) < 6) {
		exit('{"code":-1,"msg":"密码不能低于6位"}');
	} elseif (strlen($qq) < 5 || !preg_match('/^[0-9]+$/',$qq)) {
		exit('{"code":-1,"msg":"QQ格式不正确！"}');
	} elseif ($pwd == $user) {
		exit('{"code":-1,"msg":"用户名和密码不能相同！"}');
	}
	if($conf['captcha_open']==1){
		if(isset($_POST['geetest_challenge']) && isset($_POST['geetest_validate']) && isset($_POST['geetest_seccode'])){
			if(!isset($_SESSION['gtserver']))exit('{"code":-1,"msg":"验证加载失败"}');
			$GtSdk = new \lib\GeetestLib($conf['captcha_id'], $conf['captcha_key']);

			$data = array(
				'user_id' => $cookiesid,
				'client_type' => "web",
				'ip_address' => $clientip
			);

			if ($_SESSION['gtserver'] == 1) {   //服务器正常
				$result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
				if ($result) {
					//echo '{"status":"success"}';
				} else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}else{  //服务器宕机,走failback模式
				if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
					//echo '{"status":"success"}';
				}else{
					exit('{"code":-1,"msg":"验证失败，请重新验证"}');
				}
			}
		}else{
			exit('{"code":2,"type":1,"msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open']==2){
		if(isset($_POST['token'])){
			$client = new \lib\CaptchaClient($conf['captcha_id'], $conf['captcha_key']);
			$client->setTimeOut(2);
			$response = $client->verifyToken($_POST['token']);
			if($response->result){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":2,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}elseif($conf['captcha_open']==3){
		if(isset($_POST['token'])){
			if(vaptcha_verify($conf['captcha_id'], $conf['captcha_key'], $_POST['token'], $clientip)){
				/**token验证通过，继续其他流程**/
			}else{
				/**token验证失败**/
				exit('{"code":-1,"msg":"验证失败，请重新验证"}');
			}
		}else{
			exit('{"code":2,"type":3,"appid":"'.$conf['captcha_id'].'","msg":"请先完成验证"}');
		}
	}elseif (!$code || strtolower($code) != $_SESSION['vc_code']) {
		unset($_SESSION['vc_code']);
		exit('{"code":2,"msg":"验证码错误！"}');
	}
	$sql="insert into `pre_supplier` (`user`,`pwd`,`rmb`,`qq`,`addtime`,`lasttime`,`status`) values (:user,:pwd,'0',:qq,NOW(),NOW(),'1')";
	$data = [':user'=>$user, ':pwd'=>$pwd, ':qq'=>$qq];
	if($DB->exec($sql, $data)){
		$sid = $DB->lastInsertId();
		unset($_SESSION['addsalt']);
		$session=md5($user.$pwd.$password_hash);
		$token=authcode("{$sid}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("sup_token", $token, time() + 604800, '/');
		log_result('供货商登录', 'User:'.$user.' IP:'.$clientip, null, 1);
		exit('{"code":1,"msg":"注册用户成功","sid":"'.$sid.'"}');
	}else{
		exit('{"code":-1,"msg":"注册用户失败！'.$DB->error().'"}');
	}
break;
case 'checkuser':
    $user = trim($_GET['user']);
    $srow=$DB->getRow("SELECT sid FROM pre_supplier WHERE user=:user LIMIT 1", [':user'=>$user]);
    if($srow)exit('1');
    else exit('0');
    break;
default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}