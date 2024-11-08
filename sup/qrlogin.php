<?php


error_reporting(0);
session_start();
header("Content-type: application/json");
class qq_qrlogin
{
	public function getqrpic()
	{
		$_var_0 = "https://ssl.ptlogin2.qq.com/ptqrshow?appid=716027609&e=2&l=M&s=4&d=72&v=4&t=0.5409099" . time() . "&daid=383&pt_3rd_aid=101487368";
		$_var_1 = $this->get_curl($_var_0, 0, "https://xui.ptlogin2.qq.com/cgi-bin/xlogin?appid=716027609&daid=383&style=33&theme=2&login_text=%E6%8E%88%E6%9D%83%E5%B9%B6%E7%99%BB%E5%BD%95&hide_title_bar=1&hide_border=1&target=self&s_url=https%3A%2F%2Fgraph.qq.com%2Foauth2.0%2Flogin_jump&pt_3rd_aid=101487368&pt_feedback_link=https%3A%2F%2Fsupport.qq.com%2Fproducts%2F77942%3FcustomInfo%3Dwww.qq.com.appid101487368", 0, 1, 0, 0, 1);
		preg_match("/qrsig=(.*?);/", $_var_1["header"], $_var_2);
		$_var_3 = $_var_2[1];
		if ($_var_3) {
			return array("saveOK" => 0, "qrsig" => $_var_3, "data" => base64_encode($_var_1["body"]));
		} else {
			return array("saveOK" => 1, "msg" => "二维码获取失败");
		}
	}
	public function qrlogin($qrsig)
	{
		if (empty($qrsig)) {
			return array("saveOK" => -1, "msg" => "qrsig不能为空");
		}
		$_var_4 = "";
		$_var_5 = "https://ssl.ptlogin2.qq.com/ptqrlogin?u1=https%3A%2F%2Fgraph.qq.com%2Foauth2.0%2Flogin_jump&ptqrtoken=" . $this->getqrtoken($qrsig) . "&ptredirect=0&h=1&t=1&g=1&from_ui=1&ptlang=2052&action=0-0-" . time() . "0000&js_ver=21020514&js_type=1&login_sig=" . $_var_4 . "&pt_uistyle=40&aid=716027609&daid=383&pt_3rd_aid=101487368&";
		$_var_6 = $this->get_curl($_var_5, 0, $_var_5, "qrsig=" . $qrsig . "; ", 1);
		if (preg_match("/ptuiCB\\('(.*?)'\\)/", $_var_6, $_var_7)) {
			$_var_8 = explode("','", str_replace("', '", "','", $_var_7[1]));
			if ($_var_8[0] == 0) {
				preg_match("/uin=(\\d+)&/", $_var_6, $_var_9);
				$_var_9 = $_var_9[1];
				preg_match("/superkey=(.*?);/", $_var_6, $_var_10);
				$_var_10 = $_var_10[1];
				if ($_var_9 && $_var_10) {
					$_SESSION["findpwd_qq"] = $_var_9;
					return array("saveOK" => 0, "uin" => $_var_9, "nickname" => $_var_8[5]);
				} else {
					return array("saveOK" => 4, "msg" => "QQ验证未通过！");
				}
			} elseif ($_var_8[0] == 65) {
				return array("saveOK" => 1, "msg" => "二维码已失效。");
			} elseif ($_var_8[0] == 66) {
				return array("saveOK" => 2, "msg" => "二维码未失效。");
			} elseif ($_var_8[0] == 67) {
				return array("saveOK" => 3, "msg" => "正在验证二维码。");
			} else {
				return array("saveOK" => 6, "msg" => $_var_8[4]);
			}
		} else {
			return array("saveOK" => 6, "msg" => $_var_6);
		}
	}
	private function getqrtoken($qrsig)
	{
		$_var_11 = strlen($qrsig);
		$_var_12 = 0;
		for ($_var_13 = 0; $_var_13 < $_var_11; $_var_13++) {
			$_var_12 += ($_var_12 << 5 & 2147483647) + ord($qrsig[$_var_13]) & 2147483647;
			$_var_12 &= 2147483647;
		}
		return $_var_12 & 2147483647;
	}
	private function get_curl($url, $post = 0, $referer = 0, $cookie = 0, $header = 0, $ua = 0, $nobaody = 0, $split = 0)
	{
		$_var_14 = curl_init();
		curl_setopt($_var_14, CURLOPT_URL, $url);
		curl_setopt($_var_14, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($_var_14, CURLOPT_SSL_VERIFYHOST, false);
		$_var_15[] = "Accept: application/json";
		$_var_15[] = "Accept-Encoding: gzip,deflate,sdch";
		$_var_15[] = "Accept-Language: zh-CN,zh;q=0.8";
		$_var_15[] = "Connection: close";
		curl_setopt($_var_14, CURLOPT_HTTPHEADER, $_var_15);
		if ($post) {
			curl_setopt($_var_14, CURLOPT_POST, 1);
			curl_setopt($_var_14, CURLOPT_POSTFIELDS, $post);
		}
		if ($header) {
			curl_setopt($_var_14, CURLOPT_HEADER, TRUE);
		}
		if ($cookie) {
			curl_setopt($_var_14, CURLOPT_COOKIE, $cookie);
		}
		if ($referer) {
			curl_setopt($_var_14, CURLOPT_REFERER, $referer);
		}
		if ($ua) {
			curl_setopt($_var_14, CURLOPT_USERAGENT, $ua);
		} else {
			curl_setopt($_var_14, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36");
		}
		if ($nobaody) {
			curl_setopt($_var_14, CURLOPT_NOBODY, 1);
		}
		curl_setopt($_var_14, CURLOPT_ENCODING, "gzip");
		curl_setopt($_var_14, CURLOPT_RETURNTRANSFER, 1);
		$_var_16 = curl_exec($_var_14);
		if ($split) {
			$_var_17 = curl_getinfo($_var_14, CURLINFO_HEADER_SIZE);
			$header = substr($_var_16, 0, $_var_17);
			$_var_18 = substr($_var_16, $_var_17);
			$_var_16 = array();
			$_var_16["header"] = $header;
			$_var_16["body"] = $_var_18;
		}
		curl_close($_var_14);
		return $_var_16;
	}
}
if (strpos($_SERVER["HTTP_REFERER"], $_SERVER["HTTP_HOST"]) === false) {
	exit("{\"saveOK\":-1}");
}
$login = new qq_qrlogin();
if ($_GET["do"] == "qrlogin") {
	$array = $login->qrlogin($_GET["qrsig"]);
}
if ($_GET["do"] == "getqrpic") {
	$array = $login->getqrpic();
}
echo json_encode($array);