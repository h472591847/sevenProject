<?php
/**
 * @todo            手机站首页控制器
 * @copyright       m.p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-9-18
 */
class IndexAction extends Action {

    public function index(){
      
       $loan_db = M('loan');
       /*首页推荐投资*/       

       $loan_where['is_effect'] = 1;
       $loan_where['is_delete'] = 0;
       $rec_loan_where['recommend'] = 1;
       $rec_loan_where = array_merge($loan_where,$rec_loan_where);
       $rec_top = $loan_db->where($rec_loan_where)->order('loan_order DESC,create_time DESC,id DESC')->find();
       $invested_sum = M('invested')->field('sum(amount) as sum_amount')->where("lid=".$rec_top['id'])->find();
       $rec_top['loan_rate'] = $rec_top['loan_rate'] + $rec_top['loan_add_rate'];       
       $rec_top['load_rate'] = (string) sprintf("%.2f", substr(sprintf("%.3f", $invested_sum['sum_amount'] / $rec_top['loan_money'] * 100), 0, -1));       
       $end_date = $rec_top['start_time'] + $rec_top['end_time']*24*3600;
       $retime = $end_date - NOW_TIME;//剩余时间 (秒)  
       $rec_top['now_time'] = NOW_TIME;
       $rec_top['end_date'] = $end_date;//结束日期时间戳                                 
       //若项目未开始,则算出距离开标剩余时间
       if($rec_top['start_time'] > NOW_TIME){
          $retime = $rec_top['start_time'] - NOW_TIME;
       }
       //++++++++2015-04-05 04:03:40 update_by:liying++++++++
       if($rec_top['deal_status'] == 1){
         //若项目没到发布日,将剩余发布时间传入模版
         if(time() < $rec_top['start_time']){
              $rec_top['re_start_time'] = $rec_top['start_time'] - time();
         }
       }         
       if($rec_top['re_start_time'] <= 0)
          $retime = 0;       
       //++++++++2015-04-05 04:03:40 update_by:liying++++++++
       $retime = ($retime<0)?0:$retime;//若已经过期，剩余时间则清零
       $rec_top['retime'] = ($rec_top['deal_status']==2)?0:$retime;//若满标，则剩余天数清零       
       $this->rec_top = $rec_top;
       /*-----首页投资列表开始-----*/
       $loan_where['recommend'] = array('neq','1');//列表不包含推荐标
       $loan_list = $loan_db->where($loan_where)->order('loan_order DESC,create_time DESC,id DESC')->limit(8)->select();
       $lids = array();
       foreach ($loan_list as $key => $value) {
          $lids[] = $value['id'];
       }
       $invested_where['lid'] = array('IN',$lids);        
       $invested_list = M('invested')->where($invested_where)->select();
       //取出每个项目投资总额
       foreach ($loan_list as $key => $value) {
         foreach ($invested_list as $k => $v) {
            if($value['id']==$v['lid']){
              $value['count_amount'] +=$v['amount'];
            }
         }        
         $loan_list[$key] = $value;
       }
       unset($lids);
       $i = 0;
       //最后数据计算
       foreach ($loan_list as $key => $value) {
            $i++;
            $value['i'] = $i;
            $value['load_rate'] = $value['count_amount']/$value['loan_money']*100;
            $value['load_rate'] = number_format($value['load_rate'],2,".",'');
            $value['loan_rate'] = $value['loan_rate'] + $value['loan_add_rate'];
            $value['now_time'] = NOW_TIME;
            //++++++++2015-04-05 04:03:40 update_by:liying++++++++
            if($value['deal_status'] == 1){
                //若项目没到发布日,将剩余发布时间传入模版
                if(time() < $value['start_time']){
                    $value['re_start_time'] = $value['start_time'] - time();
                }
            }            
            //++++++++2015-04-05 04:03:40 update_by:liying++++++++
            $list_end_date = $value['start_time'] + $value['end_time'] * 24 * 3600;
            $list_retime = $list_end_date - NOW_TIME;//剩余时间 (秒)  
            //若项目未开始,则算出距离开标剩余时间
            if($value['start_time'] > NOW_TIME){
              $list_retime = $value['start_time'] - NOW_TIME;
            }       
            //++++++++2015-04-05 04:03:40 update_by:liying++++++++
            if($value['re_start_time'] <=0)
              $list_retime = 0;
            //++++++++2015-04-05 04:03:40 update_by:liying++++++++            
            $list_retime = ($list_retime<0)?0:$list_retime;//若已经过期，剩余时间则清零
            $value['retime'] = ($value['deal_status']==2)?0:$list_retime;//若满标，则剩余天数清零                     
            $loan_list[$key] = $value;
       }    
       $this->loan_list = $loan_list;

	   $this->display();
    }

}

