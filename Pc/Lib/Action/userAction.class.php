<?php 
/**
 * @todo            前台会员用户控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-10
 */
class userAction extends Action{

    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        $this->phone_title = "金 投 资";//手机短信显示的公司名称
        $this->page_size = 6;
        $this->uid = session("user_id");
        $this->user_db = M('web_user');            
    }



    /**
     * @todo 用户注册第一步视图
     */
    public function Register() {
        $pid = I('get.pid');        
        if($pid){
            $pid = getDecrypt($pid);
        }     
        if(!empty($pid)){
            $pid = rawurldecode($pid);       
            setcookie("pid",$pid);//记录上级ID 避免误点击别处，失去pid的情况
        }                
        //如果记录了pid 则pid重新赋值
        if($_COOKIE['pid']){
            $pid = (int)$_COOKIE['pid'];
        }                            
        $this->pid = $pid;//推广人ID                        
        $this->display("register");
    } 

       
    /**
     * @todo 用户注册验证第一步处理
     */
    public function DoRegister() {                             
        $user_name = I('post.user_name');//帐号
        $user_pwd = I('post.user_pwd');//密码
        $phone = I('post.phone');//手机号
        $email = I('post.email');//email地址
        $verify = I("post.verify"); //验证码        
        $phone_code = I("post.phone_code"); //手机验证码  
        $pid = I('pid'); //推广人ID                      
        $obj = M('web_user'); 
        if(empty($user_name))
           $this->error('用户名不能为空');
        if(empty($user_pwd))
           $this->error('密码不能为空');       
        if(empty($phone))
           $this->error('手机号码不能为空');
        // if(empty($email))
        //    $this->error('邮箱地址不能为空');  
        if(empty($verify))
           $this->error('验证码不能为空');                                                               
        if(strtolower($verify) != session('verify')) $this->error('验证码错误');//判断验证码        
        if(empty($phone_code))
           $this->error('手机验证码不能为空');                   
         /* 查看是否已存在此用户 */
        $rs_user_name = $obj->where(array('user_name' => $user_name))->find();
        if ($rs_user_name) {
            $this->error('该帐号已注册，请重新填写帐号');
        }                
        /* 查看是否已存在此用户 */
        $rs_phone = $obj->where(array('phone' => $phone))->find();
        if ($rs_phone) {
            $this->error('该手机号已注册，请重新填写手机号码');
        }          
        /* 查看是否已存在此用户 */
        // $rs_email = $obj->where(array('email' => $email))->find();
        // if ($rs_email) {
        //     $this->error('该邮箱已注册，请重新填写邮箱');
        // }

        /* 验证码处理 查询未使用过的验证码 */
        $where_code = array('phone' => $phone, 'status' => 0, 'code_number' => $phone_code);
        $obj_code = M('temp_code')->field(array('id', 'code_number'))->where($where_code)->find();

        /* 如果验证码和数据库中的密码不匹配,返回错误 */
        if ($obj_code) {
            if ($phone_code != $obj_code['code_number']) {
                $this->error('手机验证码输入错误,请重新输入');   
            }               
        }else{
            $this->error('手机验证码不存在,请重新输入');
        }        
        $data = $_POST;
        $data['user_pwd'] = md5($user_pwd);
        $code_number = $phone_code; 
        $data['referer'] = $GLOBALS['referer'];//更新来路地址            
        $data['code_number'] = $code_number;        
        $data['is_effect'] = 1;//有效状态:有效       
        $data['create_time'] = NOW_TIME;
        // $data['bonus_money'] = 50;//注册奖励金额50元                     
        $data['bonus_money'] = 25;//注册奖励金额25元                             
        $data['pid'] = $pid;//推广人ID(上级用户ID)        
        $data['is_phone'] = 1;//手机认证为通过
        $data['score'] = 20;//通过手机绑定获得20积分                
        if ($insert = $obj->add($data)) {   
            
            /*推广页面快速注册记录*/
            $reg_type = I('get.reg_type');  
            if($reg_type == 'speed'){
                $fp = fopen("./reg_remember.txt", "r+"); 
                $reg_count = (int)fgets($fp);           
                $reg_count += 1;
                fseek($fp, 0);
                fwrite($fp, (string)$reg_count);            
                fclose($fp);
            }                


            /* 更新验证码状态变为已使用 */
            $data_code['id'] = $obj_code['id'];
            $data_code['status'] = 1;
            M('temp_code')->where($where_code)->save($data_code);            
            //将注册奖励,插入奖励表 
            $bouns_data['uid'] = $insert;//用户ID
            $bouns_data['bonus_money'] = $data['bonus_money']; 
            $bouns_data['content'] = '注册奖励￥'.$bouns_data['bonus_money'].'元';
            $bouns_data['type'] = 1;//类型为注册奖励
            $bouns_data['create_time'] = NOW_TIME;
            $bouns_data['status'] = 1;//状态为已使用
            $bouns_data['limit_days'] = 0;
            insert_bonus($bouns_data); //插入奖励表     
            /*写入交易记录表*/
            $deal_log_data[0]['uid'] = $insert;
            $deal_log_data[0]['lid'] = '';
            $deal_log_data[0]['invested_id'] = '';
            $deal_log_data[0]['money'] = $data['bonus_money'];
            $deal_log_data[0]['type'] = 7;//交易状态为奖励金额
            $deal_log_data[0]['pay_status'] = 1;//收支状态为收入
            $deal_log_data[0]['detail'] = '获得注册奖励：奖励金额为'.$data['bonus_money'].'元。';//明细
            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
            insert_deal_log($deal_log_data);  //插入资金记录表
            $score_log_data[0]['uid'] = $insert;
            $score_log_data[0]['lid'] = '';
            $score_log_data[0]['invested_id'] = '';
            $score_log_data[0]['score'] = $data['score'];
            $score_log_data[0]['type'] = 3;//交易状态：认证操作
            $score_log_data[0]['pay_status'] = 1;//收支状态为收入
            $score_log_data[0]['detail'] = '手机认证通过：奖励积分为'.$data['score'].'分。';//明细
            $score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
            insert_score_log($score_log_data); //插入积分记录表             
            $remember_sql = $obj->getLastSql(); 
            $view = $obj->where('id='.$insert)->find();
            //写入动作表
            $msg_data['title'] = '新用户注册';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】注册成功';
            $msg_data['user_id'] = $insert;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);                          
            $phone_msg = '尊敬的'.$this->phone_title.'用户，恭喜您成功注册本站会员，如有任何疑问请致电'.C('web_tel').'。详情见官网。';
            $phone_msg2 = '温馨提示：感谢您选择'.$this->phone_title.'，本平台不会以任何理由向您索要账 号、密 码、手机验证码！遇此情况请不予理会';
            // send_sms($phone, $phone_msg);//发送提示短信
            // ++++++++++++++2015-04-09 10:25:58 update_by:liying++++++++++
            send_sms($phone,$view['user_name'],'注册成功提示');
            send_sms($phone,$view['user_name'],'注册成功温馨提示');
            // ++++++++++++++2015-04-09 10:25:58 update_by:liying++++++++++                           
            //send_sms($phone, $phone_msg2,'',3);//发送温馨提示
            session('user_id',null);
            session('user_name',null);
            setcookie("pid",'');//清空记录的pid
            $this->success('恭喜您,注册成功',U('user/Login'));          
        } else {
           $this->error('数据错误'); 
        }      

    }


    /**
     * @todo   VerifyUserExist    AJAX验证用户名\手机\邮箱
     * @param  type               验证类型  type:user_name,phone,mail
     * @param  v                  对应验证的input值
     */
    public function VerifyUserExist(){
        $v = I('post.v');    
        $type = I('post.type');
        if(IS_AJAX){            
            switch ($type) {
                case 'user_name':
                    $where['user_name'] = $v;
                    break;
                case 'phone':
                    $where['phone'] = $v;
                    break;
                case 'mail':
                    $where['email'] = $v;
                    break;                                    
            }          
            $res = M('web_user')->where($where)->find();                        
            if($res){
                $this->ajaxReturn('','',0);
                exit();  
            }else{
                $this->ajaxReturn('','',1);
                exit();                  
            }
        }else{
            $this->error('请勿非法提交参数');
        }
    }


    /**
     * @todo  FindParent  推荐人查找验证
     * @param  $pid       推荐人ID
     * @author liying <472591847@qq.com>
     * @since  2015-04-13 10:59:43
     */
    public function FindParent(){
        $pid = I('pid',0,'intval');
        if(IS_AJAX){
            $res = M('web_user')->where('id='.$pid)->find();
            if($res){
               $this->ajaxReturn('','',1);exit();
            }else{
               $this->ajaxReturn('','不存在的推荐人ID',0);exit();
            }            
        }else{
            $this->ajaxReturn('','提交方式错误',0);exit();
        }
    }


    /**
     * @todo 生成短信验证码并发送到手机
     * @param  int $phone 手机号码
     * @return int $rnd 4位随机数
     */
    public function sendCode() {
        $phone = I("phone");     
		$type = I("type");               
        $temp_str = I("temp_str");                
        if(IS_AJAX){                
            $rnd = (string) substr(bin2hex(md5("L" . time() . rand(1000, 9999) . "Y")), 0, 4);              
            $obj = M('temp_code');
            $where["phone"] = $phone;
            $where["status"] = 0;
            $result = $obj->where($where)->find();
            if (!preg_match("/^(1[3|4|5|7|8]\d{9})$/", $phone) || strlen($phone) != 11) {
                $this->ajaxReturn('','手机号码格式错误',0);exit(); 
            }                       
            if($result){
                $rnd = $result["code_number"];
            }else{        
                $data['phone'] = $phone;
                $data['code_number'] = $rnd;
                $data['create_date'] = NOW_TIME;        
                $insert = $obj->add($data);
                if($insert===false){
                    $this->ajaxReturn('','数据错误',0);exit(); 
                }            
            }           
            /* 发送短信到用户手机，并将验证码和手机号存入数据库 */                                  
            $msg = "验证码:$rnd,欢迎使用".$this->phone_title.",您的手机号码为$phone";                 
            if($type == 1){
                $message = $rnd.',15';
                // ++++++++++++++2015-04-07 17:04:42 update_by:liying++++++++++
                $status = send_sms($phone,$message,$temp_str);                
                // ++++++++++++++2015-04-07 17:04:42 update_by:liying++++++++++                   
				// $status = send_sms($phone, $msg);//发送验证码短信
			}elseif($type == 2){
				$status = voice_verify($phone, $rnd);//发送验证码语音
			}elseif($type == 3){
                $status = send_sms($phone,$msg,'',$type);//发送验证码短信
            }            
			$this->ajaxReturn('','',1);      
        }else{
           $this->ajaxReturn('','提交方式错误',0); 
        }
    }     


    /**
     * @todo 用户登录
     */
    public function Login() { 
        $user_db = M('web_user_db');
        $uid = session('user_id');        
        //判断已登录的跳转
        if(!empty($uid)){
            $where['id'] = $uid;
            $res = $user_db->where($where)->find();              
            if($res['is_email'] == '0' || $res['is_phone'] == '0' || $res['real_name'] == '' || $res['idno'] == '' || $res['trade_pwd'] == '')
                redirect(U('Member/personal',array('Prompt'=>1)));                 
            redirect(U('Member/Index'));
        }
        $this->referer_url = $_SERVER['HTTP_REFERER'];//来路地址传到模版
        $this->display();
    } 


    /**
     * @todo              用户登录验证
     * @param $user_name  用户名
     * @param $user_pwd   密码
     * @param $verify     验证码
     */
    public function DoLogin() {
        $user_name = I('post.user_name');
        $user_pwd = I('post.user_pwd');
        $verify = I('post.verify');    
        $referer_url = I('post.referer_url');//来路地址        
        if(strtolower($verify) != session('verify')) $this->error('验证码错误');//判断验证码        
        $where['user_name|email|phone'] = $user_name;//判断帐号多方式登录
        $where['user_pwd'] = array('eq',md5($user_pwd)); 
        //$where['is_effect'] = 1;//有效       
        $where['is_delete'] = 0;//未删除
        $user_db = M('web_user');
        $res = $user_db->where($where)->find();              
        if(!$res){
            $this->error('用户名或密码错误');
        }
        if($res['is_effect'] != 1){
            $this->error('此账户已冻结,请及时联系客服');
        }
        $update = array(
            'login_time'=>NOW_TIME,
            'login_ip'=>get_client_ip()
        );        
        $save = $user_db->where('id='.$res['id'])->save($update);
        if($save === false){
            $this->error('数据错误');
        }  
        session('user_id',$res['id']);
        session('user_name',$res['user_name']);        
        /*判断来自首页登录的用户显示提示页*/
        if($referer_url != ''){
            $ar_index = explode('php/',$referer_url);
            $str = (string) $ar_index[1];                        
            if($ar_index[1] == 'Index/index.html' || $referer_url == 'http://www.p2p222.com/' || $referer_url == 'http://www.p2p222.com/index.php'){
                $Prompt = 1;
            }
        }                      
        // session('logtime',NOW_TIME);//登录时间记录(用于做超时处理)        
        //若有未认证的信息，跳转到个人资料页
        if($res['is_email'] == '0' || $res['is_phone'] == '0' || $res['real_name'] == '' || $res['idno'] == '' || $res['trade_pwd'] == '' && $Prompt != 1)
            redirect(U('Member/personal',array('Prompt'=>1)));          
        
        //判断来路跳转
        if($referer_url != '' && strstr($referer_url,"Member") == '' && strstr($referer_url,"user") == '' && $Prompt != 1)      
            redirect($referer_url);        
        $this->redirect('Member/Index',array('Prompt'=>$Prompt));
    } 



    /**
     * @todo  getPasswordIndex        找回密码首页视图
     */
    public function getPasswordIndex(){       
        $this->display('back_index');
    }



    /**
     * @todo    getPasswordInPhone   通过手机找回密码视图
     */
    public function getPasswordInPhone(){
        $this->display('back_phone');
    }


    /**
     * @todo    DoGetPasswordInPhone   通过手机找回密码处理
     */
    public function DoGetPasswordInPhone(){
        $phone = I('post.phone');
        $phone_code = I('post.phone_code');
        $verify = I('post.verify');
        $res = M('web_user')->where('phone='.$phone)->find();
        if(!IS_POST){
          $this->error('请勿非法提交参数');
        }       
        if(!$res){
          $this->error('该用户不存在，或尚未绑定手机');
        }
        if(empty($verify))
          $this->error('验证码不能为空');  
        if(session('verify') != strtolower($verify))
          $this->error('验证码输入错误');
        if(empty($phone_code))
          $this->error('手机验证码不能为空');         
        /* 验证码处理 查询未使用过的验证码 */
        $where_code = array('phone' => $phone, 'status' => 0, 'code_number' => $phone_code);
        $obj_code = M('temp_code')->field(array('id', 'code_number'))->where($where_code)->find();        
        /* 如果验证码和数据库中的密码不匹配,返回错误 */
        if (!$obj_code) {            
            $this->error('手机验证码输入错误,请重新输入');
        }          
        M('temp_code')->where($where_code)->setfield('status',1);
        $query = '?'.md5('user_id').'='.rawurlencode(getEncrypt($res['id'])).'&referer=phone';                
        redirect(U('user/ResetPassword').$query);


    }    

    /**
     * @todo  getPassword        找回密码视图
     */
    public function getPassword(){
        import('Class.mail','./');
        $this->display('back');
    }



    /**
     * @todo  DoGetPassword        找回密码处理
     * @param email                用户邮箱地址
     */
    public function DoGetPassword(){
        $email = I('post.email');
        if(empty($email)){
            $this->error('邮箱地址不能为空');
        }
        $res = M('web_user')->where("email='$email'")->find();
        $host = 'http://'.$_SERVER['HTTP_HOST'];
        if(!$res){
            $this->error('该邮箱用户不存在');
        }
        $mail_to = $email;//发送到的email
        $mail_author = C('WEB_TITLE');//发件人（站点名称）        
        $title = '找回密码-'.C('WEB_TITLE');
        $mail_subject = iconv('UTF-8','GB2312',$title);//邮件主题
        /*拼接重置密码连接  包括：用户ID ,发送时间，邮箱地址*/
        $link_url = $host.U('user/ResetPassword')."?".md5('user_id')."=".rawurlencode(getEncrypt($res['id']))."&".md5('time')."=".rawurlencode(getEncrypt(NOW_TIME))."&".md5('email')."=".md5($email);        
        //将参数传递到邮件模版,读取邮件模版
        $mail_content = file_get_contents($host.'/Public/Templates/Mail/mail_get_pwd.php?user_name='.$res['user_name'].'&host='.$host.'&now_time='.NOW_TIME.'&link_url='.rawurlencode($link_url).'&web_title=金投资');                                  
        send_mail($mail_to,$mail_author, $mail_subject, $mail_content, "HTML");
        //var_dump($smtp->sendmail($mail_to,$mail_author,$mail_from, $mail_subject, $mail_content, "HTML"));        
        $this->display('back2');        
    }


    /**
     * @todo  ResetPassword   修改密码邮件返回处理，重置密码视图
     * @param $user_id   int  用户ID
     * @param $send_time int  邮件发送时间
     * @param $email     str  邮件地址
     */
    public function ResetPassword(){         
        $referer = I('referer');
        $user_id = (int)iconv('UTF-8','GB2312',getDecrypt(I(md5('user_id'))));//用户ID                 
        $send_time = (int)iconv('UTF-8','GB2312',getDecrypt(I(md5('time'))));//邮件发送时间        
        $email = iconv('UTF-8','GB2312',I(md5('email')));//邮箱地址                
        $user_db = M('web_user');                    
        $res = $user_db->where('id='.$user_id)->find();                        
        //判断ID是否匹配
        if(!$res){
            $this->error('该用户不存在',U('Index/index'));
        }                        
        if(!$referer){
            /*邮件方式找回密码处理*/
            //判断接到的邮件地址与数据库邮件地址是否相同            
            if(md5($res['email']) != $email){
                $this->error('邮件地址不匹配',U('Index/index'));
            }
            $time_span = 15;//间隔分钟数
            $true_time_span = (NOW_TIME-$send_time)/60;//实际间隔分钟数
            //判断是否在15分钟内操作
            if($true_time_span > $time_span){
               $this->error('操作时间已过期,请重新提交请求',U('user/getPassword'));
            }
        }
        $this->user_id = $user_id;
        $this->referer = $referer;
        $this->display('back3');
    }


    /**
     * @todo    DoResetPassword     重置密码处理
     * @param   $password           密码
     */
    public function DoResetPassword(){
        $password = I('post.password');
        $user_id = I('post.user_id'); 
        $msg_refer = (I('referer') == 'phone')?'通过手机':'通过邮件';       
        if(!IS_POST){
            $this->error('请求方式错误');
        }
        $user_db = M('web_user');
        $data['user_pwd'] = md5($password);
        //保存并修改密码
        $update = $user_db->where('id='.$user_id)->save($data);        
        if($update === false){
            $this->error('数据错误');
        }else{
            $remember_sql = $user_db->getLastSql(); 
            $view = $user_db->where('id='.$user_id)->find();
            //写入动作表
            $msg_data['title'] = '用户重置密码';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】'.$msg_refer.'重置密码成功';
            $msg_data['user_id'] = $user_id;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);                     
            $this->success('恭喜您！密码重置成功',U('user/Login'));
        }

    }



    /**
     * @todo      DoFindMail    邮箱绑定处理
     * @param     $user_id      用户ID
     * @param     $send_time    邮件发送时间
     * @param     $email        要绑定的邮箱地址
     */
    public function DoFindMail(){
        $user_id = (int)iconv('UTF-8','GB2312',getDecrypt(I(md5('user_id'))));//用户ID
        $send_time = (int)iconv('UTF-8','GB2312',getDecrypt(I(md5('time'))));//邮件发送时间        
        $email = iconv('UTF-8','GB2312',I('email'));//邮箱地址                                        、、、、、、、、、、、、、、、、、、                                 
        if(empty($user_id) || empty($send_time) || empty($email)){
            $this->error('绑定邮箱失败,请重新尝试','Login');
        }
        $time_span = 15;//间隔分钟数
        $true_time_span = (NOW_TIME-$send_time)/60;//实际间隔分钟数        
        //判断是否在15分钟内操作
        if($true_time_span > $time_span){
           $this->error('操作时间已过期,请重新提交请求',U('Member/personal'));
        }                 
        $res = $this->user_db->where('id='.$user_id)->find();  
        if($res['is_email'] == 1){
            $this->success('绑定认证邮箱成功',U('Member/personal'));exit();
        }                              
        $auth = check_user_auth($user_id);//查询安全认证项                                  
        $score_log_data[0]['uid'] = $user_id;
        $score_log_data[0]['lid'] = '';
        $score_log_data[0]['invested_id'] = '';
        $score_log_data[0]['score'] = 20;
        $score_log_data[0]['type'] = 3;//交易状态：认证操作
        $score_log_data[0]['pay_status'] = 1;//收支状态为收入
        $now_user_score = $res['score'] + 20;
        $score_log_data[0]['detail'] = '邮箱认证通过：奖励积分为20分。当前剩余积分：'.$now_user_score.'分';//明细
        $score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
        insert_score_log($score_log_data); //插入积分记录表                  
        update_score($user_id,20);//如果邮箱没认证过，用户获得20积分
        //检查安全认证项,如果是最后一项认证则增加奖励金额25元                
        if($auth == 3){                                    
            //将注册奖励,插入奖励表 
            $bouns_data['uid'] = $user_id;//用户ID
            $bouns_data['bonus_money'] = 25; 
            $bouns_data['content'] = '认证奖励￥'.$bouns_data['bonus_money'].'元';
            $bouns_data['type'] = 3;//类型为认证奖励
            $bouns_data['create_time'] = NOW_TIME;
            $bouns_data['status'] = 1;//状态为已使用
            $bouns_data['limit_days'] = 0;
            insert_bonus($bouns_data); //插入奖励表
            /*写入交易记录表*/
            $deal_log_data[0]['uid'] = $user_id;//用户ID
            $deal_log_data[0]['lid'] = '';
            $deal_log_data[0]['invested_id'] = '';
            $deal_log_data[0]['money'] = $bouns_data['bonus_money'];
            $deal_log_data[0]['type'] = 7;//交易状态为奖励金额
            $deal_log_data[0]['pay_status'] = 1;//收支状态为收入                    
            $now_user_money = $res['money'];//当前剩余金额
            $now_user_lock_money = $res['lock_money'];//当前剩余冻结金额        
            $now_user_bonus_money = $res['bonus_money'] + $bouns_data['bonus_money'];//当前剩余奖励金额
            // 当前剩余金额:'.$now_user_money.'元剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细            
            $deal_log_data[0]['detail'] = '获得认证奖励：奖励金额为'.$bouns_data['bonus_money'].'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
            insert_deal_log($deal_log_data);                                            
            update_bonus_money($user_id,25);//为会员追加奖励金额25元
        }                
        $data['is_email'] = 1;//邮箱状态为绑定
        $data['email'] = $email;               
        $update = $this->user_db->where('id='.$user_id)->save($data);       
        if($update !== false){           
            //写入动作表
            $remember_sql = $this->user_db->getLastSql();
            $view = $this->user_db->where('id='.$user_id)->find();            
            $msg_data['title'] = '绑定认证邮箱';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】'.'绑定认证邮箱成功';
            $msg_data['user_id'] = $user_id;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);   
            $this->success('绑定认证邮箱成功',U('Member/personal'));            
        }else{
            $this->error('数据错误',U('Member/personal'));
        }
    }

    /**
     * @todo  CheckVerify  图形验证码验证
     */
    public function CheckVerify(){
        $verify = I('verify');
        if(IS_AJAX){
            if(strtolower($verify) == session('verify')){
                $this->ajaxReturn('','',1);
            }else{
                $this->ajaxReturn('','验证码错误',0);
            }
        }
    }

    /**
     * @todo   verify   验证码生成
     */
    public function verify(){
        import("Class.Image","./");
        $image = New Image();        
        $image->verify();
    }


    /**
     * @todo    ExitUser    退出登录
     */
    public  function  ExitUser(){
        session('user_id',null);
        session('user_name',null);
        session('logtime',null);
        $this->redirect('Login');
    }
}
 ?>