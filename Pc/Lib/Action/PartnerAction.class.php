<?php
/**
 * @todo            合作伙伴控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class PartnerAction extends Action {


    /**
     * @todo  index   合作伙伴视图
     */
    public function index(){        
       $cid = 42;  
       $page_size = 8;                 
       $field = 'id,pid,title,time,sort,description,img';     
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
     * @todo  ParternerDetail   合作伙伴详细页
     */
    public function PartnerDetail(){
        $id = (int)I('id');
        if($id == 2021){
          redirect(U('Index/Index'));die;
        }        
        $info = M('info')->where('id='.$id)->find();        
        $cate = M('cate')->where('id='.$info['pid'])->find();
        if(!empty($info['imgs'])){          
          $imgs_ar = explode("\r\n", $info['imgs']);//返回实地考察图片组  
          /*筛选数组只留两组数组元素*/          
          $info['imgs'] = retain_array($imgs_ar,2);
        }
        $list = M('info')->field($field)->where('pid=42')->order('sort desc,id desc')->limit(12)->select();
        $this->list = $list;            
        $this->cate = $cate;
        $this->info = $info;
        $this->display();
    }



    /**
     * @todo   Join    申请加盟视图
     */
    public function Join(){
      session('book_action','join');//防捆绑软件恶意提交   
      $cid = 42;
      $limit = 12;
      $this->list = M('info')->where('pid='.$cid)->order('sort desc,id desc')->limit($limit)->select();      
      $this->display('join');      
    }


    /**
     * @todo    DoJoin   申请加盟处理
     */
    public function DoJoin(){
        $data = $_POST;
        $data['create_time'] = NOW_TIME;
        if(IS_POST){
            if(session('book_action')!='join' && session("book_action") != 'loan_apply'){
                $this->error('非法操作');
            }
            if(empty($data['real_name']))
              $this->error('请输入您的真实姓名');
            if(empty($data['city']))
              $this->error('请输入您的所在城市');
            if(empty($data['company_name']))
              $this->error('请输入您的公司名称');
            if(!preg_match("/^(1[3|4|5|7|8]\d{9})$/", $data['phone']))
              $this->error('手机号码不正确请重新输入');                                        
            $insert = M('join')->add($data);
            if($insert !== false){
                session('book_action','');//防捆绑软件恶意提交
                $this->success('申请加盟提交成功，资料审核后，我们的客服会与您取得联系。');
            }else{
                $this->error('数据错误');
            }
        }else{
            $this->error('请勿非法提交');
        }
    }
}