<?php
/**
 * @todo            操作管理列表
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-30
 */
class MsgBoxAction extends CommonAction {


    //操作列表视图
    public function Index(){                
        import('ORG.Util.Page');                                
        $db = M('msg_box');  
        $web_status = trim(I('web_status'));
        $user_id = trim(I('user_id'));
        $k_start_time = trim(I('k_start_time'));
        $k_end_time = trim(I('k_end_time'));         
        $kwz = iconv_to_utf8(trim(I('kwz')));
        $content = iconv_to_utf8(trim(I('content')));   
        //向分页传递参数   
        $parameter = array(            
            'user_id'=>$user_id,                      
            'k_start_time'=>$k_start_time,
            'k_end_time'=>$k_end_time,
            'kwz'=>$kwz,
            'content'=>$content,
            'web_status'=>$web_status, 
        );          
        $k_start_time = strtotime(I('k_start_time'));
        $k_end_time = strtotime(I('k_end_time'));         
        $where['web_status'] = $web_status;        
        //按用户ID查找
        if(!empty($user_id)){
            $where['user_id'] = $user_id;
        }    
        if($web_status == 2){
            $this->kwz_list = $db->field('title')->where('web_status='.$web_status)->group('title')->order('id asc')->select();            
            $user_list = $db->field('user_id')->where('web_status='.$web_status)->group('user_id')->order('id asc')->select();            
            $u_res = M('user')->order('id asc')->select();
            foreach ($user_list as $key => $value) {
                foreach ($u_res as $k => $v) {
                    if($value['user_id'] == $v['id']){
                        $value['username'] = $v['username'];
                    }                
                }
                $user_list[$key] = $value;                
            }
            $this->user_list = $user_list;            
        } 
        //按操作名称查找
        if(!empty($kwz))
            $where['title'] = array('LIKE','%'.$kwz.'%');
        //按操作内容查找
        if(!empty($content))
            $where['content'] = array('LIKE','%'.$content.'%');        
        //按操作时间区间查找
        if(!empty($k_start_time))
            $where['create_time'] = array('EGT',$k_start_time);
        if(!empty($k_end_time))
            $where['create_time'] = array('ELT',$k_end_time);       
        if(!empty($k_start_time) && !empty($k_end_time))
            $where['create_time'] = array(array('EGT',$k_start_time),array('ELT',$k_end_time),'AND');                           
        //分页
        $count = $db->where($where)->count();            
        $page = new Page($count,25,$parameter);
        $limit = $page->firstRow . ',' . $page->listRows;
        //结束
        $info = $db->where($where)->order('create_time DESC,id ASC')->limit($limit)->select();     
        $uids = array();
        foreach ($info as $key => $value) {
            $uids[] = $value['user_id'];
        }               
        $user_where['id'] = array('IN',$uids);
        switch (I('web_status')) {
            case 1:
            $user_info = M('web_user')->where($user_where)->select();            
            foreach ($info as $key => $value) {
                foreach ($user_info as $k => $v) {
                    if($v['id']==$value['user_id']){
                        $value['user_name'] = $v['user_name'];
                    }
                }
              $info[$key] = $value;
            }
                break;            
            case 2:
            $user_info = M('user')->where($user_where)->select();
            foreach ($info as $key => $value) {
                foreach ($user_info as $k => $v) {
                    if($v['id']==$value['user_id']){
                        $value['user_name'] = $v['username'];
                    }
                }
              $info[$key] = $value;
            }
                break;
        }
        unset($uids);
        $this->page_param = $parameter;
        $this->web_status = I('web_status');
        $this->page = $page->show();//分页
        $this->info = $info;//内容
        $this->display();
    }



    /**
     * @todo  InfoDelete         删除操作信息
     * @param $del      array    要删除的主键ID
     */
    public function InfoDelete(){
        $db = M('msg_box');
        $web_status = I('web_status');               
        $del = $_POST['del'];
        if($db->where('id in(' . implode(',',$del) . ')')->delete()){
            $this->success('信息删除成功!',U('MsgBox/Index',array('web_status'=>$web_status)));
        }else{
            $this->error('信息删除失败!');
        }
    }
}
?>