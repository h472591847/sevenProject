<?php
/**
 * @todo            活动集锦控制器
 * @copyright       m.p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-9-18
 */
class ActivityAction extends Action {
    public function _initialize(){
        $this->page_size = 10;
    }

    /**
     * @todo  index    文章列表视图
     * @param $cid      栏目ID
     * @return  array
     */
    public function index(){ 

       $cid = I('cid',0,'intval');                   
       $field = 'id,pid,title,time,sort,img,url';     
       $list = M('info')->field($field)->where('pid='.$cid)->order('sort desc,id desc')->limit(0,$this->page_size)->select();       
       foreach ($list as $key => $value) {
           $value['title'] = msubstr($value['title'],0,13);//标题长度截取
           $list[$key] = $value;
       }            
       $this->list = $list;                       
       $this->cid = $cid;        
       $this->cate = M('cate')->where('id='.$cid)->find();
       $this->display();
    }


    /**
     * @todo  loadData   滚动加载数据    
     * @param $cid       栏目ID
     * @param $page      分页数
     * @return   
     *         json  data：数据结果
     *               info：提示信息
     *               status：返回状态
     */
    public function loadData(){
        $page = I('page',$this->page_size,'intval');
        $cid = I('post.cid',0,'intval');
        $begin = $page * $this->page_size;        
        $field = 'id,pid,title,time,sort,img';   
        //取出对应分页的数据从
        $data = M('info')->field($field)->where('pid='.$cid)->order('sort desc,id desc')->limit($begin,$this->page_size)->select();            
        foreach ($data as $key =>$value) {
                $value['title'] = msubstr($value['title'],0,13);//标题长度截取
                $value['Y']= date('Y',$value['time']);
                $value['m'] = date('m',$value['time']);
                $value['d'] = date('d',$value['time']);     
                $data[$key] = $value;                     
        }   
        if($data){
             $this->ajaxreturn($data,'',1);
        }else{
             $this->ajaxreturn('','没有了',0);
        }
    }
}