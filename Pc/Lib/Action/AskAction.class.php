<?php
/**
 * @todo            投资技巧控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class AskAction extends Action {
    
    public function _initialize(){
        $this->page_size = 10;
    }


    /**
     * @todo  index    投资技巧列表视图
     * @param $cid      栏目ID
     * @return  array
     */
    public function index(){          
       $cid = '30';                            
       $list_where['pid'] = array('between',array('31','33'));      
       $limit = 5;
       $hot_list = M('info')->where($list_where)->order('click desc,id desc')->limit($limit)->select();              
       $child_cate_1 =  M('cate')->where('id=31')->find();       
       $child_list_1 = M('info')->where('pid='.$child_cate_1['id'])->order('sort desc,id desc')->limit($limit)->select();
       $child_cate_2 =  M('cate')->where('id=32')->find();
       $child_list_2 = M('info')->where('pid='.$child_cate_2['id'])->order('sort desc,id desc')->limit($limit)->select();       
       $child_cate_3 =  M('cate')->where('id=33')->find();
       $child_list_3 = M('info')->where('pid='.$child_cate_3['id'])->order('sort desc,id desc')->limit($limit)->select();              
       $this->hot_list = $hot_list;                       
       $this->child_cate_1 = $child_cate_1;                       
       $this->child_list_1 = $child_list_1;                       
       $this->child_cate_2 = $child_cate_2;                       
       $this->child_list_2 = $child_list_2;                       
       $this->child_cate_3 = $child_cate_3; 
       $this->child_list_3 = $child_list_3;                              
       $this->cid = $cid;        
       $this->cate = M('cate')->where('id='.$cid)->find();
	     $this->display();
    }


    /**
     * @todo   ask_part 问答子列表
     */
    public function ask_part(){
       $cid = I('cid');                   
       $field = 'id,pid,title,time,sort,description';     
       $count = M('info')->field($field)->where('pid='.$cid)->count();                      
       import('ORG.Util.Page'); //引入分页                       
       $page = new Page($count,$this->page_size);       
       $limit = $page->firstRow . ',' . $page->listRows;          
       $list = M('info')->field($field)->where('pid='.$cid)->order('sort desc,id desc')->limit($limit)->select();
       $this->list = $list;                       
       $this->cid = $cid; 
       $this->page = $page->show();        
       $this->cate = M('cate')->where('id='.$cid)->find();
       $this->display();
    }



    /**
     * @todo   ask_search   常见问题搜索结果列表
     * @param  $kwz         搜索关键词
     */
    public function ask_search(){       
       $kwz = iconv_to_utf8(I('kwz'));
       $where['title|description'] = array('LIKE','%'.$kwz.'%');
       $where['pid'] = array('IN','31,32,33');//平台介绍，充值提现，怎么理财 在这三个类别中查找
       $field = 'id,pid,title,time,sort,description';     
       $count = M('info')->field($field)->where($where)->count();                             
       import('ORG.Util.Page'); //引入分页                       
       $page = new Page($count,$this->page_size);       
       $limit = $page->firstRow . ',' . $page->listRows;          
       $list = M('info')->field($field)->where($where)->order('sort desc,id desc')->limit($limit)->select();
       foreach ($list as $key => $value) {
        //替换关键字，并且把关键字高亮显示        
        $value['kwz_title'] = str_replace($kwz, "<span style=color:#000>".$kwz."</span>", $value['title']);
        $value['kwz_description'] = str_replace($kwz, "<span style=color:#000>".$kwz."</span>", $value['description']);        
        $list[$key] = $value;
       }
       $this->list = $list;  
       $this->kwz = $kwz;                     
       $this->page = $page->show();        
       $this->display();
    }
}