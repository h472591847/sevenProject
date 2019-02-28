<?php
/**
 * @todo            公司简介控制器
 * @copyright       m.p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-9-18
 */
class AboutAction extends Action {

    /**
     * @todo  index   公司简介视图
     */
    public function index(){        
       $db = M('info');       
       $this->about = $db->where('id=26')->find();                     
	   $this->display();
    }
}