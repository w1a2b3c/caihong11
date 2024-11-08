<?php
namespace plugins;

class third_kayisu{

	private $config = [];

	static public $info = [
		'name'        => 'third_kayisu',
		'type'        => 'third',
		'title'       => '卡易速',
		'author'      => '安享',
		'version'     => '1.0',
		'link'        => '',
		'sort'        => 14,
		'showedit'    => false,
		'showip'      => false,
		'input' => [
			'url' => '网站域名',
			'username' => '登录账号',
			'password' => '登录密码',
			'paypwd' => '支付密码',
			'paytype' => false,
		],
	];

	public function __construct($config)
	{
		$this->config = $config;
	}

	private function login(){
		$url = 'http://'.$this->config['url'].'/user/login';
		$param = [
			'username' => $this->config['username'],
			'password' => $this->config['password']
		];
		$post = http_build_query($param);
		$data1 = $this->get_curl($url,$post,$url,0,1,['X-Requested-With: XMLHttpRequest']);
		$data2 = "{" . getSubstr($data1, "{", "}") . "}";
		$json = json_decode($data2,true);
		if ($json['status'] == 1)
		{
			$cookies='';
			preg_match_all('/Set-Cookie: (.*);/iU',$data1,$matchs);
			foreach ($matchs[1] as $val) {
				$cookies.=$val.'; ';
			}
			return $cookies;
		}else{
			return '登录失败：'.$json['info'];
		}
	}

	public function do_goods($goods_id, $type, $orderurl, $num = 1, $input = array(), $money, $tradeno, $inputsname)
	{
		global $CACHE;
		$result['code'] = -1;

		$cache_key = 'kayisu_'.substr(md5($this->config['url'].$this->config['username']),0,10);
		$cookies = $CACHE->read($cache_key);
		if(!$cookies){
			$cookies=$this->login();
			if(strpos($cookies,'失败')){
				$result['message'] = $cookies;
				return $result;
			}else{
				$CACHE->save($cache_key, $cookies);
			}
		}

		$url = 'http://'.$this->config['url'].'/goods/buy';
		$post = 'id='.$goods_id.'&quantity='.$num.'&mark=&trade_password='.$this->config['paypwd'];

		if ($type == 0 && is_array($input) && $input[0]){
			if($input[0])$post .= '&accountname='.urlencode($input[0]).'&reaccountname='.urlencode($input[0]);
			if($input[1])$post .= '&lblName0='.urlencode($input[1]);
			if($input[2])$post .= '&lblName1='.urlencode($input[2]);
			if($input[3])$post .= '&lblName2='.urlencode($input[3]);
			if($input[4])$post .= '&lblName3='.urlencode($input[4]);
		}

		$data = $this->get_curl($url, $post, $url, $cookies, 0, ['X-Requested-With: XMLHttpRequest']);
		if(!$data){
			$cookies=$this->login();
			if(strpos($cookies,'失败')){
				$result['message'] = $cookies;
				return $result;
			}else{
				$CACHE->save($cache_key, $cookies);
			}
			$data = $this->get_curl($url, $post, $url, $cookies, 0, ['X-Requested-With: XMLHttpRequest']);
		}
		$json = json_decode($data,true);
		if(isset($json['status']) && $json['status']==1){
			$id = getSubstr($json['url'], "/orderid/", ".html");
			$result = array(
				'code' => 0,
				'id' => $id
			);
			if($type==1){
				$data = $this->get_curl('http://'.$this->config['url'].'/order/info/id/'.$id.'/type/buyer',0,'http://'.$this->config['url'].'/buyer/order.html',$cookies);
				$table = getSubstr($data, "<tbody>", "</tbody>");
				$kmdata=[];
				if(preg_match_all('/<td class="card_no">(.*?)<\/td>.*?<td class="card_password">(.*?)<\/td>/s',$data,$matchs)){
					for($i=0;$i<count($matchs[1]);$i++){
						$card = $matchs[1][$i]?$matchs[1][$i]:$matchs[2][$i];
						$pass = $matchs[1][$i]?$matchs[2][$i]:null;
						$kmdata[] = ['card'=>$card, 'pass'=>$pass];
					}
				}elseif(preg_match_all('/<input type="hidden" name="passarr" value="卡号：(.*?)">/s',$data,$matchs)){
					for($i=0;$i<count($matchs[1]);$i++){
						$arr = explode('，卡密：', $matchs[1][$i]);
						$card = $arr[0];
						$pass = $arr[1];
						$kmdata[] = ['card'=>$card, 'pass'=>$pass];
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

	private function get_curl($url,$post=0,$referer=0,$cookie=0,$header=0,$addheader=0){
		return shequ_get_curl($url,$post,$referer,$cookie,$header,$addheader);
	}
}