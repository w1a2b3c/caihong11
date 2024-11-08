<?php
namespace plugins;

class third_kakayun{

	private $config = [];

	static public $info = [
		'name'        => 'third_kakayun',
		'type'        => 'third',
		'title'       => '卡卡云',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 27,
		'showedit'    => false,
		'showip'      => false,
		'pricejk'     => 1,
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
		$url = '/dockapi/index/buy.html';
		$param = [
			'userid'=>$this->config['username'],
			//'outorderno'=>$tradeno,
			'goodsid'=>$goods_id,
			'buynum'=>$num,
			'maxmoney'=>$money
		];
		if($goods_type==0){
			$attach=[];
			foreach($input as $inpu){
				if(empty($inpu))continue;
				$attach[] = $inpu;
			}
			$param['attach'] = json_encode($attach);
		}
		$param['sign'] = $this->getSign($param,$this->config['password']);
		$data = $this->get_curl($url,http_build_query($param));
		$json = json_decode($data,true);
		if (isset($json['code']) && $json['code']==1) {
			$result = array(
				'code' => 0,
				'id' => $json['orderno']
			);
			if($goods_type==1||is_array($json['cardlist']) && count($json['cardlist'])>0){
				$kami = [];
				foreach($json['cardlist'] as $row){
					$kami[] = ['card'=>$row];
				}
				$result['faka']=true;
				$result['kmdata']=$kami;
			}
		} elseif(array_key_exists('msg',$json)){
			$result['message'] = $json['msg'];
		} else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_list(){
		$url = '/dockapi/index/getallgoods.html';
		$param = [
			'userid' => $this->config['username']
		];
		$param['sign'] = $this->getSign($param,$this->config['password']);
		$data = $this->get_curl($url,http_build_query($param));
		$json = json_decode($data,true);
		if (isset($json['code']) && $json['code']==1) {
			$list = [];
			foreach($json['data'] as $rows){
				foreach($rows['goods'] as $row){
					$list[] = array(
						'id' => $row['id'],
						'name' => $row['goodsname'],
						'shopimg' => $row['imgurl'],
						'price' => $row['goodsprice'],
						'status' => $row['goodsstatus'],
						'type' => $row['goodstype'],
						'alert' => $row['tiptext'],
						'desc' => $row['details'],
						'min' => $row['buyminnum'],
						'max' => $row['buymaxnum'],
						'stock' => $row['stock']
					);
				}
			}
			return $list;
		}elseif (isset($json['msg'])){
			return $json['msg'];
		}else{
			return '打开对接网站失败';
		}
	}

	public function goods_info($goods_id){
		$url = '/dockapi/index/goodsdetails.html';
		$param = [
			'userid' => $this->config['username'],
			'goodsid' => $goods_id
		];
		$param['sign'] = $this->getSign($param,$this->config['password']);
		$data = $this->get_curl($url,http_build_query($param));
		$json = json_decode($data,true);
		if (isset($json['code']) && $json['code']==1) {
			$row = $json['goodsdetails'];
			$result = array(
				'id' => $row['id'],
				'name' => $row['goodsname'],
				'shopimg' => $row['imgurl'],
				'price' => $json['price']['data']['goodsprice'],
				'status' => $row['goodsstatus'],
				'type' => $row['goodstype'],
				'alert' => $row['tiptext'],
				'desc' => $row['details'],
				'min' => $row['buyminnum'],
				'max' => $row['buymaxnum'],
				'stock' => $row['stock']
			);
			return $result;
		} elseif(array_key_exists('msg',$json)){
			return $json['msg'];
		} else{
			return '打开对接网站失败';
		}
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$order_state = array('未使用','已使用','未付款','进行中','已撤回','已完成');
		$url = '/dockapi/index/queryorder.html';
		$param = [
			'userid' => $this->config['username'],
			'orderno' => $orderid
		];
		$param['sign'] = $this->getSign($param, $this->config['password']);
		$data = $this->get_curl($url,http_build_query($param));
		$json = json_decode($data,true);
		if (isset($json['code']) && $json['code']==1) {
			$result = [
				'订单状态' => $order_state[$json['data']['status']]
			];
			return $result;
		}
		return false;
	}

	public function pricejk($shequid, &$success)
	{
		global $DB, $conf;
		$list = $this->goods_list();
		if (is_array($list)) {
			$price_arr = array();
			$goods_status_arr = array();
			$stock_arr = array();
			foreach ($list as $row) {
				$price_arr[$row['id']] = round($row['price']/100, 2);
				$goods_status_arr[$row['id']] = $row['status']; //商品状态 0下架 1出售中
				$stock_arr[$row['id']] = $row['stock']; //库存
			}
			$rs2 = $DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']})");
			while ($res2 = $rs2->fetch()) {
				if ($res2['price']==='0.00') continue;
				if (isset($price_arr[$res2['goods_id']]) && $price_arr[$res2['goods_id']] > 0 && $res2['prid']>0) {
					$price = ceil($price_arr[$res2['goods_id']] * $res2['value'] * 100)/100;
					if ($conf['pricejk_edit'] == 1 && $price > $res2['price']) {
						$DB->query("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					} elseif ($conf['pricejk_edit']==0 && $price != $res2['price']) {
						$DB->query("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					}
				}
				if(isset($goods_status_arr[$res2['goods_id']])){
					if($goods_status_arr[$res2['goods_id']]==0 && $res2['close']==0){
						$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
					}elseif($goods_status_arr[$res2['goods_id']]==1 && $res2['close']==1){
						$DB->exec("update `pre_tools` set `close`=0 where `tid`='{$res2['tid']}'");
					}
				}elseif($res2['close']==0){
					$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
				}
				if(isset($stock_arr[$res2['goods_id']]) && $stock_arr[$res2['goods_id']]!==null && $res2['stock']!==$stock_arr[$res2['goods_id']]){
					$DB->exec("update `pre_tools` set `stock`=:stock where `tid`='{$res2['tid']}'", [':stock'=>$stock_arr[$res2['goods_id']]]);
				}
			}
			return true;
		} else {
			return '获取商品列表失败';
		}
	}

	private function get_curl($path,$post=0,$referer=0,$cookie=0,$header=0,$addheader=0){
		$url = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] . $path;
		return get_curl($url,$post,$referer,$cookie,$header,$addheader);
	}

	private function getSign($param,$userkey){
		ksort($param);
		reset($param);
		$signtext='';
		foreach ($param AS $key => $val) {
			if ($val == '' || $key == 'sign') continue;
			if ($signtext) $signtext .= '&';
			$signtext .= "$key=$val";
		}
		$newsign=md5($signtext.$userkey);
		return $newsign;
	}
}