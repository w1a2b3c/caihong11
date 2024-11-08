<?php
namespace plugins;

class third_kashangwl{

	private $config = [];

	static public $info = [
		'name'        => 'third_kashangwl',
		'type'        => 'third',
		'title'       => '卡商网',
		'author'      => '安享',
		'version'     => '1.0',
		'sort'        => 21,
		'link'        => '',
		'showedit'    => false,
		'showip'      => false,
		'pricejk'     => 2,
		'input' => [
			'url' => '网站域名',
			'username' => '商家编号',
			'password' => '接口密钥',
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
		global $siteurl;
		$siteurl = str_replace('/admin/','/',$siteurl);

		$result['code'] = -1;
		$inputs=explode('|',$inputsname);

		$url = '/api/buy';
		$param = [
			'customer_id' => $this->config['username'],
			'timestamp' => time(),
			'product_id' => $goods_id,
			'recharge_account' => $input[0],
			'quantity' => $num,
			'notify_url' => $siteurl.'other/notify.php/shequ/'.$this->config['id'].'/'.$tradeno.'/',
			'outer_order_id' => $tradeno
		];
		if (is_array($inputs) && $inputs[0]){
			$recharge_template_input_items = array();
			$i=1;
			foreach ($inputs as $val){
				$param['recharge_template_input_items['.$val.']'] = $input[$i];
				$i++;
			}
		}
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$data = $this->get_curl($url, $post);
		$json = json_decode($data,true);
		if(isset($json['code']) && $json['code']=='ok'){
			$result = array(
				'code' => 0,
				'id' => $json['data']['order_id']
			);
			if(isset($json['data']['cards']) && count($json['data']['cards'])>0){
				$kmdata = [];
				foreach($json['data']['cards'] as $kmrow){
					$kmdata[] = ['card'=>$kmrow['card_no'], 'pass'=>$kmrow['card_password']];
				}
				$result['faka'] = true;
				$result['kmdata'] = $kmdata;
			}
		}elseif(isset($json['message'])){
			$result['message'] = $json['message'];
		}else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_info($goods_id, $is_params = true){
		$url = '/api/product';
		$param = array('customer_id'=>$this->config['username'], 'timestamp'=>time(), 'product_id'=>$goods_id);
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$data = $this->get_curl($url, $post);
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] == 'ok') {
			$return = $ret['data'];
			if(!$is_params)return $return; 
			$url = '/api/product/recharge-params';
			$param = array('customer_id'=>$this->config['username'], 'timestamp'=>time(), 'product_id'=>$goods_id);
			$sign = $this->getSign($param, $this->config['password']);
			$param['sign'] = $sign;
			$post = http_build_query($param);
			$data = $this->get_curl($url, $post);
			$ret = json_decode($data, true);
			if ($ret['code'] == 'ok') {
				$return['input'] = $ret['data']['recharge_account_label'];
				$inputs = '';
				foreach($ret['data']['recharge_params'] as $row){
					if($row['type'] == 'select' || $row['type'] == 'radio'){
						$inputs .= $row['name'].'{'.$row['options'].'}|';
					}else{
						$inputs .= $row['name'].'|';
					}
				}
				$return['inputs'] = trim($inputs,'|');
				return $return;
			} else {
				return $return;
			}
		} else {
			return $ret['message'];
		}
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$url = '/api/order';
		$order_state = [100 => '待处理', 200 => '充值成功', 500 => '充值失败'];
		$param = array('customer_id'=>$this->config['username'], 'timestamp'=>time(), 'order_id'=>$orderid);
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$data = $this->get_curl($url, $post);
		if (!$ret = json_decode($data, true)) {
			return false;
		} elseif ($ret['code'] == 'ok') {
			$v = $ret['data'];
			if(isset($v['progress_init'])){
				return array('num'=>$v['progress_target']-$v['progress_init'],'start_num'=>$v['progress_init'],'now_num'=>$v['progress_now'],'end_num'=>$v['progress_target'],'add_time'=>$v['created_at'],'order_state'=>$v['recharge_info']);
			}else{
				$result = ['订单状态' => $order_state[$v['state']]];
				if(!empty($v['recharge_info']))$result['返回信息'] = $v['recharge_info'];
				return $result;
			}
		} else{
			return $ret['message'];
		}
	}

	public function pricejk_one($tool){
		global $DB,$conf;
		$success=0;
		$details = $this->goods_info($tool['goods_id'], false);
		if(is_array($details)){
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ={$tool['shequ']} AND goods_id={$tool['goods_id']}");
			while($res2 = $rs2->fetch())
			{
				if($res2['price']==='0.00')continue;
				$price = ceil($details['price'] * 100)/100;
				if($conf['pricejk_edit']==1 && $price>$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}elseif($conf['pricejk_edit']==0 && $price!=$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}
				if(($details['supply_state']>1 || $details['stock_state']>1) && $res2['close']==0){
					$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
				}elseif($details['supply_state']==1 && $details['stock_state']==1 && $res2['close']==1){
					$DB->exec("update `pre_tools` set `close`=0 where `tid`='{$res2['tid']}'");
				}
				$DB->exec("update `pre_tools` set `uptime`='".time()."' where `tid`='{$res2['tid']}'");
			}
		}elseif(strpos($details,'商品不存在')!==false){
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ={$tool['shequ']} AND goods_id={$tool['goods_id']}");
			while($res2 = $rs2->fetch())
			{
				$DB->exec("update `pre_tools` set `close`=1,`uptime`='".time()."' where `tid`='{$res2['tid']}'");
				$success++;
			}
		}
		return $success;
	}

	public function pricejk($shequid,&$success){
		global $DB,$conf;
		$pricejk_time = $conf['pricejk_time']?$conf['pricejk_time']:600;
		for($i=0;$i<30;$i++){
			$tool=$DB->getRow("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']}) AND uptime<'".(time()-$pricejk_time)."' ORDER BY uptime ASC");
			if(!$tool)break;
			$count = $this->pricejk_one($tool);
			$success+=$count;
		}
		return true;
	}

	public function notify($order){
		global $DB;
		$arr = $_POST;
		if(isset($arr['order_id']) && $this->getSign($arr, $this->config['password']) == $arr['sign']){
			if($order['status']==2 && $arr['state']==200){
				$DB->exec("update pre_orders set status=1 where id='{$order['id']}'");
			}elseif($order['status']==2 && $arr['state']==500){
				$DB->exec("update pre_orders set status=3 where id='{$order['id']}'");
				if($arr['state_info']){
					$DB->exec("update pre_orders set result=:result where id=:id", [':result'=>$arr['state_info'], ':id'=>$order['id']]);
				}
			}
			echo 'ok';
		}else{
			echo 'error';
		}
		return true;
	}

	private function get_curl($path,$post=0,$referer=0,$cookie=0,$header=0,$addheader=0){
		$url = 'http://www.kashangwl.com' . $path;
		return shequ_get_curl($url,$post,$referer,$cookie,$header,$addheader);
	}

	private function getSign($param, $key)
	{
		$signPars = "";
		ksort($param);
		foreach ($param as $k => $v) {
			if ("sign" != $k) {
				$signPars .= $k. $v;
			}
		}
		$signPars = $key.$signPars;
		$sign = md5($signPars);
		return $sign;
	}
}