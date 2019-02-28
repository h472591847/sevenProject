<?php
//后台信息管理控制器
class InfoAction extends CommonAction {
	//信息列表视图
    public function Index(){
		import('ORG.Util.Page');
		import('Class.Category','./');
		$pid = I('pid',0,'intval');
		$ss = I('ss','请输入查询标题');
		$db = M('info');
		$ss = ($ss == '请输入查询标题') ? '' : iconv_to_utf8($ss);			
        //向分页传递参数   
        $parameter = array(
            'pid'=>$pid,
            'ss'=>$ss 
        );  
		//读取全部分类信息
		$cate = M('cate')->order('sort ASC,id ASC')->select();//读取全部分类信息
		$cate = Category::UnlimitedForLevel($cate);//递归、组合一维数组
		$field = 'id,title,pid,click,time,sort,img,imgs,url,tel';
		if(!empty($pid)){//读取查询分类信息
			$pids = Category::GetChilds($cate,$pid);//传递父级分类id获取全部子分类;
			$pids[] = $pid;
			$where = 'pid in(' . implode(',',$pids) . ')';
			($ss != '请输入查询标题') ? $where .= " and title like '%$ss%'" : $where .= ' and 1=1';//搜索条件
		}else{//读取全部信息
			($ss != '请输入查询标题') ? $where = "title like '%$ss%'" : $where = "1=1";//搜索条件
		}
		//分页
		$count = $db->where($where)->count();		
		$page = new Page($count,25,$parameter);
		$limit = $page->firstRow . ',' . $page->listRows;
		//结束
		$info = $db->field($field)->where($where)->order('time Desc,id desc')->limit($limit)->select();
		foreach($info as $key => $value){
			$info[$key]['cate'] = Category::GetParents($cate,$value['pid']);//递归、传递子分类id获取全部父级分类
		}		
		$this->ss = $ss;//搜索
		$this->page = $page->show();//分页
		$this->info = $info;//内容
		$this->pid = $pid;//查询类id
		$this->cate = $cate;//显示查询类
		$this->display();
    }
	//添加/修改信息视图
	public function Info(){
		$refer = $_SERVER['HTTP_REFERER'];//读取来路地址			
		import('Class.Category',"./");
		$upid = I('upid',0,'intval');
		//显示操作文字
		if(!empty($upid)){
			$display_txt = '修改';
			$edit_info = M('info')->where("id = $upid")->find();
			$edit_info['imgs_view'] = explode("\r\n", $edit_info['imgs']);//组图传递到模版
			if(count(array_filter($edit_info['imgs_view'])) == 0)
			  $edit_info['imgs_view'] = '';//如果没图片，赋空值
			$edit_info['company_action_view'] = explode("\r\n", $edit_info['company_action']);//公司实景传递到模版
			if(count(array_filter($edit_info['company_action_view'])) == 0)
			  $edit_info['company_action_view'] = '';//如果没图片，赋空值			
			$edit_info['team_gather_view'] = explode("\r\n", $edit_info['team_gather']);//团队集锦传递到模版
			if(count(array_filter($edit_info['team_gather_view'])) == 0)
			  $edit_info['team_gather_view'] = '';//如果没图片，赋空值			
			$this->edit_info = $edit_info;
		}else{
			$display_txt = '添加';
		}
		
		$cate = M('cate')->order('sort ASC,id ASC')->select();//读取全部分类信息
		$cate = Category::UnlimitedForLevel($cate);//递归
		$this->upid = $upid;
		$this->refer = urlencode($refer);//来路地址传递到模版		
		$this->display_txt = $display_txt;
		$this->cate = $cate;
		$this->display();
    }
	//信息表单处理
	public function InfoHandle(){
		$refer = urldecode(I('refer'));//接收来路地址，用于修改后跳转返回
		$db = M('info');
		$date = $_POST;
		$date['time'] = strtotime($date['time']);
		$upid = I('upid',0,'intval');		
		if(empty($upid)){
			$insert = $db->add($date);
			if($insert !== false){
	            //写入动作表
	            $remember_sql = $db->getLastSql();
	            $msg_data['title'] = '添加信息成功';
	            $msg_data['content'] = '【后台用户：'.session('username').'】添加信息成功。标题:【'.$date['title'].'】ID:'.$insert;
	            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
	            $msg_data['create_time'] = NOW_TIME;
	            $msg_data['type'] = 2;//信息操作
	            $msg_data['web_status'] = 2;//状态：后台操作
	            $msg_data['sql'] = $remember_sql;
	            insert_msg_box($msg_data);	            	            
				$this->success('信息添加成功!',U('Info/Index'));
			}else{
				$this->error('信息添加失败!');
			}
		}else{
			//若单图有改动，则判断删除旧图片文件
            $view_find = $db->where("id = $upid")->find(); 			
			if(!empty($date['img']) && $date['img'] != $view_find['img']){
				unlink(DocumentRoot().$view_find['img']);
			}
			//若有改动，则删除旧的图片组文件			 
            $old_imgs = explode("\r\n", $view_find['imgs']);//旧图片组               
            $new_imgs = explode("\r\n", $date['imgs']);//新图片组               
            $diff_imgs = array_diff($old_imgs, $new_imgs);//被删掉的旧图片                    
            if(count($diff_imgs) > 0){			 	
			 	//删除旧图片
				foreach ($diff_imgs as $value) {
			 		unlink(DocumentRoot().$value);			 		
				}
			}	

			//若有改动，则删除旧的图片组文件			 
            $old_company = explode("\r\n", $view_find['company_action']);//旧图片组               
            $new_company = explode("\r\n", $date['company_action']);//新图片组               
            $diff_company = array_diff($old_company, $new_company);//被删掉的旧图片                    
            if(count($diff_company) > 0){			 	
			 	//删除旧图片
				foreach ($diff_company as $value) {
			 		unlink(DocumentRoot().$value);			 		
				}
			}				

			//若有改动，则删除旧的图片组文件			 
            $old_gather = explode("\r\n", $view_find['team_gather']);//旧图片组               
            $new_gather = explode("\r\n", $date['team_gather']);//新图片组               
            $diff_gather = array_diff($old_gather, $new_gather);//被删掉的旧图片                                                  	
            if(count($diff_gather) > 0){			 	
			 	//删除旧图片
				foreach ($diff_gather as $value) {
			 		unlink(DocumentRoot().$value);			 		
				}
			}
			
			if($db->where("id = $upid")->save($date)!==false){
	            //写入动作表
	            $remember_sql = $db->getLastSql();
	            
	            $msg_data['title'] = '修改信息成功';
	            $msg_data['content'] = '【后台用户：'.session('username').'】修改信息成功。标题:【'.$date['title'].'】ID:'.$upid;
	            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
	            $msg_data['create_time'] = NOW_TIME;
	            $msg_data['type'] = 2;//信息操作
	            $msg_data['web_status'] = 2;//状态：后台操作
	            $msg_data['sql'] = $remember_sql;
	            insert_msg_box($msg_data);	    
				$this->success('信息修改成功!',$refer);
			}else{
				$this->error('信息修改失败!');
			}
		}
    }
	//信息更新排序
	public function InfoSort(){
		$db = M('info');
		foreach($_POST as $id => $value){
			$db->where("id = $id")->setfield('sort',$value);
		}
		$this->redirect('Info/Index');
    }
	//信息选中删除
	public function InfoDelete(){
		$db = M('info');
		$del_imgs = array();
		$del = $_POST['del'];
		foreach($del as $id){
			$info = $db->field('img,imgs,content')->where("id = $id")->find();
			//单图
			if(!empty($info['img'])){
				$del_imgs[] = $info['img'];
			}
			//图片组
			if(!empty($info['imgs'])){
				$img_arr=explode("\r\n",$info['imgs']);
				foreach($img_arr as $img){
						$del_imgs[] = $img;
				}
			}
			//公司实景
			if(!empty($info['company_action'])){
				$img_company_action_arr=explode("\r\n",$info['company_action']);
				foreach($img_company_action_arr as $img){
						$del_imgs[] = $img;
				}
			}
			//团队集锦
			if(!empty($info['team_gather'])){
				$img_team_gather_arr=explode("\r\n",$info['team_gather']);
				foreach($img_team_gather_arr as $img){
						$del_imgs[] = $img;
				}
			}						
			//内容图
			if(strstr($info['content'],'<img')){
				$get_img_str=explode('<img',stripslashes($info['content']));
				foreach($get_img_str as $get_img){
					$i++;
					if($i > 1){
						$tag = ModStr(' ','>',$get_img);
						$img = ModStr('src="','"',$tag);
						if(!strstr($img,"http://")){
							$del_imgs[] = $img;
						}
					}
				}
			}
		}
		if($db->where('id in(' . implode(',',$del) . ')')->delete()){
			//删除图片
			foreach($del_imgs as $img){
				unlink(DocumentRoot() . $img);
			}
            //写入动作表
            $remember_sql = $db->getLastSql();
            
            $msg_data['title'] = '删除信息成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】删除信息成功。ID:'.implode(',',$del);
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = $remember_sql;
            insert_msg_box($msg_data);	 			
			$this->success('信息删除成功!',U('Info/Index'));
		}else{
			$this->error('信息删除失败!');
		}
    }
       	
}
?>