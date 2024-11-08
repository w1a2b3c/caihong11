
<?php
/**
 * 充值余额
**/
$is_defend=true;
include("../includes/common.php");
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
if($islogin2==1 && $userrow['power']>0){
}
$title='充值余额';
//获取用户真实IP
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
        $ip = getenv("HTTP_CLIENT_IP");
    else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
        $ip = getenv("HTTP_X_FORWARDED_FOR");
    else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
        $ip = getenv("REMOTE_ADDR");
    else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
        $ip = $_SERVER['REMOTE_ADDR'];
    else
        $ip = "unknown";

    $ips=$DB->getRow("select * from shua_ip where id=1 limit 1");
    
if(strpos($ips['ips'],$ip) !== false){ 
 header("location:/404.html");
}
?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
  <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>余额充值</title>
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
<body><style>
    img.logo {
        width: 1.8rem;


    }
    .money-input{

        width: 90%;
        background:#f2f2f2;
        border-radius: 50px;

    }
    .pay_list{
        font-size: 1.4rem;
        width: 100%;
        height: 3rem;
        border-radius: 40px;
        background: #fff;
        border:  1px solid #b0b0b0;
        margin-right: 0%;
    }
    .pay-button{
        width: 25%;
        height: 80%;
        border-radius: 50px;

        background: #fff;
        margin-left: 10px;
        font-size: 1.2rem;
    }
    /*偶数even 奇数 odd*/
    .mx-list .mx-list-item:nth-child(odd){
        background: #fafafa;
    }
    .li-list-item{
        padding:3px 0;
        justify-content: start;

    }
    .li-list-item .item-title{
        padding-right: 10px;
        color: #999999;
        font-size: 1.3rem;
        width: auto;
    }
    .li-list-item .item-info{
        flex-grow:2;
        width: auto;
        color: #0a0c0d;
        font-size: 1.3rem;
    }
    .li-list-item .item-right{
        flex-grow:2;
        width: auto;
        text-align: right;

    }
    .left-title{

    }
    .left-title::before{
        height: 100%;
        width: 5px;
        border-radius: 15px;
        background: #f99144;
    }
    .layerdemo{
        border-radius: 10px;
        color:black;
        overflow: hidden;
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
            <font><a href="">余额充值</a></font>

            </div>
                </div>
        <div class="form-group form-group-transparent" style="background: #f2f2f2;">
            <div class="input-group" style="width:100%">
                <div class="input-group-addon" style="color:#969494;font-size:13px;">
                    余额充值                </div>
                <div></div>
            </div>
        </div>
        <div style="width: 100%;background: #fff;border-radius: 15px" class="display-column align-center">
            <div style="width: 95%;padding-top: 10px" class="display-row align-center justify-between">

                <font style="font-weight: 550;font-size: 1.3rem;letter-spacing:.05rem;">我的余额</font>
                <font style="font-size: 1.1rem;color: #999999">（单位 : 元）</font>
                <div style="flex-grow:2;text-align:right ">

                    <a style="font-size:1.2rem;color: #999999;"
                       href="javascript:layer.alert('提示：充值的余额只能用于消费，无法提现')">
                        <i class="fa fa-question-circle-o" style="font-size:1.5rem;"></i> 充值规则
                    </a>
                </div>
            </div>
            <div style="width: 90%;height: 7rem;font-size: 2.4rem;letter-spacing:.05rem;color: #FF6133;font-weight: 450" class="display-row align-center">
                <?php echo $userrow['rmb']?>           </div>
            <div style="height:5.5rem;width: 100%;  font-size: 1.3rem;" class="display-row align-center justify-center form-group-border-top">
                <div class="money-input display-row align-center" style=" height: 80%;">
                    <font size="2" style="width:10%;text-align: center;color: #000 ">￥</font>
                    <input type="text" class="form-control" name="value" autocomplete="off" placeholder="请输入充值金额"   style="width:90%;height:100%;border: 0px solid transparent;background: transparent;padding-top: 3px" value="" />
                </div>
            </div>
            
            <div class="display-row flex-nowrap align-center" style="width: 90%;height: 5rem">
                
                <?php
if($conf['wxpay_api'])echo '<button type="submit" class="pay_list" id="buy_wxpay"><img src="../assets/img/wxpay.png" class="logo">微信支付</button>&nbsp;';
if($conf['alipay_api'])echo '<button type="submit" class="pay_list" id="buy_alipay"><img src="../assets/img/alipay.png" class="logo">支付宝</button>&nbsp;';
if($conf['qqpay_api'])echo '<button type="submit" class="pay_list" id="buy_qqpay"><img src="../assets/img/qqpay.png" class="logo">QQ钱包</button>&nbsp;';
?>
   
            </div>
              </div>
            <div style="padding: 12px 20px;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.15rem">
               <a style="color: #ff0000;">* 温馨提示：</a>
               支付时出现支付异常，可进行分批小额充值，也可选择下面的转账充值，支持微信/支付宝/花呗/信用卡。<br><br>
               <div align="center" style="font-size: 1.3rem">如果无法微信支付<br>
               可以在微信公众号搜QQ钱包<br>
               然后把微信余额转到QQ里<br>
               <a href="./wxzy.php" style="color: #980000;">点我看详细微信余额转QQ方法教程</a></div>
            </div>
<style>
    .tcbox{
     position: relative;
    display: flex;
    height: 30px;
    align-items: center;
    flex-direction: row;
    width: 90%;
    margin: 10px auto;
    }
    .boxn1{
     width: 50%;
     display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    }
    .boxn2{
     width: 100%;
     display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: flex-start;
    }
    .n1{
        text-align: center;
        height: 30px;
        background: rgb(255, 242, 204);
        width: 98%;
        border-radius: 10px 10px 10px 10px;
        font-weight: 600;
        line-height: 30px;
    }
    .n2{
        text-align: center;
        height: 30px;
        background: rgb(255, 242, 204);
        width: 99%;
        border-radius: 10px 10px 10px 10px;
        font-weight: 600;
        line-height: 30px;
    }
    .pad_right{ padding-right:2em;}
 
#scroll_div {height:26px;overflow: hidden;white-space: nowrap;width: 96%;
    margin: 0 auto; font-weight:600;color:red;}
#scroll_begin,#scroll_end {display: inline; font-weight:600;color:red;}
.tanc1{
    transition-duration: 300ms;
    transition-timing-function: ease-out;
    z-index: 99;
    position: fixed;
    align-items: center;
    justify-content: center;
    left: var(--window-left);
    right: var(--window-right);
    top: 0px;
    bottom: 0px;
}
.tanc2{
    width: 100%;
    max-width: 550px;
    background: #0000004d;
    transition-duration: 300ms;
    transition-timing-function: ease-out;
    z-index: 999999999;
    position: fixed;
    display: flex;
    align-items: center;
    justify-content: center;
    left: var(--window-left);
    right: var(--window-right);
    top: 0px;
    bottom: 0px;
}
.tanc3{
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
    flex-grow: 0;
    flex-basis: auto;
    align-items: stretch;
    align-content: flex-start;
}
.tanc4{
    width: 315px;
    padding: 10px;
    background-color: #fff;
    border-radius: 20px;
}
.tanc5{
    display: flex;
    align-items: center;
    justify-content: space-around;
        width: 100%;

}
.tanc51{
        display: flex;
    flex-direction: column;
    align-items: flex-start;
        width: 100%;
}
.z1{
           display: flex;
    width: 50%;
    margin-top: 1px;
    justify-content: space-between;
}

.z2{
    width: 80%;
    height: 32px;
    text-align: center;
    line-height: 32px;
    font-size: 12px;
    font-weight: 700;
    background: #cfe2f3;
    border-radius: 50px;
}
</style>

        <div class="tcbox">
            <div class="boxn2">
                <div onclick="hongbao()" class="n2" style="color: #4c1130;font-size: 11px;">
                    <img src="../assets/img/hongbao.jpg" class="logo">【免费领取】支付宝每日红包,本站充值可抵扣使用</div>
        </div>
            </div>
                </div>

                   <div class="tanc1" id="hongbao" style="display:none;">
             <div class="tanc2">
                  <div class="tanc3">
                       <div class="tanc4">
                            <div align="center">
                           <div class="tanc5">
                                <div class="tanc51">
                                    <div align="justify">
                                        <div class="z1">
                                    </div><br>
                                    <a style="font-size: 11px;">1.截图保存相册使用支付宝扫码领取红包<br>2.在平台使用支付宝充值余额即可抵扣现金使用<br>3.如支付宝充值不能抵扣 可使用下面的转账充值即可<br>4.金额随机无上限，每天可领一次<br>
                                    <hr>
                                    <div align="center">
                                       <img style="width:70%;" src="../assets/img/hongbao.jpg">
                                       <hr>
                              <div class="z2" onclick="closetc()"> 关闭 </div></div>
                                </div>
                                    </div>
                                         </div>
                                            </div>
                       </div>
                  </div>
             </div>
        </div>
        
            <div style="padding: 12px 20px;;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.45rem">
               <div align="center"><a>上面充值不了的 请使用下面转账充值</a></div>
               </div>

        <div class="tcbox">
            <div class="boxn1">
                <div onclick="zzcz()" class="n1" style="font-size: 13px;">
                    <img src="../assets/img/rmb.png" class="logo">转账充值</div>
        </div>
                    <div class="boxn1">
                <div onclick="zzsm()" class="n1" style="font-size: 13px;">转账充值说明</div>
        </div>
            </div>
        <div class="tcbox">
                    <div class="boxn2">
                <a href="./workorder.php" class="n1" style="color: #ff0000;font-size: 13px;">→ 转账充值10分钟未到账点我进行反馈 ←</a>
        </div>
            </div>

                   <div class="tanc1" id="zzcz" style="display:none;">
             <div class="tanc2">
                  <div class="tanc3">
                       <div class="tanc4">
                        <div align="center">
                           <div class="tanc5">
                                <div class="tanc51">
                                    <div align="center">
                                        <div class="z1">
                                    </div><br>
                                       <img style="width:100%;" src="../assets/img/33588.jpg">
                                    <hr>
                                       <b style="color: #ff0000;">充值1元起充，无上限</b>
                                    <hr>
                                       <img style="width:70%;" src="../assets/img/zzcz.png">
                                       <hr>
                              <div class="z2" onclick="closetc()"> 关闭 </div>
                                </div>
                                    </div>
                                         </div>
                                            </div>
                       </div>
                  </div>
             </div>
        </div>
        
                   <div class="tanc1" id="zzsm" style="display:none;">
             <div class="tanc2">
                  <div class="tanc3">
                       <div class="tanc4">
                            <div align="center">
                           <div class="tanc5">
                                <div class="tanc51">
                                    <div align="center">
                                        <div class="z1">

                                    </div><br>
                                    <div align="left"><a style="font-size: 13px;"><b>转账付款说明：</b><br>截图用微信/支付宝扫码，无需联系人工客服，付完款将在10分钟内到账，若超过10分钟未到账或者忘记备注UID可联系客服处理！<br></a>
                                    </div>
                                       <hr>
                                       <img style="width:80%;" src="../assets/img/beizhu.jpg">
                                       <img style="width:80%;" src="../assets/img/uid.jpg">
                                       <hr>
                                       <div class="z2" onclick="closetc()"> 关闭 </div>
                                </div>
                                    </div>
                                         </div>
                                            </div>
                       </div>
                  </div>
             </div>
        </div>
<script type="text/javascript">
function zzcz(){
    $("#zzcz").show();
}
function hongbao(){
    $("#hongbao").show();
}
function zzsm(){
    $("#zzsm").show();
}
function closetc(){
    $(".tanc1").hide();
}
        var speed = 50;
        var MyMar = null;
        var scroll_begin = document.getElementById("scroll_begin");
        var scroll_end = document.getElementById("scroll_end");
        var scroll_div = document.getElementById("scroll_div");
        scroll_end.innerHTML = scroll_begin.innerHTML;
 
        function Marquee() {
          if (scroll_end.offsetWidth - scroll_div.scrollLeft <= 0)
            scroll_div.scrollLeft -= scroll_begin.offsetWidth;
          else
            scroll_div.scrollLeft++;
          // console.log(scroll_end.offsetWidth - scroll_div.scrollLeft <= 0)
        }
        MyMar = setInterval(Marquee, speed);
        scroll_div.onmousedown = function () {
          clearInterval(MyMar);
        }
        scroll_div.onmouseover = function () {
          clearInterval(MyMar);
        }
        scroll_div.onmouseout = function () {
          MyMar = setInterval(Marquee, speed);
        }
      
</script>
        
<?php if(!empty($conf['fenzhan_gift'])){
$rules = explode('|',$conf['fenzhan_gift']);
//$i=0;
?>
<?php foreach($rules as $rule){
$arr = explode(':',$rule);
echo $i++.'<div style="background: #f2f2f2; height: 10px"></div>
           <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.3rem">充值赠送活动</div>
                <div class="my-cell-title-r  display-row  align-center">
                </div>
         
                </div>
                       <div style="padding: 12px 5px;;background: #f7f7f7;border-radius: 5px;width: 100%;font-size: 1.4rem">
                           
                    <div align="center">
                        
                     <div class="li-list-item">
                        <div class="item-info ellipsis1">充值平台余额</div>
                        <div class="item-info ellipsis1">≥￥'.$arr[0].'元</div>
                        <div class="item-info ellipsis1">单笔赠送'.$arr[1].'%</div>
                    </div>
                    <br>
                        <a style="font-size: 1.3rem">充值'.$arr[0].'元赠送'.$arr[1].'元<br>多充多送无上限，自动到账</a>
                        </div>
                            </div>
                                </div>';
}
?>
<?php }?>
                                
        <div style="background: #f2f2f2; height: 10px"></div>
        
        <div class="my-cell block-white form-group-border-bottom" style="margin-bottom: 0px;border-radius: 0;padding: 2px 10px">
            <div class="my-cell-title  ">
                <div class="my-cell-title-l left-title" style="font-size:1.3rem">充值记录</div>
                
            </div>
        </div>
               <tbody>
<?php
$flag=false;
$rs=$DB->query("SELECT * FROM pre_points WHERE zid='{$userrow['zid']}' AND action='充值' ORDER BY id DESC LIMIT 10");
while($res = $rs->fetch())
{
$flag=true;
echo '

<div class="mx-list" style="width: 100%;">
            <div class="mx-list-item" style="width: 100%;font-size: 1.4rem;">
                         <div style="width: 100%;padding: 12px 10px;position: relative">
                            <div class="li-list-item">
                                <div class="item-title">充值金额</div>
                                <div class="item-info">
                                   '.$res['point'].'
                                </div>
    
                               <div class="item-right"> '.$res['addtime'].'</div>
                            </div>
                            
                            <div class="li-list-item">
                                <div class="item-title">充值状态</div>
                                <div class="item-info">
                                    已完成
                                </div>
    
                                <div class="item-right"></div>
                            </div>
                         </div>
                    </div>     </div>';
        }
    ?>
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
    function dopay(type) {
        var value = $("input[name='value']").val();
        if (value == '' || value == 0) {
            layer.alert('充值金额不能为空');
            return false;
        }
        $.get("ajax_user.php?act=recharge&type=" + type + "&value=" + value, function (data) {
            if (data.code == 0) {
                window.location.href = '../other/submit.php?type=' + type + '&orderid=' + data.trade_no;
            } else {
                layer.alert(data.msg);
            }
        }, 'json');
    }

    $(document).ready(function () {
        $("#buy_alipay").click(function () {
            dopay('alipay')
        });
        $("#buy_qqpay").click(function () {
            dopay('qqpay')
        });
        $("#buy_wxpay").click(function () {
            dopay('wxpay')
        });
        $("#usekm").click(function () {
            var km = $("input[name='km']").val();
            $.ajax({
                type: "POST",
                url: "ajax_user.php?act=usekm",
                data: {km: km},
                dataType: 'json',
                async: true,
                success: function (data) {
                    if (data.code == 0) {
                        layer.alert(data.msg, {icon: 1}, function () {
                            window.location.reload()
                        });
                    } else {
                        layer.alert(data.msg, {icon: 2});
                    }
                }
            });
        });
    })
</script>



<?php include './foot.php';?>
<script>
function dopay(type){
	var value=$("input[name='value']").val();
	if(value=='' || value==0){layer.alert('充值金额不能为空');return false;}
	$.get("ajax_user.php?act=recharge&type="+type+"&value="+value, function(data) {
		if(data.code == 0){
			window.location.href='../other/submit.php?type='+type+'&orderid='+data.trade_no;
		}else{
			layer.alert(data.msg);
		}
	}, 'json');
}
$(document).ready(function(){
$("#buy_alipay").click(function(){
	dopay('alipay')
});
$("#buy_qqpay").click(function(){
	dopay('qqpay')
});
$("#buy_wxpay").click(function(){
	dopay('wxpay')
});
$("#usekm").click(function(){
	var km = $("input[name='km']").val();
	$.ajax({
		type : "POST",
		url : "ajax_user.php?act=usekm",
		data : {km:km},
		dataType : 'json',
		async: true,
		success : function(data) {
			if(data.code == 0){
				layer.alert(data.msg, {icon:1}, function(){ window.location.reload() });
			}else{
				layer.alert(data.msg, {icon:2});
			}
		}
	});
});
})
layer.open({
    type:1,
    title: false,
    area: '33rem',
    shade: 0.5,
    skin: "layerdemo",
    shadeClose: false,
    closeBtn: 0,
    offset: '15%',
    content:
        '<div class="display-column  align-center"  style="position: relative" >'+
        '<div class="form-group-border-bottom text-center" style="height: 4.5rem;line-height: 4.5rem;font-weight: 550;width:100%;"><b>温馨提示</b></div>'+
        '<img onclick="layer.closeAll();"  style="height: 2rem;position: absolute;top:1rem;right:1rem" src="../assets/user/img/close.png">'+
        '<div style="padding:10px 15px;font-size: 1.3rem;color: #545454;line-height:2rem;letter-spacing:.1rem;">1.使用QQ钱包充值可以跳转到微信支付;<br>2.充值成功后有延迟，请不要退出，等待提示充值成功即可返回;<br>3.若出现支付风控付款失败的情况，可进行小额分批充值;<br><a style="color: #ff0000;">4.截图用支付宝扫码，免费领取支付宝红包，本站充值可抵扣使用，每天可领一次，如遇支付宝充值未抵扣可使用转账充值里的支付宝即可。</a><br><img style="display: inline-block; width: 60%; max-width: 60%; height: auto;" src="../assets/img/hongbao.jpg" /><img src=""></p>'+
        '<hr><div align="center"><a onclick="layer.closeAll();" style="height:4.5rem;width: 100%;line-height: 4.5rem;font-size: 1.6rem;color:#378bd3;font-weight: 510">知道了</a></div>' +
        '</div>'+
        '</div>',

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