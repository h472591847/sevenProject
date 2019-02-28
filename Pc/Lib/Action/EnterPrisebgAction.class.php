<?php
/**
 * @todo            企业背景控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2015-1-23
 */
class EnterPrisebgAction extends Action {

    /**
     * @todo  index   企业背景视图
     */
    public function index(){        
       $db = M('info');       
       $about = $db->where('id=1176')->find();   
       if(!empty($about['imgs']))
          $about['imgs_view'] = explode("\r\n", $about['imgs']);//金湧博发资质       
       if(!empty($about['company_action']))
          $about['company_action_view'] = explode("\r\n", $about['company_action']);//分公司实景组图传递到模版               
       $this->about = $about;
	   $this->display();
    }
}