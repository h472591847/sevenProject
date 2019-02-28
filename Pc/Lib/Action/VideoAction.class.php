<?php
/**
 * @todo            理财视频控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-31
 */
class VideoAction extends Action {
    
    public function _initialize(){
        $this->page_size = 100;
    }

    /**
     * @todo  index    文章列表视图
     * @param $cid      栏目ID
     * @return  array
     */
    public function index(){                                               
	     $this->display();    
    }


}