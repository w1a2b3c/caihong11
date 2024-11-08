<?php
/**
 * 自助开通分站
**/
$is_defend=true;
include("../includes/common.php");
if($islogin2==1 && $userrow['power']>0){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('您已开通过分站！');window.location.href='./';</script>");
}elseif($conf['fenzhan_buy']==0){
	@header('Content-Type: text/html; charset=UTF-8');
	exit("<script language='javascript'>alert('当前站点未开启自助开通分站功能！');window.location.href='./';</script>");
}

if($is_fenzhan == true) {
    if($siterow['power'] == 2) {
        if($siterow['ktfz_price'] > 0) {
            $conf['fenzhan_price'] = $siterow['ktfz_price'];
        }
        if($conf['fenzhan_cost2'] <= 0) {
            $conf['fenzhan_cost2'] = $conf['fenzhan_price2'];
        }
        if($siterow['ktfz_price2'] > 0 && $siterow['ktfz_price2'] >= $conf['fenzhan_cost2']) {
            $conf['fenzhan_price2'] = $siterow['ktfz_price2'];
        }
    } elseif($siterow['power'] == 1) {
      $upzid = $siterow['upzid'];
while($upzid > 0) {
    $sql2 = "SELECT `power`, `ktfz_price`, `ktfz_price2`, `upzid` FROM `shua_site` WHERE `zid`='{$upzid}'";
    $siterow2 = $DB->query($sql2)->fetch(PDO::FETCH_ASSOC);
    if($siterow2['power'] == 2) {
        if($siterow2['ktfz_price'] > 0) {
            $conf['fenzhan_price'] = $siterow2['ktfz_price'];
        }
        if($conf['fenzhan_cost2'] <= 0) {
            $conf['fenzhan_cost2'] = $conf['fenzhan_price2'];
        }
        if($siterow2['ktfz_price2'] > 0 && $siterow2['ktfz_price2'] >= $conf['fenzhan_cost2']) {
            $conf['fenzhan_price2'] = $siterow2['ktfz_price2'];
        }
        break;
    } else {
        $upzid = $siterow2['upzid'];
    }
}
        // 如果找到根站点（`upzid=0`）仍未找到代理商站点，则使用默认的分站价格和成本。
        if ($upzid == 0) {
            if($conf['fenzhan_price'] <= 0) {
                $conf['fenzhan_price'] = 100;
            }
            if($conf['fenzhan_cost2'] <= 0) {
                $conf['fenzhan_cost2'] = $conf['fenzhan_price2'];
            }
        }
    }
}

$title='自助开通站点';
include './head2.php';

$addsalt=md5(mt_rand(0,999).time());
$_SESSION['addsalt']=$addsalt;
$x = new \lib\hieroglyphy();
$addsalt_js = $x->hieroglyphyString($addsalt);

$kind = isset($_GET['kind'])?$_GET['kind']:0;

if($is_fenzhan == true && $siterow['power']==2 && !empty($siterow['ktfz_domain'])){
	$domains=explode(',',$siterow['ktfz_domain']);
}else{
	$domains=explode(',',$conf['fenzhan_domain']);
}
$select='';
foreach($domains as $domain){
	$select.='<option value="'.$domain.'">'.$domain.'</option>';
}
if(empty($select))showmsg('请先到后台分站设置，填写可选分站域名',3);
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>&#33258;&#21161;&#24320;&#36890;&#20998;&#31449;</title>
  <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
  <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
  <link rel="stylesheet" href="../assets/simple/css/plugins.css">
  <link rel="stylesheet" href="../assets/simple/css/main.css">
  <link rel="stylesheet" href="../assets/css/common.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet"/>
  <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
  <link rel="stylesheet" href="../assets/user/css/my.css">
   <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<body>
<style>
    .block {
        margin: 0 0 0px;

        background-color: rgba(218, 224, 232, 0);

    }

    .list-group-item {
        border: 0px solid #ddd;
        padding: 5px;
        font-size: 1.15rem;

    }

    .list-group-item b {
        font-weight: 0;

    }

    .list-group-item img {
        width: 2.2rem;

    }

    .panel-default1 {
        border-color: rgba(218, 224, 232, 0);
        background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0), rgba(0, 0, 0, 0));
        border-radius: 10px;
        padding-top: 5px;
        position: relative;
    }

    .panel-default {
        border-color: rgba(218, 224, 232, 0);
        background-image: linear-gradient(to bottom, rgba(250, 166, 166, 1), rgba(236, 130, 173, 1));
        border-radius: 10px;
        padding-top: 5px;
        position: relative;
    }

    .panel-default .title-panel {
        top: 5px;
        position: absolute;
        width: 100%;
        height: 13%;
        left: 0%;
        z-index: 999;

    }

    .panel-default .title-panel .view {
        margin: auto;
        background: rgba(250, 166, 166, 1);
        width: 28%;
        height: 100%;
        border-radius: 5px; /* 设置圆角 */
        transform: perspective(10px) scale(1.2, 1.1) rotateX(-5deg);
        position: relative;
    }

    .panel-default .view-title {
        top: 5px;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        z-index: 1000;
        display: flex;
        justify-content: center;
        align-items: center;
        color: #fff;
        font-weight: 500;
    }

    .panel {
        background-color: rgba(218, 224, 232, 0);
        border: 0px solid transparent;
    }

    .fz-view {
        width: 100%;
        display: flex;
        justify-content: space-between;
        align-items: center;

    }

    .fz-view .fz-detail {
        width: 47%;
        height: 8rem;
        position: relative;
        border-radius: 12px;
        overflow: hidden;

    }

    .fz-view .fz-detail .fz-info {
        width: 100%;
        height: 90%;
        border-radius: 12px;
        position: relative;
        z-index: 9;
    }

    .fz-view .fz-detail .fz-bg {
        width: 95%;
        height: 50%;
        position: absolute;
        bottom: 0;
        left: 50%;
        z-index: 8;
        border-radius: 12px;
        transform: translateX(-50%);
        background: rgba(255, 255, 255, 0.8);
        /*filter: blur(20px);*/
        /*box-shadow: 0 0 5px 1px #fff;*/
    }

    .fz-view .fz-detail .fz-detail-bg {
        width: 100%;
        height: 150%;
        position: absolute;
        bottom: 0;
        left: 0;
        z-index: 10;

    }
    .layui-layer-title {
        padding: 0 80px 0 20px;
        height: 42px;
        line-height: 42px;
        border-bottom: 0px solid #fff1dc;
        font-size: 14px;
        color: #333;
        overflow: hidden;
        background-color: #fff1dc;
        border-radius: 2px !important;
    }
    .layui-layer-btn .layui-layer-btn0 {
            border-color: #fff1dc;
        background-color: #fff1dc;
        color: #333;
        font-size: 13px;
        border-radius: 10px !important;
    }
    .layui-layer{
       border-radius: 9px !important;
    }
    .layui-layer-title{
        border-radius: 9px !important;
    }
</style>
<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block "
     style="float: none; background-color:#f2f2f2;padding:0;max-width: 550px;">
    <div class="block">

        <div class="block-back" style="padding: 5px">
            <a href="javascript:history.back()"  class="font-weight display-row align-center">
                <img style="height: 1.5rem" src="../assets/img/fanhui.png"></img>&nbsp;&nbsp;
                <font>自助开通分站</font>
            </a>
        </div>
        <hr>

        <div class="panel panel-default">
            <div class="title-panel">
                <div class="view"></div>
            </div>
            <div class="view-title">
                <img style="height:2.2rem" src="../assets/user/img/qiandao.png">
                <font style="font-size:1.3rem;">分站收入</font>
            </div>
            <marquee direction="up" behavior="scroll" loop="3" scrollamount="3" scrolldelay="8" align="top" hspace="10"
                     vspace="7" onMouseOver="this.stop()" onMouseOut="this.start()"
                     style="background-color:#ffffff; height: 170px; width:calc(100% - 20px) ;padding:0 5px">

                <a class="list-group-item">UID[3***1] ：<span>今日&#25910;&#30410;<font color="red">972</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red"> 25712 </font>  &#20803;   </span><img
                            src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***8] ：<span>今日&#25910;&#30410;<font color="red">5421</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">91741</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***2] ：<span>今日&#25910;&#30410;<font color="red">3578</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">71147</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***9] ：<span>今日&#25910;&#30410;<font color="red">521</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">41441</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***2] ：<span>今日&#25910;&#30410;<font color="red">198</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">4014</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***3] ：<span>今日&#25910;&#30410;<font color="red">478</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">13171</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***5] ：<span>今日&#25910;&#30410;<font color="red">761</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">47511</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[3***2] ：<span>今日&#25910;&#30410;<font color="red">171</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">451</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***3] ：<span>今日&#25910;&#30410;<font color="red">575</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">9451</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***8] ：<span>今日&#25910;&#30410;<font color="red">679</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">14217</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***5] ：<span>今日&#25910;&#30410;<font color="red">171</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">6414</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***1] ：<span>今日&#25910;&#30410;<font color="red">765</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">89823</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[8***9] ：<span>今日&#25910;&#30410;<font color="red">165</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">9741</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***7] ：<span>今日&#25910;&#30410;<font color="red">885</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">64142</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***1] ：<span>今日&#25910;&#30410;<font color="red">1076</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">23214</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***8] ：<span>今日&#25910;&#30410;<font color="red">1791</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">111899</font>  &#20803;   </span><img
                            src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***1] ：<span>今日&#25910;&#30410;<font color="red">1865</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">90471</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***4] ：<span>今日&#25910;&#30410;<font color="red">587</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">48411</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***2] ：<span>今日&#25910;&#30410;<font color="red">1212</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">19101</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***9] ：<span>今日&#25910;&#30410;<font color="red">541</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">8441</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***3] ：<span>今日&#25910;&#30410;<font color="red">452</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">6971</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***5] ：<span>今日&#25910;&#30410;<font color="red">874</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">9711</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[3***2] ：<span>今日&#25910;&#30410;<font color="red">471</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">12412</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***3] ：<span>今日&#25910;&#30410;<font color="red">459</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">17414</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***8] ：<span>今日&#25910;&#30410;<font color="red">965</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">14874</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***5] ：<span>今日&#25910;&#30410;<font color="red">168</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">6144</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***1] ：<span>今日&#25910;&#30410;<font color="red">965</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">80123</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***1] ：<span>今日&#25910;&#30410;<font color="red">1091</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">10238</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***9] ：<span>今日&#25910;&#30410;<font color="red">761</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">9914</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***7] ：<span>今日&#25910;&#30410;<font color="red">671</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">8741</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***1] ：<span>今日&#25910;&#30410;<font color="red">567</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">7331</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***8] ：<span>今日&#25910;&#30410;<font color="red">478</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">8199</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***1] ：<span>今日&#25910;&#30410;<font color="red">871</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">871</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***4] ：<span>今日&#25910;&#30410;<font color="red">667</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">41101</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***1] ：<span>今日&#25910;&#30410;<font color="red">847</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">7912</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***4] ：<span>今日&#25910;&#30410;<font color="red">1717</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">44015</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***2] ：<span>今日&#25910;&#30410;<font color="red">1921</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">147011</font>  &#20803;   </span><img
                            src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***9] ：<span>今日&#25910;&#30410;<font color="red">198</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">4141</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***3] ：<span>今日&#25910;&#30410;<font color="red">972</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">9571</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[2***4] ：<span>今日&#25910;&#30410;<font color="red">652</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">8839</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[2***1] ：<span>今日&#25910;&#30410;<font color="red">2511</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">201235</font>  &#20803;   </span><img
                            src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[6***5] ：<span>今日&#25910;&#30410;<font color="red">1797</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">149701</font>  &#20803;   </span><img
                            src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***2] ：<span>今日&#25910;&#30410;<font color="red">1074</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">51099</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***9] ：<span>今日&#25910;&#30410;<font color="red">878</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">16054</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[8***3] ：<span>今日&#25910;&#30410;<font color="red">1190</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">57011</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***2] ：<span>今日&#25910;&#30410;<font color="red">991</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">21019</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***9] ：<span>今日&#25910;&#30410;<font color="red">2481</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">44101</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***3] ：<span>今日&#25910;&#30410;<font color="red">1472</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">80971</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***5] ：<span>今日&#25910;&#30410;<font color="red">1127</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">50031</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[6***2] ：<span>今日&#25910;&#30410;<font color="red">4512</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">60870</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[3***3] ：<span>今日&#25910;&#30410;<font color="red">979</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">4514</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***8] ：<span>今日&#25910;&#30410;<font color="red">1196</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">30717</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***2] ：<span>今日&#25910;&#30410;<font color="red">861</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">6614</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***1] ：<span>今日&#25910;&#30410;<font color="red">581</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">20143</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***1] ：<span>今日&#25910;&#30410;<font color="red">672</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">8841</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***9] ：<span>今日&#25910;&#30410;<font color="red">542</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">6921</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***7] ：<span>今日&#25910;&#30410;<font color="red">698</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">11071</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***1] ：<span>今日&#25910;&#30410;<font color="red">479</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">5331</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***8] ：<span>今日&#25910;&#30410;<font color="red">962</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">962</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***1] ：<span>今日&#25910;&#30410;<font color="red">1291</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">6902</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***4] ：<span>今日&#25910;&#30410;<font color="red">558</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">4914</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[2***1] ：<span>今日&#25910;&#30410;<font color="red">681</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">5229</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[2***4] ：<span>今日&#25910;&#30410;<font color="red">1728</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">122887</font>  &#20803;   </span><img
                            src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***2] ：<span>今日&#25910;&#30410;<font color="red">971</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">42145</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[2***9] ：<span>今日&#25910;&#30410;<font color="red">651</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">10351</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***3] ：<span>今日&#25910;&#30410;<font color="red">1861</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">11025</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***4] ：<span>今日&#25910;&#30410;<font color="red">761</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">5912</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***1] ：<span>今日&#25910;&#30410;<font color="red">421</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">4391</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[2***3] ：<span>今日&#25910;&#30410;<font color="red">2791</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">45801</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***2] ：<span>今日&#25910;&#30410;<font color="red">561</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">13599</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[1***9] ：<span>今日&#25910;&#30410;<font color="red">657</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">1509</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[3***3] ：<span>今日&#25910;&#30410;<font color="red">512</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">54515</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[6***1] ：<span>今日&#25910;&#30410;<font color="red">912</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">51204</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***8] ：<span>今日&#25910;&#30410;<font color="red">785</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">20183</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***1] ：<span>今日&#25910;&#30410;<font color="red">471</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">1871</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[9***4] ：<span>今日&#25910;&#30410;<font color="red">491</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">4057</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***1] ：<span>今日&#25910;&#30410;<font color="red">785</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">5541</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***4] ：<span>今日&#25910;&#30410;<font color="red">998</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">80848</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[7***2] ：<span>今日&#25910;&#30410;<font color="red">1627</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">41406</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***9] ：<span>今日&#25910;&#30410;<font color="red">1162</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">30420</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[3***3] ：<span>今日&#25910;&#30410;<font color="red">2451</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">112258</font>  &#20803;   </span><img
                            src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***4] ：<span>今日&#25910;&#30410;<font color="red">571</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">5090</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***1] ：<span>今日&#25910;&#30410;<font color="red">198</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">4307</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[2***3] ：<span>今日&#25910;&#30410;<font color="red">997</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">40167</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***2] ：<span>今日&#25910;&#30410;<font color="red">657</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">5998</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[5***9] ：<span>今日&#25910;&#30410;<font color="red">951</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">10509</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
                <a class="list-group-item">UID[4***3] ：<span>今日&#25910;&#30410;<font color="red">786</font>,&#32047;&#35745;&#25910;&#30410; <font
                                color="red">40217</font>  &#20803;   </span><img src="../assets/user/img/jinbi.png"></a>
            </marquee>
        </div>

                    
                    
                    
        <div class="fz-view">
            <a class="fz-detail" href="#about" data-toggle="modal">
                <div class="fz-info display-row align-center justify-around"
                     style="background-image:linear-gradient(to right, rgba(130, 193, 255, 1), rgba(148, 93, 246, 1));">
                    <div class="display-column" style="color: #eaeaea;">
                        <font size="3" color="#FFFFFF">站点介绍</font>
                        <font size="1" style="padding-top: 5px;">点击查看</font>
                    </div>
                    <img style="width: 4rem;height: 4rem;" src="../assets/user/img/jieshao.png">
                </div>
                <img class="fz-detail-bg" src="../assets/user/img/mengban.png">
                <div class="fz-bg"></div>
            </a>
            <a class="fz-detail" href="#userjs" data-toggle="modal">
                <div class="fz-info display-row align-center justify-around"
                     style="background-image:linear-gradient(to right, rgba(252, 198, 108, 1), rgba(249, 138, 63, 1));">
                    <div class="display-column" style="color: #eaeaea;">
                        <font size="3" color="#FFFFFF">站点功能</font>
                        <font size="1" style="padding-top: 5px;">点击查看</font>
                    </div>
                    <img style="width: 4rem;height: 4rem;" src="../assets/user/img/banben.png">
                </div>
                <img class="fz-detail-bg" src="../assets/user/img/mengban.png">
                <div class="fz-bg"></div>
                <!--<a class="btn btn-block btn-info" href="#userjs" data-toggle="modal">分站版本介绍</a>-->
            </a>
        </div>
        <br>
        
            <div class="my-cell">
                <div class="my-cell-title display-row justify-between align-center">
                    <div class="my-cell-title-l left-title">站点版本详细介绍</div>
                    <a style="color: #ff0000;font-size:1.4rem;"href="./ktzd.php">
                         点我查看
                    </a>
                </div>
            </div>


        <form>
            <div class="my-cell">
                <div class="my-cell-title display-row justify-between align-center">
                    <div class="my-cell-title-l left-title">站点版本 <a style="font-size: 10px;">(升级后所有商品均有折扣)</a></div>
                    <a class="my-cell-title-r" style="color: #b6bcbd;font-size:1.3rem;"
                       href="javascript:layer.alert('1.顶级合伙人可以无限免费搭建下级网站,并且别人在你下级网站下单你还有提成赚！
<br>2.顶级合伙人的商品成本价比分站站长更便宜，利润更多！
<br>3.顶级合伙人可自定义抽取下级同级别合伙人的订单佣金，无限躺赚！')">
                        <i class="fa fa-question-circle-o" style="font-size:1.5rem;"></i> 分站规则
                    </a>
                </div>

                <div class="form-group fenzhan_1 form-group-border">
                    <div class="input-group " onClick="setRadio('1')">
                        <div class="form-control">分站站长 <font color="red">￥<?php echo $conf['fenzhan_price']?></font>
                        <a style="font-size: 10px;">(可享受商品小幅折扣)</a>
                        </div>
                        <span class="input-group-addon ">
                             <input type="radio" name="kind" value="1" checked>
                        </span>
                    </div>
                </div>
                <div class="form-group fenzhan_2">
                    <div class="input-group " onClick="setRadio('2')">
                        <div class="form-control">顶级合伙人 <font color="red">￥<?php echo $conf['fenzhan_price2']?></font>
                        <a style="font-size: 10px;">(可享受商品大幅折扣)</a>
                        </div>
                        <span class="input-group-addon">
                            <input type="radio" name="kind" value="2" >
                        </span>
                    </div>
                </div>
            </div>

            <div class="my-cell">
                <div class="my-cell-title display-row justify-between align-center">
                    <div class="my-cell-title-l left-title">站点名称</div>
                    <a class="my-cell-title-r" style="color: #b6bcbd;"
                       href="javascript:layer.alert('例如：XX创业网,XX项目商城,自定义你想要的商城名字！')">
                        <i class="fa fa-question-circle-o" style="font-size:1.5rem;"></i>
                    </a>
                </div>
                <div class="form-group form-group-radius">
                    <div class="input-group form-group-radius" style="width:100%">
                        <input type="text" name="name" id="name" class="form-control input-content"
                               style="width:100%;border-radius: 50px;" required
                               data-parsley-length="[2,10]"
                               placeholder="输入网站名称">
                        <i class="fa fa-times-circle input-group-icon input-clear-a" style="pointer-events: auto;"
                           onclick="$('#name').val('')"></i>
                    </div>

                </div>
                <div class="form-bottm-border"></div>
                <div class="my-cell-title display-row justify-between align-center">
                    <div class="my-cell-title-l left-title">二级域名</div>
                    <a class="my-cell-title-r" style="color: #b6bcbd;"
                       href="javascript:layer.alert('可用字母，数字建议为2-5字，不能有标点符号（尽量简短,便于推广宣传）！')">
                        <i class="fa fa-question-circle-o" style="font-size:1.5rem;"></i>
                    </a>
                </div>
                <div class="form-group form-group-radius">
                    <div class="input-group form-group-radius" style="width:100%">
                        <div class="input-group-addon">
                            自定前缀
                        </div>
                        <input type="text" onKeyUp="value=value.replace(/[^\w\.\/]/ig,'')" name="qz"
                               class="form-control " style="width:100%;border-radius: 50px;" required
                               data-parsley-length="[2,8]" placeholder="输入你想要的二级前缀">
                        <i class="fa fa-refresh input-group-icon"
                           style="color: #3793FF;font-size:1.1rem;border-radius:50px;padding:.6rem;background: #f1f1f1"
                           onclick="$('[name=\'qz\']').val(Math.random().toString(36).substr(6))">&nbsp;随机生成</i>
                    </div>

                </div>
                <div class="form-group form-group-radius">
                    <div class="input-group form-group-radius" style="width:100%">
                        <div class="input-group-addon">
                            选择后缀
                        </div>
                                                    <select name="domain" class="form-control"><?php echo $select?></select>
                    </div>

                </div>

            </div>



                            <div class="my-cell">
                                <?php if(!$islogin2){?>
                    <div class="my-cell-title display-row justify-between align-center">
                        <div class="my-cell-title-l left-title">账号管理</div>
                        <a class="my-cell-title-r" style="color: #b6bcbd;"
                           href="javascript:layer.alert('1.建议填写您的QQ号作为用户名，方便记住！<br>2.可以用字母或数字，密码不能低于6位！')">
                            <i class="fa fa-question-circle-o" style="font-size:1.5rem;"></i>
                        </a>
                    </div>

				
                <div class="form-group form-group-radius">
                    <div class="input-group form-group-radius" style="width:100%">
                        <div class="input-group-addon">
                                管理账号
                      </div>
                            <input type="text" name="user" class="form-control" required placeholder="输入要注册的账号">
                  </div>
                    </div>
                    
                    
                    
                <div class="form-group form-group-radius">
                    <div class="input-group form-group-radius" style="width:100%">
                        <div class="input-group-addon">
                                管理密码
                      </div>
                            <input type="text" name="pwd" class="form-control" required placeholder="密码不能低于6位">
                  </div>
                    </div>
                    
                    
                    
                <div class="form-group form-group-radius">
                    <div class="input-group form-group-radius" style="width:100%">
                        <div class="input-group-addon">
                                绑定ＱＱ
                      </div>
                            <input type="number" name="qq" class="form-control" required
                                   data-parsley-length="[5,10]"
                                   placeholder="方便联系和找回密码" value="">
                  </div>
                    </div>
					<?php }?>
				
                <label class="c-switch u-mr-small display-row align-center justify-center" style="margin-bottom: 14px;font-size: .75rem;">
                    
                    <span class="c-switch__label" style="margin-left: 5px;margin-top: 6px;">
                                    <span class="d1 "><input class="c-switch__input" id="switch1" type="checkbox" on="" checked="">支付即视为您同意<a onClick="tanc()">《会员服务条款》</a>
                                    </span>
                </span>
                </label>
            </div>
            
            



            <div class="text-center">
                <input type="button" id="submit_buy" value="创建分站" style="width: 80%;" class="btn submit_btn ">
            </div>
            
            <br>
            <div class="list-group-item reed" align="center">
                <a style="font-size: 12px;color:green;">使用QQ钱包支付可跳转到微信支付<br><a href="./wxzy.php" style="color: #980000;">点我看详细微信余额转QQ方法教程</a><br>若需要单独微信支付或支付时出现异常无法支付的情况<br>可在【会员中心→充值】里进行转账充值余额<br>客服会在线为您充值余额到您的账户里</a>
            </div>
            
            <br><br>

        </form>
    </div>
</div>

<!--分站介绍开始-->
<div class="modal fade" align="left" id="userjs" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">


            <div class="table-responsive">
                <table class="table table-borderless table-vcenter">
                    <thead>
                        <tr>
                            <th style="width: 100px;">专属功能</th>
                            <th class="text-center" style="width: 20px;">分站站长</th>
                            <th class="text-centerx" style="width: 20px;">顶级合伙人</th>
                        </tr>
                    </thead>
					<tbody>
						<tr class="active">
                            <td>专属商城平台</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="success">
                            <td>专属网站域名</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="">
                            <td>赚取用户提成</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="info">
                            <td>设置商品价格</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="">
                            <td>赚取下级分站提成</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger"><i class="fa fa-close"></i></span>
							</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="warning">
                            <td>设置下级分站商品价格</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger"><i class="fa fa-close"></i></span>
							</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="">
                            <td>搭建下级分站</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger"><i class="fa fa-close"></i></span>
							</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
						<tr class="danger">
                            <td>赠送专属APP</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-danger"><i class="fa fa-close"></i></span>
							</td>
                            <td class="text-center">
								<span class="btn btn-effect-ripple btn-xs btn-success"><i class="fa fa-check"></i></span>
							</td>
                        </tr>
                    </tbody>
                </table>

	<div align="center"><atype="button" data-dismiss="modal" onclick="layer.closeAll();" style="height:4.5rem;width: 100%;line-height: 6rem;font-size: 1.6rem;color:#378bd3;font-weight: 510">知道了</a></div>
		</div>
    </div>
  </div>
</div>
<!--分站介绍结束-->

<div class="modal fade" align="left" id="about" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-body">
			<div class="panel-group" id="accordion">
				<div class="panel panel-default1">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" class="collapsed"><b>分站是怎么获取收益的？</b></a>
						</h4>
					</div>
					<div id="collapseOne" class="panel-collapse collapse" style="height: 0px;" aria-expanded="false">
						<div class="panel-body">
							其实很简单，你只需要把你的分站域名发给你的用户让他下单，一旦下单付款成功，你的账户就会增加你所赚差价的金额，自己是可以设置销售价格的哦！
						</div>
					</div>
				</div>
				<div class="panel panel-default1">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false"><b>赚到的钱在哪里？我如何得到？</b></a>
						</h4>
					</div>
					<div id="collapseTwo" class="panel-collapse collapse" style="height: 0px;" aria-expanded="false">
						<div class="panel-body">
							分站后台有完整的消费明细和余额信息，后台余额可供您在平台消费，满<?php echo $conf['tixian_min']; ?>元可以在后台提现到支付宝中。
						</div>
					</div>
				</div>
				<div class="panel panel-default1">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false"><b>需要我自己供货吗？哪来的商品货源？</b></a>
						</h4>
					</div>
					<div id="collapseThree" class="panel-collapse collapse" style="height: 0px;" aria-expanded="false">
						<div class="panel-body">
							所有商品全部由主站提供，您无需当心货源问题，所有订单由我们来处理，如果网站没有您想要的商品可联系主站客服添加即可！
						</div>
					</div>
				</div>
				<div class="panel panel-default1">
					<div class="panel-heading">
						<h4 class="panel-title">
							<a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="collapsed" aria-expanded="false"><b>可以自己上架商品吗？可以修改售价吗？</b></a>
						</h4>
					</div>
					<div id="collapseFive" class="panel-collapse collapse" style="height: 0px;" aria-expanded="false">
						<div class="panel-body">
							所有分站都不支持自己上架商品，但可以修改销售价格！
						</div>
					</div>
				</div>
				<div class="panel panel-default1">
					<div class="panel-heading">
						<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#accordion" href="#collapseSix" class="collapsed" aria-expanded="false"><b>那么多网站有分站，为什么选择你们呢？</b></a>
						</h4>
					</div>
					<div id="collapseSix" class="panel-collapse collapse" style="height: 0px;" aria-expanded="false">
						<div class="panel-body">
							全网最专业的商城系统，商品齐全、货源稳定、价格低廉、售后保障。实体工作室运营，拥有丰富的人脉和资源，我们的货源全部精挑细选全网性价比最高的，实时掌握市场的动态，加入我们，只要你坚持，你不用担心不赚钱，不用担心货源不好，更不用担心我们跑路，我们即使不敢保证你月入上万，在网上赚个零花钱绝对没问题！
						</div>
					</div>
				</div>
			</div>

	<div align="center"><atype="button" data-dismiss="modal" onclick="layer.closeAll();" style="height:4.5rem;width: 100%;line-height: 4.5rem;font-size: 1.6rem;color:#378bd3;font-weight: 510">知道了</a></div>
    </div>
  </div>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script>
    var hashsalt =(!+[]+!![]+!![]+[])+(+[]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(+!![]+[])+([]+{})[!+[]+!![]]+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(![]+[])[+[]]+(+[]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+[])+(+{}+[])[+!![]]+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+[])+([][[]]+[])[!+[]+!![]]+(!+[]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+[])+(+[]+[])+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+[])+(!+[]+!![]+!![]+!![]+!![]+[])+([]+{})[!+[]+!![]+!![]+!![]+!![]]+(+!![]+[])+([]+{})[!+[]+!![]+!![]+!![]+!![]]+(+{}+[])[+!![]]+([][[]]+[])[!+[]+!![]]+(!+[]+!![]+!![]+!![]+!![]+!![]+!![]+[])+([]+{})[!+[]+!![]]+(!+[]+!![]+!![]+[]);
    $(function () {
        $('.form-control').focus(function () {

            $(this).parent().addClass('form-group-border');


        })
        $('.form-control').blur(function () {
            $(this).parent().removeClass('form-group-border');
        })
        $("input[type=radio][name=kind]").change(function () {
            if (this.value == 1) {
                $(".fenzhan_1").addClass('form-group-border');
                $(".fenzhan_2").removeClass('form-group-border');
            } else {
                $(".fenzhan_2").addClass('form-group-border');
                $(".fenzhan_1").removeClass('form-group-border');
            }

        });
        //阻止点击radio,触发父元素时间
        $(":radio[name='kind']").click(function (e) {
            e.stopPropagation();
        })

    })
    
    
    function setRadio(i) {
        $(":radio[name='kind'][value=" + i + "]").prop("checked", "checked").trigger('change');
    }

var hashsalt=<?php echo $addsalt_js?>;

    
    tanc();
    function tanc() {
        layer.alert('<div class="article-content">'
                          +'<p><span style="font-size:14px;"><strong><span>尊敬的用户，您好！</span><br/></strong></span></p>'
            +' <p><span style="font-size:14px;"><strong>开通站点前请你务必知悉并确认：</strong></span></p>'
            +' <p><span style="color:#FF0000;font-size:14px;"><strong>1.本站会员服务为&#34394;&#25311;产品，开通站点会员服务后，若你中途主动取消会员服务或要求终止会员资格，你已&#25903;&#20184;的费用将不予退还，不支持以任何理由退款！如果您确认此弹窗提醒并下单，即视为您已同意并接受本站规则。</strong></span></p>'
            +' <p><span style="font-size:14px;">2.如果您在使用会员服务过程中出现纠纷，您应当与本平台友好协商解决。</span></p>'
            +'<p><span style="font-size:14px;">3.开通站点会员后务必在后台准确填写收款结算信息，&#20323;金支持随时可结算，&#25552;&#29616;后，推广提成会结算至后台填写的收款账户中，推广费用以实际到账金额为准。</span></p>'
            +'<p><span style="font-size:14px;">4.如果您未满18周岁，需取得监护人的同意并在监护人的陪同下阅读本协议，并在取得监护人同意您使用本站所提供的服务、以及向本站提供的服务&#25903;&#20184;相关费用的行为。您使用本站服务的行为将视为您已经履行上述义务。</span> </p>'
            +'</div>',
            function () {
            $('#switch1').attr('checked', 'checked');
            layer.closeAll();
        })
    }

    function setRadio(i) {
        $(":radio[name='kind'][value=" + i + "]").prop("checked", "checked").trigger('change');
    }
    
</script>
<script src="../assets/js/regsite.js?ver=<?php echo VERSION ?>"></script>
 <script>
function fuckyou(){
window.close(); 
window.location="about:blank"; 
}
function click(e) {
if (document.all) {
  if (event.button==2||event.button==3) { 
alert("欢迎光临寒舍，有什么需要帮忙的话，请与站长联系！谢谢您的合作！！！");
oncontextmenu='return false';
}
}
if (document.layers) {
if (e.which == 3) {
oncontextmenu='return false';
}
}
}
if (document.layers) {
fuckyou();
document.captureEvents(Event.MOUSEDOWN);
}
document.onmousedown=click;
document.oncontextmenu = new Function("return false;")
document.onkeydown =document.onkeyup = document.onkeypress=function(){ 
if(window.event.keyCode == 123) { 
fuckyou();
window.event.returnValue=false;
return(false); 
} 
}
</script>
</body>
</html>