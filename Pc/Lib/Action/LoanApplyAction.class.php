<?php 
/**
 * @todo            前台我要贷款控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-30
 */
class LoanApplyAction extends Action{


    /**
     * @todo       index        贷款申请视图
     */
    public function index(){
       session('book_action','loan_apply');//防捆绑软件恶意提交       
       $this->display();
    }

    /**
     * @todo   LoanHandle    贷款申请处理
     */
    public function LoanHandle(){        
        $loan_apply_db = M('loan_apply');
        $verify = I('post.verify');
        $data = $_POST;
        $data['create_time'] = NOW_TIME;
        if(empty($data['real_name']))
            $this->error('请输入借款姓名');
        if(empty($data['phone']))
            $this->error('请输入手机号码');
        if(empty($data['money']))
            $this->error('请输入借款金额');
        if(empty($data['province']) || empty($data['city']))
            $this->error('请选择所在地区');
        if(empty($data['purpose']))
            $this->error('请输入借款用途');  
        $data['address'] = $data['province'].' - '.$data['city'];                                           
        //判断验证码
        if(session('verify') != $verify)
            $this->error('验证码输入错误');        
        //令牌验证避免重复提交
        if(!$loan_apply_db->autoCheckToken($_POST))
            $this->error('令牌验证错误');
        if(IS_POST){
            if(session('book_action') != 'loan_apply')
                $this->error('非法操作');
            $insert = $loan_apply_db->add($data);
            if($insert !== false){
                session('book_action','');//防捆绑软件恶意提交
                 /*发送工作人员提醒消息*/
                 $alert_data['title'] = '申请贷款提醒';
                 $alert_data['content'] = '网站有用户今天申请了贷款，请前往后台查看';
                 $alert_data['status'] = 1;
                 $alert_data['create_time'] = time();
                 insert_system_alert($alert_data);
                 send_sms('13904314687',$alert_data['content']);//手机提醒
                 send_mail('2956000@qq.com,1526877756@qq.com','金投资', iconv('UTF-8','GB2312',$alert_data['title']), iconv('UTF-8','GB2312',$alert_data['content']), "HTML");//邮件提醒                                      
                $this->success('贷款申请提交成功，资料审核后，我们的客服会与您取得联系。');
            }else{
                $this->error('数据错误');
            }
        }else{
            $this->error('请勿非法提交');
        }
    }
}