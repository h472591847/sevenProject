<?php
/**
 * @todo            会员提现列表模块
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-10-22
 */
class WithdrawalAction extends CommonAction {

    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        parent::_initialize();
        $this->page_size = 25;
        $this->db = M('withdrawal');
        $this->phone_title = '金 投 资';
    }


	/**
	 * @todo  Index  		 提现列表视图
	 */
    public function Index(){
    	import('ORG.Util.Page');
        $action = trim(I('action'));								
        $uid = trim(I('uid'));  
        $w_id = trim(I('w_id'));      
        $user_name = trim(I('user_name'));           
        $money_start = trim(I('money_start'));
        $money_end = trim(I('money_end'));             
        $k_start_time = strpos(I('k_start_time'),'-')?strtotime(I('k_start_time')):I('k_start_time');
        $k_end_time = strpos(I('k_end_time'),'-')?strtotime(I('k_end_time')):I('k_end_time');                
        $type = trim(I('type'));
        $audit_status = I('audit_status');        
        $biz_no = I('biz_no');
        //向分页传递参数   
        $parameter = array(
            'uid'=>$uid,
            'user_name'=>$user_name,  
            'money_start'=>$money_start,
            'money_end'=>$money_end,     
            'audit_status'=>$audit_status,                 
            'k_start_time'=>$k_start_time,
            'k_end_time'=>$k_end_time,
            'type'=>$type,
            'w_id'=>$w_id,
            'biz_no'=>$biz_no
        );                           
        //按主键筛选
        if(!empty($w_id)){
            $where['id'] = $w_id;
        }
        //按会员筛选
        if(!empty($uid)){
            $where['uid'] = $uid;
        }		
        //按条件筛选
        if(!empty($user_name)){         
            $user_res = M('web_user')->where("user_name='$user_name'")->find();                                 
              $where['uid'] = $user_res['id'];                      
        }  
        //金额区间筛选
        if(!empty($money_start))
            $where['withdrawal_price'] = array('EGT',$money_start);
        if(!empty($money_end))
            $where['withdrawal_price'] = array('ELT',$money_end);
        if(!empty($money_start) && !empty($money_end))
            $where['withdrawal_price'] = array(array('EGT',$money_start),array('ELT',$money_end),'AND');            
        if($audit_status != '')
            $where['audit_status'] = $audit_status;
        //申请时间筛选
        //导出excel时采用不格式化的时间
        //strrpos($k_start_time, needle)
        // if($action == 'export_excel'){ 
        //     $k_start_time = I('k_start_time');
        //     $k_end_time = I('k_end_time');
        // }    
        if(!empty($k_start_time))
            $where['create_time'] = array('EGT',$k_start_time);
        if(!empty($k_end_time))
            $where['create_time'] = array('ELT',$k_end_time);       
        if(!empty($k_start_time) && !empty($k_end_time))
            $where['create_time'] = array(array('EGT',$k_start_time),array('ELT',$k_end_time),'AND');                    	        
		$where['is_delete'] = 0;
        //按提现类别筛选		
        if(!empty($type))
            $where['type'] = $type;
        //按批次号查询
        if(!empty($biz_no)){
            $where['biz_no'] = $biz_no;
        }   		
        //分页
		$count = $this->db->where($where)->count();		
		$page = new Page($count,$this->page_size,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
        //导出excel数据时不分页
        if($action == 'export_excel'){            
            $limit = '';
        }                      
		//结束
		$info = $this->db->where($where)->order('create_time DESC,id DESC')->limit($limit)->select();				                        
		$uids = array();
		foreach ($info as $key => $value) {
			$uids[] = $value['uid'];			
			$info[$key] = $value;
		}		
		$where_user['id'] = array('IN',$uids);
		$user_info = M('web_user')->where($where_user)->select();
		foreach ($info as $key => $value) {
			foreach ($user_info as $k => $v) {
				if($v['id'] == $value['uid']){
					$value['user_info'] = $v;
                    $value['user_name_view'] = $v['user_name'];
				}
			}	
            $value['withdrawal_price'] = $value['withdrawal_price'] - $value['management_price'];
			$info[$key] = $value;
		}		
        /*导出excel数据*/   
        if($action =='export_excel'){
            foreach ($info as $key => $value) {
                switch ($value['audit_status']) {
                     case '0':
                        $value['audit_status_view'] = '申请中';
                         break;
                     case '1':
                         $value['audit_status_view'] = '通过';
                         break;
                     case '2':
                         $value['audit_status_view'] = '不通过';
                         break; 
                     case '3':
                         $value['audit_status_view'] = '打款完成';
                         break;                                                                       
                 } 

                 
                $value['type_view'] = ($value['type'] == 0)?'正常提现':'加急提现';
                $value['create_time_view'] = (!empty($value['create_time']))?date('Y-m-d H:i:s',$value['create_time']):'暂无';
                $value['audit_date_view'] = (!empty($value['audit_date']))?date('Y-m-d H:i:s',$value['audit_date']):'暂无';
                $info[$key] = $value;
            }            
            $excel_title  = array('ID','会员帐号','提现金额','手续费','申请时间','提现状态','提现类型','审核时间');
            $excel_field = array('id','user_name_view','withdrawal_price','management_price','create_time_view','audit_status_view','type_view','audit_date_view');
            ExcelOut($info,$excel_title,$excel_field);
        }
        unset($uids);           
        $this->page_param = $parameter;
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }


    /**
     * @todo   Withdrawal    审核视图
     * @param  $upid         主键
     */
    public function Withdrawal(){
    	$upid = I('upid');        
        $refer = $_SERVER['HTTP_REFERER'];//获取上一页地址
        $no_refer = (int)I('no_refer');
        if($no_refer != 1){
            session('w_refer',$refer);
        }else{
            $refer = session('w_refer');
        }                    
    	$info = $this->db->where('id='.$upid)->find();
		$user_info = M("web_user")->where('id='.$info['uid'])->find();
    	$bank = M('bank')->where('id='.$info['bank_id'].' AND uid='.$info['uid'])->find();
    	$info['bank'] = $bank;
        $info['withdrawal_price'] = $info['withdrawal_price'] - $info['management_price'];
		$this->user_info = $user_info;
    	$this->info = $info;
        $this->refer = $refer;//获取上一页地址
    	$this->upid = $upid;
    	$this->display();
    }


    /**
     * @todo   DoWithdrawal    审核处理
     * @param  $upid           主键
     * @param
     */
    public function DoWithdrawal(){
    	$data = $_POST;
        $act = '';
    	$data['audit_date'] = NOW_TIME;
    	$with_res = $this->db->where('id='.$data['upid'])->find();
        $management_price = $with_res['management_price'];
        // $true_price = $management_price + $with_res['withdrawal_price'];          
        $true_price = $with_res['withdrawal_price'];          
        $user_info = M('web_user')->where('id='.$with_res['uid'])->find();                     
        //上次审核的结果
        $old_res = M('withdrawal')->where('id='.$data['upid'])->find();
        if(!M('withdrawal')->autoCheckToken($_POST)){
            $this->error('令牌验证错误');
        }          
    	//审核状态为审核失败时从冻结的提现金额扣除退回给会员余额
		if($data['audit_status'] == 2 && $old_res['audit_status'] != 2){	
            if($user_info['lock_money'] < $true_price){
                $this->error('冻结余额已不足以进行当前提现');
            }
            M('web_user')->where('id='.$with_res['uid'])->setDec('lock_money',$true_price);//从冻结金额扣除提现余额            
            M('web_user')->where('id='.$with_res['uid'])->setInc('money',$true_price);//将提现申请的金额退回用户账户           
            $now_user_money = $user_info['money'] + $true_price;//记录当前剩余金额
            $now_user_money = ($now_user_money >0 )?$now_user_money:0;//负数清零
            $now_user_lock_money = $user_info['lock_money'] - $true_price;//当前剩余冻结金额                            
            $now_user_bonus_money = $user_info['bonus_money'];//当前剩余奖励金额            
            /*提现信息写入交易记录表*/
            $deal_log_data[0]['uid'] = $with_res['uid'];
            $deal_log_data[0]['lid'] = '';
            $deal_log_data[0]['invested_id'] = '';
            $deal_log_data[0]['money'] = '';
            $deal_log_data[0]['type'] = 3;//交易状态为提现
            $deal_log_data[0]['pay_status'] = 2;//收支状态为支出
            $deal_log_data[0]['detail'] = '提现审核失败 '.$data['audit_content'].'当前剩余金额：'.$now_user_money.'元。剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细            
            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
            insert_deal_log($deal_log_data); 
            //提现审核失败，发送短信                      
            $msg = $user_info['user_name'].'您好，您在 '.$this->phone_title.' 提现'.format_price($with_res['withdrawal_price']).'元，审核失败。原因请进入我的账户-消息中心查看';
            // send_sms($user_info['phone'],$msg);   
            // ++++++++++++++2015-04-07 16:41:42 update_by:liying++++++++++
            send_sms($user_info['phone'],$user_info['user_name'],'提现审核失败');
            // ++++++++++++++2015-04-07 16:41:42 update_by:liying++++++++++               
            $act = '对【'.$user_info['user_name'].'】申请的提现操作为【不通过】';
         //审核状态为通过并且上次结果不是通过的进行以下操作     
		}elseif($data['audit_status'] == 3 && $old_res['audit_status'] != 3){
            /*提现审核通过处理*/
            if($user_info['lock_money'] < $true_price){
                $this->error('会员当前可用余额已不足请求提现金额,请审核为不通过');
            }
            M('web_user')->where('id='.$with_res['uid'])->setDec('lock_money',$true_price);//从冻结金额扣除提现余额            
            /*提现信息写入交易记录表*/
            $deal_log_data[0]['uid'] = $with_res['uid'];
            $deal_log_data[0]['lid'] = '';
            $deal_log_data[0]['invested_id'] = '';
            $deal_log_data[0]['money'] = $with_res['withdrawal_price'];
            $deal_log_data[0]['type'] = 3;//交易状态为提现
            $deal_log_data[0]['pay_status'] = 2;//收支状态为支出
            $now_user_money = $user_info['money'];//记录当前剩余金额                        
            $now_user_lock_money = $user_info['lock_money'] - $true_price;//当前剩余冻结金额                            
            $now_user_bonus_money = $user_info['bonus_money'];//当前剩余奖励金额  
            $to_account_money = $with_res['withdrawal_price'] - $management_price;                                   
            $deal_log_data[0]['detail'] = '提现审核通过：提现金额：'.$with_res['withdrawal_price'].'元,到账金额：'.$to_account_money.'元,手续费：'.$management_price.'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细            
            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
            /*提现手续费写入交易记录表*/
            // $deal_log_data[1]['uid'] = $with_res['uid'];
            // $deal_log_data[1]['lid'] = '';
            // $deal_log_data[1]['invested_id'] = '';
            // $deal_log_data[1]['money'] = $management_price;
            // $deal_log_data[1]['type'] = 6;//交易状态为管理费
            // $deal_log_data[1]['pay_status'] = 2;//收支状态为支出
            // //$now_user_money = $now_user_money - $management_price;//记录当前剩余金额
            // $now_user_money = ($now_user_money >0 )?$now_user_money:0;//负数清零
            // $deal_log_data[1]['detail'] = '扣除提现手续费:'.$management_price.'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细            
            // $deal_log_data[1]['create_time'] = NOW_TIME;//交易时间    
            insert_deal_log($deal_log_data);   
            //提现审核成功，发送短信           
            // $msg = $user_info['user_name'].'您好，您在 '.$this->phone_title.' 提现'.format_price($with_res['withdrawal_price']).'元已打款完成。请注意查询您的账户。';
            // send_sms($user_info['phone'],$msg);
            // ++++++++++++++2015-04-07 15:59:42 update_by:liying++++++++++
            send_sms($user_info['phone'],$user_info['user_name'],'提现审核成功');
            // ++++++++++++++2015-04-07 15:59:42 update_by:liying++++++++++
            $act = '对【'.$user_info['user_name'].'】申请的提现操作为【打款完成】';
        }else if($data['audit_status'] == 1){
            $act = '对【'.$user_info['user_name'].'】申请的提现操作为【通过】';
        }
    	$update = $this->db->where('id='.$data['upid'])->save($data);    	
    	if($update){
            //写入动作表
            $remember_sql = $this->db->getLastSql();            
            $msg_data['title'] = '审核提现信息';
            $msg_data['content'] = '【后台用户：'.session('username').'】'.$act.' ID:'.$data['upid'];
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);              
    		$this->success('审核成功',$data['refer']);
    	}else{
    		$this->error('数据错误');
    	}
    }


    /**
     * @todo   DoPayment   打款方法
     * @param  $w_id       提现ID
     */
    public function  DoPayment(){
        $w_id = I('w_id'); //提现ID
        $w_info = $this->db->where('id='.$w_id)->find();//提现信息
        $uid = $w_info['uid'];//用户ID
        $user_info = M('web_user')->where('id='.$uid)->find();
        $bank_info = M('bank')->where('uid='.$w_info['uid'].' and is_default=1')->find();//银行卡信息
        $true_price = $w_info['withdrawal_price'] - $w_info['management_price'];                
        //审核状态判断
        if($w_info['audit_status'] == 3 || $w_info['error_code'] == '0000')
            $this->error('请勿重复打款',U('Withdrawal/withdrawal',array('upid'=>$w_id)));        
        //冻结与提现金额比对
        if($user_info['lock_money'] < $true_price){
            $this->error('冻结余额已不足以进行当前提现',U('Withdrawal/withdrawal',array('upid'=>$w_id)));
        }
        $user_name = $user_info['user_name'];    
        $real_name = $bank_info['real_name'];    
        $bank_name = $bank_info['bank_name']; 
        $bank_number = $bank_info['bank_number']; 
        $province = $this->filter_province($bank_info['province']);
        $city = $this->filter_city($bank_info['city']);
        $phone = $user_info['phone'];
        if(substr($phone, 0,3) == '170' && $user_info['email'] != ''){
            $phone = (string)$user_info['email'];
        }
        $withdrawal_price = $true_price * 100;//元转化成分 
        $bank_address = $bank_info['bank_address']; 
        $query_str = '&user_name='.$user_name.'&real_name='.$real_name.'&bank_name='.$bank_name.'&bank_number='.$bank_number;
        $query_str .= '&province='.$province.'&city='.$city.'&phone='.$phone.'&withdrawal_price='.$withdrawal_price.'&bank_address='.$bank_address;                        
        redirect('/Manage/payment/payment.php?w_id='.$w_id.$query_str);die;
            
    }


    /**
     * @todo   filter_province   特殊省份筛选
     * @param  $province         未处理省份名称
     * @return $result           处理后省份名称
     */
    public function  filter_province($province){
        //若为特殊城市或省份，则不拼接 '省'字
        switch ($province) {
            case '北京':
                $result = '北京市';
                break;  
            case '上海':
                $result = '上海市';
                break;  
            case '天津':
                $result = '天津市';
                break;  
            case '重庆':
                $result = '重庆市';
                break;  
            case '内蒙古':
                $result = '内蒙古自治区';
                break;     
            case '广西':
                $result = '广西自治区';
                break;                                                                                            
            case '新疆':
                $result = '新疆自治区';
                break;                       
            case '西藏':
                $result = '西藏自治区';
                break;  
            case '宁夏':
                $result = '宁夏自治区';
                break;                    
            case '香港':
                $result = '香港';
                break; 
            case '澳门':
                $result = '澳门';
                break;                                                                                                  
            default:
                $result = $province.'省';
                break;
        }
        return $result;
    }

    /**
     * @todo   filter_city       特殊城市筛选
     * @param  $city             未处理城市名称
     * @return $result           处理后城市名称
     */
    public function  filter_city($city){        
        switch ($city) {
            case '巴音郭楞':
                $result = '巴音郭楞蒙古族自治州';
                break;
            case '克孜勒苏':
                $result = '克孜勒苏柯尔克孜自治州';
                break;
            case '黔西南':
                $result = '黔西南布依族苗族自治州';
                break;
            case '博尔塔拉':
                $result = '博尔塔拉蒙古族自治州';
                break;
            case '昌都':
                $result = '昌都地区';
                break;
            case '山南':
                $result = '山南地区';
                break;                                                                                
            case '日喀则':
                $result = '日喀则地区';
                break;                                                                                
            case '那曲':
                $result = '那曲地区';
                break;                                                                                
            case '阿里':
                $result = '阿里地区';
                break;                                                                                
            case '林芝':
                $result = '林芝地区';
                break;                                                                                
            case '临夏':
                $result = '临夏回族自治州';
                break;
            case '甘南':
                $result = '甘南藏族自治州';
                break;
            case '海北':
                $result = '海北藏族自治州';
                break;
            case '黄南':
                $result = '黄南藏族自治州';
                break;
            case '海南':
                $result = '海南藏族自治州';
                break;
            case '果洛':
                $result = '果洛藏族自治州';
                break;  
            case '玉树':
                $result = '玉树藏族自治州';
                break;  
            case '海西':
                $result = '海西蒙古族藏族自治州';
                break;  
            case '吐鲁番':
                $result = '吐鲁番地区';
                break;       
            case '哈密':
                $result = '哈密地区';
                break;       
            case '昌吉':
                $result = '昌吉回族自治州';
                break;       
            case '阿克苏':
                $result = '阿克苏地区';
                break;       
            case '喀什':
                $result = '喀什地区';
                break;  
           case '和田':
                $result = '和田地区';
                break;  
           case '伊犁':
                $result = '伊犁哈萨克自治州';
                break;  
           case '塔城':
                $result = '塔城地区';
                break;  
           case '阿勒泰':
                $result = '阿勒泰地区';
                break;  
           case '恩施':
                $result = '恩施土家族苗族自治州';
                break;  
           case '湘西':
                $result = '湘西土家族苗族自治州';
                break;   
           case '阿坝':
                $result = '阿坝藏族羌族自治州';
                break;   
           case '甘孜':
                $result = '甘孜藏族自治州';
                break;   
           case '凉山':
                $result = '凉山彝族自治州';
                break;   
           case '黔东':
                $result = '黔东南苗族侗族自治州';
                break;   
           case '黔南':
                $result = '黔南布依族苗族自治州';
                break;   
           case '楚雄':
                $result = '楚雄彝族自治州';
                break;     
           case '红河':
                $result = '红河哈尼族彝族自治州';
                break;     
           case '文山':
                $result = '文山壮族苗族自治州';
                break;     
           case '西双版纳':
                $result = '西双版纳傣族自治州';
                break;     
           case '大理':
                $result = '大理白族自治州';
                break;     
           case '德宏':
                $result = '德宏傣族景颇族自治州';
                break;     
           case '怒江':
                $result = '怒江傈僳族自治州';
                break;     
           case '迪庆':
                $result = '迪庆藏族自治州';
                break;     
           case '锡林郭勒':
                $result = '锡林郭勒盟';
                break;   
           case '阿拉善':
                $result = '阿拉善盟';
                break;   
           case '延边':
                $result = '延边朝鲜族自治州';
                break;   
           case '大兴安岭':
                $result = '大兴安岭地区';
                break;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
            default:
                $result = $city.'市';//初始化
                break;
        }
        //若为区则不拼接 '市'字
        if(strpos($city, '区')){
            $result = $city;
        }
        return $result;
    }



    /**
     * @todo   RequestPayment   接收打款结果
     * @param  $w_id            提现ID
     * @param  $result          返回的处理结果(xml)
     * @param  $error_msg       成功或失败原因
     */
    public function RequestPayment(){
        $w_id = I('w_id');//提现ID
        $result = I('result');//付款结果
        $error_code = I('error_code');        
        $error_msg = I('error_msg');//付款成功或失败原因(文字提示)
        $biz_no = I('biz_no');//批次号 
        $where['id'] = $w_id;        
        if(!empty($result) && !empty($error_msg)){
            if($error_code == '0000'){
                $alert_text = '打款成功';
            }else{
                $alert_text = $error_msg;
            }            
            $save_data['payment_result'] = $result;
            $save_data['biz_no'] = $biz_no;
            $save_data['error_code'] = $error_code;
            $this->db->where($where)->save($save_data);
            $uid = $this->db->where($where)->getField('uid');
            $user_name = M('web_user')->where('id='.$uid)->getField('user_name');
            //写入动作表
            $remember_sql = $this->db->getLastSql();            
            $msg_data['title'] = '操作打款信息';
            $msg_data['content'] = '【后台用户：'.session('username').'】对【'.$user_name.'】的提现进行了打款操作 状态：【'.$alert_text.'】。提现ID:'.$w_id;
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data); 
            echo "<span><center>".$alert_text."<br><a href='".U('Withdrawal/Withdrawal',array('upid'=>$w_id,'no_refer'=>1))."'>返回审核</a></center></span>";           
        }
       
    }


    /**
     * @todo   CheckBeforeResult       付款请求发送前记录
     * @param  $w_id                   提现ID
     * @param  $before_result          返回的处理结果(base64)
     */    
    public function CheckBeforeResult(){
        $w_id = I('w_id');
        $before_result = I('before_result');//付款请求前字符串
        if(!empty($w_id) && !empty($before_result)){
           $this->db->where('id='.$w_id)->setField('before_result',$before_result);
        }        
    }


	/**
	 * @todo  InfoDelete  		 删除贷款信息
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){		
		$del = $_POST['del'];
		$save['is_delete'] = 1;
		if($this->db->where('id in(' . implode(',',$del) . ')')->save($save)){
			$this->redirect('Index');
		}else{
			$this->error('信息删除失败!');
		}
    }


	/**
	 * @todo  Recommend   改变推荐状态
	 * @param $v          推荐状态
	 * @param $id         贷款信息主键
	 */
	
    public function Recommend(){
    	$v = $_GET['v'];
    	$id = $_GET['id'];    	        	
		$update = M('loan')->where('id='.$id)->setField('recommend',$v);    				
		if($update !== false){
			$this->redirect('index');
		}else{
			$this->error('参数错误');
		}
    }


}
?>