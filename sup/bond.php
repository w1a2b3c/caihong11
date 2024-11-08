<?php


include "../includes/common.php";
$title = "保证金管理";
include "./head.php";
if ($islogin3 == 1) {
} else {
	exit("<script language='javascript'>window.location.href='./login.php';</script>");
}
if ($_GET["mod"] == "thaw") {
	$money = round($_POST["money"], 2);
	if ($money > $suprow["bond"]) {
		showmsg("解冻金额不能大于您已缴纳金额！", 3);
	}
	if ($money <= 0) {
		showmsg("解冻金额不能小于等于0！", 3);
	}
	if ($DB->exec("update pre_supplier set rmb = rmb + '" . $money . "', bond = bond - '" . $money . "' where sid='" . $suprow["sid"] . "'") !== false) {
		showmsg("解冻保证金成功！<br/><br/><a href=\"./bond.php?act=thaw\">>>返回保证金解冻</a>", 1);
	} else {
		showmsg("解冻保证金失败！" . $DB->error(), 4);
	}
} elseif ($_GET["mod"] == "pay") {
	$money = round($_POST["money"], 2);
	if ($money > $suprow["rmb"]) {
		showmsg("您的余额不足以缴纳，请充值后再来！", 3);
	}
	if ($money <= 0) {
		showmsg("缴纳金额不能小于等于0！", 3);
	}
	if ($DB->exec("update pre_supplier set rmb = rmb - '" . $money . "', bond = bond + '" . $money . "' where sid='" . $suprow["sid"] . "'") !== false) {
		showmsg("缴纳保证金成功！<br/><br/><a href=\"./bond.php\">>>返回保证金缴纳</a>", 1);
	} else {
		showmsg("缴纳保证金失败！" . $DB->error(), 4);
	}
}
if ($_GET["act"] == "thaw") {
	?><div class="col-md-12 center-panel panel-default" style="float: none;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">保证金解冻</h3>
        </div>
        <div class="panel-body">
            <form action="?mod=thaw" method="post" class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">需缴纳</label>
                    <div class="col-sm-10"><input type="text" name="bzj" value="<?php echo $conf["sup_bond"];?>元" class="form-control" disabled="disabled" /></div>
                </div><br/>
                <div class="form-group">
                    <label class="col-sm-2 control-label">已缴纳</label>
                    <div class="col-sm-10"><input type="text" name="bzj" value="<?php echo $suprow["bond"];?>元" class="form-control" disabled="disabled" /></div>
                </div><br/>
                <div class="form-group">
                    <label class="col-sm-2 control-label">您的余额</label>
                    <div class="col-sm-10"><input type="text" name="bzj" value="<?php echo $suprow["rmb"];?>元" class="form-control" disabled="disabled" /></div>
                </div><br/>
                <div class="form-group">
                    <label class="col-sm-2 control-label">解冻</label>
                    <div class="col-sm-10"><input type="text" name="money" value="" class="form-control" placeholder="解冻金额"/><pre><font color="green">解冻金额前需将所有商品隐藏即可解冻</font></pre></div>
                </div><br/>
                <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="解冻" class="btn btn-primary form-control"/></div><br/><br/>
                <span style="color:#0d47e0;">保证金介绍</span><br><br>
                <span style="color:#f04e66;">1.缴纳保证金上架商品免审（需符合平台要求，否则会受到处罚或封号）！</span><br><br>
                <span style="color:#8a6d3b;">2.保证金可随时解冻到余额进行提现，也可以联系客服将余额缴纳到保证金！</span><br><br>

            </form>
        </div>
    </div>
</div>
<?php 
} else {
	?><div class="col-md-12 center-panel panel-default" style="float: none;">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">保证金缴纳</h3>
        </div>
        <div class="panel-body">
            <form action="?mod=pay" method="post" class="form-horizontal" role="form">
                <div class="form-group">
                    <label class="col-sm-2 control-label">需缴纳</label>
                    <div class="col-sm-10"><input type="text" name="bzj" value="<?php echo $conf["sup_bond"];?>元" class="form-control" disabled="disabled" /></div>
                </div><br/>
                <div class="form-group">
                    <label class="col-sm-2 control-label">已缴纳</label>
                    <div class="col-sm-10"><input type="text" name="bzj" value="<?php echo $suprow["bond"];?>元" class="form-control" disabled="disabled" /></div>
                </div><br/>
                <div class="form-group">
                    <label class="col-sm-2 control-label">您的余额</label>
                    <div class="col-sm-10"><input type="text" name="bzj" value="<?php echo $suprow["rmb"];?>元" class="form-control" disabled="disabled" /></div>
                </div><br/>
                <div class="form-group">
                    <label class="col-sm-2 control-label">缴纳</label>
                    <div class="col-sm-10"><input type="text" name="money" value="" class="form-control" placeholder="缴纳金额"/><pre><font color="green">缴纳足够的保证金即可上架商品</font></pre></div>
                </div><br/>
                <div class="col-sm-offset-2 col-sm-10"><input type="submit" name="submit" value="缴纳" class="btn btn-primary form-control"/></div><br/><br/>
                <span style="color:#0d47e0;">保证金介绍</span><br><br>
                <span style="color:#f04e66;">1.缴纳保证金上架商品免审（需符合平台要求，否则会受到处罚或封号）！</span><br><br>
                <span style="color:#8a6d3b;">2.保证金可随时解冻到余额进行提现，也可以联系客服将余额缴纳到保证金！</span><br><br>

            </form>
        </div>
    </div>
</div>
<?php 
}