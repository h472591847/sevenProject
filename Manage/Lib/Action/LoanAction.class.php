<?php
/**
 * @todo            贷款管理控制器
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-10-8
 */
class LoanAction extends CommonAction {

    /* 赋值Common中的权限处理*/
    //自动运行函数
    public function _initialize(){      
        parent::_initialize();
        $this->phone_title = '金 投 资';
    }   
	//信息列表视图
    public function Index(){
        $loan_id = trim(I('loan_id'));//项目ID
        $loan_title = trim(I('loan_title'));//项目名称
        $class_id = trim(I('class_id'));//产品类别ID
        $deal_status = trim(I('deal_status'));//项目状态
        $loan_deadline_type = trim(I('loan_deadline_type'));//借款期限状态
        $k_start_time = trim(I('k_start_time'));//发布日期开始
        $k_end_time = trim(I('k_end_time')); // 发布日期结束
        $e_start_time = trim(I('e_start_time'));
        $e_end_time = trim(I('e_end_time'));
        $change_type = trim(I('change_type'));  
        //向分页传递参数   
        $parameter = array(
            'change_type'=>$change_type,
            'loan_id'=>$loan_id,
            'loan_title'=>$loan_title,
            'class_id'=>$class_id,
            'deal_status'=>$deal_status,
            'loan_deadline_type'=>$loan_deadline_type,
            'k_start_time'=>$k_start_time,
            'k_end_time'=>$k_end_time,
            'e_start_time'=>$e_start_time,
            'e_end_time'=>$e_end_time
        );   
        $k_start_time = strtotime($k_start_time); //发布日期开始段 转换时间戳     
        $k_end_time = strtotime($k_end_time);//发布日期结束段 转换时间戳                                 
        $e_start_time = strtotime($e_start_time); //到期日期开始段 转换时间戳     
        $e_end_time = strtotime($e_end_time);//到期日期结束段 转换时间戳                                         
        $product_class_ids = array(1,2,3,4);
        $where['class_id'] = 0;
        if($change_type == 'product')
            $where['class_id'] = array('IN',$product_class_ids);
        //按条件筛选
        if(!empty($loan_id))
           $where['id'] = $loan_id;
        if(!empty($loan_title))
           $where['loan_title'] = array('LIKE','%'.$loan_title.'%'); 
        if(!empty($class_id))
           $where['class_id'] = $class_id;         
        if(!empty($deal_status))
            $where['deal_status'] = $deal_status;
        if($loan_deadline_type != '')
            $where['loan_deadline_type'] = $loan_deadline_type;
        if(!empty($k_start_time))
            $where['create_time'] = array('EGT',$k_start_time);
        if(!empty($k_end_time))
            $where['create_time'] = array('ELT',$k_end_time);       
        if(!empty($k_start_time) && !empty($k_end_time))
            $where['create_time'] = array(array('EGT',$k_start_time),array('ELT',$k_end_time),'AND');                        
    	import('ORG.Util.Page');								
		$db = M('loan');	        
        /*按到期时间区间筛选 读取数据*/	
        if(!empty($e_start_time))
            $where['expire_time'] = array('EGT',$e_start_time);
        if(!empty($e_end_time))
            $where['expire_time'] = array('ELT',$e_end_time);       
        if(!empty($e_start_time) && !empty($e_end_time))
            $where['expire_time'] = array(array('EGT',$e_start_time),array('ELT',$e_end_time),'AND');                                       
		//分页
		$count = $db->where($where)->count();	        	                      
		$page = new Page($count,25,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
		//结束
		$info = $db->where($where)->order('loan_order DESC,create_time DESC,id ASC')->limit($limit)->select();                
        /*若到期时间区间筛选条件存在 则读取拼接的sql和拼接的统计sql*/  
        $product_class_db = M('product_class');
        $product_class_arr = $product_class_db->order('class_order DESC,id ASC')->select();         
        $this->product_class_arr = $product_class_arr;//产品类别              
        $this->page_param = $parameter;//将分页参数传递到模版
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }


	/**
	 * @todo  Loan 		修改/添加视图
	 * @param $upid     用于分辨修改/添加功能的主键ID
	 */
	public function Loan(){		
        $refer = $_SERVER['HTTP_REFERER'];                
		$upid = I('upid','','intval');
		//显示操作文字
		if(!empty($upid)){
			$display_txt = '修改';
			$edit_info = M('loan')->where("id = $upid")->find();
            $edit_info['company_material_view'] = explode("\r\n", $edit_info['company_material']);//企业材料
            if(count(array_filter($edit_info['company_material_view'])) == 0)
                $edit_info['company_material_view'] = '';//如果没图片，赋空值
            $edit_info['pawn_action_view'] = explode("\r\n", $edit_info['pawn_action']);//实地考察            
            if(count(array_filter($edit_info['pawn_action_view'])) == 0)
                $edit_info['pawn_action_view'] = '';//如果没图片，赋空值            
			$edit_info['contract_agreement_view'] = explode("\r\n", $edit_info['contract_agreement']);//合同协议
            if(count(array_filter($edit_info['contract_agreement_view'])) == 0)
                $edit_info['contract_agreement_view'] = '';//如果没图片，赋空值            
			$edit_info['field_visit_view'] = explode("\r\n", $edit_info['field_visit']);//实地考察
            if(count(array_filter($edit_info['field_visit_view'])) == 0)
                $edit_info['field_visit_view'] = '';//如果没图片，赋空值            
			$this->edit_info = $edit_info;			
		}else{
			$display_txt = '添加';
		}
        $product_class_db = M('product_class');
        $product_class_arr = $product_class_db->order('class_order DESC,id ASC')->select();               
        $this->product_class_arr = $product_class_arr;//产品类别
		$this->upid = $upid;
		$this->display_txt = $display_txt;
		$this->cate = $cate;
		$this->refer = $refer;//来路记录
        $this->display();
    }


	/**
	 * @todo  LoanHandle   贷款信息表单处理
	 */
	public function LoanHandle(){ 
        $refer = I('refer');
		$db = M('loan');
		$date = $_POST;        
		$date['start_time'] = strtotime($date['start_time']);//开始招标时间
		$date['bad_time'] = strtotime($date['bad_time']);//流标时间
        $date['success_time'] = strtotime($date['success_time']);//满标时间
		$date['repay_confirm_time'] = strtotime($date['repay_confirm_time']);//还款确认时间
		$upid = I('upid','','intval');
		$date['recommend'] = (!empty($date['recommend']))?1:0;//判断推荐状态		
        if(empty($date['loan_money']))
            $this->error('请输入借款金额');
        if(empty($date['min_loan_money']))
            $this->error('最小投资金额');
        if(empty($date['max_loan_money']))
            $this->error('最高投资金额');
        if(empty($date['loan_rate']))
            $this->error('请输入年利率');
        if(empty($date['loan_deadline']))
            $this->error('请输入借款期限');  
        if(empty($date['end_time']))
            $this->error('请输入筹标期限'); 
        if(empty($date['start_time']))
            $this->error('请输入开始时间');                                                                    
		if(!empty($upid)){			
			$date['update_time'] = NOW_TIME;
		}else{
			$date['create_time'] = NOW_TIME;
		}		
		$date['update_by'] = session('username');	
		if(empty($upid)){
			$insert = $db->add($date);
			if($insert !== false){
                //写入动作表
                $remember_sql = $db->getLastSql();
                
                $view_find = $db->where("id = $insert")->find();                  
                $msg_data['title'] = '添加项目成功';
                $msg_data['content'] = '【后台用户：'.session('username').'】添加【项目信息:'.$view_find['loan_title'].'】成功。项目ID:'.$insert;
                $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
                $msg_data['create_time'] = NOW_TIME;
                $msg_data['type'] = 2;//信息操作
                $msg_data['web_status'] = 2;//状态：后台操作
                $msg_data['sql'] = $remember_sql;
                insert_msg_box($msg_data);  				
				$this->success('信息添加成功!',U('Loan/Loan'));
			}else{
				$this->error('信息添加失败!');
			}
		}else{
            //读取项目详细信息
            $view_find = $db->where("id = $upid")->find(); 
            //若投资中项目需修改总借款金额，且是未上线项目。则同步修改剩余借款金额
            if($date['deal_status'] == 1 && $view_find['is_effect'] == 0 && $view_find['surplus_money'] != $date['loan_money']){
                $date['surplus_money'] = $date['loan_money'];
            }
            //若合同协议有改动，则删除旧的图片文件             
            $old_contract = explode("\r\n", $view_find['contract_agreement']);//旧图片组               
            $new_contract = explode("\r\n", $date['contract_agreement']);//新图片组               
            $diff_contract = array_diff($old_contract, $new_contract);//被删掉的旧图片                    
            if(count($diff_contract) > 0){              
                //删除旧图片
                foreach ($diff_contract as $value) {
                    unlink(DocumentRoot().$value);                  
                }
            }
            
            //若实地考察有改动，则删除旧的图片文件
            $old_visit = explode("\r\n", $view_find['field_visit']);//旧图片组               
            $new_visit = explode("\r\n", $date['field_visit']);//新图片组               
            $diff_visit = array_diff($old_visit, $new_visit);//被删掉的旧图片               
            if(count($diff_visit) > 0){             
                //删除旧图片
                foreach ($diff_visit as $value) {
                    unlink(DocumentRoot().$value);                  
                }
            } 
            //查询在此之前有没有确认过还款
            $old_repay_confirm_time = $db->where('id = '.$upid)->getField('repay_confirm_time'); 
            /*到期时间计算*/            
            if(!empty($date['repay_confirm_time'])){                
                if($date['loan_deadline_type'] == 1){
                    //借款时间期限按天计算
                    $date['expire_time'] = $date['repay_confirm_time'] + ($date['loan_deadline'] * 24 * 3600);
                }else{
                    //借款时间期限按月计算
                    $date['expire_time'] = strtotime(date('Y-m-d H:i:s',$date['repay_confirm_time'])." + ".$date['loan_deadline']." month");
                }
            }                                
            //查询上次的项目信息
            $old_loan = $db->where('id='.$upid)->find();               
			if($db->where("id=".$upid)->save($date) !== false){
                //初始化投资金额奖励积分配置   
                $invest_section1_min = (int)C('invest_section1_min');//区间1最小投资金额                                                              
                $invest_section1_max = (int)C('invest_section1_max');//区间1最大投资金额  
                $invest_section1_score = (int)C('invest_section1_score'); //区间1投资奖励积分
                $invest_section2_min = (int)C('invest_section2_min');//区间2最小投资金额
                $invest_section2_max = (int)C('invest_section2_max');//区间2最大投资金额
                $invest_section2_score = (int)C('invest_section2_score'); //区间2投资奖励积分
                $invest_section3 = (int)C('invest_section3');//区间3投资金额
                $invest_section3_score = (int)C('invest_section3_score'); //区间3投资奖励积分                               
                //若修改成功，并且修改状态为还款中，且上次状态不为还款中则执行奖励
                if($date['deal_status'] == 4 && $old_loan['deal_status'] != 4){
                    //投资信息表中取投资用户ID
                    $invested_ar = M('invested')->where('lid='.$upid)->order('id asc')->select();
                    $invested_uids = array();//投资人ID集合
                   // $invested_phones = array();//投资人电话号集合
                    foreach ($invested_ar as $key => $value) {
                        $return_invest_score = 0;//初始化应奖励积分
                        $invested_uids[] = $value['uid'];
                        //+++++++++++++++2015-04-09 15:11:11 update_by:liying++++++++ 
                        $value['amount'] = (int)$value['amount'];
                        //设置活动结束时间 2015-04-30 23:59:00截至
                        if($value['create_time'] <= strtotime('2015-04-30 23:59:00')){
                            /*投资金额区间，奖励相应积分*/
                            if($value['amount'] >= $invest_section1_min && $value['amount'] < $invest_section1_max){                                
                                $return_invest_score = $invest_section1_score;
                            }elseif($value['amount'] >= $invest_section2_min && $value['amount'] < $invest_section2_max){
                                $return_invest_score = $invest_section2_score;
                            }elseif($value['amount'] >= $invest_section3){
                                $return_invest_score = $invest_section3_score;
                            }                        
                            if(!empty($return_invest_score) && !empty($value['uid'])){
                                update_score($value['uid'],$return_invest_score);//执行投资返积分                                               
                                $now_user_score = M('web_user')->where('id='.$value['uid'])->getField('score');
                                $score_log_data[0]['uid'] = $value['uid'];
                                $score_log_data[0]['lid'] = $value['lid'];
                                $score_log_data[0]['invested_id'] = $value['id'];
                                $score_log_data[0]['score'] = $return_invest_score;//投资奖励积分
                                $score_log_data[0]['type'] = 4;//交易状态：投资
                                $score_log_data[0]['pay_status'] = 1;//收支状态为收入
                                $score_log_data[0]['detail'] = '投资奖励：奖励积分为'.$return_invest_score.'分。当前剩余积分:'.$now_user_score.'分';//明细
                                $score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
                                insert_score_log($score_log_data); //插入积分记录表                                                           
                            }
                        }
                    }                    
                    //读取投资用户手机号，并压入数组
                    if(is_array($invested_uids)){
                      $u_where['id'] = array('IN',$invested_uids);
                      $u_info = M('web_user')->where($u_where)->select();
                      //+++++++++++++2015-03-27 15:43:49 update_by:liying
                      // foreach ($u_info as $key => $value) {
                      //     $invested_phones[] = $value['phone'];//将投资者手机号压入数组
                      // }                     
                    }  

                    /*
                     *每次投资给上级用户年化收益提点                   
                     *第一次投资给上级用户积分奖励
                    */
                    foreach ($invested_ar as $key => $value) {
                        if(is_array($u_info)){
                            foreach ($u_info as $u_k => $u_v) {
                              if($u_v['id'] == $value['uid']){
                                if(!empty($u_v['pid'])){
                                    $earning_money_rate = C('earning_money_rate');//年化收益奖励的比率
                                    if($view_find['loan_deadline_type'] == 1){
                                        //年化收益 = 投资金额 / 365 * 投资天数 * 上级提点(百分比)
                                        $year_earning_money = $value['amount'] / 365 * $view_find['loan_deadline'] * $earning_money_rate;
                                    }else{
                                        //年化收益 = 投资金额 / 12 * 投资月数 * 上级提点(百分比)
                                        $year_earning_money = $value['amount'] / 12 * $view_find['loan_deadline'] * $earning_money_rate;
                                    }  
                                    $year_earning_money = (string) sprintf("%.2f", substr(sprintf("%.3f", $year_earning_money), 0, -1));       
                                    $earning_score = C('earning_score'); //初始化奖励积分 
                                    $recharge_log_db = M('recharge_log');//充值表
                                    $user_recharge_log = $recharge_log_db->where('user_id='.$u_v['id'])->find();
                                    if($user_recharge_log){
                                        $old_user_res = M('web_user')->where('id='.$u_v['pid'])->find();//上级用户信息
                                        //查询由此用户带来的积分邀请奖励记录
                                        $score_log_where['uid'] = $u_v['pid'];//受奖励用户ID
                                        $score_log_where['from_uid'] = $u_v['id'];//来源用户ID
                                        $score_log_where['type'] = 2;//交易状态：邀请注册
                                        $score_log_db = M('score_log');//积分日志表                                        
                                        $score_log_res = $score_log_db->where($score_log_where)->find();                                                                               
                                        //若此用户的上级没接受过来自这个投资用户的注册奖励,则执行奖励
                                        if(!$score_log_res){                                                                                  
                                            update_score($u_v['pid'],$earning_score);//奖励上级用户积分
                                            $now_user_score = $old_user_res['score'] + $earning_score;//上级用户当前剩余积分                                            
                                            $score_log_data[0]['from_uid'] = $u_v['id'];//来源用户ID
                                            $score_log_data[0]['uid'] = $u_v['pid'];
                                            $score_log_data[0]['lid'] = $value['lid'];
                                            $score_log_data[0]['invested_id'] = $value['id'];
                                            $score_log_data[0]['score'] = $earning_score;
                                            $score_log_data[0]['type'] = 2;//交易状态：邀请注册
                                            $score_log_data[0]['pay_status'] = 1;//收支状态为收入
                                            $score_log_data[0]['detail'] = '邀请奖励：奖励积分为'.$earning_score.'分。当前剩余积分:'.$now_user_score.'分';//明细
                                            $score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
                                            insert_score_log($score_log_data); //插入积分记录表                                        
                                        }
										if($year_earning_money > 0.01){ //奖励金额小于0.01时不做奖励收益和记录                                                                                
											M('web_user')->where('id='.$u_v['pid'])->setInc('bonus_money',$year_earning_money); //上级用户获得奖励收益                                                                       
											$now_user_bonus_money = $old_user_res['bonus_money'] + $year_earning_money;//上级用户当前剩余奖励金额
											$deal_log_data[0]['from_uid'] = $u_v['id'];//来源用户ID
                                            $deal_log_data[0]['uid'] = $u_v['pid'];//用户ID
											$deal_log_data[0]['lid'] = $value['lid'];
											$deal_log_data[0]['invested_id'] = $value['id'];
											$deal_log_data[0]['money'] = $year_earning_money;
											$deal_log_data[0]['type'] = 9;//交易状态：邀请奖励
											$deal_log_data[0]['pay_status'] = 1;//收支状态为收入
											$deal_log_data[0]['detail'] = '邀请奖励：奖励金额为'.$year_earning_money.'元。当前剩余金额:'.$old_user_res['money'].'元,剩余冻结金额:'.$old_user_res['lock_money'].'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
											$deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
											insert_deal_log($deal_log_data);//插入资金日志表                                       
										}
                                    }
                                }
                              }
                            }                     
                        }                                                  

                    }                    
                    //+++++++++++++2015-03-27 15:43:49 update_by:liying                    
                    // /*处理投资者手机号，每100个手机号分为一组，压入数组$phone_ar*/
                    // $i = -1;
                    // $x = -1;
                    // $phone_ar = array();
                    // foreach($invested_phones as $phone_v){  
                    //     $i++;                                                                      
                    //     if($i % 100 == 0){
                    //         $x++;
                    //     }
                    //     $phone_ar[$x][$i] = $phone_v;
                    // }  
                    // //开始还款短信提醒内容
                    // $msg_str = "尊敬的".$this->phone_title."用户,您投资的项目:".$view_find['loan_title'].',于'.date('Y-m-d',$date['repay_confirm_time']).'开始还款啦';
                    // // 给投资人发送开始还款通知,判断是第一次操作<还款中>发送短信。
                    // if(is_array($phone_ar) && empty($old_repay_confirm_time)){
                    //     //循环发送开始还款通知短信                        
                    //     foreach ($phone_ar as $key => $value) {
                    //         $piecs = implode($value, ',');
                    //         send_sms($piecs,$msg_str,2);//按","批量发送短信
                    //     }
                    // }  
                    //2015-03-27 15:43:49 update_by:liying +++++++++++++ 
                }
                //unset($phone_ar,$invested_uids,$invested_phones);//释放数组
                unset($invested_uids);
                //写入动作表
                $remember_sql = $db->getLastSql();                                    
                $msg_data['title'] = '修改项目成功';
                switch ($date['deal_status']) {
                    case 1:
                        $act = '进行中';
                        break;          
                    case 2:
                        $act = '满标';
                        break;          
                    case 3:
                        $act = '流标';
                        break;          
                    case 4:
                        $act = '还款中';
                        break;          
                    case 5:
                        $act = '已还清';
                        break;                                                                                                                    
                }
                $msg_data['content'] = '【后台用户：'.session('username').'】修改【项目信息：'.$view_find['loan_title'].'】成功。项目状态:【'.$act.'】项目ID:'.$upid;
                $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
                $msg_data['create_time'] = NOW_TIME;
                $msg_data['type'] = 2;//信息操作
                $msg_data['web_status'] = 2;//状态：后台操作
                $msg_data['sql'] = $remember_sql;
                insert_msg_box($msg_data);  				
				$this->success('信息修改成功!',$refer);
			}else{
				$this->error('信息修改失败!');
			}
		}
    }



    /**
     * @todo      repay_confirm_sms  发送开始计息短信
     * @param     $upid              项目ID 
     * @author    liying            <472591847@qq.com>
     * @since     2015-03-28 16:36:36
     */
    public function repay_confirm_sms(){        
        $upid = I('upid',0,'intval');
        $db = M('loan');
        $is_send = $db->where('id = '.$upid)->getField('is_send');                       
        //查询投资
        $view_find = $db->where('id='.$upid)->find();        
        $invested_where['lid'] = $upid;
        $invested_where['uid'] = array('NOT IN',array('102'));//过滤系统帐号xiaoxiao 的投资
        $invested_ar = M('invested')->where($invested_where)->order('id asc')->select();
        $invested_uids = array();//投资人ID集合
        $invested_phones = array();//投资人电话号集合
        foreach ($invested_ar as $key => $value) {
          $invested_uids[] = $value['uid'];
        }        
        //读取投资用户手机号，并压入数组
        if(is_array($invested_uids)){
          $u_where['id'] = array('IN',$invested_uids);
          $u_info = M('web_user')->where($u_where)->select();
          foreach ($u_info as $key => $value) {
              $invested_phones[] = $value['phone'];//将投资者手机号压入数组
          }                     
        }
        /*处理投资者手机号，每100个手机号分为一组，压入数组$phone_ar*/
        $i = -1;
        $x = -1;
        $phone_ar = array();
        foreach($invested_phones as $phone_v){  
            $i++;                                                                      
            if($i % 100 == 0){
                $x++;
            }
            $phone_ar[$x][$i] = $phone_v;
        }          
        //开始还款短信提醒内容
        $msg_str = "尊敬的".$this->phone_title."用户,您投资的项目:".$view_find['loan_title'].',于'.date('Y-m-d',$view_find['repay_confirm_time']).'开始还款啦';
        $remember_str = '用户手机号:';
        // 给投资人发送开始还款通知,判断是第一次操作<还款中>发送短信。
        if(is_array($phone_ar) && $is_send == 0){                    
            //循环发送开始还款通知短信                        
            foreach ($phone_ar as $key => $value) {
                $piecs = implode($value, ',');
                $remember_str .= $piecs;//拼接记录字符串
                send_sms($piecs,$msg_str,2);//按","批量发送短信
            }            
            $msg_data['title'] = '发送开始计息短信';
            $msg_data['content'] = '【后台用户：'.session('username').'】为项目【'.$view_find['loan_title'].'】发送开始计息短信成功。';
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_str;
            insert_msg_box($msg_data);           
            $db->where('id='.$upid)->setField('is_send',1);//修改发送短信状态变为已发送确认还款短信       
            $this->success('发送开始计息短信成功!',U('Loan/Index'));            
        }else{
            $this->error('已经发送过短信的项目，请勿重新发送');
        }    
        unset($phone_ar,$invested_uids,$invested_phones);//释放数组                                         
    }


	/**
	 * @todo  LoanSort   更新排序
	 * @param $id    Array        
	 *        		 key对应ID value对应排序值   
	 */
	public function LoanSort(){
		$db = M('loan');
		foreach($_POST as $id => $value){
			$db->where("id = $id")->setfield('loan_order',$value);
		}
		$this->redirect('Loan/Index');
    }


	/**
	 * @todo  InfoDelete  		 删除贷款信息
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){
		$db = M('loan');
		$del_imgs = array();
		$del = $_POST['del'];
		foreach($del as $id){
			$info = $db->field('company_material,pawn_action,contract_agreement,field_visit,loan_description,company_info')->where("id = $id")->find();			
            //单图
            if(!empty($info['img'])){
                $del_imgs[] = $info['img'];
            }            
            // 删除企业材料图片
            if(!empty($info['company_material'])){
                $img_arr_cailiao = explode("\r\n",$info['company_material']);
                foreach($img_arr_cailiao as $img_cailiao){
                        $del_imgs[] = $img_cailiao;
                }
            }

            // 删除抵押物实景图片
            if(!empty($info['pawn_action'])){
                $img_arr_dyw = explode("\r\n",$info['pawn_action']);
                foreach($img_arr_dyw as $img_dyw){
                        $del_imgs[] = $img_dyw;
                }
            }

			// 删除合同协议图片
			if(!empty($info['contract_agreement'])){
				$img_arr_hetong = explode("\r\n",$info['contract_agreement']);
				foreach($img_arr_hetong as $img_hetong){
						$del_imgs[] = $img_hetong;
				}
			}

			//删除实地考察图片
			if(!empty($info['field_visit'])){
				$img_arr_shidi = explode("\r\n",$info['field_visit']);
				foreach($img_arr_shidi as $img_shidi){
						$del_imgs[] = $img_shidi;
				}
			}

			//项目描述内容图
			if(strstr($info['loan_description'],'<img')){
				$get_img_str=explode('<img',stripslashes($info['loan_description']));
				foreach($get_img_str as $get_img){
					$i++;
					if($i > 1){
						$tag = ModStr(' ','>',$get_img);
						$img_des = ModStr('src="','"',$tag);
						if(!strstr($img_des,"http://")){
							$del_imgs[] = $img_des;
						}
					}
				}
			}			
			//企业信息内容图
			if(strstr($info['company_info'],'<img')){
				$get_img_str=explode('<img',stripslashes($info['company_info']));
				foreach($get_img_str as $get_img){
					$z++;
					if($z > 1){
						$tag = ModStr(' ','>',$get_img);
						$img = ModStr('src="','"',$tag);
						if(!strstr($img,"http://")){
							$del_imgs[] = $img;
						}
					}
				}
			}			
		}			
		if($db->where('id in(' . implode(',',$del) . ')')->delete()){
			//删除图片
			foreach($del_imgs as $img_del){
				unlink(DocumentRoot() . $img_del);
			}
			unset($del_imgs);
            //写入动作表
            $remember_sql = $db->getLastSql();
                       
            $msg_data['title'] = '删除项目成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】删除项目成功。';
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);  			
			$this->success('信息删除成功!',U('Loan/Index'));
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
    	$v = I('v',0,'intval');
    	$id = I('id',0,'intval');
        $txt = ($v == 0)?'取消推荐':'推荐';
		$update = M('loan')->where('id='.$id)->setField('recommend',$v);    				
        $loan_title = M('loan')->where('id='.$id)->getField('loan_title');
		if($update !== false){
            //写入动作表
            $remember_sql = M('loan')->getLastSql();                       
            $msg_data['title'] = $txt.'项目成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】对项目【'.$loan_title.'】操作为【'.$txt.'】。';
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);             
			$this->redirect('index');
		}else{
			$this->error('参数错误');
		}
    }


	/**
	 * @todo  IsEffect   改变有效状态
	 * @param $v          有效状态
	 * @param $id         贷款信息主键
	 */
	
    public function IsEffect(){
        $v = I('v',0,'intval');
        $id = I('id',0,'intval');  
        $txt = ($v == 0)?'无效':'有效';         	
		$update = M('loan')->where('id='.$id)->setField('is_effect',$v);    						
        $loan_title = M('loan')->where('id='.$id)->getField('loan_title');
		if($update !== false){
            //写入动作表
            $remember_sql = M('loan')->getLastSql();                       
            $msg_data['title'] = '操作项目为'.$txt.'状态成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】对项目【'.$loan_title.'】操作为【'.$txt.'】状态。项目ID:'.$id;
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql; 
            insert_msg_box($msg_data);             
			$this->redirect('index');
		}else{
			$this->error('参数错误');
		}
    }


	/**
	 * @todo    ReturnInvested   投资还款处理
	 */
	public function ReturnInvested(){
		$lid = I('lid',0,'intval'); //借款项目id
		$status = return_invested($lid); //还款函数
		if($status == 1){
			$this->error('非法操作!');
		}elseif($status == 2){
			$this->error('借款金额与投资金额不符,无法还款!');
		}elseif($status == 3){
			$this->error('还款失败');
		}elseif($status == 0){
			$this->success('还款完成!',U('Loan/Index'));
		}
	}

}
?>