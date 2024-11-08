<?php
if(!defined('IN_CRONLITE'))exit();
?>
<div class="dibucss">
    <div class="fui-navbar footerBox">
        <a href="./" class="external nav-item <?php if(!$_GET['mod'] || $_GET['mod']=='wapso'){echo 'active';} ?>">
            <span class="iconfont icon-home"></span>
            <span class="label">首页</span>
        </a>
        <a href="./?mod=wapfenlei" class="external nav-item <?php if($_GET['mod']=='wapfenlei'){echo 'active';} ?>">
            <span class="iconfont icon-list"></span>
            <span class="label">商品分类</span>
        </a>
        <a href="./?mod=wapquery" class="external nav-item <?php if($_GET['mod']=='wapquery'){echo 'active';} ?>">
            <span class="iconfont icon-dingdan"></span>
            <span class="label">订单查询</span>
            </a>
        <a href="./?mod=wapkf" class="external nav-item <?php if($_GET['mod']=='wapkf'){echo 'active';} ?>">
            <span class="iconfont icon-service"></span>
            <span class="label">联系客服</span>
        </a>
        <a href="./user" class="external nav-item ">
            <span class="iconfont icon-user"></span>
            <span class="label">用户中心</span>
        </a>
	</div>
</div>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<script src="<?php echo $cdnserver?>assets/faka/js/jquery.dlmenu.js"></script>
<script>
$(function(){
	$('#dl-menu').dlmenu();
});
</script>