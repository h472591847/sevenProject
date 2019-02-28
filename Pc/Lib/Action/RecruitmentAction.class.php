<?php
/**
 * @todo            招贤纳士控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class RecruitmentAction extends Action {
    
    public function _initialize(){
        $this->page_size = 15;
    }


    /**
     * @todo  index    招贤纳士列表视图
     * @param $cid      栏目ID
     * @return  array
     */
    public function index(){          
       $cid = I('cid');                   
       $field = 'id,pid,title,time,sort,content';     
       $count = M('info')->field($field)->where('pid='.$cid)->count();                      
       import('ORG.Util.Page'); //引入分页                       
       $page = new Page($count,$this->page_size);       
       $limit = $page->firstRow . ',' . $page->listRows;          
       $list = M('info')->field($field)->where('pid='.$cid)->order('sort desc,id desc')->limit($limit)->select();
       //echo M('info')->getlastsql();
       $this->list = $list;                       
       $this->cid = $cid; 
       $this->page = $page->show();        
       $this->cate = M('cate')->where('id='.$cid)->find();
	   $this->display();
    }

}