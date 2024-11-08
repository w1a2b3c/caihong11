<?php
namespace plugins;

class third_yile{

	private $config = [];

	static public $info = [
		'name'        => 'third_yile',  //插件名称，必须和类名一致
		'type'        => 'third',  //插件类型，固定为third
		'title'       => '亿乐社区',  //插件显示名称
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 11,  //在对接列表显示的排序号
		'showedit'    => false,  //是否在编辑商品页面插入html
		'showip'      => true,  //是否显示加ip白名单提示
		'batchgoods'    => true,  //是否允许被一键上架商品
		'pricejk'     => 2,  //价格监控模式，2为可以下单时检查，1为直接监控批量更新
		'input' => [  //配置对接站点的输入框名称
			'url' => '网站域名',
			'username' => 'TokenID',
			'password' => '密匙',
			'paypwd' => false,
			'paytype' => false,
		],
	];

	public function __construct($config)
	{
		$this->config = $config;
	}

	/**
     * 提交到对接网站
	 * @param int $goods_id 商品ID
	 * @param int $goods_type 类型ID
	 * @param string $goods_param 参数名
	 * @param int $num 下单数量（下单份数×默认数量信息）
	 * @param array $input 下单输入框内容
	 * @param string $money 订单金额
	 * @param string $tradeno 支付订单号
	 * @param string $inputsname 商品其他输入框标题
     * @return array 返回信息（code=0成功，-1失败，message是提示信息）
     */
	public function do_goods($goods_id, $goods_type, $goods_param, $num = 1, $input = array(), $money, $tradeno, $inputsname)
	{
		$result['code'] = -1;
		$url = '/api/order';
		$param = array('api_token'=>$this->config['username'], 'timestamp'=>time(), 'gid'=>$goods_id, 'num'=>$num);
		if (is_array($input) && $input){
			$i=1;
			foreach ($input as $val){
				$param['value'.$i]=$val;
				$i++;
			}
		}
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$data = $this->get_curl($url,$post);
		$json = json_decode($data,true);
		if (isset($json['status']) && $json['status']==0) {
			$result = array(
				'code' => 0,
				'id' => $json['id']
			);
			if(strpos($json['message'],'购买卡密')!==false){
				$result['faka'] = true;
				$result['kmdata'] = $json['data'];
			}
		} elseif(isset($json['message'])){
			$result['message'] = $json['message'];
		} else{
			$result['message'] = $data;
		}
		return $result;
	}

	/**
     * 商品列表
     * @return array
     */
	public function goods_list(){
		$url = '/api/goods/list';
		$param = array('api_token'=>$this->config['username'], 'timestamp'=>time());
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$ret = $this->get_curl($url,$post);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} elseif ($ret['status'] !== 0) {
			return $ret['message'];
		} else {
			$list = array();
			foreach ($ret['data'] as $v) {
				$list[] = array(
					'id' => $v['gid'],
					'name' => $v['name'],
					'close' => $v['close']
				);
			}
			return $list;
		}
	}

	/**
     * 商品详情
	 * @param int $goods_id 商品ID
     * @return array
     */
	public function goods_info($goods_id){
		$url = '/api/goods/info';
		$param = array('api_token'=>$this->config['username'], 'timestamp'=>time(), 'gid'=>$goods_id);
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$ret = $this->get_curl($url,$post);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} elseif ($ret['status'] !== 0) {
			return $ret['message'];
		} else {
			$result = $ret['data'];
			$paramname = '';
			foreach($result['inputs'] as $v){
				$paramname.=$v[0].'|';
			}
			$result['paramname'] = trim($paramname, '|');
			return $result;
		}
	}
	
	/**
     * 订单查询
	 * @param int $orderid 订单ID
	 * @param int $goodsid 商品ID
	 * @param array $value 下单输入框内容
     * @return array
     */
	public function query_order($orderid, $goodsid, $value = []){
		$order_state = array(0=>'等待中',1=>'进行中',2=>'退单中',3=>'已退单',4=>'异常中',5=>'补单中',6=>'已更新',90=>'已完成',91=>'已退单',92=>'已退款');
		$url = '/api/order/query';
		$param = array('api_token'=>$this->config['username'], 'timestamp'=>time(), 'id'=>$orderid);
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$ret = $this->get_curl($url,$post);
		if (!$ret = json_decode($ret, true)) {
			return false;
		} elseif ($ret['status'] !== 0) {
			return $ret['message'];
		} else {
			$v = $ret['data'];
			return array('num'=>$v['num'],'start_num'=>$v['start_num'],'now_num'=>$v['now_num'],'add_time'=>$v['created_at'],'order_state'=>$order_state[$v['status']]);
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
				$price = ceil($details['price'] * $res2['value'] * 100)/100;
				if($conf['pricejk_edit']==1 && $price>$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}elseif($conf['pricejk_edit']==0 && $price!=$res2['price'] && $res2['prid']>0){
					$DB->exec("update `pre_tools` set `price` ='{$price}' where `tid`='{$res2['tid']}'");
					$success++;
				}
				if($details['close']==1 && $res2['close']==0){
					$DB->exec("update `pre_tools` set `close`=1 where `tid`='{$res2['tid']}'");
				}elseif($details['close']==0 && $res2['close']==1){
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
					$goods_status_arr[$row['id']] = $row['close']; //商品状态 1为禁止下单
				}
				$rs2=$DB->query("SELECT * FROM pre_tools WHERE is_curl=2 AND shequ='{$shequid}' AND active=1 AND cid IN ({$conf['pricejk_cid']})");
				while($res2 = $rs2->fetch())
				{
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
	}

	public function batch_goods_list(){
		$url = '/api/goods/list';
		$param = array('api_token'=>$this->config['username'], 'timestamp'=>time());
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$ret = $this->get_curl($url,$post);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} elseif ($ret['status'] !== 0) {
			return $ret['message'];
		} else {
			$list = array();
			foreach ($ret['data'] as $v) {
				$list[] = array(
					'goods_id' => $v['gid'],
					'name' => $v['name']
				);
			}
			return $list;
		}
	}

	public function batch_goods_info($goods_id){
		$url = '/api/goods/info';
		$param = array('api_token'=>$this->config['username'], 'timestamp'=>time(), 'gid'=>$goods_id);
		$sign = $this->getSign($param, $this->config['password']);
		$param['sign'] = $sign;
		$post = http_build_query($param);
		$ret = $this->get_curl($url,$post);
		if (!$ret = json_decode($ret, true)) {
			return '打开对接网站失败';
		} elseif ($ret['status'] !== 0) {
			return $ret['message'];
		} else {
			$result = $ret['data'];
			$paramname = '';
			foreach($result['inputs'] as $v){
				$paramname.=$v[0].'|';
			}
			$paramname = trim($paramname, '|');
			$paramname_arr = explode('|', $paramname);
			foreach ($paramname_arr as $k => $item) {
				if ($k == 0) {
					$input = $item;
				} else {
					if($item=='QQ空间说说ID')$item='说说ID';
					$inputs .= $item.'|';
				}
			}
			$inputs = trim($inputs, '|');
			$result['input'] = $input;
			$result['inputs'] = $inputs;
			return $result;
		}
	}

	private function get_curl($path,$post=0,$referer=0,$cookie=0,$header=0,$addheader=0){
		$url = 'http://' . $this->config['url'] . '.api.yilesup.net' . $path;
		return get_curl($url,$post,$referer,$cookie,$header,0,0,$addheader);
	}

	private function getSign($param, $key)
	{
		$signPars = "";
		ksort($param);
		foreach ($param as $k => $v) {
			if ("sign" != $k && "" != $v) {
				$signPars .= $k . "=" . $v . "&";
			}
		}
		$signPars = trim($signPars, '&');
		$signPars .= $key;
		$sign = md5($signPars);
		return $sign;
	}
}