<?php
/**
 * @todo            后台首页控制器
 * @copyright 	    p2p222.com
 * @author 			LiYing <472591847@qq.com>
 * @package 		JinYongBoFa
 * @version 		1.0
 * @since 			2014-11-12
 */
class IndexAction extends CommonAction {

    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        parent::_initialize();
        $this->page_size = 25;     
        $this->db = M('user');
        $this->uid = session(C('USER_AUTH_KEY'));
    }	


	/**
	 * @todo   Index     框架首页视图
	 */
    public function Index(){        
		$this->display();
    }


	/**
	 * @todo   Logout     退出登录
	 */	
	public function Logout(){
		session_unset();
		session_destroy();
		$this->redirect('Login/Index');
	}


    /**
     * @todo  Menu   后台菜单
     */
    public function Menu(){ 
        $node_db = M('node');
        $role_user_db = M('role_user');
        $access_db = M('access');        
        $node_ids = array();
        $action_ids = array();
        $role_ids = $role_user_db->where("user_id = ".$this->uid)->getField('role_id',true);        
        $access_where['role_id'] = array('IN',$role_ids);
        $access_res = $access_db->where($access_where)->select();        
        foreach ($access_res as $key => $value) {
            $node_ids[] = $value['node_id'];
        }
        $action_where['id'] = array('IN',$node_ids);
        $action_where['pid'] = 5;
        $action_res = $node_db->where($action_where)->order('id ASC')->select();
        foreach ($action_res as $key => $value) {
            $action_ids[] = $value['id'];
        }        
        $module_where['pid'] = array('IN',$action_ids);
        $module_res = $node_db->where($module_where)->order('id ASC')->select();
        $uri_ar = array();
        foreach ($action_res as $key => $value) {
            foreach ($module_res as $k => $v) {
                if($v['pid'] == $value['id']){
                    $value['uri'] = $value['name'].'/'.$v['name'];
                    
                }
            }
            $action_res[$k][$key] = $value;
        }
        foreach ($module_res as $key => $value) {
            foreach ($action_res as $k => $v) {
                if($v['id'] == $value['pid']){
                    $value['uri'] = $v['name'].'/'.$value['name'];
                    
                }
            }   
            $uri_ar[] = $value['uri'];         
        }  
        if(!empty($uri_ar)){
            $this->uri_ar = $uri_ar;//此登录用户所拥有的，控制器和动作名集合            
        }         
        unset($node_ids,$action_ids,$uri_ar) ;      
        $this->display();
    }


	/**
	 * @todo   Main     后台首页视图
	 */
	public function Main(){	
		$user_info = $this->db->where('id='.$this->uid)->find();		
		$where_loan['is_effect'] = 1;
		$where_loan['is_delete'] = 0;
		$where_loan_count = array_merge(array('deal_status'=>1),$where_loan);
		$this->loan_count = M('loan')->where($where_loan_count)->count();//进行中的项目条数
		$where_loan_full = array_merge(array('deal_status'=>2),$where_loan);
		$this->full_loan_count = M('loan')->where($where_loan_full)->count();//满标可还款的项目
		$where_loan_over = array_merge(array('deal_status'=>5),$where_loan);
		$this->loan_over_count = M('loan')->where($where_loan_over)->count();//已还清（成交）项目		
		$field_over_money = 'sum(loan_money) as money';
		$this->loan_over_money = M('loan')->field($field_over_money)->where($where_loan_over)->find();//成交总额
		$recharge = M('recharge_log')->field('sum(payAmount) as money')->where('stateCode=2')->find();
		$recharge['money'] = $recharge['money']/100;//分转换为元      
        $end_where['deal_status'] = 4;//状态:还款中
        $end_where = array_merge($end_where,$where_loan);
        $loan_res = M('loan')->where($end_where)->select();        
        $end_count = 0;
        //即将到期的项目
        foreach ($loan_res as $key => $value) {
            //借款期限时间 (天，月)
            if($value['loan_deadline_type'] == 1){
                $loan_deadline = $value['loan_deadline']  * 24 * 3600;
            }else{
                $loan_deadline = $value['loan_deadline'] * 30 * 24 * 3600;
            }      
            //到期时间 = 还款确认时间 + 借款期限时间       
            $dead_line =  $value['repay_confirm_time'] + $loan_deadline;            
            //剩余借款时间 = 总时间 - 当前时间
            $re_time = $dead_line - time(); 
            $three_day = 3 * 24 * 3600; 
            if($re_time <= $three_day){
                $end_count++;
            }  
        }        
        $this->end_count = $end_count;
		$this->recharge = $recharge;
		$this->loan_apply_count = M('loan_apply')->count();	//贷款申请条数
		$this->user_info = $user_info;
		$this->display();
	}

}
?>