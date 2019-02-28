<?php 
/**
 * @todo            前台项目交易控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-13
 */
class DealAction extends Action{


    public function index(){        
       $k_deal_status = I('k_deal_status',0,'intval');
       $k_loan_rate = I('k_loan_rate');
       $loan_db = M('loan');
       $k_loan_deadline_type = I('k_loan_deadline_type');
       $k_loan_deadline = I('k_loan_deadline');
        //向分页传递参数   
        $parameter = array(
            'k_deal_status'=>$k_deal_status,
            'k_loan_rate'=>$k_loan_rate,
            'k_loan_deadline_type'=>$k_loan_deadline_type,
            'k_loan_deadline'=>$k_loan_deadline,
        );   
       $loan_where = 'is_effect = 1 AND class_id = 0';//有效状态：有效
       $loan_where .= ' AND is_delete = 0 ';//伪删除状态：未删除               
       //按项目状态筛选
       if(!empty($k_deal_status))
          $loan_where .= ' AND  deal_status = '.$k_deal_status;       
       //按项目期限筛选
       if($k_loan_deadline_type != ''){
          //0:月 1：天
          $loan_where .= ' AND loan_deadline_type = '.$k_loan_deadline_type;
          if(!empty($k_loan_deadline)){
            //项目期限 区间筛选
            if(strpos($k_loan_deadline, ",")){                
                $loan_deadline_ar = explode(",",$k_loan_deadline);
                //区间（>）
                $min_loan_deadline = $loan_deadline_ar[0];
                $loan_where .= ' AND loan_deadline > '.$min_loan_deadline;
                //区间（<=）
                $max_loan_deadline = $loan_deadline_ar[1];
                $loan_where .= ' AND loan_deadline <= '.$max_loan_deadline;                
            }   
          }         
       }
       //按利率筛选
       if(!empty($k_loan_rate)){
          if(strpos($k_loan_rate, ",")){
            $loan_rate_ar = explode(",",$k_loan_rate);
            //利率区间（>）
            $min_loan_rate = $loan_rate_ar[0];
            //利率区间（<=）
            $max_loan_rate = $loan_rate_ar[1];            
            $loan_where .= ' AND loan_rate + loan_add_rate >= '.$min_loan_rate;            
            $loan_where .= ' AND loan_rate + loan_add_rate < '.$max_loan_rate;            
          }
          //14%以下（<=）
          if($k_loan_rate == 'elt10'){
            $loan_where .= ' AND loan_rate + loan_add_rate < 10';
          }
          //14%以上（>=）
          if($k_loan_rate == 'gt14'){
            $loan_where .= ' AND loan_rate + loan_add_rate >= 14';
          }
       }       
       $count = $loan_db->where($loan_where)->count();        
       //echo $loan_db->getlastsql();
       import('ORG.Util.Page'); //引入分页                       
       $page = new Page($count,12,$parameter);
       $limit = $page->firstRow . ',' . $page->listRows;                    
       $loan_list = $loan_db->where($loan_where)->order('loan_order DESC,create_time DESC,id DESC')->limit($limit)->select();
       $lids = array();
       foreach ($loan_list as $key => $value) {
          $lids[] = $value['id'];
       }
       $invested_where['lid'] = array('IN',$lids);        
       $invested_list = M('invested')->where($invested_where)->select();
       //取出每个项目投资总额
       foreach ($loan_list as $key => $value) {
         foreach ($invested_list as $k => $v) {
            if($value['id'] == $v['lid']){
              $value['count_amount'] += $v['amount'];//当前投资额总和
            }
         }        
         $loan_list[$key] = $value;
       }
       unset($lids);
       //最后数据计算
       foreach ($loan_list as $key => $value) {
            $value['now_time'] = time();//当前时间传递到模版
            $value['load_rate'] = number_format($value['count_amount']/$value['loan_money']*100, 2, '.', '');            
            $loan_list[$key] = $value;
       }    
       $this->page_param = $parameter;
       $this->k_deal_status = $k_deal_status;
       $this->loan_list = $loan_list;
       $this->page = $page->show();//分页
       $this->display();
    }


    /**
     * @todo DealContent        投资详情页
     * @param  $lid             项目ID
     * @param  $uid             当前登录用户ID
     */
    public function DealContent(){      
        $lid = I('lid',0,'intval');
        $uid = session('user_id');
        // if(empty($uid))
        //   $this->error('登陆后方可查看此页内容',U('user/Login'));        
        $user_db = M('web_user');
        $loan_db = M('loan');
        $invested_db = M('invested');                
        $user_ar = $user_db->where('id='.$uid)->find();//读取用户信息

        //此操作仅对于2015-02-10 23:00:00 前注册用户投资（临时用）+++++++
        // if($user_ar['create_time'] <= strtotime('2015-02-10 23:00:00')){             
        //   $user_ar['money'] = $user_ar['money'] + $user_ar['bonus_money'];//用户可投资金额 = 用户余额+奖励金额        
        // }
        $loan_ar = $loan_db->where('id='.$lid)->find();//读取借款信息
        $sum_amount = $invested_db->where('lid='.$lid)->getField('SUM(amount)');//已投总额                        
        $loan_ar['sum_amount'] = $sum_amount;//已投总额传递给模版
        $loan_ar['loan_over'] = $loan_ar['loan_money'] - $sum_amount;//已投总额传递给模版
        $loan_ar['loan_over_view'] = sprintf("%.0f", substr(sprintf("%.3f", $loan_ar['loan_money'] - $sum_amount), 0, -1));//显示已投总额传递给模版
        $end_time = $loan_ar['end_time'] * 24 * 3600;//天转换成秒        
        $retime = ($loan_ar['start_time'] + $end_time) - NOW_TIME;//当前剩余秒数  
        if(!empty($loan_ar['company_material'])){      
           $loan_ar['company_material'] = explode("\r\n", $loan_ar['company_material']);//返回企业资料图片组        
        }        
        if(!empty($loan_ar['pawn_action'])){  
           $loan_ar['pawn_action'] = explode("\r\n", $loan_ar['pawn_action']);//抵押物实景图片组
        }
        if(!empty($loan_ar['contract_agreement'])){      
           $loan_ar['contract_agreement'] = explode("\r\n", $loan_ar['contract_agreement']);//返回资质图片组        
        }        
        if(!empty($loan_ar['field_visit'])){  
           $loan_ar['field_visit'] = explode("\r\n", $loan_ar['field_visit']);//返回实地考察图片组
        }
        if($loan_ar['deal_status'] == 1){
          //若项目没到发布日,将剩余发布时间传入模版
          if(time() < $loan_ar['start_time']){
              $loan_ar['re_start_time'] = $loan_ar['start_time'] - time();
          }
        }
        if($loan_ar['deal_status'] == 2 || $loan_ar['deal_status'] == 4){
          $retime = 0;//若满标，剩余时间归零
        }
        $loan_ar['retime'] = ($retime>0)?remain_time($retime):'0天0时0分';//格式化剩余天数
        $loan_ar['load'] = (string) sprintf("%.2f", substr(sprintf("%.3f", $sum_amount / $loan_ar['loan_money'] * 100), 0, -1));       
        /*查询可使用的加息券总数*/
        $web_user_rate_db = M('web_user_rate');
        $web_user_rate_where['uid'] = $uid;
        $web_user_rate_where['use_status'] = 0;           
        //$web_user_rate_where['deadline_time'] = array('EGT',NOW_TIME); //查询没过期的加息券  本期不使用此功能
        $web_user_rate_count = $web_user_rate_db->where($web_user_rate_where)->count();  
        //读取一个加息券信息
        $one_user_rate_res = $web_user_rate_db->where($web_user_rate_where)->order('deadline_time ASC,id ASC')->find();          
        $this->web_user_rate_count = $web_user_rate_count; //可使用的加息券总数            
        $this->one_user_rate_res = $one_user_rate_res;//一个加息券的信息
        //投资技巧输出
        $this->right_activity = M('info')->where('pid=23')->order('sort Desc,id DESC')->limit(5)->select();                                               
        $this->user_info = $user_ar;//当前登录用户信息
        $this->loan = $loan_ar;//本项目信息
        $this->lid = $lid;
        //此操作仅对于2015-02-10 23:00:00 前注册用户投资（临时用）+++++++
        // if($user_ar['create_time'] <= strtotime('2015-02-10 23:00:00')){          
        //   $this->display('OldDealContent');
        // }else{
          $this->display();
        // }
    } 



    /**
     * @todo   ProductList    产品列表页
     */
    public function ProductList(){
       $class_id = I('class_id','intval');
       $loan_db = M('loan');
       $loan_where = 'is_effect = 1 AND class_id = '.$class_id;//有效状态：有效
       $loan_where .= ' AND is_delete = 0 ';//伪删除状态：未删除               
       $count = $loan_db->where($loan_where)->count();        
       //echo $loan_db->getlastsql();
       import('ORG.Util.Page'); //引入分页                       
       $page = new Page($count,12);
       $limit = $page->firstRow . ',' . $page->listRows;                    
       $loan_list = $loan_db->where($loan_where)->order('loan_order DESC,create_time DESC,id DESC')->limit($limit)->select();
       $lids = array();
       foreach ($loan_list as $key => $value) {
          $lids[] = $value['id'];
       }
       $invested_where['lid'] = array('IN',$lids);        
       $invested_list = M('invested')->where($invested_where)->select();
       //取出每个项目投资总额
       foreach ($loan_list as $key => $value) {
         foreach ($invested_list as $k => $v) {
            if($value['id'] == $v['lid']){
              $value['count_amount'] += $v['amount'];//当前投资额总和
            }
         }        
         $loan_list[$key] = $value;
       }
       unset($lids);
       //最后数据计算
       foreach ($loan_list as $key => $value) {
            $value['now_time'] = time();//当前时间传递到模版
            $value['load_rate'] = number_format($value['count_amount']/$value['loan_money']*100, 2, '.', '');            
            $loan_list[$key] = $value;
       }   
       $this->cate = M('product_class')->where('id='.$class_id)->find(); 
       $this->loan_list = $loan_list;
       $this->page = $page->show();//分页            
      $this->display();
    }


    /**
     * @todo   yueyuexin   月月薪产品介绍
     */
    public function yueyuexin(){
        $lid = I('lid');    
        $uid = session('user_id');    
        $res = M('loan')->where('id='.$lid)->find();        
        if($res['class_id'] != 1)
          $this->error('参数错误');        
      $this->GetProduct($lid,$uid);
      $this->display();
    }      

    /**
     * @todo   danjiying   单季盈产品介绍
     */
    public function danjiying(){
        $lid = I('lid');    
        $uid = session('user_id');    
        $res = M('loan')->where('id='.$lid)->find();               
        if($res['class_id'] != 2)
          $this->error('参数错误');        
      $this->GetProduct($lid,$uid);    
      $this->display();
    }


    /**
     * @todo   shuangjifeng   双季丰产品介绍
     */
    public function shuangjifeng(){
        $lid = I('lid');    
        $uid = session('user_id');    
        $res = M('loan')->where('id='.$lid)->find();        
        if($res['class_id'] != 3)
          $this->error('参数错误');        
      $this->GetProduct($lid,$uid);     
      $this->display();
    }

    /**
     * @todo   niannianhong   年年红产品介绍
     */
    public function niannianhong(){
        $lid = I('lid');
        $uid = session('user_id');
        $res = M('loan')->where('id='.$lid)->find();        
        if($res['class_id'] != 4)
          $this->error('参数错误');
        $this->GetProduct($lid,$uid);
        $this->display();

    }

    /**
     * @todo    GetProduct   获得产品详细各项参数并赋值到模版
     * @param int        $lid   项目ID
     * @param int        $uid   当前会员ID
     */
     function GetProduct($lid,$uid){
        $user_db = M('web_user');
        $loan_db = M('loan');
        $invested_db = M('invested');                
        $pro_cls_db = M('product_class');        
        $user_ar = $user_db->where('id='.$uid)->find();//读取用户信息
        $loan_ar = $loan_db->where('id='.$lid)->find();//读取借款信息        
        $this->cate = $pro_cls_db->where('id='.$loan_ar['class_id'])->find();//读取产品类别
        $invested_ar = $invested_db->where('lid='.$lid)->select();//读取投资信息
        $sum_amount = $invested_db->where('lid='.$lid)->getField('SUM(amount)');//已投总额                               
        $loan_ar['sum_amount'] = $sum_amount?$sum_amount:0;//已投总额传递给模版      
        $loan_ar['loan_over'] = $loan_ar['loan_money'] - $sum_amount;//剩余借款金额传递给模版
        $loan_ar['loan_over_view'] = sprintf("%.0f", substr(sprintf("%.3f", $loan_ar['loan_money'] - $sum_amount), 0, -1));//可投余额传递模版
        $end_time = $loan_ar['end_time'] * 24 * 3600;//天转换成秒        
        $retime = ($loan_ar['start_time'] + $end_time) - NOW_TIME;//当前剩余秒数  
        if($loan_ar['deal_status'] == 1){
          //若项目没到发布日,将剩余发布时间传入模版
          if(time() < $loan_ar['start_time']){
              $loan_ar['re_start_time'] = $loan_ar['start_time'] - time();
          }
        }
        if($loan_ar['deal_status']==2){
          $retime = 0;//若满标，剩余时间归零
        }
        $loan_ar['retime'] = ($retime>0)?remain_time($retime):'0天0时0分';//格式化剩余天数
        $loan_ar['load'] = (string) sprintf("%.2f", substr(sprintf("%.3f", $sum_amount / $loan_ar['loan_money'] * 100), 0, -1));       
        $in_uids = array();//本项目投资会员ID集合
        foreach ($invested_ar as $key => $value) {
            $in_uids[] = $value['uid'];
        }
        $invest_user_where = array(
            'id'=>array('IN',$in_uids)
        );
        //投资会员用户名 取出并传入数组
        $invest_user_ar = $user_db->where($invest_user_where)->select();
        foreach ($invested_ar as $key => $value) {
            foreach ($invest_user_ar as $in_k => $in_v) {
                if($value['uid'] == $in_v['id']){
                    $center = str_repeat('*',strlen($in_v['user_name']) - 4);
                    $left = $repeat.substr($in_v['user_name'],0,2);
                    $right = $repeat.substr($in_v['user_name'],-2);
                    $value['user_name'] = $left.$center.$right;
                }
            }
            $invested_ar[$key] = $value;
        } 
        /*查询可使用的加息券总数*/
        $web_user_rate_db = M('web_user_rate');
        $web_user_rate_where['uid'] = $uid;
        $web_user_rate_where['use_status'] = 0;           
        //$web_user_rate_where['deadline_time'] = array('EGT',NOW_TIME); //查询没过期的加息券  本期不使用此功能
        $web_user_rate_count = $web_user_rate_db->where($web_user_rate_where)->count();  
        //读取一个加息券信息
        $one_user_rate_res = $web_user_rate_db->where($web_user_rate_where)->order('deadline_time ASC,id ASC')->find();          
        $this->web_user_rate_count = $web_user_rate_count; //可使用的加息券总数            
        $this->one_user_rate_res = $one_user_rate_res;//一个加息券的信息
        //投资技巧输出
        $this->right_activity = M('info')->where('pid=23')->order('sort Desc,id DESC')->limit(5)->select();                                  
        unset($in_uids);              
        $this->user_info = $user_ar;//当前登录用户信息
        $this->loan = $loan_ar;//本项目信息
        $this->invested_list = $invested_ar;//本项目投资列表      
    }
  


    /**
     * @todo    tz_record     投资记录
     */
    public function tz_record(){
        $lid = I('lid');        
        $user_db = M('web_user');                
        $invested_db = M('invested');  
        $invested_count = $invested_db->where('lid='.$lid)->count();        
        import('ORG.Util.Page'); //引入分页                       
        $page = new Page($invested_count,10);        
        $limit = $page->firstRow . ',' . $page->listRows;           
        $invested_ar = $invested_db->where('lid='.$lid)->order('id DESC')->limit($limit)->select();//读取投资信息                
        $in_uids = array();//本项目投资会员ID集合
        foreach ($invested_ar as $key => $value) {
            $in_uids[] = $value['uid'];
        }
        $invest_user_where = array(
            'id'=>array('IN',$in_uids)
        );
        //投资会员用户名 取出并传入数组
        $invest_user_ar = $user_db->where($invest_user_where)->select();
        foreach ($invested_ar as $key => $value) {            
            foreach ($invest_user_ar as $in_k => $in_v) {                
                if($value['uid'] == $in_v['id']){                    
                    $left = $repeat.substr($in_v['user_name'],0,2);
                    $right = $repeat.substr($in_v['user_name'],-2);
                    if($in_v['user_name'] == 'xiaoxiao'){
                        $in_v['user_name'] = $in_v['user_name'].$value['id'];
                        $left = $repeat.substr($in_v['user_name'],0,2);
                        $right = $repeat.substr($in_v['user_name'],-2);                        
                    }                      
                    $value['user_name'] = $left.'*****'.$right;                                      
                }
            }
            $invested_ar[$key] = $value;
        }     
        unset($in_uids);                    
        $this->invested_list = $invested_ar;//本项目投资列表
        $this->lid = $lid;        
        $this->page = $page->show();  
        $this->display();
    }

    /**
     * @todo   ProductContent   产品详细页
     */
    public function ProductContent(){
      $this->display();
    }


    /**
     * @todo  ProductIntroduce  产品说明页
     */
    public function ProductIntroduce(){
      $this->display();
    }


   /**
     * @todo          OldDoAddInvested   02-10 23:00前添加投资处理(临时用)
     * @param         $uid           投资会员ID
     * @param         $lid           所投项目ID
     */
    // public function OldDoAddInvested(){    
    //     $uid = I('uid');                //登录用户id
    //     $lid = I('lid',0);              //借款项目id
    //     $amount = I('money',0);         //投资金额  
    //     $rate_id = I('rate_id');        //加息券ID          
    //     if(empty($uid) || empty($lid) || empty($amount)){ //说明页面跳过js判断
    //         $this->error('非法操作!<br>error:1');
    //     }        
    //     $user_add_rate = 0;//初始化用户加息利率
    //     if(!empty($rate_id)){
    //       $user_rate_res = M('web_user_rate')->where('id='.$rate_id)->find();          
    //       //若没过期,且未使用则设置附加利率
    //       // if(NOW_TIME <= $user_rate_res['deadline_time'] && $user_rate_res['use_status'] == '0'){
    //         $user_add_rate = $user_rate_res['add_rate'];            
    //       // }
    //     }  
    //     $user_db = M('web_user');       
    //     $user_where["id"] = $uid;
    //     //取出用户当前可用余额,冻结金额
    //     $user_res = $user_db->field('id,money,lock_money,bonus_money')->where($user_where)->find();                 
    //     $loan = M('loan')->field('loan_money,loan_rate,loan_deadline_type,loan_add_rate,loan_deadline,min_loan_money,max_loan_money,start_time,loan_title')->where("id = $lid")->find();//借款项目表 分别查询出：借款总额,年利率,期限              
    //     //投资可用余额=用户余额+用户奖励金额
    //     $user_res['true_money'] = $user_res['money'] + $user_res['bonus_money'];                
    //     if($user_res['true_money'] < $amount){
    //         $this->error('当前用户余额不足');
    //     }
    //     $M_invested = M('Invested');//投资记录表
    //     $sum_invested = $M_invested->field("SUM(amount) sum_amount")->where("lid = $lid")->find();//查询已投资金额
    //     $loan_over = $loan['loan_money'] - $sum_invested['sum_amount']; //计算剩余借款额       
    //     //最低投资限制判断
    //     if($loan['min_loan_money'] != 0 && $amount < $loan['min_loan_money']){
    //         $this->error('投资金额不可低于最低投资金额:'.$loan['min_loan_money'].'元');
    //     }
    //     //投资最低金额
    //     if($amount % $loan['min_loan_money'] != 0 ){
    //         $this->error('投资金额需是'.format_price($loan['min_loan_money']).'或'.format_price($loan['min_loan_money']).'的倍数');
    //     }        
    //     //最高投资限制判断
    //     if($loan['max_loan_money'] != 0 && $amount > $loan['max_loan_money']){
    //         $this->error('投资金额不可大于最高投资金额:'.$loan['max_loan_money'].'元');
    //     }       
    //     $last_money = $sum_invested['sum_amount']+$amount;//本次投资后的投资总额
    //     if($amount > $loan_over){ //判断如果输入金额大于剩余借款金额（说明页面跳过js计算）
    //         $this->error('输入错误，投资总额超过总借款金额!<br>error:2');
    //     }   
    //     //判断项目是否开始
    //     if(NOW_TIME < $loan['start_time']){
    //       $this->error('未开放的项目，请关注项目开放时间');
    //     }

    //     //投资信息表中取投资用户ID
    //     // $invested_ar = M('invested')->where('lid='.$lid)->select();
    //     // $invested_uids = array();//投资人ID集合
    //     // $invested_phones = array();//投资人电话号集合
    //     // foreach ($invested_ar as $key => $value) {
    //     //   $invested_uids[] = $value['uid'];
    //     // }
    //     // //读取投资用户手机号，并压入数组
    //     // if(is_array($invested_uids)){
    //     //   $u_where['id'] = array('IN',$invested_uids);
    //     //   $u_info = M('web_user')->where($u_where)->select();
    //     //   foreach ($u_info as $key => $value) {
    //     //     $invested_phones[] = $value['phone'];
    //     //   }                     
    //     // }  
    //     $loan_rate = ($loan['loan_rate'] + $loan['loan_add_rate'] + $user_add_rate) / 100 ;//年利率                                      
    // //M改于2015年1月9日 11:10:59 ++
    // $loan_deadline_type = $loan['loan_deadline_type'] ;//借款期限类型 0为月1为天
    // //++
    //     $loan_month = $loan['loan_deadline']; //借款期限（月、天）
    // //M改于 2015年1月9日 11:13:32 ++
    // if($loan_deadline_type == 1){ //期限为天计算方法

    //   $loan_deadline = $amount + ($amount * $loan_rate / 365 * $loan_month);//利后所得金额
    // }else{ //期限为月计算方法

    //   $loan_deadline = $amount + ($amount * $loan_rate / 12 * $loan_month);//利后所得金额
    // }
    // //++
    //     //M改于2015年1月9日 11:13:05 --$loan_deadline = $amount + ($amount * $loan_rate / 12 * $loan_month);//利后所得金额 //--
    //     $data = array(
    //         'uid' => $uid,                          //登录用户id
    //         'lid' => $lid,                          //借款项目id
    //         'amount' => $amount,                    //投资金额
    //         'repayment_amount' => $loan_deadline,   //利后所得金额
    //         'to_rate' => $loan_rate * 100,        //写入本次投资利率
    //         'status' => 0,                          //还款状态 0未1已
    //         'create_time' => time()                 //操作时间
    //     );          
    //     $if = $M_invested->add($data);
    //     if($if){                    
    //         //奖励金额是否存在，存在则用奖励金额抵消投资金额 获取差额
    //         if(!empty($user_res['bonus_money'])){
    //             $bonus_amount = $user_res['bonus_money'] - $amount;
    //         }
    //         //修改奖励金额字段
    //         $user_update['bonus_money'] = array('exp','bonus_money-'.$amount);
    //         //若奖励金额不够抵消投资金额，则用用户余额-剩余投资额,同时清空奖励金额变为0
    //         if($bonus_amount < 0){
    //             $user_update['money'] = array('exp','money+'.$bonus_amount);                
    //             $user_update['bonus_money'] = 0; 
    //         }
    //         //将投资金额转移到冻结金额
    //         $user_update['lock_money'] = array('exp','lock_money+'.$amount);//冻结金额                
    //         if($last_money == $loan['loan_money']){
    //             M('loan')->where('id='.$lid)->setField('deal_status','2');//若满额，改变借款状态 2 满额
    //             M('loan')->where('id='.$lid)->setField('success_time',NOW_TIME);//筹标成功时间
    //              $alert_data['title'] = '满标提醒';
    //              $alert_data['content'] = '网站有项目今天已满标，请前往后台查看。';
    //              $alert_data['status'] = 1;
    //              $alert_data['create_time'] = time();
    //              insert_system_alert($alert_data);   
    //              send_sms('13904314687',$alert_data['content']);//手机提醒
    //              send_mail('2956000@qq.com','金投资', iconv('UTF-8','GB2312',$alert_data['title']), $alert_data['content'], "HTML");//邮件提醒                              
    //             //给投资人发送满标通知
    //             // if(is_array($invested_phones)){
    //             //   foreach ($invested_phones as $phone_v) {
    //             //     $msg_str = "满标通知：您投资的项目【".$loan['loan_title'].'】于'.date('Y-m-d H:i:s',NOW_TIME).'完成满额';
    //             //     send_sms($phone_v,$msg_str);
    //             //   }
    //             // }                  
    //         }
    //         $user_db->where($user_where)->save($user_update);   
    //         /*写入交易记录表*/
    //         $deal_log_data[0]['uid'] = $uid;
    //         $deal_log_data[0]['lid'] = $lid;
    //         $deal_log_data[0]['invested_id'] = $if;
    //         $deal_log_data[0]['money'] = $amount;
    //         $deal_log_data[0]['type'] = 2;//交易状态为投资
    //         $deal_log_data[0]['pay_status'] = 2;//收支状态为支出            
    //         /*记录当前用户资金信息*/
    //         $user_info = $user_db->where('id='.$uid)->find();//读取投资后用户信息
    //         $now_user_money = $user_info['money'];//当前剩余金额记录
    //         $now_user_lock_money = $user_info['lock_money'];//当前剩余冻结金额
    //         $now_user_bonus_money = $user_info['bonus_money'];//当前剩余奖励金额                      
    //         if($user_add_rate != 0){
    //           $add_rate_detail = '使用加息券成功加息:'.$user_add_rate.'%。';
    //         }
    //         $deal_log_data[0]['detail'] = '投资成功：投资金额为'.$amount.'元。'.$add_rate_detail;//明细            
    //         $deal_log_data[0]['detail'] .= '当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';
    //         $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
    //         insert_deal_log($deal_log_data);  
    //         if(!empty($rate_id)){            
    //             $rate_data['use_status'] = 1;//使用状态
    //             $rate_data['lid'] = $lid;//项目ID
    //             M('web_user_rate')->where('id='.$rate_id)->save($rate_data);
    //         }
    //         // unset($invested_uids,$invested_phones);//释放数组   
    //         $this->success('投资成功',U('Deal/DealContent',array('lid'=>$lid)));
    //     }else{
    //         $this->error('数据错误');
    //     }
    // } 





    /**
     * @todo          DoAddInvested 添加投资处理
     * @param         $uid           投资会员ID
     * @param         $lid           所投项目ID
     */
    public function DoAddInvested(){            
        $uid = session('user_id');
        $refer = $_SERVER['HTTP_REFERER'];        
        $lid = I('lid',0);              //借款项目id
        $amount = I('money',0);         //投资金额  
        $rate_id = I('rate_id');        //加息券ID           
        if(empty($uid)){
            $data['info'] = '登录超时,请重新登录再进行投资'; 
            $data['url'] = U('user/Login');
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();
        }        
        //$this->error('登录超时,请重新登录再进行投资',U('user/Login'));
        if(empty($lid) || empty($amount)){ //说明页面跳过js判断
            $data['info'] = '非法操作!<br>error:1';
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();            
            //$this->error('非法操作!<br>error:1');
        }       		 
////////////
////////////
$fp = fopen("./m/lock/DoAddInvestedLock.txt", "r+");
if(flock($fp, LOCK_EX)){  
////////////
////////////
        $user_add_rate = 0;//初始化用户加息利率
        if(!empty($rate_id)){
          $rate_where['id'] = $rate_id;//加息券id
          $rate_where['uid'] = $uid;//用户ID
          $user_rate_res = M('web_user_rate')->where($rate_where)->find();          
          //若没过期,且未使用则设置附加利率
          // if(NOW_TIME <= $user_rate_res['deadline_time'] && $user_rate_res['use_status'] == '0'){
            $user_add_rate = $user_rate_res['add_rate'];            
          // }
        }  
        $user_db = M('web_user');       
        $user_where["id"] = $uid;
        //取出用户当前可用余额,冻结金额
        $user_res = $user_db->field('id,money,lock_money,bonus_money')->where($user_where)->find();                 
        $loan = M('loan')->field('loan_money,loan_rate,loan_deadline_type,loan_add_rate,loan_deadline,min_loan_money,max_loan_money,start_time,loan_title')->where("id = $lid")->find();//借款项目表 分别查询出：借款总额,年利率,期限                              
        $temp_money = 0;
        $temp_money = $amount * C('bonus_rate');//可用奖励金额            
        //当用户奖励金额 足够支付本次可用奖励金额时
        if($temp_money <= $user_res['bonus_money']){ 
           //投资可用余额 = 用户余额 + 可用奖励金额
		   $true_money = $user_res['money'] + $temp_money;
           $trade_money = $amount - $temp_money;//用户余额实际支付金额           
           $user_update['money'] = array('exp','money-'.$trade_money);//用户余额扣款
           $user_update['bonus_money'] = array('exp','bonus_money-'.$temp_money);//奖励金额扣款           
        }else{
          //投资可用余额 = 用户余额 + 奖励金额
          $true_money = $user_res['money'] + $user_res['bonus_money'];
          $trade_money = $amount - $user_res['bonus_money'];
          $user_update['money'] = array('exp','money-'.$trade_money);//用户余额扣款
          $user_update['bonus_money'] = 0;//奖励金额清零(使用全部)
        }    
        //当前用户可投资金额和投资金额对比
        if($true_money < $amount){
            $data['info'] = '当前用户余额不足';
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();                        
            //$this->error('当前用户余额不足');
        }
        $M_invested = M('Invested');//投资记录表
        $sum_invested = $M_invested->field("SUM(amount) sum_amount")->where("lid = $lid")->find();//查询已投资金额
        $loan_over = $loan['loan_money'] - $sum_invested['sum_amount']; //计算剩余借款额       
        //最低投资限制判断
        if($loan['min_loan_money'] != 0 && $amount < $loan['min_loan_money']){
            $data['info'] = '投资金额不可低于最低投资金额:'.$loan['min_loan_money'].'元';
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();               
            //$this->error('投资金额不可低于最低投资金额:'.$loan['min_loan_money'].'元');
        }
        //投资最低金额
        if($amount % $loan['min_loan_money'] != 0 ){
            $data['info'] = '投资金额需是'.format_price($loan['min_loan_money']).'或'.format_price($loan['min_loan_money']).'的倍数';
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();              
            //$this->error('投资金额需是'.format_price($loan['min_loan_money']).'或'.format_price($loan['min_loan_money']).'的倍数');
        }        
        //最高投资限制判断
        if($loan['max_loan_money'] != 0 && $amount > $loan['max_loan_money']){
            $data['info'] = '投资金额不可大于最高投资金额:'.$loan['max_loan_money'].'元';
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();                                         
            // $this->error('投资金额不可大于最高投资金额:'.$loan['max_loan_money'].'元');
        }       
        $last_money = $sum_invested['sum_amount']+$amount;//本次投资后的投资总额
        if($amount > $loan_over){ //判断如果输入金额大于剩余借款金额（说明页面跳过js计算）
            $data['info'] = '输入错误，投资总额超过总借款金额!<br>error:2';
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();    
            // $this->error('输入错误，投资总额超过总借款金额!<br>error:2');
        }   
        //判断项目是否开始
        if(NOW_TIME < $loan['start_time']){
            $data['info'] = '未开放的项目，请关注项目开放时间';
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();                       
          // $this->error('未开放的项目，请关注项目开放时间');
        }

        //投资信息表中取投资用户ID
        // $invested_ar = M('invested')->where('lid='.$lid)->select();
        // $invested_uids = array();//投资人ID集合
        // $invested_phones = array();//投资人电话号集合
        // foreach ($invested_ar as $key => $value) {
        //   $invested_uids[] = $value['uid'];
        // }
        // //读取投资用户手机号，并压入数组
        // if(is_array($invested_uids)){
        //   $u_where['id'] = array('IN',$invested_uids);
        //   $u_info = M('web_user')->where($u_where)->select();
        //   foreach ($u_info as $key => $value) {
        //     $invested_phones[] = $value['phone'];
        //   }                     
        // }  
        $loan_rate = ($loan['loan_rate'] + $loan['loan_add_rate'] + $user_add_rate) / 100 ;//年利率                                      
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
        $in_data = array(
            'uid' => $uid,                          //登录用户id
            'lid' => $lid,                          //借款项目id
            'amount' => $amount,                    //投资金额
            'bonus_money' => ($temp_money <= $user_res['bonus_money'])?$temp_money:$user_res['bonus_money'], //使用的奖励金额写进投资表            
            'repayment_amount' => $loan_deadline,   //利后所得金额
            'to_rate' => $loan_rate * 100,        //写入本次投资利率
            'status' => 0,                          //还款状态 0未1已
            'create_time' => time()                 //操作时间
        );          
        $if = $M_invested->add($in_data);
        //2015-05-11 12:19:10 update_by:liying $if判断!==false
        if($if !== false){                    

            //将投资金额转移到冻结金额
            $user_update['lock_money'] = array('exp','lock_money+'.$amount);//冻结金额                
            if($last_money == $loan['loan_money']){
                M('loan')->where('id='.$lid)->setField('deal_status','2');//若满额，改变借款状态 2 满额
                M('loan')->where('id='.$lid)->setField('success_time',NOW_TIME);//筹标成功时间
                 $alert_data['title'] = '满标提醒';
                 $alert_data['content'] = '网站有项目今天已满标，请前往后台查看。';
                 $alert_data['status'] = 1;
                 $alert_data['create_time'] = time();
                 M('system_alert')->add($alert_data);     
                 send_sms('13904314687',$alert_data['content']);//手机提醒
                 send_mail('2956000@qq.com','金投资', iconv('UTF-8','GB2312',$alert_data['title']), $alert_data['content'], "HTML");//邮件提醒                              
                //给投资人发送满标通知
                // if(is_array($invested_phones)){
                //   foreach ($invested_phones as $phone_v) {
                //     $msg_str = "满标通知：您投资的项目【".$loan['loan_title'].'】于'.date('Y-m-d H:i:s',NOW_TIME).'完成满额';
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
            /*记录当前用户资金信息*/
            $user_info = $user_db->where('id='.$uid)->find();//读取投资后用户信息
            $now_user_money = $user_info['money'];//当前剩余金额记录
            $now_user_lock_money = $user_info['lock_money'];//当前剩余冻结金额
            $now_user_bonus_money = $user_info['bonus_money'];//当前剩余奖励金额                      
            if($user_add_rate != 0){
              $add_rate_detail = '使用加息券成功加息:'.$user_add_rate.'%。';
            }
            $deal_log_data[0]['detail'] = '投资成功：投资金额为'.$amount.'元。'.$add_rate_detail;//明细            
            $deal_log_data[0]['detail'] .= '当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';
            $deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
            M('deal_log')->addAll($deal_log_data);  
            if(!empty($rate_id)){            
                $rate_data['use_status'] = 1;//使用状态
                $rate_data['lid'] = $lid;//项目ID
                M('web_user_rate')->where('id='.$rate_id)->save($rate_data);
            }
////////////////
////////////////
flock($fp, LOCK_UN);
fclose($fp);
////////////////
////////////////
            // unset($invested_uids,$invested_phones);//释放数组  
            $data['url'] = $refer; 
            $data['info'] = '投资成功!';
            $data['status'] = 1;
            $this->ajaxReturn($data,'JSON');exit();               
            //$this->success('投资成功',U('Deal/DealContent',array('lid'=>$lid)));
        }else{
            $data['info'] = '数据错误!';
            $data['status'] = 0;
            $this->ajaxReturn($data,'JSON');exit();              
            // $this->error('数据错误');
        }
////////////////
////////////////
}
////////////////
////////////////
    } 



    /**
     * @todo    DoVerify    提交投资的验证码比对
     */
    public function DoVerify(){
        $verify = I('post.verify');
        $verify = strtolower($verify); //转小写
        if(IS_AJAX){
          if($verify != session('verify')){
            $this->ajaxReturn('','验证码输入错误',0);  
          }else{
            $this->ajaxReturn('','',1);  
          }          
        }else{
            $this->error('请勿非法提交参数');
        }
    }  


    /**
     * @todo   4ActivityList   活动列表
     */
    public function ActivityList(){
      $this->display('activity_list');
    } 
}
 ?>