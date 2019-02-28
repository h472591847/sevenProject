<?php 
/**
 * @todo            新春特别标专题页控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2015-1-15
 */
class LotteryAction extends Action{

    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){        
        $this->page_size = 6;
        $this->phone_title = "金 投 资";//手机短信显示的公司名称
        $this->uid = session("user_id");
        $this->user_db = M('web_user');
    }


    /**
     * @todo       index        专题页视图
     */
    public function index(){                      
       $lt_db = M('lt');
       $record_db = M('record');
       $lt_arr = $lt_db->order('id asc')->select();       
       $i = 0;
       foreach ($lt_arr as $key => $value) {
           $i++;
           $value['i'] = $i;
           $lt_arr[$key] = $value;
       }
       //读取中奖记录表
       $record_ar = $record_db->order('create_time DESC,id DESC')->select();
       $uids = array();
       //用户id集合
       foreach ($record_ar as $key => $value) {
           $uids[] = $value['uid'];           
       }
       /*取关联用户信息，压入结果集*/
       if(is_array($uids)){
           $user_where['id'] = array('IN',$uids);
           $user_arr = $this->user_db->where($user_where)->select();//读取关联用户信息
           foreach ($record_ar as $key => $value) {
                foreach ($user_arr as $k => $v) {
                    if($v['id'] == $value['uid']){
                        $value['user_name'] = $v['user_name'];
                    }
                } 
                //奖品名称关联  
                foreach ($lt_arr as $lt_k => $lt_v) {
                   if($lt_v['id'] == $value['lt_prize']){
                      $value['lt_prize_name'] = $lt_v['lt_prize'];
                   }
                }
                $left_phone = substr($value['user_name'], 0,2);//截取左数2位
                $right_phone = substr($value['user_name'], -2);//截取右数2位                               
                $repeat_str = str_repeat('*', 4); //替换中间4位字符为星号
                $value['user_name'] = $left_phone.$repeat_str.$right_phone;
                $record_ar[$key] = $value;       
           }
       }       
       //p($record_ar);die;
       unset($uids);//释放数组
       //会员登录则读取会员信息
       if(!empty($this->uid))
            $this->user_info = $this->user_db->where('id='.$this->uid)->find();
       $this->record_ar = $record_ar;//中奖记录信息
       $this->lt_arr = $lt_arr;//奖品名额，以及概率。
       $this->display();
    }



    /**
     * @todo  DoLottery   大转盘处理
     * @param $type        抽奖类型
     */
    public function DoLottery(){
        $host = 'http://'.$_SERVER['HTTP_HOST'];//获取主机地址
        $true_url = $host.U('Lottery/index');//拼接正确的来路地址
        $referer = $_SERVER['HTTP_REFERER'];//获取来路
        $lt_db = M('lt');
        $record_db = M('record'); 
        $web_user_rate_db = M('web_user_rate');
        $invested_db = M('invested');
        $type = I('post.type');//抽奖类型

        // $start_time = strtotime('2015-02-9');//活动开始时间
        // $end_time = strtotime('2015-02-12');//活动结束时间
        $start_time = strtotime('2018-05-16');//活动开始时间
        $end_time = strtotime('2018-05-26');//活动结束时间        
        //活动开始判断
        if(NOW_TIME < $start_time){
           $result['info'] = "活动尚未开始，请在".date('Y-m-d',$start_time)."至".date('Y-m-d',$end_time)."之间参与此活动。";
           $this->ajaxreturn('',$result['info'],0);
           exit();               
        }
        //活动结束判断
        if(NOW_TIME > $end_time){
           $result['info'] = "活动已结束，下次再来吧！";
           $this->ajaxreturn('',$result['info'],0);
           exit();               
        }        
        //判断是否登录
        if(empty($this->uid)){
           $result['info'] = "您还尚未登录，不能进行抽奖!err:0";
           $this->ajaxreturn('',$result['info'],0);
           exit();            
        }
        $user_info = $this->user_db->where('id='.$this->uid)->find();        
        //提交方式过滤
        if(!IS_POST || !IS_AJAX){
           $result['info'] = "提交方式错误!err:1";
           $this->ajaxreturn('',$result['info'],0);
           exit();
        }
        //提交参数判断
        if($type != 'rotate'){        
           $result['info'] = "参数错误!err:2";
           $this->ajaxreturn('',$result['info'],0);
           exit();
        }   
        //防跨站提交
        if($referer != $true_url){            
           $result['info'] = "请勿非法提交参数!err:3";
           $this->ajaxreturn('',$result['info'],0);
           exit();
        }        

        /*判断没有新春标成功投资记录不可投资*/
        $invested_where['lid'] = 19;//新春特别标ID
        $invested_where['uid'] = $this->uid;
        $invested_where['status'] = 1;//还款状态：已还款
        $invested_count = $invested_db->where($invested_where)->count();
        // if(empty($invested_count)){
        //    $info_html = "需要投资<a href=\"".U('Deal/DealContent',array('lid'=>19))."\" target=\"_blank\" class=\"result_jump\">新春特别标</a>，并还款后才可以进行抽奖哦! err:4";
        //    $result['info'] = $info_html;
        //    $this->ajaxreturn('',$result['info'],0);
        //    exit();            
        // }

        /*查询此会员中奖记录，每个投资会员只能抽奖3次*/
        // $record_count = $record_db->where('uid='.$this->uid)->count();
        // if($record_count >= 3){
        //    $result['info'] = "您的抽奖机会用光了，敬请期待下次活动! err:5";
        //    $this->ajaxreturn('',$result['info'],0);
        //    exit();              
        // }

        /*验证同一IP不能重复抽奖*/
        $today = NOW_TIME;
        $yesterday = strtotime(date("Y-m-d",strtotime("-1 day"))); 
        $tomorrow = strtotime(date("Y-m-d",strtotime("+1 day")));        
        $record_where['ip'] = get_client_ip();
        $record_where['create_time'] = array(array('GT',$yesterday),array('LT',$tomorrow),'AND');
        $record = $record_db->where($record_where)->count();                
        // if($record>0){
        //    $this->ajaxreturn('',$result['info'],0);
        //    exit();
        // }

        //读取抽奖配置信息
        $sql = 'select * from `lt` order by id asc';
        $query_sql = mysql_query($sql);
        $prize_arr = array();
        $i = 0;
        $lt_res = $lt_db->order('id asc')->select();
        foreach($lt_res as $key => $rs){
            $i++;
            $lt_min = explode(',',$rs['lt_min']); 
            $lt_max = explode(',',$rs['lt_max']);    
            $rs['lt_min'] = count($lt_min)>1?$lt_min:$rs['lt_min'];//如果多个角度，则返回数组 
            $rs['lt_max'] = count($lt_max)>1?$lt_min:$rs['lt_max']; //如果多个角度，则返回数组   
            $rs_count = $record_db->where('lt_prize='.$rs['id'])->count();
            $prize_arr[$i] = array(
                'id'=>$rs['id'],
                'min'=>$rs['lt_min'],//最小角度(大于0度 小于360度) 多个角度用,分隔
                'max'=>$rs['lt_max'],//最大角度 (大于0度 小于360度) 多个角度用,分隔
                'prize'=>$rs['lt_prize'],//奖项 （奖品名称）
                'v'=>$rs['lt_v'],//中奖概率 （单位 %）
                'lt_allowed'=>$rs['lt_allowed'],//中奖名额
                'c'=>$rs_count //中奖记录表中的已中奖数量    
            );   
        }        
        /*如果中奖数量已达名额上限，则此奖项概率归零,并记录总概率*/
        foreach ($prize_arr as $key =>$val) {
            if($val['c'] >= $val['lt_allowed']){
                $val['v'] = 0;
            }else{
                $val['v'] = $val['v'];
            }  
            $count_v += $val['v'];//奖项总概率 
           $prize_arr[$key] = $val;
        } 
        /*判断总概率若为0 则表示名额已用完，不能再抽奖*/
        if($count_v == 0){
            $result['info'] = "当前所有奖项已被抽空，请下次再来err:6";
            $this->ajaxreturn('',$result['info'],'0');
            exit();    
        }

        //取出中奖配置
        foreach ($prize_arr as $key =>$val) {
            $arr[$val['id']] = $val['v'];
        }
        $rid = $this->getRand($arr); //根据概率获取奖项id
        // $res = $prize_arr[$rid-1]; //中奖项
        $res = $prize_arr[$rid]; //中奖项        
        $min = $res['min'];//得到最小角度
        $max = $res['max'];//得到最大角度
        $result['angle'] = mt_rand($min,$max); //配置好的角度范围内随机生成一个角度
        $result['prize'] = $res['prize'];
        $add_record_data['real_name'] = $user_info['real_name'];
        $add_record_data['uid'] = $user_info['id'];
        $add_record_data['ip'] = get_client_ip();
        $add_record_data['lt_prize'] = $rid;
        $add_record_data['create_time'] = NOW_TIME;
        /*插入中奖记录表*/        
        if($res['prize']){            
            $insert_record = $record_db->add($add_record_data);
            $result['record_id'] = $insert_record;//记录ID用于收货地址修改
            $result['lt_id'] = $res['id'];//奖项ID
            /*如果中了六等奖（再次投资加息1%）则插入到用户附加利息表一条数据*/
            if($rid == 6){
                $user_rate_data['add_rate'] = '1';
                $user_rate_data['use_status'] = '0';
                $user_rate_data['uid'] = $this->uid;
                $user_rate_data['effect_deadline'] = 30;//有效期为一个月 单位：天                
                $user_rate_data['deadline_time'] = ($user_rate_data['effect_deadline'] * 24 * 3600) + NOW_TIME;
                $user_rate_data['create_time'] = NOW_TIME;
                insert_web_user_rate($user_rate_data);
            }
        } 
        $result['status'] = 1;       
        $this->ajaxreturn($result);
    }

    //获取中奖项ID
    public function getRand($proArr) {
        $result = '';
        //概率数组的总概率精度
        $proSum = array_sum($proArr);
        //概率数组循环
        foreach ($proArr as $key =>$proCur) {
            $randNum = mt_rand(1, $proSum);
            if ($randNum <= $proCur) {
                $result = $key;
                break;
            } else {
                $proSum -= $proCur;
            }
        }
        unset ($proArr);
        return $result;
    }


    /**
     * @todo   promote_introduce   平台推广页
     */
    public function promote_introduce(){
       $this->loan = M('loan')->where('recommend = 1')->order('loan_order desc,create_time desc')->limit('0,1')->find();       
       $this->display();
    }


    /**
     * @todo    credits   积分推广页
     */
    public function credits(){
       $this->display();
    }


    /**
     * @todo   DoSaveAddress   保存收货地址      
      *@param  $record_id      中奖记录ID
      *@param  $real_name      收货人姓名
      *@param  $address        详细地址
      *@param  $phone          联系方式
     */
    public function DoSaveAddress(){
      $record_db = M('record');//中奖记录表
      $record_id = I('post.record_id');//中奖记录ID
      $real_name = I('post.real_name');//收货人姓名
      $address = I('post.address');//详细地址
      $phone = I('post.phone');//联系方式
      if(!IS_POST)
        $this->error('提交方式错误');
      if(!$record_db->autoCheckToken($_POST))
        $this->error('令牌验证失败,请勿重复提交');
      if(empty($record_id))
        $this->error('请勿非法提交参数');
      if(empty($real_name) || empty($address) || empty($phone))
        $this->error('请填写完整的收货信息');
      $record_data['real_name'] = $real_name;
      $record_data['address'] = $address;
      $record_data['phone'] = $phone;
      session('record_data',$record_data); //记录填写的收货地址，以便用户再次填写     
      //修改中奖记录的收货信息
      $update = $record_db->where('id='.$record_id)->save($record_data);
      if($update !== false){
        $this->success('恭喜您，收货地址填写成功,工作人员会及时与您联系');
      }else{
        $this->error('数据错误');
      }
    }

    public function mode(){
      $this->display();
    }


    /**
     * @todo  feed_back   意见反馈页面视图
     */
    public function feed_back(){                     
      $feed_back_db = M('feed_back');         
      $limit = '0,14';        
      $feed_list = $feed_back_db->where('is_check=1')->order('score DESC,create_time DESC,id DESC')->limit($limit)->select();      
      $uids = array();
      foreach ($feed_list as $key => $value) {
         $uids[] = $value['uid'];
      }
      $user_where['id'] = array('IN',$uids);
      $user_info = $this->user_db->where($user_where)->select();
      $i = 0;      
      foreach ($feed_list as $key => $value) {
        $i++;
        $value["i"] = $i;
        foreach ($user_info as $k => $v) {
           if($v['id'] == $value['uid']){
              $user_left = substr($v['user_name'], 0,2);
              $user_right = substr($v['user_name'], -2);
              $value['user_name'] = $user_left.'****'.$user_right;
              $value['face_pic'] = $v['face_pic'];
           }
        }
        $feed_list[$key] = $value;
      }      
      unset($uids);      
      $this->feed_list = $feed_list;
      $this->display();
    }


    /**
     * @todo   feed_list    意见反馈列表(iframe)
     */
    public function feed_list(){
      import('ORG.Util.Page'); //引入分页                       
      $feed_back_db = M('feed_back');      
      $count = $feed_back_db->where('is_check=1')->count();   
      $page = new Page($count,3); 
      $limit = $page->firstRow . ',' . $page->listRows;     
      $feed_list = $feed_back_db->where('is_check=1')->order('score DESC,create_time DESC,id DESC')->limit($limit)->select();                            
      $uids = array();
      foreach ($feed_list as $key => $value) {
         $uids[] = $value['uid'];
      }
      $user_where['id'] = array('IN',$uids);
      $user_info = $this->user_db->where($user_where)->select();
      foreach ($feed_list as $key => $value) {
        foreach ($user_info as $k => $v) {
           if($v['id'] == $value['uid']){
              $value['face_pic'] = $v['face_pic'];
           }
        }
        $feed_list[$key] = $value;
      }         
      unset($uids);  
      $page->setConfig('theme',"%nowPage%/%totalPage% 页 %upPage%   %linkPage% %downPage%");
      $this->page = $page->show();
      $this->feed_list = $feed_list;      
      $this->display();
    }


    /**
     * @todo  DoFeedBack   意见反馈处理
     */
    public function DoFeedBack(){
      $content = I('post.content');
      $verify = I('post.verify');
      $uid = $this->uid;
      $feed_back_db = M('feed_back');
      if(empty($this->uid))
        $this->error('请登陆后进行操作',U('user/Login'));            
      if(empty($content))
        $this->error('意见内容不能为空');
      if(strtolower($verify) != session('verify')) $this->error('验证码错误');//判断验证码        
      $data['content'] = $content;
      $data['uid'] = $uid;
      $data['create_time'] = NOW_TIME;
      $insert = $feed_back_db->add($data);
      if($insert !== false){
        $this->success('感谢您提出的宝贵意见，若您的意见被采纳，将获得50-500分的奖励');
      }else{
        $this->error('数据错误');
      }
    }


    /**
     * @todo  qualification   资质推广页
     */
    public function qualification(){
      $db = M('ad');
      $info_db = M('info');
      $limit = '0,20';
      $ad_field = 'id,ad_name,ad_image';
      $info_field = 'id,title,img,imgs,description';
      $this->zz_list = $db->field($ad_field)->where("class_name='我们的资质'")->order('ad_order desc,create_time desc,id desc')->limit($limit)->select();
      $hzhb_list = $info_db->field($info_field)->where("pid=42")->order('sort desc,time desc,id desc')->limit($limit)->select();      
      foreach ($hzhb_list as $key => $value) {
        if(!empty($value['imgs'])){
          $value['imgs'] = explode("\r\n", $value['imgs']);
        }
        $hzhb_list[$key] = $value;
      }    
      //p($hzhb_list);die;    
      $this->hzhb_list = $hzhb_list;
      $this->qysj_list = $db->field($ad_field)->where("class_name='企业实景'")->order('ad_order desc,create_time desc,id desc')->limit($limit)->select();
      $gsly_list = $db->field($ad_field)->where("class_name='分公司掠影'")->order('ad_order desc,create_time desc,id desc')->limit($limit)->select();           
      $this->gsly_list = $gsly_list;       
      $this->dywst_list = $db->field($ad_field)->where("class_name='抵押物实图'")->order('ad_order desc,create_time desc,id desc')->limit($limit)->select();
      $this->jkxyzs_list = $db->field($ad_field)->where("class_name='借款协议展示'")->order('ad_order desc,create_time desc,id desc')->limit($limit)->select();
      $this->display();
    }


    /**
     * @todo  activity_integral   积分活动推广页
     */
    public function activity_integral(){
      $this->display();
    }


    /**
     * @todo   bond_advantage     债权优势
     */
    public function bond_advantage(){
      $this->display();
    }


    /**
     * @todo  cumulative  累计积分
     */
    public function cumulative(){
      $this->display();
    }


    /**
     * @todo    upgrade    托管升级专题页
     */
    public function upgrade(){
      $this->display();
    }    
}