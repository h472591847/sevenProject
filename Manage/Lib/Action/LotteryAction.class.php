<?php
/**
 * @todo            大转盘活动控制器
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2015-1-14
 */
class LotteryAction extends CommonAction {


	//列表视图
    public function Index(){
    	import('ORG.Util.Page');								
		$db = M('lt');		
		//分页
		$count = $db->where($where)->count();		
		$page = new Page($count,25);
		$limit = $page->firstRow . ',' . $page->listRows;
		//结束
		$info = $db->where($where)->order('create_time ASC,id ASC')->limit($limit)->select();			
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }


	/**
	 * @todo  InfoDelete  		 删除贷款申请信息
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){
		$db = M('lt');				
		$del = $_POST['del'];
		if($db->where('id in(' . implode(',',$del) . ')')->delete()){
			$this->success('信息删除成功!',U('Lottery/Index'));
		}else{
			$this->error('信息删除失败!');
		}
    }


    /**
     * @todo    LotterySet   大转盘设置视图
     */
    public function LotterySet(){
    	$upid = I('upid');
    	if(!$upid)
    		$this->error('参数错误');
    	$this->edit_info = M('lt')->where('id='.$upid)->find();
    	$this->upid = $upid;
    	$this->display();
    }


    /**
     * @todo    DoLotterySet  保存大转盘设置
     * @param   upid          被修改信息主键ID
     */
    public function DoLotterySet(){
    	$upid = I('upid');
    	$data = $_POST;
    	$db = M('lt');
    	if(!$upid)
    		$this->error('参数错误'); 
    	$update = $db->where('id='.$upid)->save($data);  	
    	if($update !== false){
            //写入动作表
            $remember_sql = $db->getLastSql();                                    
            $msg_data['title'] = '修改活动成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】修改大转盘设置成功。ID:'.$upid;
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);  		    		
    		$this->success('设置成功');
    	}else{
    		$this->error('数据错误');
    	}
    }


    /**
     * @todo    RecordIndex   大转盘中奖记录列表
     */
    public function RecordIndex(){
    	import('ORG.Util.Page');								
		$db = M('record');		
		$web_user_db = M('web_user');
		$lt_db = M('lt');
		//分页
		$count = $db->where($where)->count();		
		$page = new Page($count,25);
		$limit = $page->firstRow . ',' . $page->listRows;
		//结束
		$info = $db->where($where)->order('create_time DESC,id DESC')->limit($limit)->select();			
		foreach ($info as $key => $value) {
			$uids[] = $value['uid'];
			$prize_ids[] = $value['lt_prize'];
		}
		if(is_array($uids)){
			$user_where['id'] = array('IN',$uids);
			$user_res = $web_user_db->where($user_where)->select();
		}
		if(is_array($prize_ids)){
			$lt_where['id'] = array('IN',$prize_ids);
			$lt_res = $lt_db->where($lt_where)->select();
		}	

		foreach ($info as $key => $value) {
			foreach ($user_res as $u_k => $u_v) {
				if($u_v['id'] == $value['uid']){
					$value['user_name'] = $u_v['user_name'];
				}
			}
			foreach ($lt_res as $l_k => $l_v) {
				if($l_v['id'] == $value['lt_prize']){
					$value['prize_name'] = $l_v['lt_prize'];
				}
			}
			$info[$key] = $value;			
		}		
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }


	/**
	 * @todo  RecordDelete 		 删除贷款申请信息
	 * @param $del      array    要删除的主键ID
	 */
	public function RecordDelete(){
		$db = M('record');				
		$del = $_POST['del'];
		if($db->where('id in(' . implode(',',$del) . ')')->delete()){
			$this->success('信息删除成功!',U('Lottery/RecordIndex'));
		}else{
			$this->error('信息删除失败!');
		}
    }


    /**
     * @todo  CheckStatus  中奖处理状态
     * @param  $status     0未处理 1已处理
     * @param  $id         记录ID
     */
    public function CheckStatus(){
        $db = M('record');
        $status = I('get.status');
        $id = I('get.id');    
        $txt = ($status == 0)?'取消处理':'处理';    
        if($status != ''){
            $update = $db->where('id='.$id)->setField('status',$status);
            if($update !== false){
                //写入动作表
                $remember_sql = $db->getLastSql();
                $uid = $db->where('id='.$id)->getField('uid');
                $user_name = M('web_user')->where('id='.$uid)->getField('user_name');                       
                $msg_data['title'] = $txt.'中奖信息成功';
                $msg_data['content'] = '【后台用户：'.session('username').'】【'.$txt.'】用户【'.$user_name.'】的中奖信息 记录ID:'.$id;
                $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
                $msg_data['create_time'] = NOW_TIME;
                $msg_data['type'] = 2;//信息操作
                $msg_data['web_status'] = 2;//状态：后台操作
                $msg_data['sql'] = $remember_sql;
                insert_msg_box($msg_data);                  
                $this->redirect('RecordIndex');
            }else{
                $this->error('数据错误');    
            }
        }else{
            $this->error('参数错误');
        }
    }
}
?>