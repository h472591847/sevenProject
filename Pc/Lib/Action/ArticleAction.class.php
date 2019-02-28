<?php
/**
 * @todo            文章类型控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-24
 */
class ArticleAction extends Action {

    /**
     * @todo    articleDetail 文章详细页
     * @param   $id           文章主键
     * @return  array
     */
    public function articleDetail(){
        $id = I('id',0,'intval');       
        if(empty($id)){
          $this->error('参数错误');
        }
        $article = M('info')->where('id='.$id)->find();        
        $cate = M('cate')->where('id='.$article['pid'])->find();        
        M('info')->where('id='.$id)->setInc('click');//点击数
        $this->before = M('info')->where('pid='.$article['pid'].' and id>'.$id)->order('sort asc,id asc')->find();//上一篇
        $this->after = M('info')->where('pid='.$article['pid'].' and id<'.$id)->order('sort desc,id desc')->find();//下一篇
        $this->article = $article;
        $this->cate = $cate;        
        $this->display("article_detail");
    }


}