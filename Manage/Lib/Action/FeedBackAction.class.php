<?php
/**
 * @todo            意见反馈列表模块
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2015-3-4
 */
class FeedBackAction extends CommonAction {

    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        parent::_initialize();
        $this->page_size = 25;
        $this->db = M('feed_back');
        
    }


	/**
	 * @todo  Index  		 意见反馈列表视图
	 */
    public function Index(){
    	import('ORG.Util.Page');	
        $uid = I('uid');
        if(!empty($uid))
            $where['uid'] = $uid;							
		//分页
		$count = $this->db->where($where)->count();		
		$page = new Page($count,$this->page_size,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
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
				}
			}			
			$info[$key] = $value;
		}	
        unset($uids);	
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }



    /**
     * @todo  IsCheck   审核视图
     */
    public function IsCheck(){
        $upid = I('upid'); 
        $info = $this->db->where('id='.$upid)->find();                   
        $info['user_name'] = M('web_user')->where('id='.$info['uid'])->getField('user_name');//用户名获取
        $this->info = $info;
        $this->refer = $_SERVER['HTTP_REFERER'];//来源页地址
        $this->upid = $upid;//主键ID
        $this->display();
    }

    /**
     * @todo   DoIsCheck    审核处理
     * @param  $upid         主键
     */
    public function DoIsCheck(){        
        $refer = I('refer');//来源页地址
    	$upid = I('upid');//反馈ID
        $is_check = I('is_check');//审核状态
        $is_read = I('is_read');//阅读状态
        $remark = I('remark');//管理员回复
        $web_user_db = M('web_user');
        $score = I('score');//获得奖励积分
        $uid = $this->db->where('id='.$upid)->getField('uid'); 
        if($is_check != ''){   
            $save_data['is_check'] = $is_check;//审核状态
        }
        if($is_read != ''){   
            $save_data['is_read'] = $is_read;//阅读状态
        }            
        $save_data['check_time'] = NOW_TIME;//审核时间     
        $save_data['score'] = $score;//奖励积分
        $save_data['remark'] = $remark;//管理员回复
        $old_res = $this->db->where('id='.$upid)->find(); 
    	$update = $this->db->where('id='.$upid)->save($save_data);        
        $remember_sql = $this->db->getlastsql();        
        //echo $old_res['is_check'];die;       
        if($update !== false){ 
            //如果已读
            if($is_read == 1 && $old_res['is_read'] == 0 && !empty($remark)){ 
                if(!empty($remark))
                    $remark = ' 管理员回复：'.$remark;
                if(!empty($score)){
                    update_score($uid,$score);//给予此会员积分奖励
                    //记录积分操作日志
                    $score_log_data[0]['uid'] = $uid;
                    $score_log_data[0]['lid'] = '';
                    $score_log_data[0]['invested_id'] = '';
                    $score_log_data[0]['score'] = $score;
                    $score_log_data[0]['type'] = 7;//交易状态：意见奖励
                    $score_log_data[0]['pay_status'] = 1;//收支状态为收入
                    //读取此会员当前剩余积分
                    $now_user_score = $web_user_db->where('id='.$uid)->getField('score');
                    $score_log_data[0]['detail'] = '意见反馈审核通过：奖励积分为'.$score.'分。当前剩余积分：'.$now_user_score.'分'.$remark;//明细
                    $score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
                    insert_score_log($score_log_data); //插入积分记录表              
                }
            }
            //记录后台操作            
            $msg_data['title'] = '审核反馈意见成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】审核 意见反馈信息。';
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);             
        }
        $this->success('审核成功',$refer);    	
    }

	/**
	 * @todo  InfoDelete  		 删除意见反馈
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
}
?>