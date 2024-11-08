<?php
namespace plugins;

class third_yunbao{

	private $config = [];

	static public $info = [
		'name'        => 'third_yunbao',
		'type'        => 'third',
		'title'       => '云宝发卡',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 26,
		'showedit'    => false,
		'showip'      => true,
		'pricejk'     => 1,
		'input' => [
			'url' => '网站域名',
			'username' => '登录账号',
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
		//$yibu = 'http://'.$_SERVER['HTTP_HOST'].'/notify.php';
		$result['code'] = -1;
		$token = md5('user='.$this->config['username'].'&&pass='.md5($this->config['password']).'');
		$url = '/home/api';
		$param = [
			'code' => $goods_type==0?'102':'101',
			'order' => $tradeno,
			'user' => $this->config['username'],
			'pass' => md5($this->config['password']),
			'spid' => $goods_id,
			'token' => $token,
			'mun' => $num,
			'money' => strval(round($money,2)+0.1),
			];
		if($goods_type==0){
			$i=0;
			$beizhu=[];
			foreach($input as $inpu){
				$beizhu[$i++] = $inpu;
			}
			$param['beizhu'] = base64_encode(json_encode($beizhu));
			$param['yibu'] = '1';
		}
		$data = $this->get_curl($url,http_build_query($param));
		$json = json_decode($data,true);
		if (isset($json['study']) && $json['study']==1) {
			$result = array(
				'code' => 0,
				'id' => $json['order']
			);
		} elseif(array_key_exists('code',$json)){
			$result['message'] = 'code:'.$json['code'].$json['text'];
		} else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_list(){
		$token = md5('user='.$this->config['username'].'&&pass='.md5($this->config['password']).'');
		$url = '/home/api';
		$param = [
			'code' => '1013',
			'user' => $this->config['username'],
			'pass' => md5($this->config['password']),
			'spid' => 1,
			'token' => $token
		];
		$ret = $this->get_curl($url, http_build_query($param));
		if ($ret = json_decode($ret, true)) {
			if(isset($ret['study']) && $ret['study']==1){
				$list = array();
				foreach ($ret['data'] as $v) {
					$list[] = array('id' => $v['id'], 'type' => $v['lei'], 'name' => $v['title'], 'price' => $v['money'], 'input' => $v['beizhutrue']=='1'?$v['beizhu']:'');
				}
				return $list;
			}else{
				return '[code:'.$ret['code'].'] '.$ret['text'];
			}
		} else {
			return '打开对接网站失败';
		}
	}

	public function goods_info($goods_id){
		$token = md5('user='.$this->config['username'].'&&pass='.md5($this->config['password']).'');
		$url = '/home/api';
		$param = [
			'code' => '1012',
			'user' => $this->config['username'],
			'pass' => md5($this->config['password']),
			'spid' => $goods_id,
			'token' => $token
		];
		$ret = $this->get_curl($url, http_build_query($param));
		if ($ret = json_decode($ret, true)) {
			if(isset($ret['study']) && $ret['study']==1){
				return array('id' => $ret['id'], 'type' => $ret['lei'], 'name' => $ret['title'], 'price' => $ret['money'], 'input' => $ret['beizhutrue']=='1'?$ret['beizhu']:'');
			}else{
				return '[code:'.$ret['code'].'] '.$ret['text'];
			}
		} else {
			return false;
		}
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$order_state = array(1=>'未付款',2=>'待处理',3=>'待处理',4=>'已完成',5=>'异常',6=>'已取消');
		$token = md5('user='.$this->config['username'].'&&pass='.md5($this->config['password']).'');
		$url = '/home/api';
		$param = [
			'code' => '1010',
			'user' => $this->config['username'],
			'pass' => md5($this->config['password']),
			'order' => $orderid,
			'token' => $token
		];
		$data = $this->get_curl($url, http_build_query($param));
		$arr = json_decode($data,true);
		if (isset($arr['study']) && $arr['study']==1) {
			$kami = [];
			$result = [];
			if($arr['stduy']>0)$result['订单状态'] = $order_state[$arr['stduy']];
			if(is_array($arr['kami'])){
				$kmdata = '';
				foreach($arr['kami'] as $row){
					$kmdata .= $row['code'].'<br/>';
				}
				$result['卡密'] = $kmdata;
				return $result;
			}
		}elseif(isset($arr['code'])){
			return '[code:'.$arr['code'].'] '.$arr['text'];
		}
		return false;
	}

	public function goods_stock($goods_id){
		$token = md5('user='.$this->config['username'].'&&pass='.md5($this->config['password']).'');
		$url = '/home/api';
		$param = [
			'code' => '1011',
			'user' => $this->config['username'],
			'pass' => md5($this->config['password']),
			'spid' => $goods_id,
			'token' => $token
		];
		$ret = $this->get_curl($url, http_build_query($param));
		if ($ret = json_decode($ret, true)) {
			if(isset($ret['study']) && $ret['study']==1){
				return $ret['kucun'];
			}else{
				return false;
			}
		} else {
			return false;
		}
	}

	public function pre_check($tool, $num){
		global $DB;
		$stock = $this->goods_stock($tool['goods_id']);
		if($stock!==false && $stock!==null && $stock!==$tool['stock']){
			$DB->exec("update `pre_tools` set `stock`='$stock',`uptime`='".time()."' where `tid`='{$tool['tid']}'");
			if($num>$stock){
				return ['code' => -1, 'msg'=>'当前商品库存不足，无法购买'];
			}
		}else{
			$DB->exec("update `pre_tools` set `uptime`='".time()."' where `tid`='{$tool['tid']}'");
		}
		return ['code' => 0];
	}

	public function stockjk_one($tool){
		global $DB;
		$stock = $this->goods_stock($tool['goods_id']);
		if($stock!==false && $stock!==null && $stock!==$tool['stock']){
			$DB->exec("update `pre_tools` set `stock`='$stock',`uptime`='".time()."' where `tid`='{$tool['tid']}'");
		}else{
			$DB->exec("update `pre_tools` set `uptime`='".time()."' where `tid`='{$tool['tid']}'");
		}
		return 1;
	}

	public function pricejk($shequid, &$success)
	{
		global $DB, $conf;
		//库存监控
		/*$pricejk_time = $conf['pricejk_time']?$conf['pricejk_time']:600;
		for($i=0;$i<10;$i++){
			$tool=$DB->getRow("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']}) AND uptime<'".(time()-$pricejk_time)."' AND goods_type=1 ORDER BY uptime ASC");
			if(!$tool)break;
			$count = $this->stockjk_one($shequ, $tool);
			$success+=$count;
		}
		return true;*/
		//价格监控
		$list = $this->goods_list();
		if (is_array($list)) {
			$price_arr = array();
			foreach ($list as $row) {
				$price_arr[$row['id']] = round($row['price'], 2);
			}
			$rs2 = $DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']})");
			while ($res2 = $rs2->fetch()) {
				if ($res2['price']==='0.00') continue;
				if (isset($price_arr[$res2['goods_id']]) && $price_arr[$res2['goods_id']] > 0 && $res2['prid']>0) {
					$price = $price_arr[$res2['goods_id']];
					if ($conf['pricejk_edit'] == 1 && $price > $res2['price']) {
						$DB->query("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					} elseif ($conf['pricejk_edit']==0 && $price != $res2['price']) {
						$DB->query("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					}
				}elseif (!isset($price_arr[$res2['goods_id']]) && $res2['close'] == 0){
					$DB->query("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
				}
			}
			return true;
		} else {
			return '获取商品列表失败';
		}
	}

	private function get_curl($path,$post=0,$referer=0,$cookie=0,$header=0,$addheader=0){
		$url = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] . $path;
		return shequ_get_curl($url,$post,$referer,$cookie,$header,$addheader);
	}
}