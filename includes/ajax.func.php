<?php


function getDatePoint()
{
	global $DB;
	global $conf;
	$DatePoint = $DB->getColumn("SELECT v FROM pre_config WHERE k='datepoint' limit 1");
	$DatePointArr = @unserialize($DatePoint);
	if (!isset($DatePointArr[0]) || $DatePointArr[0]["date"] != date("md", strtotime("-1 day"))) {
		$CountArr = array();
		for ($i = 1; $i < 8; $i++) {
			$thtime = date("Y-m-d", strtotime("-" . $i . " day")) . " 00:00:00";
			$yesterday_time = date("Y-m-d", strtotime("-" . ($i + 1) . " day")) . " 00:00:00";
			$OrderCount = $DB->getColumn("SELECT count(*) FROM pre_orders WHERE addtime<='" . $thtime . "' AND addtime>'" . $yesterday_time . "'");
			$PayCount = $DB->getColumn("SELECT sum(money) FROM `pre_pay` WHERE (addtime<='" . $thtime . "' AND addtime>'" . $yesterday_time . "') AND `status`=1");
			$CountArr[] = array("date" => date("md", strtotime("-" . $i . " day")), "orders" => $OrderCount, "money" => round($PayCount, 2));
		}
		$DatePoint = serialize($CountArr);
		saveSetting("datepoint", $DatePoint);
		$DatePointArr = $CountArr;
	}
	$DatePointArr = array_reverse($DatePointArr);
	$PointData = array("date" => array(), "orders" => array(), "money" => array());
	$i = 1;
	foreach ($DatePointArr as $DatePointData) {
		$PointData["date"][] = array($i, $DatePointData["date"]);
		$PointData["orders"][] = array($i, $DatePointData["orders"]);
		$PointData["money"][] = array($i, $DatePointData["money"]);
		$i = $i + 1;
	}
	return $PointData;
}
function shequ_get_curl($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $addheader = 0)
{
	global $conf;
	$server_hash = md5($_SERVER["SERVER_SOFTWARE"] . $_SERVER["SERVER_ADDR"]);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	if ($server_hash == $conf["server_hash"] && $conf["proxy"] == 1) {
		curl_setopt($ch, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_PROXY, $conf["proxy_server"]);
		curl_setopt($ch, CURLOPT_PROXYPORT, $conf["proxy_port"]);
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $conf["proxy_user"] . ':' . $conf["proxy_pwd"]);
		curl_setopt($ch, CURLOPT_PROXYTYPE, $conf["proxy_type"]);
	}
	$httpheader[] = "Accept: */*";
	$httpheader[] = "Accept-Encoding: gzip,deflate,sdch";
	$httpheader[] = "Accept-Language: zh-CN,zh;q=0.8";
	$httpheader[] = "Connection: close";
	if ($addheader) {
		$httpheader = array_merge($httpheader, $addheader);
	}
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	if ($post) {
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
		$httpheader[] = "Content-Type: application/x-www-form-urlencoded; charset=UTF-8";
	}
	curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheader);
	if ($header) {
		curl_setopt($ch, CURLOPT_HEADER, TRUE);
	}
	if ($cookie) {
		curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	}
	if ($referer) {
		if ($referer == 1) {
			curl_setopt($ch, CURLOPT_REFERER, 'http://m.qzone.com/infocenter?g_f=');
		} else {
			curl_setopt($ch, CURLOPT_REFERER, $referer);
		}
	}
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
	curl_setopt($ch, CURLOPT_ENCODING, "gzip");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$ret = curl_exec($ch);
	curl_close($ch);
	return $ret;
}
function setToolSort($cid, $tid, $sort = 0)
{
	global $DB;
	$tools = $DB->getRow("select * from pre_tools where tid='" . $tid . "' limit 1");
	$sortdata = $tools["sort"];
	if ($sort == 1) {
		if ($tools = $DB->getRow("select tid,sort from pre_tools where cid='" . $cid . "' and sort<'" . $sortdata . "' order by sort desc limit 1")) {
			$DB->exec("UPDATE pre_tools SET sort=" . $tools["sort"] . " WHERE tid='" . $tid . "'");
			$DB->exec("UPDATE pre_tools SET sort=" . $sortdata . " WHERE tid='" . $tools["tid"] . "'");
			return true;
		}
	} elseif ($sort == 2) {
		if ($tools = $DB->getRow("select tid,sort from pre_tools where cid='" . $cid . "' and sort>'" . $sortdata . "' order by sort asc limit 1")) {
			$DB->exec("UPDATE pre_tools SET sort=" . $tools["sort"] . " WHERE tid='" . $tid . "'");
			$DB->exec("UPDATE pre_tools SET sort=" . $sortdata . " WHERE tid='" . $tools["tid"] . "'");
			return true;
		}
	} elseif ($sort == 3) {
		$tools = $DB->getRow("select tid,sort from pre_tools order by sort desc limit 1");
		$DB->exec("UPDATE pre_tools SET sort=sort-1 WHERE sort>" . $sortdata . '');
		$DB->exec("UPDATE pre_tools SET sort=" . $tools["sort"] . " WHERE tid='" . $tid . "'");
		return true;
	} else {
		$tools = $DB->getRow("select tid,sort from pre_tools order by sort asc limit 1");
		$DB->exec("UPDATE pre_tools SET sort=sort+1 WHERE sort<" . $sortdata . '');
		$DB->exec("UPDATE pre_tools SET sort=" . $tools["sort"] . " WHERE tid='" . $tid . "'");
		return true;
	}
	return false;
}
function setClassSort($cid, $sort = 0)
{
	global $DB;
	$class = $DB->getRow("select * from pre_class where cid='" . $cid . "' limit 1");
	$sortdata = $class["sort"];
	if ($sort == 1) {
		if ($class = $DB->getRow("select cid,sort from pre_class where sort<'" . $sortdata . "' order by sort desc limit 1")) {
			$DB->exec("UPDATE pre_class SET sort=" . $class["sort"] . " WHERE cid='" . $cid . "'");
			$DB->exec("UPDATE pre_class SET sort=" . $sortdata . " WHERE cid='" . $class["cid"] . "'");
			return true;
		}
	} elseif ($sort == 2) {
		if ($class = $DB->getRow("select cid,sort from pre_class where sort>'" . $sortdata . "' order by sort asc limit 1")) {
			$DB->exec("UPDATE pre_class SET sort=" . $class["sort"] . " WHERE cid='" . $cid . "'");
			$DB->exec("UPDATE pre_class SET sort=" . $sortdata . " WHERE cid='" . $class["cid"] . "'");
			return true;
		}
	} elseif ($sort == 3) {
		$class = $DB->getRow("select cid,sort from pre_class order by sort desc limit 1");
		$DB->exec("UPDATE pre_class SET sort=sort-1 WHERE sort>" . $sortdata . '');
		$DB->exec("UPDATE pre_class SET sort=" . $class["sort"] . " WHERE cid='" . $cid . "'");
		return true;
	} else {
		$class = $DB->getRow("select cid,sort from pre_class order by sort asc limit 1");
		$DB->exec("UPDATE pre_class SET sort=sort+1 WHERE sort<" . $sortdata . '');
		$DB->exec("UPDATE pre_class SET sort=" . $class["sort"] . " WHERE cid='" . $cid . "'");
		return true;
	}
	return false;
}
function getshareid($url)
{
	$arr = get_curl('https://app.heikz.com/ajax.php?act=getshareid', 'url=' . $url, 'https://app.heikz.com/');
	if (!($ret = json_decode($arr, true))) {
		$data = array('code' => -1, 'msg' => '云端打开失败,请联系作者处理！');
	} else {
		$data = $ret;
	}
	return $data;
}
function getshuoshuo($uin, $page = 1)
{
	$arr = get_curl('https://api.nanyinet.com/api/sayid/api.php?qq=' . $uin . '&page=' . $page . '');
	if (!($ret = json_decode($arr, true))) {
		$data = array('code' => -1, 'msg' => '云端打开失败,请联系作者处理！');
	} else {
		if ($ret['code'] == 200) {
			if (is_array($ret['data'])) {
				$data = array('code' => 0, 'msg' => '获取成功', 'data' => $ret['data']);
			} else {
				$data = array('code' => -1, 'msg' => '未发布说说');
			}
		} else {
			$data = array('code' => -1, 'msg' => $ret['data']);
		}
	}
	return $data;
}
function getservices($qq, $name = '')
{
	$arr = get_curl('https://api.nanyinet.com/api/privilege/api.php?qq=' . $qq . '');
	if (!($ret = json_decode($arr, true))) {
		$data = array('code' => -1, 'msg' => '云端打开失败,请联系作者处理！');
	} else {
		if ($ret['code'] == 200) {
			if (!empty($name)) {
				foreach ($ret['data'] as $v) {
					if (strpos($name, $v['name']) !== false) {
						$data = array('code' => 200, 'msg' => '当前QQ已开通' . $name . '业务,无法完成下单');
					}
				}
			} else {
				$data = array('code' => 0, 'msg' => '业务列表获取成功', 'data' => $ret['data']);
			}
		} else {
			$data = array('code' => -1, 'msg' => $ret['data']);
		}
	}
	return $data;
}
function get_app_token($key)
{
	$addsalt = md5(mt_rand(0, 999) . time());
	if (strtolower($key) === md5("APP" . $_SERVER["HTTP_HOST"] . "KEY")) {
		$_SESSION["addsalt"] = $addsalt;
	}
	$result = authcode($addsalt, "ENCODE", "APP99KEYWW");
	return $result;
}
function processInvite($key)
{
	global $DB;
	global $date;
	global $clientip;
	if (!$key) {
		return NULL;
	}
	$inviterow = $DB->getRow("SELECT * FROM `pre_invite` WHERE `key` = '" . $key . "' LIMIT 1");
	$inviteshoprow = $DB->getRow("SELECT * FROM `pre_inviteshop` WHERE `id` = '" . $inviterow["nid"] . "' LIMIT 1");
	if ($inviterow && $inviteshoprow && $inviteshoprow['active'] == 1) {
		$date = date("Y-m-d") . " 00:00:00";
		if ($inviteshoprow['type'] == 1) {
			if ($DB->getColumn("SELECT count(*) FROM `pre_invitelog` WHERE `iid`='" . $inviterow["id"] . "' AND `type`='1' AND `ip`='" . $clientip . "'") == 0 && $inviterow['status'] == 0) {
				return "captcha";
			} else {
				return false;
			}
		} else {
			if ($DB->getColumn("SELECT count(*) FROM `pre_invitelog` WHERE `iid`='" . $inviterow["id"] . "' AND `type`='0' AND `ip`='" . $clientip . "' and `date`>'" . $date . "'") == 0 && $clientip != $inviterow["ip"]) {
				$DB->query("INSERT INTO `pre_invitelog`(`iid`,`type`,`date`,`ip`,`status`) VALUES ('" . $inviterow["id"] . "', 0, '" . $date . "', '" . $clientip . "', 0)");
				$invite_id = $DB->lastInsertId();
				setcookie("invite", $invite_id, time() + 7200, '/');
			}
			return true;
		}
	} else {
		return false;
	}
}
function fanghongdwz($url, $force = false)
{
	global $conf;
	$key = substr(md5($url), 0, 6);
	if (isset($_SESSION["dwz_" . $key]) && $force == false) {
		return $_SESSION["dwz_" . $key];
	}
	if ($conf["fanghong_api"] == 9) {
		if ($conf["fanghong_url"] && strpos($conf["fanghong_url"], "http") !== false && strpos($conf["fanghong_url"], "=") !== false && strpos($conf["fanghong_url"], "/") !== false) {
			$data = get_curl($conf["fanghong_url"] . urlencode($url));
			$result = json_decode($data, true);
			if (is_array($result)) {
				if (isset($result[$conf['fanghong_key']]) && $result[$conf['fanghong_key']] != "") {
					$_SESSION["dwz_" . $key] = $result[$conf['fanghong_key']];
					return $result[$conf['fanghong_key']];
				} else {
					return $url;
				}
			} else {
				return $url;
			}
		}
	} else {
		if ($conf['fanghong_api'] == 1) {
			$fhurl = 'https://fh.165r.com/api/url.php?type=' . (!empty($conf['fanghong_gftype']) ? $conf['fanghong_gftype'] : 'wy163') . '&pattern=' . (!empty($conf['fanghong_gfpattern']) ? $conf['fanghong_gfpattern'] : '3') . '&token=' . $conf['fanghong_gftoken'] . '&url=';
		} else {
			return $url;
		}
		$data = get_curl($fhurl . $url);
		$result = json_decode($data, true);
		if (is_array($result)) {
			if (isset($result['dwz']) && $result['dwz'] != "") {
				$_SESSION["dwz_" . $key] = $result['dwz'];
				return $result['dwz'];
			} else {
				return $url;
			}
		} else {
			return $url;
		}
	}
	return $url;
}
function getTimeToDay($timestamp)
{
	if ($timestamp <= 60) {
		return "00天00小时0分" . $timestamp . "秒";
	}
	$day = floor($timestamp / (3600 * 24));
	$day = $day > 0 ? $day . "天" : "0天";
	$hour = floor(($timestamp - 3600 * 24 * $day) / 3600);
	$hour = $hour > 0 ? $hour . "小时" : "0小时";
	$minutes = floor(($timestamp - 3600 * 24 * $day - $hour * 3600) / 60);
	$minutes = $minutes > 0 ? $minutes . "分" : "0小时";
	$second = $timestamp - 3600 * 24 * $day - $hour * 3600 - $minutes * 60;
	$second = $second . "秒";
	return $day . $hour . $minutes . $second;
}
function adminpermission($permission, $type = 1)
{
	global $conf;
	global $admintypeid;
	global $adminuserrow;
	if ($admintypeid == '1' && !in_array($permission, explode(",", $adminuserrow['permisson']))) {
		if ($type == 1) {
			showmsg('您的权限不足！', 4);
		} elseif ($type == 2) {
			exit('{"code":-1,"msg":"您的权限不足！"}');
		} else {
			exit("您的权限不足！");
		}
	}
}
function vaptcha_verify($id, $secretkey, $token, $ip)
{
	$url = 'https://guao.vaptcha.net/verify';
	$param = array();
	$param['id'] = $id;
	$param['secretkey'] = $secretkey;
	$param['scene'] = '0';
	$param['token'] = $token;
	$param['ip'] = $ip;
	$post = http_build_query($param);
	$data = get_curl($url, $post);
	$json = json_decode($data, true);
	if ($json['success'] == 1) {
		return true;
	} else {
		return false;
	}
}
function validate_qzone($uin)
{
	$arr = get_curl('https://api.nanyinet.com/api/sayid/api.php?qq=' . $uin . '');
	$arr = json_decode($arr, true);
	if (strstr($arr, '没有权限查看')) {
		return false;
	} else {
		return true;
	}
}
function checkIfActive($string)
{
	$array = explode(',', $string);
	$php_self = substr($_SERVER['REQUEST_URI'], strrpos($_SERVER['REQUEST_URI'], '/') + 1, strrpos($_SERVER['REQUEST_URI'], '.') - strrpos($_SERVER['REQUEST_URI'], '/') - 1);
	if (in_array($php_self, $array)) {
		return 'active';
	} else {
		if (isset($_GET['mod']) && in_array(str_replace('_n', '', $_GET['mod']), $array)) {
			return 'active';
		} else {
			return null;
		}
	}
}
function checkRefererHost()
{
	if (!$_SERVER['HTTP_REFERER']) {
		return false;
	}
	$url_arr = parse_url($_SERVER['HTTP_REFERER']);
	$http_host = $_SERVER['HTTP_HOST'];
	if (strpos($http_host, ':')) {
		$http_host = substr($http_host, 0, strpos($http_host, ':'));
	}
	return $url_arr['host'] === $http_host;
}
function sec_check()
{
	global $conf;
	global $dbconfig;
	$arr = array("readme.txt.zip", "mini.php.zip", "index.php.zip", "cron.php.zip", "config.php.zip", "api.php.zip", "ajax.php.zip", "archive.zip", "wwwroot.zip", "www.zip", "web.zip", "bf.zip", "beifen.zip", "backup.zip", "yuanma.zip", "1.zip", "2.zip", "daishua.zip", "ds.zip", "htdocs.zip", "wz.zip", "1.zip", "2.zip", "123.zip");
	foreach ($arr as $val) {
		if (file_exists(ROOT . $val)) {
			unlink(ROOT . $val);
		}
	}
	$result = array();
	$arr = glob(ROOT . "assets/img/*.php");
	foreach ($arr as $val) {
		unlink($val);
	}
	if (strpos($_SERVER["SERVER_SOFTWARE"], "kangle") !== false && function_exists("pcntl_exec")) {
		$result[] = "<li class=\"list-group-item\"><span class=\"btn-sm btn-danger\">高危</span>&nbsp;当前主机为kangle且开启了php的pcntl组件，会被黑客入侵，请联系主机商修复或更换主机</a></li>";
	}
	if (strpos($_SERVER["SERVER_SOFTWARE"], "kangle") !== false && count(glob("/vhs/kangle/etc/*")) > 1) {
		$result[] = "<li class=\"list-group-item\"><span class=\"btn-sm btn-danger\">高危</span>&nbsp;当前主机为kangle且未设置open_basedir防跨站，会被黑客入侵，请联系主机商修复或更换主机</a></li>";
	}
	if ($conf["admin_pwd"] === "123456") {
		$result[] = "<li class=\"list-group-item\"><span class=\"btn-sm btn-danger\">重要</span>&nbsp;请及时修改默认管理员密码 <a href=\"set.php?mod=account\">点此进入网站信息配置修改</a></li>";
	} else {
		if (strlen($conf["admin_pwd"]) < 6 || is_numeric($conf["admin_pwd"]) && strlen($conf["admin_pwd"]) <= 10 || $conf["admin_pwd"] === $conf["kfqq"]) {
			$result[] = "<li class=\"list-group-item\"><span class=\"btn-sm btn-danger\">重要</span>&nbsp;网站管理员密码过于简单，请不要使用较短的纯数字或自己的QQ号当做密码</li>";
		} else {
			if ($conf["admin_user"] === $conf["admin_pwd"]) {
				$result[] = "<li class=\"list-group-item\"><span class=\"btn-sm btn-danger\">重要</span>&nbsp;网站管理员用户名与密码相同，极易被黑客破解，请及时修改密码</li>";
			}
		}
	}
	if (strlen($dbconfig["pwd"]) < 5 || is_numeric($dbconfig["pwd"]) && strlen($dbconfig["pwd"]) <= 10 || $dbconfig["pwd"] === $conf["kfqq"]) {
		$result[] = "<li class=\"list-group-item\"><span class=\"btn-sm btn-danger\">重要</span>&nbsp;当前主机的数据库密码过于简单，请不要使用较短的纯数字或自己的QQ号当做数据库密码</li>";
	} else {
		if ($dbconfig["pwd"] === $dbconfig["user"]) {
			$result[] = "<li class=\"list-group-item\"><span class=\"btn-sm btn-danger\">重要</span>&nbsp;当前主机的数据库用户名与密码相同，极易被黑客破解，请及时修改数据库密码</li>";
		}
	}
	$suffix = glob(ROOT . "*.zip");
	$suffix2 = glob(ROOT . "*.7z");
	$suffix3 = glob(ROOT . "*.rar");
	if ($suffix && count($suffix) > 0 || $suffix2 && count($suffix2) > 0 || $suffix3 && count($suffix3) > 0) {
		$result[] = "<li class=\"list-group-item\"><span class=\"btn-sm btn-warning\">提示</span>&nbsp;网站根目录存在压缩包文件，可能会被人恶意获取并泄露数据库密码，请及时删除</a></li>";
	}
	return $result;
}
function article_url($article_id = '0', $link = '')
{
	global $conf;
	if ($conf["article_rewrite"] == 1) {
		if ($article_id >= 1) {
			$url = './article-' . $article_id . '.html';
		} else {
			if ($article_id == '0') {
				$url = './article.html?' . $link;
			} else {
				$url = './articlelist.html';
			}
		}
	} else {
		if ($article_id >= 1) {
			$url = './mod=article&id=' . $article_id;
		} else {
			if ($article_id == '0') {
				$url = './mod=article&' . $link;
			} else {
				$url = './mod=articlelist';
			}
		}
	}
	return $url;
}
function send_wechat($sub, $msg)
{
	global $conf;
	if ($conf['wechat_api'] == 1) {
		$url = 'http://wxpusher.zjiecode.com/api/send/message';
		$param = array();
		$param['appToken'] = $conf['wechat_apptoken'];
		$param['content'] = $msg;
		$param['uid'] = $conf['wechat_appuid'];
		$post = http_build_query($param);
		$data = get_curl($url, $post);
		$json = json_decode($data, true);
		if ($json['data'][0]['code'] == 1000) {
			return true;
		} else {
			return $json['data'][0]['status'];
		}
	} else {
		$url = 'https://sctapi.ftqq.com/' . $conf['wechat_sckey'] . '.send';
		$param = array();
		$param['title'] = $sub;
		$param['desp'] = $msg;
		$post = http_build_query($param);
		$data = get_curl($url, $post);
		$json = json_decode($data, true);
		if ($json['code'] == 0) {
			return true;
		} else {
			return $json['info'];
		}
	}
}