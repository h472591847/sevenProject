<?php
/**
 * @todo            群发短信功能
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2015-1-28
 */
class TestSmsSendAction extends Action {
    
    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){        
        $this->page_size = 25;     
        $this->db = M('test_sms_send');
        $this->sms_pwd = md5('25918');      
    }

    //短信群发视图
    public function Index(){
        if(session('sms_pwd') != $this->sms_pwd){
            $this->redirect('VerifyPwd');exit();
        }        
        import('ORG.Util.Page');        
        //分页
        $count = $this->db->where($where)->count();       
        $page = new Page($count,$this->page_size);
        $limit = $page->firstRow . ',' . $page->listRows;
        $list = $this->db->order('id desc')->limit($limit)->select();                         
        $this->list = $list;
        $this->page = $page->show();//分页        
        $this->display();
    }


    public function VerifyPwd(){                      
        $this->display();
    }

    public function DoVerifyPwd(){
        $sms_pwd = I('sms_pwd');        
        if(md5($sms_pwd) != $this->sms_pwd)
            $this->error('密码输入错误');
        session('sms_pwd',md5($sms_pwd));
        $this->redirect('Send');
    }

    //发送短信视图
    public function Send(){
        if(session('sms_pwd') != $this->sms_pwd){
            $this->redirect('VerifyPwd');exit();
        }           
        $web_user_db = M('web_user');
        $refer = $_SERVER['HTTP_REFERER'];//读取来路地址          
        $upid = I('upid',0,'intval');                        
        $this->upid = $upid;
        $this->refer = urlencode($refer);//来路地址传递到模版        
        $this->display();
    }


    /**
     * @todo   SendHandle  处理群发短信
     */
    public  function SendHandle(){
        if(session('sms_pwd') != $this->sms_pwd){
            $this->redirect('VerifyPwd');exit();
        }           
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
        if(empty($data['dest']))
            $this->error('请输入目的号码');
        if(empty($data['content']))
            $this->error('请输入发送内容');        
        send_sms($data['dest'],$data['content'],'',2);
        $data['create_time'] = NOW_TIME;
        //插入群发短信记录表                  
        $insert = $this->db->add($data);        
        if($insert !== false){                
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
        if(session('sms_pwd') != $this->sms_pwd){
            $this->redirect('VerifyPwd');exit();
        }           
        // $db = M('many_sms_send');              
        // $del = $_POST['del'];
        // if($db->where('id in(' . implode(',',$del) . ')')->delete()){
        //     $this->success('信息删除成功!',U('ManySmsSend/Index'));
        // }else{
        //     $this->error('信息删除失败!');
        // }
    }
}
?>