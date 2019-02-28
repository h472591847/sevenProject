<?php
/**
 * @todo            会员列表管理
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-10-24
 */
class MemberAction extends CommonAction {


    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        parent::_initialize();
        $this->page_size = 25;     
        $this->db = M('web_user');
    }	

	//信息列表视图
    public function Index(){
    	import('ORG.Util.Page');								
		$pid = I('get.pid');	
        $user_name = I('user_name');
        $user_id = I('user_id');
        $real_name = I('real_name');
        $phone = I('phone');
        $parent_status = I('parent_status');
        $p_user_name = I('p_user_name');
        $money_start = I('money_start');
        $money_end = I('money_end');
        $lock_money_start = I('lock_money_start');
        $lock_money_end = I('lock_money_end');

        $bonus_money_start = I('bonus_money_start');        
        $bonus_money_end = I('bonus_money_end');
        $score_start = I('score_start');
        $score_end = I('score_end');
        $login_ip = I('login_ip');

        $k_start_time = I('k_start_time');
        $k_end_time = I('k_end_time'); 
        $e_start_time = I('e_start_time');
        $e_end_time = I('e_end_time');                
        //向分页传递参数   
        $parameter = array(
            'pid'=>$pid,
            'user_name'=>$user_name,
            'user_id'=>$user_id,
            'real_name'=>$real_name,
            'phone'=>$phone,
            'parent_status'=>$parent_status,
            'p_user_name'=>$p_user_name,
            'money_start'=>$money_start,
            'money_end'=>$money_end,
            'lock_money_start'=>$lock_money_start,
            'lock_money_end'=>$lock_money_end,
            'bonus_money_start'=>$bonus_money_start,
            'bonus_money_end'=>$bonus_money_end,
            'score_start'=>$score_start,
            'score_end'=>$score_end,
            'login_ip'=>$login_ip,            
            'k_start_time'=>$k_start_time,
            'k_end_time'=>$k_end_time,
            'e_start_time'=>$e_start_time,
            'e_end_time'=>$e_end_time
        );   
        $k_start_time = strtotime($k_start_time); //登录时间开始段 转换时间戳     
        $k_end_time = strtotime($k_end_time);//登录时间结束段 转换时间戳                                 
        $e_start_time = strtotime($e_start_time); //创建时间开始段 转换时间戳     
        $e_end_time = strtotime($e_end_time);//创建时间结束段 转换时间戳      
        //按条件查询开始
        if(!empty($pid))
            $where['pid'] = $pid;
        if(!empty($user_name))
            $where['user_name'] = $user_name;
        if($user_id != '')
            $where['id'] = $user_id;
        if(!empty($real_name))
            $where['real_name'] = $real_name;
        if(!empty($phone))
            $where['phone'] = $phone;   
        if($parent_status == '0')
            $where['pid'] = 0;
        if($parent_status == 1)
            $where['pid'] = array('NEQ',0);        
        //根据推荐人帐号 查找该用户的所有下级用户
        if(!empty($p_user_name)){
            $parent_where['user_name'] = $p_user_name;
            $parent_res = $this->db->where($parent_where)->select();            
            if($parent_res){
                $prt_ids = array();
                foreach ($parent_res as $key => $value) {
                    $prt_ids[] = $value['id'];
                }
                $where['pid'] = array('IN',$prt_ids);                
            }
        }
        if(!empty($money_start))
            $where['money'] = array('EGT',$money_start);
        if(!empty($money_end))
            $where['money'] = array('ELT',$money_end);
        if(!empty($money_start) && !empty($money_end))
            $where['money'] = array(array('EGT',$money_start),array('ELT',$money_end),'AND'); 
        //按冻结金额
        if(!empty($lock_money_start))
            $where['lock_money'] = array('EGT',$lock_money_start);
        if(!empty($lock_money_end))
            $where['lock_money'] = array('ELT',$lock_money_end);
        if(!empty($lock_money_start) && !empty($lock_money_end))
            $where['lock_money'] = array(array('EGT',$lock_money_start),array('ELT',$lock_money_end),'AND');                           
        //按奖励金额
        if(!empty($bonus_money_start))
            $where['bonus_money'] = array('EGT',$bonus_money_start);
        if(!empty($bonus_money_end))
            $where['bonus_money'] = array('ELT',$bonus_money_end);
        if(!empty($bonus_money_start) && !empty($bonus_money_end))
            $where['bonus_money'] = array(array('EGT',$bonus_money_start),array('ELT',$bonus_money_end),'AND');                                   
        //按积分
        if(!empty($score_start))
            $where['score'] = array('EGT',$score_start);
        if(!empty($score_end))
            $where['score'] = array('ELT',$score_end);
        if(!empty($score_start) && !empty($score_end))
            $where['score'] = array(array('EGT',$score_start),array('ELT',$score_end),'AND');                                                   
        //按最后登录IP
        if(!empty($login_ip))
            $where['login_ip'] = $login_ip;
        //按创建时间
        if(!empty($k_start_time))
            $where['create_time'] = array('EGT',$k_start_time);
        if(!empty($k_end_time))
            $where['create_time'] = array('ELT',$k_end_time);       
        if(!empty($k_start_time) && !empty($k_end_time))
            $where['create_time'] = array(array('EGT',$k_start_time),array('ELT',$k_end_time),'AND');                
        //按登录时间
        if(!empty($e_start_time))
            $where['login_time'] = array('EGT',$e_start_time);
        if(!empty($e_end_time))
            $where['login_time'] = array('ELT',$e_end_time);       
        if(!empty($e_start_time) && !empty($e_end_time))
            $where['login_time'] = array(array('EGT',$e_start_time),array('ELT',$e_end_time),'AND');   
        //分页
        $count = $this->db->where($where)->count();                
        $page = new Page($count,$this->page_size,$parameter);
        $limit = $page->firstRow . ',' . $page->listRows;
		//结果数据
		$info = $this->db->where($where)->order('create_time DESC,id ASC')->limit($limit)->select();		                                        
        $user_not_where['id'] = array('NOT IN','102,103');
        $user_count = $this->db->where($user_not_where)->count();
        $this->user_count = $user_count;//会员总数传输到模版        
        $member_res = $this->db->where($user_not_where)->select();//会员表全部记录                
        $this->sum_money = $this->db->field('sum(money) as sum_money,sum(bonus_money) as sum_bonus_money')->where($user_not_where)->find(); //用户余额统计
        $this->sum_lock_money = $this->db->field('sum(lock_money) as sum_lock_money')->where($user_not_where)->find();//用户冻结金额统计                
        $pids = array();//推荐人ID集合
        $uids = array();//会员ID集合
        //压缩推荐人id到数组
        foreach ($info as $key => $value) {            
            if($value['pid'] != 0){
                $pids[] = $value['pid'];
            }
        }  
        foreach ($member_res as $key => $value) {
            $uids[] = $value['id'];
        }
        
        $investe_where['uid'] = array('IN',$uids);
        $investe_where['status'] = 0;//状态为待收                
        $sum_invested = M('invested')->field("sum(repayment_amount) as sum_repay,SUM(amount) as sum_amount")->where($investe_where)->find();//获取用户在投本息                                             
        $this->sum_invested = $sum_invested;        
        $income_amount_where = array(
            'uid'=>array('IN',$uids),
            'type'=>4,//还款本金
            'pay_status'=>1//收入
        );        
        $log_amount = M('deal_log')->field('sum(money) as sum_income_amount')->where($income_amount_where)->find();        
        $income_accrual_where = array(
            'uid'=>array('IN',$uids),
            'type'=>5,//还款利息(不含管理费)
            'pay_status'=>1//收入
        );   
        $log_accrual = M('deal_log')->field('sum(money) as sum_income_accrual')->where($income_accrual_where)->find();    
        //赚得金额
        $this->log_accrual = $log_accrual;
        //统计回款总额 = 已收本金 + 已收利息 
        $this->count_sum_income = $log_amount['sum_income_amount'] + $log_accrual['sum_income_accrual'];
        if($pids)
            $p_where['id'] = array('IN',$pids);  
        /*读取推荐人信息，将推荐人ID，推荐人帐号压入结果数据*/      
        $p_user_info = $this->db->field('id,user_name')->where($p_where)->select();                        
        foreach ($info as $key => $value) {
            foreach ($p_user_info as $k => $v) {  
                if($v['id'] == $value['pid']){              
                    $value['parent_user_name'] = $v['user_name'];                
                }
            }
            $info[$key] = $value;
        }
        $this->page_param = $parameter;//将分页参数传递到模版
        unset($pids,$prt_ids,$uids);
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }


	/**
	 * @todo  Member 		修改/添加视图
	 * @param $upid     用于分辨修改/添加功能的主键ID
	 */
	public function Member(){				
		$upid = I('upid','','intval');        
        $user_info =  $this->db->where('id='.$upid)->find();  
        $bank_res = M('bank')->where('uid='.$user_info['id'].' AND is_default=1')->find();//读取用户银行卡信息
        $user_info['is_bank'] = (!$bank_res)?0:1;//返回用户银行卡绑定状态：0未绑定 1已绑定
        $user_info['deal_statistics'] = deal_statistics($user_info['id']);//统计信息
        //回款总额
        $user_info['deal_statistics']['sum_income_count'] = $user_info['deal_statistics']['sum_income_amount'] + $user_info['deal_statistics']['sum_income_accrual'];                
        $this->user_info = $user_info;
        $this->bank_res = $bank_res;
		$this->display();
    }


	/**
	 * @todo  MemberHandle   贷款信息表单处理
	 */
	public function MemberHandle(){
		
		$date = $_POST;
		$date['start_time'] = strtotime($date['start_time']);//开始招标时间
		$date['bad_time'] = strtotime($date['bad_time']);//流标时间
		$date['repay_confirm_time'] = strtotime($date['repay_confirm_time']);//还款确认时间
		$upid = I('upid','','intval');
		if(!empty($upid)){
			$date['update_time'] = NOW_TIME;
		}else{
			$date['create_time'] = NOW_TIME;
		}		
		$date['update_by'] = session('username');	
		if(empty($upid)){
			if($this->db->add($date)){
				$this->success('信息添加成功!',U('Member/Member'));
			}else{
				$this->error('信息添加失败!');
			}
		}else{

			//若合同协议有改动，则删除旧的图片文件
			 $res = $this->db->field("contract_agreement,field_visit")->where("id = $upid")->find();
			 if(!empty($date['contract_agreement']) && $date['contract_agreement'] != $res['contract_agreement']){
			 	$ar_contract = explode("\r\n", $res['contract_agreement']);			 	
			 	//删除旧图片
			 	foreach ($ar_contract as $value) {
			 		unlink(DocumentRoot().$value);			 		
			 	}
			 }
			
			//若实地考察有改动，则删除旧的图片文件
			 if(!empty($date['field_visit']) && $date['field_visit'] != $res['field_visit']){
			 	$ar_visit = explode("\r\n", $res['field_visit']);			 	
			 	//删除旧图片
			 	foreach ($ar_visit as $value) {
			 		unlink(DocumentRoot().$value);			 		
			 	}
			 }			 
			if($this->db->where("id = $upid")->save($date)){
				$this->success('信息修改成功!',U('Member/Index'));
			}else{
				$this->error('信息修改失败!');
			}
		}
    }


	/**
	 * @todo  MemberSort   更新排序
	 * @param $id    Array        
	 *        		 key对应ID value对应排序值   
	 */
	public function MemberSort(){
		
		foreach($_POST as $id => $value){
			$this->db->where("id = $id")->setfield('Member_order',$value);
		}
		$this->redirect('Member/Index');
    }


	/**
	 * @todo  InfoDelete  		 删除贷款信息
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){
		
		$del_imgs = array();
		$del = $_POST['del'];
		foreach($del as $id){
			$info = $this->db->field('contract_agreement,field_visit,Member_description,company_info')->where("id = $id")->find();			
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
			if(strstr($info['Member_description'],'<img')){
				$get_img_str=explode('<img',stripslashes($info['Member_description']));
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
		if($this->db->where('id in(' . implode(',',$del) . ')')->delete()){
			//删除图片
			foreach($del_imgs as $img_del){
				unlink(DocumentRoot() . $img_del);
			}
			unset($del_imgs);
			$this->success('信息删除成功!',U('Member/Index'));
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
		$update = $this->db->where('id='.$id)->setField('recommend',$v);    				
		if($update !== false){
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
		$update = $this->db->where('id='.$id)->setField('is_effect',$v);    						
        $user_name = $this->db->where('id='.$id)->getField('user_name');
		if($update !== false){
            //写入动作表
            $remember_sql = $this->db->getLastSql();                       
            $msg_data['title'] = '操作会员为'.$txt.'状态成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】对会员【'.$user_name.'】操作为【'.$txt.'】状态。ID:'.$id;
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
     * @todo  LockMoney    冻结金额视图
     */
    public function LockMoney(){
    	$upid = I('upid');
  		$user_info = $this->db->where("id=".$upid)->find();  		
  		$this->user_info = $user_info;
  		$this->upid = $upid;
    	$this->display('lock_money');
    }



    /**
     * @todo  LockMoney    冻结金额处理
     * @param lock_money   冻结操作金额  正数转入到冻结金额，负数恢复到会员余额
     * @param content      操作说明
     * @param upid         用户ID
     */
    public function DoLockMoney(){
    	$lock_money = I('lock_money');
    	$content = I("content");
    	$upid = I("upid");
        $user_info = $this->db->where("id=".$upid)->find();
    	if($lock_money>0){
    		$data['lock_money'] = array('exp','lock_money+'.$lock_money);
    		$data['money'] = array('exp','money-'.$lock_money);
    		$txt = '冻结资金'.$lock_money.'元';
    	}

    	if($lock_money<0){            
            if(abs($lock_money)>$user_info['lock_money']){
                $this->error('恢复金额不能大于已有冻结金额');
            }
    		$data['lock_money'] = array('exp','lock_money+'.$lock_money);
    		$data['money'] = array('exp','money-'.$lock_money);    		
    		$txt = '取出冻结资金'.abs($lock_money).'元';
    	}                
    	$update = $this->db->where("id=".$upid)->save($data); 
        $content = !empty($content)?'说明：'.$content:'';
    	if($update!==false){
            $remember_sql = $this->db->getLastSql();//记录操作SQL
            $view = M('user')->where('id='.session(C('USER_AUTH_KEY')))->find();//读取后台操作人数据            
    		if($lock_money>0){
	            /*写入交易记录表 */
	            $deal_log_data[0]['uid'] = $upid;
	            $deal_log_data[0]['lid'] = '';
	            $deal_log_data[0]['invested_id'] = '';
	            $deal_log_data[0]['money'] = $lock_money;//冻结资金
	            $deal_log_data[0]['type'] = 8;//交易状态为 系统操作
	            $deal_log_data[0]['pay_status'] = 2;//收支状态:支出
                /*记录当前会员资金信息*/
                $now_user_money = $user_info['money'] - $lock_money;
                $now_user_lock_money = $user_info['lock_money'] + $lock_money;
                $now_user_bonus_money = $user_info['bonus_money'];                
	            $deal_log_data[0]['detail'] = '您的账户被冻结资金:'.$lock_money.'元。'.$content.'当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
	            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间       			
	            //写入动作表      
	            $msg_data['title'] = '冻结会员资金成功';
	            $msg_data['content'] = '【后台用户：'.$view['username'].'】冻结了【前台会员：'.$user_info['user_name'].'】资产'.format_price($lock_money).'元 ID:'.$upid;
	            $msg_data['user_id'] = $view['id'];
	            $msg_data['create_time'] = NOW_TIME;
	            $msg_data['type'] = 2;//信息操作
	            $msg_data['web_status'] = 2;//状态：后台操作
	            $msg_data['sql'] = $remember_sql;	            
    		}

    		if($lock_money<0){
	            /*写入交易记录表 */
	            $deal_log_data[0]['uid'] = $upid;
	            $deal_log_data[0]['lid'] = '';
	            $deal_log_data[0]['invested_id'] = '';
	            $deal_log_data[0]['money'] = $lock_money;//冻结资金
	            $deal_log_data[0]['type'] = 8;//交易状态: 系统操作
	            $deal_log_data[0]['pay_status'] = 1;//收支状态:收入
                /*记录当前会员资金信息*/
                $now_user_money = $user_info['money'] - $lock_money;
                $now_user_lock_money = $user_info['lock_money'] + $lock_money;
                $now_user_bonus_money = $user_info['bonus_money']; 
	            $deal_log_data[0]['detail'] = '您的冻结资金:'.abs($lock_money).'元 已恢复。'.$content.'当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
	            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间       			
	            //写入动作表     
	            $msg_data['title'] = '恢复会员冻结资金成功';
	            $msg_data['content'] = '【后台用户：'.$view['username'].'】恢复了【前台会员：'.$user_info['user_name'].'】的冻结资金'.format_price(abs($lock_money)).'元 ID:'.$upid;
	            $msg_data['user_id'] = $view['id'];
	            $msg_data['create_time'] = NOW_TIME;
	            $msg_data['type'] = 2;//信息操作
	            $msg_data['web_status'] = 2;//状态：后台操作
	            $msg_data['sql'] = $remember_sql;	
    		}
    		insert_deal_log($deal_log_data);
			insert_msg_box($msg_data);   	                		
    		$this->success($txt.'成功');
    	}else{
    		$this->error('数据错误');
    	}    	

    }


    /**
     * @todo  Account    账户操作视图
     * @param $upid      用户ID
     */
    public function Account(){
        $upid = I('upid');
        $user_info = $this->db->where("id=".$upid)->find();         
        $this->user_info = $user_info;
        $this->upid = $upid;
        $this->display('account');
    }


    /**
     * @todo  DoAccount         账户操作处理
     * @param $upid             用户ID
     * @param $money            操作金额
     * @param $bonus_money      操作奖励金额
     * @param content           操作说明
     */
    public function DoAccount(){
        $upid = I('upid');
        $money = I('post.money');
        $bonus_money = I('post.bonus_money');        
        $score = I('post.score'); 
        $content = I('post.content');
        $content = !empty($content)?'说明：'.$content:'';        
        $user_info = $this->db->where("id=".$upid)->find();
        $txt = '';        
        //用户余额操作处理
        if($money>0){
            $data['money'] = array('exp','money+'.$money);            
            $txt = '本次充值金额：'.$money.'元<br>';
        }
        if($money<0){            
            if(abs($money)>$user_info['money']){
                $this->error('扣款金额不能大于当前用户余额');
            }            
            $data['money'] = array('exp','money+'.$money);         
            $txt = '本次扣款金额：'.abs($money).'元<br>';
        }  

        //奖励金额操作处理
        if($bonus_money>0){
            $data['bonus_money'] = array('exp','bonus_money+'.$bonus_money);            
            $txt .= '本次获得奖励金额：'.$bonus_money.'元<br>';
        }
        if($bonus_money<0){            
            if(abs($bonus_money)>$user_info['bonus_money']){
                $this->error('扣款金额不能大于当前用户余额');
            }            
            $data['bonus_money'] = array('exp','bonus_money+'.$bonus_money);         
            $txt .= '本次扣除奖励金额：'.abs($bonus_money).'元<br>';
        }
        //用户积分操作处理
        if($score>0){
            $data['score'] = array('exp','score+'.$score);            
            $txt .= '本次充值积分：'.$score.'<br>';
        }        
        if($score<0){            
            if(abs($score) > $user_info['score']){
                $this->error('扣除积分不能大于当前剩余积分');
            }            
            $data['score'] = array('exp','score+'.$score);         
            $txt .= '本次扣除积分：'.abs($score).'<br>';
        }  
             
        if(!empty($money) || !empty($bonus_money) || !empty($score)){
            if(is_array($data)){
                $update = $this->db->where("id=".$upid)->save($data); 
                if($update!==false){
                    $remember_sql = $this->db->getLastSql();//记录操作SQL
                    $view = M('user')->where('id='.session(C('USER_AUTH_KEY')))->find();//读取后台操作人数据
                    if($money>0){
                        /*写入交易记录表 */
                        $deal_log_data[0]['uid'] = $upid;
                        $deal_log_data[0]['lid'] = '';
                        $deal_log_data[0]['invested_id'] = '';
                        $deal_log_data[0]['money'] = $money;//操作金额
                        $deal_log_data[0]['type'] = 8;//交易状态为 系统操作
                        $deal_log_data[0]['pay_status'] = 1;//收支状态:收入
                        /*当前用户资金信息记录*/
                        $now_user_money = $user_info['money'] + $money;//当前剩余金额
                        $now_user_lock_money = $user_info['lock_money'];//当前剩余冻结金额
                        $now_user_bonus_money = $user_info['bonus_money'];//当前剩余奖励金额
                        $deal_log_data[0]['detail'] = '系统为您充值:'.$money.'元。'.$content.'当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
                        $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间                  
                        //写入动作表     
                        $msg_data['title'] = '充值会员资金成功';
                        $msg_data['content'] = '【后台用户：'.$view['username'].'】为【前台会员：'.$user_info['user_name'].'】充值'.format_price($money).'元 ID:'.$upid;
                        $msg_data['user_id'] = $view['id'];
                        $msg_data['create_time'] = NOW_TIME;
                        $msg_data['type'] = 2;//信息操作
                        $msg_data['web_status'] = 2;//状态：后台操作
                        $msg_data['sql'] = $remember_sql;     
                        insert_deal_log($deal_log_data);
                        insert_msg_box($msg_data);                                     
                    }

                    if($money<0){
                        /*写入交易记录表 */
                        $deal_log_data[0]['uid'] = $upid;
                        $deal_log_data[0]['lid'] = '';
                        $deal_log_data[0]['invested_id'] = '';
                        $deal_log_data[0]['money'] = $money;//操作金额
                        $deal_log_data[0]['type'] = 8;//交易状态为 系统操作
                        $deal_log_data[0]['pay_status'] = 2;//收支状态:支出
                        /*当前用户资金信息记录*/
                        $now_user_money = $user_info['money'] + $money;//当前剩余金额
                        $now_user_lock_money = $user_info['lock_money'];//当前剩余冻结金额
                        $now_user_bonus_money = $user_info['bonus_money'];//当前剩余奖励金额                        
                        $deal_log_data[0]['detail'] = '系统扣除您的资金:'.abs($money).'元。'.$content.'当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
                        $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间                  
                        //写入动作表         
                        $msg_data['title'] = '扣除会员资金成功';
                        $msg_data['content'] = '【后台用户：'.$view['username'].'】扣除了【前台会员：'.$user_info['user_name'].'】资金'.format_price(abs($money)).'元 ID:'.$upid;
                        $msg_data['user_id'] = $view['id'];
                        $msg_data['create_time'] = NOW_TIME;
                        $msg_data['type'] = 2;//信息操作
                        $msg_data['web_status'] = 2;//状态：后台操作
                        $msg_data['sql'] = $remember_sql;   
                        insert_deal_log($deal_log_data);
                        insert_msg_box($msg_data);                           
                    } 

                    if($bonus_money>0){
                        /*写入交易记录表 */
                        $deal_log_data[0]['uid'] = $upid;
                        $deal_log_data[0]['lid'] = '';
                        $deal_log_data[0]['invested_id'] = '';
                        $deal_log_data[0]['money'] = $bonus_money;//奖励金额
                        $deal_log_data[0]['type'] = 8;//交易状态为 系统操作
                        $deal_log_data[0]['pay_status'] = 1;//收支状态:收入
                        /*当前用户资金信息记录*/
                        $now_user_money = $user_info['money'];//当前剩余金额
                        $now_user_lock_money = $user_info['lock_money'];//当前剩余冻结金额
                        $now_user_bonus_money = $user_info['bonus_money'] + $bonus_money;//当前剩余奖励金额                           
                        $deal_log_data[0]['detail'] = '获得系统奖励:'.$bonus_money.'元。'.$content.'当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
                        $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间                  
                        //写入动作表         
                        $msg_data['title'] = '发放会员奖励金额成功';
                        $msg_data['content'] = '【后台用户：'.$view['username'].'】为【前台会员：'.$user_info['user_name'].'】发放奖励金额'.format_price($bonus_money).'元 ID:'.$upid;
                        $msg_data['user_id'] = $view['id'];
                        $msg_data['create_time'] = NOW_TIME;
                        $msg_data['type'] = 2;//信息操作
                        $msg_data['web_status'] = 2;//状态：后台操作
                        $msg_data['sql'] = $remember_sql;     
                        insert_deal_log($deal_log_data);
                        insert_msg_box($msg_data);                                     
                    }

                    if($bonus_money<0){
                        /*写入交易记录表 */
                        $deal_log_data[0]['uid'] = $upid;
                        $deal_log_data[0]['lid'] = '';
                        $deal_log_data[0]['invested_id'] = '';
                        $deal_log_data[0]['money'] = $bonus_money;//扣除的奖励金额
                        $deal_log_data[0]['type'] = 5;//交易状态为 系统操作
                        $deal_log_data[0]['pay_status'] = 2;//收支状态:支出
                        /*当前用户资金信息记录*/
                        $now_user_money = $user_info['money'];//当前剩余金额
                        $now_user_lock_money = $user_info['lock_money'];//当前剩余冻结金额
                        $now_user_bonus_money = $user_info['bonus_money'] + $bonus_money;//当前剩余奖励金额                           
                        $deal_log_data[0]['detail'] = '系统扣除奖励金额:'.abs($bonus_money).'元。'.$content.'当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
                        $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间                  
                        //写入动作表          
                        $msg_data['title'] = '扣除会员奖励金额成功';
                        $msg_data['content'] = '【后台用户：'.$view['username'].'】扣除了【前台会员：'.$user_info['user_name'].'】的奖励金额'.format_price(abs($bonus_money)).'元 ID:'.$upid;
                        $msg_data['user_id'] = $view['id'];
                        $msg_data['create_time'] = NOW_TIME;
                        $msg_data['type'] = 2;//信息操作
                        $msg_data['web_status'] = 2;//状态：后台操作
                        $msg_data['sql'] = $remember_sql;   
                        insert_deal_log($deal_log_data);
                        insert_msg_box($msg_data);                           
                    }    

                    if($score>0){
                        /*写入交易记录表 */
                        $score_log_data[0]['uid'] = $upid;
                        $score_log_data[0]['lid'] = '';
                        $score_log_data[0]['invested_id'] = '';
                        $score_log_data[0]['score'] = $score;//积分
                        $score_log_data[0]['type'] = 5;//交易状态为 系统操作
                        $score_log_data[0]['pay_status'] = 1;//收支状态:收入
                        /*当前用户积分信息记录*/
                        $now_user_score = $user_info['score'] + $score;//当前剩余积分
                        $score_log_data[0]['detail'] = '系统奖励积分:'.$score.'。'.$content.'当前剩余积分：'.$now_user_score;//明细
                        $score_log_data[0]['create_time'] = NOW_TIME;//交易时间                  
                        //写入动作表         
                        $msg_data['title'] = '发放会员积分成功';
                        $msg_data['content'] = '【后台用户：'.$view['username'].'】为【前台会员：'.$user_info['user_name'].'】发放会员积分'.$score.'分 ID:'.$upid;
                        $msg_data['user_id'] = $view['id'];
                        $msg_data['create_time'] = NOW_TIME;
                        $msg_data['type'] = 2;//信息操作
                        $msg_data['web_status'] = 2;//状态：后台操作
                        $msg_data['sql'] = $remember_sql;     
                        insert_score_log($score_log_data);
                        insert_msg_box($msg_data);                                     
                    }  
                    if($score<0){
                        /*写入交易记录表 */
                        $score_log_data[0]['uid'] = $upid;
                        $score_log_data[0]['lid'] = '';
                        $score_log_data[0]['invested_id'] = '';
                        $score_log_data[0]['score'] = abs($score);//积分
                        $score_log_data[0]['type'] = 5;//交易状态为 系统操作
                        $score_log_data[0]['pay_status'] = 2;//收支状态:收入
                        /*当前用户积分信息记录*/                        
                        $now_user_score = $user_info['score'] - abs($score);//当前剩余积分
                        $score_log_data[0]['detail'] = '系统扣除积分:'.abs($score).'。'.$content.'当前剩余积分：'.$now_user_score;//明细
                        $score_log_data[0]['create_time'] = NOW_TIME;//交易时间                  
                        //写入动作表         
                        $msg_data['title'] = '扣除会员积分成功';
                        $msg_data['content'] = '【后台用户：'.$view['username'].'】为【前台会员：'.$user_info['user_name'].'】扣除会员积分'.abs($score).'分 ID:'.$upid;
                        $msg_data['user_id'] = $view['id'];
                        $msg_data['create_time'] = NOW_TIME;
                        $msg_data['type'] = 2;//信息操作
                        $msg_data['web_status'] = 2;//状态：后台操作
                        $msg_data['sql'] = $remember_sql;     
                        insert_score_log($score_log_data);
                        insert_msg_box($msg_data);                                     
                    }                                                                            
                    $this->success($txt);
                }else{
                    $this->error('数据错误');
                }
            }
        }else{
            $this->success('账户资金无修改');        
        }        
        
    }


    /**
     * @todo   BankInfo   修改银行卡信息
     * @param  $upid      用户ID 关联tp_bank表uid
     */
    public function BankInfo(){
        $upid = I('upid');//用户ID 关联tp_bank表uid
        if(empty($upid)){
            $this->error('非法参数');
        }        
        $bank = M('bank')->where('uid='.$upid .' AND is_default=1')->find();        
        if(!$bank){
            $this->error('该用户尚未绑定银行卡信息',U('Member/Index'));
        }        
        $this->bank = $bank;
        $this->upid = $upid;
        $this->display('bank_info');        
    }


    /**
     * @todo   DoBankInfo   修改银行卡信息处理
     */
    public function DoBankInfo(){        
        $data = $_POST;   
        $upid = I('upid');//用户ID 关联tp_bank表uid 
        //判断同一银行卡号不可绑定多账户
        $begin = M('bank')->where("bank_number='".$data['bank_number']."' AND not uid=".$upid)->count();
        if($begin > 0)
            $this->error('同一银行卡不可绑定多个会员账户,请更换后重试');        
        $update = M('bank')->where("uid=".$upid)->save($data);
        $remember_sql = M('bank')->getLastSql();//记录操作SQL
        $user_info = M('web_user')->where('id='.$upid)->find();
        if($update !== false){
            $view = M('user')->where('id='.session(C('USER_AUTH_KEY')))->find();//读取后台操作人数据            
            //写入动作表         
            $msg_data['title'] = '修改银行卡信息成功';
            $msg_data['content'] = '【后台用户：'.$view['username'].'】为【前台会员：'.$user_info['user_name'].'】【修改银行卡】成功 修改的银行卡ID:'.$upid;
            $msg_data['user_id'] = $view['id'];
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;   
            insert_msg_box($msg_data);                
            $this->success('修改成功');
        }else{
            $this->error('数据错误');
        }
    }



    /**
     * @todo   MemberInfo   修改会员信息
     * @param  $upid      用户ID 关联tp_bank表uid
     */
    public function MemberInfo(){
        $upid = I('upid');//用户ID 关联tp_bank表uid
        if(empty($upid)){
            $this->error('非法参数');
        }
        $user_info = M('web_user')->where('id='.$upid)->find();        
        if(!$user_info){
            $this->error('该用户不存在');
        }   
        $this->user_info = $user_info;     
        $this->upid = $upid;
        $this->display('member_info');        
    }


    /**
     * @todo   DoMemberInfo   修改会员信息处理
     */
    public function DoMemberInfo(){        
        $data = $_POST;   
        $upid = I('upid');//用户ID 关联tp_bank表uid 
        $update = M('web_user')->where("id=".$upid)->save($data);
        $remember_sql = M('web_user')->getLastSql();//记录操作SQL
        $user_info = M('web_user')->where('id='.$upid)->find();
        if($update !== false){
            $view = M('user')->where('id='.session(C('USER_AUTH_KEY')))->find();//读取后台操作人数据            
            //写入动作表         
            $msg_data['title'] = '修改会员信息成功';
            $msg_data['content'] = '【后台用户：'.$view['username'].'】为【前台会员：'.$user_info['user_name'].'】【修改会员信息】成功 会员ID:'.$upid;
            $msg_data['user_id'] = $view['id'];
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;   
            insert_msg_box($msg_data);                
            $this->success('修改成功');
        }else{
            $this->error('数据错误');
        }
    }
}
?>