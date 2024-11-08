<?php


function third_call($type, $shequ, $cztype, $data = [])
{
	$third = '\\plugins\\third_' . $type;
	$third = new $third($shequ);
	$getInfo = \lib\Plugin::getConfig('third_' . $type);
	if ($cztype == "goods_list") {
		return $third->goods_list();
	} elseif ($cztype == "goods_info") {
		return $third->goods_info($data[0]);
	} elseif ($cztype == "getKyxCategory") {
		return $third->getKyxCategory();
	} elseif ($cztype == "getKyxProductList") {
		return $third->getKyxProductList($data[0]);
	} elseif ($cztype == "goods_list_by_cid") {
		return $third->goods_list_by_cid($data[0]);
	} elseif ($cztype == "query_order") {
		return $third->query_order($data[0], $data[1], $data[2]);
	} elseif ($cztype == "pricejk" && $getInfo['pricejk'] >= 1) {
		return $third->pricejk($data[0], $data[1]);
	} elseif ($cztype == "pricejk_one" && $getInfo['pricejk'] == 2) {
		return $third->pricejk_one($data[0]);
	} elseif ($cztype == "pre_check") {
		return $third->pre_check($data[0], $data[1]);
	} elseif ($cztype == "notify") {
		return $third->notify($data[0]);
	} elseif ($cztype == "do_goods") {
		return $third->do_goods($data[0], $data[1], $data[2], $data[3], $data[4], $data[5], $data[6], $data[7]);
	} elseif ($cztype == "shopeditjs") {
		return $third->shopeditjs($data[0]);
	} elseif ($cztype == "class_list" && $type == "daishua") {
		return $third->class_list();
	} elseif ($cztype == "goods_list_by_cid" && $type == "daishua") {
		return $third->goods_list_by_cid($data[0]);
	} elseif ($cztype == "batch_goods_list") {
		return $third->batch_goods_list();
	} elseif ($cztype == "batch_goods_info") {
		if ($type == "daishua" || $type == "jiuwu" || $type == "mengchuang" || $type == "zhike" || $type == "skysq" || $type == "yunshanggou") {
			return $third->goods_info($data[0]);
		} else {
			return $third->batch_goods_info($data[0]);
		}
	} else {
		return "操作类型错误，请重试！！！";
	}
}
function getFakaInput()
{
	global $conf;
	if ($conf["faka_input"] == 1) {
		return "手机号码";
	}
	if ($conf["faka_input"] == 2) {
		return "你的ＱＱ";
	}
	if ($conf["faka_input"] == 3) {
		return "";
	}
	if ($conf["faka_input"] == 4) {
		return "取卡密码";
	}
	if ($conf["faka_input"] == 5) {
		return $conf["faka_inputname"];
	}
	return "你的邮箱";
}
function sitetask_type($type)
{
	if ($type == 1) {
		return "余额充值";
	}
	if ($type == 2) {
		return "订单数量";
	}
	if ($type == 3) {
		return "销售金额";
	}
	if ($type == 4) {
		return "邀新开户";
	}
	if ($type == 5) {
		return "连续签到";
	}
	return "商品推广";
}
function processOrder($srow, $is_fenzhan = true)
{
	global $islogin2;
	global $DB;
	global $date;
	global $conf;
	$input = explode("|", $srow['input']);
	if ($srow['tid'] == -1) {
		$zid = intval($srow['input']);
		changeUserMoney($zid, $srow['money'], true, "充值", "你在线充值了" . $srow['money'] . "元余额");
		if ($conf['fenzhan_gift']) {
			$fenzhan_gift = explode('|', $conf['fenzhan_gift']);
			$fenzhan_gift_arr = array();
			foreach ($fenzhan_gift as $row) {
				$arr = explode(':', $row);
				$fenzhan_gift_arr[$arr[0]] = $arr[1];
			}
			krsort($fenzhan_gift_arr);
			foreach ($fenzhan_gift_arr as $key => $value) {
				if ($srow['money'] >= $key) {
					$money = round($value, 2);
				}
			}
			if ($money < $srow['money'] && $money > 0) {
				changeUserMoney($zid, $money, true, "返利", "你在线充值了" . $srow['money'] . "元余额，本次返利" . $money . "元已到账，感谢充值！");
			}
		}
		return true;
	}
	if ($srow['tid'] == -4) {
		$sid = intval($srow['input']);
		changeSupMoney($sid, $srow['money'], true, "充值", "你在线充值了" . $srow['money'] . "元余额");
		return true;
	}
	if ($srow['tid'] == -2) {
		$type = addslashes($input[0]);
		if ($type == "update") {
			$zid = intval($input[1]);
			$kind = intval($input[2]);
			$domain = addslashes($input[3]);
			$sitename = addslashes($input[4]);
			$endtime = addslashes($input[5]);
			$upzid = intval($srow['zid']);
			$fenzhan_free = $conf['fenzhan_free'] && $srow['money'] > $conf['fenzhan_free'] ? $conf['fenzhan_free'] : 0;
			$title = addslashes($conf['title']);
			$keywords = addslashes($conf['keywords']);
			$description = addslashes($conf['description']);
			if ($conf['fenzhan_html'] == 1) {
				$anounce = addslashes($conf['anounce']);
				$alert = addslashes($conf['alert']);
			}
			$sql = "UPDATE `pre_site` SET `power`=:power,`domain`=:domain,`sitename`=:sitename,`title`=:title,`keywords`=:keywords,`description`=:description,`anounce`=:anounce,`alert`=:alert,`endtime`=:endtime WHERE `zid`=:zid";
			$data = array(':power' => $kind, ':domain' => $domain, ':sitename' => $sitename, ':title' => $title, ':keywords' => $keywords, ':description' => $description, ':anounce' => $anounce, ':alert' => $alert, ':endtime' => $endtime, ':zid' => $zid);
			$DB->exec($sql, $data);
		} else {
			$kind = intval($input[1]);
			$domain = addslashes($input[2]);
			$user = addslashes($input[3]);
			$pwd = addslashes($input[4]);
			$sitename = addslashes($input[5]);
			$qq = addslashes($input[6]);
			$endtime = addslashes($input[7]);
			$upzid = intval($srow['zid']);
			$fenzhan_free = $conf['fenzhan_free'] && $srow['money'] > $conf['fenzhan_free'] ? $conf['fenzhan_free'] : 0;
			$keywords = addslashes($conf['keywords']);
			$description = addslashes($conf['description']);
			if ($conf['fenzhan_html'] == 1) {
				$anounce = addslashes($conf['anounce']);
				$alert = addslashes($conf['alert']);
			}
			$sql = "INSERT INTO `pre_site` (`upzid`,`power`,`domain`,`domain2`,`user`,`pwd`,`rmb`,`qq`,`sitename`,`title`,`keywords`,`description`,`anounce`,`alert`,`kfqq`,`addtime`,`endtime`,`status`) VALUES (:upzid, :power, :domain, NULL, :user, :pwd , :rmb, :qq, :sitename, :title, :keywords, :description, :anounce, :alert, :kfqq, NOW(), :endtime, 1)";
			$data = array(':upzid' => $upzid, ':power' => $kind, ':domain' => $domain, ':user' => $user, ':pwd' => $pwd,  ':rmb' => $fenzhan_free, ':qq' => $qq, ':sitename' => $sitename, ':title' => $conf['title'], ':keywords' => $keywords, ':description' => $description, ':anounce' => $anounce, ':alert' => $alert, ':kfqq' => $qq, ':endtime' => $endtime);
			$DB->exec($sql, $data);
			$zid = $DB->lastInsertId();
			if (strlen($srow['userid']) == 32) {
				$DB->exec("update `pre_orders` set `userid`='" . $zid . "' where `userid`='" . $srow['userid'] . "'");
			}
		}
		if ($fenzhan_free > 0) {
			addPointRecord($zid, $fenzhan_free, "赠送", "你首次开通分站获赠" . $fenzhan_free . "元余额");
		}
		if ($srow['zid'] > 1) {
			$siterow = $DB->getColumn("SELECT power FROM pre_site WHERE zid='" . $srow['zid'] . "' limit 1");
			if ($siterow == 2 && $kind == 1) {
				$money = round($srow['money'] - $fenzhan_free, 2);
				changeUserMoney($srow['zid'], $money, true, "提成", "你网站的用户开通分站获得" . $money . "元提成");
			} else {
				if ($kind == 1 && $conf['fenzhan_cost'] > 0 && $srow['money'] > $conf['fenzhan_cost']) {
					$money = round($srow['money'] - $conf['fenzhan_cost'], 2);
					changeUserMoney($srow['zid'], $money, true, "提成", "你网站的用户开通分站获得" . $money . "元提成");
				} else {
					if ($kind == 2 && $conf['fenzhan_cost2'] > 0 && $srow['money'] > $conf['fenzhan_cost2']) {
						$money = round($srow['money'] - $conf['fenzhan_cost2'], 2);
						changeUserMoney($srow['zid'], $money, true, "提成", "你网站的用户开通分站获得" . $money . "元提成");
					}
				}
			}
		}
		return true;
	}
	if ($srow['tid'] == -3) {
		$cart_num = count($input);
		for ($i = 0; $i < $cart_num; $i++) {
			$cart_id = $input[$i];
			if (intval($cart_id) < 1) {
				continue;
			}
			$cart_item = $DB->getRow("SELECT * FROM `pre_cart` WHERE `id`='" . $cart_id . "' LIMIT 1");
			if ($cart_item && $cart_item['input']) {
				if ($cart_id > 0 && $cart_num > 0) {
					$cart_item = $DB->getRow("SELECT * FROM `pre_cart` WHERE `id`='" . $cart_id . "' LIMIT 1");
					$tools = $DB->getRow("SELECT * FROM `pre_tools` WHERE `tid`='" . $cart_item['tid'] . "' LIMIT 1");
					$input = explode("|", $cart_item['input']);
					$cost = $tools['price'] * $cart_item['num'];
					$DB->exec("INSERT INTO `pre_orders` (`tid`,`zid`,`input`,`input2`,`input3`,`input4`,`input5`,`value`,`userid`,`addtime`,`tradeno`,`money`,`cost`,`status`,`djzt`) VALUES ('" . $cart_item['tid'] . "','" . $srow['zid'] . "','" . addslashes($input[0]) . "','" . addslashes($input[1]) . "','" . addslashes($input[2]) . "','" . addslashes($input[3]) . "','" . addslashes($input[4]) . "','" . $cart_item['num'] . "','" . $srow['userid'] . "','" . $date . "','cart" . $srow['trade_no'] . "','" . $cart_item['money'] . "','" . $cost . "','0','" . ($tools['is_curl'] == 2 ? 2 : 0) . "')");
					$orderid = $DB->lastInsertId();
					if (do_goods($orderid)) {
						$DB->exec("UPDATE `pre_cart` SET `endtime`='" . $date . "',`status`='1' WHERE `id`='" . $cart_id . "'");
						$invitelog_row = $DB->getRow("SELECT * FROM `pre_invitelog` WHERE `id` = '" . $srow['inviteid'] . "' LIMIT 1");
						$invite_row = $DB->getRow("SELECT * FROM `pre_invite` WHERE `id` = '" . $invitelog_row['iid'] . "' LIMIT 1");
						$inviteshop_row = $DB->getRow("SELECT * FROM `pre_inviteshop` WHERE `id` = '" . $invite_row['nid'] . "' LIMIT 1");
						if ($srow['inviteid'] > 0 && $conf['invite_tid'] && ($inviteshop_row['value'] > 0 && $srow['money'] >= $inviteshop_row['value'])) {
							if ($invitelog_row && $invitelog_row['status'] == 0 && $invite_row && $invite_row['status'] == 0 && $inviteshop_row && $invite_row['active'] == 1) {
								$qq = $DB->getColumn("SELECT qq FROM `pre_invite` WHERE `id` = '" . $invitelog_row['nid'] . "' LIMIT 1");
								$DB->exec("INSERT INTO `pre_orders` (`tid`,`zid`,`input`,`value`,`status`,`djzt`,`money`,`cost`,`tradeno`,`addtime`,`endtime`) VALUES (" . $inviteshop_row['tid'] . ",'" . $srow['zid'] . "','" . $qq . "',1,0,2,'0','0','invite" . $srow['trade_no'] . "','" . $date . "','" . $date . "')");
								$invite_orderid = $DB->lastInsertId();
								do_goods($invite_orderid);
								if ($inviteshop_row['times'] == 0) {
									$DB->exec("UPDATE `pre_invite` SET `status` = '1' WHERE `id` = '" . $invitelog_row['id'] . "'");
								}
								$DB->exec("UPDATE `pre_invitelog` SET `orderid` = '" . $invite_orderid . "',`status` = 1 WHERE `id` = '" . $srow['inviteid'] . "'");
							}
						}
					}
				}
			}
		}
		return true;
	}
	$tools = $DB->getRow("SELECT * FROM `pre_tools` WHERE `tid`='" . $srow['tid'] . "' LIMIT 1");
	$status = 0;
	if ($tools['prid'] == 0) {
		$cost = $tools['cost2'] * $srow['num'];
	} else {
		$cost = $tools['price'] * $srow['num'];
	}
	$sql2="INSERT INTO `pre_orders` (`tid`,`zid`,`input`,`input2`,`input3`,`input4`,`input5`,`value`,`userid`,`addtime`,`tradeno`,`money`,`cost`,`status`,`djzt`) VALUES (:tid,:zid,:input,:input2,:input3,:input4,:input5,:value,:userid,:addtime,:tradeno,:money,:cost,:status,:djzt)";
	
	$data2 = array(':tid' => $srow['tid'], ':zid' => $srow['zid'], ':input' => addslashes($input[0]), ':input2' => addslashes($input[1]), ':input3' => addslashes($input[2]),  ':input4' => addslashes($input[3]), ':input5' => addslashes($input[4]), ':value' => $srow['num'], ':userid' => $srow['userid'], ':addtime' => $date, ':tradeno' => $srow['trade_no'], ':money' => $srow['money'], ':cost' => $cost, ':status' => $status, ':djzt' => ($tools['is_curl'] == 2 ? 2 : 0));
	$DB->exec($sql2, $data2);
	$orderid = $DB->lastInsertId();
	if (!$orderid) {
		return false;
	}
	if ($srow['zid'] > 1 && $srow['money'] > 0 && $is_fenzhan == true) {
		$price_obj = new \lib\Price($srow['zid']);
		$price_obj->setToolInfo($srow['tid'], $tools);
		$price_obj->setToolProfit($srow['tid'], $srow['num'], $tools['name'], $srow['money'], $orderid, $srow['userid']);
	}
	$num = $tools['value'] * $srow['num'];
	if ($num <= 0) {
		$num = 1;
	}
	if ($tools['is_curl'] == 1) {
		$result = do_curl($tools['curl'], $input, $num, $tools['name'], $tools['money'], $orderid, $tools['goods_param']);
		if ($result = json_decode($result, true)) {
			if ($result['code'] == 0) {
				$status = 1;
			} else {
				$status = 0;
			}
		} else {
			$status = 1;
		}
		$param = "url:" . $tools['curl'] . " data:" . http_build_query($input);
		log_result("自动访问URL", $param, $result, 0);
	} elseif ($tools['is_curl'] == 2 && $srow['blockdj'] == 0) {
		$inputsname = $tools['inputs'] ? $tools['input'] . "|" . $tools['inputs'] : $tools['input'];
		$shequ = $DB->getRow("SELECT * FROM `pre_shequ` WHERE `id`='" . $tools['shequ'] . "' limit 1");
		if ($shequ && $shequ['type'] && $shequ['username'] && $shequ['password']) {
			$result = third_call($shequ['type'], $shequ, 'do_goods', array($tools['goods_id'], $tools['goods_type'], $tools['goods_param'], $num, $input, $srow['money'], $srow['trade_no'], $inputsname));
			$param = $shequ['type'] . ":" . $tools['shequ'] . " goods_id:" . $tools['goods_id'] . " num:" . $num . " data:" . http_build_query($input);
			if ($result['faka'] == true) {
				$kmdata = '';
				foreach ($result['kmdata'] as $km_arr) {
					$DB->query("INSERT INTO `pre_faka` (`tid`,`km`,`pw`,`orderid`,`addtime`,`usetime`) VALUES ('" . $srow['tid'] . "','" . $km_arr['card'] . "','" . $km_arr['pass'] . "','" . $orderid . "',NOW(),NOW())");
					if (!empty($km_arr['pass'])) {
						$kmdata = $kmdata . ("卡号：" . $km_arr['card'] . " 密码：" . $km_arr['pass'] . "<br/>");
					} else {
						$kmdata = $kmdata . ($km_arr['card'] . "<br/>");
					}
				}
				$DB->exec("UPDATE `pre_orders` SET `status`='1',`djzt`='3',`result`='" . $kmdata . "',`djorder`='" . $result['id'] . "' WHERE `id`='" . $orderid . "'");
				if (!empty($kmdata)) {
					if (is_numeric($input[0]) && strlen($input[0]) <= 10) {
						$to = $input[0] . "@qq.com";
					} else {
						if (strpos($input[0], "@")) {
							$to = $input[0];
						}
					}
					if (checkEmail($to)) {
						$sub = $conf['sitename'] . " 卡密购买提醒";
						$msg = $conf['faka_mail'];
						$msg = str_replace("[kmdata]", $kmdata, $msg);
						$msg = str_replace("[alert]", $tools['desc'], $msg);
						$msg = str_replace("[name]", $tools['name'], $msg);
						$msg = str_replace("[date]", $date, $msg);
						$msg = str_replace("[email]", $to, $msg);
						$msg = str_replace("[domain]", $_SERVER['HTTP_HOST'], $msg);
						$msg = str_replace("[sitename]", $conf['sitename'], $msg);
						send_mail($to, $sub, $msg);
					}
				}
			}
			log_result("社区对接", $param, $result, 0);
			if ($result['code'] == 0) {
				if (!strpos($_SERVER['PHPRC'], "phpStudy") && $shequ['status'] == 0) {
					$DB->exec("UPDATE `pre_shequ` SET status=1 WHERE `id`='" . $shequ['id'] . "'");
				}
				$status = $shequ['result'] ?: 1;
			} else {
				if ($conf['message_duijie'] == 1) {
					\lib\MessageSend::orderbuy_fail($tools['name'], $tools['input'], $tools['inputs'], $input, $srow['money'], $num, $srow['type'], 0, $param, $result);
				}
				$status = 0;
			}
		} else {
			$status = 0;
		}
	} elseif ($tools['is_curl'] == 3) {
		\lib\MessageSend::orderbuy($tools['name'], $tools['input'], $tools['inputs'], $input, $srow['money'], $num, $srow['type'], 0);
	} elseif ($tools['is_curl'] == 4) {
		if ($tools['goods_sid'] != 0) {
			$tcmoney = $tools['sup_price'] * $srow['num'];
			changeSupMoney($tools['goods_sid'], $tcmoney, true, '提成', '网站用户下单 ' . $tools['name'] . ' 获得' . $tcmoney . '元');
		}
		$limit = $srow['num'];
		$rs = $DB->query("SELECT * FROM pre_faka WHERE `tid`='" . $srow['tid'] . "' AND orderid=0 LIMIT " . $limit . '');
		$kmdata = '';
		while ($res = $rs->fetch()) {
			if (!empty($res['pw'])) {
				$kmdata = $kmdata . ("卡号：" . $res['km'] . " 密码：" . $res['pw'] . "<br/>");
			} else {
				$kmdata = $kmdata . ($res['km'] . "<br/>");
			}
			$DB->exec("UPDATE `pre_faka` SET `orderid`='" . $orderid . "',`usetime`='" . $date . "' WHERE `kid`='" . $res['kid'] . "'");
		}
		switch ($conf['faka_input']) {
			case 0:
				$to = $input[0];
				break;
			case 2:
				if (is_numeric($input[0]) && strlen($input[0]) <= 10) {
					$to = $input[0] . "@qq.com";
				} else {
					if (strpos($input[0], "@")) {
						$to = $input[0];
					}
				}
				break;
			default:
				break;
		}
		if (!empty($kmdata)) {
			$status = 1;
			$DB->exec("UPDATE `pre_orders` SET `status`='1',`result`='" . $kmdata . "',`djzt`='3' WHERE `id`='" . $orderid . "'");
			$sub = $conf['sitename'] . " 卡密购买提醒";
			$msg = $conf['faka_mail'];
			$msg = str_replace("[kmdata]", $kmdata, $msg);
			$msg = str_replace("[alert]", $tools['alert'], $msg);
			$msg = str_replace("[name]", $tools['name'], $msg);
			$msg = str_replace("[date]", $date, $msg);
			$msg = str_replace("[email]", $to, $msg);
			$msg = str_replace("[domain]", $_SERVER['HTTP_HOST'], $msg);
			$msg = str_replace("[sitename]", $conf['sitename'], $msg);
			if (isset($to)) {
				if (checkEmail($to)) {
					send_mail($to, $sub, $msg);
				}
			}
		} else {
			$status = 0;
			$DB->exec("UPDATE `pre_orders` SET `status`='0',`djzt`='4' WHERE `id`='" . $orderid . "'");
		}
	} elseif ($tools['is_curl'] == 5) {
		$DB->exec("UPDATE `pre_orders` SET `status`='1',`djzt`='3',`result`='" . $tools['showcontent'] . "' WHERE `id`='" . $orderid . "'");
	}
	if ($tools['is_curl'] != 3) {
		\lib\MessageSend::orderbuy($tools['name'], $tools['input'], $tools['inputs'], $input, $srow['money'], $num, $srow['type'], $status);
	}
	if ($status > 0 && $tools['is_curl'] != 4) {
		if (!$result['faka'] && $tools['is_curl'] == 2) {
			$DB->exec("update `pre_orders` set `status`='" . $status . "',`djzt`='1',`djorder`='" . $result['id'] . "' where `id`='" . $orderid . "'");
		} else {
			if ($tools['is_curl'] == 0) {
				$DB->exec("update `pre_orders` set `djzt`='0' where `id`='" . $orderid . "'");
			}
		}
	}
	$invitelog_row = $DB->getRow("SELECT * FROM `pre_invitelog` WHERE `id` = '" . $srow['inviteid'] . "' LIMIT 1");
	$invite_row = $DB->getRow("SELECT * FROM `pre_invite` WHERE `id` = '" . $invitelog_row['iid'] . "' LIMIT 1");
	$inviteshop_row = $DB->getRow("SELECT * FROM `pre_inviteshop` WHERE `id` = '" . $invite_row['nid'] . "' LIMIT 1");
	if ($srow['inviteid'] > 0 && $conf['invite_tid'] && ($inviteshop_row['value'] > 0 && $srow['money'] >= $inviteshop_row['value'])) {
		if ($invitelog_row && $invitelog_row['status'] == 0 && $invite_row && $invite_row['status'] == 0 && $inviteshop_row && $invite_row['active'] == 1) {
			$qq = $DB->getColumn("SELECT qq FROM `pre_invite` WHERE `id` = '" . $invitelog_row['nid'] . "' LIMIT 1");
			$DB->exec("INSERT INTO `pre_orders` (`tid`,`zid`,`input`,`value`,`status`,`djzt`,`money`,`cost`,`tradeno`,`addtime`,`endtime`) VALUES (" . $inviteshop_row['tid'] . ",'" . $srow['zid'] . "','" . $qq . "',1,0,2,'0','0','invite" . $srow['trade_no'] . "','" . $date . "','" . $date . "')");
			$invite_orderid = $DB->lastInsertId();
			do_goods($invite_orderid);
			if ($inviteshop_row['times'] == 0) {
				$DB->exec("UPDATE `pre_invite` SET `status` = '1' WHERE `id` = '" . $invitelog_row['iid'] . "'");
			}
			$DB->exec("UPDATE `pre_invitelog` SET `orderid` = '" . $invite_orderid . "',`status` = 1 WHERE `id` = '" . $srow['inviteid'] . "'");
		}
	}
	return $orderid;
}
function do_curl($curl, $input, $num, $name, $money, $orderid, $param)
{
	$curl = str_replace("[input]", urlencode($input[0]), $curl);
	$curl = str_replace("[input2]", urlencode($input[1]), $curl);
	$curl = str_replace("[input3]", urlencode($input[2]), $curl);
	$curl = str_replace("[input4]", urlencode($input[3]), $curl);
	$curl = str_replace("[input5]", urlencode($input[4]), $curl);
	$curl = str_replace("[num]", $num, $curl);
	$curl = str_replace("[name]", urlencode($name), $curl);
	$curl = str_replace("[money]", $money, $curl);
	$curl = str_replace("[time]", time(), $curl);
	$curl = str_replace("[id]", $orderid, $curl);
	if (!empty($param) && strpos($param, "=")) {
		$param = str_replace("[input]", urlencode($input[0]), $param);
		$param = str_replace("[input2]", urlencode($input[1]), $param);
		$param = str_replace("[input3]", urlencode($input[2]), $param);
		$param = str_replace("[input4]", urlencode($input[3]), $param);
		$param = str_replace("[input5]", urlencode($input[4]), $param);
		$param = str_replace("[num]", $num, $param);
		$param = str_replace("[name]", urlencode($name), $param);
		$param = str_replace("[money]", $money, $param);
		$param = str_replace("[time]", time(), $param);
		$param = str_replace("[id]", $orderid, $param);
		return get_curl($curl, $param);
	}
	return get_curl($curl);
}
function do_goods($orderid, $url = '', $post = '')
{
	global $DB;
	global $date;
	global $conf;
	if (!empty($url)) {
		return get_curl($url, $post);
	}
	$order_row = $DB->getRow("SELECT * FROM `pre_orders` WHERE `id`='" . $orderid . "' LIMIT 1");
	$tools = $DB->getRow("SELECT * FROM `pre_tools` WHERE `tid`='" . $order_row['tid'] . "' LIMIT 1");
	$status = 0;
	$input = array($order_row['input'], $order_row['input2'], $order_row['input3'], $order_row['input4'], $order_row['input5']);
	$num = $tools['value'] * $order_row['value'];
	if ($num <= 0) {
		$num = 1;
	}
	if ($tools['is_curl'] == 1) {
		$result = do_curl($tools['curl'], $input, $num, $tools['name'], $tools['money'], $orderid, $tools['goods_param']);
		if ($result = json_decode($result, true)) {
			if ($result['code'] == 0) {
				$status = 1;
				$message = "下单成功！订单号:" . $result['id'];
			} else {
				$status = 0;
				$message = "下单失败，原因未知，请查看日志";
			}
		} else {
			$status = 1;
			$message = "下单成功！";
		}
		$DB->exec("UPDATE `pre_orders` SET `status`='" . $status . "' WHERE `id`='" . $orderid . "'");
		$param = "url:" . $tools['curl'] . " data:" . http_build_query($input);
		log_result("自动访问URL", $param, $result, 0);
	} elseif ($tools['is_curl'] == 2) {
		$inputsname = $tools['inputs'] ? $tools['input'] . "|" . $tools['inputs'] : $tools['input'];
		$shequ = $DB->getRow("select * from pre_shequ where id='" . $tools['shequ'] . "' limit 1");
		if ($shequ && $shequ['type'] && $shequ['username'] && $shequ['password']) {
			$result = third_call($shequ['type'], $shequ, 'do_goods', array($tools['goods_id'], $tools['goods_type'], $tools['goods_param'], $num, $input, $order_row['money'], $order_row['trade_no'], $inputsname));
			$param = $shequ['type'] . ":" . $tools['shequ'] . " goods_id:" . $tools['goods_id'] . " num:" . $num . " data:" . http_build_query($input);
			if ($result['faka'] == true) {
				$kmdata = '';
				foreach ($result['kmdata'] as $km_arr) {
					$DB->query("INSERT INTO `pre_faka` (`tid`,`km`,`pw`,`orderid`,`addtime`,`usetime`) VALUES ('" . $order_row['tid'] . "','" . $km_arr['card'] . "','" . $km_arr['pass'] . "','" . $orderid . "',NOW(),NOW())");
					if (!empty($km_arr['pass'])) {
						$kmdata = $kmdata . ("卡号：" . $km_arr['card'] . " 密码：" . $km_arr['pass'] . "<br/>");
					} else {
						$kmdata = $kmdata . ($km_arr['card'] . "<br/>");
					}
				}
				$DB->exec("UPDATE `pre_orders` SET `status`='1',`djzt`='3',`djorder`='" . $result['id'] . "' WHERE `id`='" . $orderid . "'");
				if (!empty($kmdata)) {
					if (is_numeric($input[0]) && strlen($input[0]) <= 10) {
						$to = $input[0] . "@qq.com";
					} else {
						if (strpos($input[0], "@")) {
							$to = $input[0];
						}
					}
					if (checkEmail($to)) {
						$sub = $conf['sitename'] . " 卡密购买提醒";
						$msg = $conf['faka_mail'];
						$msg = str_replace("[kmdata]", $kmdata, $msg);
						$msg = str_replace("[alert]", $tools['desc'], $msg);
						$msg = str_replace("[name]", $tools['name'], $msg);
						$msg = str_replace("[date]", $date, $msg);
						$msg = str_replace("[email]", $to, $msg);
						$msg = str_replace("[domain]", $_SERVER['HTTP_HOST'], $msg);
						$msg = str_replace("[sitename]", $conf['sitename'], $msg);
						send_mail($to, $sub, $msg);
					}
				}
			}
			if ($result['code'] == 0) {
				$status = $shequ['result'] ?: 1;
				if ($status > 0 && !$result['faka']) {
					$DB->exec("UPDATE `pre_orders` SET `status`='" . $status . "',`djzt`='1',`djorder`='" . $result['id'] . "',result=NULL WHERE `id`='" . $orderid . "'");
				}
				$message = "下单成功!订单号:" . $result['id'];
			} else {
				if ($result['message']) {
					$message = "下单失败：" . $result['message'];
				} else {
					$message = "下单失败，原因未知，请查看日志";
				}
			}
			log_result("社区对接", $param, $result, 0);
		} else {
			$message = "未配置好网站对接信息";
		}
	} elseif ($tools['is_curl'] == 3) {
		\lib\MessageSend::orderbuy($tools['name'], $tools['input'], $tools['inputs'], $input, $tools['money'], $num, $tools['type'], 0);
	} elseif ($tools['is_curl'] == 4) {
		$limit = $order_row['value'];
		$rs = $DB->query("SELECT * FROM pre_faka WHERE tid='" . $order_row['tid'] . "' AND orderid=0 LIMIT " . $limit . '');
		$kmdata = '';
		while ($res = $rs->fetch()) {
			if (!empty($res['pw'])) {
				$kmdata = $kmdata . ("卡号：" . $res['km'] . " 密码：" . $res['pw'] . "<br/>");
			} else {
				$kmdata = $kmdata . ($res['km'] . "<br/>");
			}
			$DB->exec("UPDATE `pre_faka` SET `orderid`='" . $orderid . "',`usetime`='" . $date . "' WHERE `kid`='" . $res['kid'] . "'");
		}
		if (!empty($kmdata)) {
			$DB->exec("UPDATE `pre_orders` SET `status`='1',`djzt`='3' WHERE `id`='" . $orderid . "'");
			if (is_numeric($input[0]) && strlen($input[0]) <= 10) {
				$to = $input[0] . "@qq.com";
			} else {
				if (strpos($input[0], "@")) {
					$to = $input[0];
				}
			}
			$sub = $conf['sitename'] . " 卡密购买提醒";
			$msg = $conf['faka_mail'];
			$msg = str_replace("[kmdata]", $kmdata, $msg);
			$msg = str_replace("[alert]", $tools['desc'], $msg);
			$msg = str_replace("[name]", $tools['name'], $msg);
			$msg = str_replace("[date]", $date, $msg);
			$msg = str_replace("[email]", $to, $msg);
			$msg = str_replace("[domain]", $_SERVER['HTTP_HOST'], $msg);
			$msg = str_replace("[sitename]", $conf['sitename'], $msg);
			if (checkEmail($to)) {
				send_mail($to, $sub, $msg);
			}
			$message = "发卡成功，商品发货成功！";
		} else {
			$DB->exec("UPDATTE `pre_orders` SET `status`='0',`djzt`='4' WHERE `id`='" . $orderid . "'");
			$message = "卡密库存不足，发卡失败！";
		}
	} elseif ($tools['is_curl'] == 5) {
		$DB->exec("UPDATE `pre_orders` SET `status`='1',`djzt`='3',`result`='" . $tools['showcontent'] . "' WHERE `id`='" . $orderid . "'");
		$message = "下单成功，商品发货成功！";
	}
	$message = str_replace(array("\r\n", "\r", "\n"), '', $message);
	$message = htmlspecialchars($message);
	return $message;
}
function addPointRecord($zid, $money = 0, $action = "提成", $bz = NULL)
{
	global $DB;
	$action = addslashes($action);
	$bz = addslashes($bz);
	$DB->exec("INSERT INTO `pre_points` (`zid`, `action`, `point`, `bz`, `addtime`) VALUES (:zid, :action, :point, :bz, NOW())", array(':zid' => $zid, ':action' => $action, ':point' => $money, ':bz' => $bz));
}
function addSupPointRecord($sid, $money = 0, $action = "提成", $bz = NULL)
{
	global $DB;
	$action = addslashes($action);
	$bz = addslashes($bz);
	$DB->exec("INSERT INTO `pre_suppoints` (`sid`, `action`, `point`, `bz`, `addtime`) VALUES (:sid, :action, :point, :bz, NOW())", array(':sid' => $sid, ':action' => $action, ':point' => $money, ':bz' => $bz));
}
function rollbackPoint($orderid)
{
	global $DB;
	$rs = $DB->query("SELECT id,zid,point FROM pre_points WHERE orderid='" . $orderid . "' AND action='提成' LIMIT 2");
	while ($res = $rs->fetch()) {
		$DB->exec("UPDATE pre_site SET rmb=rmb-" . $res['point'] . " WHERE zid='" . $res['zid'] . "'");
		$DB->exec("DELETE FROM pre_points WHERE id='" . $res['id'] . "'");
	}
}
function log_result($action, $param, $array, $status = 0)
{
	global $DB;
	if (array_key_exists("code", $array) && $array['code'] == 0) {
		$result = "下单成功!订单号:" . $array['id'];
	} else {
		$result = $array['message'];
		if (strlen($result) > 200) {
			$result = substr($result, 0, 200);
		}
		$result = htmlspecialchars($result);
	}
	$action = addslashes($action);
	$param = addslashes($param);
	$result = addslashes($result);
	$DB->exec("INSERT INTO `pre_logs` (`action`, `param`, `result`, `addtime`, `status`) VALUES ('" . $action . "', '" . $param . "', '" . $result . "', NOW(), '" . $status . "')");
}
function isWeiXin()
{
	$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
	if (strpos($useragent, 'MicroMessenger') === true && strpos($useragent, 'Windows Phone') === true) {
		return 1;
	} else {
		return 0;
	}
}
function micropay_api()
{
	global $conf;
	if ($conf['micropayapi'] == 1) {
		return "http://api.qqjqr.net/";
	} elseif ($conf['micropayapi'] == 2) {
		return "http://api.wyfpay.com/";
	} else {
		return "http://api.fcypay.cn/";
	}
}
function get_pay_api($type)
{
	global $conf;
	if ($type == "alipay" && $conf['alipay_api'] == 2 || $type == "qqpay" && $conf['qqpay_api'] == 2 || $type == "wxpay" && $conf['wxpay_api'] == 2) {
		$url = $conf['epay_url'];
		$pid = $conf['epay_pid'];
		$key = $conf['epay_key'];
		$channel = "epay1";
	} else {
		if ($type == "qqpay" && $conf['qqpay_api'] == 8 || $type == "wxpay" && $conf['wxpay_api'] == 8) {
			$url = $conf['epay_url2'];
			$pid = $conf['epay_pid2'];
			$key = $conf['epay_key2'];
			$channel = "epay2";
		} else {
			if ($type == "wxpay" && $conf['wxpay_api'] == 9) {
				$url = $conf['epay_url3'];
				$pid = $conf['epay_pid3'];
				$key = $conf['epay_key3'];
				$channel = "epay3";
			} else {
				$url = $conf['epay_url'];
				$pid = $conf['epay_pid'];
				$key = $conf['epay_key'];
				$channel = "epay1";
			}
		}
	}
	return array("url" => empty($url) ? null : $url, "pid" => empty($pid) ? null : $pid, "key" => empty($key) ? null : $key, "channel" => empty($channel) ? null : $channel);
}
function pay_api()
{
	global $conf;
	return $conf['epay_url'];
}