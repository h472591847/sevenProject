<?php
/**
 * @todo            合作银行控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class BankAction extends Action {
    
    public function _initialize(){
        $this->page_size = 100;
    }

    /**
     * @todo  index    文章列表视图
     * @param $cid      栏目ID
     * @return  array
     */
    public function index(){                                 
       $class_name = (string)iconv_to_utf8(I('class_name'));
       $field = 'id,class_name,ad_name,ad_link,ad_image,ad_size,ad_order,create_time';     
       $count = M('ad')->field($field)->where("class_name='$class_name'")->count();                      
       import('ORG.Util.Page'); //引入分页                       
       $page = new Page($count,$this->page_size);
       $limit = $page->firstRow . ',' . $page->listRows;             
       $list = M('ad')->field($field)->where("class_name='$class_name'")->order('ad_order desc,id desc')->limit($limit)->select();             
       $this->list = $list;                         
       $this->class_name = $class_name;
       $this->page = $page->show();        
	     $this->display('index');    
    }


}