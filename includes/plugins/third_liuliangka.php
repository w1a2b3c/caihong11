<?php
namespace plugins;

class third_liuliangka{

	private $config = [];

	static public $info = [
		'name'        => 'third_liuliangka',
		'type'        => 'third',
		'title'       => '发傲流量卡',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 31,
		'showedit'    => false,
		'showip'      => false,
		'pricejk'     => 0,
		'input' => [
			'url' => '网站域名',
			'username' => '用户ID',
			'password' => 'Token',
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
		$url = '/card/public/index.php/api/api/submit_info';
		$param = [
			'userid' => $this->config['username'],
			'keys' => $this->config['password'],
			'timestamp' => date("Y-m-d H:i:s"),
			'c_id' => $goods_id,
			'ce' => $input[1],
			'ce_m' => $input[0],
			'ce_a' => $input[2],
			'num' => $num,
			];
		$param['sign'] = $this->getSign($param, $this->config['password']);
		$post = http_build_query($param);
		$data = $this->get_curl($url,$post);
		$json = json_decode($data,true);
		if (isset($json['code']) && $json['code']==0) {
			$result = array(
				'code' => 0,
				'id' => $json['orderno']
			);
		} elseif(isset($json['message'])){
			$result['message'] = $json['message'];
		} else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_list(){
		$url = '/card/public/index.php/api/api/cate_info';
		$param = [
			'userid' => $this->config['username'],
			'keys' => $this->config['password'],
			'timestamp' => date("Y-m-d H:i:s"),
			];
		$param['sign'] = $this->getSign($param, $this->config['password']);
		$post = http_build_query($param);
		$data = $this->get_curl($url,$post);
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] != '0000') {
			return $ret['message'];
		} else {
			$data = json_decode($ret['result'], true);
			$list = array();
			foreach ($data as $v) {
				$list[] = array(
					'id' => $v['id'],
					'name' => $v['category_name']
				);
			}
			return $list;
		}
	}

	public function query_order($orderid, $goodsid, $value = []){
		$order_state = array('WAIT'=>'待发货','SEND'=>'已发货','REFUSE'=>'拒绝');
		$courier_state = array(0=>'在途中',1=>'已揽收',2=>'疑难',3=>'已签收',4=>'退签',5=>'同城派送中',6=>'退回',7=>'转单');
		$url = '/card/public/index.php/api/api/order_info';
		$param = [
			'userid' => $this->config['username'],
			'keys' => $this->config['password'],
			'timestamp' => date("Y-m-d H:i:s"),
			'mobile' => $orderid,
			];
		$param['sign'] = $this->getSign($param, $this->config['password']);
		$post = http_build_query($param);
		$data = $this->get_curl($url,$post);
		if (!$ret = json_decode($data, true)) {
			return false;
		} elseif ($ret['code'] == '0000') {
			$v = json_decode($ret['result'], true);
			$v = $v[0];
			$result = [
				'订单状态' => $order_state[$v['state']],
				'订单创建时间' => $v['created_time'],
				'出卡数量' => $v['card_number'],
				];
			if($v['note'])$result['备注信息'] = $v['note'];
			if($v['courier_number']){
				$result += [
				'快递单号' => $v['courier_number'],
				'出单时间' => $v['action_time'],
				'监控消息' => $v['courier_message'],
				'签收状态' => $courier_state[$v['courier_state']],
				];
			}
			return $result;
		} else{
			return $ret['message'];
		}
	}

	private function get_curl($path,$post=0){
		$url = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] . $path;
		return get_curl($url,$post);
	}

	private function getSign($param, $key)
	{
		ksort($param);
		$str = $key . '';
		foreach ($param as $k => $v) {
			$str .= $k . "=" . $v . "&";
		}
		$sign = strtoupper(md5($str . 'key=' . $key));
		return $sign;
	}
}