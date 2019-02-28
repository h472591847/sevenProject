<?php 
/**
 * @todo            前台会员首页功能模块控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-11
 */
class MemberAction extends CommonAction{

    
    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        parent::_initialize();
        $this->page_size = 6;
        $this->phone_title = "金 投 资";//手机短信显示的公司名称
        $this->uid = session("user_id");
        $this->user_db = M('web_user');        
    }


   /**
     * @todo  manage_tx  会员页面左上角公用
   */
   public function manage_tx(){    
        $week_key = date("w");//当前星期数值
        $week_arr = array("日","一","二","三","四","五","六");
        $this->week = $week_arr[$week_key];//获取当前星期
        $user_info = $this->user_db->where('id='.$this->uid)->find();         
        $user_info['auth'] = 0;
        /*----安全等级 计算开始----*/
        $auth = check_user_auth($this->uid);            
        $user_info['auth_rate'] = sprintf("%.0f", substr(sprintf("%.3f", $auth / 4 * 100), 0, -1));         
        /*----安全等级 计算结束----*/
        $this->deal_static = deal_statistics($this->uid);   
        $today = strtotime(date('Y-m-d',time()));    //今天0点
        $tomorrow = strtotime(date("Y-m-d",strtotime("+1 day")));  //明天0点            
        $score_log_db  = M('score_log');
        $score_log_where['type'] = 1;//交易类型：签到
        $score_log_where['uid'] = $this->uid;
        $score_log_where['pay_status'] = 1;//收支状态：收入
        $score_log_where['create_time'] = array('EGT',$today);
        $yesterday = date('Y-m-d',strtotime(date("Y-m-d",strtotime("-1 day")))); //昨天的日期   
        $sign_count_res= $score_log_db->field('sum(score) as count_score')->where($score_log_where)->find();//查找该用户签到共得到了多少分            
        $count_score = $sign_count_res['count_score'];               
        //若签到中断，则显示签到次数为0        
        if(date('Y-m-d',$user_info['signin_time']) != $yesterday && date('Y-m-d',$user_info['signin_time']) != date('Y-m-d',$today)){
            $user_info['continu_login'] = 0;
        }            
        $count_score = ($count_score)?$count_score:0;
        $score_log_where['create_time'] = array(array('EGT',$today),array('ELT',$tomorrow),'AND');//时间为今天
        $sign_res = $score_log_db->where($score_log_where)->find();//检查该用户是否已签到        
        $sign_status = (!empty($sign_res))?1:0;//签到状态：1已签到，0 未签到
        //账户消息记录条数读取
        $com_msg_where['user_id'] = $this->uid;
        $com_msg_where['is_read'] = 0;//阅读状态：0未读 1已读    
        $com_msg_where['web_status'] = 1;//类型：1前台操作 2后台操作                
        $com_msg_count = M('msg_box')->where($com_msg_where)->count();
        //未读资金记录条数读取
        $dlg_where['uid'] = $this->uid;
        $dlg_where['is_read'] = 0;//阅读状态：0未读 1已读
        $dlg_count = M('deal_log')->where($dlg_where)->count();                
        $com_count = $dlg_count + $com_msg_count;                    
        //未读积分记录条数读取
        $slg_where['uid'] = $this->uid;
        $slg_where['is_read'] = 0;//阅读状态：0未读 1已读
        $slg_count = M('score_log')->where($slg_where)->count();                        
        $this->slg_count = $slg_count;
        $this->com_count = $com_count;
        $this->sign_status = $sign_status;      
        $this->count_score = $count_score;//连续签到得分   
        $this->user_info = $user_info;
      $this->display();
   }

    /**
     * @todo   Index 会员首页视图
     */
    public function Index(){                
        $this->now_time = strtotime('2015-04-11');//1428681600  4-11定时通知
        $investe_db = M('invested');
        $log_db = M('deal_log');        
        $user_info = $this->user_db->where('id='.$this->uid)->find();                
        $t5 = strtotime(date('Ym01',strtotime("-5 month")));//5个月前
        $t4 = strtotime(date('Ym01',strtotime("-4 month")));//4个月前
        $t3 = strtotime(date('Ym01',strtotime("-3 month")));//3个月前
        $t2 = strtotime(date('Ym01',strtotime("-2 month")));//2个月前
        $t1 = strtotime(date('Ym01',strtotime("-1 month")));//1个月前        
        $t0 = NOW_TIME;
        $tend_month = strtotime(date('Ym01',NOW_TIME));//当前日期
        $old_date = strtotime(date("Y-m-d H:i:s",$t));                
        $where_month['is_effect'] = 1;
        $where_month['is_delete'] = 0;
        $where_month['uid'] = $this->uid;
        $field_month = 'sum(amount) as sum_month';//投资本金总和
        /*5个月前投资总额*/
        $where_month6['create_time'] = array(array('EGT',$t5),array('LT',$t4));
        $where_month6 = array_merge($where_month,$where_month6);                
        $user_info['month_5'] = $investe_db->field($field_month)->where($where_month6)->find();
        if(empty($user_info['month_5']['sum_month'] ))
            $user_info['month_5']['sum_month']  = 0;        
        $user_info['month_5_name'] = date('m',$t5).'月';        
        /*4个月前投资总额*/
        $where_month4['create_time'] = array(array('EGT',$t4),array('LT',$t3));
        $where_month4 = array_merge($where_month,$where_month4);                
        $user_info['month_4'] = $investe_db->field($field_month)->where($where_month4)->find();
        if(empty($user_info['month_4']['sum_month']))
            $user_info['month_4']['sum_month'] = 0;        
        $user_info['month_4_name'] = date('m',$t4).'月';  
        /*3个月前投资总额*/
        $where_month3['create_time'] = array(array('EGT',$t3),array('LT',$t2));
        $where_month3 = array_merge($where_month,$where_month3);                
        $user_info['month_3'] = $investe_db->field($field_month)->where($where_month3)->find();        
        if(empty($user_info['month_3']['sum_month'] ))
            $user_info['month_3']['sum_month']  = 0;        
        $user_info['month_3_name'] = date('m',$t3).'月';               
        /*2个月前投资总额*/
        $where_month2['create_time'] = array(array('EGT',$t2),array('LT',$t1));
        $where_month2 = array_merge($where_month,$where_month2);                
        $user_info['month_2'] = $investe_db->field($field_month)->where($where_month2)->find();                
        if(empty($user_info['month_2']['sum_month']))
            $user_info['month_2']['sum_month']  = 0;        
        $user_info['month_2_name'] = date('m',$t2).'月';        
        /*1个月前投资总额*/
        $where_month1['create_time'] = array(array('EGT',$t1),array('LT',$tend_month));
        $where_month1 = array_merge($where_month,$where_month1);                
        $user_info['month_1'] = $investe_db->field($field_month)->where($where_month1)->find();
        if(empty($user_info['month_1']['sum_month'] ))
            $user_info['month_1']['sum_month']  = 0;
        $user_info['month_1_name'] = date('m',$t1).'月';                                              
        /*当前月投资总额*/
        $where_month0['create_time'] = array(array('EGT',$tend_month),array('LT',$t0));
        $where_month0 = array_merge($where_month,$where_month0);                
        $user_info['month_0'] = $investe_db->field($field_month)->where($where_month0)->find();
        if(empty($user_info['month_0']['sum_month'] ))
            $user_info['month_0']['sum_month']  = 0;
        $user_info['month_0_name'] = date('m',$t0).'月';                
        import('ORG.Util.Page'); //引入分页                       
        $where['uid'] = $this->uid;          
        $order = 'create_time DESC,id DESC';
        $count = $log_db->where($where)->order($order)->count();        
        $page = new Page($count,5);
        $limit = $page->firstRow . ',' . $page->listRows;                                  
        $list = $log_db->where($where)->order($order)->limit($limit)->select();     
        foreach ($list as $key => $value) {
            $value['money'] = abs($value['money']);
            $list[$key] = $value;
        }  

        /*+++++2015-04-08 17:08:12 update_by:liying 奖励金额使用比例后 所需充值金额计算start+++++++++*/
        //$user_info['bonus_money'] = 0.5;                    
        $true_recharge_money = 0;
        $recharge_money = 0;        
        $bonus_rate = C('bonus_rate');//奖励金额使用率 %
        $true_need_invest_money = $user_info['bonus_money'] / $bonus_rate;//需投资金额
        $need_invest_money = intval(floor($true_need_invest_money / 50)) * 50;//投资金额能被50整除化 取相邻大的整除数                                                                 
        //若需投资金额不能被50整除或需投资金额<=100则 +50
        if($true_need_invest_money % 50 != 0 || $true_need_invest_money <= 100){
          $need_invest_money = $need_invest_money + 50;
        }                        
        //实际需充值金额 = 需充值金额 - 奖励金额 
        $true_recharge_money = $need_invest_money - $user_info['bonus_money'];
        //奖励金额小于等于10都按+50算
        if($user_info['bonus_money'] <= 10){
            $true_recharge_money = $true_recharge_money + 50;
            //奖励金额小于等于5都按100算
            if($user_info['bonus_money'] <= 5){
                $true_recharge_money = 100;
            }
        }
        /*+++++2015-04-08 17:08:12 update_by:liying 奖励金额使用比例后 所需充值金额计算end+++++++++*/
        $this->true_recharge_money = $true_recharge_money;//奖励金额使用需充值金额          
        //echo $this->true_recharge_money = $true_recharge_money;
        $this->deal_static = deal_statistics($this->uid); 
        $this->list = $list;        
        $this->user_info = $user_info;
        $this->Prompt = I("Prompt"); //弹出使用技巧
        $this->display();
    }



    /**
     * @todo    personal    个人资料视图
     * @return [type] [description]
     */
    public function personal(){        
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $user_info['repeat_user_pwd'] = str_repeat( '*',strlen($user_info['user_pwd']));//将密码替换成*        
        $user_info['repeat_trade_pwd'] = str_repeat( '*',strlen($user_info['trade_pwd']));//将密码替换成*                
        //真实姓名截取处理
        $user_info['repeat_real_name'] = mb_substr($user_info['real_name'], 0,1,'utf-8').'**';  
        //手机号码截取处理
        $len_phone = strlen($user_info['phone']);                           
        $left_phone = substr($user_info['phone'],0,3);
        $center_phone = str_repeat('*', $len_phone - 3 - 4); 
        $right_phone = substr($user_info['phone'],7,4);
        //邮箱截取处理
        if($user_info['email'] != ''){
            $mail_ar = explode('@',$user_info['email']);
            if(count($mail_ar[0])>0){
                $len_mail = strlen($mail_ar[0]);                           
                $left_mail = substr($mail_ar[0],0,3);
                $center_mail = str_repeat('*', $len_mail - 3 - 3); 
                $right_mail = substr($mail_ar[0],-3);
                $user_info['repeat_email'] = $left_mail.$center_mail.$right_mail.'@'.$mail_ar[1];
            }
        }     
        $user_info['repeat_phone'] = $left_phone.$center_phone.$right_phone;
        /*----安全等级 计算开始----*/
        $auth = check_user_auth($this->uid);             
        $user_info['auth_rate'] = sprintf("%.0f", substr(sprintf("%.3f", $auth / 4 * 100), 0, -1));                 
        /*----安全等级 计算结束----*/
		//身份证号码截取处理
        if(!empty($user_info['idno'])){
            $len_idno = strlen($user_info['idno']);                           
            $left_idno = substr($user_info['idno'],0,4);
            $center_idno = str_repeat('*', $len_idno - 8); 
            $right_idno = substr($user_info['idno'],-4);
            $user_info['idno'] = $left_idno.$center_idno.$right_idno;                
        }
        $this->user_info = $user_info;
		$this->Prompt = I("Prompt",0); //弹出使用技巧
        $this->display();
    }



    /**
     * @todo    face   头像编辑视图
     */
    public function face(){             
        $this->user_info = $this->user_db->field('face_pic')->where('id='.$this->uid)->find();
        $this->display();
    }

    /**
     * @todo       saveFace      头像上传处理
     */
    public function saveFace(){        
        if (!$_FILES['face_pic']) {
            die ( 'Image data not detected!' );
        }
        if ($_FILES['face_pic']['error'] > 0) {
            switch ($_FILES ['face_pic'] ['error']) {
                case 1 :
                    $error_log = 'The file is bigger than this PHP installation allows';
                    break;
                case 2 :
                    $error_log = 'The file is bigger than this form allows';
                    break;
                case 3 :
                    $error_log = 'Only part of the file was uploaded';
                    break;
                case 4 :
                    $error_log = 'No file was uploaded';
                    break;
                default :
                    break;
            }
            die ( 'upload error:' . $error_log );
        } else {
            $img_data = $_FILES['face_pic']['tmp_name'];
            $size = getimagesize($img_data);
            $file_type = $size['mime'];
            if (!in_array($file_type, array('image/jpg', 'image/jpeg', 'image/pjpeg', 'image/png', 'image/gif'))) {
                $error_log = 'only allow jpg,png,gif';
                die ( 'upload error:' . $error_log );
            }
            switch($file_type) {
                case 'image/jpg' :
                case 'image/jpeg' :
                case 'image/pjpeg' :
                    $extension = 'jpg';
                    break;
                case 'image/png' :
                    $extension = 'png';
                    break;
                case 'image/gif' :
                    $extension = 'gif';
                    break;
            }   
        }
        if (!is_file($img_data)) {
            die ( 'Image upload error!' );
        }
        //图片保存路径,默认保存在该代码所在目录(可根据实际需求修改保存路径)
        $save_path = documentRoot().'/Uploads/face_pics';
        $uinqid = uniqid();
        $filename = $save_path . '/' . $uinqid . '.' . $extension;//图片路径        
        $result = move_uploaded_file( $img_data, $filename );
        if ( ! $result || ! is_file( $filename ) ) {
            die ( 'Image upload error!' );
        }
        //echo $filename;//将图片路径返回给前台                        
        $user_where['id'] = $this->uid;
        $filename = str_replace(documentRoot(), '', $filename);
        $data['face_pic'] = $filename;
        $res = $this->user_db->field('face_pic')->where($user_where)->find();                
        //检测旧图片是否存在，有则删除
        $old_file = documentRoot().$res['face_pic'];        
        unlink($old_file);
        $this->user_db->where($user_where)->save($data);        
        //写入动作表
        $remember_sql = $this->user_db->getLastSql();
        $view = $this->user_db->where('id='.$this->uid)->find();            
        $msg_data['title'] = '用户头像修改';
        $msg_data['content'] = '【前台会员：'.$view['user_name'].'】用户头像修改成功';
        $msg_data['user_id'] = $this->uid;
        $msg_data['create_time'] = NOW_TIME;
        $msg_data['type'] = 1;//账户操作
        $msg_data['web_status'] = 1;//状态：前台操作
        $msg_data['sql'] = $remember_sql;
        insert_msg_box($msg_data);          
        echo $url = U('Member/personal');
    }


    /**
     * @todo  FindMail    邮箱绑定
     * @param $email      新的邮箱地址
     */
    public function FindMail(){
        $email = I('post.email');
        if(!preg_match("/^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/", $email))
          $this->error('邮箱格式错误');
        $agin_where['id'] = array('NEQ',$this->uid);
        $agin_where['email'] = $email;
        $agin_res = $this->user_db->where($agin_where)->find();
        //判断
        if($agin_res){
            $this->error('该邮箱已被其他用户使用');
        }
        $res = $this->user_db->where("id=".$this->uid)->find();
        $host = 'http://'.$_SERVER['HTTP_HOST'];

        $mail_to = $email;//发送到的email
        $mail_author = C('WEB_TITLE');//发件人（站点名称）        
        $title = '邮箱认证-'.C('WEB_TITLE');
        $mail_subject = iconv('UTF-8','GB2312',$title);//邮件主题
        /*拼接重置密码连接  包括：用户ID ,发送时间，邮箱地址*/
        $link_url = $host.U('user/DoFindMail')."?".md5('user_id')."=".rawurlencode(getEncrypt($res['id']))."&".md5('time')."=".rawurlencode(getEncrypt(NOW_TIME))."&email=".$email;        
        //将参数传递到邮件模版,读取邮件模版
        $mail_content = file_get_contents($host.'/Public/Templates/Mail/mail_find_mail.php?user_name='.$res['user_name'].'&host='.$host.'&now_time='.NOW_TIME.'&link_url='.rawurlencode($link_url).'&web_title=金投资&action=邮箱认证');                                          
        send_mail($mail_to,$mail_author, $mail_subject, $mail_content, "HTML");
        $this->success('激活邮件已经发送到您的邮箱，请及时查收');
    }





    /**
     * @todo    ModifyOldEmailVerify   旧邮箱身份验证邮件发送
     * @param   $uid                   用户ID
     * @param   $email                 邮箱地址
     */
    public function ModifyOldEmailVerify(){
        $uid = $this->uid;
        $u_info = $this->user_db->where('id='.$uid)->find();
        $email = $u_info['email'];                
        $host = 'http://'.$_SERVER['HTTP_HOST'];        
        $mail_to = $email;//发送到的email
        $mail_author = C('WEB_TITLE');//发件人（站点名称）        
        $title = '修改邮箱地址-'.C('WEB_TITLE');
        $mail_subject = iconv('UTF-8','GB2312',$title);//邮件主题
        /*拼接重置密码连接  包括：用户ID ,发送时间，邮箱地址*/
        $link_url = $host.U('Member/DoUpdateMail')."?".md5('user_id')."=".rawurlencode(getEncrypt($u_info['id']))."&".md5('time')."=".rawurlencode(getEncrypt(NOW_TIME))."&email=".$email;        
        //将参数传递到邮件模版,读取邮件模版
        $mail_content = file_get_contents($host.'/Public/Templates/Mail/mail_find_mail.php?user_name='.$u_info['user_name'].'&host='.$host.'&now_time='.NOW_TIME.'&link_url='.rawurlencode($link_url).'&web_title=金投资&action=邮箱修改');                                          
        if(!send_mail($mail_to,$mail_author, $mail_subject, $mail_content, "HTML")){
            $this->error("系统繁忙,请稍后重试!");
        }
        $this->success('激活邮件已经发送到您的邮箱，请及时查收');       
    }



    /**
     * @todo    DoUpdateMail   已确认身份，新邮箱修改视图
     * @param   $user_id           用户ID
     * @param   $send_time         发送时间
     * @param   $email             邮箱地址
     */
    public function DoUpdateMail(){
        $user_id = (int)iconv('UTF-8','GB2312',getDecrypt(I(md5('user_id'))));//用户ID
        $send_time = (int)iconv('UTF-8','GB2312',getDecrypt(I(md5('time'))));//邮件发送时间        
        $email = iconv('UTF-8','GB2312',I('email'));//邮箱地址                                                    
        if(empty($user_id) || empty($email))
            $this->error('请勿非法操作');
        $time_span = 15;//间隔分钟数
        $true_time_span = (NOW_TIME-$send_time)/60;//实际间隔分钟数        
        //判断是否在15分钟内操作
        if($true_time_span > $time_span){
           $this->error('操作时间已过期,请重新提交请求',U('Member/personal'));
        }  
        $this->user_id = $user_id;   
        $this->display('modify_email');
    }



    /**
     * @todo    DoUpdateMailSend   新邮箱确认邮件发送
     * @param   $email             邮箱地址
     * @param   $user_id           用户ID
     */
    public function DoUpdateMailSend(){
        $email = I('email');
        $user_id = I('user_id',0,'intval');
        if(empty($email))
            $this->error('邮箱地址不能为空');
        //验证身份
        if($user_id != $this->uid)
            $this->error('参数错误');        
        //除修改人以外邮箱重复的过滤
        $agin_where['id'] = array('NEQ',$user_id);
        $agin_where['email'] = $email;
        $agin_res = $this->user_db->where($agin_where)->find();        
        if($agin_res)
            $this->error('该邮箱已被其他用户使用');
        $u_info = $this->user_db->where('id='.$user_id)->find();                
        $host = 'http://'.$_SERVER['HTTP_HOST'];        
        $mail_to = $email;//发送到的email
        $mail_author = C('WEB_TITLE');//发件人（站点名称）        
        $title = '修改新邮箱验证-'.C('WEB_TITLE');
        $mail_subject = iconv('UTF-8','GB2312',$title);//邮件主题
        /*拼接重置密码连接  包括：用户ID ,发送时间，邮箱地址*/
        $link_url = $host.U('Member/DoUpdateMailSave')."?".md5('user_id')."=".rawurlencode(getEncrypt($u_info['id']))."&".md5('time')."=".rawurlencode(getEncrypt(NOW_TIME))."&email=".$email;        
        //将参数传递到邮件模版,读取邮件模版
        $mail_content = file_get_contents($host.'/Public/Templates/Mail/mail_find_mail.php?user_name='.$u_info['user_name'].'&host='.$host.'&now_time='.NOW_TIME.'&link_url='.rawurlencode($link_url).'&web_title=金投资&action=邮箱修改');                                          
        if(send_mail($mail_to,$mail_author, $mail_subject, $mail_content, "HTML")){
            $this->success('验证邮件已发送，请点击收到邮件中的链接以确认修改');
        }else{
            $this->error('验证邮件发送失败，请重新提交请求');
        }
    }




    /**
     * @todo    DoUpdateMailSave   新邮箱地址保存数据
     * @param   $user_id           用户ID
     * @param   $send_time         发送时间
     * @param   $email             邮箱地址
     */
    public function DoUpdateMailSave(){
        $user_id = (int)iconv('UTF-8','GB2312',getDecrypt(I(md5('user_id'))));//用户ID
        $send_time = (int)iconv('UTF-8','GB2312',getDecrypt(I(md5('time'))));//邮件发送时间        
        $email = iconv('UTF-8','GB2312',I('email'));//邮箱地址                                                    
        if(!IS_GET)
            $this->error('提交方式错误');
        //验证身份
        if($user_id != $this->uid)
            $this->error('参数错误');
        $time_span = 15;//间隔分钟数
        $true_time_span = (NOW_TIME-$send_time)/60;//实际间隔分钟数        
        //判断是否在15分钟内操作
        if($true_time_span > $time_span){
           $this->error('操作时间已过期,请重新提交请求',U('Member/personal'));
        }  
        if(!empty($user_id) && !empty($email)){
            
            $update = $this->user_db->where('id='.$user_id)->setField('email',$email);
            if($update !== false){
                //写入动作表
                $remember_sql = $this->user_db->getLastSql();
                $view = $this->user_db->where('id='.$this->uid)->find();            
                $msg_data['title'] = '修改新邮箱';
                $msg_data['content'] = '【前台会员：'.$view['user_name'].'】修改新邮箱成功';
                $msg_data['user_id'] = $this->uid;
                $msg_data['create_time'] = NOW_TIME;
                $msg_data['type'] = 1;//账户操作
                $msg_data['web_status'] = 1;//状态：前台操作
                $msg_data['sql'] = $remember_sql;
                insert_msg_box($msg_data);                  
                $this->success('恭喜您，修改新邮箱成功','personal');
            }else{
                $this->error('数据错误');
            }
        }
    }




    /**
     * @todo  FindIdno    身份证绑定
     * @param $real_name   姓名
     * @param $idno        身份证号
     */
    public function FindIdno(){
       $real_name = I('post.real_name');
       $idno = (string)I('post.idno');       
       //表单令牌验证
       if(!$this->user_db->autoCheckToken($_POST)){
            $this->error('令牌验证错误');
       }
       if(!empty($idno) && !empty($real_name)){
            $data['real_name'] = $real_name;
            $data['idno'] = $idno;
            //判断用户身份证号是否已占用
            $only_idno = $this->user_db->where("idno='$idno'")->count();            
            if(!empty($only_idno)){
                $this->error('此身份证号码已占用，请更换其他身份证号码进行认证');
            }
            $res = $this->user_db->where('id='.$this->uid)->find();
            if(empty($res['idno']) && empty($res['real_name'])){
                update_score($this->uid,20);//通过实名认证获得20积分（仅限第一次绑定）
                $score_log_data[0]['uid'] = $this->uid;
                $score_log_data[0]['lid'] = '';
                $score_log_data[0]['invested_id'] = '';
                $score_log_data[0]['score'] = 20;
                $score_log_data[0]['type'] = 3;//交易状态：认证操作
                $score_log_data[0]['pay_status'] = 1;//收支状态为收入
                $now_user_score = $res['score'] + 20;
                $score_log_data[0]['detail'] = '身份认证通过：奖励积分为20分。当前剩余积分：'.$now_user_score.'分';//明细                
                $score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
                insert_score_log($score_log_data); //插入积分记录表                   
                //检查安全认证项,如果是最后一项认证则增加奖励金额25元
                $auth = check_user_auth($this->uid);
                if($auth == 3){                    
                    update_bonus_money($this->uid,25);//为会员追加奖励金额25元
                    //将注册奖励,插入奖励表 
                    $bouns_data['uid'] = $this->uid;//用户ID
                    $bouns_data['bonus_money'] = 25; 
                    $bouns_data['content'] = '认证奖励￥'.$bouns_data['bonus_money'].'元';
                    $bouns_data['type'] = 3;//类型为认证奖励
                    $bouns_data['create_time'] = NOW_TIME;
                    $bouns_data['status'] = 1;//状态为已使用
                    $bouns_data['limit_days'] = 0;
                    insert_bonus($bouns_data); //插入奖励表
                    /*写入交易记录表*/
                    $deal_log_data[0]['uid'] = $this->uid;//用户ID
                    $deal_log_data[0]['lid'] = '';
                    $deal_log_data[0]['invested_id'] = '';
                    $deal_log_data[0]['money'] = $bouns_data['bonus_money'];
                    $deal_log_data[0]['type'] = 7;//交易状态为奖励金额
                    $deal_log_data[0]['pay_status'] = 1;//收支状态为收入
                    $now_user_money = $res['money'];//当前剩余金额
                    $now_user_lock_money = $res['lock_money'];//当前剩余冻结金额
                    $now_user_bonus_money = $res['bonus_money'] + $bouns_data['bonus_money'];//当前剩余奖励金额记录                                        
                    $deal_log_data[0]['detail'] = '获得认证奖励：奖励金额为'.$bouns_data['bonus_money'].'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
                    $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
                    insert_deal_log($deal_log_data);                                            
                }                 
            }
            $this->user_db->where('id='.$this->uid)->save($data);
            //写入动作表
            $remember_sql = $this->user_db->getLastSql();
            $view = $this->user_db->where('id='.$this->uid)->find();            
            $msg_data['title'] = '绑定实名认证';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】绑定实名认证成功';
            $msg_data['user_id'] = $this->uid;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);             
       }   
       $this->redirect('personal');
    }

    /**
     * @todo  UpdatePwd    会员修改密码
     * @param $user_pwd     旧密码
     * @param $new_pwd      新密码
     */
    public function UpdatePwd(){
       $user_pwd = I('post.user_pwd');
       $new_pwd = I('post.new_pwd');       
       if(empty($user_pwd) || empty($new_pwd)){
          $this->error('原密码和新密码都不能为空');
       }   
       $where['id'] = $this->uid;
       $user_ar = $this->user_db->where($where)->find();
       if($user_ar['user_pwd'] != md5($user_pwd)){
         $this->error('原密码输入错误，请重新输入');
       }
       //修改成新密码
       $data['user_pwd'] = md5($new_pwd);
       $update = $this->user_db->where($where)->save($data);
       if($update !== false){            
            //写入动作表
            $remember_sql = $this->user_db->getLastSql();
            $msg_data['title'] = '用户修改密码';
            $msg_data['content'] = '【前台会员：'.$user_ar['user_name'].'】密码修改成功';
            $msg_data['user_id'] = $this->uid;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);            
          $this->success('修改成功,请重新登录',U('user/ExitUser'));
       }else{
          $this->error('数据错误');
       }       
    }


    /**
     * @todo   setTrade             设置交易密码
     * @param  $trade_pwd           交易密码
     * @param  $trade_phone         验证手机号
     * @param  $trade_code_number   验证码
     */
    public function setTrade(){
        $trade_pwd = I('post.trade_pwd');
        $trade_phone = $this->user_db->where('id='.$this->uid)->getField('phone');        
        $trade_code_number = I('post.trade_code_number',0,'intval');
        $code_db = M('temp_code');
        $code_where = array(
            'code_number'=>$trade_code_number,
            'phone'=>$trade_phone,
            'status'=>0
        );
        $result = $code_db->where($code_where)->find();
        //表单令牌验证
        if(!$this->user_db->autoCheckToken($_POST)){
            $this->error('令牌验证错误');
        }        
        if(!$result){
            $this->error('输入错误,请检查手机号和验证码');
        }
        $code_data['status'] = 1;//改变验证码状态
        $code_save = $code_db->where($code_where)->save($code_data);
        if($code_save === false){
            $this->error('数据错误');   
        }
        $where['id'] = $this->uid;
        $save['trade_pwd'] = md5($trade_pwd);
        $res = $this->user_db->where($where)->find();
        if(empty($res['trade_pwd'])){
            update_score($this->uid,20);//通过设置交易密码获得20积分(仅限第一次设置)
            $score_log_data[0]['uid'] = $this->uid;
            $score_log_data[0]['lid'] = '';
            $score_log_data[0]['invested_id'] = '';
            $score_log_data[0]['score'] = 20;
            $score_log_data[0]['type'] = 3;//交易状态：认证操作
            $score_log_data[0]['pay_status'] = 1;//收支状态为收入
            $now_user_score = $res['score'] + 20;
            $score_log_data[0]['detail'] = '支付密码已设置：奖励积分为20分。当前剩余积分：'.$now_user_score.'分';//明细                            
            $score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
            insert_score_log($score_log_data); //插入积分记录表                  
            $auth = check_user_auth($this->uid); //检查安全认证项,如果是最后一项认证则增加奖励金额25元
            if($auth == 3){                    
                update_bonus_money($this->uid,25);//为会员追加奖励金额25元
                //将注册奖励,插入奖励表 
                $bouns_data['uid'] = $this->uid;//用户ID
                $bouns_data['bonus_money'] = 25; 
                $bouns_data['content'] = '认证奖励￥'.$bouns_data['bonus_money'].'元';
                $bouns_data['type'] = 3;//类型为认证奖励
                $bouns_data['create_time'] = NOW_TIME;
                $bouns_data['status'] = 1;//状态为已使用
                $bouns_data['limit_days'] = 0;
                insert_bonus($bouns_data); //插入奖励表
                /*写入交易记录表*/
                $deal_log_data[0]['uid'] = $this->uid;//用户ID
                $deal_log_data[0]['lid'] = '';
                $deal_log_data[0]['invested_id'] = '';
                $deal_log_data[0]['money'] = $bouns_data['bonus_money'];
                $deal_log_data[0]['type'] = 7;//交易状态为奖励金额
                $deal_log_data[0]['pay_status'] = 1;//收支状态为收入
                $now_user_money = $res['money'];//当前剩余金额
                $now_user_lock_money = $res['lock_money'];//当前剩余冻结金额
                $now_user_bonus_money = $res['bonus_money'] + $bouns_data['bonus_money'];//当前剩余奖励金额记录                                        
                $deal_log_data[0]['detail'] = '获得认证奖励：奖励金额为'.$bouns_data['bonus_money'].'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
                $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
                insert_deal_log($deal_log_data);                                            
            }  
        }        
        $update = $this->user_db->where($where)->save($save);
        if($update!==false){
            //写入动作表
            $remember_sql = $this->user_db->getLastSql();
            $view = $this->user_db->where('id='.$this->uid)->find();            
            $msg_data['title'] = '交易密码设置';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】交易密码设置成功';
            $msg_data['user_id'] = $this->uid;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);                 
            $this->redirect('personal');
        }else{
            $this->error('数据错误:update error!');  
        }

    }



    /**
     * @todo    FindPhone          验证旧手机号身份以进行下一步
     * @param   $phone         手机号
     * @param   $code_number   验证码
     * @return  json         {data:数据结果集,info:提示信息,status:处理状态}
     */
    public function FindPhone(){
        if(!IS_AJAX)
            $this->error('请勿非法操作');
        $phone = I('post.phone');        
        $code_number = I('post.code_number'); 
        $code_db = M('temp_code');
        $code_where = array(
            'code_number'=>$code_number,
            'phone'=>$phone,
            'status'=>0
        );
        $result = $code_db->where($code_where)->find();
        if(!$result){
            $this->ajaxReturn('','验证码输入错误',0);exit();
        }        
        $code_data['status'] = 1;//改变验证码状态
        $code_save = $code_db->where($code_where)->save($code_data);
        if($code_save === false){
            $this->ajaxReturn('','数据错误',0);exit();
        }
        $this->ajaxReturn('','',1);exit();
    }




    /**
     * @todo  DoFindPhone        新手机号验证,修改绑定手机处理
     * @param $new_phone         手机号
     * @param $new_code_number   验证码
     * @return  json             {data:数据结果集,info:提示信息,status:处理状态}
     */
    public function DoFindPhone(){
        if(!IS_AJAX){
            $this->ajaxReturn('','此手机号已被使用请重新输入',0);exit();      
        }
        $new_phone = I('post.new_phone');
        $new_code_number = I('post.new_code_number'); 
        $code_db = M('temp_code');
        $code_where = array(
            'code_number'=>$new_code_number,
            'phone'=>$new_phone,
            'status'=>0
        );
        //除修改人以外手机重复的过滤
        $agin_where['id'] = array('NEQ',$this->uid);
        $agin_where['phone'] = $new_phone;
        $agin_res = $this->user_db->where($agin_where)->find();   
        if($agin_res){
            $this->ajaxReturn('','此手机号已被使用请重新输入',0);exit();
        }        
        $result = $code_db->where($code_where)->find();        
        if(!$result){
            $this->ajaxReturn('','输入错误,请检查手机号和验证码',0);exit();          
        }
        $code_data['status'] = 1;//改变验证码状态
        $code_save = $code_db->where($code_where)->save($code_data);
        if($code_save === false){
            $this->ajaxReturn('','数据错误',0);exit();          
        }
        $user_save = $this->user_db->where('id='.$this->uid)->setField('phone',$new_phone);       
        if($user_save !== false){
            //写入动作表
            $remember_sql = $this->user_db->getLastSql();
            $view = $this->user_db->where('id='.$this->uid)->find();            
            $msg_data['title'] = '修改绑定手机';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】修改绑定手机成功';
            $msg_data['user_id'] = $this->uid;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);              
            $this->ajaxReturn('','修改成功',1);exit();    
        }else{
            $this->ajaxReturn('','数据错误',0);exit();    
        }

    }


    /**
     * @todo 生成验证码并发送到手机
     * @param  int $phone 手机号码
     * @return int $rnd 4位随机数
     */
    public function sendCode() {
        $phone = I("phone");     
        $type = I("type",1,"intval");  
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
            // $msg = "验证码:$rnd,欢迎使用".$this->phone_title.",您的手机号码为$phone";                 
            if($type == 1){
                $message = $rnd.',15';
                // ++++++++++++++2015-04-07 17:04:42 update_by:liying++++++++++
                $status = send_sms($phone,$message,$temp_str);                
                // ++++++++++++++2015-04-07 17:04:42 update_by:liying++++++++++                                   
                // $status = send_sms($phone, $msg);//发送验证码短信
            }elseif($type == 2){
                $status = voice_verify($phone, $rnd);//发送验证码语音
            }       
            $this->ajaxReturn('','',1);      
        }else{
           $this->ajaxReturn('','提交方式错误',0); 
        }
    }   


  
    /**
     * @todo      investment        投资记录
     * @param     $deal_status      交易状态筛选
     * @param     $filter_date      日期筛选
     */
//M修改于 2014年12月25日 13:21:13 ++
    public function investment(){
        import('ORG.Util.Page');
        $invest_db = M('invested');        
        $loan_db = M('loan');
        $web_user_rate_db = M('web_user_rate');
        $filter_date = I('filter_date');
        $deal_status = I('deal_status',0,'intval');           
        $where = array(
            'uid'=>$this->uid,            
        );                     
        //总数查询        
        //++++++++++++添加条件：有效的项目才显示2015-04-14 15:55:33 update_by:liying+++++++++++++
        $count_where = "  and tp_invested.uid=".$this->uid;
        if(!empty($filter_date)){
            if($filter_date == 'today'){
                $filter_date = strtotime(date('Y-m-d',time()));
            }else{            
                $filter_date = NOW_TIME - ($filter_date*24*3600);//选定日期前的日期                                
            }
            //数据总数条件 按投资时间筛选
            $count_where .= " and (tp_invested.create_time >= ".$filter_date." and tp_invested.create_time<=".NOW_TIME.")";
            //按照投标时间筛选投资信息
            $where['create_time'] = array(array('EGT',$filter_date),array('ELT',NOW_TIME));            
        }
        if(!empty($deal_status)){
            //数据总数条件 按项目状态
            $count_where.=" and tp_loan.deal_status=".$deal_status;
            //按项目状态筛选项目
            $loan_where['deal_status'] = $deal_status;
        }  
        $count_where .= " and tp_loan.is_effect=1";               
        //多表查询 关联项目表tp_loan和投资表tp_invested                       
        $extend_sql = "FROM tp_invested LEFT JOIN tp_loan ON tp_loan.id=tp_invested.lid where 1=1 ";        
        $count_sql = $extend_sql.$count_where;                
        $count = M()->query("SELECT count(*) as count_limit ".$count_sql);        
        $count = $count[0]['count_limit']; //返回数据总数
        $this->count = $count;//总数        
        //---统计收益开始-- +++++++++2015-04-14 15:24:45 update_by:liying+++++++++        
        $sum_sql = "select sum(amount) as c,sum(repayment_amount) as r_amount ";
        $sum_sql .= $extend_sql.$count_where;//统计条件拼接(关联项目表，筛选条件)
        $c = M()->query($sum_sql);//关联查询（去掉失效项目同时统计收益等信息）
        $c['c'] = $c[0]['c']?$c[0]['c']:0;
        $c['earnings'] = (!empty($c[0]['r_amount'])&&!empty($c[0]['r_amount']))?($c[0]['r_amount'] - $c[0]['c']):0;
        $this->c = $c;
        //---统计收益结束-- +++++++++2015-04-14 15:24:45 update_by:liying+++++++++
        $page = new Page($count,$this->page_size);
        $limit = $page->firstRow . ',' . $page->listRows;  
        //++++++++++++将投资表主键单独给字段 in_id   2015-04-14 15:55:33 update_by:liying+++++++++++++        
        $invest_ar = M()->query("select *,tp_invested.create_time as in_create_time,tp_invested.id as in_id ".$count_sql." order by tp_invested.id desc limit $limit");             
        $list = array();        
        $lids = array();
        //取项目名称,项目金额,项目ID
        foreach ($invest_ar as $key => $value) {
            $lids[] = $value['lid'];
            //收益 = 预算还款额-本金
            //已还本息 = 收益 - （收益* 投资管理费10%） + 本金
            if($value['status'] == 1){    
                $accrual = $value['repayment_amount'] - $value['amount'];                
                $value['return_money'] = $accrual - ($accrual * 0.1) + $value['amount'];
            }else{
                $value['return_money'] = 0;
            }
            //到期时间赋值
            $end_date = $value['expire_time'];
            //后台没设置成还款中的不显示到期时间
            if($value['deal_status'] != 4){
                $value['end_date'] = 0;
            }else{
                $value['end_date'] = $end_date;
            }
            $list[]= $value;
        }  
        /*读取用户加息情况*/
        $web_user_rate_where['use_status'] = 1;
        $web_user_rate_where['uid'] = $this->uid;
        $web_user_rate_where['lid'] = array('IN',$lids);
        $web_user_rate_res = $web_user_rate_db->where($web_user_rate_where)->select();             
        foreach ($list as $key => $value) {
            foreach ($web_user_rate_res as $k => $v) {
                if($v['lid'] == $value['lid'] && $v['uid'] == $value['uid']){
                    $value['user_add_rate'] = $v['add_rate'];
                }
            }
            $list[$key] = $value;
        }
        $tpl_deal_status = array();
        //交易状态5种赋值给模版
        for ($i=1; $i <=5 ; $i++) { 
            $tpl_deal_status[] = $i;
        }  
        $this->tpl_today = 'today'; //当天凌晨0点的时间戳          
        $this->tpl_one_week = 7;//一周前时间戳
        $this->tpl_one_month = 30;//一个月前时间戳
        $this->tpl_six_month = 180;//一个月前时间戳        
        $this->filter_date = I('filter_date');
        $this->deal_status = $deal_status;
        $this->tpl_deal_status = $tpl_deal_status;
        $this->list = $list;
        unset($list,$tpl_deal_status,$neq_ids);//释放数组
        $this->page = $page->show();//分页        
        $this->display("investment");
    }


    /**
     * @todo  pdf_footer   绘制PDF页脚
     * @param  object $pdf 实例化后的pdf对象
     */
    function pdf_footer($pdf){
        //----页脚输出开始----
        $pdf->SetY(-15); //叶面上的Y坐标，单位为毫米，负数表示从页面底部向上的距离
        $pdf->SetTextColor(128,128,128);  
        $pdf->SetFont('uni','','10');        
        $pdf->Cell(0,10,'“上述内容以金投资平台备份信息为准，自行修改均无效”'); //PageNo函数输出页码                
        $pdf->SetTextColor(0,0,0);
        //----页脚输出结束----        
    }

    
    /**
     * @todo  invest_hetong     PDF合同样本生成
     * @author liying <472591847@qq.com>
     * @since 2015-04-14 14:15:42
     */
    public  function invest_hetong(){  
        //引入pdf类          
        require '/Class/chinese-unicode.php';
        $id = I('get.id',0,'intval');//投资ID       
        $lid = I('get.lid',0,'intval');//项目ID               
        $user_load = $this->user_db->where('id='.$this->uid)->find();//当前用户的信息(投资人信息)     
        $deal  = M('loan')->where('id='.$lid)->find(); //贷款详细信息                              
        $load = M('invested')->where('id='.$id)->find(); //投资详细信息                                            
        $bottom_logo = documentRoot()."/Public/Images/bottom_logo.png";        
        //绘制PDF开始
        $pdf=new PDF_Unicode();
        $pdf->Open();
        $pdf->AddPage();
        $y = $pdf->gety();
        $x = $pdf->getx();
        $pdf->AddUniGBhwFont('uni');
        $pdf->SetFont('uni','',14);
        $pdf->Cell(85); //缩进
        $pdf->Image($bottom_logo,10,0);//插入图片
        $pdf->SetFont('uni','B',16);
        $pdf->cell('',20,"债权收益权转让协议",30,0);//标题 
        $pdf->ln(); //换行 
        $pdf->Cell(170);  
        $pdf->SetFont('uni','',10);   //字体大小 
        $pdf->ln(8);  
        $pdf->cell(10);       
        $loan_number = date('YmdH',$deal['repay_confirm_time']).'-'.$lid;
        $pdf->cell('',0,"项目编号:".$loan_number,30,0);//协议编号    
        $pdf->ln(8);
        $pdf->Cell(10);
        $pdf->cell(10,'',"本转让协议（“本协议”）由以下各方于".date('Y-m-d',$deal['repay_confirm_time'])."签订：",30,0);//投资人
        $pdf->ln(8);
        $pdf->Cell(10);
        $pdf->cell(10,'',"甲方(转让人):马睿",30,0);
        $pdf->ln(10);
        $pdf->Cell(10);
        $pdf->cell(10,'',"乙方（受让人）：".$user_load['real_name'],30,0);
        $pdf->ln(10);
        $pdf->Cell(10);
        $pdf->cell(10,'',"金投资用户名：".$user_load['user_name'],30,0);                      
        $pdf->ln(10);
        $pdf->Cell(10);
        $pdf->cell(10,'',"身份证号：".$user_load['idno'],30,0);                   
        $pdf->Cell(30);
        $pdf->ln(8);
        $pdf->Cell(10);     
        /*表格开始*/
        $pdf->Cell(30,16,'用户名',1,0,"c");               
        //$pdf->setxy($x+20,$y);        
        $pdf->Cell(30,16,'投资金额(元)',1,0,"c");     
        $pdf->Cell(30,16,'起息日',1,0,"c");                 
        $pdf->Cell(30,16,'到期日',1,0,"c");             
        $pdf->Cell(60,16,'预期到期日总产值(元)',1,0,"c");             
        $pdf->ln(16);
        $pdf->Cell(10); //缩进
        $pdf->Cell(30,16,$user_load['user_name'],1,0,"c");   
        $pdf->Cell(30,16,$load['amount'],1,0,"c");   
        $pdf->Cell(30,16,date('Y-m-d',$deal['repay_confirm_time']),1,0,"c");                 
        $pdf->Cell(30,16,date('Y-m-d',$deal['expire_time']),1,0,"c");             
        $pdf->Cell(60,16,$load['repayment_amount'],1,0,"c");           
        $pdf->ln(12);
        $pdf->Cell(10);                          
        $pdf->Cell(30,16,'丙方（平台服务商）：吉林省金湧博发投资管理有限公司，住所：吉林省长春市朝阳区西安大路1016号',30,0);   
        $pdf->ln(10);
        $pdf->Cell(10);                                  
        $pdf->Cell(30,16,'就甲方通过由丙方运营管理的金投资平台（域名为www.p2p222.com，“金投资”）平台向乙方进行其存量债权的收益权转让、',30,0);                   
        $pdf->ln(5);
        $pdf->Cell(10);
        $pdf->Cell(30,16,'并由甲方指定的并经丙方认可的担保方提供连带责任保证的事宜，各方根据平等、自愿的原则，达成本协议如下：',30,0);        
        $pdf->ln(10);
        $pdf->Cell(10); //缩进
        $pdf->SetFont('uni','B',14);                            
        $pdf->Cell(30,16,'一、 收益权转让金额、期限 、还款方式');
        $pdf->SetFont('uni','',10);   //字体大小 
        $pdf->ln(10);
        $pdf->Cell(10);
        $pdf->Cell(30,16,'1. 甲方同意通过金投资平台向乙方转让收益权如下，乙方同意通过金投资平台向甲方发放该等转让：');
        $this->pdf_footer($pdf);        
        $pdf->ln(26);
        $pdf->Cell(10);
        $pdf->Cell(35,16,'项目名称',1,0,"c");           
        $pdf->Cell(65,16,$deal['loan_title'],1,0,"c");                  
        $pdf->Cell(20,16,'年化利率',1,0,"c");   
        $loan_rate = $deal['loan_rate'] + $deal['loan_add_rate'];
        $pdf->Cell(10,16,$loan_rate.'%',1,0,"c");   
        $pdf->Cell(20,16,'期限',1,0,"c"); 
        //$dd_type = $deal['loan_deadline_type'];
        $unit = ($deal['loan_deadline_type'] == 1)?'天':'个月';
        $pdf->Cell(20,16,$deal['loan_deadline'].$unit,1,0,"c");  
        $pdf->ln(16);
        $pdf->Cell(10);
        $pdf->Cell(35,16,'项目转让本金数额',1,0,"c");   
        $pdf->Cell(135,16,$deal['loan_money'].'元',1,0,"c");   
        $pdf->ln(16);
        $pdf->Cell(10);
        $pdf->Cell(35,48,'风险保障措施',1,0,"c"); 
        $y = $pdf->gety();  
        $pdf->setxy($x+45,$y-32); 
        $pdf->Cell(135,80,'1、由高信誉、有实力的专业担保公司进行全额本息担保',1,0);                               
        $pdf->setxy($x+45,$y+5);
        $pdf->MultiCell(130,16,'2、小贷机构与借款企业承担无限连带责任担保');        
        $y = $pdf->gety();
        $pdf->setxy($x+45,$y-10); 
        $pdf->MultiCell(130,16,'3、借款企业的足值抵押');           
        $pdf->setxy($x+45,$y+2);
        $pdf->MultiCell(130,8,'4、金投资设置还款风险金账户，平台按照资金比例提取风险保障金，委托第三方对风险金账户进行严格监管，在风险事项出现且担保机构不能及时代偿情形下迅速启用，确保投资人资金及收益可即时收回，为投资人增加一层重要安全保障');                
        $pdf->ln(1);
        $pdf->Cell(10);
        $pdf->Cell(35,16,'计息办法',1,0); 
        $pdf->Cell(135,16,'标满的次日开始计息（T+1）',1,0); 
        $pdf->ln(16);
        $pdf->Cell(10);
        $pdf->Cell(35,16,'还款方式',1,0); 
        $pdf->Cell(135,16,'到期还本付息',1,0);  
        $pdf->SetFont('uni','B',14);
        $pdf->ln(16);
        $pdf->Cell(10);
        $pdf->Cell(10,16,'二、 转让流程');
        $pdf->ln(16);
        $pdf->Cell(10);
        $pdf->SetFont('uni','',10);
        $content = '2.1 本协议成立：甲方首先在金投资网站上发布相关的债权收益权转让需求，乙方按照金投资的规则，通过在金投资上对甲方发布的收益权转让需求点击“投标”按钮确认时，本协议下所约定的债权收益权转让关系即成立，本协议的签署与否不影响收益权转让关系的成立。
2.2 转让资金冻结：同时，乙方点击“投标”按钮即视为其已经向丙方发出不可撤销的授权指令，授权丙方委托相应的第三方支付机构以丙方名义开立独立的资金监管账户（以下简称“监管账户”），在监管账户中乙方金投资用户名项下虚拟账户（“乙方金投资账户”）中的冻结金额等同于本协议第一条所列的“转让本金数额”的资金。上述冻结在本协议生效时或本协议确定失效时解除。
2.3 受让资金划转：本协议生效的同时（最迟不超过生效后两个工作日），甲方即不可撤销地授权丙方委托相应的第三方支付机构，将金额等同于本协议第一条所列的“转让本金数额”的资金，划转至甲方收款专用账户，划转完毕即视为转让发放成功。';
        $pdf->MultiCell(200,10,$content);        
        $pdf->Cell(10);
        $pdf->SetFont('uni','B',14);
        $pdf->Cell(10,16,'三、 转让资金来源保证');
        $pdf->ln(16);
        $pdf->cell(10);
        $pdf->SetFont('uni','',10);
        $content = '3.1乙方保证其所用于受让收益权的资金来源合法，乙方是该资金的合法所有人，如果第三方对资金归属、合法性问题发生争议，由乙方自行负责解决。如乙方未能解决，则放弃其享有的债权收益权转让所带来的收益。如因乙方资金安全问题导致监管账户被相关司法机关冻结或者执行，乙方应赔偿所有损失。';
        $pdf->MultiCell(200,10,$content);   
        $pdf->Cell(10);
        $pdf->SetFont('uni','B',14);
        $pdf->Cell(10,16,'四、 连带清偿责任');
        $pdf->ln(16);
        $pdf->cell(10);
        $pdf->SetFont('uni','',10);
        $content = '4.1本协议如涉及两人以上转让收益权，任一转让人均应履行本协议项下的义务，对全部转让承担连带清偿责任，乙方有权或授权丙方向任一转让人追索本金、利息、逾期罚息、违约金、滞纳金及其他费用。';
        $pdf->MultiCell(200,10,$content); 
        $pdf->Cell(10);
        $pdf->SetFont('uni','B',14);
        $pdf->Cell(10,16,'五、 本息偿还方式');
        $pdf->ln(16);
        $pdf->cell(10);
        $pdf->SetFont('uni','',10);
        $content = '5.1 甲方同意并承诺，乙方或丙方不直接和任何一个实际借款人发生关系，甲方负责对实际借款人进行本金和利息的催收和归集后，甲方必须按照本协议的约定按时足额的将其归集的实际借款人支付的本息按照本协议第5.2款约定的汇款方式和本协议第一条所列的每期本息数额向乙方支付，以偿还其对乙方的本金和利息。实际借款人是否按时足额向甲方支付本息不影响甲方向乙方按照本协议约定的时间进行本息支付的义务。
5.2 甲方应在到期日当天将本息回款至乙方的平台账户内，如甲方申请提现，提现申请通过后1-3个工作日将提现申请金额回款至乙方指定银行账户内。
5.3 提现回款日为T+I，每日16:00前申请提现的当日审核通过后在次一个工作日回款，16:00之后申请的视为次日申请。
5.4 如果还款日遇到法定假日或公休日，还款日期顺延至该法定假日或者公休日的下一个工作日。';
        $pdf->MultiCell(200,10,$content); 
        $pdf->Cell(10);
        $pdf->SetFont('uni','B',14);
        $pdf->Cell(10,16,'六、 提前还款');
        $pdf->ln(16);
        $pdf->cell(10);
        $pdf->SetFont('uni','',10);
        $content = '甲方可在还款期间内任何时候申请提前还款,在申请提前还款经丙方同意后,甲方应在一个工作日内将所有未偿还的本金、按照实际借款天数计算的利息的款项划转入本协议第5.2款所述的银行帐户。';
        $pdf->MultiCell(200,10,$content); 
        $pdf->Cell(10);
        $pdf->SetFont('uni','B',14);
        $pdf->Cell(10,16,'七、 逾期还款');
        $pdf->ln(16);
        $pdf->cell(10);
        $pdf->SetFont('uni','',10);
        $content = '7.1 到期日24:00前，甲方未足额支付当月应还款至平台账户内的，则视为逾期。
7.2 甲方逾期的，尚未清偿的全部剩余本金和利息自逾期之日按0.05%/日的利率按日计收逾期罚息（“逾期罚息”），直至清偿完毕之日。逾期罚息不计复利。同时，如果甲方有逾期，甲方还须承担逾期滞纳金（“滞纳金”），滞纳金应按尚未清偿的全部剩余本金和利息之和自逾期之日起按0.2%/日的利率按日计收。滞纳金不计复利。
7.3 逾期款项中的利息、逾期罚息不计利息。
7.4 逾期利息、罚息和滞纳金应由甲方支付至本条5.2款所述的银行账户。';
        $pdf->MultiCell(200,10,$content);                 
        $pdf->Cell(10);
        $pdf->SetFont('uni','B',14);
        $pdf->Cell(10,16,'八、 风险保障金细则');
        $pdf->ln(16);
        $pdf->cell(10);
        $pdf->SetFont('uni','',10);
        $content = '如借款企业发生逾期还款或其他突发情况，金投资平台将即时启动还款风险金账户，到期当天24时前代偿投资人全部收益，3个工作日内，100%代偿投资人本金。';
        $pdf->MultiCell(200,10,$content);           
        $pdf->Cell(10);
        $pdf->SetFont('uni','B',14);
        $pdf->Cell(10,16,'九、 违约');        
        $pdf->ln(16);
        $pdf->cell(10);
        $pdf->SetFont('uni','',10);
        $content = '9.1 如果甲方严重违反本协议项下的义务，或未经乙方或丙方同意擅自再次转让本协议项下债务，本协议视为提前终止；同时，甲方应向乙方支付转让本金总额10%的金额作为违约金。甲方须在收到丙方提示终止协议的书面通知后的三日内，向丙方还款专用账户一次性支付余下的所有本金、利息和违约金，丙方再根据其与乙方之间的约定向乙方支付该等资金。
9.2 甲方保证其提供的信息和资料的真实性，不得提供虚假资料或隐瞒重要事实。甲方提供虚假资料或者故意隐瞒重要事实，构成违约，应承担违约责任，同时本协议视为提前终止；甲方应向乙方赔偿项目贷款金额的5倍。甲方须在收到丙方终止协议的书面通知后三日内，向本协议第5.2款所述的账户一次性支付余下的所有本金、利息和违约金。
9.3 发生下列任何一项或几项情形的， (1) 甲方、担保方或者实际借款人的任何财产遭受没收、征用、查封、扣押、冻结等可能影响其履约能力的不利事件，且不能及时提供有效补救措施的；(2) 甲方、担保方或者实际借款人的财务状况出现影响其履约能力的不利变化，且不能及时提供有效补救措施的。或根据乙方或丙方合理判断甲方、担保方或者实际借款人可能发生本条第3款所述的违约事件的，乙方有权自行或委托丙方采取下列任何一项或几项救济措施： (1) 立即暂缓、取消发放全部或部分转让；(2) 宣布已发放转让全部提前到期，甲方应立即偿还所有应付款；(3) 提前终止本协议；(4) 采取法律、法规以及本协议约定的其他救济措施。
9.4 如发现上述任意违约情况，导致本协议无法履行或者提前终止，并且甲方不能按照本条约定向乙方一次性支付余下的所有本金、利息和违约金，则甲方需督促其指定的担保方履行前述第六条涉及的担保责任，代替甲方向乙方一次性支付余下的所有本金、利息和违约金。
9.5 如发生甲方或者担保方违约，导致乙方的权利经非司法救济途径无法实现，乙方在此同意将甲方或者担保方未清偿的本息数额以及的违约金、滞纳金所对应的债权转让给丙方，并通知甲方和担保方。丙方可向甲方或者担保方提起诉讼、仲裁或者其它司法救济。';
        $pdf->MultiCell(200,10,$content);                                         
        $pdf->Cell(10);
        $pdf->SetFont('uni','B',14);
        $pdf->MultiCell(200,10,'十、 本协议的转让未经乙方或丙方事先书面（包括但不限于电子邮件等方式）同意，甲方不得将本协议项下的任何权利义务转让给任何第三方。');
        $pdf->Cell(10);
        $pdf->Cell(10,16,'十一、 其他');
        $pdf->ln(16);
        $pdf->cell(10);
        $pdf->SetFont('uni','',10);
        $content = '11.1 本协议在下述条件全部满足时生效：
（1）本协议由乙方通过金投资以网络在线点击确认投资的方式签订；
（2）自甲方发布的相应收益权转让所需要的资金全部被满足且在乙方金投资账户中被冻结。
11.2 本协议的任何修改、补充均须以金投资平台电子文本形式作出。
11.3 各方均确认，本协议的签订、生效和履行以不违反法律为前提。如果本协议中的任何一条或多条违反适用的法律，则该条将被视为无效，但该无效条款并不影响本协议其他条款的效力。
11.4 如果各方在本协议履行过程中发生任何争议，应友好协商解决；如协商不成，则提交丙方所在地的人民法院进行诉讼。
11.5 各方委托丙方保管所有与本协议有关的书面文件或电子信息。';
        $pdf->MultiCell(200,10,$content);            
        /*表格结束*/
        //$pdf->Cell(30,8,'asdf',1,"c");
        $pdf->Output(); //输出PDF 到浏览器 至此PDF绘制结束                  
    }





    /**
     * @todo      trading           交易记录
     * @param     $filter_date      日期筛选
     * @param     $type             交易类型
     */
    public function trading(){
        import('ORG.Util.Page');
        $filter_date = I('filter_date');
        $type = I('type',0,'intval');
        $obj = M('deal_log');
        $where['uid'] = $this->uid;
        if(!empty($filter_date)){            
            if($filter_date == "today"){
                $filter_date = strtotime(date('Y-m-d',time()));                
            }else{            
                $filter_date = NOW_TIME - ($filter_date*24*3600);//选定日期前的日期                                            
            }
            //按日期筛选
            $where['create_time'] = array(array('EGT',$filter_date),array('ELT',NOW_TIME));            
        }
        if(!empty($type)){            
            //按状态筛选
            $where['type'] = $type;
        }           
        $order = 'create_time DESC,id DESC';
        $count = $obj->where($where)->order($order)->count();        
        $page = new Page($count,$this->page_size);
        $limit = $page->firstRow . ',' . $page->listRows;                                  
        $list = $obj->where($where)->order($order)->limit($limit)->select();  
        foreach ($list as $key => $value) {
            $value['money'] = abs($value['money']);
            $list[$key] = $value;
        }        
        //交易状态5种赋值给模版
        for ($i=1; $i <=5 ; $i++) { 
            $tpl_type[] = $i;
        }         
        $this->tpl_today = 'today'; //当天凌晨0点的时间戳          
        $this->tpl_one_week = 7;//一周前时间戳
        $this->tpl_one_month = 30;//一个月前时间戳
        $this->tpl_six_month = 180;//一个月前时间戳              
        $this->tpl_type = $tpl_type;         
        $this->filter_date = I('filter_date');
        $this->type = $type; 
        $this->list = $list;//数据集
        unset($tpl_type);
        $this->page = $page->show();//分页        
        $this->display();
    }







    /**
     * @todo        assets     资产统计视图
     */
    public function assets(){        
        $this->deal_static = deal_statistics($this->uid);  
        $this->display();
    }



    /**
     * @todo  message   消息中心
     */
    public function message(){
        $obj = M('deal_log');
        $obj_msg_box = M('msg_box');
        $change = I('change')?I('change'):'msg_box';
        $where['uid'] = $this->uid;
        $order = 'is_read ASC,create_time DESC,id DESC';
        import('ORG.Util.Page'); //引入分页  
        switch ($change) {
          case 'deal_log':                
            $count = $obj->where($where)->order($order)->count();
            $page = new Page($count,$this->page_size);
            $limit = $page->firstRow . ',' . $page->listRows;           
            $list = $obj->where($where)->order($order)->limit($limit)->select();
            $lids = array();
            foreach ($list as $key => $value) {
                $lids[] = $value['lid'];
            }
            $loan_db = M('loan');
            $loan_where = array(
                'id'=>array('IN',$lids)
            );
            $loan = $loan_db->field('loan_title,id')->where($loan_where)->select();
            
            foreach ($list as $key => $value) {
                foreach ($loan as $k => $v) {
                    if($v['id'] == $value['lid']){
                        $value['loan'] = $v;
                    }
                }
                $value['money'] = abs($value['money']);
                $list[$key] = $value;
            }
            unset($lids);   
            $this->list = $list;
            break;
            //该用户的账户操作记录列表
          case 'msg_box':
            $where['type'] = 1;//操作类型：账户信息
            $where['web_status'] = 1;//操作状态：前台操作
            $where['user_id'] = $this->uid;
            //echo $this->uid;die;
            $count = $obj_msg_box->where($where)->order($order)->count();
            $page = new Page($count,$this->page_size);
            $limit = $page->firstRow . ',' . $page->listRows;           
            $list = $obj_msg_box->where($where)->order($order)->limit($limit)->select();
            $this->msg_box_list = $list;

        }

        $read_deal_log_where = array(
            'uid'=>$this->uid,
            'is_read'=>0
        );
        $read_msg_box_where = array(
            'user_id'=>$this->uid,
            'is_read'=>0
        );        
        //未读资金消息总数
        $this->deal_log_count = $obj->where($read_deal_log_where)->order($order)->count();        
        //未读账户消息总数
        $this->msg_box_count = $obj_msg_box->where($read_msg_box_where)->order($order)->count();                    
        $this->change = $change;             
        $this->page = $page->show();
        $this->display();
    }


    /**
     * @todo  withdrawal   提现视图
     */
    public function withdrawal(){        
        $this->fast_management = C('fast_management') * 1000;//小数点后三位转换成整数
        $this->replenish_management = C('replenish_management');//补充手续费
        $user_info = $this->user_db->where('id='.$this->uid)->find();        
        $user_info['money'] = sprintf("%.2f", substr(sprintf("%.3f",$user_info['money']), 0, -1));         
        $bank_list = M('bank')->where('uid='.$this->uid)->order('id DESC')->select();
        foreach ($bank_list as $key => $value) {
            $value['real_name_view'] = '**'.substr($value['real_name'], -3);
            $value['bank_number_view'] = substr($value['bank_number'], -4);
            $bank_list[$key] = $value;
        }
        $d_where['is_default'] = 1;
        $d_where['uid'] = $this->uid;
        $this->default_bank_id = M('bank')->where($d_where)->getField("id");        
        $this->bank_list = $bank_list;        
        $this->user_info = $user_info;
        $this->display();
    }


    /**
     * @todo  DoWithdrawal   提现申请处理
     * @param $withdrawal_price 提现金额
     * @param $trade_pwd      交易密码
     * @param $bank_id        绑定银行卡ID
     */    
    public function DoWithdrawal(){
        $data = $_POST;        
        $data['audit_status'] = 0;//审核中
        $data['create_time'] = NOW_TIME;
        $data['uid'] = $this->uid;       
        $user_info = $this->user_db->where('id='.$this->uid)->find();        
        $withdrawal_time = M('withdrawal')->where('uid='.$this->uid)->order('create_time DESC')->getField('create_time');
        $res_date = date('Y-m-d',$withdrawal_time);
        $now_date = date('Y-m-d',NOW_TIME);
        $ido = I('ido',0,'intval');
        if(!M('withdrawal')->autoCheckToken($_POST)){
            $this->error('令牌验证错误');
        }          
        if(empty($ido))
            $this->error('请先同意《金投资充值及提现协议》');
        if(!is_numeric($data['withdrawal_price'])){
            $this->error('提现金额输入错误，请返回后重试');
        }
        //特殊提现最低限额
        if($data['type'] == 1){
            if($data['withdrawal_price'] < C('min_special_withdrawal')){
                $this->error('特殊提现金额不能低于'.C('min_special_withdrawal').'元');
            }
        }
        //普通提现最低限额
        if($data['withdrawal_price'] < C('section1_min')){
            $this->error('提现金额不能低于'.C('section1_min').'元');
        }       
        //提现日期判断，若今天已经有提现记录则弹出错误        
        // if($now_date == $res_date){
        //     $this->error('Sorry!  每人每天仅限提现一次');
        // }        
        //查看是否绑定银行卡
        $bank_res = M('bank')->where('uid='.$this->uid.' AND is_default=1')->find();
        if(!$bank_res){
            $this->error('请先绑定银行卡');            
        } 
        //双重核对银行卡信息 
        $confirm = M('bank')->where('id='.$data['bank_id'].' AND uid='.$this->uid)->find();
        if(!$confirm){
            $this->error('身份确认出错');
        }   
        //正常提现 不投资不可提现
        // if($data['type'] == 0){
        //     /*未投资的用户不可提现*/
        //     $investe_where['uid'] = $this->uid;
        //     $investe_where['status'] = 1;
        //     $investe_count = M('invested')->where($investe_where)->count();    
        //     if($investe_count<=0){
        //         $this->error('请在本站投资并还清至少一次，才可以进行提现');
        //     }
        // }
        //交易密码验证
        if($user_info['trade_pwd'] != md5($data['trade_pwd'])){
            $this->error('交易密码错误,页面正在返回..');
        }

        /* 验证码处理 查询未使用过的验证码 */
        $where_code = array('phone' => $data['phone'], 'status' => 0, 'code_number' => $data['phone_code']);
        $obj_code = M('temp_code')->field(array('id', 'code_number'))->where($where_code)->find();        
        /* 如果验证码和数据库中的密码不匹配,返回错误 */
        if (!$obj_code) {
            $this->error('手机验证码输入错误,请重新输入');   
        } 

        /*提现手续费计算*/
        if($data['type'] == 0){            
            if($data['withdrawal_price'] >= C('section1_min') && $data['withdrawal_price'] < C('section1_max')){
                $management_price = C('section1_money');
            }
            if($data['withdrawal_price'] >= C('section2_min') && $data['withdrawal_price'] < C('section2_max')){      
                $management_price = C('section2_money');
            }     
            if($data['withdrawal_price'] >= C('section3_min') && $data['withdrawal_price'] <= C('section3_max')){      
                $management_price = C('section3_money');
            }
        }elseif($data['type'] == 1){
            //加急手续费计算 = 提现手续费率*提现金额(低于1000元按1000元算) + 补充手续费) 
            if($data['withdrawal_price'] >= 1000){          
                $management_price = $data['withdrawal_price'] * C('fast_management') + C('replenish_management');
            }else{
                $management_price = 1000 * C('fast_management') + C('replenish_management');
            }
        }                 
        $data['management_price'] = $management_price;//手续费入库      
        // $true_price = $management_price + $data['withdrawal_price'];        
        $true_price =  $data['withdrawal_price'];        
        $data_user['money'] = array('exp','money-'.$true_price);
        $data_user['lock_money'] = array('exp','lock_money+'.$true_price);
        //提现金额与用户余额对比
        if($user_info['money'] < $true_price){
            $this->error('提现金额不得低于用户余额');
        }
        $update_user = $this->user_db->where('id='.$this->uid)->save($data_user);
        if($update_user===false){
            $this->error('数据错误');
        }                
        $insert = M('withdrawal')->add($data);        
        if($insert !== false){
            /*提现信息写入交易记录表*/
            $now_user_money = $user_info['money'] - $true_price;//当前余额记录
            $now_user_lock_money = $user_info['lock_money'] + $true_price;//当前冻结金额记录
            $now_user_bonus_money = $user_info['bonus_money'];//当前奖励金额记录
            $now_user_money = number_format($now_user_money,2,'.','');
            $now_user_lock_money = number_format($now_user_lock_money,2,'.','');
            $now_user_bonus_money = number_format($now_user_bonus_money,2,'.','');
            $deal_log_data[0]['uid'] = $this->uid;
            $deal_log_data[0]['lid'] = '';
            $deal_log_data[0]['invested_id'] = '';
            $deal_log_data[0]['money'] = '';
            $deal_log_data[0]['type'] = 3;//交易状态为提现
            $deal_log_data[0]['pay_status'] = 2;//收支状态为支出
            $deal_log_data[0]['detail'] = '提现审核申请中- 提现金额:'.$data['withdrawal_price'].'元。当前剩余金额：'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细            
            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
            insert_deal_log($deal_log_data);                         
            /* 更新验证码状态变为已使用 */
            $data_code['id'] = $obj_code['id'];
            $data_code['status'] = 1;
            M('temp_code')->where($where_code)->save($data_code);               
             $alert_data['title'] = '提现申请提醒';
             $alert_data['content'] = '网站有客户申请了提现，请前往后台查看。';
             $alert_data['status'] = 1;
             $alert_data['create_time'] = time();
             insert_system_alert($alert_data);
             //send_sms('18943023307',$alert_data['content']);//手机提醒
             send_mail('1570208750@qq.com','金投资', iconv('UTF-8','GB2312',$alert_data['title']), $alert_data['content'], "HTML");//邮件提醒                             
            $this->success('提现申请成功，请及时关注审核进度。',U('Member/withdrawal'));
        }else{
            $this->error('数据错误');
        }
    }



    /**
     * @todo   bank_card     银行卡绑定视图
     */
    public function bank_card(){
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $bank_list = M('bank')->where('uid='.$this->uid)->order('id DESC')->select();
        foreach ($bank_list as $key => $value) {
            $value['real_name_view'] = mb_substr($value['real_name'],0,1,'utf-8').'**';
            $value['bank_number_view'] = substr($value['bank_number'], -4);
            $bank_list[$key] = $value;
        }
        $this->user_info = $user_info;
        $this->bank_list = $bank_list;
        $this->display();
    }



    /**
     * @todo   modify_bank    获取银行卡信息
     * @return $id            银行卡主键
     */
    public function modify_bank(){
        $id = I("id",0,"intval");
        if(IS_AJAX){            
            $bank_info = M('bank')->where("id=".$id)->find();
            $bank_info['real_name_view'] = mb_substr($bank_info['real_name'],0,1,'utf-8').'**';
            $bank_info['bank_number_view'] = substr($bank_info['bank_number'],0,4).'*****'.substr($bank_info['bank_number'],-4);
            $this->ajaxReturn($bank_info,'',1);
        }else{
            $this->ajaxReturn('','提交方式错误',0);
        }
    }


    /**
     * @todo     do_modify_bank        修改银行卡处理
     * @param    $upid   intval        银行卡id
     */
    public function do_modify_bank(){
        $refer = $_SERVER['HTTP_REFERER'];
        $upid = I("upid",0,"intval");
        $data = $_POST;        
        $data['uid'] = $this->uid;
        $data['create_time'] = NOW_TIME;
        $b_where['bank_number'] = $data['bank_number'];
        $b_where['id'] = array('NEQ',$upid);
        //查询此号码有没有被其他人使用过
        $begin = M('bank')->where($b_where)->count();
        if(empty($data['province']))
            $this->error('请选择开户省份');
        if(empty($data['city']))
            $this->error('请选择开户城市');
        if(empty($data['bank_address']))
            $this->error('请填写开户行全称');                                                        
        //表单令牌验证
        if(!M('bank')->autoCheckToken($_POST)){
            $this->error('令牌验证错误');
        }           
        if($begin != 0){
            $this->error('此号码已被使用,若非本人操作,请及时联系客服');
        }         
        $where['id'] = $upid;
        $update = M('bank')->where($where)->save($data);//插入银行卡表        
        if($update !== false){            
            //写入动作表
            $remember_sql = M('bank')->getLastSql();
            $view = $this->user_db->where('id='.$this->uid)->find();            
            $msg_data['title'] = '用户修改银行卡信息';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】修改银行卡信息成功';
            $msg_data['user_id'] = $this->uid;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);                
            redirect($refer);
        }else{
            $this->error('数据错误');
        }        
    }



    /**
     * @todo     do_add_bank        修改银行卡处理
     * @param    $upid   intval        银行卡id
     */
    public function do_add_bank(){
        $refer = $_SERVER['HTTP_REFERER'];
        $data = $_POST;                
        $data['bank_number'] = trimall($data['bank_number']);
        $data['uid'] = $this->uid;
        $data['create_time'] = NOW_TIME;
        $begin = M('bank')->where("bank_number='".$data['bank_number']."'")->count();
        $user_info = $this->user_db->where('id='.$data['uid'])->find();    
        if(empty($data['real_name']))
            $this->error('请输入户名');
        if(empty($data['bank_number']))
            $this->error('请输入银行卡号');
        if(empty($data['bank_name']))
            $this->error('请选择银行名称');
        if(empty($data['province']))
            $this->error('请选择开户省份');
        if(empty($data['city']))
            $this->error('请选择开户城市');
        if(empty($data['bank_address']))
            $this->error('请填写开户行全称');                                                        
        //表单令牌验证
        if(!M('bank')->autoCheckToken($_POST)){
            $this->error('令牌验证错误');
        }          
        //判断是否通过实名认证
        if(empty($user_info['real_name'])  || empty($user_info['idno'])){
            $this->error('请先绑定真实姓名和身份证号','personal');
        }        
        //判断是否与认证姓名保持一致
        if($user_info['real_name'] != $data['real_name'])
            $this->error('户名请与实名认证姓名保持一致');   
        if($begin != 0){
            $this->error('同一银行卡不可绑定多个会员账户,请更换后重试');
        }         
        $res = M('bank')->where('uid='.$this->uid)->count();//查看此用户绑定银行卡数量
        //绑定银行卡数量最高限额
        if($res == 5)
            $this->error('最多只允许绑定5张银行卡');        
        if($res == 0){
            $data['is_default'] = 1;//若这是第一张银行卡，则默认
            update_score($this->uid,20);//通过绑定银行卡获得20积分(仅限第一次设置)
            $score_log_data[0]['uid'] = $this->uid;
            $score_log_data[0]['lid'] = '';
            $score_log_data[0]['invested_id'] = '';
            $score_log_data[0]['score'] = 20;
            $score_log_data[0]['type'] = 3;//交易状态：认证操作
            $score_log_data[0]['pay_status'] = 1;//收支状态为收入
            $now_user_score = $user_info['score'] + 20;
            $score_log_data[0]['detail'] = '成功绑定银行卡：奖励积分为20分。当前剩余积分：'.$now_user_score.'分';//明细
            $score_log_data[0]['create_time'] = NOW_TIME;//交易时间              
            insert_score_log($score_log_data);//写入积分消息表
        }                 
        $insert = M('bank')->add($data);//插入银行卡表
        if($insert !== false){            
            //写入动作表
            $remember_sql = M('bank')->getLastSql();
            $view = $this->user_db->where('id='.$this->uid)->find();            
            $msg_data['title'] = '用户绑定银行卡';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】用户绑定银行卡成功';
            $msg_data['user_id'] = $this->uid;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);                
            redirect($refer);
        }else{
            $this->error('数据错误');
        }
    }



    /**
     * @todo    do_default_bank  银行卡设置默认处理
     * @param   $id    intval    银行卡ID主键
     */
    public function do_default_bank(){
        $id = I("id",0,"intval");        
        if(IS_AJAX && IS_POST){
            if(empty($id)){
                $this->ajaxReturn('','参数错误',0);exit();    
            }
            //将这张卡设为默认
            M('bank')->where('id='.$id)->setField('is_default',1);
            $where['uid'] = $this->uid;
            $where['id'] = array('NEQ',$id);
            //还原该用户其他卡变非默认
            M('bank')->where($where)->setField('is_default',0);
            //写入动作表
            $remember_sql = M('bank')->getLastSql();
            $view = $this->user_db->where('id='.$this->uid)->find();            
            $msg_data['title'] = '默认银行卡设置';
            $msg_data['content'] = '【前台会员：'.$view['user_name'].'】设置默认银行卡成功';
            $msg_data['user_id'] = $this->uid;
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 1;//账户操作
            $msg_data['web_status'] = 1;//状态：前台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);                          
            $this->ajaxReturn($data,'',1);exit();
        }else{
            $this->ajaxReturn('','提交方式错误',0);exit();
        }
    }

    /**
     * @todo  TopUp    充值页面视图
     */
    public function TopUp(){
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $this->no = I('no',0,'intval');
        //可用余额=用户余额+奖励金额
        //$user_info['money'] = sprintf("%.2f", substr(sprintf("%.3f",$user_info['money']+$user_info['bonus_money']), 0, -1)); 
        $user_info['money'] = sprintf("%.2f", substr(sprintf("%.3f",$user_info['money']), 0, -1)); 
        $this->user_info = $user_info;
        $this->display();
    }


    /**
     * @todo  incharge_redirect    跳转新生支付接口并传值
     * @return 跳转，并传递金额和用户ID
     */
    public function incharge_redirect(){
        $money = floatval($_REQUEST['money']);
        // $slt_bank_name = $_REQUEST['slt_bank_name'];//选择的充值网银
        // if(empty($slt_bank_name))
        //     $this->error('请选择充值网银');
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $user_id = $this->uid;
        $ido = I('ido',0,'intval');
        if($money<=0)
           $this->error('金额输入错误');  
        if(empty($ido))
            $this->error('您需同意《金投充值及提现协议》');
        // redirect("/Pay/xin_pay.php?money=".$money."&user_id=".$user_id."&user_name=".$user_info['user_name']."&user_pwd=".$user_info['user_pwd']."&slt_bank_name=".urlencode($slt_bank_name));
        $user_id = rawurlencode(getEncrypt($user_id));
        redirect("/Pay/xin_pay.php?money=".$money."&dd=".$user_id."&slt_bank_name=".rawurlencode($slt_bank_name));
    }   


    /**
     * @todo  incharge_msg    接口回调,提示用户
     * @return 提示文字和连接
     */ 
    public function incharge_msg()
    {        
        $payAmount = $_REQUEST["payAmount"];
        $msg = "恭喜您，充值成功!<br>您本次充值金额为<font style='color:#FF000'>".format_price($payAmount/100)."</font>";
        $this->success($msg,'index');
    }  


    /**
     * @todo     MsgIsRead   修改消息阅读状态  
     * @param    $change     要修改的 消息表名 
     * @param    $id         消息ID
     * @param    $ids        批量选中的消息ID集合
     * @return   json
     *               {data: 返回的数据。info:提示信息。status:操作状态 1 成功 0  失败}
     */
   public function MsgIsRead(){
        $change = I('change');
        $id = I('id',0,'intval');
        $ids = I('ids');
        $obj = M($change);
        $data['is_read'] = 1;   
        if(!empty($id))     
          $read = $obj->where('id='.$id)->save($data);
        if(!empty($ids))
          $reads = $obj->where('id in('.$ids.')')->save($data);
        if(IS_AJAX){
            if($read !== false || $reads !== false){
                $this->ajaxreturn('','',1);
            }else{
                $this->ajaxreturn('','数据错误',0);
            }
        }else{
            $this->error('提交方式错误');
        }
   }



   /**
    * @todo RedEnvelope    我的红包视图
    */
   public function RedEnvelope(){  
        $obj = M('bonus');
        import('ORG.Util.Page'); //引入分页                       
        $where['uid'] = $this->uid;    
        $order = 'create_time DESC,id DESC';        
        $count = $obj->where($where)->order($order)->count();        
        $page = new Page($count,$this->page_size);
        $limit = $page->firstRow . ',' . $page->listRows;                                  
        $list = $obj->where($where)->order($order)->limit($limit)->select(); 
        foreach ($list as $key => $value) {
            if($value['status']==1){
                $value['effect_time'] = '-';//若已经使用，则截止日期显示 - 
            }else{
                $value['effect_time'] = $value['create_time'] + ($value['limit_days'] * 24 * 3600);//截至日期
            }
            $list[$key] = $value;
        }        
        $this->list = $list;
        $this->page = $page->show();
        $this->display('red_envelope');
   }





   /**
    * @todo    AutoMaticList  自动投标 列表页
    */
   public function AutoMaticList(){
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $auto_arr = M('auto_invested')->where('uid='.$this->uid)->find();
        $this->auto_arr = $auto_arr;
        $this->user_info = $user_info;            
        $this->display('automatic_list');
   }  


   /**
    * @todo   AutoMatic   自动投标视图
    */
   public function AutoMatic(){
        $id = I('get.id',0,'intval');
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        if(!empty($id)){
            $auto_arr = M('auto_invested')->where('uid='.$this->uid)->find();        
            $this->auto_arr = $auto_arr;
            $this->upid = $id;
        }
        $this->user_info = $user_info;
        $this->display('automatic');
   }


   /**
    * @todo   DoAutoMatic    处理自动投标规则
    */
   public function DoAutoMatic(){
        $data = $_POST;
        $data['uid'] = $this->uid;
        $upid = I('upid',0,'intval');
        $data['switch'] = '1';//开关状态：0 关闭 1 开启
        $reg_number = '/^[0-9]+$/';        
        if(!preg_match($reg_number,$data['every_money']))
            $this->error('请输入正确的每次投标金额');
        if($data['deadline_status'] == 1){
            if(!preg_match($reg_number,$data['min_deadline']))
                $this->error('请输入正确的最少借款期限');
            if(!preg_match($reg_number,$data['max_deadline']))
                $this->error('请输入正确的最高借款期限');            
        }
        if($data['rate_status'] == 1){
            if(!preg_match($reg_number,$data['min_rate']))
                $this->error('请输入正确的最小年利率');            
        }        
        if(!preg_match($reg_number,$data['reserve_money']))
            $this->error('请输入正确的账户保留金额');
        $user_res = M('auto_invested')->where('uid='.$data['uid'])->find();
        if($user_res && !empty($upid)){
            $data['update_time'] = NOW_TIME;
            if(M('auto_invested')->where('id='.$upid)->save($data) !== false){
                $this->success('保存自动投标规则成功');
            }else{
                $this->error('数据错误');
            }
        }else{
            $data['create_time'] = NOW_TIME;
            if(M('auto_invested')->add($data) !== false){
                $this->success('添加自动投标规则成功');
            }else{
                $this->error('数据错误');
            }            
        }
   }

   

   /**
    * @todo    SwitchAutoMatic  自动投标规则开关
    * @param   $id              规则主键ID
    * @param   $turn            开关状态 0 关闭 1 开启
    */
   public function SwitchAutoMatic(){
        $id = I('get.id',0,'intval');
        $turn = I('get.turn',0,'intval');
        $data['switch'] = $turn;
        $msg = ($turn == 0)?'关闭':'开启';
        if(M('auto_invested')->where('id='.$id)->save($data) !== false){            
            $this->success($msg.'自动投标成功');
        }else{            
            $this->error('数据错误');
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
     * @todo   HandleMsg   用于控制登录后的技巧提示控制
     */
    public function HandleMsg(){
        $no_msg = I('no_msg',0,'intval');
        if(IS_AJAX){                   
            $date_exp = 3600 * 24 * 7;//有效时间设置为7天
            if($no_msg == 1){
                cookie('no_msg',1,$date_exp);                            
                $this->ajaxReturn('',$_COOKIE['no_msg'],1);exit();            
            }else{                            
                cookie('no_msg',null,$date_exp);                 
                $this->ajaxReturn('',$_COOKIE['no_msg'],1);exit();                           
            }
        } 
    }


    /**
     * @todo      SignIn    会员签到功能
     * @param     type      业务名
     */
    public function SignIn(){
        //echo strtotime(date('Y-m-d',strtotime(date("Y-m-d",strtotime("-1 day")))));  die;
        $type = I('post.type');
        if(!IS_AJAX){
            $this->ajaxReturn('','请勿非法操作',1);exit();
        }
        $today = strtotime(date('Y-m-d',time()));    //今天0点
        $tomorrow = strtotime(date("Y-m-d",strtotime("+1 day")));  //明天0点                
        $yesterday = date('Y-m-d',strtotime(date("Y-m-d",strtotime("-1 day")))); //昨天的日期               
        $score_log_db  = M('score_log');
        $score_log_where['type'] = 1;//交易类型：签到
        $score_log_where['uid'] = $this->uid;
        $score_log_where['pay_status'] = 1;//收支状态：收入
        $score_log_where['create_time'] = array(array('EGT',$today),array('LT',$tomorrow),'AND');//时间为今天
        $sign_res = $score_log_db->where($score_log_where)->find();//检查该用户是否已签到
        if($sign_res){
            $this->ajaxReturn('','您今天已经签到过了，明天再来吧!',1);exit();
        }                
        if($type == 'sign_in'){
            //读取会员信息
            $user_info = $this->user_db->where('id='.$this->uid)->find();                                  
            $score = 5;//初始化赠送的积分                        
            //重新查询签到后的记录                     
            if(!empty($user_info['signin_time'])){
                //如果上次签到日期等于昨天的日期即是连续签到
                if($yesterday == date('Y-m-d',$user_info['signin_time'])){                    
                    $count_continu = $user_info['continu_login'] + 1;//加上本次之后，共签到的次数
                    if($count_continu % 7 == 0 ){
                        $score = 20; //连续签到7天 送20积分
                    }                    
                }else{
                    //签到中断 次数归零
                    $this->user_db->where('id='.$this->uid)->setField('continu_login',0);            
                }
            }            
            //刷新记录签到时间
            $this->user_db->where('id='.$this->uid)->setField("signin_time",time());           
            //连续签到字段 自加
            $this->user_db->where('id='.$this->uid)->setInc('continu_login',1);           

            update_score($this->uid,$score);//签到送积分
            $score_log_data[0]['uid'] = $this->uid;
            $score_log_data[0]['lid'] = '';
            $score_log_data[0]['invested_id'] = '';
            $score_log_data[0]['score'] = $score;
            $score_log_data[0]['type'] = 1;//交易状态：签到
            $score_log_data[0]['pay_status'] = 1;//收支状态为收入
            $now_user_score = 0;//初始化剩余积分
            $now_user_score = $user_info['score'] + $score;
            $score_log_data[0]['detail'] = '签到成功：获得积分为 '.$score.' 分。当前剩余积分:'.$now_user_score.'分';//明细
            $score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
            insert_score_log($score_log_data); //插入积分记录表
            //再次查询最新的用户信息，得到用户的连续签到天数
            $data['continu_login'] = $this->user_db->where('id='.$this->uid)->getField('continu_login');                       
            $count_where['type'] = 1;//交易类型：签到
            $count_where['uid'] = $this->uid;
            $count_where['pay_status'] = 1;//收支状态：收入
            //查询操作后的积分总额,若连续签到天数大于0，则显示连续签到得分，否则连续签到得分归0
            //$count_res = $score_log_db->field('sum(score) as count_score')->where($count_where)->find();                        
            $data['ref_score'] = $this->user_db->where('id='.$this->uid)->getField('score');//读取最新的用户总积分            
            //$data['count_score'] = ($data['continu_login'] > 0 ) ? $count_res['count_score'] : 0;
            $data['count_score'] = $score;//本次签到获得的积分
            $this->ajaxReturn($data,'签到成功,获得'.$score.'积分',1);
        }else{
            $this->ajaxReturn('','请求方式错误',0);exit();
        }
    }



    /**
     * @todo   integral  我的积分  
     */
    public function integral(){ 
        import('ORG.Util.Page');
        $score_log_db = M('score_log');
        /*改变阅读状态*/
        if(IS_AJAX){
            if($_POST['is_read'] == 1){
                $score_log_db->where('uid='.$this->uid.' AND is_read=0')->setField('is_read',1);
                $this->ajaxReturn('','',1);exit();
            }else{
                $this->ajaxReturn('','请求方式错误',0);exit();
            }        
        }
        $score_log_where['uid'] = $this->uid;
        $count = $score_log_db->where($score_log_where)->count();
        $page = new Page($count,5);
        $limit = $page->firstRow . ',' . $page->listRows;                  
        $score_log_ar = $score_log_db->where($score_log_where)->limit($limit)->order('create_time DESC,id DESC')->select();
        $sum_score_ar = score_statistics($this->uid,'','',$type,1,'sum');//获取用户积分总收入
        $use_sum_score_ar = score_statistics($this->uid,'','',$type,2,'sum');//获取用户积分总支出
        $user_info = $this->user_db->where("id=".$this->uid)->find();		
        $this->user_info = $user_info;//当前用户信息
        $this->page = $page->show();//分页
        $this->score_log_list = $score_log_ar;//消息列表
        $this->sum_score = $sum_score_ar['sum_score'];//收入总积分
        $this->use_sum_score = $use_sum_score_ar['sum_score'];//支出总积分
		$this->score_exchange = $user_info['score']; //可兑换积分
        $this->display();
    }



    /**
     * @todo   invitation  邀请好友  
     */
    public function invitation(){        
        $uid = $this->uid;                  
        $referral_id = urlencode(getEncrypt($this->uid));  //推广ID(加密串)         
        $rands = rands();//不重复随机数 
        $host = $_SERVER['HTTP_HOST'];        
        //会员推广链接 
        $this->referral_links = "http://".$host.U("user/Register").'?pid='.$referral_id;        
        $this->display();
    }


    public function invitation_list(){
        $uid = $this->uid;   
        $page_size = 10;
        import('ORG.Util.Page');              
        $change = I('change');
        $score_log_db = M('score_log');          
        $deal_log_db = M('deal_log');  
        $order = 'create_time DESC,id DESC';      
        switch ($change) {
            /*积分奖励列表*/
            case 'score_log':
                $score_log_where['uid'] = $uid;//用户ID        
                $score_log_where['type'] = 2;//交易类型：邀请奖励
                $score_log_where['pay_status'] = 1;//交易状态：收入
                //积分奖励记录分页
                $score_count = $score_log_db->where($score_log_where)->count();
                $score_page = new Page($score_count,$page_size);        
                $score_limit = $score_page->firstRow . ',' . $score_page->listRows;                     
                $score_log_res = $score_log_db->where($score_log_where)->limit($score_limit)->order($order)->select();                
                //奖励来源用户ID 整理，压入数组       
                $from_uids = array();
                foreach ($score_log_res as $key => $value) {
                     $from_uids[] = $value['from_uid'];
                } 
                //查询来源用户信息
                $from_user_where['id'] = array('IN',$from_uids);
                $from_user_info = $this->user_db->where($from_user_where)->limit($limit)->select();        
                //来源用户信息压入记录集
                foreach ($score_log_res as $key => $value) {
                    foreach ($from_user_info as $k => $v) {
                        if($v['id'] == $value['from_uid']){
                            $value['user_name'] = $v['user_name'];
                            //隐藏截取用户名
                            if(!empty($value['user_name'])){
                                $value['user_name'] = substr($value['user_name'], 0,2).'****'.substr($value['user_name'], -2);
                            }                            
                        }
                    }
                    $score_log_res[$key] = $value;
                }        
                unset($from_uids);        
                $this->score_page = $score_page->show();//积分奖励记录分页                
                $this->score_log_res = $score_log_res;//奖励积分记录                    
            $this->display('invitation_score_log');
            break;
            /*金额奖励列表*/
            case 'deal_log':
                $deal_log_where['uid'] = $uid;//用户ID       
                $deal_log_where['type'] = 9;//交易类型：邀请奖励
                $deal_log_where['pay_status'] = 1;//交易状态：收入
                //金额奖励记录分页
                $deal_log_count = $deal_log_db->where($deal_log_where)->count();                
                $deal_log_page = new Page($deal_log_count,$page_size);
                $deal_log_limit = $deal_log_page->firstRow . ',' . $deal_log_page->listRows;     
                $deal_log_res  = $deal_log_db->where($deal_log_where)->limit($deal_log_limit)->order($order)->select();            
                //奖励来源用户ID 整理，压入数组       
                $from_uids = array();
                foreach ($deal_log_res as $key => $value) {
                     $from_uids[] = $value['from_uid'];
                } 
                //查询来源用户信息
                $from_user_where['id'] = array('IN',$from_uids);
                $from_user_info = $this->user_db->where($from_user_where)->limit($limit)->select();        
                //来源用户信息压入记录集
                foreach ($deal_log_res as $key => $value) {
                    foreach ($from_user_info as $k => $v) {
                        if($v['id'] == $value['from_uid']){
                            $value['user_name'] = $v['user_name'];
                            //隐藏截取用户名
                            if(!empty($value['user_name'])){
                                $value['user_name'] = substr($value['user_name'], 0,2).'****'.substr($value['user_name'], -2);
                            }
                        }
                    }
                    $deal_log_res[$key] = $value;
                }        
                unset($from_uids);                    
                $this->deal_log_page = $deal_log_page->show();//金额奖励记录分页
                $this->deal_log_res = $deal_log_res;//奖励金额记录                              
            $this->display('invitation_deal_log');
            break;
            /*推荐会员注册列表*/
            default:
            $user_where['pid'] = $uid;            
            $user_count = $this->user_db->where($user_where)->count(); 
            $user_page = new Page($user_count,$page_size); 
            $user_limit = $user_page->firstRow . ',' . $user_page->listRows;           
            $user_list = $this->user_db->where($user_where)->limit($user_limit)->order($order)->select();
            foreach ($user_list as $key => $value) {                
                //隐藏截取用户名
                if(!empty($value['user_name'])){
                    $value['user_name'] = substr($value['user_name'], 0,2).'****'.substr($value['user_name'], -2);
                }                
                $user_list[$key] = $value;
            }
            $this->user_page = $user_page->show();            
            $this->user_list = $user_list;
            $this->display('invitation_user_list');
            break;                       
        }            
    }


	/**
     * @todo   invitation  积分兑换处理  
     * @param  $exchange   要兑换的积分
     */
	public function integral_handle(){
		$uid = $this->uid ;//会员id
		$exchange = I("exchange",0,'intval') ; //兑换积分
        $res = $this->user_db->where('id='.$uid)->find();
        if(empty($exchange)){
            $this->ajaxreturn('','请输入兑换金额',0);
        }        
        if($exchange > $res['score']){
            $this->ajaxreturn('','您当前的积分数量不足兑换输入的积分！',0);
        }
		$exchange_money = $exchange / 10 ;
		$remain_score = $res['score'] - $exchange;//兑换后剩余积分
		update_score($uid,-$exchange);//扣除用户兑换的积分
		update_bonus_money($uid,$exchange_money);//给此用户充值奖励金额             
		$score_log_data[0]['uid'] = $uid;
		$score_log_data[0]['lid'] = '';
		$score_log_data[0]['invested_id'] = '';
		$score_log_data[0]['score'] = $exchange;
		$score_log_data[0]['type'] = 6;//交易状态：积分兑换
		$score_log_data[0]['pay_status'] = 2;//收支状态为支出
		$score_log_data[0]['detail'] = '积分兑换：扣除积分为'.$exchange.'分。当前剩余积分:'.$remain_score.'分';//明细
		$score_log_data[0]['create_time'] = NOW_TIME;//交易时间   
		insert_score_log($score_log_data); //插入积分记录表                                                       
		/*写入交易记录表*/
		$deal_log_data[0]['uid'] = $uid;//用户ID
		$deal_log_data[0]['lid'] = '';
		$deal_log_data[0]['invested_id'] = '';
		$deal_log_data[0]['money'] = $exchange_money;
		$deal_log_data[0]['type'] = 10;//交易状态：积分兑换
		$deal_log_data[0]['pay_status'] = 1;//收支状态为收入
        $now_user_money = $res['money'];//当前剩余金额
        $now_user_lock_money = $res['lock_money'];//当前剩余冻结金额                                              
        $now_user_bonus_money = $res['bonus_money'] + $exchange_money;//当前剩余奖励金额记录
		$deal_log_data[0]['detail'] = '积分兑换获得奖励：奖励金额为'.$exchange_money.'元。当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细        
		$deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
		insert_deal_log($deal_log_data);               
		$this->ajaxreturn('','恭喜:积分兑换成功！',1);
	}


    /**
     * @todo    VerifyTrade            异步验证交易密码正确性
     * @param   string   $trade_pwd    用户输入的交易密码   
     * @return  Json  {info:提示信息,status:验证状态 0 错误 1 正确}
     */
    public function VerifyTrade(){
        if(IS_AJAX){
            $trade_pwd = I('trade_pwd');
            $user_trade_pwd = $this->user_db->where("id=".$this->uid)->getField("trade_pwd");
            if($user_trade_pwd != md5($trade_pwd)){
                $data['info'] = '交易密码错误';
                $data['status'] = '0';
            }else{
                $data['info'] = '';
                $data['status'] = 1;                
            }
            $this->ajaxreturn($data,"JSON");exit();
        }else{
            $data['info'] = '请求方式错误';
            $data['status'] = '0';
            $this->ajaxreturn($data,"JSON");exit();
        }
    }
}
 ?>