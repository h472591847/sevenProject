<?php
/**
 * @todo            合作伙伴控制器
 * @copyright       m.p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-9-18
 */
class PartnerAction extends Action {


    /**
     * @todo  index    文章列表视图
     * @return  array
     */
    public function index(){

       $list = M('ad')->where("class_name='合作伙伴'")->select();
       $this->main_title = '合作伙伴';
       $this->list = $list;
	   $this->display();
    }


}