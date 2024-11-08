<?php
namespace plugins;

class third_yiqida{

	private $config = [];

	static public $info = [
		'name'        => 'third_yiqida',
		'type'        => 'third',
		'title'       => '亿奇达',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 23,
		'showedit'    => false,
		'showip'      => false,
		'pricejk'     => 2,
		'input' => [
			'url' => '网站域名',
			'username' => '登录账号',
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
		$template = [];
		foreach ($input as $val){
			$template[] = urlencode($val);
		}

		$callbackUrl = $siteurl.'other/notify.php/shequ/'.$this->config['id'].'/'.$tradeno.'/';
		$param = array('commodityId'=>$goods_id, 'external_orderno'=>$tradeno, 'buyCount'=>$num, 'callbackUrl'=>$callbackUrl, 'externalSellPrice'=>$money, 'template'=>$template);
		$post = json_encode($param);
		$url = '/api/UserOrder/CreateOrder';

		$data = $this->get_curl($url, $post);
		$json = json_decode($data,true);
		if(isset($json['code']) && $json['code']==200){
			$result = array(
				'code' => 0,
				'id' => $json['data']['orderNo']
			);
		}elseif(isset($json['msg'])){
			$result['message'] = $json['msg'];
		}else{
			$result['message'] = $data;
		}
		return $result;
	}

	public function goods_list($page = 1){
		$param = array('page'=>$page);
		$post = json_encode($param);
		$url = '/api/UserCommdity/GetCommodityList';
		$ret = $this->get_curl($url, $post);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} else if ($ret['code'] != 200) {
			return $ret['msg'];
		} else {
			$list = array();
			foreach ($ret['data'] as $v) {
				$list[] = array(
					'id' => $v['mainId'],
					'name' => $v['name'],
					'shopimg' => $v['MainImg'],
					'price' => $v['price']
				);
			}
			return $list;
		}
	}

	public function goods_info($goods_id){
		$param = array('id'=>$goods_id);
		$post = json_encode($param);
		$url = '/api/UserCommdity/GetCommodityInfo';
		$data = $this->get_curl($url, $post);
		if (!$ret = json_decode($data, true)) {
			return '打开对接网站失败';
		} elseif ($ret['code'] != 200) {
			return $ret['msg'];
		} else {
			$return['id'] = $ret['data']['mainId'];
			$return['mainid'] = $return['id'];
			$return['name'] = $ret['data']['name'];
			$return['img'] = $ret['data']['MainImg'];
			$return['price'] = $ret['data']['price'];
			$return['status'] = $ret['data']['status'];
			$return['isPreSale'] = $ret['data']['isPreSale'];
			$template = json_decode($ret['data']['template'], true);
			$return['input'] = $template[0]['txt'];
			$inputs = '';
			foreach($template as $row){
				if($return['input'] == $row['txt'])continue;
				if($row['type'] == 'select' || $row['type'] == 'radio'){
					$options = '';
					foreach($row['selectData'] as $option){
						$options .= $option['value'].':'.$option['label'].',';
					}
					$inputs .= $row['txt'].'{'.trim($options,',').'}|';
				}else{
					$inputs .= $row['txt'].'|';
				}
			}
			$return['inputs'] = trim($inputs,'|');
			return $return;
		}
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$order_state = array(0=>'等待中',2=>'等待发货，等待处理',3=>'已发货，物流派送中',4=>'已完成',5=>'异常',6=>'正在处理');
		$param = array('orderNo'=>$orderid);
		$post = json_encode($param);
		$url = '/api/UserOrder/QueryOrderModel';
		$data = $this->get_curl($url, $post);
		if (!$ret = json_decode($data, true)) {
			return false;
		} elseif ($ret['code'] != 200) {
			return $ret['msg'];
		} else{
			$v = $ret['data'];
			$result = array('订单状态'=>$order_state[$v['status']]);
			if($v['sellerMessage'][0]['message']){
				$result['卖家留言'] = $v['sellerMessage'][0]['message'];
			}
			if(!empty($v['carmi'])){
				$kmdata = '';
                foreach ($v['carmi'] as $kmrow) {
					$kmdata.=$kmrow.'<br/>';
                }
				$return['卡密信息'] = $kmdata;
			}
			return $result;
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
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}elseif($conf['pricejk_edit']==0 && $price!=$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
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
		$json = file_get_contents("php://input");
		if(md5($json.$this->config['password']) !== $_GET['sign']) exit('sign error');
		$arr = json_decode($json, true);
		if($arr){
			if($arr['externalOrderno'] == $order['tradeno']){
				if($order['status'] <= 2){
					if($arr['status']==4){
						$DB->exec("update pre_orders set status=1 where id='{$order['id']}'");
					}elseif($arr['status']==5){
						$DB->exec("update pre_orders set status=3 where id='{$order['id']}'");
					}
				}
			}
			echo 'ok';
		}else{
			echo 'data error';
		}
	}

	private function get_curl($path,$post=0){
		$timestamp = $this->getMillisecond();
		$sign = md5($timestamp.$post.$this->config['password']);
		$url = 'http://open.yiqida.cn'.$path.'?timestamp='.$timestamp.'&userName='.urlencode($this->config['username']).'&sign='.$sign;
		return shequ_get_curl($url,$post);
	}

	private function getMillisecond() {
		list($s1, $s2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($s1) + floatval($s2)) * 1000);
	}
}