<?php


include "../includes/common.php";
$title = "举报供货商";
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
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">联系方式</h3>
        </div>
        <div class="panel-body">
            <?php 
if (!$conf["sup_report"]) {
	exit("<script>alert(\"站长暂未设置联系方式\");location.href=\"/\";</script>");
}
$rs = explode("\n", $conf["sup_report"]);
foreach ($rs as $res) {
	$arr = explode("|", $res);
	echo "<a href=\"" . $arr[0] . "\" target=\"_blank\" class=\"btn btn-success\">" . $arr[1] . "</a>&nbsp;";
}
?>        </div>
    </div>
</div>
</body>
</html>