<?php
$template_info = [
	'name' => '电脑手机发卡首页模板',
	'version' => 1.1
];

//PC用户中心模板文件
$template_route = [
	'userreg' => TEMPLATE_ROOT.'faka/user/reg.php',
	'userlogin' => TEMPLATE_ROOT.'faka/user/login.php',
	'userfindpwd' => TEMPLATE_ROOT.'faka/user/findpwd.php',
];

//手机用户中心模板文件
$template_route_m = [
	'userreg' => TEMPLATE_ROOT.'faka/user/reg.php',
	'userlogin' => TEMPLATE_ROOT.'faka/user/login.php',
	'userfindpwd' => TEMPLATE_ROOT.'faka/user/findpwd.php',
];

$template_settings = [
	'template_style' => [
		'name'=>'选择界面风格',
		'type'=>'select',
		'options'=> [
			'1'=>'红色',
			'2'=>'黑色',
			'3'=>'天蓝',
			'4'=>'橙色',
			'5'=>'绿色',
			'6'=>'灰色',
			'7'=>'蓝色',
		]
	],
	'template_bgopen' => [
		'name'=>'是否显示背景图',
		'type'=>'select',
		'options'=> [
			'0'=>'不显示',
			'1'=>'显示',
		]
	],
	'template_showsales' => [
		'name'=>'是否显示销量',
		'type'=>'select',
		'options'=> [
			'0'=>'不显示',
			'1'=>'显示',
		]
	],
	'template_label_auto' => [
		'name'=>'发卡和对接商品标签文字',
		'type'=>'input',
		'note'=> '不填写默认是“自动”'
	],
	'template_label_manual' => [
		'name'=>'自营商品标签文字',
		'type'=>'input',
		'note'=> '不填写默认是“手动”'
	],
	'template_about' => [
		'name'=>'关于页面内容',
		'type'=>'textarea',
		'note'=> '支持html代码'
	],
	'template_help' => [
		'name'=>'帮助页面内容',
		'type'=>'textarea',
		'note'=> '支持html代码'
	],
];
