<?php
//分类屏蔽指定地区
if(!defined('IN_CRONLITE'))exit();

$hideData=[];
$rs=$DB->query("SELECT * FROM `pre_class` WHERE `active`=1 AND `block` IS NOT NULL AND `block`<>''");
while($row = $rs->fetch()){
	$hideData[$row['cid']] = explode(',',$row['block']);
}
$json = json_encode($hideData);
?>
<script>
$(document).ready(function(){
	var hideData = <?php echo $json?>;
	$.ajax({
		url: 'https://restapi.amap.com/v3/ip?key=0113a13c88697dcea6a445584d535837',
		type: "GET",
		dataType: 'jsonp',
		jsonp: "callback",
		success: function (res) {
			if(res.status != "1"){
				console.log(res);return false;
			}
			var loc = res.province+res.city;
			$('#cid option').each(function (i, item) {
				let key = parseInt($(item).val());
				if (hideData.hasOwnProperty(key)) {
					for (let str of hideData[key]) {
						if (loc.indexOf(str) > -1) {
							$(item).remove();
							break;
						}
					}
				}
			});
			$.each(hideData, function (key, content) {
				for (let str of content) {
					if (loc.indexOf(str) > -1) {
						var tempDom = $('#collapse' + key);
						if (tempDom.length > 0)
							tempDom.parent().remove();
						$('.cid' + key).remove();
						break;
					}
				}
			});
		}
	});
})
</script>