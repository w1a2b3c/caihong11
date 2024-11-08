<?php
namespace lib;

class MessageSend {
	//新工单提醒
	static public function workorder_new($id, $username, $uid, $type, $content) {
		global $date,$conf;
		$title=mb_substr($content, 0, 16, 'utf-8');
		$sub = '工单提醒：用户'.$username.'提交'.$type.'类型工单';
		if($conf['message_type'] == 1){
			$msg = '**'.$username.'** （UID:'.$uid.'）于 '.$date.' 提交工单，请及时进入网站后台工单列表处理。'."\n\n".'**工单编号：** '.$id."\n\n".'**问题类型：** '.$type."\n\n".'**工单标题：** '.$title."\n\n".'**工单内容：** '.$content."\n\n".'**来源网站：** '.$_SERVER['HTTP_HOST']."\n\n".'**发送时间：** '.$date;
			send_wechat($sub, $msg);
		}else{
			$msg = '<b>'.$username.'</b>（UID:'.$uid.'）于 '.$date.' 提交工单，请及时进入网站后台工单列表处理。<br/><b>工单编号：</b>'.$id.'<br/><b>问题类型：</b>'.$type.'<br/><b>工单标题：</b>'.$title.'<br/><b>工单内容：</b>'.$content.'<br/>----------<br/><b>来源网站：</b>'.$_SERVER['HTTP_HOST'].'<br/><b>发送时间：</b>'.$date;
			$mail_name = $conf['mail_recv']?$conf['mail_recv']:$conf['mail_name'];
			send_mail($mail_name,$sub,$msg);
		}
	}

	//回复工单提醒
	static public function workorder_reply($id, $username, $uid, $type, $content) {
		global $date,$conf;
		$sub = '工单提醒：用户'.$username.'回复了编号为'.$id.'的工单';
		if($conf['message_type'] == 1){
			$msg = '**'.$username.'** （UID:'.$uid.'）于 '.$date.' 回复了编号为'.$id.'工单，请及时进入网站后台工单列表处理。'."\n\n".'**工单编号：** '.$id."\n\n".'**问题类型：** '.$type."\n\n".'**回复内容：** '.$content."\n\n".'**来源网站：** '.$_SERVER['HTTP_HOST']."\n\n".'**发送时间：** '.$date;
			send_wechat($sub, $msg);
		}else{
			$msg = '<b>'.$username.'</b>（UID:'.$uid.'）于 '.$date.' 回复了编号为'.$id.'工单，请及时进入网站后台工单列表处理。<br/><b>工单编号：</b>'.$id.'<br/><b>问题类型：</b>'.$type.'<br/><b>回复内容：</b>'.$content.'<br/>----------<br/><b>来源网站：</b>'.$_SERVER['HTTP_HOST'].'<br/><b>发送时间：</b>'.$date;
			$mail_name = $conf['mail_recv']?$conf['mail_recv']:$conf['mail_name'];
			send_mail($mail_name,$sub,$msg);
		}
	}

	//商品下单提醒
	static public function orderbuy($name, $inputname, $inputnames, $array, $money, $num, $paytype, $status) {
		global $date,$conf;
		$input=$inputname?$inputname:'下单账号';
		$inputs=explode('|',$inputnames);
		$inputsdata='  '.$input.'：'.$array[0];
		$i=1;
		foreach($inputs as $input){
			if(!$input)continue;
			if(strpos($input,'{')!==false && strpos($input,'}')!==false){
				$input = substr($input,0,strpos($input,'{'));
			}
			if(strpos($input,'[')!==false && strpos($input,']')!==false){
				$input = substr($input,0,strpos($input,'['));
			}
			$inputsdata.="\n\n  ".$input.'：'.$array[$i];
			$i++;
		}
		$namelite=$name;
		if(mb_strlen($namelite, 'utf-8')>16)$namelite=mb_substr($namelite, 0, 16, 'utf-8').'...';
		$status_arr=['待处理','已完成','正在处理','异常','已退单'];
		$statusname=$status_arr[$status];
		$sub = '新订单提醒：'.$num.'份'.$namelite;
		if($conf['message_type'] == 1){
			$msg = '**商品名称：** '.$name."\n\n".'**下单信息↓** '."\n\n".$inputsdata."\n\n".'**订单金额：** '.$money." 元\n\n".'**支付方式：** '.$paytype."\n\n".'**下单份数：** '.$num."\n\n".'**订单状态：** '.$statusname."\n\n".'**来源网站：** '.$_SERVER['HTTP_HOST']."\n\n".'**发送时间：** '.$date;
			send_wechat($sub, $msg);
		}else{
			$msg = '<b>商品名称：</b>'.$name.'<br/><b>下单信息↓</b>'.str_replace("\n\n",'<br/>',$inputsdata).'<br/><b>订单金额：</b>'.$money.' 元<br/><b>支付方式：</b>'.$paytype.'<br/><b>下单份数：</b>'.$num.'<br/><b>订单状态：</b>'.$statusname.'<br/>----------<br/><b>来源网站：</b>'.$_SERVER['HTTP_HOST'].'<br/><b>发送时间：</b>'.$date;
			$mail_name = $conf['mail_recv']?$conf['mail_recv']:$conf['mail_name'];
			send_mail($mail_name,$sub,$msg);
		}
	}

	//对接失败提醒
	static public function orderbuy_fail($name, $inputname, $inputnames, $array, $money, $num, $paytype, $status, $param, $result) {
		global $date,$conf;
		$input=$inputname?$inputname:'下单账号';
		$inputs=explode('|',$inputnames);
		$inputsdata='  '.$input.'：'.$array[0];
		$i=1;
		foreach($inputs as $input){
			if(!$input)continue;
			if(strpos($input,'{')!==false && strpos($input,'}')!==false){
				$input = substr($input,0,strpos($input,'{'));
			}
			if(strpos($input,'[')!==false && strpos($input,']')!==false){
				$input = substr($input,0,strpos($input,'['));
			}
			$inputsdata.="\n\n  ".$input.'：'.$array[$i];
			$i++;
		}
		$namelite=$name;
		if(mb_strlen($namelite, 'utf-8')>10)$namelite=mb_substr($namelite, 0, 10, 'utf-8').'...';
		$status_arr=['待处理','已完成','正在处理','异常','已退单'];
		$statusname=$status_arr[$status];
		$sub = $namelite.' 对接失败提醒';
		if($conf['message_type'] == 1){
			$msg = '**商品名称：** '.$name."\n\n".'**下单信息↓** '."\n\n".$inputsdata."\n\n".'**订单金额：** '.$money." 元\n\n".'**支付方式：** '.$paytype."\n\n".'**下单份数：** '.$num."\n\n".'**订单状态：** '.$statusname."\n\n".'**对接提交参数：** '.$param."\n\n".'**对接返回结果：** '.htmlspecialchars($result)."\n\n".'**来源网站：** '.$_SERVER['HTTP_HOST']."\n\n".'**发送时间：** '.$date;
			send_wechat($sub, $msg);
		}else{
			$msg = '<b>商品名称：</b>'.$name.'<br/><b>下单信息↓</b>'.str_replace("\n\n",'<br/>',$inputsdata).'<br/><b>订单金额：</b>'.$money.' 元<br/><b>支付方式：</b>'.$paytype.'<br/><b>下单份数：</b>'.$num.'<br/><b>订单状态：</b>'.$statusname.'<br/><b>对接提交参数：</b><font color="blue">'.$param.'</font><br/><b>对接返回结果：</b><font color="red">'.htmlspecialchars($result).'</font><br/>----------<br/><b>来源网站：</b>'.$_SERVER['HTTP_HOST'].'<br/><b>发送时间：</b>'.$date;
			$mail_name = $conf['mail_recv']?$conf['mail_recv']:$conf['mail_name'];
			send_mail($mail_name,$sub,$msg);
		}
	}
}
