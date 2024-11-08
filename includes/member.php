<?php
if(!defined('IN_CRONLITE'))exit();

$clientip=real_ip($conf['ip_type']?$conf['ip_type']:0);

if(isset($_SESSION["admin_token"]))
{
	$token=authcode(daddslashes($_SESSION['admin_token']), 'DECODE', SYS_KEY);
	list($admintypeid, $user, $sid, $time) = explode("\t", $token);
	if($admintypeid == '1'){
		if($adminuserrow = $DB->getRow("SELECT * FROM pre_account WHERE id='".intval($user)."' LIMIT 1")){
			$session=md5($adminuserrow['username'].$adminuserrow['password'].$password_hash);
			if($session===$sid && $adminuserrow['active']==1 && $time > time()) {
				$islogin=1;
			}
		}
	}else{
		$session=md5($conf['admin_user'].$conf['admin_pwd'].$password_hash);
		if($session===$sid && $time > time()) {
			$islogin=1;
		}
	}
}
if(isset($_COOKIE["user_token"]))
{
	$token=authcode(daddslashes($_COOKIE['user_token']), 'DECODE', SYS_KEY);
	list($zid, $sid) = explode("\t", $token);
	if($userrow = $DB->getRow("SELECT * FROM pre_site WHERE zid='".intval($zid)."' LIMIT 1")){
		$session=md5($userrow['user'].$userrow['pwd'].$password_hash);
		if($session===$sid && $userrow['status']==1) {
			$islogin2=1;
		}
	}
}
if(isset($_COOKIE["sup_token"]))
{
    $token=authcode(daddslashes($_COOKIE['sup_token']), 'DECODE', SYS_KEY);
    list($sid, $ssid) = explode("\t", $token);
    if($suprow = $DB->getRow("SELECT * FROM pre_supplier WHERE sid='".intval($sid)."' LIMIT 1")){
        $session=md5($suprow['user'].$suprow['pwd'].$password_hash);
        if($session===$ssid && $suprow['status']==1) {
            $islogin3=1;
        }
    }
}
?>