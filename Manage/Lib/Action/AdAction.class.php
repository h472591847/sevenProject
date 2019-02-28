<?php 
/**
 * @todo            其他信息(广告管理)控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-9-17
 */
    class AdAction extends UploadAction{ 

        public function _initialize(){
            parent::_initialize();
            $this->db = M('ad');
        }     


        /**
         * @todo  Index    管理页视图
         */
        public function Index(){
            $refer = $_SERVER['HTTP_REFERER'];//读取来路地址         
            import('ORG.Util.Page');
            $upid = I('upid',0,'intval');
            $k_class_name = iconv_to_utf8(I('k_class_name'));
            $display_txt = '添加';//操作文字            
            //向分页传递参数   
            $parameter = array('k_class_name'=>$k_class_name);               
            $class_list = $this->db->field("class_name")->group("class_name")->select();                      
            if($k_class_name !=''){
                $where['class_name'] = $k_class_name;                            
            }
            $count = $this->db->where($where)->count();            
            $page = new Page($count,25,$parameter);
            $limit = $page->firstRow . ',' . $page->listRows;                      
            $list = $this->db ->where($where)->order('ad_order ASC,id ASC')->limit($limit)->select();//读取全部信息                            
            //如果修改则读取相关信息
            if(!empty($upid)){
                $select['id'] = $upid;
                $display_txt = '修改';
                $this->edit_ad = $this->db->where($select)->find();                
            }
            $this->refer = $refer;//来路地址 传递到模版
            $this->upid = $upid;
            $this->display_txt = $display_txt;
            $this->class_list = $class_list;//类别列表
            $this->page = $page->show();//分页            
            $this->list = $list;                     
            $this->display();
        }

        /**
         * @todo  HandleAd  修改或添加处理
         */
        public function HandleAd(){
            $refer = I('refer'); //接收来路地址，用于修改后返回           
            $data = array();
            $data = $_POST;                        
            $upid = I('upid',0,'intval');
            if($data['class_fname'] != ''){
                $data['class_name'] = $data['class_fname'];
            }            
            //使用UploadAction中的传统图片上传方法                            
            $img_data = $this->UploadOne("/ad/");                                   
            $data['ad_image'] = $img_data['img_url'];             
            $data['create_time'] = NOW_TIME;          
            if(empty($upid)){                  
                $insert = $this->db->add($data);
                if($insert !== false){
                    //写入动作表
                    $remember_sql = $this->db->getLastSql();
                    
                    $view_find = $this->db->where("id = $insert")->find();                  
                    $msg_data['title'] = '添加其他信息成功';
                    $msg_data['content'] = '【后台用户：'.session('username').'】添加【其他信息:'.$view_find['class_name'].'->'.$view_find['ad_name'].'】成功。';
                    $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
                    $msg_data['create_time'] = NOW_TIME;
                    $msg_data['type'] = 2;//信息操作
                    $msg_data['web_status'] = 2;//状态：后台操作
                    $msg_data['sql'] = $remember_sql;
                    insert_msg_box($msg_data);                          
                    $this->redirect('Index');
                }else{
                    $this->error("添加失败");
                }
            }else{
                $where['id'] = $upid;
                $ad = $this->db->where($where)->find();
                //图片若有改动，则删除旧图片
                if(!empty($data['ad_image']) && !empty($ad['ad_image'])){
                    unlink(DocumentRoot().$ad['ad_image']);
                }
                //图片未修改则把老图片路径重新赋值
                if(empty($data['ad_image']))
                     $data['ad_image'] = $ad['ad_image'];
                if($this->db->where($where)->save($data) !== false){
                    //写入动作表
                    $remember_sql = $this->db->getLastSql();
                    
                    $view_find = $this->db->where("id = $upid")->find();                    
                    $msg_data['title'] = '修改其他信息成功';
                    $msg_data['content'] = '【后台用户：'.session('username').'】修改【其他信息：'.$view_find['class_name'].'->'.$view_find['ad_name'].'】成功。';
                    $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
                    $msg_data['create_time'] = NOW_TIME;
                    $msg_data['type'] = 2;//信息操作
                    $msg_data['web_status'] = 2;//状态：后台操作
                    $msg_data['sql'] = $remember_sql;
                    insert_msg_box($msg_data);                       
                    redirect($refer);
                }else{
                    $this->error('修改失败');
                }
            }
            unset($data);
        }

        /**
         * @todo   AdDelete   信息选中删除
         */
        public function AdDelete(){            
            $del_imgs = array();
            $del = $_POST['del'];
            foreach($del as $id){
                $ad = $this->db->field('ad_image')->where("id = $id")->find();
                //单图
                if(!empty($ad['ad_image'])){
                    $del_imgs[] = $ad['ad_image'];
                }
            }
            if($this->db->where('id in(' . implode(',',$del) . ')')->delete()){
                //删除图片
                foreach($del_imgs as $img){
                    unlink(DocumentRoot() . $img);
                }
                //写入动作表
                $remember_sql = $this->db->getLastSql();
                            
                $msg_data['title'] = '其他信息删除成功';
                $msg_data['content'] = '【后台用户：'.session('username').'】其他信息删除成功 ID:'.implode(',',$del);
                $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
                $msg_data['create_time'] = NOW_TIME;
                $msg_data['type'] = 2;//信息操作
                $msg_data['web_status'] = 2;//状态：后台操作
                $msg_data['sql'] = $remember_sql;
                insert_msg_box($msg_data);                              
                $this->redirect("Index");                
            }else{
                $this->error('信息删除失败!');
            }
        }

    }
 ?>