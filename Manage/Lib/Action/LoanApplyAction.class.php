<?php
/**
 * @todo            贷款申请列表
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-10-30
 */
class LoanApplyAction extends CommonAction {


	//贷款申请列表视图
    public function Index(){
    	import('ORG.Util.Page');								
		$db = M('loan_apply');		
		//分页
		$count = $db->where($where)->count();		
		$page = new Page($count,25);
		$limit = $page->firstRow . ',' . $page->listRows;
		//结束
		$info = $db->where($where)->order('create_time DESC,id DESC')->limit($limit)->select();		
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->display();
    }


	/**
	 * @todo  InfoDelete  		 删除贷款申请信息
	 * @param $del      array    要删除的主键ID
	 */
	public function InfoDelete(){
		$db = M('loan_apply');				
		$del = $_POST['del'];
		if($db->where('id in(' . implode(',',$del) . ')')->delete()){
			$this->success('信息删除成功!',U('LoanApply/Index'));
		}else{
			$this->error('信息删除失败!');
		}
    }
}
?>