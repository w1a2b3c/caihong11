<?php
namespace plugins;

class third_kayixin{

	private $config = [];

	static public $info = [
		'name'        => 'third_kayixin',
		'type'        => 'third',
		'title'       => '卡易信',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 13,
		'showedit'    => false,
		'showip'      => false,
		'pricejk'     => 2,
		'input' => [
			'url' => '网站域名',
			'username' => '客户编号',
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
		$result['code'] = -1;

		$param = array('userNo'=>$this->config['username'], 'id'=>$goods_id, 'mainKey'=>$goods_param, 'count'=>$num);
		// $param['safePrice'] = $money; //安全进价

		if($goods_type==1){
			$url = '/api/buyCardGoodOrder.htm';
		}else{
			$url = '/api/buyGoodOrder.htm';
			$param['account'] = $input[0];
			if(count($input)>1){
				$good_info = $this->goods_info($goods_id.'|0');
				if(is_array($good_info) && count($good_info['template'])>0){
					$i=0;
					foreach($good_info['template'] as $inputname){
						$param['name'.$i] = $inputname;
						$param['val'.$i] = $input[$i+1];
					}
				}
			}
		}

		$param['sign'] = md5($this->config['password'] . $param['userNo'] . $goods_id . $num);
		$data = $this->get_curl($url, http_build_query($param));
		$json = json_decode($data,true);
		if(isset($json['code']) && $json['code']==1000){
			$result = array(
				'code' => 0,
				'id' => $json['id']
			);
			if(!empty($json['cards'])){
				$kmdata = [];
				foreach($json['cards'] as $kmrow){
					if(!empty($kmrow['number']) && !empty($kmrow['pwd'])){
						$kmdata[] = ['card'=>$kmrow['number'], 'pass'=>$kmrow['pwd']];
					}elseif(empty($kmrow['pwd'])){
						$kmdata[] = ['card'=>$kmrow['number']];
					}else{
						$kmdata[] = ['card'=>$kmrow['pwd']];
					}
				}
				$result['faka']=true;
				$result['kmdata']=$kmdata;
			}
		}elseif(isset($json['msg'])){
			$result['message'] = $json['msg'];
		}else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function class_list(){
		$url = '/api/getDirs.htm';
		$param = ['userNo'=>$this->config['username']];
		$param['sign'] = md5($this->config['password'] . $param['userNo']);
		$ret = $this->get_curl($url, http_build_query($param));
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} else if ($ret['code'] != 1000) {
			return $ret['msg'];
		} else {
			$list = array();
			if($ret['type'] == 2){
				foreach ($ret['data2'] as $dir) {
					foreach ($dir['data'] as $v) {
						$list[] = array(
							'id' => $v['id'],
							'name' => $v['name']
						);
					}
				}
			}else{
				foreach ($ret['data1'] as $v) {
					$list[] = array(
						'id' => $v['id'],
						'name' => $v['name']
					);
				}
			}
			return $list;
		}
	}

	public function goods_list($classid){
		$url = '/api/getGoods.htm';
		$param = ['userNo'=>$this->config['username'], 'type'=>'1', 'word'=>$classid];
		$param['sign'] = md5($this->config['password'] . $param['userNo'] . $param['type']);
		$ret = $this->get_curl($url, http_build_query($param));
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} else if ($ret['code'] != 1000) {
			return $ret['msg'];
		} else {
			$list = array();
			foreach ($ret['data'] as $v) {
				$list[] = array(
					'id' => $v['id'],
					'keyid' => $v['keyId'],
					'name' => $v['name'],
					'type' => $v['type'] == 1 ? 1 : 0,
					'price' => $v['money']
				);
			}
			return $list;
		}
	}

	public function goods_info($goods_id){
		$id_arr = explode('|',$goods_id);
		$goods_id = $id_arr[0];
		$keyId = $id_arr[1];
		$url = '/api/getGood.htm';
		$param = ['userNo'=>$this->config['username'], 'id'=>$goods_id, 'keyId'=>$keyId];
		$param['sign'] = md5($this->config['password'] . $param['userNo'] . $goods_id);
		$data = $this->get_curl($url, http_build_query($param));
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] == 1000) {
			$data = $ret['data'][0];
			$return = [];
			$return['id'] = $data['id'];
			$return['mainKey'] = $data['mainKey'];
			$return['name'] = $data['name'];
			$return['type'] = $data['type'] == 1 ? 1 : 0;
			$return['price'] = $data['money'];
			$return['min'] = $data['min'];
			$return['max'] = $data['max'];
			$return['note'] = $data['note'];
			$return['desc'] = $data['desc'];
			$return['stock'] = $data['count'];
			$return['img'] = $data['imgs'][0]['img'];
			$return['input'] = $data['accountName'];
			$inputs = '';
			$template = [];
			foreach($data['templates'] as $row){
				if($row['type'] == 3 || $row['type'] == 4){
					$inputs .= $row['name'].'{'.$row['content'].'}|';
				}else{
					$inputs .= $row['name'].'|';
				}
				$template[] = $row['name'];
			}
			$return['inputs'] = trim($inputs,'|');
			$return['template'] = $template;
			return $return;
		} else {
			return $ret['msg'];
		}
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$url = '/api/getOrder.htm';
		$order_stats = [0=>'待支付', 1=>'待处理', 2=>'正在处理', 3=>'充值成功', 4=>'充值失败', 5=>'已退款', 6=>'订单异常', 7=>'待同步', 8=>'退单中', 9=>'退款中'];
		$param = ['userNo'=>$this->config['username'], 'id'=>$orderid];
		$param['sign'] = md5($this->config['password'] . $param['userNo'] . $param['id']);
		$data = $this->get_curl($url, http_build_query($param));
		if (!$ret = json_decode($data, true)) {
			return false;
		} elseif ($ret['code'] == 1000) {
			$data = $ret['data'][0];
			$return = ['订单状态'=>$order_stats[$data['ostatus']]];
			if(!empty($ret['retinfo']))$return['充值信息'] = $data['retinfo'];
			if(!empty($ret['recurl']))$return['充值网址'] = $data['recurl'];
			if($data['startCount']>0 || $data['nowCount']>0){
				$return['开始数量'] = $data['startCount'];
				$return['当前数量'] = $data['nowCount'];
				$return['结束数量'] = $data['endCount'];
			}
			if(isset($data['cards']) && count($data['cards'])>0){
				$kmdata = '';
				foreach($data['cards'] as $kmrow){
					if(!empty($kmrow['number']) && !empty($kmrow['pwd'])){
						$kmdata.='卡号：'.$kmrow['number'].' 密码：'.$kmrow['pwd'].'<br/>';
					}elseif(empty($kmrow['number'])){
						$kmdata.=$kmrow['pwd'].'<br/>';
					}else{
						$kmdata.=$kmrow['number'].'<br/>';
					}
				}
				$return['卡密信息'] = $kmdata;
			}
			return $return;
		} else{
			return $ret['info'];
		}
	}

	public function pricejk_one($tool){
		global $DB,$conf;
		$success=0;
		$details = $this->goods_info($tool['goods_id']);
		if(is_array($details)){
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ={$tool['shequ']} AND goods_id={$tool['goods_id']}");
			while($res2 = $rs2->fetch())
			{
				if($res2['price']==='0.00')continue;
				$price = ceil($details['price'] * 100)/100;
				if($conf['pricejk_edit']==1 && $price>$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price`='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}elseif($conf['pricejk_edit']==0 && $price!=$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price`='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}
				if($details['type']==1){
					$DB->exec("update `pre_tools` set `stock`='{$details['stock']}' where `tid`='{$res2['tid']}'");
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
		for($i=0;$i<10;$i++){
			$tool=$DB->getRow("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']}) AND uptime<'".(time()-$pricejk_time)."' ORDER BY uptime ASC");
			if(!$tool)break;
			$count = $this->pricejk_one($tool);
			$success+=$count;
		}
		return true;
	}

	private function get_curl($path,$post=0){
		$url = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] . $path;
		return shequ_get_curl($url,$post);
	}
}