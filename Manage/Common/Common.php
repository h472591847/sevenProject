<?php
include('./Common/Common.php');
/**
 * format_number_price           格式化价格
 * @param  [type] $price  价格
 */
function format_number_price($price){
    return sprintf("%.2f", substr(sprintf("%.3f",$price), 0, -1));
}

//投资还款处理
//返回 1非法操作 2借款金额与投资金额不符 3还款失败 0成功
function return_invested($lid){ 
	set_time_limit(0);
	if(empty($lid)){
		return 1 ;
		//$this->error('非法操作!');
	}
	$M_loan = M('loan');//借款表
	$M_invested = M('invested'); //投资记录表
	$M_web_user = M('web_user'); //会员表
	$invested_uid = $M_invested->where("lid = $lid and status = 0")->getField('uid',true);//查询此项目所有会员id
	$loan = $M_loan->field('loan_money,loan_rate,loan_deadline,loan_title')->where("id=".$lid)->find();//借款项目表 分别查询出：借款总额,年利率,期限
	$sum_invested = $M_invested->field("SUM(amount) sum_amount")->where("lid=".$lid)->find();//查询已投资金额
	$loan_over = $loan['loan_money'] - $sum_invested['sum_amount']; //计算剩余借款额
	if($loan_over != 0){
		return 2;
		//$this->error('借款金额与投资金额不符,无法还款!');
	}
	$invested = $M_invested->where("lid = $lid and status = 0")->select();//查询此项目所有投资信息
	$invested_date = array(); 
	$user_field = array();
	$user_date = array();
	$uids = array();
	foreach($invested as $in){
		$user_field['id'] = $in['uid']; //记录会员id
		$user_field['invested_id'] = $in['id']; //记录投资ID
		$management_fee = ($in['repayment_amount'] - $in['amount']) * 0.1; //收益额度的百分之10为账户管理费
        //+++++++++2015-04-27 16:55:31 update_by:liying+++++++++++
        $management_fee = format_number_price($management_fee);//取两位小数进行计算
        //+++++++++2015-04-27 16:55:31 update_by:liying+++++++++++
		$user_field['repayment_amount'] = $in['repayment_amount'] - $in['amount'];//记录应还款利息(不含管理费)			
		$user_field['money'] = $in['repayment_amount'] - $management_fee;  //记录还款金额
		$user_field['management_fee'] = $management_fee; //记录管理费
		$user_field['amount'] = $in['amount'];//本金(投资金额)
        $user_field['bonus_money'] = $in['bonus_money'];//使用的奖励金额
		$user_field['count_repayment'] = $in['amount'] + $user_field['repayment_amount'];//总收益（不扣除管理费）
		$user_date[] = $user_field;
	}            	                   
	$if_loan = $M_loan->where("id = $lid")->setField('deal_status','5');//修改项目表状态
	$if_invested = $M_invested->where("lid = $lid")->setField('status','1');//修改投资表还款状态
	$deal_log_data = array();//消息处理数组
	foreach($user_date as $k => $v){		
		$user_info = $M_web_user->where('id='.$v['id'])->find();//读取操作前用户信息                        
        //+++++++++++++ 判断冻结金额是否足够支付还款本金 2015-05-18 16:41:31 update_by:liying ++++++++++++++
        if($user_info['lock_money'] >= $v['amount']){
    		$M_web_user->where("id = ".$v['id'])->setInc('money',$v['money']); //修改全部会员金额                  		
            $M_web_user->where("id = ".$v['id'])->setDec('lock_money',$v['amount']); //将冻结的投资金额消掉                            
    		$now_user_money = $user_info['money'] + $v['amount'];//当前剩余金额记录
            //当前剩余冻结金额                        
    		$now_user_lock_money = ($user_info['lock_money'] - $v['amount'] < 0) ? 0 : ($user_info['lock_money'] - $v['amount']);
    		/*写入交易记录表 记录收回本金*/
    		$deal_log_data[$k][0]['uid'] = $v['id'];
    		$deal_log_data[$k][0]['lid'] = $lid;
    		$deal_log_data[$k][0]['invested_id'] = $v['invested_id'];
    		$deal_log_data[$k][0]['money'] = $v['amount'];//还款本金
    		$deal_log_data[$k][0]['type'] = 4;//交易状态为 还款本金
    		$deal_log_data[$k][0]['pay_status'] = 1;//收支状态为收入
    		$deal_log_data[$k][0]['detail'] = '已收回本金:'.$v['amount'].'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$user_info['bonus_money'].'元';//明细
    		$deal_log_data[$k][0]['create_time'] = time();//交易时间    
    		/*记录收回利息*/
    		$deal_log_data[$k][1]['uid'] = $v['id'];
    		$deal_log_data[$k][1]['lid'] = $lid;
    		$deal_log_data[$k][1]['invested_id'] = $v['invested_id'];
    		$deal_log_data[$k][1]['money'] = $v['repayment_amount'];//还款利息（不含管理费）
    		$deal_log_data[$k][1]['type'] = 5;//交易状态为 还款利息
    		$deal_log_data[$k][1]['pay_status'] = 1;//收支状态为收入
    		$now_user_money = $now_user_money + $v['repayment_amount'];//当前剩余金额记录
    		$now_user_lock_money = $now_user_lock_money ;//- $v['repayment_amount'];//当前剩余冻结金额
    		$now_user_lock_money = ($now_user_lock_money>0)?$now_user_lock_money:0;//负数清零
    		$deal_log_data[$k][1]['detail'] = '已收回利息:'.$v['repayment_amount'].'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$user_info['bonus_money'].'元';//明细
    		$deal_log_data[$k][1]['create_time'] = time();//交易时间    
    		//记录扣除管理费
    		$deal_log_data[$k][2]['uid'] = $v['id'];
    		$deal_log_data[$k][2]['lid'] = $lid;
    		$deal_log_data[$k][2]['invested_id'] = $v['invested_id'];
    		$deal_log_data[$k][2]['money'] = $v['management_fee'];//管理费
    		$deal_log_data[$k][2]['type'] = 6;//状态为 管理费 
    		$deal_log_data[$k][2]['pay_status'] = 2;//支出 状态
    		$now_user_money = $now_user_money - $v['management_fee'];//当前剩余金额记录            
    		$deal_log_data[$k][2]['detail'] = '扣除管理费:'.$v['management_fee'].'元。当前剩余金额:'.format_number_price($now_user_money).'元,剩余冻结金额:'.format_number_price($now_user_lock_money).'元';//明细
            $deal_log_data[$k][2]['detail'] .= ',剩余奖励金额:'.format_number_price($user_info['bonus_money']).'元';
    		$deal_log_data[$k][2]['create_time'] = time();//交易时间   
        }else{
            $deal_log_data[$k][2]['uid'] = $v['id'];
            $deal_log_data[$k][2]['lid'] = $lid;
            $deal_log_data[$k][2]['invested_id'] = $v['invested_id'];
            $deal_log_data[$k][2]['money'] = 0;//金额
            $deal_log_data[$k][2]['type'] = 8;//状态为 系统操作 
            $deal_log_data[$k][2]['pay_status'] = 2;//支出 状态
            $now_user_money = $now_user_money - $v['management_fee'];//当前剩余金额记录            
            $deal_log_data[$k][2]['detail'] = '还款时出现异常：工作人员处理后将会给予通知，对于未能及时回款深感抱歉。';//明细            
            $deal_log_data[$k][2]['create_time'] = time();//交易时间               
        }
	}
	$deal_log_arr = array();//消息写入数组
	foreach ($deal_log_data as $key => $value) {					
		foreach ($value as $k => $v) {
			$deal_log_arr[] = $v;				
		}						
	}		
	insert_deal_log($deal_log_arr);//将以上三种结果写进记录表
	if($if_loan && $if_invested){	
		$user_arr = $M_web_user->where(array('is_effect'=>1,'is_delete'=>0))->select();             
		/*到账通知需要的数据,压入数组*/
		$user_repayment = array();
		foreach ($user_arr as $k => $v) {  
			foreach ($user_date as $key => $value) {
				if($value['id'] == $v['id']){
					//$user_repayment[$k]['user_id'] = $v['id'];
					//$user_repayment[$k]['user_name'] = $v['user_name'];
					$user_repayment[$k]['phone'] = $v['phone']; 
					
					$user_repayment[$k]['repayment'] += $value['count_repayment'];//纯利润+管理费 =  给客户看的还款总额
				}                
			}
		}   
		//记录操作sql
		$remember_sql = $M_invested->getLastSql().'\n\r'.$M_web_user->getLastSql();
		$msg_data['title'] = '项目还款';
		$msg_data['content'] = '【后台用户：'.session('username').'】对【'.$loan['loan_title'].'】进行了还款。';
		$msg_data['user_id'] = session(C('USER_AUTH_KEY'));
		$msg_data['create_time'] = time();
		$msg_data['type'] = 2;//信息操作
		$msg_data['web_status'] = 2;//状态：后台操作
		$msg_data['sql'] = $remember_sql;
		insert_msg_box($msg_data);          
		//发送到账通知短信
		foreach ($user_repayment as $key => $value) {
			// $msg_str = "到账通知:您今日共收到投资还款".$value['repayment'].'元,可到"我的账户"-"交易记录"页查看详细';
			if(!empty($value['phone'])){
				// send_sms($value['phone'],$msg_str,'',3);   //此处为发送还款短信   测试需注释
				send_sms($value['phone'],$value['repayment'],'项目还款通知');   //此处为发送还款短信   测试需注释
			}
		}
		unset($invested_date,$user_field,$user_date,$deal_log_data,$deal_log_arr,$user_repayment);
		return 0;
		//$this->success('还款完成!',U('Loan/Index'));
	}else{
		return 3;			
		//$this->error('还款失败！');
	}
}

 //获取文件目录列表,该方法返回数组 
function getDir($dir) { 
    $dirArray = array();     
    if (false != ($handle = opendir ( $dir ))) { 
        $i=0; 
        while ( false !== ($file = readdir ( $handle )) ) { 
            //去掉"“.”、“..”以及带“.xxx”后缀的文件             
            if ($file != "." && $file != ".."&&!strpos($file,".")) { 
                $dirArray[$i] = $file; 
                $i++; 
            } 
        } 
        //关闭句柄         
        closedir ( $handle ); 
    } 
    return $dirArray; 
} 


 //获取文件列表 
function getFile($dir) { 
    $fileArray = array(); 
    if (false != ($handle = opendir ( $dir ))) { 
        $i=0; 
        while ( false !== ($file = readdir ( $handle )) ) { 
            //去掉"“.”、“..”以及带“.xxx”后缀的文件             
            if ($file != "." && $file != ".."&&strpos($file,".")) { 
                $fileArray[$i] = $file; 
                if($i==100){ 
                    break; //读取限制为100个文件
                } 
                $i++; 
            } 
        } 
        //关闭句柄         
        closedir ( $handle ); 
    } 
    return $fileArray; 
} 


//获取文件夹和子文件夹中所有文件
function getAllFiles($dir){
    $fileArray = array();     
    if (false != ($handle = opendir ( $dir ))) { 
        $i=0;           
        while ( false !== ($file = readdir ( $handle )) ) { 
            $i++; 
            //去掉"“.”、“..”以及带“.xxx”后缀的文件             
            if ($file != "." && $file != ".."&&strpos($file,".")) { 
                $fileArray[$i] = $file;                 
            }elseif($file != "." && $file != ".."&&!strpos($file,".")){                
                $file_name = $dir.$file;                                            
                $fileArray = array_merge(getAllFiles($file_name),$fileArray);                
            }           
        } 
        //关闭句柄         
        closedir ( $handle ); 
    } 
    return $fileArray;    
}
?>