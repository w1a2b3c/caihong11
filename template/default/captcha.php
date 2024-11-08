<?php
/**
 * 推广验证
 */
?>
<!DOCTYPE html>
<html lang="zh-cn">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>正在返回首页</title>
	<link href="//lib.baomitu.com/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet"/>
	<style>
#captcha_text{height:42px;width:100%;text-align:center;border-radius:2px;background-color:#F3F3F3;color:#BBBBBB;font-size:14px;letter-spacing:0.1px;line-height:42px}#captcha_wait{display:none;height:42px;width:100%;text-align:center;border-radius:2px;background-color:#F3F3F3}.loading{margin:auto;width:70px;height:20px}.loading-dot{float:left;width:8px;height:8px;margin:18px 4px;background:#ccc;-webkit-border-radius:50%;-moz-border-radius:50%;border-radius:50%;opacity:0;-webkit-box-shadow:0 0 2px black;-moz-box-shadow:0 0 2px black;-ms-box-shadow:0 0 2px black;-o-box-shadow:0 0 2px black;box-shadow:0 0 2px black;-webkit-animation:loadingFade 1s infinite;-moz-animation:loadingFade 1s infinite;animation:loadingFade 1s infinite}.loading-dot:nth-child(1){-webkit-animation-delay:0s;-moz-animation-delay:0s;animation-delay:0s}.loading-dot:nth-child(2){-webkit-animation-delay:0.1s;-moz-animation-delay:0.1s;animation-delay:0.1s}.loading-dot:nth-child(3){-webkit-animation-delay:0.2s;-moz-animation-delay:0.2s;animation-delay:0.2s}.loading-dot:nth-child(4){-webkit-animation-delay:0.3s;-moz-animation-delay:0.3s;animation-delay:0.3s}@-webkit-keyframes loadingFade{0%{opacity:0}50%{opacity:0.8}100%{opacity:0}}@-moz-keyframes loadingFade{0%{opacity:0}50%{opacity:0.8}100%{opacity:0}}@keyframes loadingFade{0%{opacity:0}50%{opacity:0.8}100%{opacity:0}}
.vaptcha-init-main{display:table;width:100%;height:100%;background-color:#eee} .vaptcha-init-loading{display:table-cell;vertical-align:middle;text-align:center} .vaptcha-init-loading>a{display:inline-block;width:18px;height:18px;border:none}? .vaptcha-init-loading>a img{vertical-align:middle} .vaptcha-init-loading .vaptcha-text{font-family:sans-serif;font-size:12px;color:#ccc;vertical-align:middle}
	</style>
</head>
<body>
<input type="hidden" name="captcha_open" value="<?php echo $conf['captcha_open']?>"/>
<input type="hidden" name="appid" value="<?php echo $conf['captcha_id']?>"/>
</body>
<script src="//lib.baomitu.com/jquery/1.12.4/jquery.min.js"></script>
<script src="//lib.baomitu.com/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
<script src="//lib.baomitu.com/layer/2.3/layer.js"></script>
<script src="assets/js/captcha.js?ver=<?php echo VERSION ?>"></script>
</html>