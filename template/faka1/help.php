<?php
if(!defined('IN_CRONLITE'))exit();

$hometitle = '帮助中心 - '.$conf['sitename'];
include_once TEMPLATE_ROOT.'faka/inc/head.php';
?>
<div class="g-body">
<br/>
<br/>
<div id="bd">

<div class="ziti" style="font-size: 16px;color: #7a7a7a;padding-top: 12px;padding-right: 6px;padding-bottom: 6px;padding-left: 4px;border-radius: 3px;">当前位置 -&gt;
    <a href="./">网站首页</a> -&gt; 帮助中心
</div>

<div id="bar">
<div class="bar_top">关于我们</div><br />

<ul id="bar_ul">
<?php if(!empty($conf['template_about'])){?><li ><a href="./?mod=about">关于我们</a></li><?php }?>
<li class="va"><a href="./?mod=help">帮助中心</a></li>
</ul>
</div>


<div id="bar_r">


<div id="body_xiao" style="background-image:url('assets/faka/images/aboutbg.png');background-repeat: no-repeat; background-color:#FFFFFF;">
    	<div class="table">

<br/>
<span class="title"><p style="text-align: center;">
    <strong style="font-size: 30px; white-space: normal;">帮助中心</strong>
</p></span><br/>

    	
<span style="font-size: 16px; white-space: normal;" class="ziti"><?php echo $conf['template_help']?></span>


</div>
</div>
</div>
</div></div>
<?php include_once TEMPLATE_ROOT.'faka/inc/foot.php';?>
</body>
</html>