<?php
session_start();
include('./Common/Common.php');

/**
 * @todo  get_http    获取协议 
 */
function get_http()
{
    return (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) != 'off')) ? 'https://' : 'http://';
}


/**
 * @todo   get_domain    获取域名   
 */
function get_domain()
{
    /* 协议 */
    $protocol = get_http();

    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        /* 端口 */
        if (isset($_SERVER['SERVER_PORT']))
        {
            $port = ':' . $_SERVER['SERVER_PORT'];

            if ((':80' == $port && 'http://' == $protocol) || (':443' == $port && 'https://' == $protocol))
            {
                $port = '';
            }
        }
        else
        {
            $port = '';
        }

        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'] . $port;
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'] . $port;
        }
    }

    return $protocol . $host;
}


/**
 * @todo   get_host 获取域名主机头
 */
function get_host()
{


    /* 域名或IP地址 */
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST']))
    {
        $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
    }
    elseif (isset($_SERVER['HTTP_HOST']))
    {
        $host = $_SERVER['HTTP_HOST'];
    }
    else
    {
        if (isset($_SERVER['SERVER_NAME']))
        {
            $host = $_SERVER['SERVER_NAME'];
        }
        elseif (isset($_SERVER['SERVER_ADDR']))
        {
            $host = $_SERVER['SERVER_ADDR'];
        }
    }
    return $host;
}



/**
 * @todo   isMobile   判断是否移动设备
 * @return boolean    true 移动设备  false 非移动设备
 */
function isMobile() {
   // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
   if (isset ($_SERVER['HTTP_X_WAP_PROFILE'])) {
    return true;
   }
   //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
   if (isset ($_SERVER['HTTP_VIA'])) {
    //找不到为flase,否则为true
    return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
   }
   //脑残法，判断手机发送的客户端标志,兼容性有待提高
   if (isset ($_SERVER['HTTP_USER_AGENT'])) {
    $clientkeywords = array (
     'nokia',
     'sony',
     'ericsson',
     'mot',
     'samsung',
     'htc',
     'sgh',
     'lg',
     'sharp',
     'sie-',
     'philips',
     'panasonic',
     'alcatel',
     'lenovo',
     'iphone',
     'ipod',
     'blackberry',
     'meizu',
     'android',
     'netfront',
     'symbian',
     'ucweb',
     'windowsce',
     'palm',
     'operamini',
     'operamobi',
     'openwave',
     'nexusone',
     'cldc',
     'midp',
     'wap',
     'mobile'
    );
    // 从HTTP_USER_AGENT中查找手机浏览器的关键字
    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
     return true;
    }
   }
   //协议法，因为有可能不准确，放到最后判断
   if (isset ($_SERVER['HTTP_ACCEPT'])) {
    // 如果只支持wml并且不支持html那一定是移动设备
    // 如果支持wml和html但是wml在html之前则是移动设备
    if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
     return true;
    }
   }
   return false;
}


/**
* 保存来路
*/
if(!$_SESSION['referer_url'])
{       
    //echo urlencode($_SERVER["HTTP_REFERER"]);die;
    if(!preg_match("/".urlencode(get_domain())."/",urlencode($_SERVER["HTTP_REFERER"])))
    $_SESSION["referer_url"] = $_SERVER["HTTP_REFERER"];
}
$GLOBALS['referer'] = $_SESSION["referer_url"];
/**
* 301重定向
*/
$the_host = $_SERVER['HTTP_HOST'];//取得进入所输入的域名
$request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';//判断地址后面部分
if($the_host == 'p2p222.com' || $the_host == 'p2p222.net' || $the_host == 'www.p2p222.net')//这是我要以前的域名地址
{
  header('HTTP/1.1 301 Moved Permanently');//发出301头部 
  header('Location: http://www.p2p222.com'.$request_uri);//跳转到我的新域名地址
} 
    
/*
*判断是否移动设备，及按钮进行跳转
 */
if(isMobile()){
	if(!empty($_GET['i_change'])){               
        $_SESSION['is_change'] = 'www';
    }
    if(empty($_SESSION['is_change'])){               
        $redirect_uri = 'http://m.p2p222.com'.$request_uri;    
        header('Location:'.$redirect_uri);
    }
    if(!empty($_GET['is_change'])){
        $request_uri = str_replace('is_change=1','',$request_uri);                   
        $redirect_uri = 'http://m.p2p222.com'.$request_uri;    
        header('Location:'.$redirect_uri);     
    }
}
/*
 *自动投标功能
*/

// $loan_where['deal_status'] = 1;
// $loan_where['is_effect'] = 1;
// $loan_where['is_delete'] = 0;
// $loan_ar = M('loan')->where($loan_where)->select();
// foreach ($loan_ar as $deal_k => $deal_info) {
//     $deal_info['loan_rate'] = $deal_info['loan_rate'] + $deal_info['loan_add_rate'];
//     $end_time = $deal_info['end_time'] * 24 * 3600;//天转换成秒        
//     $deal_info['remain_time'] = ($deal_info['start_time'] + $end_time) - time();//当前剩余秒数          
//     if($deal_info['deal_status'] == 1  &&  $deal_info['remain_time'] > 0 && time() - $deal_info['start_time'] >= 1800){    
//         $in_where['lid'] = $deal_info['id'];
//         $in_where['status'] = 0;
//         $invested_ar = M('invested')->field('sum(amount) as load_money')->where($in_where)->find();
//         $sql = "SELECT usa.uid,usa.every_money,u.user_name,usa.id FROM ".C('DB_PREFIX')."auto_invested usa LEFT JOIN ".C('DB_PREFIX')."web_user u ON u.id=usa.uid " .
//                 "WHERE usa.every_money >=".$deal_info['min_loan_money'] ." AND usa.every_money <=".$deal_info['max_loan_money']." AND usa.switch=1 ".
//                 "AND u.money-usa.every_money >= usa.reserve_money " .
//                 "AND (".$deal_info['loan_rate']." >= usa.min_rate ) OR usa.rate_status=1 " .
//                 "AND (".$deal_info['loan_deadline']." between usa.min_deadline AND usa.max_deadline) OR  usa.deadline_status=1 " .            
//                 "AND usa.every_money <=".($deal_info['loan_money'] - $invested_ar['load_money'])." AND MOD(usa.every_money,".$deal_info['min_loan_money'].")=0 ".
//                 "AND (usa.every_money <= ".$deal_info['max_loan_money']." or ".$deal_info['max_loan_money']." = 0)".
//                 "GROUP BY usa.uid ORDER BY usa.last_invest_time ASC,usa.create_time ASC";                 
//         $auto_invest_user = M()->query($sql);        
//         if($auto_invest_user){            
//             foreach ($auto_invest_user as $key => $value) {                                
//                 $uid = $value['uid'];           
//                 $lid = $deal_info['id'];                 
//                 $amount = $value['every_money'];   
//                 $referer_type = 1;//投标来源  0 正常 1 自动投标 
//                 $f_where['uid'] = $uid;                                                            
//                 $f_where['lid'] = $lid;                                                               
//                 $f_where['referer_type'] = $referer_type; 
//                 $find_in_res = M('invested')->where($f_where)->find();                                 
//                 //判断此项目 此人 是否已经使用过自动投标
//                 if(!$find_in_res){
//                     //如果投标成功修改此用户最后自动投标时间
//                     if(DoAddInvested($uid,$lid,$amount,$referer_type)){                    
//                         M('auto_invested')->where('id='.$value['id'])->setField('last_invest_time',time());
//                     }
//                 }
//             }        
//         }              
//     } 
// }

/**
 * @todo          DoAddInvested  添加投资处理
 * @param         $uid           投资会员ID
 * @param         $lid           所投项目ID
 * @param         $amount        投资金额  
 * @param         $referer_type  投标来源 0 正常投标 1 自动投标      
 */
function DoAddInvested($uid,$lid,$amount,$referer_type=0){    
    if(empty($uid) || empty($lid) || empty($amount)){ //说明页面跳过js判断
        return false;
        // $this->error('非法操作!<br>error:1');
    }        
    $user_db = M('web_user');       
    $user_where["id"] = $uid;
    //取出用户当前可用余额,冻结金额
    $user_res = $user_db->field('id,money,lock_money,bonus_money')->where($user_where)->find();     
    //echo $user_money;die;     
    $loan = M('loan')->field('loan_money,loan_rate,loan_deadline_type,loan_deadline,min_loan_money,max_loan_money,start_time,loan_title')->where("id = $lid")->find();//借款项目表 分别查询出：借款总额,年利率,期限              
    //投资可用余额=用户余额+用户奖励金额
    $user_res['true_money'] = $user_res['money'] + $user_res['bonus_money'];        
    if($user_res['true_money'] < $amount){
        return false;
        // $this->error('当前用户余额不足');
    }
    $M_invested = M('Invested');//投资记录表
    $sum_invested = $M_invested->field("SUM(amount) sum_amount")->where("lid = $lid")->find();//查询已投资金额
    $loan_over = $loan['loan_money'] - $sum_invested['sum_amount']; //计算剩余借款额       
    //最低投资限制判断
    if($loan['min_loan_money'] != 0 && $amount < $loan['min_loan_money']){
        return false;
        // $this->error('投资金额不可低于最低投资金额:'.$loan['min_loan_money'].'元');
    }
    //最高投资限制判断
    if($loan['max_loan_money'] != 0 && $amount > $loan['max_loan_money']){
        return false;
        //$this->error('投资金额不可大于最高投资金额:'.$loan['max_loan_money'].'元');
    }       
    $last_money = $sum_invested['sum_amount']+$amount;//本次投资后的投资总额
    if($amount > $loan_over){ //判断如果输入金额大于剩余借款金额（说明页面跳过js计算）
        return false;
        //$this->error('输入错误，投资总额超过总借款金额!<br>error:2');
    }   
    //判断项目是否开始
    if(time() < $loan['start_time']){
      return false;
      //$this->error('未开放的项目，请关注项目开放时间');
    }
    $loan_rate = ($loan['loan_rate'] + $loan['loan_add_rate']) / 100 ;//年利率
    //M改于2015年1月9日 11:10:59 ++
    $loan_deadline_type = $loan['loan_deadline_type'] ;//借款期限类型 0为月1为天
    //++
    $loan_month = $loan['loan_deadline']; //借款期限（月、天）
    //M改于 2015年1月9日 11:13:32 ++
    if($loan_deadline_type == 1){ //期限为天计算方法
        $loan_deadline = $amount + ($amount * $loan_rate / 365 * $loan_month);//利后所得金额
    }else{ //期限为月计算方法
        $loan_deadline = $amount + ($amount * $loan_rate / 12 * $loan_month);//利后所得金额
    }
    //++
    //M改于2015年1月9日 11:13:05 --$loan_deadline = $amount + ($amount * $loan_rate / 12 * $loan_month);//利后所得金额 //--
    $data = array(
        'uid' => $uid,                          //登录用户id
        'lid' => $lid,                          //借款项目id
        'amount' => $amount,                    //投资金额
        'repayment_amount' => $loan_deadline,   //利后所得金额
        'status' => 0,                          //还款状态 0未1已
        'create_time' => time(),                 //操作时间
        'referer_type' => 1                     //投标来源: 0 正常投标 1 自动投标
    );
    $if = $M_invested->add($data);
    if($if){                    
        //奖励金额是否存在，存在则用奖励金额抵消投资金额 获取差额
        if(!empty($user_res['bonus_money'])){
            $bonus_amount = $user_res['bonus_money'] - $amount;
        }
        //修改奖励金额字段
        $user_update['bonus_money'] = array('exp','bonus_money-'.$amount);
        //若奖励金额不够抵消投资金额，则用用户余额-剩余投资额,同时清空奖励金额变为0
        if($bonus_amount < 0){
            $user_update['money'] = array('exp','money+'.$bonus_amount);                
            $user_update['bonus_money'] = 0; 
        }
        //将投资金额转移到冻结金额
        $user_update['lock_money'] = array('exp','lock_money+'.$amount);//冻结金额                
        if($last_money == $loan['loan_money']){
            M('loan')->where('id='.$lid)->setField('deal_status','2');//若满额，改变借款状态 2 满额
            M('loan')->where('id='.$lid)->setField('success_time',time());//筹标成功时间
            //给投资人发送满标通知
            // if(is_array($invested_phones)){
            //   foreach ($invested_phones as $phone_v) {
            //     $msg_str = "满标通知：您投资的项目【".$loan['loan_title'].'】于'.date('Y-m-d H:i:s',time()).'完成满额';
            //     send_sms($phone_v,$msg_str);
            //   }
            // }                  
        }
        $user_db->where($user_where)->save($user_update);   
        /*写入交易记录表*/
        $deal_log_data[0]['uid'] = $uid;
        $deal_log_data[0]['lid'] = $lid;
        $deal_log_data[0]['invested_id'] = $if;
        $deal_log_data[0]['money'] = $amount;
        $deal_log_data[0]['type'] = 2;//交易状态为投资
        $deal_log_data[0]['pay_status'] = 2;//收支状态为支出            
        $deal_log_data[0]['detail'] = '投资成功：投资金额为'.$amount.'元。';//明细
        $deal_log_data[0]['create_time'] = time();//交易时间    
        insert_deal_log($deal_log_data);  
        // unset($invested_uids,$invested_phones);//释放数组   
        return true;
    }else{
        return false;
    }    
}  


/**
 * @todo    ac    前台公用过程
 */
function ac(){
    /*工作人员提醒消息处理*/
    // $loan_db = M('loan');
    // $alert_db = M('system_alert');
    // $today = strtotime(date('Y-m-d',time()));    //今天0点
    // $tomorrow = strtotime(date("Y-m-d",strtotime("+1 day")));  //明天0点    
    // /*三日内到期需要还款的项目*/  
    // $loan_where['deal_status'] = '4';//状态：还款中      
    // $loan_res = $loan_db->where($loan_where)->select();
    // $end_count = 0;    
    // foreach ($loan_res as $key => $value) {
    //     //借款期限时间 (天，月)
    //     if($value['loan_deadline_type'] == 1){
    //         $loan_deadline = $value['loan_deadline']  * 24 * 3600;
    //     }else{
    //         $loan_deadline = $value['loan_deadline'] * 30 * 24 * 3600;
    //     }      
    //     //到期时间 = 还款确认时间 + 借款期限时间       
    //     $dead_line =  $value['repay_confirm_time'] + $loan_deadline;            
    //     //剩余借款时间 = 总时间 - 当前时间
    //     $re_time = $dead_line - time(); 
    //     $three_day = 3 * 24 * 3600; 
    //     if($re_time <= $three_day){
    //         $end_count++;
    //     }        
    // }       
    // /*若有即将需还款的项目则提醒*/   
    // if($end_count > 0){
    //    $alert_where['title'] = '还款提醒';
    //    $alert_where['status'] = 1;
    //    $alert_where['create_time'] = array(array('EGT',$today),array('LT',$tomorrow),'AND');      
    //    $alert_ar = $alert_db->where($alert_where)->find();        
    //    if(!$alert_ar){
    //      $alert_data['title'] = '还款提醒';
    //      $alert_data['content'] = '网站有'.$end_count.'个项目距离还款日已不足3天，请前往后台查看。';
    //      $alert_data['status'] = 1;
    //      $alert_data['create_time'] = time();
    //      insert_system_alert($alert_data);
    //      send_sms('13904314687',$alert_data['content']);//手机提醒
    //      send_mail('2956000@qq.com','金投资', iconv('UTF-8','GB2312',$alert_data['title']), $alert_data['content'], "HTML");//邮件提醒
    //    }       
    // }


    /*提示充值发生并发*/
    $recharge_catch = M('recharge_log')->field('count(*) as g,user_id')->group('orderID')->order('g desc')->find();
    $filename = DocumentRoot().'/catch.txt';
    $handle = fopen($filename, "r");
    $contents = fread($handle, filesize ($filename));
    fclose();        
    if(!$contents && $recharge_catch['g'] > 1){    
        $handlea = fopen($filename, "a");
        fwrite($handlea, $recharge_catch['user_id']);
        $msg_catch = '网站充值出现并发情况,请及时处理('.$recharge_catch['user_id'].')';
        fclose(); 
        send_sms('18633451728',$msg_catch);
        send_sms('18604414340',$msg_catch);
    }          
}

////兑换积分
////$uid  用户id
////exchange  兑换数量
////return 返回正数为可兑换 负数为不可兑换
//function integral_exchange($uid,$exchange=0){
//	$invested = M("invested"); //会员投资表
//	$recharge_log = M("recharge_log"); //会员充值表  --正式的时候改成这条	
//	$withdrawal = M("withdrawal"); //会员提现表
//	$score_exchange = M("score_exchange"); //积分兑换记录
//	$invested_count = $invested->field("sum(amount) as amount")->where("uid = $uid")->find(); //会员投资总额
//	$invested_count = ($invested_count['amount']) ? $invested_count['amount'] : 0 ; //空为0
//	$recharge_count = $recharge_log->field("sum(payAmount) as payAmount")->where("user_id = $uid")->find(); //会员充值总额 --正式的时候改成这条	
//	$recharge_count = ($recharge_count['payAmount']) ? $recharge_count['payAmount'] : 0 ; //空为0
//	$withdrawal_count = $withdrawal->field("sum(withdrawal_price) as withdrawal_price")->where("uid = $uid and audit_status = 1")->find(); //会员提现总额
//	$withdrawal_count = ($withdrawal_count['withdrawal_price']) ? $withdrawal_count['withdrawal_price'] : 0 ; //空为0
//	$score_count = $score_exchange->field("sum(score) as score")->where("uid = $uid")->find(); //积分兑换总额
//	$score_count = ($score_count['score']) ? $score_count['score'] : 0 ; //空为0
//	$can_exchange = ($recharge_count * 0.01) - $withdrawal_count ; //会员充值总金额(已分为单位所以*0.01) - 提现总金额 = 可兑换额 分为单位
//	$return = ($can_exchange * 10) -  $score_count ; //可兑换额度-积分兑换总额 = 可兑换额度
//	$return = explode('.',$return) ;
//	$return = $return[0] ;
//	if($invested_count >= $can_exchange){ //投资金额 >= 充值金额-提现额
//		if($can_exchange >= ($score_count + $exchange)){ //可兑换额 >= 积分兑换总额 + 本次兑换
//			return array(1,$return) ;
//		}else{
//			return array(2,$return) ;
//		}
//	}else{
//		return array(3,$return) ;
//	}
//}
?>