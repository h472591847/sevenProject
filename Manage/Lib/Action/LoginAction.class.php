<?php
//后台登录页控制器
class LoginAction extends Action {
	//登录页视图
    public function Index(){
		$this->display();
    }
	//登录表单验证
	public function Login(){
		if(!IS_POST){ halt('页面不存在');}
		$db = M('user');
		$user = $db->where(array('username'=>I('username')))->find();
		if(empty($user) || $user['password'] != I('password','','md5')){
			$this->error('账号或密码错误!');
		}
		$data=array(
			'id'=>$user['id'],
			'logintime'=>time(),
			'loginip'=>get_client_ip()
		);	
		$db->save($data);
		session(C('USER_AUTH_KEY'),$user['id']);
		session('username',$user['username']);
		session('logintime',date('Y-m-d H:i:s' , $user['logintime']));
		session('loginip',$user['loginip']);
		//超级管理员
		if($user['username'] == C('RBAC_SUPERADMIN')){
			session(C('ADMIN_AUTH_KEY'),1);
		}
		//读取用户权限
		import('ORG.Util.RBAC');
		RBAC::saveAccessList();
        //写入动作表
        $remember_sql = $db->getLastSql();        
        $view_find = $db->where("id = $insert")->find();                  
        $msg_data['title'] = '登录成功';
        $msg_data['content'] = '【后台用户：'.session('username').'】登录成功。';
        $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
        $msg_data['create_time'] = NOW_TIME;
        $msg_data['type'] = 2;//信息操作
        $msg_data['web_status'] = 2;//状态：后台操作
        $msg_data['sql'] = $remember_sql;
        insert_msg_box($msg_data);  			
		//p($_SESSION);die;
		$this->redirect('Index/Index');
    }
}
?>