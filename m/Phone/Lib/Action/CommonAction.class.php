<?php 
/**
 * @todo            公用模块、登录验证
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-10
 */
class CommonAction extends Action{
    public function _initialize(){ //TP初始化函数
        $id_key = session('user_id');              
        if(empty($id_key)){            
            $this->error('请先登录',U('user/Login'));
        }   
        //登录超时设置 (45分钟) 
        // if(NOW_TIME - session('logtime') >= 2700){
        //     session('user_id',null);
        //     session('user_name',null);
        //     session('logtime',null);      
        //     $this->redirect('user/Login');          
        // }              
    }        
}
 ?>