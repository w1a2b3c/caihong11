<?php
namespace plugins;

class third_shangzhanwl{

	private $config = [];

	static public $info = [
		'name'        => 'third_shangzhanwl',
		'type'        => 'third',
		'title'       => '商战网',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 22,
		'showedit'    => false,
		'showip'      => false,
		'pricejk'     => 1,
		'input' => [
			'url' => '网站域名',
			'username' => '商家编号',
			'password' => '接口密钥',
			'paypwd' => '支付密码',
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

		$param = array('customerid'=>$this->config['username'], 'goodsid'=>$goods_id, 'accountname'=>$input[0] , 'quantity'=>$num, 'tradepassword' => $this->config["paypwd"], 'external_orderno'=>$tradeno);

		if($goods_type==1){
			$url = '/api.php/Client/createCdKeyOrder';
		}else{
			$url = '/api.php/Client/createOrder';
			$i=0;
			foreach ($input as $val){
				$param['lblName'.$i] = $input[$i];
				$i++;
			}
		}

		$sign = md5($this->config['username'] . $goods_id . $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$data = $this->get_curl($url, $post);
		$json = json_decode($data,true);
		if(isset($json['code']) && $json['code']==1000){
			$result = array(
				'code' => 0,
				'id' => $json['data']['orderno']
			);
			if(!empty($json['data']['card'])){
				$kmdata = [];
				foreach($json['data']['card'] as $kmrow){
					if(!empty($kmrow['card_password']) && !empty($kmrow['card_no'])){
						$kmdata[] = ['card'=>$kmrow['card_no'], 'pass'=>$kmrow['card_password']];
					}elseif(empty($kmrow['card_no'])){
						$kmdata[] = ['card'=>$kmrow['card_password']];
					}else{
						$kmdata[] = ['card'=>$kmrow['card_no']];
					}
				}
				$result['faka']=true;
				$result['kmdata']=$kmdata;
			}
		}elseif(isset($json['info'])){
			$result['message'] = $json['info'];
		}else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_list(){
		$url = '/api.php/Client/goodsList';
		$param = array('customerid'=>$this->config['username']);
		$sign = md5($this->config['username'] . $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$ret = $this->get_curl($url, $post);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} else if ($ret['code'] != 1000) {
			return $ret['info'];
		} else {
			$list = array();
			foreach ($ret['data'] as $v) {
				$list[] = array(
					'id' => $v['id'],
					'name' => $v['name'],
					'type' => $v['type'],
					'price' => $v['price'],
					'stock_state' => $v['stock_state'],
					'supply_state' => $v['supply_state'],
					'stock_num' => $v['stock_num']
				);
			}
			return $list;
		}
	}

	public function goods_info($goods_id){
		$url = '/api.php/Client/goodsInfo';
		$param = array('customerid'=>$this->config['username'], 'id'=>$goods_id);
		$sign = md5($this->config['username'] . $goods_id . $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$data = $this->get_curl($url, $post);
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] == 1000) {
			if($ret['data']['id']==null){
				return '商品不存在';
			}
			$return = $ret['data'];
			$return['input'] = $ret['data']['template']['account'];
			$inputs = '';
			foreach($ret['data']['template']['content'] as $row){
				if($row['type'] == 'select' || $row['type'] == 'radio'){
					$inputs .= $row['name'].'{'.$row['value'].'}|';
				}else{
					$inputs .= $row['name'].'|';
				}
			}
			$return['inputs'] = trim($inputs,'|');
			return $return;
		} else {
			return $ret['info'];
		}
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$url = '/api.php/Client/orderDetail';
		$order_stats = [0=>'未知', 1=>'待处理', 2=>'正在处理', 3=>'交易成功', 4=>'处理失败', 5=>'成功退款', 6=>'订单异常'];
		$param = array('customerid'=>$this->config['username'], 'orderno'=>$orderid);
		$sign = md5($this->config['username'] . $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$data = $this->get_curl($url, $post);
		if (!$ret = json_decode($data, true)) {
			return false;
		} elseif ($ret['code'] == 1000) {
			$return = ['订单状态'=>$order_stats[$ret['data']['orderstate']]];
			if(!empty($ret['returninfo']))$return['返回信息'] = $ret['data']['returninfo'];
			if($ret['data']['start_progress']>0 || $ret['data']['target_progress']>0){
				$return['开始进度'] = $ret['data']['start_progress'];
				$return['目标进度'] = $ret['data']['target_progress'];
				$return['当前进度'] = $ret['data']['current_progress'];
			}
			if(isset($ret['data']['recharge']['card']) && count($ret['data']['recharge']['card'])>0){
				$kmdata = '';
				foreach($ret['data']['recharge']['card'] as $kmrow){
					if(!empty($kmrow['card_password']) && !empty($kmrow['card_no'])){
						$kmdata.='卡号：'.$kmrow['card_no'].' 密码：'.$kmrow['card_password'].'<br/>';
					}elseif(empty($kmrow['card_no'])){
						$kmdata.=$kmrow['card_password'].'<br/>';
					}else{
						$kmdata.=$kmrow['card_no'].'<br/>';
					}
				}
				$return['卡密信息'] = $kmdata;
			}
			return $return;
		} else{
			return $ret['info'];
		}
	}

	public function pricejk($shequid,&$success){
		global $DB,$conf;
		$list = $this->goods_list();
		if(is_array($list)){
			$price_arr = array();
			$goods_status_arr = array();
			$stock_arr = array();
			foreach($list as $row){
				$price_arr[$row['id']] = $row['price'];
				$goods_status_arr[$row['id']] = $row['supply_state']; //1 上架 2 暂停 3 下架
				$stock_arr[$row['id']] = $row['type']==1?intval($row['stock_num']):null;
			}
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']})");
			while($res2 = $rs2->fetch())
			{
				if($res2['price']==='0.00')continue;
				if(isset($price_arr[$res2['goods_id']]) && $price_arr[$res2['goods_id']]>0 && $res2['prid']>0){
					$price = ceil($price_arr[$res2['goods_id']] * 100)/100;
					if($conf['pricejk_edit']==1 && $price>$res2['price']){
						$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					}elseif($conf['pricejk_edit']==0 && $price!=$res2['price']){
						$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					}
				}
				if(isset($goods_status_arr[$res2['goods_id']])){
					if($goods_status_arr[$res2['goods_id']]!='1' && $res2['close']==0){
						$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
					}elseif($goods_status_arr[$res2['goods_id']]=='1' && $res2['close']==1){
						$DB->exec("update `pre_tools` set `close`=0 where `tid`='{$res2['tid']}'");
					}
				}
				if(isset($stock_arr[$res2['goods_id']]) && $stock_arr[$res2['goods_id']]!==null && $res2['stock']!=$stock_arr[$res2['goods_id']]){
					$DB->exec("update `pre_tools` set `stock`=:stock where `tid`='{$res2['tid']}'", [':stock'=>$stock_arr[$res2['goods_id']]]);
				}
			}
			return true;
		}else{
			return $list;
		}
	}

	private function get_curl($path,$post=0,$referer=0,$cookie=0,$header=0,$addheader=0){
		$url = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] . $path;
		return shequ_get_curl($url,$post,$referer,$cookie,$header,$addheader);
	}
}