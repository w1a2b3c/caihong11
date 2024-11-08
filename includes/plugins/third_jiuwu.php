<?php
namespace plugins;

class third_jiuwu{

	private $config = [];

	static public $info = [
		'name'        => 'third_jiuwu',
		'type'        => 'third',
		'title'       => '玖伍社区',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 10,
		'showedit'    => false,
		'showip'      => false,
		'batchgoods'    => true,
		'pricejk'     => 1,
		'input' => [
			'url' => '网站域名',
			'username' => '登录账号',
			'password' => '登录密码',
			'paypwd' => false,
			'paytype' => '支付方式',
		],
	];

	public function __construct($config)
	{
		$this->config = $config;
	}

	public function do_goods($goods_id, $goods_type, $goods_param, $num = 1, $input = array(), $money, $tradeno, $inputsname)
	{
		$goods_param=explode('|',$goods_param);
		$i=0;
		$inputdata = [];
		foreach($input as $val){
			if($val!=''){
				$inputdata[$goods_param[$i]]=$val;
				$i++;
			}
		}

		$result['code'] = -1;
		$path = '/index.php?m=home&c=order&a=add';
		$param = [
			'Api_UserName' => $this->config['username'],
			'Api_UserMd5Pass' => md5($this->config['password']),
			'goods_id' => $goods_id,
			'goods_type' => $goods_type,
			'need_num_0' => $num,
		];
		if($this->config['paytype']==1) $param['pay_type'] = '1';
		foreach ($inputdata as $key=>$val){
			$param[$key] = $val;
		}
		$data = $this->get_curl($path, http_build_query($param));
		$arr = json_decode($data,true);
		if (isset($arr['order_id'])) {
			$result = array(
				'code' => 0,
				'id' => $arr['order_id']
			);
		} elseif(isset($arr['info'])){
			$result['message'] = $arr['info'];
		} elseif(preg_match('/<p\sclass="error">(.*?)<\/p>/', $data,$msg)){
			$result['message'] = $msg[1];
		} else {
			$result['message'] = $data;
		}
		return $result;
	}

	private function login(){
		$get = $this->get_curl('/index.php?m=Home&c=User&a=login', 'username='.urlencode($this->config['username']).'&username_password='.urlencode($this->config['password']), 0, 0, 1);
		if (strpos($get, "登录成功")) {
			if (preg_match_all('/Set-Cookie:\s?([A-Za-z0-9\_=\|]+);/is', $get, $arr2)) {
				$cookie = null;
				foreach ($arr2['1'] as $item) {
					$cookie .= $item . ';';
				}
				$cookie_s = base64_encode($cookie);
				$_SESSION['api_cookie']=$cookie_s;
				return $cookie;
			}
		}
		return false;
	}
	
	public function goods_list_old(){
		$url = '/index.php?m=home&c=api&a=get_goods_lists';
		$ret = $this->get_curl($url);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} elseif ($ret['status'] !== 1) {
			return $ret['message'];
		} else {
			if (!$cookie = $this->login()) {
				return '账号或者密码错误';
			} else {
				$cookie = base64_encode($cookie);
				$_SESSION['api_cookie']=$cookie;
				$list = array();
				foreach ($ret['goods_rows'] as $v) {
					$shopimg = '';
					if($v['thumb']){
						$date = getSubstr($v['thumb'],'/image/','/');
						if($date<'2020-08-01')$shopimg = 'https://all-pt-upyun-cdn.95at.cn'.$v['thumb'];
						else $shopimg = 'http://'.$this->config['url'].$v['thumb'];
					}
					$list[] = array(
						'id' => $v['id'],
						'type' => $v['goods_type'],
						'name' => $v['title'],
						'shopimg' => $shopimg,
						'minnum' => $v['minbuynum_0'],
						'maxnum' => $v['maxbuynum_0']
					);
				}
				return $list;
			}
		}
	}

	public function goods_list(){
		$url = '/index.php?m=home&c=api&a=user_get_goods_lists_details&Api_UserName='.urlencode($this->config['username']).'&Api_UserMd5Pass='.md5($this->config['password']);
		$ret = $this->get_curl($url);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} elseif ($ret['status'] !== true) {
			return $ret['msg'];
		} else {
			$list = array();
			foreach ($ret['user_goods_lists_details'] as $v) {
				$shopimg = '';
				if($v['thumb']){
					$date = getSubstr($v['thumb'],'/image/','/');
					if($date<'2020-08-01')$shopimg = 'https://all-pt-upyun-cdn.95at.cn'.$v['thumb'];
					else $shopimg = 'http://'.$this->config['url'].$v['thumb'];
				}
				$list[] = array(
					'id' => $v['id'],
					'type' => $v['goods_type'],
					'name' => $v['title'],
					'shopimg' => $shopimg,
					'minnum' => $v['minbuynum_0'],
					'maxnum' => $v['maxbuynum_0'],
					'price' => $v['user_unitprice'],
					'close' => $v['goods_status']
				);
			}
			return $list;
		}
	}

	public function goods_info($goods_id){
		$result['code'] = -1;
		$cookie = isset($_SESSION['api_cookie']) ? base64_decode($_SESSION['api_cookie']) : null;
		if (!$cookie) {
			if(!$cookie = $this->login()){
				return '账号或者密码错误';
			}
		}
		$data = $this->get_curl('/index.php?m=Home&c=Goods&a=detail&id='.$goods_id, 0, 0, $cookie);
		if(strpos($data, '帐号登录')){
			if($cookie = $this->login()){
				$data = $this->get_curl('/index.php?m=Home&c=Goods&a=detail&id='.$goods_id, 0, 0, $cookie);
			}else{
				return '账号或者密码错误';
			}
		}
		$start = strpos($data, 'action="/index.php?m=home&c=order');
		$end = strpos($data, 'name="pay_type');
		if ($start > 1 && $end > 1) {
			$get = substr($data, $start, $end - $start);
			if (preg_match_all('/>([a-zA-Z0-9\\_\\-\\x{4e00}-\\x{9fa5}]+)：(<\\/span>|<\\/span>\\s|<\\/span>\\t)<(input|textarea|select) name="([A-Za-z0-9\\_\\-]+)/isu', $get, $arr)) {
				$param = "";
				foreach ($arr[4] as $k => $item) {
					if ($item == 'need_num_0' || $item == 'goods_id' || $item == 'goods_type' || $item == 'ssnr' || $item == 'qmkg_url' || $item == 'kszp_url' || $item == 'kszy_url' || $item == 'kszp_dwz')continue;
					$param .= $item.'|';
				}
				$param = trim($param, '|');
				$inputs = "";
				foreach ($arr[1] as $k => $item) {
					if ($k == 0) {
						$input = $item;
					} else {
						if($item=='QQ空间说说ID')$item='说说ID';
						$inputs .= $item.'|';
					}
				}
				$inputs = trim($inputs, '|');
				preg_match('/现金单价：<\/span><span  title=".*?">(.*?)<\/span>/',$data,$match);
				$data = str_replace(array("\n", "\n", "\r", "\t"), '', $data);
				preg_match('/<div class="col-md-12 banner">(.*?)<\/div>/', $data, $match2);
				$result = array(
					'code' => 0,
					'message' => 'succ',
					'price' => $match[1],
					'goods_param' => $param,
					'input' => $input,
					'inputs' => $inputs,
					'desc' => $match2[1]
				);
			} else {
				$result['code'] = -1;
				$result['msg'] = '匹配商品POST数据失败';
			}
		} else {
			$result['code'] = -1;
			$result['msg'] = '获取商品POST数据失败';
		}
		return $result;
	}
	
	public function query_order($orderid, $goodsid, $value = []){
		$order_state = array('未开始','未开始','进行中','已完成','已退单','退单中','续费中','补单中','改密中','登录失败');
		$url = '/index.php?m=Home&c=Order&a=query_orders_detail';
		$param = [
			'Api_UserName' => $this->config['username'],
			'Api_UserMd5Pass' => md5($this->config['password']),
			'return_fields' => 'id,need_num_0,start_num,end_num,now_num,order_state,login_state,start_time,end_time,add_time',
			'orders_id' => $orderid
		];
		$data = $this->get_curl($url, http_build_query($param));
		if (!$arr = json_decode($data, true)) {
			return false;
		} elseif ($arr['status'] == true) {
			$v=$arr['rows'][0];
			return array('num'=>$v['need_num_0'],'start_num'=>$v['start_num'],'now_num'=>$v['now_num'],'end_num'=>$v['end_num'],'add_time'=>$v['add_time'],'end_time'=>$v['end_time'],'order_state'=>$order_state[$v['order_state']]);
		} else{
			return $arr['info'];
		}
	}

	public function pricejk($shequid,&$success){
		global $DB,$conf;
		$list = $this->goods_list();
		if(is_array($list)){
			$price_arr = array();
			$goods_status_arr = array();
			foreach($list as $row){
				$price_arr[$row['id']] = $row['price'];
				$goods_status_arr[$row['id']] = $row['close']; //商品状态 1为禁止下单
			}
			$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']})");
			while($res2 = $rs2->fetch())
			{
				if($res2['price']==='0.00')continue;
				if(isset($price_arr[$res2['goods_id']]) && $price_arr[$res2['goods_id']]>0 && $res2['prid']>0){
					$price = ceil($price_arr[$res2['goods_id']] * $res2['value'] * 100)/100;
					if($conf['pricejk_edit']==1 && $price>$res2['price']){
						$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					}elseif($conf['pricejk_edit']==0 && $price!=$res2['price']){
						$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
						$success++;
					}
				}
				if(isset($goods_status_arr[$res2['goods_id']])){
					if($goods_status_arr[$res2['goods_id']]==1 && $res2['close']==0){
						$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
					}elseif($goods_status_arr[$res2['goods_id']]==0 && $res2['close']==1){
						$DB->exec("update `pre_tools` set `close`=0 where `tid`='{$res2['tid']}'");
					}
				}else{
					$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
				}
			}
			return true;
		}else{
			return $list;
		}
	}

	public function batch_goods_list(){
		$url = '/index.php?m=home&c=api&a=user_get_goods_lists_details&Api_UserName='.urlencode($this->config['username']).'&Api_UserMd5Pass='.md5($this->config['password']);
		$ret = $this->get_curl($url);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} elseif ($ret['status'] !== true) {
			return $ret['msg'];
		} else {
			$list = array();
			foreach ($ret['user_goods_lists_details'] as $v) {
				$shopimg = '';
				if($v['thumb']){
					$date = getSubstr($v['thumb'],'/image/','/');
					if($date<'2020-08-01')$shopimg = 'https://all-pt-upyun-cdn.95at.cn'.$v['thumb'];
					else $shopimg = 'http://'.$this->config['url'].$v['thumb'];
				}
				$list[] = array(
					'goods_id' => $v['id'],
					'goods_type' => $v['goods_type'],
					'name' => $v['title'],
					'shopimg' => $shopimg,
					'min' => $v['minbuynum_0'],
					'max' => $v['maxbuynum_0'],
					'price' => $v['user_unitprice']
				);
			}
			return $list;
		}
	}

	private function get_curl($path,$post=0,$referer=0,$cookie=0,$header=0,$addheader=0){
		$url = ($this->config['protocol']==1?'https://':'http://') . $this->config['url'] . $path;
		return shequ_get_curl($url,$post,$referer,$cookie,$header,$addheader);
	}
}