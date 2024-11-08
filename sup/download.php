<?php
include("../includes/common.php");

if($islogin3==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if($suprow['bond'] < $conf['sup_bond']){
    exit('<script>alert("您当前未缴纳保证金，正在为您跳转...");window.location.href="./bond.php";</script>');
}
if(isset($_GET['tid'])){
	$tid=intval($_GET['tid']);
	$sql="tid='$tid' and sid='{$suprow['sid']}'";
}elseif(isset($_GET['orderid'])){
	$orderid=intval($_GET['orderid']);
	$sql="orderid='$orderid' and sid='{$suprow['sid']}'";
}elseif(isset($_GET['kid'])) {
	$kid=intval($_GET['kid']);
	$sql="kid='$kid' and sid='{$suprow['sid']}'";
}else{
	$sql="sid='{$suprow['sid']}'";
}
if(isset($_GET['use']) && $_GET['use']==1)$sql.= " and orderid!=0";
elseif(isset($_GET['use']) && $_GET['use']==0)$sql.= " and orderid=0";
if(isset($_GET['num']))$limit = " limit ".$_GET['num'];
$rs=$DB->query("SELECT * FROM pre_faka WHERE {$sql} order by kid asc{$limit}");
$data='';
while($res = $rs->fetch())
{
	$data.=($res['pw']?$res['km'].' '.$res['pw']:$res['km'])."\r\n";
	if($_GET['isuse']==1&&$_GET['use']==0)$DB->exec("update `pre_faka` set orderid=1,usetime=NOW() where `kid`='{$res['kid']}'");
}

$file_name='output_'.$tid.'_'.$date.'__'.time().'.txt';
$file_size=strlen($data);
header("Content-Description: File Transfer");
header("Content-Type:application/force-download");
header("Content-Length: {$file_size}");
header("Content-Disposition:attachment; filename={$file_name}");
echo $data;
?>