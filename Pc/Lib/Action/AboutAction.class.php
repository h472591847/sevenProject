<?php
/**
 * @todo            公司简介控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class AboutAction extends Action {

    /**
     * @todo  index   公司简介视图
     */
    public function index(){        
       $db = M('info');       
       $about = $db->where('id=26')->find();   
       $about['imgs_view'] = explode("\r\n", $about['imgs']);//企业资质组图传递到模版
       if(count(array_filter($about['imgs_view'])) == 0)
           $about['imgs_view'] = '';//如果没图片，赋空值
       $about['company_action_view'] = explode("\r\n", $about['company_action']);//公司实景传递到模版
       $gsly_list = M('ad')->field('ad_name,ad_image')->where("class_name='分公司掠影'")->order('ad_order desc,create_time desc,id desc')->select();                               
       if(count(array_filter($about['company_action_view'])) == 0)
           $about['company_action_view'] = '';//如果没图片，赋空值            
       // $about['team_gather_view'] = explode("\r\n", $about['team_gather']);//团队集锦传递到模版
       // if(count(array_filter($about['team_gather_view'])) == 0)
       //     $about['team_gather_view'] = '';//如果没图片，赋空值                    
       $this->about = $about;
	     $this->gsly_list = $gsly_list; 
     $this->display();
    }
}