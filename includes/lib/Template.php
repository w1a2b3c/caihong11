<?php
namespace lib;

class Template {

	static public function getList(){
		$dir = TEMPLATE_ROOT;
		$dirArray[] = NULL;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && !strpos($file, ".")) {
                    $dirArray[$i] = $file;
                    $i++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
	}

	static public function load($name = 'index'){
		global $conf;
		$template = $conf['template']?$conf['template']:'default';
		if(checkmobile() && $conf['template_m'] && $conf['template_m']!='0')$template = $conf['template_m'];
		if(!preg_match('/^[a-zA-Z0-9]+$/',$name))exit('error');
		$filename = TEMPLATE_ROOT.$template.'/'.$name.'.php';
		$filename_default = TEMPLATE_ROOT.'default/'.$name.'.php';
		if(file_exists($filename)){
			return $filename;
		}elseif(file_exists($filename_default)){
			return $filename_default;
		}else{
			exit('Template file not found');
		}
	}

	static public function loadConfig(){
		global $conf;
		$template = $conf['template']?$conf['template']:'default';
		if(checkmobile() && $conf['template_m'] && $conf['template_m']!='0')$template = $conf['template_m'];
		$filename = TEMPLATE_ROOT.$template.'/config.php';
		if(file_exists($filename)){
			include($filename);
			return $template_info;
		}else{
			return false;
		}
	}

	static public function loadSetting(){
		global $conf;
		$template = $conf['template']?$conf['template']:'default';
		if(checkmobile() && $conf['template_m'] && $conf['template_m']!='0')$template = $conf['template_m'];
		$filename = TEMPLATE_ROOT.$template.'/config.php';
		if(file_exists($filename)){
			include($filename);
			return $template_settings;
		}else{
			return false;
		}
	}

	static public function loadRoute(){
		global $conf;
		$template = $conf['template']?$conf['template']:'default';
		if(checkmobile() && $conf['template_m'] && $conf['template_m']!='0')$template = $conf['template_m'];
		$filename = TEMPLATE_ROOT.$template.'/config.php';
		if(file_exists($filename)){
			include($filename);
			$var = checkmobile()&&isset($template_route_m)?$template_route_m:$template_route;
			return $var;
		}else{
			return false;
		}
	}

	static public function exists($template){
		$filename = TEMPLATE_ROOT.$template.'/index.php';
		if(file_exists($filename)){
			return true;
		}else{
			return false;
		}
	}

	static public function random_picture(){
		// 随机背景图片列表
		$pictures = [
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rB9hATbiaYR8DKeoBjvXKDiaztELl90ImXtQ/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rEshdOekfrjFoGh0hBA8c2vibktcVN3H4VQ/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rAqMVFGXIjpQDdGwL1n1LvNquw24Crs5mg/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rLyFCru0fnP8oWnG93s6OEsa8fk2RD0EHg/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rFMmsT2rFzmtWB348ZqZ4LMNicymcMN7aXA/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rABIXwWibeVsTyEPCic3rgFd5Ub3Ws2icOqPQ/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rNW4Uq2HNeh8aHey8bmupSJ3yO7RPpZkCg/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rNNWdJiab8eNj8ZChtz0TgXVg1kHrObSqSA/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rLNOpGUsNEDKCZpYoDahH3mDCyrKND9ibDA/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rIoIJlvYCU6opxj4JJO6yMKFaicjJgic6ANw/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rG3771XCyQ5icOLEicWRpicdibyQZMjmy2etZA/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rOs3FibDFlCNW2aC9vT9LNGXic9g7GQLxQfA/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rHHlAmEkUg7Jmjiatiaqz78XYCx8xuLTib59Q/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rMib5Dm9OgbxulhqbpiahUIyk9qakuvFiaSDQ/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rBG38IngZdEnl4NT7DELu5guRSILZrpPdw/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rHpQ82QF1aWtAh0Hm04BicibHtaYYRQgLVpQ/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rD7nSficJUDnkic8RzzJmrFB11F5mlofSvibg/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rDhhxIRqibnG3euW0K6SicW2ZTkbg0up6WUQ/0',
			'//puep.qpic.cn/coral/Q3auHgzwzM4fgQ41VTF2rMgTxnRYrIaz01y9pXd8EBZJwyibOYgUjoQ/0',
		];

		return $pictures[array_rand($pictures)];
	}

	static public function getBackground($path=null){
		global $conf,$DB,$CACHE;
		if($conf['ui_bing']==1){
			$background_image = self::random_picture();
			$background_css = '<style>body{ background:#ecedf0 url("'.$background_image.'") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>';
		}elseif($conf['ui_bing']==2){
			if(date("Ymd")==$conf['ui_bing_date']){
				$background_image=$conf['ui_backgroundurl'];
				if(checkmobile()==true)$background_image=str_replace('1920x1080','768x1366',$background_image);
			}else{
				$url = 'https://cn.bing.com/HPImageArchive.aspx?format=js&idx=0&n=1&mkt=zh-CN';
				$bing_data = get_curl($url);
				$bing_arr=json_decode($bing_data,true);
				if (!empty($bing_arr['images'][0]['url'])) {
					$background_image='//cn.bing.com'.$bing_arr['images'][0]['url'];
					saveSetting('ui_backgroundurl', $background_image);
					saveSetting('ui_bing_date', date("Ymd"));
					$CACHE->clear();
					if(checkmobile()==true)$background_image=str_replace('1920x1080','768x1366',$background_image);
				}
			}
			$background_css = '<style>body{ background:#ecedf0 url("'.$background_image.'") fixed;background-repeat:no-repeat;background-size:100% 100%;}</style>';
		}elseif($conf['ui_bing']==3){
			if($conf['ui_colorto']==1){
				$background_css = '<style>body{ background: linear-gradient(to right,'.$conf['ui_color1'].','.$conf['ui_color2'].') fixed;}</style>';
			}else{
				$background_css = '<style>body{ background: linear-gradient(to bottom,'.$conf['ui_color1'].','.$conf['ui_color2'].') fixed;}</style>';
			}
		}else{
			$background_image=$path.'assets/img/bj.png';
			if($conf['ui_background']==0)
			$repeat='background-repeat:repeat;';
			elseif($conf['ui_background']==1)
			$repeat='background-repeat:repeat-x; background-size:auto 100%;';
			elseif($conf['ui_background']==2)
			$repeat='background-repeat:repeat-y; background-size:100% auto;';
			elseif($conf['ui_background']==3)
			$repeat='background-repeat:no-repeat; background-size:100% 100%;';
			$background_css = '<style>body{ background:#ecedf0 url("'.$background_image.'") fixed;'.$repeat.'}</style>';
		}
		return [$background_image, $background_css];
	}
}
