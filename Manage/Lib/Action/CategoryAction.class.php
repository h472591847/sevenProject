<?php
//后台分类管理控制器
class CategoryAction extends CommonAction {
	//添加/修改分类、分类列表视图
    public function Index(){
		import('Class.Category',"./");
		$upid = I('upid',0,'intval');
		$display_txt = '添加';//操作文字
		$db = M('cate');
		$cate = $db->order('sort ASC,id ASC')->select();//读取全部分类信息
		$cate = Category::UnlimitedForLevel($cate);//递归
		//如果修改则读取相关信息
		if(!empty($upid)){
			$select['id'] = $upid;
			$display_txt = '修改';
			$this->edit_cate=$db->where($select)->find();
		}
		$this->upid = $upid;
		$this->display_txt = $display_txt;
		$this->cate = $cate;
		$this->display();
    }
	//分类表单处理
	public function CateHandle(){
		$upid=I('upid',0,'intval');
		if($upid){//判断执行修改
			if(M('cate')->where("id = $upid")->setfield($_POST)){
	            //写入动作表
	            $remember_sql = M('cate')->getLastSql();
	            
	            $view_find = M('cate')->where("id = $upid")->find();     	            
	            $msg_data['title'] = '修改分类成功';
	            $msg_data['content'] = '【后台用户：'.session('username').'】修改【分类：'.$view_find['name'].'】成功。ID:'.$upid;
	            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
	            $msg_data['create_time'] = NOW_TIME;
	            $msg_data['type'] = 2;//信息操作
	            $msg_data['web_status'] = 2;//状态：后台操作
	            $msg_data['sql'] = $remember_sql;
	            insert_msg_box($msg_data);	 					
				$this->success('分类修改成功!',U('Category/Index'));
			}else{
				$this->error('分类修改失败!');
			}
		}else{//否则执行添加
			$insert = M('cate')->add($_POST);
			if($insert !== false){
            //写入动作表
            $remember_sql = M('cate')->getLastSql();
            
            $view_find = M('cate')->where("id = $insert")->find();     	            
            $msg_data['title'] = '添加分类成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】添加【分类：'.$view_find['name'].'】成功。ID:'.$insert;
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);	 									
			$this->success('分类添加成功!',U('Category/Index'));
			}else{
			$this->error('分类添加失败!');
			}
		}	
	}
	//分类更新排序
	public function CateSort(){
		$db = M('cate');
		foreach($_POST as $id => $sort){
			$db->where(array("id = $id"))->setfield('sort',$sort);
			}
		$this->redirect('Category/Index');
	}
	//分类选中删除
	public function CateDelete(){
		import('Class.Category','./');
		$cate = M('cate');
		$info = M('info');
		$del = I('del');
		$del_imgs=array();
		foreach($del as $id){
			$cate_arr = $cate->field('name,img')->where("id = $id")->find();
			//取得所有选中删除类别的图片地址
			if(!empty($cate_arr['img'])){
				$del_imgs[]=$cate_arr['img'];
			}
			//取得所有子类id
			$cate_childs = Category::GetChilds($cate->select(),$id);
			//判断如果有子类id则提示
			if(!empty($cate_childs)){
				$this->error('请确认已删除【' . $cate_arr['name'] . '】类别下相关类别!');
			//否则如果没有子类、则查询信息列表中是否有数据
			}else{
				$info_if = $info->where("pid = $id")->find();
				if(!empty($info_if)){
					$this->error('请确认已删除【' . $cate_arr['name'] . '】类别下相关信息!');
				}
			}
		}
		//删除数据库内类别信息
		$if=$cate->where("id in(" . implode(',',$del) . ")")->delete();
		if($if){
			//循环删除类别图片
			foreach($del_imgs as $img){
				unlink(DocumentRoot().__ROOT__.$img);
			}
            //写入动作表
            $remember_sql = $cate->getLastSql();
                        
            $msg_data['title'] = '分类删除成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】分类删除成功 ID:'.implode(',',$del);
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);	 			
			$this->success('分类删除成功!',U('Category/Index'));
			
		}else{
			$this->error('分类删除失败!');
		}
	}



	
}
?>