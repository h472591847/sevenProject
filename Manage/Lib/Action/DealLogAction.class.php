<?php
/**
 * @todo            会员资金交易记录
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-11-5
 */
class DealLogAction extends CommonAction {


	//会员资金交易记录视图
    public function Index(){
    	import('ORG.Util.Page');								
		$db = M('deal_log');
        $uid = trim(I('uid'));
        $user_name = trim(I('user_name'));//用户名
        $money_start = trim(I('money_start'));
        $money_end = trim(I('money_end'));        
        $k_start_time = strpos(I('k_start_time'),'-')?strtotime(I('k_start_time')):I('k_start_time');//交易时间开始
        $k_end_time = strpos(I('k_end_time'),'-')?strtotime(I('k_end_time')):I('k_end_time');; // 交易时间结束	        
        $type = trim(I('type'));
        //向分页传递参数   
        $parameter = array(
            'uid'=>$uid,
            'user_name'=>$user_name,  
            'money_start'=>$money_start,
            'money_end'=>$money_end,                      
            'k_start_time'=>$k_start_time,
            'k_end_time'=>$k_end_time,
            'type'=>$type
        );   
        if(!empty($uid)){
            $where['uid'] = $uid;
        }	
		//按条件筛选
        if(!empty($user_name)){        	
        	$user_res = M('web_user')->where("user_name='$user_name'")->find();        	        	
        	if($user_res)
        	  $where['uid'] = $user_res['id'];        	        	
        }           
        if(!empty($k_start_time))
            $where['create_time'] = array('EGT',$k_start_time);
        if(!empty($k_end_time))
            $where['create_time'] = array('ELT',$k_end_time);       
        if(!empty($k_start_time) && !empty($k_end_time))
            $where['create_time'] = array(array('EGT',$k_start_time),array('ELT',$k_end_time),'AND');  	        				
        if(!empty($money_start))
            $where['money'] = array('EGT',$money_start);
        if(!empty($money_end))
            $where['money'] = array('ELT',$money_end);
        if(!empty($money_start) && !empty($money_end))
            $where['money'] = array(array('EGT',$money_start),array('ELT',$money_end),'AND');         
        if(!empty($type))        	
        	$where['type'] = $type;
		//分页
		$count = $db->where($where)->count();		
		$page = new Page($count,25,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
		//结束
		$info = $db->where($where)->order('create_time DESC,id ASC')->limit($limit)->order('create_time DESC,id DESC')->select();		
		$uids = array();
		foreach ($info as $key => $value) {
			$uids[] = $value['uid'];
		}		
		$user_where['id'] = array('IN',$uids);
		$user_info = M('web_user')->where($user_where)->select();
		foreach ($info as $key => $value) {
			foreach ($user_info as $k => $v) {
				if($v['id']==$value['uid']){
					$value['user_name'] = $v['user_name'];
				}
			}
          $value['money'] = abs($value['money']);
          $de_arr = explode('当',$value['detail']);
          //$de_arr = explode('。',$value['detail']);
          $value['detail'] = $de_arr[0];
          $re_arr = explode(',',$de_arr[1]);
          $value['remain_money'] = $re_arr[0];
          $value['remain_lock_money'] = $re_arr[1];
          $value['remain_bonus_money'] = $re_arr[2];
		  $info[$key] = $value;
		}
		unset($uids);
        $this->uid = $uid;
        $this->page_param = $parameter;
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }



    /**
     * @todo IsLabel  标记记录
     */
    public function IsLabel(){
        $db = M('deal_log');
        $refer = $_SERVER['HTTP_REFERER'];        
        $uid = I('get.uid');
        $id = I('get.id');        
        $is_label = I('get.is_label');
        $where['id'] = array('ELT',$id);
        $where['uid'] = $uid;
        $data['is_label'] = $is_label;
        $update = $db->where($where)->save($data);
        if($update !== false){
            //写入动作表
            $remember_sql = $db->getLastSql(); 
            $user_name = M('web_user')->where('id='.$uid)->getField('user_name');           
            $msg_data['title'] = '标记资金记录成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】对【'.$user_name.'】标记资金记录。记录ID:<='.$id;
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);        
            redirect($refer);    
        }else{
            $this->error('数据错误');
        }
    }


	/**
	 * @todo  InfoDelete  		 删除资金交易记录信息
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){
		$db = M('deal_log');		
		$del = $_POST['del'];
		if($db->where('id in(' . implode(',',$del) . ')')->delete()){
			$this->success('信息删除成功!',U('DealLog/Index'));
		}else{
			$this->error('信息删除失败!');
		}
    }
}
?>