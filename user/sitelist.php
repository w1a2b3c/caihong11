<?php
/**
 * 分站管理
**/
include("../includes/common.php");
$title='分站管理';
include './head.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
?>
<div class="wrapper">
<div class="col-sm-12">
		<div class="panel panel-default">
<div class="modal fade" align="left" id="search" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">搜索分站</h4>
      </div>
      <div class="modal-body">
      <form action="sitelist.php" method="GET">
<input type="text" class="form-control" name="kw" placeholder="请输入分站用户名或域名或QQ或站长ID"><br/>
<input type="submit" class="btn btn-primary btn-block" value="搜索"></form>
</div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<?php
if($userrow['power']<2){
	showmsg('你没有权限使用此功能！',3);
}
$my=isset($_GET['my'])?$_GET['my']:null;

if($my=='add')
{
$domains=explode(',',$conf['fenzhan_domain']);
$select='';
foreach($domains as $domain){
	$select.='<option value="'.$domain.'">'.$domain.'</option>';
}
echo '<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">添加一个分站</div>';
echo '<div class="panel-body">';
echo '<form action="./sitelist.php?my=add_submit" method="POST">
<div class="form-group">
<label>管理员用户名:</label><br>
<input type="text" class="form-control" name="user" value="" required>
</div>
<div class="form-group">
<label>管理员密码:</label><br>
<input type="text" class="form-control" name="pwd" value="123456" required>
</div>
<div class="form-group">
<label>绑定域名:</label><br>
<div class="input-group">
<input type="text" class="form-control" name="qz" value="" placeholder="输入二级前缀" required>
<div class="input-group-addon"><select name="domain">'.$select.'</select></div>
</div>
</div>
<div class="form-group">
<label>网站名称:</label><br>
<input type="text" class="form-control" name="sitename" value="'.$conf['sitename'].'">
</div>
<div class="form-group">
<label>站长QQ:</label><br>
<input type="text" class="form-control" name="qq" value="">
</div>
<div class="form-group">
<label>到期时间:</label><br>
<input type="date" class="form-control" name="endtime" value="'.date("Y-m-d",strtotime("+1 years")).'" required>
</div>
<input type="submit" class="btn btn-primary btn-block" value="确定添加"></form>';
echo '<br/><a href="./sitelist.php">>>返回分站列表</a>';
echo '</div></div>';
}
elseif($my=='edit')
{
$zid=intval($_GET['zid']);
$row=$DB->getRow("SELECT * FROM pre_site WHERE zid='$zid' AND upzid='{$userrow['zid']}' AND power=1 LIMIT 1");
if(!$row)
	showmsg('当前记录不存在！',3);
echo '<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">修改分站信息</div>';
echo '<div class="panel-body">';
echo '<form action="./sitelist.php?my=edit_submit&zid='.$zid.'" method="POST">
<div class="form-group">
<label>绑定域名:</label><br>
<input type="text" class="form-control" name="domain" value="'.$row['domain'].'" disabled>
</div>
<div class="form-group">
<label>额外域名:</label><br>
<input type="text" class="form-control" name="domain2" value="'.$row['domain2'].'" placeholder="没有请留空">
</div>
<div class="form-group">
<label>站点名称:</label><br>
<input type="text" class="form-control" name="sitename" value="'.$row['sitename'].'">
</div>
<div class="form-group">
<label>到期时间:</label><br>
<input type="date" class="form-control" name="endtime" value="'.date("Y-m-d",strtotime($row['endtime'])).'" required>
</div>
<input type="submit" class="btn btn-primary btn-block" value="确定修改"></form>';
echo '<br/><a href="./sitelist.php">>>返回分站列表</a>';
echo '</div></div>';
}
elseif($my=='add_submit')
{
if(!$conf['fenzhan_adds'])showmsg('请在前台开通分站');
$user=trim(htmlspecialchars(strip_tags(daddslashes($_POST['user']))));
$pwd=trim(htmlspecialchars(strip_tags(daddslashes($_POST['pwd']))));
$qz = trim(htmlspecialchars(strtolower(daddslashes($_POST['qz']))));
$domain = trim(htmlspecialchars(strtolower(strip_tags(daddslashes($_POST['domain'])))));
$qq=trim(htmlspecialchars(strip_tags(daddslashes($_POST['qq']))));
$endtime=trim(htmlspecialchars(strip_tags(daddslashes($_POST['endtime']))));
$sitename=trim(htmlspecialchars(strip_tags(daddslashes($_POST['sitename']))));
$keywords=$conf['keywords'];
$description=$conf['description'];
$domain = $qz . '.' . $domain;
$thtime =date("Y-m-d").' 00:00:00';
if($user==NULL or $pwd==NULL or $qz==NULL or $domain==NULL or $endtime==NULL){
	showmsg('保存错误,请确保每项都不为空!',3);
} elseif (!in_array($_POST['domain'],explode(',',$conf['fenzhan_domain']))) {
	showmsg('域名后缀不存在！');
} elseif (strlen($qz) < 2 || strlen($qz) > 10 || !preg_match('/^[a-z0-9\-]+$/',$qz)) {
	showmsg('域名前缀不合格！');
} elseif (!preg_match('/^[a-zA-Z0-9]+$/',$user)) {
	showmsg('用户名只能为英文或数字！');
} elseif (!preg_match('/^[a-zA-Z0-9\_\-\.]+$/',$domain)) {
	showmsg('域名格式不正确');
} elseif ($DB->getRow("SELECT zid FROM pre_site WHERE user=:user LIMIT 1", [':user'=>$user])) {
	showmsg('用户名已存在！');
} elseif (strlen($pwd) < 6) {
	showmsg('密码不能低于6位');
} elseif (strlen($sitename) < 2) {
	showmsg('网站名称太短！');
} elseif (strlen($qq) < 5 || !preg_match('/^[0-9]+$/',$qq)) {
	showmsg('QQ格式不正确！');
} elseif ($DB->getRow("SELECT zid FROM pre_site WHERE domain=:domain OR domain2=:domain LIMIT 1", [':domain'=>$domain]) || $qz=='www' || $domain==$_SERVER['HTTP_HOST'] || in_array($domain,explode(',',$conf['fenzhan_remain']))) {
	showmsg('此前缀已被使用！');
} elseif ($DB->getColumn("SELECT count(*) FROM pre_site WHERE upzid='{$userrow['zid']}' and addtime>'$thtime'")>20) {
	showmsg('你今天添加的分站较多，暂无法后台手动添加，请直接使用前台网址自助开通分站！',3);
} else {
if($conf['fenzhan_html']==1){
	$anounce=$conf['anounce'];
	$alert=$conf['alert'];
}
$sql="INSERT INTO `pre_site` (`upzid`,`power`,`domain`,`domain2`,`user`,`pwd`,`rmb`,`qq`,`sitename`,`keywords`,`description`,`anounce`,`alert`,`addtime`,`endtime`,`status`) VALUES (:upzid, :power, :domain, NULL, :user, :pwd, :rmb, :qq, :sitename, :keywords, :description, :anounce, :alert, NOW(), :endtime, 1)";
$data = [':upzid'=>$userrow['zid'], ':power'=>1, ':domain'=>$domain, ':user'=>$user, ':pwd'=>$pwd, ':rmb'=>'0.00', ':qq'=>$qq, ':sitename'=>$sitename, ':keywords'=>$keywords, ':description'=>$description, ':anounce'=>$anounce, ':alert'=>$alert, ':endtime'=>$endtime];
if($DB->exec($sql, $data)){
	showmsg('添加分站成功！<br/><br/><a href="./sitelist.php">>>返回分站列表</a>',1);
}else
	showmsg('添加分站失败！'.$DB->error(),4);
}
}
elseif($my=='edit_submit')
{
$zid=intval($_GET['zid']);
$rows=$DB->getRow("SELECT * FROM pre_site WHERE zid='$zid' AND upzid='{$userrow['zid']}' AND power=1 LIMIT 1");
if(!$rows)
	showmsg('当前记录不存在！',3);
$domain2=trim(strtolower(htmlspecialchars(strip_tags(daddslashes($_POST['domain2'])))));
$endtime=trim(htmlspecialchars(strip_tags(daddslashes($_POST['endtime']))));
$sitename=trim(htmlspecialchars(strip_tags(daddslashes($_POST['sitename']))));
if($sitename==NULL or $endtime==NULL){
showmsg('保存错误,请确保每项都不为空!',3);
} elseif (!empty($domain2) && !preg_match('/^[a-zA-Z0-9\_\-\.]+$/',$domain2)) {
	showmsg('域名格式不正确');
} else {
if (!empty($domain2) && $DB->getRow("SELECT zid FROM pre_site WHERE (domain=:domain OR domain2=:domain) AND zid!=:zid LIMIT 1", [':domain'=>$domain2, ':zid'=>$zid]) || $domain2==$_SERVER['HTTP_HOST'] || !empty($domain2) && (in_array($domain2,explode(',',$conf['fenzhan_remain'])) || in_array($domain2,explode(',',$conf['fenzhan_domain'])))) {
	showmsg('此域名已被使用！');
}elseif(strpos($domain2,'www.')!==false){
	$domain=str_replace('www.','',$domain2);
	if(in_array($domain,explode(',',$conf['fenzhan_remain'])) || in_array($domain,explode(',',$conf['fenzhan_domain'])))
		showmsg('此域名已被使用！');
}
if($DB->exec("UPDATE pre_site SET domain2=:domain2,sitename=:sitename,endtime=:endtime WHERE zid=:zid", [':domain2'=>$domain2, ':sitename'=>$sitename, ':endtime'=>$endtime, ':zid'=>$zid])!==false)
	showmsg('修改分站成功！<br/><br/><a href="./sitelist.php">>>返回分站列表</a>',1);
else
	showmsg('修改分站失败！'.$DB->error(),4);
}
}
elseif($my=='delete')
{
$zid=intval($_GET['zid']);
$srow=$DB->getRow("SELECT * FROM pre_site WHERE zid='{$zid}' limit 1");
if($srow['rmb']>=1)showmsg('当前站点余额较多，无法删除',3);
$sql="DELETE FROM pre_site WHERE zid='$zid' AND upzid='{$userrow['zid']}' AND power=1";
if($DB->exec($sql)!==false)
	showmsg('删除成功！<br/><br/><a href="./sitelist.php">>>返回分站列表</a>',1);
else
	showmsg('删除失败！'.$DB->error(),4);
}
else
{

$numrows=$DB->getColumn("SELECT count(*) FROM pre_site WHERE upzid='{$userrow['zid']}' AND power=1");
if(isset($_GET['zid'])){
	$zid=intval($_GET['zid']);
	$sql = " zid={$zid} AND upzid='{$userrow['zid']}' AND power=1";
}elseif(isset($_GET['kw'])){
	$kw=daddslashes($_GET['kw']);
	$sql = " (user='{$kw}' OR domain='{$kw}' OR qq='{$kw}' OR zid='{$kw}') AND upzid='{$userrow['zid']}' AND power=1";
}else{
	$sql = " upzid='{$userrow['zid']}' AND power=1";
}
$con='你共有 <b>'.$numrows.'</b> 个下级分站<br/>'.($conf['fenzhan_adds']==1?'<a href="./sitelist.php?my=add" class="btn btn-primary">添加分站</a>':null).'&nbsp;<a href="#" data-toggle="modal" data-target="#search" id="search" class="btn btn-success">搜索</a>';

echo '<div class="alert" style="background-color: #9999CC;color: white;">';
echo $con;
echo '</div>';

?>
      <div class="table-responsive">
        <table class="table table-striped">
          <thead><tr><th>ZID</th><th>用户名</th><th>站点名称/站长QQ</th><th>余额</th><th>开通/到期时间</th><th>绑定域名</th><th>操作</th></tr></thead>
          <tbody>
<?php
$pagesize=30;
$pages=ceil($numrows/$pagesize);
$page=isset($_GET['page'])?intval($_GET['page']):1;
$offset=$pagesize*($page - 1);

$rs=$DB->query("SELECT * FROM pre_site WHERE{$sql} ORDER BY zid DESC LIMIT $offset,$pagesize");
while($res = $rs->fetch())
{
echo '<tr><td><b>'.$res['zid'].'</b></td><td>'.$res['user'].'</td><td>'.$res['sitename'].'<br/>'.$res['qq'].'</td><td>'.$res['rmb'].'</td><td>'.$res['addtime'].'<br/>'.$res['endtime'].'</td><td>'.$res['domain'].'<br/>'.$res['domain2'].'</td><td><a href="./sitelist.php?my=edit&zid='.$res['zid'].'" class="btn btn-info btn-xs">编辑</a>&nbsp;<a href="./sitelist.php?my=delete&zid='.$res['zid'].'" class="btn btn-xs btn-danger" onclick="return confirm(\'你确实要删除此站点吗？\');">删除</a></td></tr>';
}
?>
          </tbody>
        </table>
      </div>
<?php
echo'<ul class="pagination" style="margin-left:1em">';
$first=1;
$prev=$page-1;
$next=$page+1;
$last=$pages;
if ($page>1)
{
echo '<li><a href="sitelist.php?page='.$first.$link.'">首页</a></li>';
echo '<li><a href="sitelist.php?page='.$prev.$link.'">&laquo;</a></li>';
} else {
echo '<li class="disabled"><a>首页</a></li>';
echo '<li class="disabled"><a>&laquo;</a></li>';
}
$start=$page-10>1?$page-10:1;
$end=$page+10<$pages?$page+10:$pages;
for ($i=$start;$i<$page;$i++)
echo '<li><a href="sitelist.php?page='.$i.$link.'">'.$i .'</a></li>';
echo '<li class="disabled"><a>'.$page.'</a></li>';
for ($i=$page+1;$i<=$end;$i++)
echo '<li><a href="sitelist.php?page='.$i.$link.'">'.$i .'</a></li>';
if ($page<$pages)
{
echo '<li><a href="sitelist.php?page='.$next.$link.'">&raquo;</a></li>';
echo '<li><a href="sitelist.php?page='.$last.$link.'">尾页</a></li>';
} else {
echo '<li class="disabled"><a>&raquo;</a></li>';
echo '<li class="disabled"><a>尾页</a></li>';
}
echo'</ul>';
#分页
}
?>
    </div>
  </div>
<?php include './foot.php';?>
</body>
</html>