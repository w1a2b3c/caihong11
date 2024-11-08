<?php
namespace lib;

class Plugin {

	static public function getList(){
		$dir = PLUGIN_ROOT;
		$dirArray[] = NULL;
        if (false != ($handle = opendir($dir))) {
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && strpos($file, ".php")) {
                    $dirArray[$i] = $file;
                    $i++;
                }
            }
            closedir($handle);
        }
        return $dirArray;
	}

    static public function getConfig($name){
        $classname = '\\plugins\\'.$name;
		if(class_exists($classname) && property_exists($classname, 'info')){
			return $classname::$info;
		}else{
			return false;
		}
	}

    static public function getThirdPluginsList(){
        global $CACHE;
        $data = $CACHE->read('ThirdPluginsList');
        if($data){
            return unserialize($data);
        }
        return self::refreshThirdPluginsList();
    }

    static public function refreshThirdPluginsList(){
        global $CACHE;
        $data = [];
        $list = self::getList();
        $pricejk_type1 = [];
        $pricejk_type2 = [];
        foreach($list as $name){
            $name = str_replace('.php','',$name);
            if ($config = self::getConfig($name)) {
                $config['code'] = str_replace('third_','',$config['name']);
                $data[] = $config;
                if($config['pricejk']==2)$pricejk_type2[] = $config['code'];
                elseif($config['pricejk']==1)$pricejk_type1[] = $config['code'];
            }
        }
        foreach($data as $val){
            $key_arrays[]=$val['sort'];
        }
        array_multisort($key_arrays,SORT_ASC,SORT_NUMERIC,$data);
        $CACHE->save('ThirdPluginsList', serialize($data));
        $CACHE->save('pricejk_type1', implode(',',$pricejk_type1));
        $CACHE->save('pricejk_type2', implode(',',$pricejk_type2));
        return $data;
    }

    static public function showThirdPluginsEditJs(){
        $list = self::getThirdPluginsList();
        foreach($list as $plugin){
            if($plugin['showedit']){
                third_call($plugin['code'], [], 'shopeditjs');
            }
        }
    }

}
