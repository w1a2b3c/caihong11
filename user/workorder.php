<?php
/**
 * 我的工单
**/
include("../includes/common.php");
$title='我的工单';

?>

<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>我的工单</title>
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
<style>body{ background:#ecedf0 url("https://api.dujin.org/bing/1920.php") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style></head>
<body><link rel="stylesheet" href="../assets/user/css/work.css">

<style>
.gdan_gout{width:100%;height:auto;background-color:#fff;padding-bottom:1em}
.gdan_txt{height:3em;line-height:3em;text-indent:1em;font-family:"微软雅黑";font-weight:800;}
.gdan_txt>span{position:absolute;right:3em;}
.gdan_zhugan{width:96%;height:auto;padding-top:1em;margin-left:2%;padding-left:.5em;padding-right:1em;margin-bottom:1em;border-top:dashed 1px #a9a9a9}
.gdan_kjia1{width:auto;margin-left:4em;margin-top:-3em}
.gdan_xiaozhi{width:100%;height:1em;color:#a9a9a9;margin-bottom:1em}
.gdan_xiaozhi>span{position:absolute;right:3em;}
.gdan_huifu{width:100%;height:auto;margin-top:1em;border-top:solid #ccc 1px}
.gdan_srk{width:98%;height:8em;margin-left:1%;margin-top:1em;border-color:#6495ed}
.gdan_huifu1{width:6em;height:2.5em;border:none;background-color:#1e90ff;color:#fff;margin:.5em 0 .5em 1%}
.gdan_jied{width:100%;height:3em;line-height:3em;text-align:center;color:#129DDE}
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

<?php

if($conf['workorder_open']==0){
	showmsg('当前站点未开启工单功能',3);
}

function display_type($type){
	global $conf;
	$types = explode('|', $conf['workorder_type']);
	if($type==0 || !array_key_exists($type-1,$types))
		return '其它问题';
	else
		return $types[$type-1];
}

function display_status($status){
	if($status==1)
		return '<font color="">客服已回复,待我处理</font>';
	elseif($status==2)
		return '<font color="">已结单</font>';
	else
		return '<font color="">等待客服处理</font>';
}

$count1=$DB->getColumn("SELECT count(*) FROM pre_workorder WHERE zid='{$userrow['zid']}' AND status=1");
$count2=$DB->getColumn("SELECT count(*) FROM pre_workorder WHERE zid='{$userrow['zid']}' AND status=0");
$count3=$DB->getColumn("SELECT count(*) FROM pre_workorder WHERE zid='{$userrow['zid']}'");

$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='add')
{
?>

<!--提交按钮进入的工单信息页面-->
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
                <a href="javascript:history.back()"   class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">提交工单</a></font>

            </div>
                </div>
            <div  style="background: #f2f2f2; height: 10px"></div>
        
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.4rem">编辑工单</div>
            </div>
            <form action="./workorder.php?my=add_submit" method="POST">
                <div class="block-white" style="padding:0 10px">
                    <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">
                                订单编号
                            </div>
<?php
if(isset($_GET['orderid']) && $_GET['orderid'] && md5($_GET['orderid'].SYS_KEY.$_GET['orderid'])===$_GET['skey']){
	$orderid = intval($_GET['orderid']);
	$res=$DB->getRow("SELECT id,tid,input FROM pre_orders WHERE id='{$orderid}' LIMIT 1");
	$toolname=$DB->getColumn("SELECT name FROM pre_tools WHERE tid='{$res['tid']}' LIMIT 1");
	echo '<input type="text" name="orderid" value="'.$orderid.'_'.$toolname.'_'.$res['input'].'" class="form-control" disabled/><input type="hidden" name="orderid" value="'.$orderid.'"/><input type="hidden" name="skey" value="'.$_GET['skey'].'"/>';
}else{
	echo '<select name="orderid" class="form-control"><option value="0">选择异常的订单（其他问题可不选）</option>';
	$rs=$DB->query("SELECT id,tid,input FROM pre_orders WHERE userid='{$userrow['zid']}' ORDER BY id DESC LIMIT 20");
	while($res = $rs->fetch()){
		$toolname=$DB->getColumn("SELECT name FROM pre_tools WHERE tid='{$res['tid']}' LIMIT 1");
		echo '<option value="'.$res['id'].'">'.$res['id'].'_'.$toolname.'</option>';
	}
	echo '</select>';
}
?>
</div>
</div>
                    <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">
                                问题类型
                            </div>

	<select name="type" class="form-control">
	<?php foreach(explode('|', $conf['workorder_type']) as $key=>$value){
	echo '<option value="'.($key+1).'">'.$value.'</option>';
}?>
		<option value="0">其它问题</option>
	</select>
</div>
</div>
                    <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <textarea class="form-control" name="content" rows="5" placeholder="填写描述信息"
                                      required></textarea>
                        </div>
                    </div>

<?php if($conf['workorder_pic']==1){?>
                    <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">问题截图</div>
                            
<input type="file" id="file" onchange="fileUpload()" style="display:none;"/>
<input type="text" class="form-control" id="picurl" name="picurl" value="" readonly onclick="fileView()">
                                <span class="input-group-btn" style="padding-right: 10px">
                                <a href="javascript:fileSelect()" title="上传图片" class="display-row  align-center">
    <img style="width: 3.5rem; height: 3.5rem; " id="picurl_img"
                                          src="../assets/user/img/add_img.svg"></img></a></span>
</div>
</div>

<?php }?>

                                        
                    <div class="form-group form-group-transparent form-group-border-bottom">
                        <div class="input-group" style="width:100%">
                            <div class="input-group-addon">验证码&nbsp;&nbsp;</div>
                            
                  
                        <div class="input-group" style="width:97%; solid #f2f2f2;line-height: 4rem ">
                            <input type="text" name="code" class="form-control"  style="height: 4rem" required="required"  placeholder="填写验证码"/>
                            
                            <div class="input-group-addon" style="padding: 0">
                                <img id="codeimg" src="./code.php?r=<?php echo time();?>" style="height:4rem" onclick="this.src='./code.php?r='+Math.random();"
                                     title="点击更换验证码">
                            </div>

                        </div>
                    </div>
</div></div>
                </div>
                <div  style="font-size: 1.2rem;color: #999999;padding: 0 10px;margin-top: 10px">

                    *找不到要提交的订单？<a href="../?mod=query">点击进入查询订单</a>，在订单详情页面点击【售后反馈】可以直接提交工单。<br>
                    *成功提交工单后，本站客服会在24小时内为您处理解答，请耐心等待。
                </div>
                <div class="text-center" style="padding: 30px 0;">
                    <input type="submit" class="btn submit_btn" style="width: 80%;padding:8px;" value="提交">
                </div>
            </form>


                    </div>
    </div>
<!--以上是提交按钮进入的工单信息页面-->
</div>
<?php
}
elseif($my=='view')
{
$id=intval($_GET['id']);
$rows=$DB->getRow("SELECT * FROM pre_workorder WHERE id='$id' AND zid='{$userrow['zid']}' LIMIT 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
$contents = explode('*',$rows['content']);
$myimg = $userrow['qq']?'//q2.qlogo.cn/headimg_dl?bs=qq&dst_uin='.$userrow['qq'].'&src_uin='.$userrow['qq'].'&fid='.$userrow['qq'].'&spec=100&url_enc=0&referer=bu_interface&term_type=PC':'../assets/img/user.png';
$kfimg = 'https://imgcache.qq.com/open_proj/proj_qcloud_v2/mc_2014/work-order/css/img/custom-service-avatar.svg';
?>



<!--点击查看进入的查看工单信息页面-->
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
                <a href="workorder.php"  class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.4rem" src="../assets/img/fanhui.png">&nbsp;
                </a>
                <div style="margin: 0px 8px; border-left: 1px solid rgb(214, 215, 217); height: 16px; border-top-color: rgb(214, 215, 217); border-right-color: rgb(214, 215, 217); border-bottom-color: rgb(214, 215, 217);"></div>
                <a href="../" class="font-weight display-row align-center" style="height: 1.6rem;line-height: 1.65rem;width: 50%">
                    <img style="height: 1.8rem" src="../assets/img/home1.png">&nbsp;
                </a>
            </div>
            <div style="font-size: 15px;">
            <font><a href="">工单信息</a></font>

            </div>
                </div>
            <div  style="background: #f2f2f2; height: 10px"></div>
                <div class="gdan_gout">
                	<div class="gdan_txt">沟通记录 - <?php echo count($contents)?><span>状态：<?php echo display_status($rows['status'])?></span></div>
                	<!------------------开始沟通------------------------>
                	<div class="gdan_zhugan" style="border: none;">
                		<img src="<?php echo $myimg?>" class="img-circle" width="40"/>
                		<div class="gdan_kjia1">
                			<div class="gdan_xiaozhi">问题描述<span><?php echo $rows['addtime']?></span></div>
                			<p><?php echo str_replace(array("\r\n", "\r", "\n"), "<br/>",htmlspecialchars($content[0]))?></p><br/>
                			<p>订单编号：<?php echo $rows['orderid']?$rows['orderid']:'无订单号';?></p>
                			<p>问题类型：<?php echo display_type($rows['type'])?></p>
                			<?php echo $rows['picurl']?'<p>问题图片：[<a href="../'.$rows['picurl'].'" target="_blank">点此查看</a>]':null;?>
                		</div>
                	</div>
                <?php
                for($i=1;$i<count($contents);$i++){
                	$content = explode('^',$contents[$i]);
                	if(count($content)==3){
                		echo '<div class="gdan_zhugan">
                	<img src="'.($content[0]==1?$kfimg:$myimg).'" class="img-circle" width="40"/>
                	<div class="gdan_kjia1">
                	<div class="gdan_xiaozhi">'.($content[0]==1?'官方客服':$userrow['user']).'<span>'.$content[1].'</span></div>
                	'.str_replace(array("\r\n", "\r", "\n"), "<br/>",htmlspecialchars($content[2])).'
                	</div>
                </div>';
                	}
                }
                if($rows['status']==0){
                ?>
                <div class="gdan_jied">请耐心等待客服处理</div>
                <?php
                }elseif($rows['status']==2){
                ?>
                <div class="gdan_jied">此工单已经结单</div>
                <?php
                }elseif($rows['status']==1){
                ?>
                                                <div class="gdan_huifu">
                                                    <form action="./workorder.php?my=reply&id=<?php echo $id?>" method="POST">
                                                    	<textarea class="gdan_srk" name="content" placeholder="可输入需要补充的内容，回复后官方客服将会收到您的消息！" required></textarea>
                                                    	
                                                    	
                                                    	  <div style="width: 100%;margin: 10px 0;" class="display-row justify-around ">
                                                                <input type="submit" name="submit" value="提交回复" class="gdan_huifu1"/>
                                                                <input type="button" name="submit" value="完结工单" class="gdan_huifu1"style="background-image:linear-gradient(to right, rgba(252, 198, 108, 1), rgba(249, 138, 63, 1));" onclick="window.location.href='./workorder.php?my=complete&id=<?php echo $id?>'"/>
                                                          </div>
                                                    
                                                    </form>
                                                </div>
                
                </div>
        
        </div>    
    </div>
<!--以上点击查看进入的查看工单信息页面-->
</div>

<?php
}
?>


<?php
}
elseif($my=='add_submit')
{
$orderid=intval($_POST['orderid']);
$type=intval($_POST['type']);
$content=str_replace(array('*','^','|'),'',trim(strip_tags(daddslashes($_POST['content']))));
$picurl=strip_tags(daddslashes($_POST['picurl']));
$code = isset($_POST['code'])?$_POST['code']:null;
if (!$code || strtolower($code) != $_SESSION['vc_code']) {
	unset($_SESSION['vc_code']);
	showmsg('验证码错误！');
}
if (empty($content)) {
	showmsg('描述信息不能为空！');
} elseif ($orderid>0 && !$DB->getRow("SELECT id FROM pre_orders WHERE id='$orderid' AND userid='{$userrow['zid']}' LIMIT 1") && md5($orderid.SYS_KEY.$orderid)!==$_POST['skey']) {
	showmsg('您只能选择自己的订单');
} elseif ($orderid>0 && $DB->getRow("SELECT id FROM pre_workorder WHERE orderid='$orderid' AND status<2 ORDER BY id DESC LIMIT 1")) {
	showmsg('请勿重复提交工单！');
} else {
	/*$res=$DB->getRow("select id,tid,addtime from pre_orders where id='{$orderid}' limit 1");
	$toolname=$DB->getColumn("select name from pre_tools where tid='{$res['tid']}' limit 1");
	if(strpos($toolname,'钻')!==false && time()-strtotime($res['addtime'])<48*3600){
		showmsg('当前商品处理需要一定的时间，请耐心等待！如果48小时以后还未到账请再提交工单！');
	}elseif(time()-strtotime($res['addtime'])<24*3600){
		showmsg('当前商品处理需要一定的时间，请耐心等待！如果24小时以后还未到账请再提交工单！');
	}*/
$sql="INSERT INTO `pre_workorder` (`zid`,`type`,`orderid`,`content`,`picurl`,`addtime`,`status`) VALUES (:zid, :type, :orderid, :content, :picurl, NOW(), 0)";
$data = [':zid'=>$userrow['zid'], ':type'=>$type, ':orderid'=>$orderid, ':content'=>$content, ':picurl'=>$picurl];
if($DB->exec($sql, $data)){
	$id = $DB->lastInsertId();
	if($conf['message_workorder']==1){
		\lib\MessageSend::workorder_new($id, $userrow['user'], $userrow['zid'], display_type($type), $content);
	}
	unset($_SESSION['vc_code']);
	showmsg('<div class="form-group form-group-transparent" style="background: #f2f2f2; padding:2px 0px;padding-left: 10px;margin: 0 -10px;">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        提交工单成功，请等待平台客服回复处理。
                    </div>
                </div>
            </div>
           ',1);
}else{
	showmsg('提交工单失败！'.$DB->error(),4);
}
}
}
elseif($my=='reply')
{
$id=intval($_GET['id']);
$rows=$DB->getRow("SELECT * FROM pre_workorder WHERE id='$id' AND zid='{$userrow['zid']}' LIMIT 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
elseif($rows['status']==2)
	showmsg('此工单已经结单',3);
elseif($rows['status']==2)
	showmsg('请耐心等待客服处理',3);
$content=str_replace(array('*','^','|'),'',trim(strip_tags(daddslashes($_POST['content']))));
if (empty($content)) {
	showmsg('补充信息不能为空！');
} else {
$contents = addslashes($rows['content']).'*0^'.$date.'^'.$content;
if($DB->exec("update pre_workorder set content=:content,status=0 where id=:id", [':content'=>$contents, ':id'=>$id])!==false){
	if($conf['message_workorder']==1){
		\lib\MessageSend::workorder_reply($id, $userrow['user'], $userrow['zid'], display_type($rows['type']), $content);
	}
	showmsg('<div class="form-group form-group-transparent" style="background: #f2f2f2; padding:2px 0px;padding-left: 10px;margin: 0 -10px;">
                <div class="input-group" style="width:100%">
                    <div class="input-group-addon">
                        回复工单成功！请等待平台客服处理。
                    </div>
                </div>
            </div>',1);
}else{
	showmsg('回复工单失败！'.$DB->error(),4);
}
}
}
elseif($my=='complete')
{
$id=intval($_GET['id']);
$rows=$DB->getRow("SELECT * FROM pre_workorder WHERE id='$id' AND zid='{$userrow['zid']}' LIMIT 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
elseif($rows['status']==2)
	showmsg('此工单已经结单',3);
if($DB->exec("UPDATE pre_workorder SET status=2 WHERE id='{$id}'")!==false)
	exit("<script language='javascript'>alert('完结工单成功！');history.go(-1);</script>");
else
	showmsg('完结工单失败！'.$DB->error(),4);
}
elseif($my=='delete')
{
$id=intval($_GET['id']);
$sql="DELETE FROM pre_workorder WHERE id='$id' AND zid='{$userrow['zid']}'";
if($DB->exec($sql)!==false)
	exit("<script language='javascript'>alert('删除成功！');history.go(-1);</script>");
else
	showmsg('删除失败！'.$DB->error(),4);
}
else
{
?>

<!--工单信息首页-->
<style>
    .gdan_gout {
        width: 100%;
        height: auto;
        background-color: #fff;
        padding-bottom: 1em
    }

    .gdan_txt {
        height: 3em;
        line-height: 3em;
        text-indent: 1em;
        font-family: "微软雅黑";
        font-weight: 800;
    }

    .gdan_txt > span {
        position: absolute;
        right: 3em;
    }

    .gdan_zhugan {
        width: 96%;
        height: auto;
        padding-top: 1em;
        margin-left: 2%;
        padding-left: .5em;
        padding-right: 1em;
        margin-bottom: 1em;
        border-top: dashed 1px #a9a9a9
    }

    .gdan_kjia1 {
        width: auto;
        margin-left: 4em;
        margin-top: -3em
    }

    .gdan_xiaozhi {
        width: 100%;
        height: 1em;
        color: #a9a9a9;
        margin-bottom: 1em
    }

    .gdan_xiaozhi > span {
        position: absolute;
        right: 3em;
    }

    .gdan_huifu {
        width: 100%;
        height: auto;
        margin-top: 1em;
        border-top: solid #ccc 1px
    }

    .gdan_srk {
        width: 98%;
        height: 8em;
        margin-left: 1%;
        margin-top: 1em;
        padding: 8px;
    }

    .gdan_huifu1 {
        width: 40%;
        height: 2.5em;
        border: none;
        background-image:linear-gradient(to right , rgba(100, 190, 243, 1.0), rgba(126, 68, 245, 1.0));
        border-radius: 50px;
        color: #fff;
        box-shadow: 1px 1px 12px #e2dfdf, 1px 5px 8px #c3c3c3;

    }

    .gdan_jied {
        width: 100%;
        height: 3em;
        line-height: 3em;
        text-align: center;
        color: #129DDE
    }
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


    .form-control[disabled]{
        background-color:transparent;
        color: #999999;
    }
    .search-input::placeholder{
        text-align: left;
    }
    input::placeholder{
        font-size: 1.3rem;
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
    .list-item .list-item-c .item-c-txet{
        min-height: 2.7rem;
        height: 2.7rem;
        font-size: 1.4rem;
    }
    .list-item .list-item-c .item-c-txet .item-c-data{
        color: #525050;
    }
    .li-list-item .item-info{
        width: 74%;
        word-break:break-all;
        color: #565656;
        font-size: 1.2rem;
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
            <font><a href="">我的工单</a></font>

            </div>
                </div>
            <div  style="background: #f2f2f2; height: 10px"></div>
        <div class="my-cell" style="margin-bottom: 0px;padding: 5px 10px;border-radius: 0">
            <div class="my-cell-title display-row justify-between align-center">
                <div class="my-cell-title-l left-title" style="font-size:1.4rem">我的工单</div>
                                <div style="flex-grow:2;text-align:right ">

                            <a onclick="openmsg1()" style="color: #999999;font-size: 1.3rem;"><i class="fa fa-question-circle-o"  style="font-size:1.5rem;padding-right: 5px"></i>提交工单规则</a>
                </div>
            </div>
           <div style="padding: 0 5px;color:#f5f5f5;font-size: 1.2rem" class="display-row align-center justify-between">
                <div  style="width: 32%; height: 7rem;border-radius:5px;background-image:linear-gradient(to right, rgba(239, 154, 183, 1), rgba(233, 95, 95, 1));position: relative"  >

                    <li style="position: absolute;top: 15%;left: 7%"><font size="5" color="#fff"><?php echo $count3?></font></li>
                    <li style="position: absolute;bottom: 20%;left: 7%""> 全部工单</li>
                    <img style="width:3.5rem;position: absolute;top:8px;right: 8px " src="../assets/user/img/workorder1.png">
                </div>
                <div  style="width: 32%; height: 7rem;border-radius:5px;background-image:linear-gradient(to right, rgba(130, 193, 255, 1), rgba(148, 93, 246, 1));position: relative" >

                    <li style="position: absolute;top: 15%;left: 7%"><font size="5" color="#fff"><?php echo $count1?></font></li>
                    <li style="position: absolute;bottom: 20%;left: 7%""> 待我处理</li>
                    <img style="width:3.5rem;position: absolute;top:8px;right: 8px "src="../assets/user/img/workorder2.png">
                </div>
                <div  style="width: 32%; height: 7rem;border-radius:5px;background-image:linear-gradient(to right, rgba(252, 198, 108, 1), rgba(249, 138, 63, 1));position: relative" >
                    <li style="position: absolute;top: 15%;left: 7%"><font size="5" color="#fff"><?php echo $count2?></font></li>
                    <li style="position: absolute;bottom: 20%;left: 7%""> 处理中</li>
                    <img style="width:3.5rem;position: absolute;top:8px;right: 8px " src="../assets/user/img/workorder3.png">
                </div>
            </div>
            <div class="text-center" style="padding: 4px 5px;padding-top: 10px">
                <a type="button" href="./workorder.php?my=add" class="btn btn-default btn-block"
                   style="width: 100%;display: inline-block;border-radius: 5px;padding: 10px 0;box-shadow: 1px 1px 2px #e2dfdf;border:  1px solid #f2f2f2;">
                    <span style="font-size:1.5rem; "><i class="fa fa-plus"></i> 提交工单（售后反馈）</span>
                </a>
            </div>
        </div>

<div class="max-width">
<?php
    $numrows=$DB->getColumn("SELECT count(*) from pre_workorder WHERE zid='{$userrow['zid']}'");
    $pagesize=30;
    $pages=intval($numrows/$pagesize);
    if ($numrows%$pagesize)
    {
     $pages++;
     }
    if (isset($_GET['page'])){
    $page=intval($_GET['page']);
    }
    else{
    $page=1;
    }
    $offset=$pagesize*($page - 1);
    
    $rs=$DB->query("SELECT * FROM pre_workorder WHERE zid='{$userrow['zid']}' ORDER BY id DESC LIMIT $offset,$pagesize");
    while($res = $rs->fetch())
    {
    $content=explode('*',$res['content']);
    $content=mb_substr($content[0], 0, 16, 'utf-8');
    echo '<div class="list-item">
             <div class="list-item-top">
                  <div class="item-logo-2" style="width: auto;padding-right: 10px">
                     <div class="item-logo-img"style="width: auto;padding: 0 25px">'.display_status($res['status']).'</div>
                  </div>
             </div>
                <div class="list-item-c">
                 <div class="item-c-txet" >
                     <div class="item-c-title">工单类型</div>
                     <div class="item-c-data"  >'.display_type($res['type']).'</div>
                 </div>
                 <div class="item-c-txet" style="position:relative">
                     <div class="item-c-title">问题描述</div>
                     <div class="item-c-data ellipsis1">
                        <a href="./workorder.php?my=view&id='.$res['id'].'">'.htmlspecialchars($content).'</a>
                     </div>
                     <a href="./workorder.php?my=view&id='.$res['id'].'" style="position: absolute;right:0;top:0">
                         <div class="btn btn-default" style="font-site:1.2rem;background: #6d9eeb;border-radius: 10px;color: #fff" >查看工单</div>
                    </a>
                 </div>
                 <div class="item-c-txet">
                     <div class="item-c-title">工单状态</div>
                     <div class="item-c-data">'.display_status($res['status']).'</div>
                 </div>
                            <div class="item-c-txet">
                     <div class="item-c-title">提交时间</div>
                     <div class="item-c-data">'.$res['addtime'].'</div>
                 </div>
             </div>
          </div>';
}?>
<?php
    echo'<div class="text-center"><ul class="pagination">';
        $first=1;
        $prev=$page-1;
        $next=$page+1;
        $last=$pages;
        if ($page>1)
        {
        echo '
        
        ';
        echo '
        <li><a href="workorder.php?page='.$prev.$link.'">&laquo;</a></li>
        ';
        } else {
        echo '<li class="disabled"><a>首页</a></li>';
        echo '<li class="disabled"><a>&laquo;</a></li>';
        }
        for ($i=1;$i<$page;$i++)
        echo '<li><a href="workorder.php?page='.$i.$link.'">'.$i .'</a></li>';
        echo '<li class="disabled"><a>'.$page.'</a></li>';
        if($pages>=10)$s=10;
        else $s=$pages;
        for ($i=$page+1;$i<=$s;$i++)
        echo '<li><a href="workorder.php?page='.$i.$link.'">'.$i .'</a></li>';
        echo '';
        if ($page<$pages)
        {
        echo '<li><a href="workorder.php?page='.$next.$link.'">&raquo;</a></li>';
        echo '<li><a href="workorder.php?page='.$last.$link.'">尾页</a></li>';
        } else {
        echo '<li class="disabled"><a>&raquo;</a></li>';
        echo '<li class="disabled"><a>尾页</a></li>';
        }
        echo'</ul></div>';
        #分页
}?>
    </div>
  </div>
</div>
<!--工单信息首页-->
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
        var status = '';
        status = status?status:7;
        if (status !== 7) {
            status = parseInt(status);
            $("#tab_" + status).addClass('hotxy-item-index');
            $("#tab").scrollLeft(100 * status / 2);
        } else {
            $("#tab_7").addClass('hotxy-item-index');
        }

        var clipboard = new Clipboard('#copy-btn');
        clipboard.on('success', function (e) {
            layer.msg('复制成功');
        });
        clipboard.on('error', function (e) {
            layer.msg('复制失败，请长按链接后手动复制');
        });
    });
    function fileSelect() {
        $("#file").trigger("click");
    }

    function fileView() {
        var picurl = $("#picurl").val();
        if (picurl == '') {
            return;
        }
        if (picurl.indexOf('http') == -1) picurl = '../' + picurl;
        layer.open({
            type: 1,
            area: ['360px', '400px'],
            title: '图片查看',
            shade: 0.3,
            anim: 1,
            shadeClose: true,
            content: '<center><img width="300px" src="' + picurl + '"></center>'
        });
    }

    function fileUpload() {
        var fileObj = $("#file")[0].files[0];
        if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
            return;
        }
        var formData = new FormData();
        formData.append("do", "upload");
        formData.append("type", "user");
        formData.append("file", fileObj);
        var ii = layer.load(2, {shade: [0.1, '#fff']});
        $.ajax({
            url: "ajax_user.php?act=uploadimg",
            data: formData,
            type: "POST",
            dataType: "json",
            cache: false,
            processData: false,
            contentType: false,
            success: function (data) {
                layer.close(ii);
                if (data.code == 0) {
                    layer.msg('上传图片成功');
                    $("#picurl").val(data.url);
                    $('#picurl_img').attr('src' , '../'+data.url);
                } else {
                    layer.alert(data.msg);
                }
            },
            error: function (data) {
                layer.msg('服务器错误');
                return false;
            }
        })
    }

    function showOrder(id, skey) {
        if (id == 0) return false;
        var ii = layer.load(2, {shade: [0.1, '#fff']});
        var area = $(window).width() > 650 ? '480px' : '90%';
        var status = ['待处理', '已完成', '处理中', '异常', '已退款'];
        $.ajax({
            type: "POST",
            url: "../ajax.php?act=order",
            data: {id: id, skey: skey},
            dataType: 'json',
            success: function (data) {
                layer.close(ii);
                if (data.code == 0) {
                    var item = '';
                    if (data.list && data.list.order_state) {
                        item +=  '<div class="showtip-center-msg" style="font-size: 1.5rem">订单实时状态</div>'+
                            '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px;overflow-x: hidden;background: #f7f7f7;border-radius: 5px">' +
                            '<div class="li-list-item">'+
                            '<div class="item-title">下单数量</div>'+
                            '<div class="item-info">'+ data.list.num +'</div>'+
                            '</div>'+
                            '<div class="li-list-item">'+
                            '<div class="item-title">下单时间</div>'+
                            '<div class="item-info">'+ data.list.add_time +'</div>'+
                            '</div>'+
                            '<div class="li-list-item">'+
                            '<div class="item-title">初始数量</div>'+
                            '<div class="item-info">'+ data.list.start_num  +'</div>'+
                            '</div>'+
                            '<div class="li-list-item">'+
                            '<div class="item-title">当前数量</div>'+
                            '<div class="item-info">'+ data.list.now_num +'</div>'+
                            '</div>'+
                            '<div class="li-list-item">'+
                            '<div class="item-title">订单状态</div>'+
                            '<div class="item-info">'+ data.list.order_state +'</div>'+
                            '</div>'+
                            '</div>';
                    } else if (data.kminfo) {
                        item +=  '<div class="showtip-center-msg" style="font-size: 1.5rem">以下是您的卡密信息</div>'+
                            '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px;overflow-x: hidden;background: #f7f7f7;border-radius: 5px">' +
                            '<div class="li-list-item">'+
                            '<div class="item-info" style="width: 100%">'+ data.kminfo +'</div>'+
                            '</div>'+
                            '</div>';
                    } else if (data.result) {
                        item +=  '<div class="showtip-center-msg" style="font-size: 1.5rem">处理结果</div>'+
                            '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px;overflow-x: hidden;background: #f7f7f7;border-radius: 5px">' +
                            '<div class="li-list-item">'+
                            '<div class="item-info" style="width: 100%">'+ data.result +'</div>'+
                            '</div>'+
                            '</div>';
                    }
                    if (data.alert) {
                        item +=  '<div class="showtip-center-msg" style="font-size: 1.5rem">商品简介</div>'+
                            '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px;overflow-x: hidden;background: #f7f7f7;border-radius: 5px">' +
                            '<div class="li-list-item">'+
                            '<div class="item-info" style="width: 100%">'+ data.desc +'</div>'+
                            '</div>'+
                            '</div>';
                    }

                    layer.open({
                        type:1,
                        title: false,
                        area: area,
                        shade: 0.7,
                        skin: "layui-layer-molv",
                        shadeClose: false,
                        closeBtn: 0,
                        btnAlign: 'c',
                        btn: ['确认'],
                        content:
                            '<div class="showtip display-column align-center" style="letter-spacing:.05rem;">' +
                               '<div class="showtip-title" style="height: 3px"></div>' +
                                '<div class="showtip-center  display-column justify-center align-center" style="width: 100%;padding: 0 10px">'+
                                     '<div class="showtip-center-msg" style="font-size: 1.5rem">订单基本信息</div>'+
                                     '<div class="showtip-center-num" style="width: 100%;margin-bottom: 15px;overflow-x: hidden;background: #f7f7f7;border-radius: 5px">' +
                                        '<div class="li-list-item">'+
                                            '<div class="item-title">订单编号</div>'+
                                            '<div class="item-info">'+ id +'</div>'+
                                        '</div>'+
                                        '<div class="li-list-item">'+
                                            '<div class="item-title">商品名称</div>'+
                                            '<div class="item-info">'+ data.name +'</div>'+
                                        '</div>'+
                                        '<div class="li-list-item">'+
                                        '<div class="item-title">订单金额</div>'+
                                        '<div class="item-info">￥'+ data.money +'</div>'+
                                        '</div>'+
                                        '<div class="li-list-item">'+
                                        '<div class="item-title">购买时间</div>'+
                                        '<div class="item-info">'+ data.date +'</div>'+
                                        '</div>'+
                                        '<div class="li-list-item">'+
                                        '<div class="item-title">下单信息</div>'+
                                        '<div class="item-info">'+data.inputs +'</div>'+
                                        '</div>'+
                                        '<div class="li-list-item">'+
                                        '<div class="item-title">订单状态</div>'+
                                        '<div class="item-info">'+ status[data.status]+'</div>'+
                                        '</div>'+

                                     '</div>'+
                                '</div>' +
                            '</div>',
                        btn1: function(index, layero){
                            layer.closeAll();
                        },

                        success: function(layero, index){
                            var btn1= $(".layui-layer-btn .layui-layer-btn0");
                            btn1.css({
                                "width":" 100%",
                                "height": "100%",
                                "padding": "0",
                                "margin":"0",
                                "line-height": "4.5rem",
                                "border-radius":" 0",
                                "border":" 1px solid transparent",
                                "background": "transparent",
                                "color":"#000",
                            })
                        }
                    });
                } else {
                    layer.alert(data.msg);
                }
            }
        });
    }
</script>
<script>
function fileSelect(){
	$("#file").trigger("click");
}
function fileView(){
	var picurl = $("#picurl").val();
	if(picurl=='') {
		return;
	}
	if(picurl.indexOf('http') == -1)picurl = '../'+picurl;
	layer.open({
		type: 1,
		area: ['360px', '400px'],
		title: '图片查看',
		shade: 0.3,
		anim: 1,
		shadeClose: true,
		content: '<center><img width="300px" src="'+picurl+'"></center>'
	});
}
function fileUpload(){
	var fileObj = $("#file")[0].files[0];
	if (typeof (fileObj) == "undefined" || fileObj.size <= 0) {
		return;
	}
	var formData = new FormData();
	formData.append("do","upload");
	formData.append("type","user");
	formData.append("file",fileObj);
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	$.ajax({
		url: "ajax_user.php?act=uploadimg",
		data: formData,
		type: "POST",
		dataType: "json",
		cache: false,
		processData: false,
		contentType: false,
		success: function (data) {
			layer.close(ii);
			if(data.code == 0){
				layer.msg('上传图片成功');
				$("#picurl").val(data.url);
			}else{
				layer.alert(data.msg);
			}
		},
		error:function(data){
			layer.msg('服务器错误');
			return false;
		}
	})
}
function showOrder(id,skey){
	if(id==0)return false;
	var ii = layer.load(2, {shade:[0.1,'#fff']});
	var status = ['<span class="label label-primary">待处理</span>','<span class="label label-success">已完成</span>','<span class="label label-warning">处理中</span>','<span class="label label-danger">异常</span>','<font color=red>已退款</font>'];
	$.ajax({
		type : "POST",
		url : "../ajax.php?act=order",
		data : {id:id,skey:skey},
		dataType : 'json',
		success : function(data) {
			layer.close(ii);
			if(data.code == 0){
				var item = '<table class="table table-condensed table-hover">';
				item += '<tr><td colspan="6" style="text-align:center"><b>订单基本信息</b></td></tr><tr><td class="info">订单编号</td><td colspan="5">'+id+'</td></tr><tr><td class="info">商品名称</td><td colspan="5">'+data.name+'</td></tr><tr><td class="info">订单金额</td><td colspan="5">'+data.money+'元</td></tr><tr><td class="info">购买时间</td><td colspan="5">'+data.date+'</td></tr><tr><td class="info">下单信息</td><td colspan="5">'+data.inputs+'</td></tr><tr><td class="info">订单状态</td><td colspan="5">'+status[data.status]+'</td></tr>';
				if(data.list && data.list.order_state){
					item += '<tr><td colspan="6" style="text-align:center"><b>订单实时状态</b></td><tr><td class="warning">下单数量</td><td>'+data.list.num+'</td><td class="warning">下单时间</td><td colspan="3">'+data.list.add_time+'</td></tr><tr><td class="warning">初始数量</td><td>'+data.list.start_num+'</td><td class="warning">当前数量</td><td>'+data.list.now_num+'</td><td class="warning">订单状态</td><td><font color=blue>'+data.list.order_state+'</font></td></tr>';
				}else if(data.kminfo){
					item += '<tr><td colspan="6" style="text-align:center"><b>以下是您的卡密信息</b></td><tr><td colspan="6">'+data.kminfo+'</td></tr>';
				}else if(data.result){
					item += '<tr><td colspan="6" style="text-align:center"><b>处理结果</b></td><tr><td colspan="6">'+data.result+'</td></tr>';
				}
				if(data.alert){
					item += '<tr><td colspan="6" style="text-align:center"><b>商品简介</b></td><tr><td colspan="6">'+data.desc+'</td></tr>';
				}
				item += '</table>';
				layer.open({
				  type: 1,
				  title: '订单详细信息',
				  skin: 'layui-layer-rim',
				  content: item
				});
			}else{
				layer.alert(data.msg);
			}
		}
	});
}

    function  openmsg1() {
        layer.open({
            type:1,
            title: false,
            area: ['30rem'],
            shade: 0.7,
            skin: "layerdemo",
            shadeClose: false,
            closeBtn: 0,
            content: '<center><div class="showtip display-column  align-center" style="background:#FFF">'+
                '<b></b>'+
                '<div class="text-left" style="width:100%;padding: 15px">'+
                '<font style="font-weight: 800;line-height: 3rem">亲爱的用户，您好！</font><br>'+
                '<font style="color: #999999">如果您在使用本平台的过程中，遇到有关于产品售后相关的问题需要咨询或建议，首先，请您不要着急，您可以在本页面【提交工单】进行反馈。</font><br>'+
                '</div>'+
                '<div class="text-left" style="width:100%;padding: 15px;background:#f2f2f2 ">'+
                '<font style="font-weight: 800;line-height: 3rem">工单处理时间</font><br>'+
                '<font style="color: #999999">您所提交的售后工单，本站客服会在24小时内为您处理解答，请耐心等待，并且及时关注工单状况，客服回复后，可点击工单旁边的【查看工单】按钮，查询工单回复状态。</font><br>'+
                '</div>'+
                
                '<div class="showtip-btn display-row justify-center align-center" >' +
                '<a  href="javascript:layer.closeAll();" class="showtip-btn-yes display-column justify-center align-center" style="height:4rem;color: #0b9ff5">确定</a>' +
                '</div>'+
                '</div>'+
                '</center>',

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