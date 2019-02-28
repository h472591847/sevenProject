<?php
/**
 * @todo            安全保障控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class SecurityAction extends Action {

    /**
     * @todo  index   安全保障视图
     */
    public function index(){        
       // $db = M('info');       
       // $this->about = $db->where('id=26')->find();   
       $about['title'] = '安全保障';
       $this->about = $about;                  
	   $this->display();
    }
}