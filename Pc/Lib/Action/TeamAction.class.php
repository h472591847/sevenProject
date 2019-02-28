<?php
/**
 * @todo            团队介绍控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class TeamAction extends Action {

    /**
     * @todo  index   团队介绍视图
     */
    public function index(){        
       // $db = M('info');       
       // $this->about = $db->where('id=26')->find();                     
       $about['title'] = '团队介绍';
       $this->about = $about;
	   $this->display();
    }
}