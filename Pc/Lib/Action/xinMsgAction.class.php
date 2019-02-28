<?php 
/**
 *@todo       接口商户通知页控制器
 *@since      2014-08-01
 *@author     liying <472591847@qq.com>
 *@copyright  金湧博发
 */
class xinMsgAction extends Action{
//此商户通知  测试中不可用

    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        //parent::_initialize();
        $this->db = M('recharge_log');
        $this->phone_title = "金 投 资";//用于发送短信的公司名称
    }



    /**
     * @todo  write_serialid_record    记录请求流水号
     * @param array        user_id,user_name,money,serialID
     */
    public function write_serialid_record(){    
        /*接收到的异步数据，拆分取键值用作字段值*/    
        if(!empty($_POST['data'])){
          $data_ar = explode("&", $_POST['data']);
          if(!empty($data_ar[0])){
              $serialID_ar = explode("=", $data_ar[0]);        
              $serialID = $serialID_ar[1];
              $insert_data['serialID'] = $serialID;
          }
          if(!empty($data_ar[1])){
              $user_id_ar = explode("=", $data_ar[1]);        
              //++++++++2015-04-24 10:14:26 update_by:liying++++++++
              $user_id = (int)getDecrypt(rawurldecode($user_id_ar[1]));
              // $user_id = $user_id_ar[1];
              $insert_data['user_id'] = $user_id;
          }          
          $u_info = M('web_user')->where('id='.$user_id)->find();
          if(!empty($data_ar[2])){
              // $user_name_ar = explode("=", $data_ar[2]);        
              // $user_name = $user_name_ar[1];
              $user_name = $u_info['user_name'];
              $insert_data['user_name'] = $user_name;
          }
          if(!empty($data_ar[3])){
              $money_ar = explode("=", $data_ar[3]);        
              $money = $money_ar[1];
              $insert_data['money'] = $money;
          } 
          //创建时间
          $insert_data['create_time'] = NOW_TIME;     
          M('serial_record')->add($insert_data);
        }
    }



    /**
     * @todo  incharge_done    接口回调，写入数据库处理
     * @return 接收商户通知参数写入数据库，并修改用户表金额
     */ 
    public function incharge_done(){
        /*incharge_done    接口回调，写入数据库处理 开始  */
		$signType = $_REQUEST["signType"];
        $signMsg = $_REQUEST["signMsg"];
        $orderID= $_REQUEST["orderID"];
        $resultCode = $_REQUEST["resultCode"];
        $stateCode = $_REQUEST["stateCode"];
        $orderAmount = $_REQUEST["orderAmount"];
        $payAmount = $_REQUEST["payAmount"];
        $acquiringTime = $_REQUEST["acquiringTime"];            
        $completeTime = $_REQUEST["completeTime"];
        $orderNo = $_REQUEST["orderNo"];
        $partnerID = $_REQUEST["partnerID"];
        $remark = $_REQUEST["remark"];
        $charset = $_REQUEST["charset"];
        $remark_arr = explode(",", $remark);//扩展字段拆分
        //++++++++++2015-04-24 10:12:24 update_by:liying        
        $user_id = (int)getDecrypt(rawurldecode($remark_arr[0])); 
        $u_info = M('web_user')->where('id='.$user_id)->find();
        $user_name = $u_info['user_name'];
        $user_pwd = $u_info['user_pwd']; 
        //++++++++++2015-04-24 10:12:24 update_by:liying
        // $user_id = $remark_arr[0];
        // $user_name = $remark_arr[1];
        // $user_pwd = $remark_arr[2];       
        $slt_bank_name = rawurldecode($remark_arr[3]);//选择的银行
        //MD5验签
        $src = "orderID=".$orderID
        ."&resultCode=".$resultCode
        ."&stateCode=".$stateCode
        ."&orderAmount=".$orderAmount
        ."&payAmount=".$payAmount
        ."&acquiringTime=".$acquiringTime
        ."&completeTime=".$completeTime
        ."&orderNo=".$orderNo
        ."&partnerID=".$partnerID
        ."&remark=".$remark
        ."&charset=".$charset
        ."&signType=".$signType;
		if(2 == $signType) //md5验签
		{
			//$pkey = "30819f300d06092a864886f70d010101050003818d0030818902818100af48db8f2aa0f029aa3fadfcf749267566544d2ce25738df295d94bb918542421f2b649e533db53553a3e9a4bd860068b759af4681d96f4732e47b4653b93339e68b7ef00e2cef3ab8ec5e9c65d4c7992f626c1f4b9a95a626d005fce59919ae1b619748194256ec4627a932d75b8f9fb51c3a78bfbc53bcc0bbb4924d59c6310203010001";
			$pkey = "30819f300d06092a864886f70d010101050003818d003081890281810080c1f67eecf234550e364e318a386ea287fb0a22bf051845825ee8bcab2c5fe61d56165ff4d374a01d00af8930a174c099efbe90656a9c3ce0781822d98e41b5626ddd22abaf68a9ab267bae503a0154c41e3c1ee203737c4621aaa804c3ddb2d89aa385d57e8ddbcd6fa85a8ddac3bcb43dc352c0a6d4232dad4dab878f075f0203010001";
			$src = $src."&pkey=".$pkey;
			$ret = md5($src);
			$status = ($signMsg == md5($src));
		}
        //如果验签失败 则停止操作
		if(!$status){
			echo '验签失败！';
			die();
		}
        $where_recharge_log['orderID'] = $orderID;
        $where_recharge_log['stateCode'] = 2;  //状态为成功
		///////////////////////////////////////////////////////M修改于 2015年1月14日 11:27:59 ++
		////////////////////////////////////////////////////////控制并发
		$fp = fopen("./mysql_lock.txt", "r+");
		if (flock($fp, LOCK_EX)) {  // 进行排它型锁定
			$count = (int)fgets($fp);
			$count += 1;
			fseek($fp, 0);
			fwrite($fp, (string)$count);
			fflush($fp);            // flush output before releasing the lock
		///////////////////////////////////////////////////////+
		///////////////////////////////////////////////////////
			//查询此订单号是否充值成功过。若存在则停止      
			$res_log = M('recharge_log')->where($where_recharge_log)->find();
			if($res_log){
				echo '订单充值已完成';
				die();
			} 
			//记录生成的验签           
			$ret = isset($ret)?$ret:0;              
			//将收到信息打包成字符串
			$str = array();     
			$str["orderID"] = $orderID;
			$str["resultCode"] = $resultCode;
			$str["stateCode"] = $stateCode;
			$str["orderAmount"] = $orderAmount;
			$str["payAmount"] = $payAmount;
			$str["acquiringTime"] = $acquiringTime;
			$str["completeTime"] = $completeTime;
			$str["orderNo"] = $orderNo;
			$str["partnerID"] = $partnerID;
			$str["remark"] = $remark;
			$str["charset"] = $charset; 
			$str["signType"] = $signType;   
			$str["signMsg"] = $signMsg; 
			$str["user_id"] = $user_id;             
            $str["slt_bank_name"] = $slt_bank_name;
			$str = implode("|", $str);  
			$money = $payAmount/100;//分转成元为进制
	
			// $insert = "insert into ".C('DB_PREFIX')."recharge_log ";
			// $insert.="(orderID,resultCode,stateCode,orderAmount,payAmount,";
			// $insert.="acquiringTime,completeTime,orderNo,partnerID,remark,charset,signType,signMsg,ret,str,user_id)VALUES(";
			// $insert.="'$orderID','$resultCode','$stateCode','$orderAmount','$payAmount','$acquiringTime','$completeTime','$orderNo','$partnerID','$remark','$charset','$signType'";
			// $insert.=",'$signMsg','$ret','$str',$user_id)"; 
			if($resultCode==""){
				switch ($stateCode) {
					case '0':
					   $resultCode = '已接受';
					   break;
					case '1':
					   $resultCode = '处理中';
					   break;                                               
					case '2':
					   $resultCode = '处理成功';
					   break;   
					case '3':
					   $resultCode = '处理失败';
					   break;                                               
				}
			}  
			$insert['orderID'] = $orderID;
			$insert['resultCode'] = $resultCode;
			$insert['stateCode'] = $stateCode;
			$insert['orderAmount'] = $orderAmount;
			$insert['payAmount'] = $payAmount;
			$insert['acquiringTime'] = $acquiringTime;
			$insert['completeTime'] = $completeTime;
			$insert['orderNo'] = $orderNo;
			$insert['partnerID'] = $partnerID;
			$insert['remark'] = $remark;
			$insert['charset'] = $charset;
			$insert['signType'] = $signType;
			$insert['signMsg'] = $signMsg;
			$insert['ret'] = $ret;
			$insert['str'] = $str;
			$insert['user_id'] = $user_id;
            $insert['slt_bank_name'] = $slt_bank_name;
			if(M('recharge_log')->add($insert) !== false){//插入充值日志表
				$where_update['id'] = $user_id;
				$where_update['user_name'] = $user_name;
				$where_update['user_pwd'] = $user_pwd;
				//$update = "update ".C('DB_PREFIX')."web_user set money=money+".$money." where id=".$user_id." and user_name='$user_name' and user_pwd='$user_pwd'";         
				$update_money = M('web_user')->where($where_update)->setInc('money',$money);//修改会员余额+充值金额
				$user_res = M('web_user')->field('phone,money,lock_money,bonus_money')->where($where_update)->find();
				//手机短信消息内容
				// $msg_phone_str = '尊敬的'.$this->phone_title.'用户，您已成功充值:'.$money.'元,如有任何疑问请致电'.C('web_tel');
				$msg_str = '充值成功：充值金额为'.$money.'元。';//站内消息内容
                $now_user_money = $user_res['money'];
                $now_user_lock_money = $user_res['lock_money'];
                $now_user_bonus_money = $user_res['bonus_money'];
                $msg_detail = '当前剩余金额:'.$now_user_money.'元,剩余冻结金额:'.$now_user_lock_money.'元,剩余奖励金额:'.$now_user_bonus_money.'元';//明细
				/*写入交易记录表*/
				$deal_log_data[0]['uid'] = $user_id;
				$deal_log_data[0]['lid'] = '';
				$deal_log_data[0]['invested_id'] = '';
				$deal_log_data[0]['money'] = $money;
				$deal_log_data[0]['type'] = 1;//交易状态为充值
				$deal_log_data[0]['pay_status'] = 1;//收支状态为收入
				$deal_log_data[0]['detail'] = $msg_str.$msg_detail;//明细
				$deal_log_data[0]['create_time'] = NOW_TIME;//交易时间    
				insert_deal_log($deal_log_data);              
                // ++++++++++++++2015-04-07 17:04:42 update_by:liying++++++++++
                $message = $user_name.",".$money;
                send_sms($user_res['phone'],$message,'充值成功');                
                // ++++++++++++++2015-04-07 17:04:42 update_by:liying++++++++++                                   
                //                 
				// send_sms($user_res['phone'],$msg_phone_str);//发送给用户通知短信
			}   
		////////////////////////////////////////////////////////////////////////////////////////////////////////////+
			flock($fp, LOCK_UN);    // 释放锁定		
		} else {
			echo "Couldn't get the lock!";
		}
		fclose($fp);
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////++
    }
}    