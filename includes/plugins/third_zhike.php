<?php
namespace plugins;

class third_zhike{

	private $config = [];

	static public $info = [
		'name'        => 'third_zhike',
		'type'        => 'third',
		'title'       => '直客SUP',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 24,
		'showedit'    => false,
		'showip'      => true,
		'batchgoods'    => true,
		'pricejk'     => 2,
		'input' => [
			'url' => '网站域名',
			'username' => '应用ID',
			'password' => '应用密钥',
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
		$array = explode('#',$goods_param);
		$goodsid = $array[0];
		if(!$goodsid)return;
		$param = explode('|',$array[1]);

		$path = '/api/client/goods/v2/order';
		$params = [];
		$params[] = ['alias'=>$param[0], 'value'=>$input[0]];
		for($i=0;$i<count($param)-1;$i++){
			$params[] = ['alias'=>$param[$i+1], 'value'=>$input[$i+1]];
		}
		$body = ['goodsSN'=>$goodsid, 'buyNotify'=>-1, 'number'=>$num, 'params'=>$params];
		$post = json_encode($body);
		
		$data = $this->get_curl($path, $post);
		$json = json_decode($data,true);
		if(isset($json['code']) && $json['code']==100){
			$result = array(
				'code' => 0,
				'id' => $json['result']['orderSN']
			);
		}elseif(isset($json['msg'])){
			$result['message'] = $json['msg'];
		}else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_list(){
		$path = '/api/client/goods/v2/goods/list';
		$ret = $this->get_curl($path);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} else if ($ret['code'] != 100) {
			return $ret['msg'];
		} else {
			$list = array();
			foreach ($ret['result']['data'] as $v) {
				$list[] = array(
					'id' => $v['goodsSN'],
					'name' => $v['goodsName'],
					'shopimg' => $v['goodsThumb']
				);
			}
			return $list;
		}
	}

	public function goods_info($goods_id){
		$goodsSN = isset($_POST['goods_param'])?trim($_POST['goods_param']):$goods_id;
		$path = '/api/client/goods/v2/goods?goodsSN='.$goodsSN;
		$data = $this->get_curl($path);
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] == 100) {
			if($ret['result']['goodsThumb']&&substr($ret['result']['goodsThumb'],0,4)!='http')$ret['result']['goodsThumb'] = 'http://'.$this->config['url'].$ret['result']['goodsThumb'];
			$return = [
				'id' => $ret['result']['goodsSN'],
				'name' => $ret['result']['goodsName'],
				'shopimg' => $ret['result']['goodsThumb'],
				'unit' => $ret['result']['goodsUnit'],
				'desc' => $ret['result']['goodsDetail'],
				'min' => $ret['result']['minOrderNum'],
				'max' => $ret['result']['maxOrderNum'],
				'price' => $ret['result']['goodsPrice'],
				'type' => $ret['result']['goodsType'],
				'unitnum' => $ret['result']['preUnitNum'],
				'close' => $ret['result']['isClose'],
			];
			$return['input'] = str_replace('：','',$ret['result']['paramsTemplate'][0]['name']);
			$inputs = '';
			$alias = '';
			foreach($ret['result']['paramsTemplate'] as $row){
				$alias .= $row['alias'].'|';
				if(str_replace('：','',$row['name']) == $return['input'])continue;
				$inputs .= str_replace('：','',$row['name']).'|';
			}
			$alias = trim($alias,'|');
			$return['inputs'] = trim($inputs,'|');
			$return['alias'] = $alias;
			$return['goods_param'] = $ret['result']['goodsSN'].'#'.$alias;
			return $return;
		} else {
			return $ret['msg'];
		}
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$order_state = array(0=>'待处理',1=>'已付款',2=>'处理中',3=>'待确认',4=>'已完成',5=>'退单中',6=>'已退单',7=>'已退款',8=>'待处理');
		$path = '/api/client/goods/v2/order?orderSN='.$orderid;
		$data = $this->get_curl($path);
		if (!$ret = json_decode($data, true)) {
			return false;
		} elseif ($ret['code'] == 100) {
			$return = ['订单状态'=>$order_state[$ret['result']['orderState']]];
			if($ret['result']['startNum']>0 || $ret['result']['currentNum']>0){
				$return['开始数量'] = $ret['result']['startNum'];
				$return['当前数量'] = $ret['result']['currentNum'];
				$return['完成数量'] = $ret['result']['finishTotal'];
			}
			if(!empty($ret['result']['cardNumber'])){
				$return['卡密信息'] = implode('<br/>',explode(',',$ret['result']['cardNumber']));
			}
			return $return;
		} else{
			return $ret['msg'];
		}
	}

	public function pricejk_one($tool){
		global $DB,$conf;
		$success=0;
		$array = explode('#',$tool['goods_param']);
		$goodsid = $array[0];
		if(!$goodsid)return;
		$details = $this->goods_info($goodsid);
		if(is_array($details)){
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ={$tool['shequ']} AND goods_param='{$tool['goods_param']}'");
			while($res2 = $rs2->fetch())
			{
				if($res2['price']==='0.00')continue;
				$price = ceil($details['price'] * $res2['value'] * 100)/100;
				if($conf['pricejk_edit']==1 && $price>$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}elseif($conf['pricejk_edit']==0 && $price!=$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}
				if($details['close']==true && $res2['close']==0){
					$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
				}elseif($details['close']==false && $res2['close']==1){
					$DB->exec("update `pre_tools` set `close`=0 where `tid`='{$res2['tid']}'");
				}
				$DB->exec("update `pre_tools` set `uptime`='".time()."' where `tid`='{$res2['tid']}'");
			}
		}elseif(strpos($details,'商品不存在')!==false){
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ={$tool['shequ']} AND goods_param='{$tool['goods_param']}'");
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
		for($i=0;$i<10;$i++){
			$tool=$DB->getRow("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']}) AND uptime<'".(time()-$pricejk_time)."' ORDER BY uptime ASC");
			if(!$tool)break;
			$count = $this->pricejk_one($tool);
			$success+=$count;
		}
		return true;
	}

	public function batch_goods_list(){
		$path = '/api/client/goods/v2/goods/list';
		$ret = $this->get_curl($path);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} else if ($ret['code'] != 100) {
			return $ret['msg'];
		} else {
			$list = array();
			foreach ($ret['result']['data'] as $v) {
				$list[] = array(
					'goods_id' => $v['goodsSN'],
					'name' => $v['goodsName'],
					'shopimg' => $v['goodsThumb']
				);
			}
			return $list;
		}
	}

	private function get_curl($path,$post=0){
		$url = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] . $path;
		$time = time();
		$token = sha1($this->config['username'].$this->config['password'].$path.$time);
		$header = ['AppId: '.$this->config['username'], 'AppToken: '.$token, 'AppTimestamp: '.$time];
		if($post){
			$header[] = 'Content-Type: application/json; charset=UTF-8';
		}
		return shequ_get_curl($url,$post,0,0,0,$header);
	}
}