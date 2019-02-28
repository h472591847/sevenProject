<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title>投资列表_<?php echo (C("web_title")); ?></title>
<meta name="keywords" content="<?php echo (C("web_keywords")); ?>" />
<meta name="description" content="<?php echo (C("web_description")); ?>" />

</head>
<link href="__PUBLIC__/css/style.css?<?php echo rand(0,1000);?>" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/css/mmenu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="__PUBLIC__/js/jquery.mmenu.js"></script>
<!--circle-->
<script type="text/javascript" src="__PUBLIC__/js/raphael-min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/circle.js"></script>
<script type="text/javascript">
function day_cx(obj){    
    $(obj).siblings().removeClass("cx_on");
    $(obj).addClass("cx_on");    
    $("#day_cx").show();
    $("#month_cx").hide();
}

function month_cx(obj){
    $(obj).siblings().removeClass("cx_on");
    $(obj).addClass("cx_on");    
    $("#month_cx").show();
    $("#day_cx").hide();
}  
</script>
<body>
<div class="center">
   <!--wap统计代码开始-->
<?php require("/hm.php"); $_hmt = new _HMT("e29aec143162fc1ca52145771a9ee305"); $_hmtPixel = $_hmt->trackPageView(); ?>
<img src="<?php echo $_hmtPixel; ?>" style="display:none" width="0" height="0" />
<?php require_once '/cs.php';echo '<img src="'._cnzzTrackPageView(5958842).'" width="0" style="display:none" height="0"/>'; ?>
<!--wap统计代码结束-->
   <script type="text/javascript">
			$(function() {
				$('nav#menu').mmenu();
			});
   </script>
   <nav id="menu">
      <ul>
        <li>
            <span class="tx_left"><img src="__PUBLIC__/images/tx_left.png"></span>
            <span class="tx"><img src="__PUBLIC__/images/tx.png" width="70"></span>
            <span class="tx_right"><img src="__PUBLIC__/images/tx_right.png"></span>
            <div class="spacer"></div>
        </li>
        <li class="login_name" style=" margin-bottom:2px;">
           <!--如果登录显示-->
           <?php if($_SESSION['user_id']!= ''): ?><a class="login_user" href="<?php echo U('Member/index');?>">您好！<?php echo (session('user_name')); ?></a>
           <?php else: ?>
           <!--没登录显示-->
           <a href="<?php echo U('user/Login');?>" class="menu_dl" style=" line-height:33px; margin-left:60px;">登录</a>
           <a href="<?php echo U('user/Register');?>" class="menu_dl" style=" line-height:33px; margin-left:20px;">注册</a><?php endif; ?>
        </li>
        <li><a href="<?php echo U('Index/Index');?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>首页</a></li>
        <li><a href="<?php echo U('Deal/Index');?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>投资列表</a></li>
        <li><a href="<?php echo U('About/Index');?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>公司简介</a></li>
        <li><a href="<?php echo U('Article/Index',array('cid'=>20));?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>网站公告</a></li>
        <li><a href="<?php echo U('Article/Index',array('cid'=>23));?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>投资技巧</a></li>
        <li><a href="<?php echo U('Media/Index',array('cid'=>24));?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>媒体报道</a></li>
        <li><a href="<?php echo U('Article/Index',array('cid'=>42));?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>合作伙伴</a></li>
        <!-- <li><a href="<?php echo U('Activity/Index',array('cid'=>26));?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>活动集锦</a></li> -->
        <li><a href="<?php echo U('Activity/Index',array('cid'=>48));?>"><span class="nar"><img src="__PUBLIC__/images/menua.png"></span>最新活动</a></li>
      </ul>
   </nav>

   <div class="top">
     <a href="#menu" class="menu_button"></a>
     <span>投资列表</span>
     <span></span>     
     <!--登录后显示 start-->
          <!--登录后显示 start-->
     <?php if($_SESSION['user_id']!= ''): ?><span class="user_icon"><a href="<?php echo U('Member/index');?>" title=""><img src="__PUBLIC__/images/user_info.png" /></a></span>
     <?php else: ?>
     <span class="user_icon"><a href="<?php echo U('user/Login');?>" title=""><img src="__PUBLIC__/images/user_info.png" /></a></span><?php endif; ?>
     <!--end-->
     <!--end-->
   </div>  
   <div class="screening">
       <ul>
          <li>
             <p class="search_tit">项目状态：</p>
             <p class="search_class">
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>'','k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>$page_param['k_loan_rate']));?>'" <?php if($page_param['k_deal_status'] == ''): ?>class="cx_on"<?php endif; ?>>全部</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>1,'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>$page_param['k_loan_rate']));?>'" <?php if($page_param['k_deal_status'] == 1): ?>class="cx_on"<?php endif; ?>>投标中</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>2,'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>$page_param['k_loan_rate']));?>'" <?php if($page_param['k_deal_status'] == 2): ?>class="cx_on"<?php endif; ?>>投资满额</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>4,'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>$page_param['k_loan_rate']));?>'" <?php if($page_param['k_deal_status'] == 4): ?>class="cx_on"<?php endif; ?>>回款中</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>5,'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>$page_param['k_loan_rate']));?>'" <?php if($page_param['k_deal_status'] == 5): ?>class="cx_on"<?php endif; ?>>回款完毕</span>
             </p>
          </li>
          <li>
             <p class="search_tit">项目期限：</p>
             <p class="search_class">
                <span <?php if($page_param['k_loan_deadline_type'] == ''): ?>class="cx_on"<?php endif; ?> onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>'','k_loan_deadline'=>'','k_loan_rate'=>$page_param['k_loan_rate']));?>'">全部</span>
                <span <?php if($page_param['k_loan_deadline_type'] == '1'): ?>class="cx_on"<?php endif; ?> onclick="day_cx(this)">按天查询</span>
                <span <?php if($page_param['k_loan_deadline_type'] == '0'): ?>class="cx_on"<?php endif; ?> onclick="month_cx(this)">按月查询</span>
             </p>
          </li>
          <li id="month_cx" <?php if($page_param['k_loan_deadline_type'] != '0'): ?>style="display:none"<?php endif; ?>>
             <p class="search_tit">按月查询：</p>
             <p class="search_class">
                <span <?php if($page_param['k_loan_deadline_type'] == '0' && $page_param['k_loan_deadline'] == ''): ?>class="cx_on"<?php endif; ?> onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>0,'k_loan_deadline'=>'','k_loan_rate'=>$page_param['k_loan_rate']));?>'">全部</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>0,'k_loan_deadline'=>'0,1','k_loan_rate'=>$page_param['k_loan_rate']));?>'" <?php if($page_param['k_loan_deadline'] == '0,1' && $page_param['k_loan_deadline_type'] == '0'): ?>class="cx_on"<?php endif; ?>>0-1个月</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>0,'k_loan_deadline'=>'1,3','k_loan_rate'=>$page_param['k_loan_rate']));?>'" <?php if($page_param['k_loan_deadline'] == '1,3' && $page_param['k_loan_deadline_type'] == '0'): ?>class="cx_on"<?php endif; ?>>1-3个月</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>0,'k_loan_deadline'=>'3,6','k_loan_rate'=>$page_param['k_loan_rate']));?>'" <?php if($page_param['k_loan_deadline'] == '3,6' && $page_param['k_loan_deadline_type'] == '0'): ?>class="cx_on"<?php endif; ?>>3-6个月</span>
             </p>
          </li>
          <li id="day_cx" <?php if($page_param['k_loan_deadline_type'] != 1): ?>style="display:none"<?php endif; ?>>
             <p class="search_tit">按天查询：</p>
             <p class="search_class">
                <span <?php if($page_param['k_loan_deadline_type'] == 1 && $page_param['k_loan_deadline'] == ''): ?>class="cx_on"<?php endif; ?> onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>1,'k_loan_deadline'=>'','k_loan_rate'=>$page_param['k_loan_rate']));?>'">全部</span>
                <span></span>
                <span <?php if($page_param['k_loan_deadline'] == '0,7' && $page_param['k_loan_deadline_type'] == 1): ?>class="cx_on"<?php endif; ?> onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>1,'k_loan_deadline'=>'0,7','k_loan_rate'=>$page_param['k_loan_rate']));?>'">0-7天</span>
                <span <?php if($page_param['k_loan_deadline'] == '7,30' && $page_param['k_loan_deadline_type'] == 1): ?>class="cx_on"<?php endif; ?> onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>1,'k_loan_deadline'=>'7,30','k_loan_rate'=>$page_param['k_loan_rate']));?>'">7-30天</span>
                <span <?php if($page_param['k_loan_deadline'] == '30,90' && $page_param['k_loan_deadline_type'] == 1): ?>class="cx_on"<?php endif; ?> onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>1,'k_loan_deadline'=>'30,90','k_loan_rate'=>$page_param['k_loan_rate']));?>'">30-90天</span>
             </p>
          </li>
          <li>
             <p class="search_tit">项目收益：</p>
             <p class="search_class">
                <span <?php if($page_param['k_loan_rate'] == ''): ?>class="cx_on"<?php endif; ?> onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>''));?>'">全部</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>'elt10'));?>'" <?php if($page_param['k_loan_rate'] == 'elt10'): ?>class="cx_on"<?php endif; ?>>10%以下</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>'10,12'));?>'" <?php if($page_param['k_loan_rate'] == '10,12'): ?>class="cx_on"<?php endif; ?>>10%-12%</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>'12,14'));?>'" <?php if($page_param['k_loan_rate'] == '12,14'): ?>class="cx_on"<?php endif; ?>>12%-14%</span>
                <span onclick="location.href='<?php echo U('Deal/Index',array('k_deal_status'=>$page_param['k_deal_status'],'k_loan_deadline_type'=>$page_param['k_loan_deadline_type'],'k_loan_deadline'=>$page_param['k_loan_deadline'],'k_loan_rate'=>'gt14'));?>'" <?php if($page_param['k_loan_rate'] == 'gt14'): ?>class="cx_on"<?php endif; ?>>14%以上</span>
             </p>
          </li>
       </ul>
       <div class="spacer"></div>
   </div>  
<?php if(empty($loan_list)): ?><div class="project" style=" height:100px; line-height:50px;"><center>暂无数据！~</center></div>
<?php else: ?>   
<?php if(is_array($loan_list)): foreach($loan_list as $key=>$v): ?><div class="project">
        <h3>
           <span class="attr"><img src="__PUBLIC__/images/db.png"></span>
           <a href="<?php echo U('Deal/DealContent',array('lid'=>$v['id']));?>" title=""><?php echo (msubstr($v["loan_title"],0,12)); ?></a>
           <?php if($v['deal_status'] == 1): if($v['now_time'] < $v['start_time'] && $v['deal_status'] == 1): ?><span class="state sta_dkf">待开放</span>
             <?php else: ?>
             <span class="state" onclick="location.href='<?php echo U('Deal/DealContent',array('lid'=>$v['id']));?>'">投资</span><?php endif; endif; ?>
           <?php if($v['deal_status'] == 2): ?><span class="state sta_mb">满标</span><?php endif; ?>           
           <?php if($v['deal_status'] == 4): ?><span class="state state1">还款中</span><?php endif; ?>
           <?php if($v['deal_status'] == 5): ?><span class="state sta_dkf">已还清</span><?php endif; ?>           
        </h3>
        <div class="proje_left">
           <ul>
              <li>借款金额：<font class="amount"><?php echo ($v["loan_money"]); ?>元</font></li>
              <li>
                <span><font class="jk_num"><?php echo ($v["loan_rate"]); ?>%</font><br>年化利率</span>
                <span><font class="jk_num"><?php echo ($v["loan_deadline"]); ?></font><font class="jk_nor"><?php if($v['loan_deadline_type'] == 1): ?>天<?php else: ?>个月<?php endif; ?></font><br>项目期限</span>
              </li>
              <li>剩余时间：<?php echo (remain_time($v["retime"])); ?></li>
              <li>担保范围：<font><?php echo ($v["guarantee_purview"]); ?></font></li>
           </ul>
        </div>
        <div class="proje_right">
            <div class="percentBox">
                <div id="per_bg<?php echo ($v["i"]); ?>"></div> 
                <!-- 承载进度文字 --> 
                <div id="per_txt<?php echo ($v["i"]); ?>" class="pertxt"></div> 
            </div>
        </div>
        
    </div>
<script>
       var load_rate = <?php echo ($v["load_rate"]); ?>;
           load_rate = load_rate/100;
       init('per_bg<?php echo ($v["i"]); ?>',load_rate,'#per_txt<?php echo ($v["i"]); ?>','#d2a7ff'); 
</script><?php endforeach; endif; endif; ?>  
  <div class="page deal_page"><span style="text-align:center;display:block;width:100%;"><?php echo ($page); ?></span></div>
  <div class="bottom">
<?php $ar = explode('?',$_SERVER['REQUEST_URI']); if(count($ar)>0){ $get_param = $ar[1]; $get_param = "&".$get_param; } ?>
   <p><a href="<?php echo U('Index/Index');?>" title="金投资_首页">首页</a> <a href="<?php echo U('Deal/Index');?>" title="金投资_我要投资">我要投资</a> <a href="<?php echo U('About/Index');?>" title="金投资_公司简介">公司简介</a><a href="?is_change=1<?php echo ($get_param); ?>" class="bot_r">电脑版</a></p>
   <p>客服热线：400-6570-826</p>
   <p>客户交流群：63693972</p>
   <p>服务时间：周一至周五9：00-17：00</p>
</div>
</div>

</body>
</html>