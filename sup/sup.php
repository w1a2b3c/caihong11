<?php

include "../includes/common.php";
$title = "举办供货商";
if ($conf["cdnpublic"] == 1) {
	$cdnpublic = "//lib.baomitu.com/";
} elseif ($conf["cdnpublic"] == 2) {
	$cdnpublic = "https://cdn.bootcdn.net/ajax/libs/";
} elseif ($conf["cdnpublic"] == 4) {
	$cdnpublic = "//s1.pstatp.com/cdn/expire-1-M/";
} else {
	$cdnpublic = "//cdn.staticfile.org/";
}
if (!empty($conf["staticurl"])) {
	$cdnserver = "//" . $conf["staticurl"] . "/";
} else {
	$cdnserver = "../";
}
$sid = intval($_GET["sid"]);
$rows = $DB->getRow("select * from pre_supplier where sid='" . $sid . "' and status = '1' limit 1");
if (!$rows) {
	exit("<script>alert(\"不存在此供货商！\");location.href=\"/\"</script>");
}
if ($rows["bond"] < $conf["sup_bond"]) {
	exit("<script>alert(\"此供货商未缴纳保证金！\");location.href=\"/\"</script>");
}
$numrows = $DB->getColumn("SELECT count(*) FROM pre_tools WHERE active=1 and goods_sid='" . $sid . "' and audit_status='1'");
$rs = $DB->query("SELECT * FROM pre_tools WHERE active=1 and goods_sid='" . $sid . "' and audit_status='1' ORDER BY sort ASC");
if (empty($rows["qq"]) && !empty($rows["faceimg"])) {
	$faceimg = htmlspecialchars($rows["faceimg"]);
} elseif (!empty($rows["qq"])) {
	$faceimg = "//q4.qlogo.cn/headimg_dl?dst_uin=" . $rows["qq"] . "&spec=100";
} else {
	$faceimg = "/assets/img/user.png";
}
?><!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8" />
    <title><?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link href="<?php echo $cdnpublic;?>twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="<?php echo $cdnpublic;?>font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="<?php echo $cdnserver;?>assets/user/css/animate.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $cdnserver;?>assets/user/css/app.css" type="text/css" />
    <script src="<?php echo $cdnpublic;?>jquery/2.1.4/jquery.min.js"></script>
    <script src="<?php echo $cdnpublic;?>twitter-bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="<?php echo $cdnpublic;?>layer/3.1.1/layer.js"></script>
    <script src="<?php echo $cdnserver;?>assets/user/js/app.js"></script>
    <!--[if lt IE 9]>
    <script src="<?php echo $cdnpublic;?>html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="<?php echo $cdnpublic;?>respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<div class="col-xs-12 col-sm-10 col-md-8 col-lg-4 center-block " style="float: none;">
    <br />

        <div class="panel panel-default">
            <div class="panel-heading" style="background-image: url(/assets/img/bj.png);background-size: 100% 100%;" >
                <div class="widget-content themed-background-flat text-center"  >
                    <img  class="img-circle"src="<?php echo $faceimg;?>" alt="Avatar" alt="avatar" height="60" width="60" />
                </div>
                <h3 class="alert alert-info">他目前的商品数量<?php echo $numrows;?>个</h3>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tbody>
                        <div class="alert alert-success" style="padding: 10px; font-size: 90%; text-align:left;font-weight:bold;background-color:#dae0e8" >
                            <span style="color:#777;font-size: 10px;">以下业务由供货商提供，由平台担保购买！</span><span style="color:red;font-size: 10px;";>已交保证金:<?php echo $rows["bond"];?>元</span><br><span style="color:#EE33EE;font-size: 10px;";>请勿脱离平台交易，谨防受骗！（线下交易，平台不负任何责任！）</span>
                        </div>
                        <form id="classlist">
                            <?php 
while ($res = $rs->fetch()) {
	echo "<tr><td><a href=\"/?cid=" . $res["cid"] . "&tid=" . $res["tid"] . "\" target=\"_blank\" style=\"color:#5ccdde;\">" . $res["name"] . "</a></td><td><a href=\"/?cid=" . $res["cid"] . "&tid=" . $res["tid"] . "\" target=\"_blank\" class=\"btn btn-sm btn-success pull-right\">查看</a></td></td></tr>";
}
?>

                        </form>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>