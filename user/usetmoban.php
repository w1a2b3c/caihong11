<?php
require '../includes/common.php';
if ($islogin2 == 1) {
} else exit("<script language='javascript'>window.location.href='./login.php';</script>");

$title = '网站设置';
include 'head.php';
if ($conf['fenzhan_cost2'] <= 0) $conf['fenzhan_cost2'] = $conf['fenzhan_price2'];
?>
<div class="wrapper">
	<div class="col-sm-12">
		<?php
		$mod = isset($_GET['mod']) ? $_GET['mod'] : null;
		if ($mod == 'user_n') {
			if (!checkRefererHost()) exit();
			$qq = daddslashes(htmlspecialchars(strip_tags($_POST['qq'])));
			$pay_type = daddslashes(intval($_POST['pay_type']));
			$pay_account = daddslashes(htmlspecialchars(strip_tags($_POST['pay_account'])));
			$pay_name = daddslashes(htmlspecialchars(strip_tags($_POST['pay_name'])));
			$pwd = daddslashes(htmlspecialchars(strip_tags($_POST['pwd'])));
			if (!empty($pwd) && !preg_match('/^[a-zA-Z0-9\_\!\@\#\$~\%\^\&\*.,]+$/', $pwd)) {
				exit("<script language='javascript'>alert('密码只能为英文与数字！');history.go(-1);</script>");
			} elseif (!preg_match('/^[0-9]{5,11}+$/', $qq)) {
				exit("<script language='javascript'>alert('QQ格式不正确！');history.go(-1);</script>");
			} else {
				$DB->exec("UPDATE pre_site SET qq=:qq,pay_type=:pay_type,pay_account=:pay_account,pay_name=:pay_name WHERE zid=:zid", [':qq' => $qq, ':pay_type' => $pay_type, ':pay_account' => $pay_account, ':pay_name' => $pay_name, ':zid' => $userrow['zid']]);
				if (!empty($pwd)) $DB->exec("update pre_site set pwd=:pwd where zid=:zid", [':pwd' => $pwd, ':zid' => $userrow['zid']]);
				exit("<script language='javascript'>alert('修改保存成功！');history.go(-1);</script>");
			}
		} elseif ($mod == 'user') {
			$url = 'https://api.fcypay.com/';
			$m = md5(rand(1000000, 9999999) . date('YmdHis') . uniqid());
			$code_url = $url . 'get_openid_qrcode?mark=' . $m;
			$cron_url = $url . 'get_openid_status?mark=' . $m;
		?>
			<div class="panel panel-default">
				<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">用户资料设置</div>
				<div class="panel-body">
					<form action="./uset.php?mod=user_n" method="post" role="form">
						<?php if ($conf['login_qq'] == 1) { ?>
							<div class="form-group">
								<label><img src="https://qzonestyle.gtimg.cn/qzone/vas/opensns/res/img/bt_blue_24X24.png">&nbsp;QQ快捷登录：</label><?php if ($userrow['qq_openid']) { ?><font color="green">已绑定</font>&nbsp;<a class="btn btn-xs btn-default" href="javascript:unbind('qq')">解绑</a><?php } else { ?><font color="red">未绑定</font>&nbsp;<a class="btn btn-xs btn-success" href="javascript:connect('qq')">立即绑定</a><?php } ?>
							</div>
						<?php } ?>
						<div class="form-group">
							<label>登录用户名:</label><br />
							<input type="text" value="<?php echo $userrow['user']; ?>" class="form-control" disabled />
						</div>
						<div class="form-group">
							<label>联系ＱＱ:</label><br />
							<input type="text" name="qq" value="<?php echo $userrow['qq']; ?>" class="form-control" placeholder="用于联系与找回密码" required />
						</div>
						<?php if ($userrow['power'] > 0) { ?>
							<div class="form-group">
								<label>提现方式:</label><br />
								<select class="form-control" name="pay_type" default="<?php echo $userrow['pay_type'] ?>"><?php if ($conf['fenzhan_tixian_alipay'] == 1) { ?><option value="0">支付宝</option><?php }
																																																		if ($conf['fenzhan_tixian_wx'] == 1) { ?><option value="1">微信</option><?php }
																																																																			if ($conf['fenzhan_tixian_qq'] == 1) { ?><option value="2">QQ钱包</option><?php } ?></select>
							</div>
							<div class="form-group">
								<label>提现账号:</label><br />
								<input type="text" name="pay_account" value="<?php echo $userrow['pay_account']; ?>" class="form-control" />
								<a href="javascript:getopenid()" class="btn btn-info" style="display:none" id="getopenid">自动获取</a>
							</div>
							<div class="form-group">
								<label>提现姓名:</label><br />
								<input type="text" name="pay_name" value="<?php echo $userrow['pay_name']; ?>" class="form-control" />
							</div>
						<?php } ?>
						<?php if (substr($userrow['user'], 0, 3) != 'qq_') { ?>
							<div class="form-group">
								<label>重置密码:</label><br />
								<input type="text" name="pwd" value="" class="form-control" placeholder="不修改请留空" />
							</div>
						<?php } ?>
						<div class="form-group">
							<input type="submit" name="submit" value="修改" class="btn btn-primary form-control" />
						</div>
					</form>
				</div>
			</div>
			<?php if (substr($userrow['user'], 0, 3) == 'qq_') { ?>
				<div class="panel panel-default">
					<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">登录用户名与密码设置</div>
					<div class="panel-body">
						<div class="alert alert-info">设置登录用户名与密码之后，就可以使用对接与用户名密码登录了，不会影响到QQ快捷登录</div>
						<form onsubmit="return setpwd()" method="post" role="form">
							<div class="form-group">
								<label>登录用户名:</label><br />
								<input type="text" name="user" placeholder="输入登录用户名" class="form-control" required />
								<font color="green">登录用户名一经设置无法修改</font>
							</div>
							<div class="form-group">
								<label>登录密码:</label><br />
								<input type="text" name="pwd" placeholder="输入6位以上密码" class="form-control" required />
							</div>
							<div class="form-group">
								<input type="submit" name="submit" value="保存" class="btn btn-primary form-control" />
							</div>
						</form>
					</div>
				</div>
			<?php } ?>
		<?php
		} elseif ($mod == 'site_n' && $userrow['power'] > 0) {
			if (!checkRefererHost()) exit();
			$sitename = trim(htmlspecialchars(strip_tags($_POST['sitename'])));
			$title = trim(htmlspecialchars(strip_tags($_POST['title'])));
			$keywords = trim(htmlspecialchars(strip_tags($_POST['keywords'])));
			$description = trim(htmlspecialchars(strip_tags($_POST['description'])));
			$kfqq = isset($_POST['kfqq']) ? trim(htmlspecialchars(strip_tags($_POST['kfqq']))) : null;
			$kfwx = isset($_POST['kfwx']) ? trim(htmlspecialchars(strip_tags($_POST['kfwx']))) : null;
			$anounce = $_POST['anounce'];
			$modal = $_POST['modal'];
			$bottom = $_POST['bottom'];
			$alert = $_POST['alert'];
			$ktfz_price = trim($_POST['ktfz_price']);
			$ktfz_price2 = trim($_POST['ktfz_price2']);
			$ktfz_domain = trim($_POST['ktfz_domain']);
			$template = isset($_POST['template']) ? trim($_POST['template']) : null;
			$appurl = trim($_POST['appurl']);
			if ($sitename == null) {
				exit("<script language='javascript'>alert('请确保各项不能为空');history.go(-1);</script>");
			} else {
				if (!empty($template) && (!preg_match('/^[a-zA-Z0-9]+$/', $template) || \lib\Template::exists($template) == false)) exit("<script language='javascript'>alert('该模板首页文件不存在！');history.go(-1);</script>");
				if ($userrow['power'] == 2) {
					if (!is_numeric($ktfz_price) || !preg_match('/^[0-9.]+$/', $ktfz_price) || $ktfz_price < 0) exit("<script language='javascript'>alert('普及分站价格输入不规范');history.go(-1);</script>");
					if (!is_numeric($ktfz_price2) || !preg_match('/^[0-9.]+$/', $ktfz_price2) || $ktfz_price2 < 0) exit("<script language='javascript'>alert('专业分站价格输入不规范');history.go(-1);</script>");
					if ($ktfz_price2 < $conf['fenzhan_cost2']) exit("<script language='javascript'>alert('专业分站价格不能低于成本价');history.go(-1);</script>");
					if ($ktfz_price2 < $ktfz_price) exit("<script language='javascript'>alert('专业分站价格不能低于普及分站价格');history.go(-1);</script>");
					if ($conf['fenzhan_edithtml'] == 1) {
						$sql = "UPDATE `pre_site` SET `sitename`=:sitename,`title`=:title,`keywords`=:keywords,`description`=:description,`kfqq`=:kfqq,`kfwx`=:kfwx,`anounce`=:anounce,`modal`=:modal,`bottom`=:bottom,`alert`=:alert,`ktfz_price`=:ktfz_price,`ktfz_price2`=:ktfz_price2,`ktfz_domain`=:ktfz_domain,`template`=:template,`appurl`=:appurl WHERE `zid`=:zid";
						$data = [':sitename' => $sitename, ':title' => $title, ':keywords' => $keywords, ':description' => $description, ':kfqq' => $kfqq, ':kfwx' => $kfwx, ':anounce' => $anounce, ':modal' => $modal, ':bottom' => $bottom, ':alert' => $alert, ':ktfz_price' => $ktfz_price, ':ktfz_price2' => $ktfz_price2, ':ktfz_domain' => $ktfz_domain, ':template' => $template, ':appurl' => $appurl, ':zid' => $zid];
					} else {
						$sql = "UPDATE `pre_site` SET `sitename`=:sitename,`title`=:title,`keywords`=:keywords,`description`=:description,`kfqq`=:kfqq,`kfwx`=:kfwx,`ktfz_price`=:ktfz_price,`ktfz_price2`=:ktfz_price2,`ktfz_domain`=:ktfz_domain,`template`=:template,`appurl`=:appurl WHERE `zid`=:zid";
						$data = [':sitename' => $sitename, ':title' => $title, ':keywords' => $keywords, ':description' => $description, ':kfqq' => $kfqq, ':kfwx' => $kfwx, ':ktfz_price' => $ktfz_price, ':ktfz_price2' => $ktfz_price2, ':ktfz_domain' => $ktfz_domain, ':template' => $template, ':appurl' => $appurl, ':zid' => $zid];
					}
					$sds = $DB->exec($sql, $data);
				} else {
					if ($conf['fenzhan_edithtml'] == 1) {
						$sql = "UPDATE `pre_site` SET `sitename`=:sitename,`title`=:title,`keywords`=:keywords,`description`=:description,`kfqq`=:kfqq,`kfwx`=:kfwx,`anounce`=:anounce,`modal`=:modal,`bottom`=:bottom,`alert`=:alert,`template`=:template,`appurl`=:appurl WHERE `zid`=:zid";
						$data = [':sitename' => $sitename, ':title' => $title, ':keywords' => $keywords, ':description' => $description, ':kfqq' => $kfqq, ':kfwx' => $kfwx, ':anounce' => $anounce, ':modal' => $modal, ':bottom' => $bottom, ':alert' => $alert, ':template' => $template, ':appurl' => $appurl, ':zid' => $zid];
					} else {
						$sql = "UPDATE `pre_site` SET `sitename`=:sitename,`title`=:title,`keywords`=:keywords,`description`=:description,`kfqq`=:kfqq,`kfwx`=:kfwx,`template`=:template,`appurl`=:appurl WHERE `zid`=:zid";
						$data = [':sitename' => $sitename, ':title' => $title, ':keywords' => $keywords, ':description' => $description, ':kfqq' => $kfqq, ':kfwx' => $kfwx, ':template' => $template, ':appurl' => $appurl, ':zid' => $zid];
					}
					$sds = $DB->exec($sql, $data);
				}
				if ($sds !== false) exit("<script language='javascript'>alert('修改保存成功！');history.go(-1);</script>");
				else exit("<script language='javascript'>alert('修改保存失败:" . $DB->error() . "');history.go(-1);</script>");
			}
		} elseif ($mod == 'site' && $userrow['power'] > 0) {
			$mblist = \lib\Template::getList();
		?>
			<div class="panel panel-default">
				<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">网站信息设置</div>
				<div class="panel-body">
					<form action="./uset.php?mod=site_n" method="post" role="form">
						<?php if ($conf['fenzhan_template'] == 1) { ?>
							<div class="form-group" style="display: none;">
								<label>首页模板设置</label><br>
								<select class="form-control" name="template">
									<option value="0">默认模板</option>
									<?php foreach ($mblist as $row) {
										echo '<option value="' . $row . '" ' . ($userrow['template'] == $row ? 'selected' : null) . '>' . $row . '</option>';
									}
									?>
								</select>
							</div>
						<?php } ?>
						<div class="form-group">
							<label>网站名称:</label><br>
							<input type="text" name="sitename" value="<?php echo $userrow['sitename']; ?>" class="form-control" required />
						</div>
						<div class="form-group">
							<label>标题栏后缀</label><br>
							<input type="text" name="title" value="<?php echo $userrow['title']; ?>" class="form-control" />
						</div>
						<div class="form-group">
							<label>关键字</label><br>
							<input type="text" name="keywords" value="<?php echo $userrow['keywords']; ?>" class="form-control" />
						</div>
						<div class="form-group">
							<label>网站描述</label><br>
							<input type="text" name="description" value="<?php echo $userrow['description']; ?>" class="form-control" />
						</div>
						<?php if ($conf['fenzhan_kfqq'] == 1) { ?>
							<div class="form-group">
								<label>客服ＱＱ</label><br>
								<input type="text" name="kfqq" value="<?php echo $userrow['kfqq']; ?>" class="form-control" placeholder="留空自动同步主站客服ＱＱ（推荐）" />
							</div>
							<?php if ($newuserhead) { ?>
								<div class="form-group">
									<label>客服微信</label><br>
									<div class="input-group"><input type="text" name="kfwx" value="<?php echo $userrow['kfwx']; ?>" class="form-control" placeholder="留空自动同步主站客服微信或只显示QQ（推荐）" /><span class="input-group-btn"><a href="./uset.php?mod=upwxqrcode" class="btn btn-default">上传二维码</a></span></div>
								</div>
						<?php }
						} ?>
						<?php if ($conf['fenzhan_edithtml'] == 1) { ?>
							<div class="form-group">
								<label>首页公告</label><br>
								<textarea class="form-control" name="anounce" rows="6" placeholder="留空则和主站同步显示最新公告内容"><?php echo htmlspecialchars($userrow['anounce']); ?></textarea>
							</div>
							<div class="form-group">
								<label>首页弹出公告</label><br>
								<textarea class="form-control" name="modal" rows="5" placeholder="留空则和主站同步显示最新公告内容"><?php echo htmlspecialchars($userrow['modal']); ?></textarea>
							</div>
							<div class="form-group">
								<label>首页底部排版</label><br>
								<textarea class="form-control" name="bottom" rows="5" placeholder="留空则和主站同步显示最新公告内容"><?php echo htmlspecialchars($userrow['bottom']); ?></textarea>
							</div>
							<div class="form-group">
								<label>在线下单提示</label><br>
								<textarea class="form-control" name="alert" rows="5" placeholder="留空自动同步主站公告代码（推荐）"><?php echo htmlspecialchars($userrow['alert']); ?></textarea>
							</div>
						<?php }
						if ($userrow['power'] == 2) { ?>
							<div class="form-group">
								<label>普通分站价格</label><br>
								<input type="text" name="ktfz_price" value="<?php echo $userrow['ktfz_price'] > 0 ? $userrow['ktfz_price'] : $conf['fenzhan_price']; ?>" class="form-control" />
								<pre>前台自助开通分站的价格</pre>
							</div>
							<div class="form-group">
								<label>专业分站价格</label><br>
								<input type="text" name="ktfz_price2" value="<?php echo $userrow['ktfz_price2'] > $conf['fenzhan_cost2'] ? $userrow['ktfz_price2'] : $conf['fenzhan_price2']; ?>" class="form-control" />
								<pre>前台自助开通分站的价格，不能低于成本价<?php echo $conf['fenzhan_cost2'] ?>元</pre>
							</div>
							<div class="form-group">
								<label>分站可选择域名</label><br>
								<input type="text" name="ktfz_domain" value="<?php echo $userrow['ktfz_domain']; ?>" class="form-control" />
								<pre>默认使用主站域名，没有请留空，不要乱填写！多个域名用,隔开！</pre>
							</div>
						<?php } ?>

						<?php if ($conf['fenzhan_editd'] > 0) { ?>
							<div class="form-group">
								<label>本站域名</label><br>
								<div class="input-group">
									<input type="text" name="domain" value="<?php echo $userrow['domain']; ?>" class="form-control" disabled />
									<div class="input-group-addon"><a href="cdomain.php">自助更换域名</a></div>
								</div>
							</div>
						<?php } ?>
						<div class="form-group">
							<label>APP下载地址</label><br>
							<input type="text" name="appurl" value="<?php echo $userrow['appurl']; ?>" class="form-control" placeholder="没有请留空" />
						</div>
						<div class="form-group">
							<input type="submit" name="submit" value="修改" class="btn btn-primary form-control" />
						</div>
					</form>
				</div>
				<div class="panel-footer">
					<span class="glyphicon glyphicon-info-sign"></span>
					实用工具：<a href="http://www.w3school.com.cn/tiy/t.asp?f=html_basic" target="_blank" rel="noreferrer">HTML在线测试</a>｜<a href="http://pic.xiaojianjian.net/" target="_blank" rel="noreferrer">图床</a>｜<a href="http://music.hi.cn/" target="_blank" rel="noreferrer">音乐外链</a>
				</div>
			</div>
		<?php
		} elseif ($mod == 'site2' && $userrow['power'] > 0) {
			$mblist = \lib\Template::getList();
		?>
			<div class="panel panel-default">
				<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;">模板配置</div>
				<div class="panel-body">
					<form action="./uset.php?mod=site_n" method="post" role="form">
						<?php if ($conf['fenzhan_template'] == 1) { ?>
							<div class="form-group">
								<label>首页模板设置</label><br>
								<select class="form-control" name="template">
									<option value="0">默认模板</option>
									<?php foreach ($mblist as $row) {
										echo '<option value="' . $row . '" ' . ($userrow['template'] == $row ? 'selected' : null) . '>' . $row . '</option>';
									}
									?>
								</select>
							</div>
						<?php } ?>
						<div class="form-group" style="display: none;">
							<label>网站名称:</label><br>
							<input type="text" name="sitename" value="<?php echo $userrow['sitename']; ?>" class="form-control" required />
						</div>
						<div class="form-group" style="display: none;">
							<label>标题栏后缀</label><br>
							<input type="text" name="title" value="<?php echo $userrow['title']; ?>" class="form-control" />
						</div>
						<div class="form-group" style="display: none;">
							<label>关键字</label><br>
							<input type="text" name="keywords" value="<?php echo $userrow['keywords']; ?>" class="form-control" />
						</div>
						<div class="form-group" style="display: none;">
							<label>网站描述</label><br>
							<input type="text" name="description" value="<?php echo $userrow['description']; ?>" class="form-control" />
						</div>
						<?php if ($conf['fenzhan_kfqq'] == 1) { ?>
							<div class="form-group" style="display: none;">
								<label>客服ＱＱ</label><br>
								<input type="text" name="kfqq" value="<?php echo $userrow['kfqq']; ?>" class="form-control" placeholder="留空自动同步主站客服ＱＱ（推荐）" />
							</div>
							<?php if ($newuserhead) { ?>
								<div class="form-group" style="display: none;">
									<label>客服微信</label><br>
									<div class="input-group"><input type="text" name="kfwx" value="<?php echo $userrow['kfwx']; ?>" class="form-control" placeholder="留空自动同步主站客服微信或只显示QQ（推荐）" /><span class="input-group-btn"><a href="./uset.php?mod=upwxqrcode" class="btn btn-default">上传二维码</a></span></div>
								</div>
						<?php }
						} ?>
						<?php if ($conf['fenzhan_edithtml'] == 1) { ?>
							<div class="form-group" style="display: none;">
								<label>首页公告</label><br>
								<textarea class="form-control" name="anounce" rows="6" placeholder="留空则和主站同步显示最新公告内容"><?php echo htmlspecialchars($userrow['anounce']); ?></textarea>
							</div>
							<div class="form-group" style="display: none;">
								<label>首页弹出公告</label><br>
								<textarea class="form-control" name="modal" rows="5" placeholder="留空则和主站同步显示最新公告内容"><?php echo htmlspecialchars($userrow['modal']); ?></textarea>
							</div>
							<div class="form-group" style="display: none;">
								<label>首页底部排版</label><br>
								<textarea class="form-control" name="bottom" rows="5" placeholder="留空则和主站同步显示最新公告内容"><?php echo htmlspecialchars($userrow['bottom']); ?></textarea>
							</div>
							<div class="form-group" style="display: none;">
								<label>在线下单提示</label><br>
								<textarea class="form-control" name="alert" rows="5" placeholder="留空自动同步主站公告代码（推荐）"><?php echo htmlspecialchars($userrow['alert']); ?></textarea>
							</div>
						<?php }
						if ($userrow['power'] == 2) { ?>
							<div class="form-group" style="display: none;">
								<label>普通分站价格</label><br>
								<input type="text" name="ktfz_price" value="<?php echo $userrow['ktfz_price'] > 0 ? $userrow['ktfz_price'] : $conf['fenzhan_price']; ?>" class="form-control" />
								<pre>前台自助开通分站的价格</pre>
							</div>
							<div class="form-group" style="display: none;">
								<label>专业分站价格</label><br>
								<input type="text" name="ktfz_price2" value="<?php echo $userrow['ktfz_price2'] > $conf['fenzhan_cost2'] ? $userrow['ktfz_price2'] : $conf['fenzhan_price2']; ?>" class="form-control" />
								<pre>前台自助开通分站的价格，不能低于成本价<?php echo $conf['fenzhan_cost2'] ?>元</pre>
							</div>
							<div class="form-group" style="display: none;">
								<label>分站可选择域名</label><br>
								<input type="text" name="ktfz_domain" value="<?php echo $userrow['ktfz_domain']; ?>" class="form-control" />
								<pre>默认使用主站域名，没有请留空，不要乱填写！多个域名用,隔开！</pre>
							</div>
						<?php } ?>

						<?php if ($conf['fenzhan_editd'] > 0) { ?>
							<div class="form-group" style="display: none;">
								<label>本站域名</label><br>
								<div class="input-group">
									<input type="text" name="domain" value="<?php echo $userrow['domain']; ?>" class="form-control" disabled />
									<div class="input-group-addon"><a href="cdomain.php">自助更换域名</a></div>
								</div>
							</div>
						<?php } ?>
						<div class="form-group" style="display: none;">
							<label>APP下载地址</label><br>
							<input type="text" name="appurl" value="<?php echo $userrow['appurl']; ?>" class="form-control" placeholder="没有请留空" />
						</div>

						<div class="form-group">
							<input type="submit" name="submit" value="修改" class="btn btn-primary form-control" />
						</div>
					</form>
				</div>
				<div class="panel-footer">
					<span class="glyphicon glyphicon-info-sign"></span>
					实用工具：<a href="http://www.w3school.com.cn/tiy/t.asp?f=html_basic" target="_blank" rel="noreferrer">HTML在线测试</a>｜<a href="http://pic.xiaojianjian.net/" target="_blank" rel="noreferrer">图床</a>｜<a href="http://music.hi.cn/" target="_blank" rel="noreferrer">音乐外链</a>
				</div>
			</div>
		<?php
		} elseif ($mod == 'logo' && $userrow['power'] > 0 && $conf['fenzhan_edithtml'] == 1) {
			echo '<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >更改首页LOGO</div>
<div class="panel-body">提示：部分模板不显示logo图片，是正常现象！<br/>';
			if ($_POST['s'] == 1) {
				if (!checkRefererHost()) exit();
				$extension = explode('.', $_FILES['file']['name']);
				if (($length = count($extension)) > 1) {
					$ext = strtolower($extension[$length - 1]);
				}
				copy($_FILES['file']['tmp_name'], ROOT . 'assets/img/logo_' . $userrow['zid'] . '.png');
				echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果，按Ctrl+F5即可一键刷新缓存）";
			}
			if (file_exists(ROOT . 'assets/img/logo_' . $userrow['zid'] . '.png')) {
				$logo = '../assets/img/logo_' . $userrow['zid'] . '.png';
			} else {
				$logo = '../assets/img/logo.png';
			}
			echo '<form action="uset.php?mod=logo" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="file" id="file" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary form-control" value="确认上传" /></form><br>现在的图片：<br><img src="' . $logo . '" style="max-width:30%">';
			echo '</div></div>';
		} elseif ($mod == 'skimg' && $userrow['power'] > 0) {

			echo '<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >提现收款图设置</div>
<div class="panel-body">';
			if ($_POST['s'] == 1) {
				if (!checkRefererHost()) exit();
				$extension = explode('.', $_FILES['shoukuan']['name']);
				if (($length = count($extension)) > 1) {
					$ext = strtolower($extension[$length - 1]);
				}
				copy($_FILES['shoukuan']['tmp_name'], ROOT . 'assets/img/skimg/sk_' . $userrow['zid'] . '.png');
				echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果，按Ctrl+F5即可一键刷新缓存）";
			}
			if (file_exists(ROOT . 'assets/img/skimg/sk_' . $userrow['zid'] . '.png')) {
				$logo = '../assets/img/skimg/sk_' . $userrow['zid'] . '.png';
			} else {
				$logo = '../assets/img/skimg/sk.png';
			}
			echo '<form action="uset.php?mod=skimg" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="shoukuan" id="shoukuan" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary form-control" value="确认上传" /></form><br>现在的收款图：<br><img src="' . $logo . '" style="max-width:30%">';
			echo '</div></div>';
		} elseif ($mod == 'upwxqrcode' && $userrow['power'] > 0 && $conf['fenzhan_kfqq'] == 1) {
			if (isset($_GET['del']) && $_GET['del'] == 1) {
				if (file_exists(ROOT . 'assets/img/qrcode/wxqrcode_' . $userrow['zid'] . '.png')) {
					unlink(ROOT . 'assets/img/qrcode/wxqrcode_' . $userrow['zid'] . '.png');
				}
				exit("<script language='javascript'>alert('删除成功');window.location.href='./uset.php?mod=upwxqrcode';</script>");
			}
			echo '<div class="panel panel-default">
<div class="panel-heading font-bold" style="background-color: #9999CC;color: white;" >客服微信二维码设置</div>
<div class="panel-body">';
			if ($_POST['s'] == 1) {
				if (!checkRefererHost()) exit();
				$extension = explode('.', $_FILES['wxqrcode']['name']);
				if (($length = count($extension)) > 1) {
					$ext = strtolower($extension[$length - 1]);
				}
				copy($_FILES['wxqrcode']['tmp_name'], ROOT . 'assets/img/qrcode/wxqrcode_' . $userrow['zid'] . '.png');
				echo "成功上传文件!<br>（可能需要清空浏览器缓存才能看到效果，按Ctrl+F5即可一键刷新缓存）";
			}
			if (file_exists(ROOT . 'assets/img/qrcode/wxqrcode_' . $userrow['zid'] . '.png')) {
				$wxqrcode = '<br><img src="../assets/img/qrcode/wxqrcode_' . $userrow['zid'] . '.png" style="max-width:30%">';
			} elseif (!empty($userrow['kfqq'])) {
				$wxqrcode = '<b>根据客服QQ自动生成QQ二维码</b>';
			} else {
				$wxqrcode = '<b>自动同步主站</b>';
			}
			echo '<form action="uset.php?mod=upwxqrcode" method="POST" enctype="multipart/form-data"><label for="file"></label><input type="file" name="wxqrcode" id="wxqrcode" /><input type="hidden" name="s" value="1" /><br><input type="submit" class="btn btn-primary form-control" value="确认上传" /><br/><br/><a href="./uset.php?mod=upwxqrcode&del=1" class="btn btn-danger btn-block btn-sm">删除图片</a></form><br>现在的客服二维码：' . $wxqrcode . '';
			echo '</div></div>';
		} ?>
	</div>
</div>
<?php include './foot.php'; ?>
<script src="<?php echo $cdnpublic ?>jquery.qrcode/1.0/jquery.qrcode.min.js"></script>
<?php if ($mod == 'user') { ?>
	<script>
		var items = $("select[default]");
		for (i = 0; i < items.length; i++) {
			$(items[i]).val($(items[i]).attr("default") || 0);
		}
		<?php if ($conf['fenzhan_daifu'] == 1) { ?>
			var getopenid = function() {
				var open = layer.open({
					type: 1,
					title: '',
					content: '<div class="layui-card-body"><h3 style="text-align:center">请使用微信扫一扫</h3><div><div id="qrcode" style="padding:15px;"></div></div></div>',
					cancel: function(index, layero) {
						layer.close(open);
						window.clearInterval(cron);
					},
					success: function() {
						var code_url = '<?php echo $code_url ?>';
						$('#qrcode').qrcode({
							text: code_url,
							width: 230,
							height: 230,
							foreground: "#000000",
							background: "#ffffff",
							typeNumber: -1
						});
					}
				});
				var cron = setInterval(function() {
					$.ajax({
						type: "GET",
						url: '<?php echo $cron_url; ?>' + '&r=' + Math.random(),
						dataType: "json",
						success: function(data) {
							if (data.code) {
								$("input[name=pay_account]").val(data.data);
								layer.close(open);
								window.clearInterval(cron);
							}
						}
					});
				}, 3000);
			}
			$("select[name='pay_type']").change(function() {
				if ($(this).val() == 1) {
					$("#getopenid").show();
					$("input[name=pay_account]").attr("readOnly", "readOnly");
				} else {
					$("#getopenid").hide();
					$("input[name=pay_account]").removeAttr("readOnly");
				}
			});
			$("select[name='pay_type']").change();
		<?php } ?>

		function setpwd() {
			var user = $("input[name='user']").val();
			var pwd = $("input[name='pwd']").val();
			if (user == '' || pwd == '') {
				layer.alert('请确保每项不能为空！');
				return false;
			}
			if (user.length < 3) {
				layer.alert('用户名太短');
				return false;
			} else if (user.length > 20) {
				layer.alert('用户名太长');
				return false;
			} else if (pwd.length < 6) {
				layer.alert('密码不能低于6位');
				return false;
			} else if (pwd.length > 30) {
				layer.alert('密码太长');
				return false;
			}
			var ii = layer.load(2, {
				shade: [0.1, '#fff']
			});
			$.ajax({
				type: "POST",
				url: "ajax_user.php?act=setpwd",
				data: {
					user: user,
					pwd: pwd
				},
				dataType: 'json',
				success: function(data) {
					layer.close(ii);
					if (data.code == 0) {
						layer.alert(data.msg, {
							closeBtn: 0
						}, function() {
							window.location.reload();
						});
					} else {
						layer.alert(data.msg, {
							icon: 0
						});
					}
				}
			});
			return false;
		}

		function connect(type) {
			var ii = layer.load(2, {
				shade: [0.1, '#fff']
			});
			$.ajax({
				type: "POST",
				url: "ajax.php?act=connect",
				data: {
					type: type
				},
				dataType: 'json',
				success: function(data) {
					layer.close(ii);
					if (data.code == 0) {
						window.location.href = data.url;
					} else {
						layer.alert(data.msg, {
							icon: 7
						});
					}
				}
			});
		}

		function unbind(type) {
			var confirmobj = layer.confirm('解绑后将无法通过QQ一键登录，是否确定解绑？', function() {
				var ii = layer.load(2, {
					shade: [0.1, '#fff']
				});
				$.ajax({
					type: "POST",
					url: "ajax.php?act=unbind",
					data: {
						type: type
					},
					dataType: 'json',
					success: function(data) {
						layer.close(ii);
						if (data.code == 0) {
							layer.alert(data.msg, {
								icon: 1
							}, function() {
								window.location.reload();
							});
						} else {
							layer.alert(data.msg, {
								icon: 0
							});
						}
					}
				});
			}, function() {
				layer.close(confirmobj);
			});
		}
	</script>
<?php } ?>
</body>

</html>