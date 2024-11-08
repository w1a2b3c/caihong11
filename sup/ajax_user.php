<?php
include("../includes/common.php");

$act=isset($_GET['act'])?daddslashes($_GET['act']):null;

@header('Content-Type: application/json; charset=UTF-8');

if(!checkRefererHost())exit('{"code":403}');
if(!$islogin3)exit('{"code":-1,"msg":"未登录"}');

switch($act){
case 'setpwd':
	if(substr($suprow['user'],0,3)!='qq_')exit('{"code":-1,"msg":"请勿重复提交"}');
	$user = trim(htmlspecialchars(strip_tags(daddslashes($_POST['user']))));
	$pwd = trim(htmlspecialchars(strip_tags(daddslashes($_POST['pwd']))));
	if (!preg_match('/^[a-zA-Z0-9\x7f-\xff]+$/',$user)) {
		exit('{"code":-1,"msg":"用户名只能为英文、数字与汉字！"}');
	} elseif ($DB->getRow("SELECT sid FROM pre_supplier WHERE user=:user LIMIT 1", [':user'=>$user])) {
		exit('{"code":-1,"msg":"用户名已存在！"}');
	} elseif (strlen($pwd) < 6) {
		exit('{"code":-1,"msg":"密码不能低于6位"}');
	} elseif ($pwd == $user) {
		exit('{"code":-1,"msg":"用户名和密码不能相同！"}');
	}
	if($DB->exec("UPDATE pre_supplier SET user=:user,pwd=:pwd WHERE sid=:sid", [':user'=>$user, ':pwd'=>$pwd, ':sid'=>$suprow['sid']])){
		$session=md5($user.$pwd.$password_hash);
		$token=authcode("{$suprow['sid']}\t{$session}", 'ENCODE', SYS_KEY);
		ob_clean();
		setcookie("sup_token", $token, time() + 604800, '/');
		exit('{"code":0,"msg":"保存成功"}');
	}else{
		exit('{"code":-1,"msg":"保存失败！'.$DB->error().'"}');
	}
break;
case 'getfakatool': //获取发卡商品
    $cid=intval($_GET['cid']);
    $rs=$DB->query("SELECT * FROM pre_tools WHERE cid='$cid' and is_curl=4 and active=1 and goods_sid='{$suprow['sid']}' and audit_status='1' order by sort asc");
    $data = array();
    while($res = $rs->fetch()){
        $data[]=array('tid'=>$res['tid'],'name'=>$res['name']);
    }
    $result=array("code"=>0,"msg"=>"succ","data"=>$data);
    exit(json_encode($result));
    break;
case 'msg':
	$income_today=$DB->getColumn("SELECT sum(point) FROM pre_suppoints WHERE sid='{$suprow['sid']}' AND action='提成' AND addtime>'$thtime'");
	$result=array("code"=>0,"income_today"=>"".round($income_today,2)."");
    exit(json_encode($result));
break;
case 'msginfo':
	if($suprow['power']==2){
		$type = array(0,2,4);
	}elseif($suprow['power']==1){
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
	if(!in_array($id,explode(',',$suprow['msgread']))){
		$msgread_n = $suprow['msgread'].$id.',';
		$DB->exec("UPDATE pre_message SET count=count+1 WHERE id='$id'");
		$DB->exec("UPDATE pre_supplier SET msgread='".$msgread_n."' WHERE sid='{$suprow['sid']}'");
	}
	$result=array("code"=>0,"msg"=>"succ","title"=>$row['title'],"type"=>$row['type'],"content"=>$row['content'],"date"=>$row['addtime']);
	exit(json_encode($result));
break;
case 'msg_read_all':
	if($suprow['power']==2){
		$type = array(0,2,4);
	}elseif($suprow['power']==1){
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
		$DB->exec("UPDATE pre_supplier SET msgread='".$id."' WHERE sid='{$suprow['sid']}'");
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
	$data=[':trade_no'=>$trade_no, ':tid'=>-4, ':input'=>(string)$suprow['sid'], ':name'=>'在线充值余额', ':money'=>$value, ':ip'=>$clientip];
	if($DB->exec($sql, $data)){
		exit('{"code":0,"msg":"提交订单成功！","trade_no":"'.$trade_no.'","money":"'.$value.'","name":"在线充值余额"}');
	}else{
		exit('{"code":-1,"msg":"提交订单失败！'.$DB->error().'"}');
	}
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
case 'sup_tixian_note':
	$id=intval($_POST['id']);
	$rows=$DB->getRow("select * from pre_suptixian where id='$id' and sid='{$suprow['sid']}' limit 1");
	$result=array("code"=>0,"msg"=>"succ","result"=>$rows['note']);
	exit(json_encode($result));
break;
default:
	exit('{"code":-4,"msg":"No Act"}');
break;
}