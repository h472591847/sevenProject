<?php

/**
 * @todo            前台首页控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-27
 */
class SMSnotificationAction extends Action {

	//接收
    public function  index(){   
		p($_POST);
    }
	//发送测试
		
//		$phone = I("phone",0,"intval");
//		$verify = I("verify",0,"intval");
//		if($phone && $verify){
//			if(voice_verify($phone,$verify)){
//				echo '发送成功';
//			}else{
//				echo '发送失败';
//			}
//		}
		
//    }
}