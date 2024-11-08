/*
SQLyog Community v13.1.6 (64 bit)
MySQL - 5.7.30 : Database - 2023faka1
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `shua_account` */

DROP TABLE IF EXISTS `shua_account`;

CREATE TABLE `shua_account` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL,
  `password` varchar(32) NOT NULL,
  `permission` text,
  `addtime` datetime DEFAULT NULL,
  `lasttime` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_account` */

/*Table structure for table `shua_apps` */

DROP TABLE IF EXISTS `shua_apps`;

CREATE TABLE `shua_apps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `taskid` int(11) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(128) NOT NULL,
  `name` varchar(128) DEFAULT NULL,
  `icon` varchar(256) DEFAULT NULL,
  `package` varchar(128) DEFAULT NULL,
  `android_url` varchar(256) DEFAULT NULL,
  `ios_url` varchar(256) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `domain` (`domain`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `shua_apps` */

/*Table structure for table `shua_article` */

DROP TABLE IF EXISTS `shua_article`;

CREATE TABLE `shua_article` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `keywords` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `addtime` datetime NOT NULL,
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `top` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_article` */

/*Table structure for table `shua_cache` */

DROP TABLE IF EXISTS `shua_cache`;

CREATE TABLE `shua_cache` (
  `k` varchar(32) NOT NULL,
  `v` longtext,
  `expire` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_cache` */

insert  into `shua_cache`(`k`,`v`,`expire`) values 
('config','a:63:{s:9:\"admin_pwd\";s:6:\"123456\";s:10:\"admin_user\";s:5:\"admin\";s:10:\"alipay_api\";s:1:\"2\";s:7:\"anounce\";s:1211:\"<p>\r\n<li class=\"list-group-item\"><span class=\"btn btn-danger btn-xs\">1</span> 售后问题可直接联系平台在线QQ客服</li>\r\n<li class=\"list-group-item\"><span class=\"btn btn-success btn-xs\">2</span> 下单之前请一定要看完该商品的注意事项再进行下单！</li>\r\n<li class=\"list-group-item\"><span class=\"btn btn-info btn-xs\">3</span> 所有业务全部恢复，都可以正常下单，欢迎尝试</li>\r\n<li class=\"list-group-item\"><span class=\"btn btn-warning btn-xs\">4</span> 温馨提示：请勿重复下单哦！必须要等待前面任务订单完成才可以下单！</li>\r\n<li class=\"list-group-item\"><span class=\"btn btn-primary btn-xs\">5</span> <a href=\"./user/regsite.php\">价格贵？不怕，点击0元搭建，在后台超低价下单！</a></li>\r\n<div class=\"btn-group btn-group-justified\">\r\n<a target=\"_blank\" class=\"btn btn-info\" href=\"http://wpa.qq.com/msgrd?v=3&uin=123456&site=qq&menu=yes\"><i class=\"fa fa-qq\"></i> 联系客服</a>\r\n<a target=\"_blank\" class=\"btn btn-warning\" href=\"http://qun.qq.com/join.html\"><i class=\"fa fa-users\"></i> 官方Q群</a>\r\n<a target=\"_blank\" class=\"btn btn-danger\" href=\"./\"><i class=\"fa fa-cloud-download\"></i> APP下载</a>\r\n</div></p>\";s:6:\"bottom\";s:0:\"\";s:5:\"build\";s:10:\"2023-05-28\";s:17:\"captcha_open_free\";s:1:\"1\";s:16:\"captcha_open_reg\";s:1:\"1\";s:9:\"cdnpublic\";s:1:\"0\";s:9:\"chatframe\";s:0:\"\";s:5:\"cishu\";s:1:\"3\";s:7:\"cronkey\";s:6:\"555267\";s:11:\"description\";s:120:\"Annie商城系统，专注数字娱乐产品、网络生活服务产品销售，只为您方便、快捷、省心！\";s:9:\"faka_mail\";s:231:\"<b>商品名称：</b> [name]<br/><b>购买时间：</b>[date]<br/><b>以下是你的卡密信息：</b><br/>[kmdata]<br/>----------<br/><b>使用说明：</b><br/>[alert]<br/>----------<br/>云商城自助下单平台<br/>[domain]\";s:11:\"fenzhan_buy\";s:1:\"1\";s:16:\"fenzhan_edithtml\";s:1:\"1\";s:14:\"fenzhan_expiry\";s:2:\"12\";s:12:\"fenzhan_free\";s:1:\"0\";s:12:\"fenzhan_kfqq\";s:1:\"1\";s:13:\"fenzhan_price\";s:2:\"10\";s:14:\"fenzhan_price2\";s:2:\"20\";s:18:\"fenzhan_pricelimit\";s:1:\"1\";s:12:\"fenzhan_rank\";s:1:\"1\";s:14:\"fenzhan_tixian\";s:1:\"0\";s:21:\"fenzhan_tixian_alipay\";s:1:\"1\";s:17:\"fenzhan_tixian_qq\";s:1:\"1\";s:17:\"fenzhan_tixian_wx\";s:1:\"1\";s:9:\"gg_search\";s:400:\"<span class=\"label label-primary\">待处理</span> 说明正在努力提交到服务器！<p></p><p></p><span class=\"label label-success\">已完成</span> 已经提交到接口正在处理！<p></p><p></p><span class=\"label label-warning\">处理中</span> 已经开始为您开单 请耐心等！<p></p><p></p><span class=\"label label-danger\">有异常</span> 下单信息有误 联系客服处理！\";s:9:\"gift_open\";s:1:\"0\";s:14:\"invite_content\";s:185:\"特价名片赞0.1元起，免费领名片赞，空间人气、QQ钻、大会员、名片赞、说说赞、空间访问、全民K歌，链接：[url] (请复制链接到浏览器打开)\";s:8:\"keywords\";s:50:\"QQ云商城,自助下单,网红助手,网红速成\";s:4:\"kfqq\";s:6:\"123456\";s:10:\"lt_version\";s:4:\"1012\";s:9:\"mail_port\";s:3:\"465\";s:9:\"mail_smtp\";s:11:\"smtp.qq.com\";s:5:\"modal\";s:0:\"\";s:6:\"paymsg\";s:203:\"<hr/>小提示：<b style=\"color:red\">如果微信出现无法付款时，您可以把微信的钱转到QQ里，然后使用QQ钱包支付！<a href=\"./?mod=wx\" target=\"_blank\">点击查看教程</a></b>\";s:12:\"pricejk_time\";s:3:\"600\";s:11:\"qiandao_day\";s:2:\"15\";s:12:\"qiandao_mult\";s:4:\"1.05\";s:14:\"qiandao_reward\";s:4:\"0.02\";s:9:\"qqpay_api\";s:1:\"2\";s:11:\"search_open\";s:1:\"1\";s:15:\"shopdesc_editor\";s:1:\"1\";s:12:\"shoppingcart\";s:1:\"1\";s:8:\"sitename\";s:21:\"Annie商城系统\";s:5:\"style\";s:1:\"1\";s:8:\"sup_bond\";s:1:\"0\";s:6:\"syskey\";s:16:\"Sv???t?$-????q\";s:8:\"template\";s:6:\"simple\";s:12:\"tixian_limit\";s:1:\"1\";s:10:\"tixian_min\";s:2:\"10\";s:11:\"tixian_rate\";s:2:\"98\";s:11:\"tongji_time\";s:3:\"300\";s:13:\"ui_background\";s:1:\"3\";s:12:\"updatestatus\";s:1:\"0\";s:21:\"updatestatus_interval\";s:1:\"6\";s:9:\"user_open\";s:1:\"1\";s:11:\"verify_open\";s:1:\"1\";s:7:\"version\";s:4:\"1010\";s:14:\"workorder_open\";s:1:\"1\";s:14:\"workorder_type\";s:66:\"业务补单|卡密错误|充值没到账|订单中途改了密码\";s:9:\"wxpay_api\";s:1:\"2\";}',0),
('getcount','a:2:{s:4:\"time\";i:1685236943;s:4:\"data\";a:20:{s:4:\"code\";i:0;s:4:\"yxts\";d:1;s:6:\"count1\";s:1:\"0\";s:6:\"count2\";s:1:\"0\";s:6:\"count3\";s:1:\"0\";s:6:\"count4\";s:1:\"0\";s:6:\"count5\";d:0;s:6:\"count6\";s:1:\"0\";s:6:\"count7\";s:1:\"0\";s:6:\"count8\";d:0;s:6:\"count9\";d:0;s:7:\"count10\";d:0;s:7:\"count11\";d:0;s:7:\"count12\";d:0;s:7:\"count13\";d:0;s:7:\"count14\";d:0;s:7:\"count15\";d:0;s:7:\"count16\";d:0;s:7:\"count17\";s:1:\"0\";s:5:\"chart\";a:3:{s:4:\"date\";a:7:{i:0;a:2:{i:0;i:1;i:1;s:4:\"0521\";}i:1;a:2:{i:0;i:2;i:1;s:4:\"0522\";}i:2;a:2:{i:0;i:3;i:1;s:4:\"0523\";}i:3;a:2:{i:0;i:4;i:1;s:4:\"0524\";}i:4;a:2:{i:0;i:5;i:1;s:4:\"0525\";}i:5;a:2:{i:0;i:6;i:1;s:4:\"0526\";}i:6;a:2:{i:0;i:7;i:1;s:4:\"0527\";}}s:6:\"orders\";a:7:{i:0;a:2:{i:0;i:1;i:1;s:1:\"0\";}i:1;a:2:{i:0;i:2;i:1;s:1:\"0\";}i:2;a:2:{i:0;i:3;i:1;s:1:\"0\";}i:3;a:2:{i:0;i:4;i:1;s:1:\"0\";}i:4;a:2:{i:0;i:5;i:1;s:1:\"0\";}i:5;a:2:{i:0;i:6;i:1;s:1:\"0\";}i:6;a:2:{i:0;i:7;i:1;s:1:\"0\";}}s:5:\"money\";a:7:{i:0;a:2:{i:0;i:1;i:1;d:0;}i:1;a:2:{i:0;i:2;i:1;d:0;}i:2;a:2:{i:0;i:3;i:1;d:0;}i:3;a:2:{i:0;i:4;i:1;d:0;}i:4;a:2:{i:0;i:5;i:1;d:0;}i:5;a:2:{i:0;i:6;i:1;d:0;}i:6;a:2:{i:0;i:7;i:1;d:0;}}}}}',0),
('tongji','a:7:{s:6:\"orders\";s:1:\"0\";s:7:\"orders1\";s:1:\"0\";s:7:\"orders2\";s:1:\"0\";s:5:\"money\";d:0;s:6:\"money1\";d:0;s:4:\"site\";s:1:\"0\";s:4:\"gift\";N;}',0);

/*Table structure for table `shua_cart` */

DROP TABLE IF EXISTS `shua_cart`;

CREATE TABLE `shua_cart` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `userid` varchar(32) NOT NULL,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `tid` int(11) NOT NULL,
  `input` text NOT NULL,
  `num` int(11) unsigned NOT NULL DEFAULT '1',
  `money` varchar(32) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `blockdj` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_cart` */

/*Table structure for table `shua_class` */

DROP TABLE IF EXISTS `shua_class`;

CREATE TABLE `shua_class` (
  `cid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `sort` int(11) NOT NULL DEFAULT '10',
  `name` varchar(255) NOT NULL,
  `shopimg` text,
  `block` text,
  `blockpay` varchar(80) DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_class` */

/*Table structure for table `shua_config` */

DROP TABLE IF EXISTS `shua_config`;

CREATE TABLE `shua_config` (
  `k` varchar(32) NOT NULL,
  `v` text,
  PRIMARY KEY (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_config` */

insert  into `shua_config`(`k`,`v`) values 
('adminlogin','2023-05-28 09:22:21'),
('admin_pwd','123456'),
('admin_user','admin'),
('alipay_api','2'),
('anounce','<p>\r\n<li class=\"list-group-item\"><span class=\"btn btn-danger btn-xs\">1</span> 售后问题可直接联系平台在线QQ客服</li>\r\n<li class=\"list-group-item\"><span class=\"btn btn-success btn-xs\">2</span> 下单之前请一定要看完该商品的注意事项再进行下单！</li>\r\n<li class=\"list-group-item\"><span class=\"btn btn-info btn-xs\">3</span> 所有业务全部恢复，都可以正常下单，欢迎尝试</li>\r\n<li class=\"list-group-item\"><span class=\"btn btn-warning btn-xs\">4</span> 温馨提示：请勿重复下单哦！必须要等待前面任务订单完成才可以下单！</li>\r\n<li class=\"list-group-item\"><span class=\"btn btn-primary btn-xs\">5</span> <a href=\"./user/regsite.php\">价格贵？不怕，点击0元搭建，在后台超低价下单！</a></li>\r\n<div class=\"btn-group btn-group-justified\">\r\n<a target=\"_blank\" class=\"btn btn-info\" href=\"http://wpa.qq.com/msgrd?v=3&uin=123456&site=qq&menu=yes\"><i class=\"fa fa-qq\"></i> 联系客服</a>\r\n<a target=\"_blank\" class=\"btn btn-warning\" href=\"http://qun.qq.com/join.html\"><i class=\"fa fa-users\"></i> 官方Q群</a>\r\n<a target=\"_blank\" class=\"btn btn-danger\" href=\"./\"><i class=\"fa fa-cloud-download\"></i> APP下载</a>\r\n</div></p>'),
('bottom',''),
('build','2023-05-28'),
('cache',''),
('captcha_open_free','1'),
('captcha_open_reg','1'),
('cdnpublic','0'),
('chatframe',''),
('cishu','3'),
('cronkey','555267'),
('datepoint','a:7:{i:0;a:3:{s:4:\"date\";s:4:\"0527\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:1;a:3:{s:4:\"date\";s:4:\"0526\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:2;a:3:{s:4:\"date\";s:4:\"0525\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:3;a:3:{s:4:\"date\";s:4:\"0524\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:4;a:3:{s:4:\"date\";s:4:\"0523\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:5;a:3:{s:4:\"date\";s:4:\"0522\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}i:6;a:3:{s:4:\"date\";s:4:\"0521\";s:6:\"orders\";s:1:\"0\";s:5:\"money\";d:0;}}'),
('description','Annie商城系统，专注数字娱乐产品、网络生活服务产品销售，只为您方便、快捷、省心！'),
('faka_mail','<b>商品名称：</b> [name]<br/><b>购买时间：</b>[date]<br/><b>以下是你的卡密信息：</b><br/>[kmdata]<br/>----------<br/><b>使用说明：</b><br/>[alert]<br/>----------<br/>云商城自助下单平台<br/>[domain]'),
('fenzhan_buy','1'),
('fenzhan_edithtml','1'),
('fenzhan_expiry','12'),
('fenzhan_free','0'),
('fenzhan_kfqq','1'),
('fenzhan_price','10'),
('fenzhan_price2','20'),
('fenzhan_pricelimit','1'),
('fenzhan_rank','1'),
('fenzhan_tixian','0'),
('fenzhan_tixian_alipay','1'),
('fenzhan_tixian_qq','1'),
('fenzhan_tixian_wx','1'),
('gg_search','<span class=\"label label-primary\">待处理</span> 说明正在努力提交到服务器！<p></p><p></p><span class=\"label label-success\">已完成</span> 已经提交到接口正在处理！<p></p><p></p><span class=\"label label-warning\">处理中</span> 已经开始为您开单 请耐心等！<p></p><p></p><span class=\"label label-danger\">有异常</span> 下单信息有误 联系客服处理！'),
('gift_open','0'),
('invite_content','特价名片赞0.1元起，免费领名片赞，空间人气、QQ钻、大会员、名片赞、说说赞、空间访问、全民K歌，链接：[url] (请复制链接到浏览器打开)'),
('keywords','QQ云商城,自助下单,网红助手,网红速成'),
('kfqq','123456'),
('lt_version','1012'),
('mail_port','465'),
('mail_smtp','smtp.qq.com'),
('modal',''),
('paymsg','<hr/>小提示：<b style=\"color:red\">如果微信出现无法付款时，您可以把微信的钱转到QQ里，然后使用QQ钱包支付！<a href=\"./?mod=wx\" target=\"_blank\">点击查看教程</a></b>'),
('pricejk_time','600'),
('qiandao_day','15'),
('qiandao_mult','1.05'),
('qiandao_reward','0.02'),
('qqpay_api','2'),
('search_open','1'),
('shopdesc_editor','1'),
('shoppingcart','1'),
('sitename','Annie商城系统'),
('style','1'),
('sup_bond','0'),
('syskey','Sv???t?$-????q'),
('template','simple'),
('tixian_limit','1'),
('tixian_min','10'),
('tixian_rate','98'),
('tongji_cachetime','1685236915'),
('tongji_time','300'),
('ui_background','3'),
('updatestatus','0'),
('updatestatus_interval','6'),
('user_open','1'),
('verify_open','1'),
('version','1010'),
('workorder_open','1'),
('workorder_type','业务补单|卡密错误|充值没到账|订单中途改了密码'),
('wxpay_api','2');

/*Table structure for table `shua_faka` */

DROP TABLE IF EXISTS `shua_faka`;

CREATE TABLE `shua_faka` (
  `kid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL,
  `km` varchar(255) DEFAULT NULL,
  `pw` varchar(255) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `usetime` datetime DEFAULT NULL,
  `orderid` int(11) unsigned NOT NULL DEFAULT '0',
  `sid` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`kid`),
  KEY `orderid` (`orderid`),
  KEY `tid` (`tid`),
  KEY `getleft` (`tid`,`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_faka` */

/*Table structure for table `shua_gift` */

DROP TABLE IF EXISTS `shua_gift`;

CREATE TABLE `shua_gift` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `rate` int(3) NOT NULL,
  `ok` tinyint(1) NOT NULL DEFAULT '0',
  `not` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_gift` */

/*Table structure for table `shua_giftlog` */

DROP TABLE IF EXISTS `shua_giftlog`;

CREATE TABLE `shua_giftlog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL,
  `gid` int(11) unsigned NOT NULL,
  `userid` varchar(32) NOT NULL,
  `ip` varchar(20) NOT NULL,
  `addtime` datetime DEFAULT NULL,
  `tradeno` varchar(32) DEFAULT NULL,
  `input` varchar(64) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_giftlog` */

/*Table structure for table `shua_invite` */

DROP TABLE IF EXISTS `shua_invite`;

CREATE TABLE `shua_invite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nid` int(11) unsigned NOT NULL,
  `tid` int(11) unsigned NOT NULL,
  `qq` varchar(20) NOT NULL,
  `input` text NOT NULL,
  `key` varchar(30) NOT NULL,
  `ip` varchar(25) DEFAULT NULL,
  `plan` int(11) unsigned NOT NULL DEFAULT '0',
  `click` int(11) unsigned NOT NULL DEFAULT '0',
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`),
  KEY `nid` (`nid`),
  KEY `qq` (`qq`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_invite` */

/*Table structure for table `shua_invitelog` */

DROP TABLE IF EXISTS `shua_invitelog`;

CREATE TABLE `shua_invitelog` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `iid` int(11) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `orderid` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `iid` (`iid`,`status`),
  KEY `iidip` (`iid`,`ip`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_invitelog` */

/*Table structure for table `shua_inviteshop` */

DROP TABLE IF EXISTS `shua_inviteshop`;

CREATE TABLE `shua_inviteshop` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `times` tinyint(1) NOT NULL DEFAULT '0',
  `value` decimal(10,2) NOT NULL DEFAULT '0.00',
  `sort` int(11) NOT NULL DEFAULT '10',
  `addtime` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tid` (`tid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_inviteshop` */

/*Table structure for table `shua_kms` */

DROP TABLE IF EXISTS `shua_kms`;

CREATE TABLE `shua_kms` (
  `kid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '0',
  `zid` int(11) unsigned NOT NULL DEFAULT '0',
  `tid` int(11) unsigned NOT NULL DEFAULT '0',
  `num` int(11) unsigned NOT NULL DEFAULT '1',
  `km` varchar(255) NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` timestamp NULL DEFAULT NULL,
  `usetime` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `orderid` int(11) unsigned DEFAULT '0',
  PRIMARY KEY (`kid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_kms` */

/*Table structure for table `shua_logs` */

DROP TABLE IF EXISTS `shua_logs`;

CREATE TABLE `shua_logs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(32) NOT NULL,
  `param` varchar(255) NOT NULL,
  `result` text,
  `addtime` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_logs` */

insert  into `shua_logs`(`id`,`action`,`param`,`result`,`addtime`,`status`) values 
(1,'后台登录','IP:127.0.0.1','','2023-05-28 09:22:21',1);

/*Table structure for table `shua_message` */

DROP TABLE IF EXISTS `shua_message`;

CREATE TABLE `shua_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `type` int(1) NOT NULL DEFAULT '0',
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `color` varchar(20) DEFAULT NULL,
  `addtime` datetime NOT NULL,
  `count` int(11) unsigned NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_message` */

/*Table structure for table `shua_orders` */

DROP TABLE IF EXISTS `shua_orders`;

CREATE TABLE `shua_orders` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `tid` int(11) unsigned NOT NULL,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `input` varchar(256) NOT NULL,
  `input2` varchar(256) DEFAULT NULL,
  `input3` varchar(256) DEFAULT NULL,
  `input4` varchar(256) DEFAULT NULL,
  `input5` varchar(256) DEFAULT NULL,
  `value` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `djzt` tinyint(1) NOT NULL DEFAULT '0',
  `djorder` varchar(32) DEFAULT NULL,
  `url` varchar(32) DEFAULT NULL,
  `result` text,
  `userid` varchar(32) DEFAULT NULL,
  `tradeno` varchar(32) DEFAULT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `uptime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `input` (`input`),
  KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_orders` */

/*Table structure for table `shua_pay` */

DROP TABLE IF EXISTS `shua_pay`;

CREATE TABLE `shua_pay` (
  `trade_no` varchar(64) NOT NULL,
  `api_trade_no` varchar(64) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `channel` varchar(10) DEFAULT NULL,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `tid` int(11) NOT NULL,
  `input` text NOT NULL,
  `num` int(11) unsigned NOT NULL DEFAULT '1',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `name` varchar(64) DEFAULT NULL,
  `money` varchar(32) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `userid` varchar(32) DEFAULT NULL,
  `inviteid` int(11) unsigned DEFAULT NULL,
  `domain` varchar(64) DEFAULT NULL,
  `blockdj` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`trade_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_pay` */

/*Table structure for table `shua_points` */

DROP TABLE IF EXISTS `shua_points`;

CREATE TABLE `shua_points` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '0',
  `action` varchar(255) NOT NULL,
  `point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bz` varchar(1024) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `orderid` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `action` (`action`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_points` */

/*Table structure for table `shua_price` */

DROP TABLE IF EXISTS `shua_price`;

CREATE TABLE `shua_price` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '0',
  `kind` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 倍数 1 价格',
  `name` varchar(255) NOT NULL,
  `p_0` decimal(8,2) NOT NULL DEFAULT '0.00',
  `p_1` decimal(8,2) NOT NULL DEFAULT '0.00',
  `p_2` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_price` */

/*Table structure for table `shua_qiandao` */

DROP TABLE IF EXISTS `shua_qiandao`;

CREATE TABLE `shua_qiandao` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `qq` varchar(20) DEFAULT NULL,
  `reward` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date` date NOT NULL,
  `time` datetime NOT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `continue` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`),
  KEY `ip` (`ip`),
  KEY `date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_qiandao` */

/*Table structure for table `shua_sendcode` */

DROP TABLE IF EXISTS `shua_sendcode`;

CREATE TABLE `shua_sendcode` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0邮箱 1手机',
  `mode` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0注册 1找回 2改绑',
  `code` varchar(32) NOT NULL,
  `to` varchar(32) DEFAULT NULL,
  `time` int(11) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `shua_sendcode` */

/*Table structure for table `shua_shequ` */

DROP TABLE IF EXISTS `shua_shequ`;

CREATE TABLE `shua_shequ` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `paypwd` varchar(255) DEFAULT NULL,
  `paytype` tinyint(1) NOT NULL DEFAULT '0',
  `type` varchar(20) NOT NULL,
  `result` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `protocol` tinyint(1) NOT NULL DEFAULT '0',
  `monitor` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_shequ` */

/*Table structure for table `shua_site` */

DROP TABLE IF EXISTS `shua_site`;

CREATE TABLE `shua_site` (
  `zid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `upzid` int(11) unsigned NOT NULL DEFAULT '0',
  `utype` int(1) unsigned NOT NULL DEFAULT '0',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `domain` varchar(50) DEFAULT NULL,
  `domain2` varchar(50) DEFAULT NULL,
  `user` varchar(20) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `qq_openid` varchar(64) DEFAULT NULL,
  `wx_openid` varchar(64) DEFAULT NULL,
  `nickname` varchar(64) DEFAULT NULL,
  `faceimg` varchar(150) DEFAULT NULL,
  `rmb` decimal(10,2) NOT NULL DEFAULT '0.00',
  `rmbtc` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` int(11) NOT NULL DEFAULT '0',
  `pay_type` int(1) NOT NULL DEFAULT '0',
  `pay_account` varchar(50) DEFAULT NULL,
  `pay_name` varchar(50) DEFAULT NULL,
  `qq` varchar(12) DEFAULT NULL,
  `sitename` varchar(80) DEFAULT NULL,
  `title` varchar(80) DEFAULT NULL,
  `keywords` text,
  `description` text,
  `kfqq` varchar(12) DEFAULT NULL,
  `kfwx` varchar(20) DEFAULT NULL,
  `anounce` text,
  `bottom` text,
  `modal` text,
  `alert` text,
  `price` text,
  `iprice` text,
  `appurl` varchar(150) DEFAULT NULL,
  `class` varchar(255) DEFAULT NULL,
  `ktfz_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ktfz_price2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ktfz_domain` text,
  `addtime` datetime DEFAULT NULL,
  `lasttime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `template` varchar(10) DEFAULT NULL,
  `msgread` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`zid`),
  UNIQUE KEY `user` (`user`),
  KEY `domain` (`domain`),
  KEY `domain2` (`domain2`),
  KEY `qq` (`qq`),
  KEY `qq_openid` (`qq_openid`),
  KEY `wx_openid` (`wx_openid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `shua_site` */

/*Table structure for table `shua_supplier` */

DROP TABLE IF EXISTS `shua_supplier`;

CREATE TABLE `shua_supplier` (
  `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user` varchar(20) NOT NULL,
  `pwd` varchar(32) NOT NULL,
  `email` varchar(64) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `qq_openid` varchar(64) DEFAULT NULL,
  `wx_openid` varchar(64) DEFAULT NULL,
  `nickname` varchar(64) DEFAULT NULL,
  `faceimg` varchar(150) DEFAULT NULL,
  `rmb` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bond` decimal(10,2) NOT NULL DEFAULT '0.00',
  `point` int(11) NOT NULL DEFAULT '0',
  `pay_type` int(1) NOT NULL DEFAULT '0',
  `pay_account` varchar(50) DEFAULT NULL,
  `pay_name` varchar(50) DEFAULT NULL,
  `qq` varchar(12) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `lasttime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `msgread` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sid`),
  UNIQUE KEY `user` (`user`),
  KEY `qq` (`qq`),
  KEY `qq_openid` (`qq_openid`),
  KEY `wx_openid` (`wx_openid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

/*Data for the table `shua_supplier` */

/*Table structure for table `shua_suppoints` */

DROP TABLE IF EXISTS `shua_suppoints`;

CREATE TABLE `shua_suppoints` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned NOT NULL DEFAULT '0',
  `action` varchar(255) NOT NULL,
  `point` decimal(10,2) NOT NULL DEFAULT '0.00',
  `bz` varchar(1024) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `orderid` int(11) unsigned DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`),
  KEY `action` (`action`),
  KEY `orderid` (`orderid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_suppoints` */

/*Table structure for table `shua_suptixian` */

DROP TABLE IF EXISTS `shua_suptixian`;

CREATE TABLE `shua_suptixian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sid` int(11) unsigned NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realmoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_type` int(1) NOT NULL DEFAULT '0',
  `pay_account` varchar(50) NOT NULL,
  `pay_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_suptixian` */

/*Table structure for table `shua_tixian` */

DROP TABLE IF EXISTS `shua_tixian`;

CREATE TABLE `shua_tixian` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL,
  `money` decimal(10,2) NOT NULL DEFAULT '0.00',
  `realmoney` decimal(10,2) NOT NULL DEFAULT '0.00',
  `pay_type` int(1) NOT NULL DEFAULT '0',
  `pay_account` varchar(50) NOT NULL,
  `pay_name` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `addtime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `note` text,
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_tixian` */

/*Table structure for table `shua_toollogs` */

DROP TABLE IF EXISTS `shua_toollogs`;

CREATE TABLE `shua_toollogs` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `content` longtext NOT NULL,
  `date` date DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_toollogs` */

insert  into `shua_toollogs`(`id`,`content`,`date`,`addtime`,`active`) values 
(1,'1111','2023-05-26','2023-05-26 00:00:00',1);

/*Table structure for table `shua_tools` */

DROP TABLE IF EXISTS `shua_tools`;

CREATE TABLE `shua_tools` (
  `tid` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `cid` int(11) unsigned NOT NULL DEFAULT '0',
  `sort` int(11) NOT NULL DEFAULT '10',
  `name` varchar(255) NOT NULL,
  `value` int(11) unsigned NOT NULL DEFAULT '0',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prid` int(11) NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `cost2` decimal(10,2) NOT NULL DEFAULT '0.00',
  `prices` varchar(100) DEFAULT NULL,
  `input` varchar(250) NOT NULL,
  `inputs` varchar(255) DEFAULT NULL,
  `desc` text,
  `alert` text,
  `shopimg` text,
  `validate` tinyint(1) NOT NULL DEFAULT '0',
  `valiserv` varchar(15) DEFAULT NULL,
  `min` int(11) NOT NULL DEFAULT '0',
  `max` int(11) NOT NULL DEFAULT '0',
  `is_curl` tinyint(1) NOT NULL DEFAULT '0',
  `curl` varchar(255) DEFAULT NULL,
  `repeat` tinyint(1) NOT NULL DEFAULT '0',
  `multi` tinyint(1) NOT NULL DEFAULT '0',
  `shequ` int(3) NOT NULL DEFAULT '0',
  `goods_id` int(11) NOT NULL DEFAULT '0',
  `goods_type` int(11) NOT NULL DEFAULT '0',
  `goods_param` text,
  `close` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `uptime` int(11) NOT NULL DEFAULT '0',
  `sales` int(11) NOT NULL DEFAULT '0',
  `stock` int(11) DEFAULT NULL,
  `addtime` datetime DEFAULT NULL,
  `goods_sid` tinyint(1) NOT NULL DEFAULT '0',
  `audit_status` tinyint(1) NOT NULL DEFAULT '0',
  `sup_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `ts` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`tid`),
  KEY `cid` (`cid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_tools` */

/*Table structure for table `shua_workorder` */

DROP TABLE IF EXISTS `shua_workorder`;

CREATE TABLE `shua_workorder` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `zid` int(11) unsigned NOT NULL DEFAULT '1',
  `type` int(1) unsigned NOT NULL DEFAULT '0',
  `orderid` int(11) unsigned NOT NULL DEFAULT '0',
  `content` text NOT NULL,
  `picurl` varchar(150) DEFAULT NULL,
  `addtime` datetime NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `ts` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `zid` (`zid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `shua_workorder` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
