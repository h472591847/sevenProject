<?php
/**
 * @todo            投资管理控制器
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-10-13
 */
class InvestedAction extends CommonAction {


	/**
	 * @todo   Index  投资列表视图
	 */
    public function Index(){
        $action = trim(I('action'));
    	$k_lid = trim(I('lid'));
        $id = trim(I('id'));
    	$k_uid = trim(I('uid'));
    	$k_start_time = trim(I('k_start_time'));
    	$k_end_time = trim(I('k_end_time'));
    	import('ORG.Util.Page');//引入分页类
    	$db = M('invested');   //投资表 	
    	$user_db = M('web_user');//会员表
    	$loan_db = M('loan');//借款项目表
		//向分页传递参数	
		$parameter = array(
			'lid'=>$k_lid,
            'id'=>$id,
			'uid'=>$k_uid,
			'k_start_time'=>$k_start_time,
			'k_end_time'=>$k_end_time
		);
		$k_start_time = strtotime($k_start_time); //筛选开始时间转换时间戳   	
		$k_end_time = strtotime($k_end_time);//筛选结束时间转换时间戳
        if(!empty($id))
            $where['id'] = $id;
		//按条件查询
		if(!empty($k_lid))
			$where['lid'] = $k_lid;
		if(!empty($k_uid))
			$where['uid'] = $k_uid;
		if(!empty($k_start_time))
			$where['create_time'] = array('EGT',$k_start_time);
		if(!empty($k_end_time))
			$where['create_time'] = array('ELT',$k_end_time);
		if(!empty($k_start_time) && !empty($k_end_time))
			$where['create_time'] = array(array('EGT',$k_start_time),array('ELT',$k_end_time),'AND');
		$count = $db->where($where)->count();			
		$page = new Page($count,25,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
        //导出excel数据时不分页
        if($action == 'export_excel'){
            $limit = '';
        }             
		$info = $db->where($where)->order('create_time DESC,id DESC')->limit($limit)->select();		
		$uids = array();//会员ID集合
		$lids = array();//项目ID集合
		foreach ($info as $key => $value) {
			$uids[] = $value['uid'];
			$lids[] = $value['lid'];
		}
		$user_where = array(
			'id'=>array('IN',$uids)
		);
		//取出投资人信息
		$user_ar = $user_db->field('id,user_name')->where($user_where)->select();
		$loan_where = array(
			'id'=>array('IN',$lids),
			'is_effect'=>1,
			'is_delete'=>0
		);
		//取出项目信息
		$loan_ar = $loan_db->where($loan_where)->select();				
		$group_lid = $db->field('lid')->group('lid')->select();	//项目ID集合		
		$group_uid = $db->field('uid')->group('uid')->select(); //用户（投资人）ID集合
		$group_lids = array();
		$group_uids = array();
		foreach ($group_lid as $key => $value) {
			$group_lids[] = $value['lid'];
		}
		foreach ($group_uid as $key => $value) {
			$group_uids[] = $value['uid'];
		}		
		$group_loan_where['id'] = array('IN',$group_lids);
		//已投资的项目结果
		$this->group_loan_res = $loan_db->field('loan_title,id')->where($group_loan_where)->order('id DESC')->select();
		$group_user_where['id'] = array('IN',$group_uids);
		//已投资的用户结果
		$this->group_user_res = $user_db->field('user_name,id')->where($group_user_where)->order('id DESC')->select();		
		$count_in = $db->field('sum(amount) as invested_count,lid,sum(repayment_amount) as repayment_count')->group('lid')->select();			
		//将项目信息和投资人信息压入数组
		foreach ($info as $key => $value) {
			foreach ($user_ar as $k => $v) {
				if($v['id'] == $value['uid']){
					$value['user_name'] = $v['user_name'];
					$value['user_id'] = $v['id'];
				}
			}
			foreach ($loan_ar as $loan_k => $loan_v) {
				if($loan_v['id'] == $value['lid']){					
					$value['loan_title'] = $loan_v['loan_title'];
					$value['loan_money'] = $loan_v['loan_money'];
					$value['loan_id'] = $loan_v['id'];		            
		            if($loan_v['loan_deadline_type'] == 1){
		            	$loan_deadline = $loan_v['loan_deadline'] * 24 * 3600;//借款时间期限按天计算
		            }else{
		            	$loan_deadline = $loan_v['loan_deadline'] * 30 * 24 * 3600;//借款时间期限按月计算
		            }
		            //到期时间 = 还款确认时间 + 借款期限时间戳
		            $value['expire_time'] = $loan_v['expire_time'];                           		             					
		            $value['repay_confirm_time'] = $loan_v['repay_confirm_time'];//还款确认时间
		            $value['deal_status'] = $loan_v['deal_status'];//项目状态
				}				
			}			
			foreach ($count_in as $in_key => $in_value) {
				if($in_value['lid'] == $value['lid']){
					$value['invested_count'] = $in_value['invested_count'];
					$value['repayment_count'] = $in_value['repayment_count'];					
				}
			}
			$info[$key] = $value;
		}	
        /*导出excel数据*/   
        if($action =='export_excel'){
            foreach ($info as $key => $value) {
                $value['status_view'] = ($value['status_view'] == 0)?'未还款':'已还款';                
                $value['create_time_view'] = (!empty($value['create_time']))?date('Y-m-d H:i:s',$value['create_time']):'暂无';
                $value['repay_confirm_time_view'] = (!empty($value['repay_confirm_time']))?date('Y-m-d H:i:s',$value['repay_confirm_time']):'未确认';
                $value['expire_time_view'] = (!empty($value['expire_time']))?date('Y-m-d H:i:s',$value['expire_time']):'未确认';
                $info[$key] = $value;
            }            
            $excel_title  = array(
                'ID','项目名称','借款额','当前项目已投资总额','还款总金额','投资人',
                '投资金额','使用奖励金额','还款金额','还款状态','投资时间','计息日','到期时间'
            );
            $excel_field = array(
                'id','loan_title','loan_money','invested_count','repayment_count','user_name',
                'amount','bonus_money','repayment_amount','status_view','create_time_view','repay_confirm_time_view','expire_time_view'
            );
            ExcelOut($info,$excel_title,$excel_field);
        }		        
        $uid_not_where = $where;        
        $uid_not_where['uid'] = array('NOT IN','102,103');
        $in = $db->field('sum(amount) as in_count,sum(repayment_amount) as in_repayment_amount,sum(bonus_money) as in_bouns_money')->where($uid_not_where)->find();                        
        $in['in_count'] = $in['in_count']?$in['in_count']:0;//投资总额传输到模版          
        $in['in_repayment_amount'] = $in['in_repayment_amount']?$in['in_repayment_amount']:0;//会员总数传输到模版                  
        $pure = $in['in_repayment_amount'] - $in['in_count'];//总利息        
        $this->in_count = $in['in_count'];//投资总额传输到模版        
        $this->pure = $pure;//总利息传输到模版
        $this->in_count_management_price = $pure?$pure * 0.1:0;//总管理费传输到模版
        $this->in_bouns_money = $in['in_bouns_money'];//使用奖励金额总额
		unset($lids,$uids,$group_lids,$group_uids);
        $this->page_param = $parameter;//将分页参数传递到模版
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }


	/**
	 * @todo  Loan 		修改/添加视图
	 */
	public function Invested(){				
    	$loan_db = M('loan');
    	$user_db = M('web_user');
		$db = M('Invested');		
		//会员列表
		$user_where = array(
			'is_effect'=>1,
			'is_delete'=>0
		);
		//项目列表
		$loan_where = array(
			'is_effect'=>1,
			'is_delete'=>0,
			'deal_status'=>1 //状态为进行中
		);		
		$this->user_list = $user_db->field('user_name,id')->where($user_where)->order('create_time DESC,id DESC')->select();
		$this->loan_list = $loan_db->field('loan_title,id')->where($loan_where)->order('create_time DESC,id DESC')->select();
		$this->display();
    }



	/**
	 * @todo          DoAddInvested 添加投资处理
	 * @param         $uid           投资会员ID
	 * @param         $lid           所投项目ID
	 */
	public function DoAddInvested(){	
		$uid = I('uid');				//登录用户id
		$lid = I('lid',0);				//借款项目id
		$amount = I('money',0);			//投资金额
		if(empty($uid) || empty($lid) || empty($amount)){ //说明页面跳过js判断
			$this->error('非法操作!<br>error:1');
		}
		$user_db = M('web_user');		
		$user_where["id"] = $uid;
		//取出用户当前可用余额,冻结金额
		$user_res = $user_db->field('id,money,lock_money,user_name,bonus_money')->where($user_where)->find();		
		//echo $user_money;die;		
		$loan = M('loan')->field('loan_money,loan_rate,loan_deadline_type,loan_deadline,min_loan_money,max_loan_money,loan_title,start_time')->where("id = $lid")->find();//借款项目表 分别查询出：借款总额,年利率,期限				
       
        $temp_money = $amount / 10;//可用奖励金额            
        //当用户奖励金额 足够支付本次可用奖励金额时
        if($temp_money <= $user_res['bonus_money']){ 
           //投资可用余额 = 用户余额 + 可用奖励金额
		       $true_money = $user_res['money'] + $temp_money;
           $trade_money = $amount - $temp_money;//用户余额实际支付金额           
           $user_update['money'] = array('exp','money-'.$trade_money);//用户余额扣款
           $user_update['bonus_money'] = array('exp','bonus_money-'.$temp_money);//奖励金额扣款
        }else{
          //投资可用余额 = 用户余额 + 奖励金额
          $true_money = $user_res['money'] + $user_res['bonus_money'];
          $trade_money = $amount - $user_res['bonus_money'];
          $user_update['money'] = array('exp','money-'.$trade_money);//用户余额扣款
          $user_update['bonus_money'] = 0;//奖励金额清零(使用全部)
        } 
        //当前用户可投资金额和投资金额对比
        if($true_money < $amount){
            $this->error('当前用户余额不足');
        }        
		$M_invested = M('Invested');//投资记录表
		$sum_invested = $M_invested->field("SUM(amount) sum_amount")->where("lid = $lid")->find();//查询已投资金额
		$loan_over = $loan['loan_money'] - $sum_invested['sum_amount']; //计算剩余借款额		
		//最低投资限制判断
		if($loan['min_loan_money'] != 0 && $amount < $loan['min_loan_money']){
			$this->error('投资金额不可低于最低投资金额:'.$loan['min_loan_money'].'元');
		}
		//最高投资限制判断
		if($loan['max_loan_money'] != 0 && $amount > $loan['max_loan_money']){
			$this->error('投资金额不可大于最高投资金额:'.$loan['max_loan_money'].'元');
		}		
		$last_money = $sum_invested['sum_amount']+$amount;//本次投资后的投资总额
		if($amount > $loan_over){ //判断如果输入金额大于剩余借款金额（说明页面跳过js计算）
			$this->error('输入错误，投资总额超过总借款金额!<br>error:2');
		}	
		//判断项目是否开始
        if(NOW_TIME < $loan['start_time']){
          $this->error('未开放的项目，请关注项目开放时间');
        }	


        //投资信息表中取投资用户ID
		// $invested_ar = M('invested')->where('lid='.$lid)->select();
		// $invested_uids = array();//投资人ID集合
		// $invested_phones = array();//投资人电话号集合
		// foreach ($invested_ar as $key => $value) {
		// 	$invested_uids[] = $value['uid'];
		// }
		// //读取投资用户手机号，并压入数组
		// if(is_array($invested_uids)){
		// 	$u_where['id'] = array('IN',$invested_uids);
		// 	$u_info = M('web_user')->where($u_where)->select();
		// 	foreach ($u_info as $key => $value) {
		// 		$invested_phones[] = $value['phone'];
		// 	}						
		// }    
		
        $loan_rate = ($loan['loan_rate'] + $loan['loan_add_rate']) / 100 ;//年利率
		//M改于2015年1月9日 11:10:59 ++
		$loan_deadline_type = $loan['loan_deadline_type'] ;//借款期限类型 0为月1为天
		//++
        $loan_month = $loan['loan_deadline']; //借款期限（月、天）
		//M改于 2015年1月9日 11:13:32 ++
		if($loan_deadline_type == 1){ //期限为天计算方法
			$loan_deadline = $amount + ($amount * $loan_rate / 365 * $loan_month);//利后所得金额
		}else{ //期限为月计算方法
			$loan_deadline = $amount + ($amount * $loan_rate / 12 * $loan_month);//利后所得金额
		}
		//++
        //M改于2015年1月9日 11:13:05 --$loan_deadline = $amount + ($amount * $loan_rate / 12 * $loan_month);//利后所得金额 //--
		$data = array(
			'uid' => $uid,							//登录用户id
			'lid' => $lid,							//借款项目id
			'amount' => $amount,					//投资金额
			'repayment_amount' => $loan_deadline,	//利后所得金额
			'status' => 0,							//还款状态 0未1已
			'create_time' => time()					//操作时间
		);
		$if = $M_invested->add($data);
		if($if){
			
			//将投资金额转移到冻结金额
			$user_update['lock_money'] = array('exp','lock_money+'.$amount);//冻结金额												
			if($last_money == $loan['loan_money']){
				M('loan')->where('id='.$lid)->setField('deal_status','2');//若满额，改变借款状态 2 满额
				M('loan')->where('id='.$lid)->setField('success_time',NOW_TIME);//筹标成功时间	
                 $alert_data['title'] = '满标提醒';
                 $alert_data['content'] = '网站有项目今天已满标，请前往后台查看。';
                 $alert_data['status'] = 1;
                 $alert_data['create_time'] = time();
                 insert_system_alert($alert_data);   
                 send_sms('13904314687',$alert_data['content']);//手机提醒
                 send_mail('2956000@qq.com','金投资', iconv('UTF-8','GB2312',$alert_data['title']), $alert_data['content'], "HTML");//邮件提醒  				
				//给投资人发送满标通知
				// if(is_array($invested_phones)){
				// 	foreach ($invested_phones as $phone_v) {
				// 		$msg_str = "满标通知：您投资的项目【".$loan['loan_title'].'】于'.date('Y-m-d H:i:s',NOW_TIME).'完成满额';
				// 		send_sms($phone_v,$msg_str);
				// 	}
				// }  							
			}
			$user_db->where($user_where)->save($user_update);	
            /*写入交易记录表*/
            $deal_log_data[0]['uid'] = $uid;
            $deal_log_data[0]['lid'] = $lid;
            $deal_log_data[0]['invested_id'] = $if;
            $deal_log_data[0]['money'] = $amount;
            $deal_log_data[0]['type'] = 2;//交易状态为投资
            $deal_log_data[0]['pay_status'] = 2;//收支状态为支出
            /*记录当前用户资金信息*/
            $user_info = $user_db->where('id='.$uid)->find();//读取投资后用户信息
            $now_user_money = $user_info['money'];//当前剩余金额记录
            $now_user_lock_money = $user_info['lock_money'];//当前剩余冻结金额
            $now_user_bonus_money = $user_info['bonus_money'];//当前剩余奖励金额                      
            $deal_log_data[0]['detail'] = '投资成功：投资金额为'.$amount.'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细            
            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
            insert_deal_log($deal_log_data);  
            //写入动作表
            $remember_sql = $user_db->getLastSql();
            $view = M('user')->where('id='.session(C('USER_AUTH_KEY')))->find();            
            $msg_data['title'] = '添加投资信息成功';
            $msg_data['content'] = '【后台用户：'.$view['username'].'】添加投资信息->【投资人：'.$user_res['user_name'].' 项目名：'.$loan['loan_title'].'】投资金额：'.format_price($amount).'成功。';
            $msg_data['user_id'] = $view['id'];
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);   
            // unset($invested_uids,$invested_phones);//释放数组           			
			$this->success('投资成功',U('Invested/index'));
		}else{
			$this->error('数据错误');
		}
	}
}
?>