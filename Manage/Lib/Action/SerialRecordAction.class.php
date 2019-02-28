<?php
/**
 * @todo            会员充值流水号列表
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-10-28
 */
class SerialRecordAction extends CommonAction {


	//会员充值流水号视图
    public function Index(){
    	import('ORG.Util.Page');								
		$db = M('serial_record');
		$action = trim(I('action'));
        $user_name = trim(I('user_name'));
        $serialID = trim(I('serialID'));
        if(!empty($user_name))
            $where['user_name'] = $user_name;
        if(!empty($serialID))
            $where['serialID'] = array('LIKE','%'.$serialID.'%');        
        $parameter = array('user_name'=>$user_name,'serialID'=>$serialID);
		//分页
		$count = $db->where($where)->count();	
		$page = new Page($count,25,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
        //导出excel数据时不分页
        if($action == 'export_excel'){
            $limit = '';
        }
		//结束
		$info = $db->where($where)->order('create_time DESC,id DESC')->limit($limit)->select();
        /*导出excel数据*/	
		if($action =='export_excel'){
            foreach ($info as $key => $value) {
                $value['serialID'] = substr($value['serialID'],0,16).'-'.substr($value['serialID'],-4);
                $info[$key] = $value;
            }              
			$excel_title  = array('编号','用户ID','请求流水号','充值金额','用户名','请求时间');
			$excel_field = array('id','user_id','serialID','money','user_name','create_time');
			ExcelOut($info,$excel_title,$excel_field);
		}
		unset($uids);
		$this->page = $page->show();//分页
        $this->page_param = $parameter;//将分页参数传递到模版
		$this->info = $info;//内容
		$this->display();
    }

	/**
	 * @todo  InfoDelete  		 删除会员充值信息
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){
		$db = M('serial_record');		
		$del = $_POST['del'];
		//if($db->where('id in(' . implode(',',$del) . ')')->delete()){
		//	$this->success('信息删除成功!',U('RechargeLog/Index'));
		//}else{
			$this->error('信息删除失败!');
		//}
    }
}
?>