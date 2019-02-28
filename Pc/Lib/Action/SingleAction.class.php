<?php
/**
 * @todo            公司简介控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class SingleAction extends Action {

    /**
     * @todo  index   公司简介视图
     */
    public function index(){                
       $db = M('info');       
       $cid = I('cid',0,'intval');       
       $about = $db->where("id=".$cid)->find();                                   
       $cate = M('cate')->where('id='.$about['pid'])->find();       
       $this->cate = $cate;
       $this->about = $about;
	   $this->display();
    }
}