<?php
//后台信息管理控制器
class RbacAction extends CommonAction {
    /**
     * @todo  _initialize    初始化默认参数
     */
    function _initialize(){
        parent::_initialize();
    }			
	//管理员添加、列表视图
	public function Index(){		
		import('Class.Category','./');
		$upid = I('upid',0,'intval');
		$role = M('role')->field('id,name,remark')->order('id')->select();
		$user = D('User')->field('password',true)->relation(true)->order('id')->select();
		if(!empty($upid)){
			$display_txt='修改';
			$role_user = M('role_user')->where("user_id = $upid")->getField('role_id',true);
			//权限判断
			$arr = array();
			foreach($role as $v){
				if(is_array($role_user)){
					$v['access'] = in_array($v['id'],$role_user) ? 1 : 0 ;
					$arr[] = $v ;
				}
			}
			//权限判断结束
			$this->edit_user = M('user')->where("id = $upid")->find();
		}else{
			$display_txt='添加';
		}
		$this->upid = $upid;
		$this->role = empty($arr) ? $role : $arr;
		$this->user = $user;
		$this->display_txt = $display_txt;
		$this->display();
	}
	//角色添加、列表视图
	public function Role(){
		//查询全部角色
		$db = M('role');
		$role = $db->order('id ASC')->select();
		$upid = I('upid',0,'intval');
		if(!empty($upid)){
			$display_txt = '修改';
			$this->edit_role = $db->where("id = $upid")->find();
		}else{
			$display_txt = '添加';
		}
		$this->upid = $upid;
		$this->role = $role;
		$this->display_txt = $display_txt;
		$this->display();
	}
	//节点添加、列表视图
	public function Node(){
		import('Class.Category','./');
		$db = M('node');
		//查询全部节点
		$node = $db->order('sort ASC,id ASC')->select();
		$pid = I('pid',0,'intval');
		$level = I('level',1,'intval');
		$upid = I('upid',0,'intval');
		$rid = I('rid',0,'intval');
		if(!empty($upid)){
			$display_txt = '修改';
			$this->edit_node = $db->where("id = $upid")->find();
		}else{
			$display_txt = '添加';
		}
		if($level==1){
			$display_type = '应用';
		}elseif($level==2){
			$display_type = '控制器';
		}elseif($level==3){
			$display_type = '动作方法';
		}
		//如果配置权限则读取权限
		if(!empty($rid)){
			$access = M('access')->where("role_id = $rid")->getField('node_id',true);
			$node = Category::UnlimitedForLayer($node,$access);//组合多维数组
		}else{
			$node = Category::UnlimitedForLayer($node);//组合多维数组
		}
		$this->pid = $pid;
		$this->rid = $rid;
		$this->level = $level;
		$this->upid = $upid;
		$this->node = $node;
		$this->display_type = $display_type;
		$this->display_txt = $display_txt;
		$this->display();
	}
	//用户表单处理
	public function UserHandle(){
		$upid = I('upid');
		$data = array(
			'username' => I('username'),
			'lock' => I('lock',0,'intval')
		);
		$password = I('password');
		//判断是否修改密码
		if(!empty($password)){
			$data['password'] = md5($password);
		}
		if(empty($upid)){
			//添加
			$user_id = M('user')->add($data);
			if(!empty($user_id)){
				//角色关联
				$role = array();
				foreach($_POST['role'] as $v){
					$role[] = array(
						'role_id' => $v,
						'user_id' => $user_id
					);
				}
				M('role_user')->addAll($role);
				$this->success('添加用户成功!',U('Rbac/Index'));
			}else{
				$this->error('添加用户失败!');
			}
		}else{
			//修改信息
			$return_1 = M('user')->where("id = $upid")->save($data);
			//清除角色关联
			M('role_user')->where("user_id = $upid")->delete();
			//重新获取角色关联
			$role = array();
			foreach($_POST['role'] as $v){
				$role[] = array(
					'role_id' => $v,
					'user_id' => $upid
				);
			}
			$return_2 = M('role_user')->addAll($role);
			if(!empty($return_1) || !empty($return_2)){
				$this->success('修改信息完成!',U('Rbac/Index'));
			}else{
				$this->error('修改信息失败!');
			}
		}
		
	}
    //角色表单处理
	public function RoleHandle(){
		$upid = I('upid',0,'intval');
		$db = M('role');
		if(!empty($upid)){
			if($db->where("id = $upid")->save($_POST)){
				$this->success('角色修改成功!',U('Rbac/Role'));
			}else{
				$this->error('角色修改失败!');
			}
		}else{
			if($db->add($_POST)){
				$this->success('角色添加成功!',U('Rbac/Role'));
			}else{
				$this->error('角色添加失败!');
			}
		}
	}  	
	//节点表单处理
	public function NodeHandle(){
		$upid = I('upid',0,'intval');
		$db = M('node');
		if(!empty($upid)){
			if($db->where("id = $upid")->save($_POST)){
				$this->success('节点修改成功!',U('Rbac/Node'));
			}else{
				$this->error('节点修改失败!');
			}
		}else{
			if($db->add($_POST)){
				$this->success('节点添加成功!',U('Rbac/Node'));
			}else{
				$this->error('节点添加失败!');
			}
		}
	}
	//删除节点
	public function NodeDelete(){
		$delete = I('check',0);
		//判断是否为复选删除
		if(is_array($delete)){
			$data = array();
			foreach($delete as $del){
				$id = explode('_',$del);
				$data[] = $id[0] ;
			}
		}else{
			$id = explode('_',$delete);
			$data = array($id[0]);
		}
		//执行删除
		$return = M('node')->where("id in(" . implode(',',$data) . ")")->delete();
		if($return){
			//删除关联权限
			M('access')->where("node_id in(" . implode(',',$data) . ")")->delete();
			$this->success('节点删除成功!',U('Rbac/Node'));
		}else{
			$this->error('节点删除失败!');
		}
	}
	//删除角色
	public function RoleDelete(){
		$delete = I('del',0);
		//执行删除
		$return = M('role')->where("id in(" . implode(',',$delete) . ")")->delete();
		if($return){
			//删除关联权限
			M('access')->where("role_id in(" . implode(',',$delete) . ")")->delete();
			$this->success('角色删除成功!',U('Rbac/Role'));
		}else{
			$this->error('角色删除失败!');
		}
	}
	//删除用户
	public function UserDelete(){
		$delete = I('del',0);
		//执行删除
		$return = M('user')->where("id in(" . implode(',',$delete) . ")")->delete();
		if($return){
			//删除关联角色
			M('role_user')->where("user_id in(" . implode(',',$delete) . ")")->delete();
			$this->success('用户删除成功!',U('Rbac/Index'));
		}else{
			$this->error('用户删除失败!');
		}
	}
	//设置权限
	public function SetAccess(){
		$rid = I('rid',0);
		$check = I('check',0);
		$db = M('access');
		$db->where("role_id = $rid")->delete();
		$data = array();
		foreach($check as $v){
			$tmp = explode('_',$v);
			$data[] = array(
				'role_id' => $rid,
				'node_id' => $tmp[0],
				'level' => $tmp[1],
			);
		}
		$return = $db->addAll($data);
		if($return){
			$this->success('权限修改完成!',U('Rbac/Role'));
		}else{
			$this->error('权限修改失败!');
		}
	}


	/**
	 * @todo  PasswordUpdate   修改密码页面视图
	 */
	public function PasswordUpdate(){
		$this->display();
	}


	/**
	 * @todo SavePasswordUpdate  保存密码修改
	 */
	public function SavePasswordUpdate(){
		$password = I("post.password");
		$uid = session(C("USER_AUTH_KEY"));//当前用户ID
		$data['password'] = md5($password);
		$obj = D("user");
		$update = $obj->where('id='.$uid)->save($data);
		if($update!==false){
			$this->success("密码修改成功");
		}else{
			$this->error("密码修改失败");
		}
	}
}
?>