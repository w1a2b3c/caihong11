<?php
require '../includes/common.php';
if($islogin2==1){}else exit("<script language='javascript'>window.location.href='./login.php';</script>");
$title = '消息列表';
include 'head.php';	
?>
      <div class="wrapper">
          <div class="col-sm-12">
	<div class="panel panel-default">
    <div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">常见问题</div>	  
        <div class="panel-body">
						<div id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">怎么获取收益提成的？</a>
                                    </h4>
                                </div>
                                <div id="collapseOne" class="panel-collapse in" style="height: auto;">
                                    <div class="panel-body">
                                    你只需要把你的网址发给你的用户让他下单，一旦下单付款成功，你的账户就会增加你所赚差价的金额，自己是可以设置销售价格的哦！<br>
                                    差价提成算法：销售价格-拿货价格=提成
									</div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed">怎么去推广自己的站点？</a>
                                    </h4>
                                </div>
                                <div id="collapseTwo" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
										你也可以通过（<a href="shoplist.php"><font color="#337ab7">商品管理</font></a>）把商品售价提高，那样利润就会更高，但是也要有分寸。<br>
										最简单的方法就是QQ群，多加点互赞、名片赞群，发名片赞广告。空间互踩群，发空间人气、说说赞等相关广告。<br>
										当然还有百度贴吧、网上各大论坛，你都可以去推广，付出越多，收益越大！不要局限在自己那几十个或者几百个QQ好友里面，互联网的陌生人才是你赚钱的方向！
									</div>
                                </div>
                            </div>
							<div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed">账户提现需要注意什么？</a>
                                    </h4>
                                </div>
                                <div id="collapseThree" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
										只要你的账户达到<?php echo $conf['tixian_min']; ?>元，即可提现，提现会扣取<?php echo (100-$conf['tixian_rate'])?>%的手续费，提现一般会在24小时内审核成功后并打款！<br>
										提现方式支持：支付宝 QQ  微信，需要上传相关的收款二维码（没有上传的提现不会到账）！
									</div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFourth" class="collapsed">下单前需要充值余额吗？</a>
                                    </h4>
                                </div>
                                <div id="collapseFourth" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
										完全不需要的哈，用户在你网站下单后，差价一样会到达你的账户里面，而且你赚取的钱还可以在后台以代理价格开单或者提现！<br>
                                        除非你自己在后台下单，后台下单是按拿货价格下单的！
									</div>
                                </div>
                            </div>
							<div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="collapsed">下单需要注意什么问题？</a>
                                    </h4>
                                </div>
                                <div id="collapseFive" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
										下单前不管是你还是你的用户，必须的提醒他，看清楚商品说明，避免刷单不到账的情况！<br>
                                        订单有什么问题直接发给客服QQ，让客服处理！
									</div>
                                </div>
                            </div>
							<div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" class="collapsed">网址给QQ或微信爆毒拦截？</a>
                                    </h4>
                                </div>
								<div id="collapseSeven" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
										在用户中心会看到域名防红链接<br>
										复制即可发给QQ或微信好友，通过短网址在QQ或微信访问是不会报毒的。<br>
									</div>
								</div>
                            </div>
							<div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive66" class="collapsed">售后和处理订单需要自己来嘛？</a>
                                    </h4>
                                </div>
                                <div id="collapseFive66" class="panel-collapse collapse" style="height: 0px;">
                                    <div class="panel-body">
										把你网站分享出去，别人下单后你就会有钱赚！或者你在外面接单，自己到后台下单！<br>
                                        你只需要宣传或者接单就行了，订单处理和售后等有我们的客服来解决！
									</div>
                                </div>
                            </div>
                                                      
                        </div>
					</div>
	</div>
</div>
<?php include './foot.php';?>
</body>
</html>