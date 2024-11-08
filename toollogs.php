<?php

include './includes/common.php';
?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<title><?php echo $conf['sitename'];?> - 上架日志</title>
		<link href="//lib.baomitu.com/twitter-bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
		<link href="//lib.baomitu.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
		<style>
		p{
		   font-size: 15px; 
white-space: pre-line;
		}
	.timeline2-centered {    position: relative;    margin-bottom: 30px;}.timeline2-centered:before, .timeline2-centered:after {    content: " ";    display: table;}.timeline2-centered:after {    clear: both;}.timeline2-centered:before, .timeline2-centered:after {    content: " ";    display: table;}.timeline2-centered:after {    clear: both;}.timeline2-centered:before {    content: '';    position: absolute;    display: block;    width: 10px;    background: #f5f5f6;    top: 20px;    bottom: 20px;    margin-left: 26px;    -webkit-border-radius: 5px;    -moz-border-radius: 5px;    border-radius: 5px;}.timeline2-centered .timeline2-entry {    position: relative;    margin-top: 5px;    margin-left: 30px;    margin-bottom: 10px;    clear: both;}.timeline2-centered .timeline2-entry:before, .timeline2-centered .timeline2-entry:after {    content: " ";    display: table;}.timeline2-centered .timeline2-entry:after {    clear: both;}.timeline2-centered .timeline2-entry:before, .timeline2-centered .timeline2-entry:after {    content: " ";    display: table;}.timeline2-centered .timeline2-entry:after {    clear: both;}.timeline2-centered .timeline2-entry.begin {    margin-bottom: 0;}.timeline2-centered .timeline2-entry.left-aligned {    float: left;}.timeline2-centered .timeline2-entry.left-aligned .timeline2-entry-inner {    margin-left: 0;    margin-right: -18px;}.timeline2-centered .timeline2-entry.left-aligned .timeline2-entry-inner .timeline2-time {    left: auto;    right: -100px;    text-align: left;}.timeline2-centered .timeline2-entry.left-aligned .timeline2-entry-inner .timeline2-icon {    float: right;}.timeline2-centered .timeline2-entry.left-aligned .timeline2-entry-inner .timeline2-label {    margin-left: 0;    margin-right: 70px;}.timeline2-centered .timeline2-entry.left-aligned .timeline2-entry-inner .timeline2-label:after {    left: auto;    right: 0;    margin-left: 0;    margin-right: -9px;    -moz-transform: rotate(180deg);    -o-transform: rotate(180deg);    -webkit-transform: rotate(180deg);    -ms-transform: rotate(180deg);    transform: rotate(180deg);}.timeline2-centered .timeline2-entry .timeline2-entry-inner {    position: relative;    margin-left: -20px;}.timeline2-centered .timeline2-entry .timeline2-entry-inner:before, .timeline2-centered .timeline2-entry .timeline2-entry-inner:after {    content: " ";    display: table;}.timeline2-centered .timeline2-entry .timeline2-entry-inner:after {    clear: both;}.timeline2-centered .timeline2-entry .timeline2-entry-inner:before, .timeline2-centered .timeline2-entry .timeline2-entry-inner:after {    content: " ";    display: table;}.timeline2-centered .timeline2-entry .timeline2-entry-inner:after {    clear: both;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-time {    position: absolute;    left: -100px;    text-align: right;    padding: 10px;    -webkit-box-sizing: border-box;    -moz-box-sizing: border-box;    box-sizing: border-box;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-time >span {    display: block;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-time >span:first-child {    font-size: 15px;    font-weight: bold;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-time >span:last-child {    font-size: 12px;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-icon {    background: #fff;    color: #737881;    display: block;    width: 70px;    height: 70px;    -moz-background-clip: padding;    -o-background-clip: padding-box;    background-clip: padding-box;    -webkit-border-radius: 50%;    -moz-border-radius: 50%;    border-radius: 50%;    text-align: center;    line-height: 70px;    font-size: 21px;    float: left;    border: 5px solid #eaeaea;    margin-left: -15px;    margin-top: 40px;    line-height: 60px;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-icon.bg-primary {    background-color: #4d9cf8;    color: #fff;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-icon.bg-secondary {    background-color: #9e9e9e;    color: #fff;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-icon.bg-success {    background-color: #4CAF50;    color: #fff;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-icon.bg-info {    background-color: #03A9F4;    color: #fff;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-icon.bg-warning {    background-color: #FFC107;    color: #fff;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-icon.bg-danger {    background-color: #f44336;    color: #fff;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-label {    position: relative;    background: #f5f5f5;    padding: 15px;    margin-left: 35px;    -webkit-border-radius: 0px;    -moz-border-radius: 0px;    border-radius: 0px;    margin-top: 45px;    border: 0px solid #eaeaea;    -webkit-border-radius: 0px;    -moz-border-radius: 0px;    border-radius: 0px;    padding: 20px;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-label:after {    content: '';    display: block;    position: absolute;    width: 0;    height: 0;    border-style: solid;    border-width: 9px 9px 9px 0;    border-color: transparent #f5f5f5 transparent transparent;    left: 0;    top: 10px;    margin-left: -9px;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-label h2, .timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-label p {    margin: 0;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-label p + p {    margin-top: 15px;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-label h2 {    font-size: 24px;    margin-bottom: 10px;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-label h2 a {    color: #4d9cf8;}.timeline2-centered .timeline2-entry .timeline2-entry-inner .timeline2-label h2 span {    -webkit-opacity: .6;    -moz-opacity: .6;    opacity: .6;    -ms-filter: alpha(opacity=60);    filter: alpha(opacity=60);}.timeline2-icon i {    font-size: 28px;}
		</style>
	</head>
	<body>
	        
		<div class="col-xs-12 col-md-10 col-md-offset-1">
		    <a class="btn btn-default btn-block" href="/" style="margin-top:35px"><<< 返回首页</a>
			<div class="timeline2-centered">
			    <?php 
$rs = $DB->query("SELECT * FROM pre_toollogs ORDER BY date DESC");
while ($res = $rs->fetch()) {
	echo '<div class="timeline2-entry">
					<div class="timeline2-entry-inner">
						
						<div class="timeline2-label">
							<h2 style="color:red">
								
									<strong>' . $res['date'] . '
								
								<span>上架时间</span></strong>
							</h2>
							<p>' . $res['content'] . '</p>
						</div>
					</div>
				</div>';
}
?>				<div class="timeline2-entry begin">
					<div class="timeline2-entry-inner">
						<div class="timeline2-icon">
							<i class="fa fa-plus" style="color: #cccccc;position: relative;top: 3px;"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>