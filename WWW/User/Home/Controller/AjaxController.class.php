<?php
namespace Home\Controller;
use Home\Controller\Basis1Controller;
class AjaxController extends Basis1Controller{
	public function getmobilecode1(){
		$mobile=trim(I('post.mobile'));
		if(empty($mobile)){
			echo json_encode('请输入用户名');exit();
		}
		$mobilepreg='/^1[3|4|5|7|8][0-9]{9}$/';
		if (!preg_match($mobilepreg,$mobile)){
			echo json_encode('用户名不符合规范');exit();
		}
		$users=M('user');
		$rsu=$users->where(array('UE_account'=>$mobile))->find();
		if (!$rsu){
			echo json_encode('会员不存在');exit();
		}
		$qycode=rand(111111,999999);
		$apikey = "025f5fa3703af85bba852dd1cffb63c5"; //请用自己的apikey代替
		$text="【GEC科技】您正在找回密码，您的新密码是".$qycode;
		$url="http://yunpian.com/v1/sms/send.json";
		$encoded_text = urlencode("$text");
		$mobile = urlencode("$mobile");
		$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
		$info = parent::sock_post($url, $post_string);
		$infoary=explode(',',$info);
		if ($infoary[0]!='{"code":0'){
			echo json_encode('发送失败');exit();
		}
		unset($upary);
		$upary['pwd']=authcode($qycode);
		if($users->where(array('UE_ID'=>$rsu['uid']))->setField($upary)===false){
			echo json_encode('更新数据失败');exit();
		}
		echo json_encode('短信发送成功。请注意查收');exit();
	}
	public function testmobile(){
		$mobile=trim(I('post.mobile'));
		//echo json_encode($mobile);die();
		$obs=substr($mobile,0,3);
		if($obs==170 || $obs==171){
			echo json_encode('请输入正确的手机号');exit();
		}
		if(empty($mobile)){
			echo json_encode('请输入手机');exit();
		}
		$mobilepreg='/^1[3|4|5|7|8][0-9]{9}$/';
		if (!preg_match($mobilepreg,$mobile)){
			echo json_encode('手机不符合规范');exit();
		}
		$users=M('user');
		$rsu=$users->where(array('phone'=>array('eq',$mobile)))->find();
		if ($rsu){
			echo json_encode('手机已经存在');exit();
		}
		//是否发送过验证码了
		$smscode=M('smscode');
		$rsyz=$smscode->where(array('mobile'=>array('eq',$mobile)))->find();
		if ($rsyz){
			if((time()-$rsyz['sendtime'])<60){
				echo json_encode('请在60秒后再获取');exit();
			}
		}	
		//发送手机验证码
		$qycode=rand(111111,999999);
		$apikey = "025f5fa3703af85bba852dd1cffb63c5"; //请用自己的apikey代替
		$text="【GEC科技】您的验证码是{$qycode}";
		$url="http://yunpian.com/v1/sms/send.json";
		$encoded_text = urlencode("$text");
		$mobile = urlencode("$mobile");
		$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
		$info = parent::sock_post($url, $post_string);
		$infoary=explode(',',$info);
		if ($infoary[0]!='{"code":0'){
			echo json_encode('发送失败');exit();
		}
		if ($rsyz){
			if(false===$smscode->where(array('id'=>$rsyz['id']))->setField(array('regcode'=>$qycode,'sendtime'=>time(),'state'=>0,'edittime'=>time()+600))){
				echo json_encode('更新数据失败');exit();
			}
		}else{
			unset($data);
			$data['mobile']=$mobile;
			$data['regcode']=$qycode;
			$data['sendtime']=time();
			$data['state']=0;
			$data['edittime']=time()+600;
			if(!$smscode->add($data)){
				echo json_encode('更新数据失败');exit();
			}
		}
		echo json_encode('短信发送成功。请注意查收');exit();
	}
	public function xgzlmobile(){
		$mobile=trim(I('post.mobile'));
		//echo json_encode($mobile);die();
		$obs=substr($mobile,0,3);
		if($obs==170 || $obs==171){
			echo json_encode('请输入正确的手机号');exit();
		}
		if(empty($mobile)){
			echo json_encode('请输入手机');exit();
		}
		$mobilepreg='/^1[3|4|5|7|8][0-9]{9}$/';
		if (!preg_match($mobilepreg,$mobile)){
			echo json_encode('手机不符合规范');exit();
		}
		$users=M('user');
		$rsu=$users->where(array('phone'=>array('eq',$mobile)))->find();
		/* if ($rsu){
			echo json_encode('手机已经存在');exit();
		} */
		//是否发送过验证码了
		$smscode=M('smscode');
		$rsyz=$smscode->where(array('mobile'=>array('eq',$mobile)))->find();
		if ($rsyz){
			if((time()-$rsyz['sendtime'])<60){
				echo json_encode('请在60秒后再获取');exit();
			}
		}	
		//发送手机验证码
		$qycode=rand(111111,999999);
		$apikey = "18bc71626676dd01e0acd5bac5aef753"; //请用自己的apikey代替
		$text="【外包科技】您的验证码是{$qycode}。如非本人操作，请忽略本短信";
		//$url="http://yunpian.com/v1/sms/send.json";
		$url="https://sms.yunpian.com/v2/sms/single_send.json";
		
		$encoded_text = urlencode("$text");
		$mobile = urlencode("$mobile");
		$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
		$info = parent::sock_post($url, $post_string);
		$infoary=explode(',',$info);
		if ($infoary[0]!='{"code":0'){
			echo json_encode('发送失败');exit();
		}
		if ($rsyz){
			if(false===$smscode->where(array('id'=>$rsyz['id']))->setField(array('regcode'=>$qycode,'sendtime'=>time(),'state'=>0,'edittime'=>time()+600))){
				echo json_encode('更新数据失败');exit();
			}
		}else{
			unset($data);
			$data['mobile']=$mobile;
			$data['regcode']=$qycode;
			$data['sendtime']=time();
			$data['state']=0;
			$data['edittime']=time()+600;
			if(!$smscode->add($data)){
				echo json_encode('更新数据失败');exit();
			}
		}
		echo json_encode('短信发送成功。请注意查收');exit();
	}
	/* public function getmobilecode(){
		
		$mobile=trim(I('post.mobile'));
		//echo json_encode($mobile);die();
		$obs=substr($mobile,0,3);
		if($obs==170 || $obs==171){
			echo json_encode('请输入正确的手机号');exit();
		}
		if(empty($mobile)){
			echo json_encode('请输入手机');exit();
		}
		$mobilepreg='/^1[3|4|5|7|8][0-9]{9}$/';
		if (!preg_match($mobilepreg,$mobile)){
			echo json_encode('手机不符合规范');exit();
		}
		$users=M('user');
		$rsu=$users->where(array('phone'=>array('eq',$mobile)))->find();
		if ($rsu){
			echo json_encode('手机已经存在');exit();
		}
		//是否发送过验证码了
		$smscode=M('smscode');
		$rsyz=$smscode->where(array('mobile'=>$mobile))->find();
		if ($rsyz){
			if((time()-$rsyz['sendtime'])<60){
				echo json_encode('请在60秒后再获取');exit();
			}
		}	
		//发送手机验证码
		$qycode=rand(111111,999999);
		$apikey = ""; //请用自己的apikey代替
		$text="【GDK大亨】您的注册验证码是{$qycode}";
		$url="http://yunpian.com/v1/sms/send.json";
		$encoded_text = urlencode("$text");
		$mobile = urlencode("$mobile");
		$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
		$info = parent::sock_post($url, $post_string);
		$infoary=explode(',',$info);
		if ($infoary[0]!='{"code":0'){
			echo json_encode('发送失败');exit();
		}
		if ($rsyz){
			if(false===$smscode->where(array('id'=>$rsyz['id']))->setField(array('regcode'=>$qycode,'sendtime'=>time(),'state'=>0,'edittime'=>time()+600))){
				echo json_encode('更新数据失败');exit();
			}
		}else{
			unset($data);
			$data['mobile']=$mobile;
			$data['regcode']=$qycode;
			$data['sendtime']=time();
			$data['state']=0;
			$data['edittime']=time()+600;
			if(!$smscode->add($data)){
				echo json_encode('更新数据失败');exit();
			}
		}
		echo json_encode('短信发送成功。请注意查收');exit();
	} */
	public function getmobilecode3(){
		
		$mobile=trim(I('post.mobile'));
		if(empty($mobile)){
			echo json_encode('请输入手机');exit();
		}
		$mobilepreg='/^1[3|4|5|7|8][0-9]{9}$/';
		if (!preg_match($mobilepreg,$mobile)){
			echo json_encode('手机不符合规范');exit();
		}
		$users=M('user');
		/* $rsu=$users->where(array('phone'=>array('eq',$mobile)))->find();
		if ($rsu){
			echo json_encode('手机已经存在');exit();
		} */
		//是否发送过验证码了
		$smscode=M('smscode');
		$rsyz=$smscode->where(array('mobile'=>array('eq',$mobile)))->find();
		if ($rsyz){
			if((time()-$rsyz['sendtime'])<60){
				echo json_encode('请在60秒后再获取');exit();
			}
		}	
		//发送手机验证码
		$qycode=rand(111111,999999);
		$apikey = "025f5fa3703af85bba852dd1cffb63c5"; //请用自己的apikey代替
		$text="【GEC科技】您的验证码是{$qycode}";
		$url="http://yunpian.com/v1/sms/send.json";
		$encoded_text = urlencode("$text");
		$mobile = urlencode("$mobile");
		$post_string="apikey=$apikey&text=$encoded_text&mobile=$mobile";
		$info = parent::sock_post($url, $post_string);
		$infoary=explode(',',$info);
		if ($infoary[0]!='{"code":0'){
			echo json_encode('发送失败');exit();
		}
		if ($rsyz){
			if(false===$smscode->where(array('id'=>$rsyz['id']))->setField(array('regcode'=>$qycode,'sendtime'=>time(),'state'=>0,'edittime'=>time()+600))){
				echo json_encode('更新数据失败');exit();
			}
		}else{
			unset($data);
			$data['mobile']=$mobile;
			$data['regcode']=$qycode;
			$data['sendtime']=time();
			$data['state']=0;
			$data['edittime']=time()+600;
			if(!$smscode->add($data)){
				echo json_encode('更新数据失败');exit();
			}
		}
		echo json_encode('短信发送成功。请注意查收');exit();
	}
	
	
	public function parnum(){
		$news=M('newsinfo');
		$num=I('post.num');
		$nid=I('post.id');
		unset($uparr);
		$uparr['praisenumber']=array('exp','praisenumber+1');
		$rsup=$news->where(array('nid'=>$nid))->setField($uparr);
		if ($rsup) {
			echo json_encode("点赞成功");
		}else{
			echo json_encode("点赞失败");
		}
	}
public function getylor(){
		$order=M('order');
		$tq=C('DB_PREFIX');
		$liestr=$tq.'order.oid as oid,'.$tq.'order.ploss as ploss,'.$tq.'userinfo.nickname as nickname';
		unset($find);
		$find['ostate']=1;
		$find['isapply']=0;
		$orde=$order->join($tq.'userinfo on '.$tq.'order.uid='.$tq.'userinfo.uid','left')->where($find)->field($liestr)->order($tq.'order.oid desc')->limit(1)->find();
		$rs=$order->where(array('oid'=>$orde['oid']))->setField(array('isapply'=>1));
		echo  json_encode($orde);	
	}
	public function getnextree(){
		$userid = i('get.userid');
		//return $userid;
		$users = m('user');
		$rs = $users->where(array('UE_ID' => $userid))->find();
		$tjtreestr = '';

		if ($rs) {
			$list = $users->where(array('UE_accName' => $rs['ue_account']))->select();
			foreach ($list as $tkey => $tval ) {
				$tjtreestr .= '<div>';
				$xjcount = $users->where(array('UE_account' => $tval['ue_accname']))->field('UE_account')->count();

				if (0 < $xjcount) {
					$tjtreestr .= '<a href=\'#tree_' . $tval['ue_id'] . '\' onclick="findtree(' . $tval['ue_id'] . ',\'' . u('index.php/Home/ajax/getnextree') . '\')">';

					if ($tkey == count($list) - 1) {
						$tjtreestr .= '<img id=\'tree_' . $tval['ue_id'] . '\' src=\'/Public/images/tree_plusl.gif\' border=0>';
					}
					else {
						$tjtreestr .= '<img id=\'tree_' . $tval['ue_id'] . '\' src=\'/Public/images/tree_plus.gif\' border=0>';
					}
				}
				else if ($tkey == count($list) - 1) {
					$tjtreestr .= '<img id=\'tree_' . $tval['ue_id'] . '\' src=\'/Public/images/tree_blankl.gif\' border=0>';
				}
				else {
					$tjtreestr .= '<img id=\'tree_' . $tval['ue_id'] . '\' src=\'/Public/images/tree_blank.gif\' border=0>';
				}
				$tjtreestr .= '</a> ' . $tval['ue_truename']/* . '  - 人数：' .$zrs. '  - 算力：' .$zkjsl */;
				$tjtreestr .= '<div id="nextree_' . $tval['ue_id'] . '" style="margin-left:30px;display:none"></div>';
				$tjtreestr .= '</div>';
			}

			echo $tjtreestr;
		}
		else {
			echo '获取错误';
		}
	}
	public function engetnextree(){
		$userid = i('get.userid');
		//return $userid;
		$users = m('user');
		$rs = $users->where(array('UE_ID' => $userid))->find();
		$tjtreestr = '';

		if ($rs) {
			$list = $users->where(array('UE_accName' => $rs['ue_account']))->select();
			foreach ($list as $tkey => $tval ) {
				$tjtreestr .= '<div>';
				$xjcount = $users->where(array('UE_account' => $tval['ue_accname']))->field('UE_account')->count();

				if (0 < $xjcount) {
					$tjtreestr .= '<a href=\'#tree_' . $tval['ue_id'] . '\' onclick="findtree(' . $tval['ue_id'] . ',\'' . u('index.php/Home/ajax/engetnextree') . '\')">';

					if ($tkey == count($list) - 1) {
						$tjtreestr .= '<img id=\'tree_' . $tval['ue_id'] . '\' src=\'/Public/images/tree_plusl.gif\' border=0>';
					}
					else {
						$tjtreestr .= '<img id=\'tree_' . $tval['ue_id'] . '\' src=\'/Public/images/tree_plus.gif\' border=0>';
					}
				}
				else if ($tkey == count($list) - 1) {
					$tjtreestr .= '<img id=\'tree_' . $tval['ue_id'] . '\' src=\'/Public/images/tree_blankl.gif\' border=0>';
				}
				else {
					$tjtreestr .= '<img id=\'tree_' . $tval['ue_id'] . '\' src=\'/Public/images/tree_blank.gif\' border=0>';
				}

				/* xiajirenshus($tval['ue_account'],$renshu);
					$zrs=$renshu;
					//总矿机算力
					kjsl($tval['ue_account'],$zsl);
					$zkjsl=$zsl; */
				$tjtreestr .= '</a> ' . $tval['ue_truename']/* . '  - Guild number：' .$zrs. '  - Guild power：' .$zkjsl */;
				$tjtreestr .= '<div id="nextree_' . $tval['ue_id'] . '" style="margin-left:30px;display:none"></div>';
				$tjtreestr .= '</div>';
			}

			echo $tjtreestr;
		}
		else {
			echo '获取错误';
		}
	}
	
		public function getgp(){
		$time=date('Y-m-d 00:00:00',time());
		$time1=date('Y-m-d 23:59:59',time());
		$date=strtotime($time);
		$date1=strtotime($time1);
		
		$maps['date']=array(array('gt',$date),array('lt',$date1));
		$xngp=M("date");
		$rsxng=$xngp->where($maps)->field('date,price')->select();
		echo json_encode($rsxng);
	}
	//
	public function getgps(){
		$rsxngs=M('ridate')->order('id asc')->select();
		echo json_encode($rsxngs);
	}


}
?>