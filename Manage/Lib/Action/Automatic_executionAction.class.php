<?php
class Automatic_executionAction extends Action {
	//自动执行程序
	public function index(){
		$turn = I('turn');//累计投资送积分统计开关(切记仅执行一次)
		$M_loan = M('loan');//借款表
		$loan_info = $M_loan->field('id,expire_time')->where('deal_status = 4')->select(); //还款日
		foreach($loan_info as $val){
			if($val['expire_time'] <= time()){ //如果当前时间大于等于还款时间那么执行还款程序
				//执行还款程序 
				 return_invested($val['id']); //执行还款
		         $alert_data['title'] = '还款成功提醒';
		         $alert_data['content'] = '网站还款提醒:项目ID('.$val['id'].')，请前往后台查看。';
		         $alert_data['status'] = 1;
		         $alert_data['create_time'] = time();
		         insert_system_alert($alert_data);
		         send_sms('13904314687',$alert_data['content']);//手机提醒
		         send_mail('2956000@qq.com','金投资', iconv('UTF-8','GB2312',$alert_data['title']), $alert_data['content'], "HTML");//邮件提醒				
			}					
		}			

		/********** 累计投资奖励积分  2015-05-04 16:06:18  update_by:liying *************/			
		//执行开关参数若为on时执行给分(请勿多次执行)
		if($turn == 'on'){
			$in_db = M('invested');//投资记录表
			$activity_start = strtotime('2015-05-04 00:00:00');//活动开始时间
			$activity_end = strtotime('2015-05-31 23:59:59');//活动结束时间
			$activity_where['create_time'] = array(array('EGT',$activity_start),array('ELT',$activity_end),'AND');
			$accumulative_info = $in_db->field('sum(amount) as s_amount,uid')->where($activity_where)->group('uid')->order('s_amount desc,create_time asc')->select();			
			if($accumulative_info){
				$x = 0;
				$c_score = 0;
				foreach($accumulative_info as $key => $value){						
					$activity_score = $this->temp($value['s_amount']);//换算奖励积分方法
					if($activity_score > 0){
						//echo $value['uid']."---".$value['s_amount'].'---'.$activity_score.'<br>';
						$x ++;
						$c_score += $activity_score;
						$this->add_activity_score($value['uid'],$activity_score,$value['s_amount']);//发放积分过程						
					}				
				}
				echo '累计投资奖励发放完毕！请勿刷新页面！参与活动总人数共：'.$x.'人。共发放积分：'.$c_score.'分';
			}
		}
	}


/**
 * @todo   temp            累计投资奖励积分换算方法
 * @param  $amount         用户投资总金额（递减）
 * @param  $score          返回的奖励积分（递加）
 * @return $score          递归后结果，实际需要奖励的积分总额
 */
function temp($amount,$score = 0){
	if($amount >=20000){
		$score += 5000;
		$shengyu = $amount - 20000 ;
	}elseif($amount >= 10000){
		$score += 2000;
		$shengyu = $amount - 10000 ;
	}elseif($amount >= 5000){
		$score += 800;
		$shengyu = $amount - 5000 ;
	}elseif($amount >= 2000){
		$score += 300;
		$shengyu = $amount - 2000 ;
	}
	if($shengyu >= 2000){
		$score = $this->temp($shengyu,$score);
	}
	return $score;
}


	/**
	 * @todo  	add_activity_score         奖励用户积分函数
	 * @param 	int    $uid                用户ID
	 * @param 	int    $activity_score     奖励的积分
	 * @param   double $s_amount           累计投资总额
	 * @since 	2015-05-04 16:07:27
	 * @author  liying    <472591847@qq.com>
	 */
	public function add_activity_score($uid,$activity_score,$s_amount){
		$now_scroe = 0;
		$u_info = M('web_user')->field('id,user_name,score')->where('id='.$uid)->find();
		$now_scroe = $u_info['score'] + $activity_score;
		update_score($uid,$activity_score);  //执行发放积分
		$log_data['score'] = $activity_score;
		$log_data['uid'] = $uid;
		$log_data['type'] = 5;//操作状态：系统操作
		$log_data['pay_status'] = 1;//收支状态：收入
		$log_data['detail'] = '累计投资奖励：活动期间投资总额达到'.$s_amount.';奖励积分共'.$activity_score.'分。当前剩余积分：'.$now_scroe.'分';		
		$log_data['create_time'] = NOW_TIME;//收支状态：收入
		// p($log_data);
		M('score_log')->add($log_data);//生成积分记录
	}

}
?>