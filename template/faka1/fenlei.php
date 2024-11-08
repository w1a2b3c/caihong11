<?php
if(!defined('IN_CRONLITE'))exit();
include_once TEMPLATE_ROOT.'faka/inc/head.php';
$data=$DB->getAll("SELECT * FROM pre_class WHERE active=1 order by sort asc");
$count = count($data);
?>
<style>.imagesqr {
    background-color: #00bb9c;
    margin: 20px;}.imagesqr .fl {
    float: left;
    font-size: 14px;
    height: 125px;
    width: 200px;
    border-radius: 5px;
    text-align: center;
    background-color: #ffffff;
    margin: 10px;
    border: 1px outset #cccccc;
}.imagesqr .fl li {
    text-align: center;
    list-style: none;
    border-radius: 5px;
    border: 10px double #ffffff;
    background-color: #ffffff;
    margin: 10px;
    color: #000000;
}#kong {margin-top: 0px;border:0px;}
</style>
<div class="g-body" style="margin-bottom:0px;">
    <div id="kong">
        <div class="ziti" style="font-size: 16px;color: #7a7a7a;padding-top: 20px;padding-right: 6px;padding-bottom: 6px;padding-left: 4px;border-radius: 3px;">
      当前位置 -&gt; <a href="./">网站首页</a> -&gt; 商品分类
      </div>
      <div class="table">
          <div class="imagesqr">
			<a href="./">
              <div class="qr_bg_lq fl">
                <li><img src="assets/faka/images/fenleitubiao.png" height="44" width="44"> <p class="ziti">全部商品
                </p></li>
              </div>
            </a>
<?php foreach($data as $row) {?>
            <a href="./?cid=<?php echo $row['cid']?>" class="cid<?php echo $row['cid']?>">
              <div class="qr_bg_lq fl">
                <li><img src="<?php echo $row['shopimg']?>" height="44" width="44" onerror="this.src='assets/faka/images/fenleitubiao.png'"> <p class="ziti"><?php echo $row['name']?>
                </p></li>
              </div>
            </a>
<?php }?>
              </div>
        </div>
      
    </div>
 
  </div>
</div>
<?php include_once TEMPLATE_ROOT.'faka/inc/foot.php';?>
<script src="<?php echo $cdnpublic?>jquery/1.12.4/jquery.min.js"></script>
<?php if($conf['classblock']==1 || $conf['classblock']==2)include TEMPLATE_ROOT.'default/classblock.inc.php'; ?>
</body>
</html>