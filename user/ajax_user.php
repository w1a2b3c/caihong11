<?php
include("../includes/common.php");

$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');
if(!$islogin2)exit('{"code":-1,"msg":"未登录"}');

switch($act){
case 'setpwd':
	if(substr($userrow['user'],0,3)!='qq_')exit('{"code":-1,"msg":"请勿重复提交"}');
	$user = trim(htmlspecialchars(strip_tags(daddslashes($_POST['user']))));
	$pwd = trim(htmlspecialchars(strip_tags(daddslashes($_POST['pwd']))));
	if (!preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$user)) {
		exit('{"code":-1,"msg":"用户名只能为英文、数字与汉字！"}');
	} elseif ($DB->getRow("SELECT zid FROM pre_site WHERE user=:user LIMIT 1", [':user'=>$user])) {
		exit('{"code":-1,"msg":"用户名已存在！"}');
	} elseif (strlen($pwd) < 6) {
		exit('{"code":-1,"msg":"密码不能低于6位"}');
	} elseif ($pwd == $user) {
		exit('{"code":-1,"msg":"用户名和密码不能相同！"}');
	}
	if($DB->exec("UPDATE pre_site SET user=:user,pwd=:pwd WHERE zid=:zid", [':user'=>$user, ':pwd'=>$pwd, ':zid'=>$userrow['zid']])){
		$session=md5($user.$pwd.$password_hash);
		$token=authcode("{$userrow['zid']}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("user_token", $token, time() + 604800, '/');
		exit('{"code":0,"msg":"保存成功"}');
	}else{
		exit('{"code":-1,"msg":"保存失败！'.$DB->error().'"}');
	}
break;
case 'reset_price':
	$cid=isset($_POST['cid'])?intval($_POST['cid']):0;
	$DB->exec("UPDATE pre_site SET price=NULL WHERE zid='{$userrow['zid']}'");
	exit('{"code":0}');
break;
case 'up_price':
	unset($islogin2);
	$price_obj = new \lib\Price($userrow['zid'],$userrow);
	$up=intval($_POST['up']);
	$cid=isset($_POST['cid'])?intval($_POST['cid']):0;
	if($up<=0)exit('{"code":-1,"msg":"输入值不正确"}');
	if($conf['fenzhan_pricelimit']==1 && $up>100)exit('{"code":-1,"msg":"商品售价最高不能超过原售价的2倍"}');
	if($cid){
		$sql=$DB->query("select * from pre_tools where active=1 and cid={$cid}");
		$data=unserialize($userrow['price']);
	}else{
		$sql=$DB->query("select * from pre_tools where active=1");
		$data=array();
	}
	while($row=$sql->fetch()){
		if($row['price']==0){
			continue;
		}
		if(strpos($row['name'],'免费')!==false){
			continue;
		}
		$price_obj->setToolInfo($row['tid'],$row);
		$price = $price_obj->getToolPrice($tid);
		$a=(float)$up/100;
		$data[$row['tid']]['price']=round($price*($a+1),2);
	}
	$array_data=serialize($data);
	$DB->exec("update `pre_site` set `price`='{$array_data}' where zid='{$userrow['zid']}'");
	exit('{"code":0}');
break;
case 'create_url':
	if(!$userrow['domain'])exit('{"code":-1,"msg":"当前分站还未绑定域名"}');
	$scriptpath = str_replace('\\','/',$_SERVER['SCRIPT_NAME']);
	$scriptpath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
	$scriptpath = substr($scriptpath, 0, strrpos($scriptpath, '/'));
	$url = (is_https() ? 'https://' : 'http://').$userrow['domain'].$scriptpath.'/';
	$_result = fanghongdwz($url);
	exit(json_encode($_result));
break;
case 'qiandao':
	if(!$conf['qiandao_reward'])exit('{"code":-1,"msg":"当前站点未开启签到功能"}');
	if(!isset($_SESSION['isqiandao']) || $_SESSION['isqiandao']!=$userrow['zid'])exit('{"code":-1,"msg":"校验失败，请刷新页面重试"}');
	$day = date("Y-m-d");
	$lastday = date("Y-m-d",strtotime("-1 day"));
	
	if ($DB->getRow("SELECT * FROM pre_qiandao WHERE zid='{$userrow['zid']}' AND date='$day' ORDER BY id DESC LIMIT 1")) {
		exit('{"code":-1,"msg":"今天已经签到过了, 明天在来吧！"}');
	}
	if ($conf['qiandao_limitip']==1 && $DB->getRow("SELECT * FROM pre_qiandao WHERE ip='{$clientip}' AND date='$day' ORDER BY id DESC LIMIT 1")) {
		exit('{"code":-1,"msg":"您的IP今天已经签到过了，明天在来吧！"}');
	}
	if ($row = $DB->getRow("SELECT * FROM pre_qiandao WHERE zid='{$userrow['zid']}' AND date='$lastday' ORDER BY id DESC LIMIT 1")) {
		$continue = $row['continue']+1;
	}else{
		$continue = 1;
	}
	if($continue > $conf['qiandao_day']) $continue = $conf['qiandao_day'];
	$reward = $conf['qiandao_reward'];
	if(strpos($reward,'|')){
		$reward = explode('|',$reward);
		$reward = $reward[$userrow['power']];
		if(!$reward)exit('{"code":-1,"msg":"未配置好签到奖励余额初始值"}');
	}
	if($conf['qiandao_mult']>0){
		for($i=1;$i<$continue;$i++){
			$reward *= $conf['qiandao_mult'];
		}
	}
	$reward = round($reward,2);
	$sql="INSERT INTO `pre_qiandao` (`zid`,`qq`,`reward`,`date`,`time`,`continue`,`ip`) VALUES ('".$userrow['zid']."','".$userrow['qq']."','".$reward."','".$day."','".$date."','".$continue."','".$clientip."')";
	if($DB->exec($sql)){
		unset($_SESSION['isqiandao']);
		changeUserMoney($userrow['zid'], $reward, true, '赠送', '您今天签到获得了'.$reward.'元奖励');
		$result = array('code'=>0, 'msg'=>'签到成功，获得'.$reward.'元现金奖励！');
	}else{
		$result = array('code'=>-1, 'msg'=>'签到失败'.$DB->error());
	}
	exit(json_encode($result));
break;
case 'qdcount':
	$day=date("Y-m-d");
	$lastday = date("Y-m-d",strtotime("-1 day"));
	$count1=$DB->getColumn("SELECT count(*) FROM pre_qiandao WHERE date='$day'");
	$count2=$DB->getColumn("SELECT count(*) FROM pre_qiandao WHERE date='$lastday'");
	$count3=$DB->getColumn("SELECT count(*) FROM pre_qiandao");
	$rewardcount=$DB->getColumn("SELECT sum(reward) FROM pre_qiandao WHERE zid='{$userrow['zid']}'");
	$result=array("count1"=>$count1,"count2"=>$count2,"count3"=>$count3,"rewardcount"=>round($rewardcount,2));
	exit(json_encode($result));
break;
case 'msg':
	if($userrow['power']==2){
		$type = '0,2,4';
	}elseif($userrow['power']==1){
		$type = '0,2,3';
	}else{
		$type = '0,1';
	}
	$msgread = trim($userrow['msgread'],',');
	if(empty($msgread))$msgread='0';
	$count=$DB->getColumn("SELECT count(*) FROM pre_message WHERE id NOT IN ($msgread) and type IN ($type)");
	$count2=$DB->getColumn("SELECT count(*) FROM pre_workorder WHERE zid='{$userrow['zid']}' AND status=1");
	$thtime=date("Y-m-d").' 00:00:00';
	$income_today=$DB->getColumn("SELECT sum(point) FROM pre_points WHERE zid='{$userrow['zid']}' AND action='提成' AND addtime>'$thtime'");
	exit('{"code":0,"count":'.$count.',"count2":'.$count2.',"income_today":"'.round($income_today,2).'"}');
break;
case 'msginfo':
	if($userrow['power']==2){
		$type = array(0,2,4);
	}elseif($userrow['power']==1){
		$type = array(0,2,3);
	}else{
		$type = array(0,1);
	}
	$id=intval($_GET['id']);
	$row=$DB->getRow("SELECT * FROM pre_message WHERE id='$id' AND active=1 LIMIT 1");
	if(!$row)
		exit('{"code":-1,"msg":"当前消息不存在！"}');
	if(!in_array($row['type'],$type))
		exit('{"code":-1,"msg":"你没有权限查看此消息内容"}');
	if(!in_array($id,explode(',',$userrow['msgread']))){
		$msgread_n = $userrow['msgread'].$id.',';
		$DB->exec("UPDATE pre_message SET count=count+1 WHERE id='$id'");
		$DB->exec("UPDATE pre_site SET msgread='".$msgread_n."' WHERE zid='{$userrow['zid']}'");
	}
	$result=array("code"=>0,"msg"=>"succ","title"=>$row['title'],"type"=>$row['type'],"content"=>$row['content'],"date"=>$row['addtime']);
	exit(json_encode($result));
break;
case 'msg_read_all':
	if($userrow['power']==2){
		$type = array(0,2,4);
	}elseif($userrow['power']==1){
		$type = array(0,2,3);
	}else{
		$type = array(0,1);
	}
	$type = implode(',', $type);
	$rs=$DB->query("SELECT id FROM pre_message WHERE `type` in ({$type})");
	$id = "";
	foreach ($rs as $key => $value) {
		$id .= $value['id'].',';
	}

	if($id){
		$DB->exec("UPDATE pre_site SET msgread='".$id."' WHERE zid='{$userrow['zid']}'");
	}
	$result=array("code"=>0,"msg"=>"succ");
	exit(json_encode($result));
break;
case 'recharge':
	$value=daddslashes($_GET['value']);
	$trade_no=date("YmdHis").rand(111,999);
	if(!is_numeric($value) || !preg_match('/^[0-9.]+$/', $value))exit('{"code":-1,"msg":"提交参数错误！"}');
	if($conf['recharge_min']>0 && $value<$conf['recharge_min'])exit('{"code":-1,"msg":"最低充值'.$conf['recharge_min'].'元！"}');
	$sql="INSERT INTO `pre_pay` (`trade_no`,`tid`,`input`,`name`,`money`,`ip`,`addtime`,`status`) VALUES (:trade_no, :tid, :input, :name, :money, :ip, NOW(), 0)";
	$data=[':trade_no'=>$trade_no, ':tid'=>-1, ':input'=>(string)$userrow['zid'], ':name'=>'在线充值余额', ':money'=>$value, ':ip'=>$clientip];
	if($DB->exec($sql, $data)){
		exit('{"code":0,"msg":"提交订单成功！","trade_no":"'.$trade_no.'","money":"'.$value.'","name":"在线充值余额"}');
	}else{
		exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
	}
break;
case 'setClass':
	$cid=intval($_GET['cid']);
	$active=intval($_GET['active']);
	$classhide = explode(',',$userrow['class']);
	if($active == 1 && in_array($cid, $classhide)){
		$classhide = array_diff($classhide, array($cid));
	}elseif($active == 0 && !in_array($cid, $classhide)){
		$classhide[] = $cid;
	}
	$class = implode(',',$classhide);
	$DB->exec("UPDATE `pre_site` SET `class`='{$class}' WHERE zid='{$userrow['zid']}'");
	exit('{"code":0}');
break;
case 'uploadimg':
	if(!$conf['workorder_pic'])exit('{"code":-1,"msg":"未开启上传图片功能"}');
	if($_POST['do']=='upload'){
		$filename = $_FILES['file']['name'];
		$ext = substr($filename, strripos($filename, '.') + 1);
		$arr = array('png', 'jpg', 'gif', 'jpeg', 'webp', 'bmp');
		if (!in_array($ext , $arr)) {
			exit('{"code":-1,"msg":"只支持上传图片文件"}');
		}
		$filename = md5_file($_FILES['file']['tmp_name']).'.png';
		$fileurl = 'assets/img/workorder/'.$filename;
		if(copy($_FILES['file']['tmp_name'], ROOT.$fileurl)){
			exit('{"code":0,"msg":"succ","url":"'.$fileurl.'"}');
		}else{
			exit('{"code":-1,"msg":"上传失败，请确保有本地写入权限"}');
		}
	}
	exit('{"code":-1,"msg":"null"}');
break;
case 'usekm':
	if(!$conf['fenzhan_jiakuanka'])exit('{"code":-1,"msg":"未开启使用加款卡功能"}');
	$km=trim(daddslashes($_POST['km']));
	$myrow=$DB->getRow("SELECT * FROM pre_kms WHERE km='$km' AND type=0 LIMIT 1");
	if(!$myrow)
	{
		exit('{"code":-1,"msg":"此卡密不存在！"}');
	}
	elseif($myrow['status']==1){
		exit('{"code":-1,"msg":"此卡密已被使用！"}');
	}
	$money = $myrow['money'];
	if($DB->exec("UPDATE `pre_kms` SET `status`=1 WHERE `kid`='{$myrow['kid']}'")){
		$DB->exec("UPDATE `pre_kms` SET `zid` ='{$userrow['zid']}',`usetime` ='".$date."' WHERE `kid`='{$myrow['kid']}'");
		$rs = changeUserMoney($userrow['zid'], $money, true, '充值', '你使用加款卡充值了'.$money.'元余额');
		if($rs){
			exit('{"code":0,"msg":"成功充值'.$money.'元余额！"}');
		}
	}
	exit('{"code":-1,"msg":"充值失败'.$DB->error().'"}');
break;
case 'app_upload':
	if(!$conf['appcreate_open'] || !$conf['appcreate_key'])exit('{"code":-1,"msg":"未开启分站自助生成APP功能"}');
	if(!$conf['appcreate_diy'])exit('{"code":-1,"msg":"未开启自定义图标和启动图"}');
	$file = $_FILES['file'];
	$type = strtolower(substr($file['name'], strrpos($file['name'], '.') + 1));
	if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
		exit(json_encode(['code' => -1, 'msg' => '上传图片格式错误']));
	}
	$path = sys_get_temp_dir().'/'.md5_file($file['tmp_name']).'.'.$type;
	if (!move_uploaded_file($file['tmp_name'], $path)) {
		exit(json_encode(['code' => -1, 'msg' => '上传失败']));
	}
	$app = new \lib\AppCreate($conf['appcreate_key']);
	if($app->uploadimg($path)){
		exit(json_encode(['code' => 0, 'msg' => '图片上传成功', 'fileid' => $app->fileid]));
	}else{
		exit(json_encode(['code' => -1, 'msg' => $app->msg]));
	}
break;
case 'app_submit':
	if(!$conf['appcreate_open'] || !$conf['appcreate_key'])exit('{"code":-1,"msg":"未开启分站自助生成APP功能"}');
	$price = $userrow['power']==2?$conf['appcreate_price2']:$conf['appcreate_price'];
    if ($price>0 && $userrow['rmb']<$price)exit('{"code":-1,"msg":"你的余额不足，生成APP需要'.$price.'元"}');
	$app = new \lib\AppCreate($conf['appcreate_key']);
	$name=trim(daddslashes($_POST['name']));
	$url=trim(daddslashes($_POST['url']));
	if(empty($name))exit('{"code":-1,"msg":"应用名称不能为空"}');
	if(!preg_match('/^[a-zA-Z0-9\x7f-\xff\.\-\! ]+$/',$name) || strlen($name)<3){
		exit('{"code":-1,"msg":"应用名称不合法"}');
	}
	if(mb_strlen($name, "UTF-8")>12)exit('{"code":-1,"msg":"应用名称长度不能超过12个字"}');
	if(empty($url))exit('{"code":-1,"msg":"应用网址不能为空"}');
	if(!strpos($url,'.'))exit('{"code":-1,"msg":"应用网址不正确"}');
	if(isset($_SESSION['appurl']) && $_SESSION['appurl']==$url)exit(json_encode(['code' => -1, 'msg' => '你已经生成过了，请在"我的生成"中查看。']));
	if($conf['appcreate_diy']==1){
		$icon = !empty($_POST['icon'])?trim($_POST['icon']):'1';
		$background = !empty($_POST['background'])?trim($_POST['background']):'2';
	}else{
		$icon = '1';
		$background = '2';
	}
	$theme = $conf['appcreate_theme'];
	if($app->submittask($name, $url, $icon, $background, $theme, $conf['appcreate_nonav'])){
		$_SESSION['appurl'] = $url;
		if($price>0){
			changeUserMoney($userrow['zid'], $price, false, '消费', '自助生成APP');
		}
		exit(json_encode(['code' => 0, 'msg' => '成功提交生成任务，生成大约需要半分钟，生成成功后请在"我的生成"中查看。', 'taskid' => $app->taskid]));
	}else{
		exit(json_encode(['code' => -1, 'msg' => $app->msg]));
		exit('{"code":-1,"msg":"'.$app->msg.'"}');
	}
break;
case 'app_query':
	if(!$conf['appcreate_open'] || !$conf['appcreate_key'])exit('{"code":-1,"msg":"未开启分站自助生成APP功能"}');
	$app = new \lib\AppCreate($conf['appcreate_key']);
	$url = 'http://'.$userrow['domain'];
	$url=isset($_SESSION['appurl'])?$_SESSION['appurl']:$url;
	$domain = parse_url($url)['host'];
	$res=$app->queryurl($url);
	if($res && is_array($res)){
		$appurl = "";
		if($res['status']==1){
			$android_url = $res['lanzou_url']?$res['lanzou_url']:$res['android_url'];
			$ios_url = $res['ios_url'];
			$approw = $DB->find('apps','*',['domain'=>$domain]);
			if($approw){
				$id = $approw['id'];
				$DB->update('apps',['taskid'=>$res['id'], 'domain'=>$domain, 'name'=>$res['name'], 'package'=>$res['package'], 'android_url'=>$android_url, 'ios_url'=>$ios_url, 'icon'=>$res['icon'], 'addtime'=>$res['created_at'], 'status'=>1], ['id'=>$id]);
			}else{
				$id = $DB->insert('apps',['taskid'=>$res['id'], 'domain'=>$domain, 'name'=>$res['name'], 'package'=>$res['package'], 'android_url'=>$android_url, 'ios_url'=>$ios_url, 'icon'=>$res['icon'], 'addtime'=>$res['created_at'], 'status'=>1]);
			}
			$appurl = '/?mod=app&id='.$id;
			$DB->exec("UPDATE `pre_site` SET `appurl`=:appurl WHERE `zid`='{$userrow['zid']}'", [':appurl'=>$appurl]);
		}
		$result=array("code"=>0,"msg"=>"succ","url"=>$url,"download_url"=>$appurl,"download_url_show"=>$url.$appurl,"android_url"=>$android_url,"ios_url"=>$ios_url,"data"=>$res);
		exit(json_encode($result));
	}else{
		exit(json_encode(['code' => -1, 'msg' => $app->msg]));
	}
break;
case 'tixian_note':
	$id=intval($_POST['id']);
	$rows=$DB->getRow("select * from pre_tixian where id='$id' and zid='{$userrow['zid']}' limit 1");
	$result=array("code"=>0,"msg"=>"succ","result"=>$rows['note']);
	exit(json_encode($result));
break;
case 'transfer':
	exit();
	$account=trim($_POST['account']);
	$money=trim($_POST['money']);
	if(empty($account) || empty($money))exit('{"code":-1,"msg":"请确保各项不能为空！"}');
	if(!is_numeric($money) || !preg_match('/^[0-9.]+$/', $money))exit('{"code":-1,"msg":"转账金额参数错误！"}');
	if($account == $userrow['user'])exit('{"code":-1,"msg":"无法给自己转账！"}');

	$rows=$DB->getRow("select * from pre_site where user='$account' limit 1");
	if(!$rows)exit('{"code":-1,"msg":"对方账号不存在"}');
	if($rows['status']==0)exit('{"code":-1,"msg":"对方账号状态异常"}');

	$userrow = $DB->getRow("SELECT * FROM pre_site WHERE zid='{$userrow['zid']}' LIMIT 1 FOR UPDATE");
	if($money>$userrow['rmb'] || $money<=0){
		exit('{"code":-1,"msg":"所输入的转账金额大于你所拥有的余额！"}');
	}
	changeUserMoney($userrow['zid'], $money, false, '转账', '转账'.$money.'元给用户'.$account);
	changeUserMoney($rows['zid'], $money, true, '转账', '收到用户'.$userrow['user'].'的'.$money.'元转账');
	
	$result=array("code"=>0,"msg"=>'成功转账'.$money.'元给用户'.$account,"result"=>$rows['note']);
	exit(json_encode($result));
break;
default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}