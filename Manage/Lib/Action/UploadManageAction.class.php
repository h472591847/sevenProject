<?php 
/**
 * @todo            图片管理控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2015-04-15
 */
class UploadManageAction extends CommonAction{

    /**
     * @todo  Index       图片管理器列表视图
     * @param $dir_name   文件夹路径(相对)
     * @since 2015-04-15 
     */
    public function Index(){
        $dir_name = I('dir_name');        
        $init_url = DocumentRoot().'/Uploads/';         
        $dir = $init_url;
        $file_dir_name = $init_url;
        if(!empty($dir_name)){
            $dir = $init_url.$dir_name;
            $file_dir_name = $init_url.$dir_name;
            $parent_dir = I('dir_name');            
            $this->parent_dir = $parent_dir;
        }        
        $this->host_dir_url = 'http://'.$_SERVER['HTTP_HOST'].'/Uploads/';
        $dir_list = getDir($dir);
        $file_list = getFile($file_dir_name);         
        $this->file_list = $file_list;     
        $this->dir_list = $dir_list;
        $this->display();
    }


    /**
     * @todo  DeleteFile  删除图片
     * @param $file_name  图片路径(相对)
     * @since 2015-04-15 15:38:35
     */
    public function DeleteFile(){
        $init_url = DocumentRoot().'/Uploads/'; 
        $default_dir = '/Uploads/';
        $file_name = I('file_name');
        if(!empty($file_name)){
            unlink($init_url.$file_name);
            $msg_data['title'] = '删除图片成功';
            $msg_data['content'] = '【后台用户：'.session('username').'】通过图片管理器删除文件成功。图片路径：'.$default_dir.$file_name;
            $msg_data['user_id'] = session(C('USER_AUTH_KEY'));
            $msg_data['create_time'] = NOW_TIME;
            $msg_data['type'] = 2;//信息操作
            $msg_data['web_status'] = 2;//状态：后台操作
            $msg_data['sql'] = '';
            insert_msg_box($msg_data);               
            $this->success('删除成功');
        }else{
            $this->error('删除失败');
        }
    }
}
 ?>