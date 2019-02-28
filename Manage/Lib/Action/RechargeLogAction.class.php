<?php
/**
 * @todo            会员充值列表
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-10-28
 */
class RechargeLogAction extends CommonAction {


	//会员充值列表视图
    public function Index(){
    	import('ORG.Util.Page');								
		$db = M('recharge_log');
		$user_id = trim(I('user_id'));
        $user_name = trim(I('user_name'));//会员用户名
        $k_start_time = strpos(I('k_start_time'),'-')?strtotime(I('k_start_time')):I('k_start_time');
        $k_end_time = strpos(I('k_end_time'),'-')?strtotime(I('k_end_time')):I('k_end_time');                
		$action = trim(I('action'));
		$text = trim(I('text')) ; //获取文本内容（批量流水号）
		$orderNo = I('orderNo'); //支付流水号
        //向分页传递参数   
        $parameter = array(
            'user_id'=>$user_id,
            'user_name'=>$user_name,            
			'orderNo'=>$orderNo,
			'text'=>$text,
            'k_start_time'=>$k_start_time,
            'k_end_time'=>$k_end_time,
            'action'=>$action
        );   
        // $k_start_time = strtotime($k_start_time); //完成时间开始段 转换时间戳     
        // $k_end_time = strtotime($k_end_time);//完成时间结束段 转换时间戳             
		if(!empty($user_id)){
			$where['user_id'] = $user_id;
		}
		//按条件筛选
        if(!empty($user_name)){        	
        	$user_res = M('web_user')->where("user_name='$user_name'")->find();        	        	
        	if($user_res)
        	  $where['user_id'] = $user_res['id'];        	        	
        }           
		if(!empty($orderNo))
            $where['orderNo'] = $orderNo;
        //导出excel时采用不格式化的时间
        if($action == 'export_excel'){ 
            $k_start_time = I('k_start_time');
            $k_end_time = I('k_end_time');
        }        
        if(!empty($k_start_time))
            $where['completeTime'] = array('EGT',date('YmdHis',$k_start_time));
        if(!empty($k_end_time))
            $where['completeTime'] = array('ELT',date('YmdHis',$k_end_time));       
        if(!empty($k_start_time) && !empty($k_end_time))
            $where['completeTime'] = array(array('EGT',date('YmdHis',$k_start_time)),array('ELT',date('YmdHis',$k_end_time)),'AND');  		
		//分页
		$count = $db->where($where)->count();	
		$page = new Page($count,25,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
        //导出excel数据时不分页
        if($action == 'export_excel'){
            $limit = '';
        }
		//结束
		$info = $db->where($where)->order('completeTime DESC,id DESC')->limit($limit)->select();
		$info_Contrast = $db->where($where)->order('completeTime DESC,id DESC')->select();//对比
		//echo $db->_sql();
		foreach($info_Contrast as $key => $value){
			$info_arr[] = $value['orderNo'];	//支付流水号数组
		}
		if(!empty($text)){
			$text_arr = explode("\r\n",$text) ;
			echo '<pre>文本：';
			p(array_diff($text_arr , $info_arr)) ."<br/>"; //对比差异 文本对比数据
			echo '数据：';
			p(array_diff($info_arr , $text_arr)) ."<br/>"; //对比差异 数据对比文本
		}
		$count_info = $db->field('sum(orderAmount) count_amount')->where($where)->find();
		$this->count_amount = $count_info['count_amount']/100;//当前充值总额
		$uids = array();
		foreach ($info as $key => $value) {
			$uids[] = $value['user_id'];
		}		
		$user_where['id'] = array('IN',$uids);
		$user_info = M('web_user')->where($user_where)->select();
		foreach ($info as $key => $value) {
			foreach ($user_info as $k => $v) {
				if($v['id']==$value['user_id']){
					$value['user_name'] = $v['user_name'];
				}
			}
			$value['orderAmount'] = $value['orderAmount']/100;//分转换成元
			$value['payAmount'] = $value['payAmount']/100;//分转换成元			
			//$value['completeTime'] =format_order_time($value['completeTime']);
		  $info[$key] = $value;
		}	
        /*导出excel数据*/	
		if($action =='export_excel'){
			foreach ($info as $key => $value) {
				$value['orderNo'] = substr($value['orderNo'],0,16).'-'.substr($value['orderNo'],-4);
				$value['acquiringTime'] =format_order_time($value['acquiringTime']);//格式化收单时间
				$value['completeTime'] =format_order_time($value['completeTime']);//格式化完成时间
				$info[$key] = $value;
			}
			$excel_title  = array('编号','商户订单号','支付流水号','处理结果码','状态码','订单金额','实付金额','收单时间','完成时间','充值用户名');
			$excel_field = array('id','orderID','orderNo','resultCode','stateCode','orderAmount','payAmount','acquiringTime','completeTime','user_name');
			ExcelOut($info,$excel_title,$excel_field);
		}
		unset($uids);
		$this->page_param = $parameter;//将分页参数传递到模版
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }

	/**
	 * @todo  InfoDelete  		 删除会员充值信息
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){
		$db = M('recharge_log');		
		$del = $_POST['del'];
		//if($db->where('id in(' . implode(',',$del) . ')')->delete()){
		//	$this->success('信息删除成功!',U('RechargeLog/Index'));
		//}else{
			$this->error('信息删除失败!');
		//}
    }
}
?>