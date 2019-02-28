<?php

/**
 * @todo            前台首页控制器
 * @copyright       p2p222.com
 * @author          LiYing <472591847@qq.com>
 * @package         JinYongBoFa
 * @version         1.0
 * @since           2014-10-27
 */
class IndexAction extends Action {

    public function  test(){
      ini_set('memory_limit','3072M');    // 临时设置最大内存占用为3G
      set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期       
      $rootDir = $_SERVER['DOCUMENT_ROOT'].'/';
      $xlsPath = $rootDir.'today_orders_copy_1204181259.xls';
      $data = data_import($xlsPath);
      $i = 0;
      $result = array();   
      foreach ($data as $k => $row) {  
        if($i >0){
          $result[$row['A']][] = $row;
        }
        $i++;
      }  
      $msg = '生成图片文件成功:<br>';      
      $completeX = 0;
      foreach ($result as $k => $val) {
        $x = 0;        
        foreach($val as $childRow){
          $x++;
          $saveFilePath = $rootDir.'/export_img/'.$childRow['A'].'('.$x.')'.$childRow['F'].'('.$childRow['G'].').png';
          $fileRemoteUrl = $childRow['H'];
          if(is_file($saveFilePath)) continue;
          if($fileContents = file_get_contents($fileRemoteUrl)){            
            if(file_put_contents($saveFilePath,$fileContents)){
              $completeX ++;
              $msg .= "文件路径:".$saveFilePath.'<br>';              
            }
            
          }          
        }        
      } 
      if($completeX > 0){
        echo $msg.'<br>';        
        echo '总计重命名下载图片：'.$completeX.'个';
      }     
      // echo "<pre>".print_r($result,true); 
      die;    
         $number = (string)'6227000943040030672';
         $bank_number = '';
         for($i = 0;$i<=strlen($number);$i++){
            if($i % 4 == 0){
              if($i == 0){
                $bank_number .= substr($number,$i,4);
              }else{
                $bank_number .= ' '.substr($number,$i,4);
              }
            }
         }
         echo $bank_number;
         die;
         $str = 'rUiI6oqi0+4poNAsbKhl6Q%3D%3D';
         echo (int)getDecrypt(rawurldecode($str));die;
         $lids = array();
         //月月薪产品项目，且状态为还款中（即已开始计息）
         $yue_loan = M('loan')->where('class_id = 1 AND is_effect = 1 AND deal_status = 4')->select();
         p($yue_invested);die;
         foreach ($yue_loan as $key => $value) {
             $lids[] = $value['id'];
             $yue_loan[$key] = $value;
         }
         //关联取出月月薪产品项目的投资信息
        $yue_invested = M('invested')->where(array('lid'=>array('IN',$lids)))->select();        
        foreach ($yue_invested as $key => $value) {
            //根据每条投资信息的项目ID，投资金额，投资者ID 执行按月自动返息
           // repay_yueyuexin_accrual($value['lid'],$value['amount'],$value['uid']);
        }
        unset($lids);    
     }

    /**
     * Created by PhpStorm.
     * function: data_import
     * Description:导入数据
     * User: Xiaoxie
     * @param $filename
     * @param string $exts
     * @param $or
     *
     */
    public function data_import($filename, $exts = 'xls',$or)
    {        
        ini_set('memory_limit','1024M');
        set_time_limit(0);
        vendor ( 'Class.PHPExcel.PHPExcel','./');
        vendor ( 'Class.PHPExcel.PHPExcel.IOFactory','./');

        $objPHPExcel = new PHPExcel ();
        //创建PHPExcel对象，注意，不能少了\
        $PHPExcel = new \PHPExcel();
        //如果excel文件后缀名为.xls，导入这个类
        if ($exts == 'xls') {
            vendor('Class.PHPExcel.PHPExcel.Reader.Excel5');
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } else if ($exts == 'xlsx') {
            vendor('Class.PHPExcel.PHPExcel.Reader.Excel2007');
            $PHPReader = new \PHPExcel_Reader_Excel2007();
        } 
        //载入文件
        $PHPExcel = $PHPReader->load($filename);
        //获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        //获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        //获取总行数
        $allRow = $currentSheet->getHighestRow();
        //循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 1; $currentRow <= $allRow; $currentRow++) {
            //从哪列开始，A表示第一列
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn++) {
                //数据坐标
                $address = $currentColumn . $currentRow;
                //读取到的数据，保存到数组$data中
                $cell = $currentSheet->getCell($address)->getValue();
 
                if ($cell instanceof PHPExcel_RichText) {
                    $cell = $cell->__toString();
                }
                $data[$currentRow - 1][$currentColumn] = $cell;
                //  print_r($cell);
            }
 
        }
        return $data;

        
    }

     
    /**
     * @todo      index 首页视图
     */
    public function index(){           
       //公用AJAX返回服务器时间
       if(IS_AJAX){
         $this->ajaxReturn(NOW_TIME,'','1');exit();
       }

       $loan_db = M('loan');
       /*首页推荐投资*/       
       $loan_where['is_effect'] = 1;
       $loan_where['is_delete'] = 0;
       $rec_loan_where['recommend'] = 1;
       $rec_loan_where = array_merge($loan_where,$rec_loan_where);
       $rec_top = $loan_db->where($rec_loan_where)->order('loan_order DESC,create_time DESC,id DESC')->find();
       $invested_sum = M('invested')->field('sum(amount) as sum_amount')->where("lid=".$rec_top['id'])->find();
       $rec_top['load_rate'] = (string) sprintf("%.2f", substr(sprintf("%.3f", $invested_sum['sum_amount'] / $rec_top['loan_money'] * 100), 0, -1));       
       $end_date = $rec_top['start_time'] + $rec_top['end_time']*24*3600;
       $rec_top['retime'] = $end_date - NOW_TIME;//剩余时间 (秒)             
       $rec_top['now_time'] = NOW_TIME;//当前访问时间
       if($rec_top['deal_status'] == 1){
         //若项目没到发布日,将剩余发布时间传入模版
         if(time() < $rec_top['start_time']){
              $rec_top['re_start_time'] = $rec_top['start_time'] - time();
         }
       }

       $rec_top['end_date'] = $end_date;//结束日期时间戳         
       $this->rec_top = $rec_top;
       /*-----首页投资列表开始-----*/
       $loan_where['recommend'] = 0;//去除推荐的标
       $loan_list = $loan_db->where($loan_where)->order('loan_order DESC,create_time DESC,id DESC')->limit(4)->select();
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
              $value['count_amount'] += $v['amount'];
            }
         }        
         $loan_list[$key] = $value;
       }
       unset($lids);
       //最后数据计算
       foreach ($loan_list as $key => $value) {
            $value['load_rate'] = number_format($value['count_amount']/$value['loan_money']*100,2,".",'');
            $value['now_time'] = NOW_TIME;//当前访问时间
            $loan_list[$key] = $value;
       }    
       $this->loan_list = $loan_list;
       /*-----首页投资列表结束-----*/       
       //媒体新闻
       $this->mt_list = M('info')->where('pid=24')->order('sort desc,id desc')->limit(3)->select();       
       //理财产品
       $pro_class_db = M('product_class');
       $this->pro_class_list = $pro_class_db->order('class_order DESC,id asc')->select();
       //网站公告
       $this->gg_list = M('info')->where('pid=20')->order('sort desc,id desc')->limit(6)->select();       
       //推荐政策
       $this->zc_list = M('info')->where('pid=44')->order('sort desc,id desc')->limit(6)->select();       
       //行业新闻
       $this->hy_list = M('info')->where('pid=29')->order('sort desc,id desc')->limit(3)->select();
       //理财故事
       $this->gs_list = M('info')->where('pid=46')->order('sort desc,id desc')->limit(3)->select();              
       //投资技巧       
       $this->jq_list = M('info')->where('pid=23')->order('sort desc,id desc')->limit(6)->select();        
       //头部 幻灯片       
       $this->top_list = M('ad')->where(array('class_name'=>'首页头部幻灯'))->order('ad_order desc,id desc')->limit(6)->select();               
       //合作伙伴
       $this->hb_list = M('info')->where('pid=42')->order('sort desc,id desc')->limit(12)->select();
       //网站公告 幻灯片       
       $ad_list = M('ad')->where(array('class_name'=>'网站公告幻灯'))->order('ad_order desc,id desc')->limit(6)->select();               
       $i = 0;
       foreach ($ad_list as $key => $value) {
         $i++;
         $value['i'] = $i;
         $ad_list[$key] = $value;
       }
       $this->ad_list = $ad_list;
	   $this->display();
    }
}