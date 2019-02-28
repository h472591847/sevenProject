<?php 
/**
 * @todo            计算器控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2015-3-1
 */
class CalculatorAction extends Action{

    /**
     * @todo  EarningsCalc  收益计算器
     */
    public function EarningsCalc(){
        if(IS_AJAX){
           $money = I('post.money');//投资金额
           $loan_deadline = I('post.loan_deadline');//投资期限
           $loan_deadline_type = I('post.loan_deadline_type');//投资期限类型
           $loan_rate = I('post.loan_rate');//年化利率           
           if(empty($money) || empty($loan_deadline) || empty($loan_rate)){
              $this->ajaxReturn('','请填写完整的计算条件',0);
           }
           $loan_rate = $loan_rate / 100;//转换成小数
           if($loan_deadline_type == 1){
              $data['count_accrual'] = $money * $loan_rate / 365 * $loan_deadline;//所得总利息              
           }else{
              $data['count_accrual'] = $money * $loan_rate / 12 * $loan_deadline;//所得总利息
           }
           //截取利息后两位小数(不四舍五入)
           $data['count_accrual'] = sprintf("%.2f", substr(sprintf("%.3f",$data['count_accrual']), 0, -1)); 
           $data['count_amount'] = $money + $data['count_accrual'];//本息合计
           $this->ajaxReturn($data,'',1);exit();
        }
        $this->display('earnings_calc');
    }


    /**
     * @todo  CPICalc  CPI跟踪器
     */
    public function CPICalc(){
        $this->display('cpi_calc');
    }


    /**
     * @todo  IdentityCalc  身份计算器
     */
    public function IdentityCalc(){
        $this->display('identity_calc');
    }


    /**
     * @todo  EarningsCompare  收益对比器
     */
    public function EarningsCompare(){
        $this->display('earnings_compare');
    }            
}
 ?>