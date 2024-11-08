
<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>官方社群</title>
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
    .layui-carousel, .layui-carousel>[carousel-item]>* {
        background-color:transparent;
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
    .cccca {
    padding-top: 10px;
    }
</style>

<?php
$is_defend=true;
require '../includes/common.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$title = '官方社群';

?>

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
                <a href="javascript:history.back()"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">官方社群</a></font>

            </div>
                </div>
            <div  style="background: #f2f2f2; height: 10px"></div>
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.4rem">官方社群说明</div>
            </div>
            
<?php  if($userrow['power']==0 || $userrow['power']==1){?>
            <div style="font-size: 1.4rem;color: #ff0000;padding: 5px 10px">
                <p>
                    官方社群仅对顶级合伙人开放<br></p>
            </div>
            
        </div>
            <div  style="background: #f2f2f2; height: 10px"></div>
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.4rem">社群列表</div>
            </div>
       <div class="layui-carousel" id="test1" lay-filter="test1" style="margin: 1px auto">
            <div class="cccca">
<?php  if( $userrow['power']==0){?>
                    <a href="regsite.php" class="btn btn-primary btn-block text-white"  style="display:block;font-size: 13px;" >
<?php }?>
<?php  if( $userrow['power']==1){?>
                    <a href="upsite.php" class="btn btn-primary btn-block text-white"  style="display:block;font-size: 13px;" >
<?php }?>
                    您当前权限不足请先升级<br>
                    升级后方可查看并加入社群<br>
                    【点我前往升级】</a>
    </div>
	</div>
    </div>
</div>
<?php }?>
<?php  if( $userrow['power']==2){?>
            <div style="font-size: 1.3rem;color: #999999;padding: 5px 10px">
                <p>
                    官方社群仅对顶级合伙人开放<br>
                    不得将官方群透露给其他人<br>
                    官方群禁止邀请普通用户及分站站长加入<br>
                    若造成人员流失及损失的本平台概不负责</p>
            </div>
            
        </div>
            <div  style="background: #f2f2f2; height: 10px"></div>
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.4rem">官方群列表</div>
            </div>
       <div class="layui-carousel" id="test1" lay-filter="test1" style="margin: 1px auto">
            <div class="cccca">
                <div style="padding: 10px 15px;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.3rem;margin-bottom: 10px;">
                    <div style="padding: 8px 0;font-size: 1.4rem;">ＱＱ通知1群(已满)：<a>951921984
                    <a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="951921984">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    </a><a style="color: #2894FF;" href="http://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=xoRCbme_KFjl4RC-BcqCfqhTlDnXo738&authKey=jK26INvYe%2Fo%2FQuJOy11oKw135hqiOkJFJvt37AH9tU8XdSVexhTru96iAWReSti0&noverify=0&group_code=951921984" target="_blank">【点我加入】</a></div>
                </div>
                <div style="padding: 10px 15px;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.3rem;margin-bottom: 10px;">
                    <div style="padding: 8px 0;font-size: 1.4rem;">ＱＱ通知2群(未满)：<a>943777004
                    <a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="943777004">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    </a><a style="color: #2894FF;" href="http://qm.qq.com/cgi-bin/qm/qr?_wv=1027&k=fyU3aMU6VkvDIpZmwr9qWPGLkyWlBFDj&authKey=3RxSBfT9JUUqy6zd6RPhQtukU%2FDslJJcqtbDt%2F1u0bwSmaGgFk30TT2gCaulciPK&noverify=0&group_code=943777004" target="_blank">【点我加入】</a></div>
                </div>
                <div style="padding: 10px 15px;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.3rem;margin-bottom: 10px;">
                    <div style="padding: 8px 0;font-size: 1.4rem;">ＱＱ交流群：<a>暂未开放
                    <a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="暂未开放">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    </a></div>
                </div>
                <div style="padding: 10px 15px;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.3rem;margin-bottom: 10px;">
                    <div style="padding: 8px 0;font-size: 1.4rem;">微信交流群：<a>暂未开放
                    <a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="暂未开放">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    </a></div>
                </div>
    </div>
    </div>
</div>
    
            <div  style="background: #f2f2f2; height: 10px"></div>
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.4rem">客服联系方式</div>
            </div>
       <div class="layui-carousel" id="test1" lay-filter="test1" style="margin: 1px auto">
            <div class="cccca">
                <div style="padding: 10px 15px;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.3rem;margin-bottom: 10px;">
                <div style="padding: 8px 0;font-size: 1.4rem;">ＱＱ客服①：<a>3334023202
                    <a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="3334023202">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    </a>
                </div>
                </div>
                <div style="padding: 10px 15px;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.3rem;margin-bottom: 10px;">
                <div style="padding: 8px 0;font-size: 1.4rem;">ＱＱ客服②：<a>2441933668
                    <a  style="width: 100%;" href="javascript:;" id="copy-btn" data-clipboard-text="2441933668">
                    <img style="width:20px;height:30px;padding-left:0px" src="../assets/store/img/fuzhi.svg" />
                        </a>
                    </a>
                </div>
                </div>
    </div>
	</div>
    </div>
</div>
<?php }?>
<script src="//cdn.staticfile.org/clipboard.js/1.7.1/clipboard.min.js"></script>
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
$(document).ready(function(){
var clipboard = new Clipboard('#copy-btn');
clipboard.on('success', function (e) {
	layer.msg('复制成功');
});
clipboard.on('error', function (e) {
	layer.msg('复制失败，请长按链接后手动复制');
});

$("#recreate_url").click(function(){
	var self = $(this);
	if (self.attr("data-lock") === "true") return;
	else self.attr("data-lock", "true");
	var ii = layer.load(1, {shade: [0.1, '#fff']});
	$.get("ajax_user.php?act=create_url&force=1", function(data) {
		layer.close(ii);
		if(data.code == 0){
			layer.msg('生成链接成功');
			$("#copy-btn").html(data.url);
			$("#copy-btn").attr('data-clipboard-text',data.url);
		}else{
			layer.alert(data.msg);
		}
		self.attr("data-lock", "false");
	}, 'json');
});
});
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