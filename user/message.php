<?php
$is_defend=true;
require '../includes/common.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");

if($userrow['power']==2){
	$type = '0,2,4';
}elseif($userrow['power']==1){
	$type = '0,2,3';
}else{
	$type = '0,1';
}
$msgcount=$DB->getColumn("SELECT count(*) FROM pre_message WHERE type IN ($type) AND active=1");
$msgread = explode(',',$userrow['msgread']);
$limit=isset($_GET['limit'])?intval($_GET['limit']):30;
$rs=$DB->query("SELECT * FROM pre_message WHERE type IN ($type) AND active=1 ORDER BY id DESC LIMIT 0,$limit");
$msgrow=array();
while($res = $rs->fetch()){
	if(in_array($res['id'],$msgread))$res['read']=true;
	else $res['read']=false;
	$msgrow[]=$res;
}

$title = '官方公告';

?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>官方公告</title>
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
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<body>
<style>
.msg-head {
    text-align: center;
    min-width: 360px;
    padding: 7px;
    background-color: #f9f9f9 !important;
}

.msg-body {
    padding: 15px;
    margin-bottom: 20px;
    word-break:break-all;
}
.msg-head{text-align: center;min-width: 360px;padding: 7px;background-color: #f9f9f9 !important;}
.msg-body{padding: 15px;margin-bottom: 20px;}

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
                <a href="javascript:history.back()"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">官方公告</a></font>

            </div>
                </div>
            <div  style="background: #f2f2f2; height: 10px"></div>
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                    <div class="my-cell-title-l left-title" style="font-size:1.3rem">官方公告</div>
                    <div class="my-cell-title-r  display-row  align-center">
                        <span style="color: #939393;font-size:1.3rem">共<?php echo $msgcount?>条消息</span>
                    </div>
                </div>
                <a href="javascript:msg_read_all();"   style="width: 100%;">
                    <div class="submit_btn" style="width:95%;height: 4.2rem;margin:10px auto;text-align: center;line-height: 4.2rem;border-radius: 5px">  一键已读</div>
                </a>
            </div> 
                <div class="max-width" style="margin: 10px auto">
    <?php
        foreach($msgrow as $row){?>
         
                    <div class="list-item">
                     <div class="list-item-top" style="padding-bottom: 10px">
                         <?php if($row['read']){?>
                          <div class="item-logo-0" style="width: auto;padding-right: 10px">
                             <div class="item-logo-img"style="width: auto;padding: 0 25px">已读消息
                             </div>
                          <?php }else{?>
                          <div class="item-logo-3" style="width: auto;padding-right: 10px">
                             <div class="item-logo-img"style="width: auto;padding: 0 25px">未读消息
                             </div>
                          
                          <?php }?>   
                             
                          </div>
                          <div class="item-operate" style="padding-top: 5px">
                             <a class="item-operate-item"  onclick="show(<?php echo $row['id']?>)">查看</a>
                          </div>
                     </div>
                         <div class="list-item-c">
                             <div class="item-c-txet " style="height: auto;line-height: normal;" >
                                 <div class="item-c-data "  style="margin-left: 5px;color:#000;font-size: 1.4rem;"><?php echo $row['title']?></div>
                             </div>
                             <div class="item-c-txet">
                                 <div class="item-c-data" style="margin-left: 10px;"><?php echo $row['addtime']?></div>
                             </div>
                             
                         </div>
                      </div>
                     
       <?php }
        if($msgcount==0){
        	echo '<tr><td class="text-center"><font color="grey">消息列表空空如也</font></td></tr>';
        }
        ?>	
                </div><br>
        		<?php if($msgcount>$limit){?>
        		<div class="list-group-item"><center><a href="?limit=<?php echo $limit+20;?>" id="btnload">加载更多</a></center></div>
        		<?php }?><br><br><br>
        </div>
            </div>
    </div>
    </div>
</div>
<script>
function msg_read_all()
{
	$.ajax({
		type : 'GET',
		url : 'ajax_user.php?act=msg_read_all',
		dataType : 'json',
		success : function(data) {
			if(data.code==0){
				window.location.reload();
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});        	
}
function show(id) {
	$.ajax({
		type : 'GET',
		url : 'ajax_user.php?act=msginfo&id='+id,
		dataType : 'json',
		success : function(data) {
			if(data.code==0){
				layer.open({
				  type: 1,
				  title: false,
				  shadeClose: false,
				  closeBtn: 0,
				  anim: 2,
				  shade: 0.5,
				  btn: ['知道了'],
				  btnAlign:'c',
				  shadeClose: true,

				  content: 
				        '<div class="form-group-border-bottom text-center" style="height: 4.5rem;line-height: 4.5rem;font-weight: 550;width:100%;"><b>公告详情</b></div><div class="msg-body">'+data.content+'</div>',
				  end: function(){
					  window.location.reload()
				  }
				});
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	});
}
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