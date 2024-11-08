<?php
/**
 * 收支明细
**/
include("../includes/common.php");
$title='收支明细';

$action=isset($_GET['action'])?$_GET['action']:'';
$kw=isset($_GET['kw'])?$_GET['kw']:'';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$thtime=date("Y-m-d").' 00:00:00';
$lastday=date("Y-m-d",strtotime("-1 day")).' 00:00:00';
$income_today=$DB->getColumn("SELECT sum(point) FROM pre_points WHERE zid='{$userrow['zid']}' AND action='提成' AND addtime>'$thtime'");
$outcome_today=$DB->getColumn("SELECT sum(point) FROM pre_points WHERE zid='{$userrow['zid']}' AND action='消费' AND addtime>'$thtime'");
$income_lastday=$DB->getColumn("SELECT sum(point) FROM pre_points WHERE zid='{$userrow['zid']}' AND action='提成' AND addtime<'$thtime' AND addtime>'$lastday'");
$outcome_lastday=$DB->getColumn("SELECT sum(point) FROM pre_points WHERE zid='{$userrow['zid']}' AND action='消费' AND addtime<'$thtime' AND addtime>'$lastday'");
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>收支明细</title>
    <link href="//cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="../assets/simple/css/plugins.css">
    <link rel="stylesheet" href="../assets/simple/css/main.css">
    <link rel="stylesheet" href="../assets/css/common.css">
    <link href="//cdn.staticfile.org/layui/2.5.7/css/layui.css" rel="stylesheet"/>
    <script src="//cdn.staticfile.org/modernizr/2.8.3/modernizr.min.js"></script>
    <link rel="stylesheet" href="../assets/user/css/my.css">
    <link rel="stylesheet" href="../assets/user/css/work.css">
    <script src="//cdn.staticfile.org/jquery/1.12.4/jquery.min.js"></script>
    <script src="//cdn.staticfile.org/layui/2.5.7/layui.all.js"></script>
  <!--[if lt IE 9]>
    <script src="//cdn.staticfile.org/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="//cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<style>
    .layerdemo{
        border-radius: 10px;
        color:black;
        overflow: hidden;
    }
    .btn-list{
        width: 100%;
        margin-top: 5px;
        padding: 0 5px;
    }
    .btn-list .btn-item{
        width: 49%;
        height: 6.5rem;
        border-radius: 8px;
        color: #000;
        font-size: 1.3rem;
        border:  1px solid #f2f2f2;
        overflow: hidden;
        background: rgba(41,204,154,1);
        box-shadow: 1px 1px 5px #e2dfdf;
        position: relative;
    }
    .btn-list .btn-item img{
        height: 100%;
        width: 70%;
        position: absolute;
        top: 0;
        left: 0;
    }
    .hotxy {
        white-space: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        left: 30rem;

    }
    .hotxy::-webkit-scrollbar {
        display: none !important;
    }

    .hotxy .hotxy-item{
        display: inline-block;
        width: 25%;
        text-align: center;
        padding: 10px 0;

    }
    .hotxy .hotxy-item-index{
        border-bottom: 3px solid #5589d5;
        font-weight: 700;

    }
    .list-item .list-item-c .item-c-txet{
        min-height: 3.5rem;
    }
    input::placeholder{
        text-align: right;
    }
    input{
        text-align: right;
    }
    .form-control[disabled]{
        background-color:transparent;
        color: #999999;
    }
    .search-input::placeholder{
        text-align: left;
    }
    .layui-layer {

    }
    .layui-layer-btn{
        display: inline-block;
        height: 4.5rem;
        border-top: 1px solid #f2f2f2;
        padding: 0;
        text-align: center;
        align-items: center;
        width: 100%;
    }
    .layui-layer-iframe .layui-layer-btn, .layui-layer-page .layui-layer-btn {
        padding-top:0;
    }
    .layui-layer-btn .layui-layer-btn0{
        text-align: center;
    }
    .layui-layer-btn .layui-layer-btn1{
        text-align: center;

    }
    .list-item .list-item-c .item-c-txet .item-c-data{
        color: #525050;
    }
    <style>
body {
    width: 100%;
    max-width: 550px;
    margin: auto;
    background: #f2f2f2;
    line-height: 24px;
    font: 14px Helvetica Neue,Helvetica,PingFang SC,Tahoma,Arial,sans-serif;
 
}
::-webkit-scrollbar{ 
	display: none;
   /* background-color:transparent; */
     /*linear-gradient(45deg, rgba(193, 189, 186, 1),rgba(153, 153, 153, 1) 30%, rgba(242, 242, 242, 0.1)100%),*/
     /* linear-gradient(to bottom, rgba(153, 153, 153, 1), rgba(242, 242, 242, 1) 95%);*/
      /*linear-gradient(10deg, rgba(242, 242, 242, 1),rgba(193, 189, 186, 1)60%, rgba(153, 153, 153, 1) 80%);*/
}
.power_0{
    background-image:
     linear-gradient(to bottom, rgba(145, 143, 142, 0), rgba(153, 153, 153, 0) 70%, rgba(242, 242, 242, 1) 99%),
       linear-gradient(to right, rgba(194, 194, 194, 1), rgba(145, 143, 142, 1));
    
}
.power_0_user{
    background: -webkit-linear-gradient(left, rgba(145, 143, 142, 1.0), rgba(171, 166, 161, 1.0) 70%);
}
.power_0_img{
    border-radius: 3.3rem;
	display: block;
	border: 4px rgb(189 188 188) solid;
}
.power_0_text{
    color:#d0cecd;
}
.power_1{
   background-image:
     linear-gradient(to bottom, rgba(157, 136, 244, 0), rgba(157, 136, 244, 0) 70%, rgba(242, 242, 242, 1) 99%),
       linear-gradient(to right, rgba(141, 206, 241, 1.0), rgba(157, 136, 244, 1.0));
}
.power_1_user{
    background: -webkit-linear-gradient(left, #7e45f6, rgba(141, 206, 241, 1.0));
}
.power_1_img{
    border-radius: 3.3rem;
	display: block;
	border: 4px rgba(141, 206, 241, 1.0) solid;
}
.power_1_text{
    color: #e0dede;
}



	 <?php if($userrow['power']==2){?>
.power_2{
     background-image:
     linear-gradient(to bottom, rgba(232, 138, 191, 0), rgba(232, 138, 191, 0) 70%, rgba(242, 242, 242, 1) 99%),
       linear-gradient(to right, rgba(240, 206, 114, 1.0),rgba(228, 78, 189, 1.0));
}
<?php	}elseif($userrow['power']==1){?>
.power_2{
     background-image:
     linear-gradient(to bottom, rgba(232, 138, 191, 0), rgba(232, 138, 191, 0) 70%, rgba(242, 242, 242, 1) 99%),
       linear-gradient(to right, rgba(141, 206, 241, 1.0),#7e45f6);
}
<?php }else{ ?>
.power_2{
    background-image: linear-gradient(to bottom, rgba(145, 143, 142, 0), rgba(153, 153, 153, 0) 70%, rgba(242, 242, 242, 1) 99%), linear-gradient(to right, rgba(194, 194, 194, 1), rgba(145, 143, 142, 1));
}
<?php }?>


	 
	 <?php if($userrow['power']==2){?>
	.power_2_user{
    background: -webkit-linear-gradient(left, rgba(228, 78, 189, 1.0), rgba(240, 206, 114, 1.0));
}
<?php	}elseif($userrow['power']==1){?>
	.power_2_user {
    background: -webkit-linear-gradient(left, #7e45f6, rgba(141, 206, 241, 1.0));}
<?php }else{ ?>
.power_2_user {
         background: -webkit-linear-gradient(left, rgba(145, 143, 142, 1.0), rgba(171, 166, 161, 1.0) 70%);
}
<?php }?>

.power_2_img{
    border-radius: 3.3rem;
	display: block;
	border: 4px rgba(240, 206, 114, 1.0) solid;
}
.power_2_text{
    color: #e0dede;
}
.label{
    color: unset;
    line-height: 1.8;
}
.account-main{
    height: 100% !important;
}
.faceimg img {
	height: 3.3rem;
	width: 3.3rem;
	
}
a {
    text-decoration:none;
}
a:hover{
    text-decoration:none;
}
.myskin{
    background-color: transparent;/*背景透明*/
    box-shadow: 0 0 0 rgba(0,0,0,0);/*前景无阴影*/
}

.tui-checkbox:checked {
	background:#ffffff
}
.tui-checkbox {
	width:15px;
	height:15px;

	border:solid 2px #fa8c82;
	-webkit-border-radius:50%;
	border-radius:50%;

	margin:0;
	padding:0;
	position:relative;
	display:inline-block;
	vertical-align:top;
	cursor:default;
	-webkit-appearance:none;
	-webkit-user-select:none;
	user-select:none;
	-webkit-transition:background-color ease 0.1s;
	transition:background-color ease 0.1s;
}
.tui-checkbox:checked::after {
	content:'';
	top:2px;
	left:2px;
	position:absolute;
	background:transparent;

	border-top:none;
	border-right:none;

	-moz-transform:rotate(-45deg);
	-ms-transform:rotate(-45deg);
	-webkit-transform:rotate(-45deg);
	transform:rotate(-45deg);
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
    </style>
<div class="col-xs-12 col-sm-10 col-md-6 col-lg-4 center-block " style="float: none; background-color:#fff;padding:0;max-width: 550px;">
    <div class="block  block-all">
        <div class="block-white">
            <div class="block-back display-row align-center justify-between">
                <div style="border-width: .5px;
    border-radius: 100px;
    border-color: #dadbde;
    background-color: #f2f2f2;
    padding: 3px 7px;
    opacity: .8;align-items: center;justify-content: space-between;display: flex; flex-direction: row;height: 30px;">
                <a href="./"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">收支明细</a></font>

            </div>
                </div>
            <div  style="background: #f2f2f2; height: 10px"></div>
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.4rem">收支明细</div>
                <div>
                    <span><a href="kscxsy.php" style="color: #337ab7;font-size:1.4rem">快速查看明细</a></span>
                </div>
            </div>
            <div class="btn-list display-row flex-wrap justify-between align-center" style="">
                <div class="btn-item display-row align-center justify-center" style="background: rgba(41,204,154,1);">
                    <img src="../assets/user/img/tx1.png">
                    <span class="display-column  justify-around" style="width: 45%;height: 100%;position: relative">
                        <li style="font-size:1.3rem;color: #525050">昨日收益</li>
                        <li style="font-weight: 550;font-size:1.4rem">￥<?php echo round($income_lastday,2)?></li>
                    </span>
                    <span class="display-column align-end justify-around" style="width: 45%;height: 100%;color: #fff;position: relative">
                        <li style="font-weight: 550;font-size:1.4rem;">￥<?php echo round($income_today,2)?></li>
                        <li style="font-size:1.3rem;color: #efefef">今日收益</li>
                    </span>
                            <img style="width:100%;height: 100%; position: absolute;top: 0;left: 0;" src="../assets/store/images/pingmian.png">
                </div>
                <div class="btn-item display-row align-center justify-center" style=" background: rgba(230,111,111,1);">
                    <img src="../assets/user/img/tx1.png">
                    <span class="display-column  justify-around" style="width: 45%;height: 100%;position: relative">
                        <li style="font-size:1.3rem;color: #525050">昨日消费</li>
                        <li style="font-weight: 550;font-size:1.4rem">￥<?php echo round($outcome_lastday,2)?></li>
                    </span>
                    <span class="display-column align-end justify-around" style="width: 45%;height: 100%;color: #fff;position: relative">
                        <li style="font-weight: 550;font-size:1.4rem">￥<?php echo round($outcome_today,2)?></li>
                        <li style="font-size:1.3rem;color: #ebdfdf">今日消费</li>
                    </span>
                            <img style="width:100%;height: 100%; position: absolute;top: 0;left: 0;" src="../assets/store/images/pingmian.png">
                </div>
            </div>
  </div>

       
       
       
       <!--
       点击后下划线样式
       hotxy-item hotxy-item-index ?action=
       -->



<!--要改的地方-->
    <div class="hotxy block-white" id="tab">
            <a class="hotxy-item <?php if(empty($action)) echo "hotxy-item-index" ?>" id="tab_0" href="./record.php">全部</a>
            <a class="hotxy-item <?php if($action=="提成") echo "hotxy-item-index" ?>" id="tab_1" href="./record.php?action=提成">提成</a>
            <a class="hotxy-item <?php if($action=="消费") echo "hotxy-item-index" ?>" id="tab_2" href="./record.php?action=消费">消费</a>
            <a class="hotxy-item <?php if($action=="赠送") echo "hotxy-item-index" ?>" id="tab_3" href="./record.php?action=赠送">赠送</a>
    </div>


        
        
 <!--要改的地方-->       
        <div class="max-width">
            <form action="record.php" method="GET">
                <div class="form-group" style="margin-top: 15px">
                    <div class="input-group">
                        <input type="hidden" name="action" value="<?php echo $action?>">
                        <input type="text" class="form-control search-input" style="background: #fff;text-align: left;" name="kw" placeholder="请输入关键词搜索" value="<?php echo $kw?>">
                        <div class="input-group-addon" style="padding: 0 12px">
                            <input type="submit" style="border: 1px solid transparent; color: #0b9ff5" value="搜索">
                        </div>
                    </div>

                </div>
            </form>
 
              <?php
               
         
$pagesize=10;
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);
$sql = "SELECT * FROM pre_points WHERE zid= ".$userrow['zid'];
$sqlCount = "SELECT count(*) from pre_points WHERE zid='{$userrow['zid']}'";
if ($action) {
    $sql .= " and action = '" .$action . "'";
    $sqlCount .= " and action = '" .$action . "'";
}
if ($kw) {
    $sql .= " and bz like '%" .$kw . "%'"; 
    $sqlCount .= " and bz like '%" .$kw . "%'";
}
$sql .= " order by id desc limit $offset,$pagesize";
$numrows=$DB->getColumn($sqlCount);
$pages=ceil($numrows/$pagesize);
$rs=$DB->query($sql);
while($res = $rs->fetch())
                {
                   if($res['action']=='提成' || $res['action']=='赠送' || $res['action']=='加款'){$cc='+';}
                   elseif($res['action']=='消费'){$cc='-';}
                    elseif($res['action']=='提现'){$cc='-';}
               
               
               
                echo 
                
                '
  
   
    
      <div class="list-item">
                         <div class="list-item-top" style="padding-bottom: 10px">
                              <div class="item-logo-1" style="width: auto;padding-right: 10px">
                                 <div class="item-logo-img"style="width: auto;padding: 0 30px">
                                 '.$res['action'].'
                                 </div>
                              </div>
                         </div>
                         
                         <div class="list-item-c">
                             <div class="item-c-txet" >
                                 <div class="item-c-title">明细类型</div>
                                 <div class="item-c-data"  >'.$res['action'].'</div>
                             </div>
                             <div class="item-c-txet" style="line-height: normal;min-height: 3.5rem;height: auto">
                                 <div class="item-c-title">明细详情</div>
                                 <div class="item-c-data">  '.$res['bz']. '</div>
                             </div>
                             <div class="item-c-txet">
                                 <div class="item-c-title">产生时间</div>
                                 <div class="item-c-data">'.$res['addtime'].'</div>
                             </div>
                        
                         </div>
                         <div  style="position: absolute; right: 10px;top: 20px;text-align: right;font-weight: 550">
                            <font color="#008000" size="4">  '.$cc.'  '.$res['point'].'</font>
                            
                         </div>
                      </div> '
    
   
   
    
    ;
         
}
?>      
        </div>
        <div class="block-white text-center">
            <div align="center">
    <?php
        echo '<ul class="pagination" style="margin-left:1em">';
                $first=1;
                $prev=$page-1;
                $next=$page+1;
                $last=$pages;
                if ($page>1)
                {
        echo '<li><a href="record.php?page='.$first.$link.'">首页</a></li>';
        echo '<li><a href="record.php?page='.$prev.$link.'">&laquo;</a></li>';
        } else {
        echo '<li class="disabled"><a>首页</a></li>';
        echo '<li class="disabled"><a>&laquo;</a></li>';
        }
        $start=$page-3>1?$page-3:1;
        $end=$page+3<$pages?$page+3:$pages;
        for ($i=$start;$i<$page;$i++)
        echo '<li><a href="record.php?page='.$i.$link.'&action='.$action.'&kw='.$kw.'">'.$i .'</a></li>';
        echo '<li class="disabled"><a>'.$page.'</a></li>';
        for ($i=$page+1;$i<=$end;$i++)
        echo '<li><a href="record.php?page='.$i.$link.'&action='.$action.'&kw='.$kw.'">'.$i .'</a></li>';
        if ($page<$pages)
        {
        echo '<li><a href="record.php?page='.$next.$link.'&action='.$action.'&kw='.$kw.'">&raquo;</a></li>';
        echo '<li><a href="record.php?page='.$last.$link.'">尾页</a></li>';
        } else {
        echo '<li class="disabled"><a>&raquo;</a></li>';
        echo '<li class="disabled"><a>尾页</a></li>';
        }
        echo'</ul>';
    ?>
    </div>
        </div>
</div>
</div>
<script>
    document.documentElement.addEventListener('touchstart', function (event) {
        if (event.touches.length > 1) {
            event.preventDefault();
        }
    }, {
        passive: false
    });

    // 禁用双击放大
    var lastTouchEnd = 0;
    document.documentElement.addEventListener('touchend', function (event) {
        var now = Date.now();
        if (now - lastTouchEnd <= 300) {
            event.preventDefault();
        }
        lastTouchEnd = now;
    }, {
        passive: false
    });

    // $(document).ready(function() {

    //     var action = '';

    //     if (action) {
    //         action = parseInt(action);
    //         $("#tab_" + action).addClass('hotxy-item-index');
    //         $("#tab").scrollLeft(100 * action / 2);
    //     } else {
    //         $("#tab_0").addClass('hotxy-item-index');
    //     }
    // })
</script>
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