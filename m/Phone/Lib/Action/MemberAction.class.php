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
     * @todo   Index 会员首页视图
     */
    public function Index(){  
        $investe_db = M('invested');
        $log_db = M('deal_log');  
        /*manage_tx start*/      
        $user_info = $this->user_db->where('id='.$this->uid)->find();        
        $week_key = date("w");//当前星期数值
        $week_arr = array("日","一","二","三","四","五","六");
        $this->week = $week_arr[$week_key];//获取当前星期        
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
        //资金记录条数读取
        $dlg_where['uid'] = $this->uid;
        $dlg_where['is_read'] = 0;//阅读状态：0未读 1已读
        $dlg_count = M('deal_log')->where($dlg_where)->count();                
        $com_count = $dlg_count + $com_msg_count;                    
        $this->com_count = $com_count;
        $this->sign_status = $sign_status;      
        $this->count_score = $count_score;//连续签到得分           
        /*manage_tx end*/      
        $t6 = strtotime("-6 month");//6个月前
        $t5 = strtotime("-5 month");//5个月前
        $t4 = strtotime("-4 month");//4个月前
        $t3 = strtotime("-3 month");//3个月前
        $t2 = strtotime("-2 month");//2个月前
        $t1 = strtotime("-1 month");//1个月前
        $t0 = NOW_TIME;//当前日期
        $old_date = strtotime(date("Y-m-d H:i:s",$t));                
        $where_month['is_effect'] = 1;
        $where_month['is_delete'] = 0;
        $where_month['uid'] = $this->uid;
        $field_month = 'sum(amount) as sum_month';//投资本金总和
        /*6个月前投资总额*/
        $where_month6['create_time'] = array(array('EGT',$t6),array('ELT',$t5));
        $where_month6 = array_merge($where_month,$where_month6);                
        $user_info['month_5'] = $investe_db->field($field_month)->where($where_month6)->find();
        if(empty($user_info['month_5']['sum_month'] ))
            $user_info['month_5']['sum_month']  = 0;        
        $user_info['month_5_name'] = date('m',$t5).'月';
        /*5个月前投资总额*/
        $where_month4['create_time'] = array(array('EGT',$t5),array('ELT',$t4));
        $where_month4 = array_merge($where_month,$where_month4);                
        $user_info['month_4'] = $investe_db->field($field_month)->where($where_month4)->find();
        if(empty($user_info['month_4']['sum_month']))
            $user_info['month_4']['sum_month'] = 0;        
        $user_info['month_4_name'] = date('m',$t4).'月';                
        /*4个月前投资总额*/
        $where_month3['create_time'] = array(array('EGT',$t4),array('ELT',$t3));
        $where_month3 = array_merge($where_month,$where_month3);                
        $user_info['month_3'] = $investe_db->field($field_month)->where($where_month3)->find();
        if(empty($user_info['month_3']['sum_month'] ))
            $user_info['month_3']['sum_month']  = 0;        
        $user_info['month_3_name'] = date('m',$t3).'月';                
        /*3个月前投资总额*/
        $where_month2['create_time'] = array(array('EGT',$t3),array('ELT',$t2));
        $where_month2 = array_merge($where_month,$where_month2);                
        $user_info['month_2'] = $investe_db->field($field_month)->where($where_month2)->find();
        if(empty($user_info['month_2']['sum_month']))
            $user_info['month_2']['sum_month']  = 0;        
        $user_info['month_2_name'] = date('m',$t2).'月';
        /*2个月前投资总额*/
        $where_month1['create_time'] = array(array('EGT',$t2),array('ELT',$t1));
        $where_month1 = array_merge($where_month,$where_month1);                
        $user_info['month_1'] = $investe_db->field($field_month)->where($where_month1)->find();
        if(empty($user_info['month_1']['sum_month'] ))
            $user_info['month_1']['sum_month']  = 0;
        $user_info['month_1_name'] = date('m',$t1).'月';
        /*上个月至当前月投资总额*/
        $where_month0['create_time'] = array(array('EGT',$t1),array('ELT',$t0));
        $where_month0 = array_merge($where_month,$where_month0);                
        $user_info['month_0'] = $investe_db->field($field_month)->where($where_month0)->find();
        if(empty($user_info['month_0']['sum_month'] ))
            $user_info['month_0']['sum_month']  = 0;
        $user_info['month_0_name'] = date('m',$t0).'月';                
        //p($user_info['month_1']);die;
        //$investe_chart = $investe_db->field('')
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
        /*未读消息条数*/
        $msg_box_db = M('msg_box');
        $read_deal_log_count = $log_db->where('uid='.$this->uid.' AND is_read=0')->count();
        $read_msg_box_count = $msg_box_db->where('user_id='.$this->uid.' AND is_read=0')->count();                                
        $this->count_read = $read_deal_log_count + $read_msg_box_count;
        $this->deal_static = deal_statistics($this->uid); 
        //p($list);die;
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
            $user_info['repeat_idno'] = $left_idno.$center_idno.$right_idno;                
        }
        $this->user_info = $user_info;
		$this->Prompt = I("Prompt",0,'intval'); //弹出使用技巧
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
     * @todo  email  邮箱绑定视图
     */
    public function email(){
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $mail_ar = explode('@',$user_info['email']);
        if(count($mail_ar[0])>0){
            $len_mail = strlen($mail_ar[0]);                           
            $left_mail = substr($mail_ar[0],0,3);
            $center_mail = str_repeat('*', $len_mail - 3 - 3); 
            $right_mail = substr($mail_ar[0],-3);
            $user_info['repeat_email'] = $left_mail.$center_mail.$right_mail.'@'.$mail_ar[1];
        }                
        $this->user_info = $user_info;
        $this->display();
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
        $host = 'http://www.p2p222.com';        
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
     * @todo  id_card  身份证绑定视图
     */
    public function id_card(){
        $this->display();
    }





    /**
     * @todo  FindIdno    身份证绑定
     * @param $real_name   姓名
     * @param $idno        身份证号
     */
    public function FindIdno(){
       $real_name = I('post.real_name');
       $idno = I('post.idno');       
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
            if(empty($res['idno'])){
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
     * @todo  ModifyPwd  修改登陆密码视图
     */
    public function ModifyPwd(){
        $this->display();
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
     * @todo  MdfTrading   设置交易密码视图
     */
    public function MdfTrading(){
        $this->user_info = $this->user_db->where('id='.$this->uid)->find();
        $this->display();
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
     * @todo   phone1    修改手机号第一步
     */
    public function phone1(){
        $this->user_info = $this->user_db->where('id='.$this->uid)->find();
        $this->display();
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
        $code_number = I('post.code_number',0,'intval'); 
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
        session('old_phone',$phone);
        $redirect_url = U('Member/phone2');//若验证成功则跳转到修改页        
        $this->ajaxReturn($redirect_url,'',1);exit();
    }



    /**
     * @todo   phone2    修改手机号第一步
     */
    public function phone2(){
        $old_phone = session('old_phone');
        if(empty($old_phone)){
            $this->error('参数错误');    
        }else{
            $this->user_info = $this->user_db->where('id='.$this->uid)->find();
            $this->display();
        }
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
        $new_code_number = I('post.new_code_number',0,'intval'); 
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
            session('old_phone',null);
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
            $msg = "验证码:$rnd,欢迎使用".$this->phone_title.",您的手机号码为$phone";                 
            if($type == 1){                
                $message = $rnd.',15';
                // ++++++++++++++2015-04-08 09:55:53 update_by:liying++++++++++                
                $status = send_sms($phone,$message,$temp_str);                
                // ++++++++++++++2015-04-08 09:55:53 update_by:liying++++++++++                   
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
        $filter_date = I('filter_date');
        $deal_status = I('deal_status',0,'intval');           
        $where = array(
            'uid'=>$this->uid,            
        );                     
        //总数查询
        $count_where = " and tp_invested.uid=".$this->uid;
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
        if(empty($deal_status)){
            //数据总数条件 按项目状态
            $count_where.=" and tp_loan.deal_status=4";
            //按项目状态筛选项目
            $loan_where['deal_status'] = 4;            
        }         
        //多表查询 关联项目表tp_loan和投资表tp_invested       
        $count_sql = "FROM tp_invested LEFT JOIN tp_loan ON tp_loan.id=tp_invested.lid where 1=1 ".$count_where;                
        $count = M()->query("SELECT count(*) as count_limit ".$count_sql);        
        $count = $count[0]['count_limit']; //返回数据总数
        $page = new Page($count,$this->page_size);
        $limit = $page->firstRow . ',' . $page->listRows;          
        $invest_ar = M()->query("select *,tp_invested.create_time as in_create_time ".$count_sql." order by tp_invested.id desc limit $limit");     
        $list = array();
        //取项目名称,项目金额,项目ID
        foreach ($invest_ar as $key => $value) {
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
        //p($list);die;
        $tpl_deal_status = array();
        //交易状态5种赋值给模版
        for ($i=1; $i <=5 ; $i++) { 
            $tpl_deal_status[] = $i;
        }                        
        // p($list);die;
        $this->tpl_today = 'today'; //当天凌晨0点的时间戳          
        $this->tpl_one_week = 7;//一周前时间戳
        $this->tpl_one_month = 30;//一个月前时间戳
        $this->tpl_six_month = 180;//一个月前时间戳        
        $this->filter_date = I('filter_date');
        $this->deal_status = $deal_status;
        $this->tpl_deal_status = $tpl_deal_status;
        $this->list = $list;
        unset($list,$tpl_deal_status);//释放数组
        $page->setConfig('theme',"%totalRow% %header% %upPage% %downPage% %first% %prePage% %linkPage% %nextPage%");
        $this->page = $page->show();//分页        
        $this->display("investment");
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
        $this->filter_date = I('filter_date',0,'intval');
        $this->type = $type; 
        $this->list = $list;//数据集
        unset($tpl_type);     
        $page->setConfig('theme'," %nowPage%/%totalPage% %upPage%  %linkPage% %downPage%");   
        $this->page = $page->show();//分页        
        $this->display();
    }







    /**
     * @todo        assets     资产统计视图
     */
    public function assets(){        
        $where = 'uid='.$this->uid;
        $deal_log_db = M('deal_log');
        //邀请奖励总额
        $invite_money = $deal_log_db->field('sum(money) as invite_money')->where($where.' AND type=9')->find();
        $this->invite_money = empty($invite_money['invite_money'])?0:$invite_money['invite_money'];        
        //积分兑换金额
        $score_money = $deal_log_db->field('sum(money) as score_money')->where($where.' AND type=10')->find();
        $this->score_money = empty($score_money['score_money'])?0:$score_money['score_money'];                   
        //认证状况
        $auth = check_user_auth($this->uid);                    
        //认证进度
        $auth_rate = sprintf("%.0f", substr(sprintf("%.3f", $auth / 4 * 100), 0, -1));                         
        $this->auth_money = ($auth_rate == 100)?25:0;
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
            break;
        }                
        $read_deal_log_where = array(
            'uid'=>$this->uid,
            'is_read'=>0
        );
        $read_msg_box_where = array(
            'user_id'=>$this->uid,
            'is_read'=>0
        );                
        $this->list = $list;
        //未读资金消息总数
        $this->deal_log_count = $obj->where($read_deal_log_where)->order($order)->count();        
        //未读账户消息总数        
        $this->msg_box_count = $obj_msg_box->where($read_msg_box_where)->order($order)->count();                    
        $this->change = $change;             
        $page->setConfig('theme'," %nowPage%/%totalPage% %upPage%  %linkPage% %downPage%");   
        $this->page = $page->show();
        $this->display();
    }



    /**
     * @todo   MessageContent   消息详细页
     */
    public function MessageContent(){
        $id = I('get.id',0,'intval');
        $change = I('get.change');
        $db = M($change);
        $info = $db->where('id='.$id)->find();        
        $db->where('id='.$id)->setField('is_read',1);
        $this->info = $info;
        $this->refer =  $_SERVER['HTTP_REFERER'];
        $this->change = $change;
        $this->display();
    }

    /**
     * @todo  withdrawal   提现视图
     */
    public function withdrawal(){

        $bank = M('bank')->where('uid='.$this->uid.' AND is_default=1')->find();
        // $bank_number = $bank['bank_number'];        
        // $bank_number = str_repeat( '*',substr($bank_number,0,3));// 
        // $repeat_center = str_repeat('*',strlen($bank_number) - 8);
        // $repeat_right = substr($bank_number,-4);
        // $repeat_left = substr($bank_number,0,4);
        // $bank_number = $repeat_left.$repeat_center.$repeat_right;
        // $len_name = strlen($bank['real_name'])/3;
        // $name_left = str_repeat('*', $len_name-1); 
        // $name_right = strlen($bank['real_name'])+3;        
        // $name_right = substr($bank['real_name'],strlen($bank['real_name']) - $name_right);
        // $real_name = $name_left.$name_right;
        // $bank['true_bank_number'] = $bank['bank_number'];//用来传值的卡号
        // $bank['bank_number'] = $bank_number;
        $bank['bank_number_view'] = substr($bank['bank_number'], -4);
        $bank['real_name'] = $real_name;        
        $this->fast_management = C('fast_management') * 1000;//小数点后三位转换成整数
        $this->replenish_management = C('replenish_management');//补充手续费
        $user_info = $this->user_db->where('id='.$this->uid)->find();        
        $user_info['money'] = sprintf("%.2f", substr(sprintf("%.3f",$user_info['money']), 0, -1)); 
        $this->bank = $bank;
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
        $bank_res = M('bank')->where('uid='.$this->uid)->find();
        if(!$bank_res){
            $this->error('请先绑定银行卡');            
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
            $deal_log_data[0]['detail'] = '提现审核申请中- 提现金额:'.$data['withdrawal_price'].'元。当前剩余金额：'.$now_user_money.'元。剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细            
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
             // send_sms('18943023307',$alert_data['content']);//手机提醒
             send_mail('1570208750@qq.com','金投资', iconv('UTF-8','GB2312',$alert_data['title']), $alert_data['content'], "HTML");//邮件提醒                             
            $this->success('提现申请成功，请及时关注审核进度。',U('Member/withdrawal'));
        }else{
            $this->error('数据错误');
        }
    }


    /**
     * @todo   bank_card   添加银行卡列表页
     * @author <472591847@qq.com> liying
     * @since  2015-04-02 16:00:39
     */
    public function bank_card(){
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $bank_list = M('bank')->where('uid='.$this->uid)->order('id DESC')->select();
        foreach ($bank_list as $key => $value) {
            $value['real_name_view'] = mb_substr($value['real_name'], 0,1,'utf-8').'**';
            $value['bank_number_view'] = substr($value['bank_number'], -4);
            $bank_list[$key] = $value;
        }
        $this->user_info = $user_info;
        $this->bank_list = $bank_list;
        $this->display();
    }


    /**
     * @todo   modify_bank   修改银行卡视图
     * @author <472591847@qq.com> liying
     * @since  2015-04-02 17:01:44
     */
    public function modify_bank(){
        $upid = I("upid",0,"intval");
        if(!empty($upid)){
            $bank_info = M('bank')->where("id=".$upid)->find();
            $bank_info['real_name_view'] = mb_substr($bank_info['real_name'],0,1,'utf-8').'**';
            $bank_info['bank_number_view'] = substr($bank_info['bank_number'],0,4).'*****'.substr($bank_info['bank_number'],-4);            
            $this->bank_info = $bank_info;                 
        }else{
            $this->error('参数错误');
        }            
        $this->upid = $upid;    
        $this->display();
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
     * @todo   add_bank      添加银行卡视图
     * @author <472591847@qq.com> liying
     * @since  2015-04-02 17:17:15
     */
    public function add_bank(){
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        //判断是否通过实名认证
        if(empty($user_info['real_name'])  || empty($user_info['idno'])){
            $this->error('请先绑定真实姓名和身份证号','personal');
        }      
        $this->user_info = $user_info;
        $this->display();
    }




    /**
     * @todo     do_add_bank        修改银行卡处理
     * @param    $upid   intval        银行卡id
     */
    public function do_add_bank(){
        // $refer = $_SERVER['HTTP_REFERER'];
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
            //redirect($refer);
            $this->redirect('bank_card');
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
        $this->success('设置默认银行卡成功',U('Member/bank_card'));
    }

    
    /**
     * @todo       DoBank        添加绑定银行卡
     * @param      $bank_number   卡号
     * @param      $bank_address  开户行全称
     * @param      $real_name     户名
     */
    public function DoBank(){
        $data = $_POST;
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
        $res = M('bank')->where('uid='.$this->uid)->count();//查看此用户是否已经绑定银行卡
        if($res == 0){
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
            $this->redirect('withdrawal');
        }else{
            $this->error('数据错误');
        }
    }

    /**
     * @todo  TopUp    充值页面视图
     */
    public function TopUp(){
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $this->no = I('no',0,'intval');
        //可用余额=用户余额+奖励金额
        $user_info['money'] = sprintf("%.2f", substr(sprintf("%.3f",$user_info['money']+$user_info['bonus_money']), 0, -1)); 
        $this->user_info = $user_info;
        $this->display();
    }


    /**
     * @todo  incharge_redirect    跳转新生支付接口并传值
     * @return 跳转，并传递金额和用户ID
     */
    public function incharge_redirect(){
        $money = floatval($_REQUEST['money']);
        $user_info = $this->user_db->where('id='.$this->uid)->find();
        $user_id = $this->uid;
        $ido = I('ido',0,'intval');
        if($money<=0)
           $this->error('金额输入错误');  
        if(empty($ido))
            $this->error('您需同意《金投充值及提现协议》');
        redirect("/Pay/xin_pay.php?money=".$money."&user_id=".$user_id."&user_name=".$user_info['user_name']."&user_pwd=".$user_info['user_pwd']);
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
     * @return   json
     *               {data: 返回的数据。info:提示信息。status:操作状态 1 成功 0  失败}
     */
   public function MsgIsRead(){
        $change = I('change');
        $id = I('id',0,'intval');
        $obj = M($change);
        $data['is_read'] = 1;        
        $read = $obj->where('id='.$id)->save($data);
        //echo $obj->getLastSql();die;
        if(IS_AJAX){
            if($read!==false){
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
        $page->setConfig('theme'," %nowPage%/%totalPage% %upPage%  %linkPage% %downPage%");           
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
        $score_log_where['uid'] = $this->uid;
        $count = $score_log_db->where($score_log_where)->count();
        $page = new Page($count,5);
        $limit = $page->firstRow . ',' . $page->listRows;                  
        $score_log_ar = $score_log_db->where($score_log_where)->limit($limit)->order('create_time DESC,id DESC')->select();
        $sum_score_ar = score_statistics($this->uid,'','',$type,1,'sum');//获取用户积分总收入
        $use_sum_score_ar = score_statistics($this->uid,'','',$type,2,'sum');//获取用户积分总支出
        $user_info = $this->user_db->where("id=".$this->uid)->find();		
        $this->user_info = $user_info;//当前用户信息
        $page->setConfig('theme'," %nowPage%/%totalPage% %upPage%  %linkPage% %downPage%");   
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
        $referral_id = rawurlencode(getEncrypt($this->uid));  //推广ID(加密串)         
        $rands = rands();//不重复随机数 
        $host = C('host');        
        //会员推广链接 
        $this->referral_links = $host.U("user/Register").'?pid='.$referral_id;        
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
                $score_page->setConfig('theme'," %nowPage%/%totalPage% %upPage%  %linkPage% %downPage%");                               
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
                $deal_log_page->setConfig('theme'," %nowPage%/%totalPage% %upPage%  %linkPage% %downPage%");                               
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
            $user_page->setConfig('theme'," %nowPage%/%totalPage% %upPage%  %linkPage% %downPage%");               
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
}
 ?>