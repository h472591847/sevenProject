<?php
/**
 * @todo            验证码管理模块
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2015-3-17
 */
class CodeAdminAction extends CommonAction {

    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        parent::_initialize();
        $this->page_size = 25;
        $this->db = M('temp_code');
    }


	/**
	 * @todo  Index  		 验证码列表视图
	 */
    public function Index(){
    	import('ORG.Util.Page');
        $phone = trim(I('phone'));
        $status = trim(I('status'));
        if(!empty($phone))
            $where['phone'] = $phone;
        if($status != '')
            $where['status'] = $status;	
        //向分页传递参数   
        $parameter = array(
            'phone'=>$phone,
            'status'=>$status
        );             					
		//分页
		$count = $this->db->where($where)->count();		
		$page = new Page($count,$this->page_size,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
		//结束
		$info = $this->db->where($where)->order('create_date DESC,id DESC')->limit($limit)->select();				                                
        $this->page_param = $parameter;
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }

	/**
	 * @todo  InfoDelete  		 删除验证码
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){		
		$del = $_POST['del'];
		$save['is_delete'] = 1;
		if($this->db->where('id in(' . implode(',',$del) . ')')->save($save)){
			$this->redirect('Index');
		}else{
			$this->error('信息删除失败!');
		}
    }
}
?>