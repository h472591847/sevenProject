<?php
/**
 * @todo            群发短信功能
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2015-1-28
 */
class ManySmsSendAction extends CommonAction {
    
    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        parent::_initialize();
        $this->page_size = 25;     
        $this->db = M('many_sms_send');
        $this->user_id = session(C('USER_AUTH_KEY'));        
    }

    //短信群发视图
    public function Index(){
        import('ORG.Util.Page');        
        $web_user_db = M('web_user');
        $user_db = M('user');        
        //分页
        $count = $this->db->where($where)->count();       
        $page = new Page($count,$this->page_size);
        $limit = $page->firstRow . ',' . $page->listRows;
        $list = $this->db->order('id desc')->limit($limit)->select();        
        $web_user_ids = array();
        $user_ids = array();
        foreach ($list as $key => $value) {
            $web_user_ids[] = $value['web_user_id'];//接收人ID集合            
            $user_ids[] = $value['user_id'];
        }        
        //读取操作人信息
        $user_where['id'] = array('IN',$user_ids);
        $user_res = $user_db->where($user_where)->select();        
        //读取目标会员信息
        $web_user_where['id'] = array('IN',$web_user_ids);
        $web_user_res = $web_user_db->where($web_user_where)->select();
        //整理信息压入记录集
        foreach ($list as $key => $value) {
            if(is_array($web_user_res)){
                foreach ($web_user_res as $k => $v) {
                    if($v['id'] == $value['web_user_id']){
                        $value['user_name'] = $v['user_name'];
                    }
                }
            }
            if(is_array($user_res)){
                foreach ($user_res as $uk => $uv) {
                    if($uv['id'] == $value['user_id']){
                        $value['username'] = $uv['username'];
                    }
                }
            }
        $list[$key] = $value;
        }         
        unset($web_user_ids,$user_ids);//释放数组   
        $this->list = $list;
        $this->page = $page->show();//分页        
        $this->display();
    }



    //发送短信视图
    public function Send(){
        $web_user_db = M('web_user');
        $refer = $_SERVER['HTTP_REFERER'];//读取来路地址          
        $upid = I('upid',0,'intval');                
        $this->web_user_list = $web_user_db->where('is_effect=1')->select();//获取所有有效的前台会员        
        $this->upid = $upid;
        $this->refer = urlencode($refer);//来路地址传递到模版        
        $this->display();
    }


    /**
     * @todo   SendHandle  处理群发短信
     */
    public  function SendHandle(){
        $web_user_db = M('web_user');
        $user_db = M('user');     
        if(!IS_POST){
            $this->error('提交方式错误');
        }
        //过滤重复提交
        if(!$this->db->autoCheckToken($_POST)){
            $this->error('表单令牌验证错误');
        }
        $data = $_POST;        
        $phones = array();
        $phone_ar =  array();        
        if($data['web_user_id'] == 0){
        //若未选择目的号码，则发送给所有前台有效会员            
            $web_user_res = $web_user_db->where('is_effect=1')->select();        
            if($web_user_res){
                $data['dest'] = '全部会员用户';
                foreach ($web_user_res as $key => $value) {
					if($value['phone']){
						$phones[] = $value['phone'];
					}
                }
                if(is_array($phones)){                    
                    /*处理手机号，每100个手机号分为一组，压入数组$phone_ar*/
                    $i = -1;
                    $x = -1;                    
                    foreach($phones as $phone_v){  
                        $i++;                                                                      
                        if($i % 100 == 0){
                            $x++;
                        }
                        $phone_ar[$x][$i] = $phone_v;
                    }  
                    if(is_array($phone_ar)){
                        //循环发送短信                        
                        foreach ($phone_ar as $key => $value) {                            
                            $piecs = implode($value, ',');
                            send_sms($piecs,$data['content'],'',2);//按","分批发送短信
                        }
                    }
                }
            }
        }else{
        //否则发送给指定用户
            $phone = $web_user_db->where('id='.$data['web_user_id'])->getField('phone'); 
            $data['dest'] = $phone;            
            send_sms($phone,$data['content'],'',2);
        }    
        $data['user_id'] = $this->user_id;
        $data['create_time'] = NOW_TIME;
        //插入群发短信记录表                  
        $insert = $this->db->add($data);
        unset($phones,$phone_ar);//释放数组
        if($insert !== false){
            //写入动作表
            $remember_sql = $this->db->getLastSql();                                    
            $msg_data['title'] = '群发短信成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】群发短信成功。';
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);                  
            $this->success('短信发送成功');
        }else{
            $this->error('数据错误');
        }

    }


    /**
     * @todo  InfoDelete         删除信息
     * @param $del      array    要删除的主键ID
     */
    public function InfoDelete(){
        $db = M('many_sms_send');              
        $del = $_POST['del'];
        if($db->where('id in(' . implode(',',$del) . ')')->delete()){
            $this->success('信息删除成功!',U('ManySmsSend/Index'));
        }else{
            $this->error('信息删除失败!');
        }
    }
}
?>