<?php
$install = true;
require_once('../includes/common.php');
@header('Content-Type: text/html; charset=UTF-8');
if($conf['lt_version'] < 1011){
	$sqls = file_get_contents('update.sql');
	$version = 1011;
}elseif($conf['lt_version'] < 1012){
	$sqls = file_get_contents('update2.sql');
	$version = 1012;
}else{
	exit('你的网站已经升级到最新版本了');
}
$explode = explode(';', $sqls);
foreach ($explode as $sql) {
    if ($sql = trim($sql)) {
        $DB->exec($sql);
    }
}
saveSetting('lt_version',$version);
$CACHE->clear();
exit("<script language='javascript'>alert('网站数据库升级完成！');window.location.href='../';</script>");
?>