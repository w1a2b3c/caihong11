<?php
namespace plugins;

class third_qingjiu{

	private $config = [];

	static public $info = [
		'name'        => 'third_qingjiu',
		'type'        => 'third',
		'title'       => '晴玖商城',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 31,
		'showedit'    => false,
		'showip'      => false,
		'pricejk'     => 2,
		'input' => [
			'url' => '网站域名',
			'username' => '用户ID',
			'password' => '对接密钥',
			'paypwd' => false,
			'paytype' => false,
		],
	];

	public function __construct($config)
	{
		$this->config = $config;
	}

	public function do_goods($goods_id, $goods_type, $goods_param, $num = 1, $input = array(), $money, $tradeno, $inputsname)
	{
		$result['code'] = -1;
		$url = '/api.php';
		$param = [
			'act' => 'Docking_buy',
			'url' => $_SERVER['HTTP_HOST'],
			'id' => $this->config['username'],
			'num' => $num,
			'gid' => $goods_id,
			'type' => '1',
			'coupon' => '-2',
		];
		if (is_array($input)){
			$i = 1; 
			foreach ($input as $val) {
				if($val){
					$param[ 'value' . $i ] = $val;
					$i++;
				}
			}
		}
		$param['sign'] = $this->getSign($param, $this->config['password']);
		$data = $this->get_curl($url, http_build_query($param));
		$json = json_decode($data,true);
		if (isset($json['code']) && $json['code']==1) {
			$result = array(
				'code' => 0,
				'id' => $json['order']
			);
			if($json['faka']==true){
				$result['faka']=true;
				$result['kmdata']=$json['kmdata'];
			}
		} elseif(isset($json['msg'])){
			$result['message'] = $json['msg'];
		} else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_list(){
		$url = '/api.php';
		$param = [
			'act' => 'DockingGoodsList',
			'url' => $_SERVER['HTTP_HOST'],
			'id' => $this->config['username'],
		];
		$param['sign'] = $this->getSign($param, $this->config['password']);
		$data = $this->get_curl($url, http_build_query($param));
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] != 1) {
			return $ret['msg'];
		} else {
			$list = array();
			foreach ($ret['data'] as $v) {
				$list[] = array(
					'id' => $v['gid'],
					'cid' => $v['cid'],
					'name' => $v['name'],
					'state' => $v['state']
				);
			}
			return $list;
		}
	}

	public function goods_info($goods_id){
		$url = '/api.php';
		$param = [
			'act' => 'DockingGoodsLog',
			'url' => $_SERVER['HTTP_HOST'],
			'id' => $this->config['username'],
			'gid' => $goods_id
		];
		$param['sign'] = $this->getSign($param, $this->config['password']);
		$data = $this->get_curl($url, http_build_query($param));
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] != 1) {
			return $ret['msg'];
		} else {
			$return = $ret['data'];
			$return['shopimg'] = $ret['data']['image'][0];
			$return['input'] = $ret['data']['input'][0]['name'];
			$inputs = '';
			foreach($ret['data']['input'] as $row){
				if($row['name'] == $return['input']) continue;
				if($row['type'] == 2){
					$inputs .= $row['name'].'{'.implode(',',$row['data']).'}|';
				}else{
					$inputs .= $row['name'].'|';
				}
			}
			$return['inputs'] = trim($inputs,'|');
			if($return['shopimg']&&substr($return['shopimg'],0,4)!='http'&&substr($return['shopimg'],0,2)!='//')$return['shopimg'] = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] .$return['shopimg'];
			return $return;
		}
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$order_state = array(0=>'待处理',1=>'已完成',2=>'待处理',3=>'异常中',4=>'处理中',5=>'已退款',6=>'售后中',7=>'已评价');
		$url = '/api.php';
		$param = [
			'act' => 'DockingQuery',
			'url' => $_SERVER['HTTP_HOST'],
			'id' => $this->config['username'],
			'order' => $orderid
		];
		$param['sign'] = $this->getSign($param, $this->config['password']);
		$data = $this->get_curl($url, http_build_query($param));
		if (!$arr = json_decode($data , true)) {
			return false;
		} elseif (isset($arr['state']) && $arr['state'] == 1) {
			$result = ['订单状态'=>$arr['data']['state'], '状态描述'=>$arr['data']['remark']];
			if($arr['data']['Initial'] > 0){
				$result['初始数量'] = $arr['data']['Initial'];
				$result['当前数量'] = $arr['data']['Present'];
			}
			return $result;
		} elseif (isset($arr['msg'])) {
			return $arr['msg'];
		}
	}

	public function class_list(){
		$url = '/main.php';
		$param = [
			'act' => 'class',
			'num' => '999'
		];
		$data = $this->get_curl($url, http_build_query($param));
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] != 1) {
			return $ret['msg'];
		} else {
			return $ret['data'];
		}
	}

	/**
     * 价格监控（1个商品）
     * @return int 成功改变的商品数量
     */
	public function pricejk_one($tool){
		global $DB,$conf;
		$success=0;
		$details = $this->goods_info($tool['goods_id']);
		if(is_array($details)){
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ={$tool['shequ']} AND goods_id={$tool['goods_id']}");
			while($res2 = $rs2->fetch())
			{
				if($res2['price']==='0.00')continue;
				$price = ceil($details['money'] * $res2['value'] * 100)/100;
				if($conf['pricejk_edit']==1 && $price>$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}elseif($conf['pricejk_edit']==0 && $price!=$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}
				if($details['state']==2 && $res2['close']==0){
					$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
				}elseif($details['state']==1 && $res2['close']==1){
					$DB->exec("update `pre_tools` set `close`=0 where `tid`='{$res2['tid']}'");
				}
				$DB->exec("update `pre_tools` set `uptime`='".time()."' where `tid`='{$res2['tid']}'");
			}
		}
		return $success;
	}

	/**
     * 价格监控（批量）
     * @return bool
     */
	public function pricejk($shequid,&$success){
		global $DB,$conf;
		if($conf['pricejk_yile']==1){
			$pricejk_time = $conf['pricejk_time']?$conf['pricejk_time']:600;
			for($i=0;$i<10;$i++){
				$tool=$DB->getRow("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']}) AND uptime<'".(time()-$pricejk_time)."' ORDER BY uptime ASC");
				if(!$tool)break;
				$count = $this->pricejk_one($tool);
				$success+=$count;
			}
			return true;
		}else{
			$list = $this->goods_list();
			if(is_array($list)){
				$goods_status_arr = array();
				foreach($list as $row){
					$goods_status_arr[$row['id']] = $row['state']; //商品状态 2为已下架
				}
				$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']})");
				while($res2 = $rs2->fetch())
				{
					if(isset($goods_status_arr[$res2['goods_id']])){
						if($goods_status_arr[$res2['goods_id']]==2 && $res2['close']==0){
							$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
						}elseif($goods_status_arr[$res2['goods_id']]==1 && $res2['close']==1){
							$DB->exec("update `pre_tools` set `close`=0 where `tid`='{$res2['tid']}'");
						}
					}
				}
				return true;
			}else{
				return $list;
			}
		}
	}

	private function getSign($param, $key){
		$signPars = '';
        ksort($param);
        foreach ($param as $k => $v) {
            if ($k !== 'sign' && $v !== '') {
                $signPars .= $k . '=' . $v . '&';
            }
        }
        $signPars = trim($signPars, '&');
        $signPars .= $key;
        return md5($signPars);
	}

	private function get_curl($path,$post=0){
		$url = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] . $path;
		return get_curl($url,$post);
	}
}