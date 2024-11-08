<?php
if(!defined('IN_CRONLITE'))exit();
if(checkmobile() && !$_GET['pc'] || $_GET['mobile']){include_once TEMPLATE_ROOT.'faka/wapquery.php';exit;}

function display_zt($zt){
	if($zt==1)
		return '<font color=green>已完成</font>';
	elseif($zt==2)
		return '<font color=orange>正在处理</font>';
	elseif($zt==3)
		return '<font color=red>异常</font>';
	elseif($zt==4)
		return '<font color=grey>已退单</font>';
	else
		return '<font color=blue>待处理</font>';
}

if($islogin2==1){
	$cookiesid = $userrow['zid'];
}

$data=trim(daddslashes($_GET['data']));
$page=isset($_GET['page'])?intval($_GET['page']):1;
if(!empty($data)){
	if(strlen($data)==17 && is_numeric($data))$sql=" A.`tradeno`='{$data}'";
	else $sql=" A.`input`='{$data}'";
	if($conf['queryorderlimit']==1)$sql.=" AND A.`userid`='$cookiesid'";
	$link='&data='.$data;
}
else $sql=" A.`userid`='{$cookiesid}'";
$limit = 10;
$start = $limit * ($page-1);

$total = $DB->getColumn("SELECT count(*) FROM `pre_orders` A WHERE{$sql} ");
$total_page = ceil($total/$limit);
$sql = "SELECT A.*,B.`name` FROM `pre_orders` A LEFT JOIN `pre_tools` B ON A.`tid`=B.`tid` WHERE{$sql} ORDER BY A.`id` DESC LIMIT {$start},{$limit}";
$rs=$DB->query($sql);
$record=array();
while($res = $rs->fetch()){
	$record[]=array('id'=>$res['id'],'tid'=>$res['tid'],'input'=>$res['input'],'money'=>$res['money'],'name'=>$res['name'],'value'=>$res['value'],'addtime'=>$res['addtime'],'endtime'=>$res['endtime'],'result'=>$res['result'],'status'=>$res['status'],'djzt'=>$res['djzt'],'skey'=>md5($res['id'].SYS_KEY.$res['id']));
}

$hometitle = '订单查询 - '.$conf['sitename'];
include_once TEMPLATE_ROOT.'faka/inc/head.php';
?>
<style type="text/css">
.query {
	padding: 25px;
	min-height: 300px;
}
.query .in {
	height: 45px;
	border: 1px solid #ff6600;
	-webkit-border-radius: 3px;
	-moz-border-radius: 3px;
	border-radius: 3px;
	width: 65%;
	margin-right: auto;
	margin-left: auto;
	overflow: hidden;
}
.query .in .in_l {
	float: left;
	width: 80%;
	height: 100%;
}
.query .in .in_r {
	float: right;
	height: 100%;
	width: 20%;
	
}
.query .in input {
	margin: 0px;
	border-top-width: 0px;
	border-right-width: 0px;
	border-bottom-width: 0px;
	border-left-width: 0px;
	border-top-style: none;
	border-right-style: none;
	border-bottom-style: none;
	border-left-style: none;
	height: 100%;
	width: 100%;
	line-height: 45px;
	font-size: 18px;
	padding-top: 0px;
	padding-right: 0px;
	padding-bottom: 0px;
	padding-left: 1%;
}
.query .in .in_r input {
	cursor: pointer;
	background-color: #F60;
	font-size: 20px;
	line-height: 45px;
	color: #FFF;
	font-weight: bold;
	padding: 0px;
	width: 100%;
	-webkit-border-radius: 0px;
	-moz-border-radius: 0px;
	border-radius: 0px;
}
#kong .query .title {
	text-align: center;
	height: 50px;
	line-height: 50px;
	margin-bottom: 15px;
}
.title2 {
	text-align: center;
	height: 25px;
	line-height: 25px;
	margin-bottom: 15px;
	font-size: 14px;
	color: #ff253a;
}
td.stitle{max-width:380px;}
td.wbreak{max-width:420px;word-break:break-all;}
#orderItem .orderTitle{word-break:keep-all;}
#orderItem .orderContent{word-break:break-all;}
#orderItem .orderContent img{max-width:100%}
.table>tbody>tr>td{padding: 5px;border-top: 1px solid #ddd;}
.table{width: 100%;max-width: 100%;margin-bottom: 20px;margin: 0;}
.table>tbody>tr>td.info{background-color: #d9edf7;}
</style>
<div class="g-body">
<br/>
<br/>
<div id="kong">
  <div class="query">
  <div class="title">请输入订单号，交易单号，手机号查询</div>
  	<form action="?" method="get"><input type="hidden" name="mod" value="query"/>
    <div class="in">
	  <div class="in_l"><input type="text" name="data" placeholder="请输入订单号或填写的联系方式查询" value="<?php echo htmlspecialchars($data)?>"/></div>
      <div class="in_r"><input type="submit" value="订单查询" /></div>
    </div>
    </form><br/>
	  <?php if(!$islogin2){?><div class="title2">注意：如果您想长期保留订单，请您注册成为我们的会员。</div><?php }?>
 <br/><font size="4" color="#000000" title="这是您最近的购买记录">这是您最近的购买记录</font>
 <br/> <table width="100%" border="0" cellspacing="0" cellpadding="10">
      <thead>
        <th style="text-align:center">订单ID</th>
        <th>名称</th>
        <th style="text-align:center">数量</th>
        <th style="text-align:center">金额</th>
        <th style="text-align:center">状态</th>
		<th style="text-align:center">日期</th>
        <th style="text-align:center">操作</th>
      </thead>
	  <tbody>
<?php
if($total>0){
	foreach($record as $row){
	  echo '<tr><td>'.$row['id'].'</td><td class="stitle"><a target="_blank" href="./?mod=buy&tid='.$row['tid'].'">'.$row['name'].'</a></td><td>'.$row['value'].'</td><td>'.$row['money'].'元</td><td>'.display_zt($row['status']).'</td><td><a>'.$row['addtime'].'</a></td><td><a href="javascript:showOrder('.$row['id'].',\''.$row['skey'].'\')" title="点击查看订单详情" class="button button-primary button-rounded button-tiny">详情</a>&nbsp;'.(($row['djzt']==3||$row['djzt']==4)?'<a href="./?mod=faka&id='.$row['id'].'&skey='.$row['skey'].'" title="点击查看卡密" class="button button-highlight button-rounded button-tiny">提卡</a>':null).'</td></tr>';
	}
}else{
	echo '<td scope="col" colspan="7">'.($_GET['data']?'<span class="empty">没有查询到数据</span>':'<span class="empty">您暂时没有任何订单哦！</span>').'</td>';
}
?>
    </tbody></table><br/> <div class='text-right'><ul class='pagination'>
<?php if($page>1){?>
<li><a href="./?mod=query&page=<?php echo ($page-1).$link;?>">上一页</a></li>
<?php }?>
<li><span class="rows"><?php echo $page?> / <?php echo $total_page?></span></li>
<?php if($total_page!=$page){?>
<li><a href="./?mod=query&page=<?php echo ($page+1).$link;?>">下一页</a></li>
<?php }?>
</ul></div></div>

</div>

<?php include_once TEMPLATE_ROOT.'faka/inc/foot.php';?>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnpublic?>layer/2.3/layer.js"></script>
<script src="<?php echo $cdnserver ?>assets/faka/js/query.js?ver=<?php echo VERSION ?>"></script>
</body>
</html>