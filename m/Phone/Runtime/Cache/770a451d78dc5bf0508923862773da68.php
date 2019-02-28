<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<title><?php echo ($loan["loan_title"]); ?>_<?php echo (C("web_title")); ?></title>
<meta name="keywords" content="<?php echo (C("web_keywords")); ?>" />
<meta name="description" content="<?php echo (C("web_description")); ?>" />
</head>
<link href="__PUBLIC__/css/style.css?<?php echo rand(0,1000);?>" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/css/mmenu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="__PUBLIC__/js/jquery.mmenu.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/countUp.js"></script>
<script type="text/javascript">
$(document).ready(function() {
     
    var $div_li = $("div.js_menu ul li");
	$div_li.click(function(){
        $(this).addClass("js_sel").siblings().removeClass("js_sel");
		var index = $div_li.index(this);//index() 方法返回指定元素相对于其他指定元素的 index 位置
		$("div.js_box > div").eq(index).show().siblings().hide();
	});
	
	$(".tz_jl li:odd").css("background","#f2f2f2");
});

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
     <span>投资详情</span>
     <span></span>     
     <!--登录后显示 start-->
         <!--登录后显示 start-->
     <?php if($_SESSION['user_id']!= ''): ?><span class="user_icon"><a href="<?php echo U('Member/index');?>" title=""><img src="__PUBLIC__/images/user_info.png" /></a></span>
     <?php else: ?>
     <span class="user_icon"><a href="<?php echo U('user/Login');?>" title=""><img src="__PUBLIC__/images/user_info.png" /></a></span><?php endif; ?>
     <!--end-->
     <!--end-->
   </div>  
   <div class="investment">
      <h3>
           <!--<span class="at"><img src="__PUBLIC__/images/db.png"></span>-->
           <span><?php echo ($loan["loan_title"]); ?></span>
      </h3>
      <div class="invest_xx">
        <ul>
           <li><span>借款金额</span><span><font color="#b00b00" size="+1"><?php echo (format_price($loan["loan_money"])); ?></font></span><div class="spacer"></div></li>
           <li><span>借款年利率</span><span class="jk_num"><?php echo ($loan["loan_rate"]); ?>%</span><div class="spacer"></div></li>
           <li><span>投资进度</span><span><font class="jumbo" id="myTargetElement"><?php echo ($loan["load"]); ?></font>%</span><div class="spacer"></div></li>
           <li><span>担保范围</span><span><?php echo ($loan["guarantee_purview"]); ?></span><div class="spacer"></div></li>
           <li><span>剩余时间</span><span><?php if($loan['re_start_time'] > 0): echo (remain_time($loan["re_start_time"])); else: echo ($loan["retime"]); endif; ?></span><div class="spacer"></div></li>
        </ul>
      </div>
      <div class="invest_xx"> 
         <ul>
            <li><span>借款用途</span><span class="xx_green"><?php echo ($loan["loan_purpose"]); ?></span><div class="spacer"></div></li>
            <li><span>抵押物</span><span class="xx_green"><?php echo ($loan["pawn"]); ?></span><div class="spacer"></div></li>
            <li><span>产值</span><span class="xx_green"><?php echo ($loan["production_value"]); ?></span><div class="spacer"></div></li>
            <li><span>还款来源</span><span class="xx_green"><?php echo ($loan["payment_source"]); ?></span><div class="spacer"></div></li>
            <li><span>还款能力</span><span class="xx_green"><?php echo ($loan["repayment_ability"]); ?></span><div class="spacer"></div></li>
         </ul>
      <?php if($loan['is_effect'] == 1){ ?>  
        <?php if($loan['deal_status'] == 1): if($loan['now_time'] < $loan['start_time'] && $loan['deal_status'] == 1): ?><a href="javascript:;" class="tz_sub tz_dkf">待开放</a> 
        <?php else: ?>
        <a href="<?php echo U("Deal/ToInvestView",array('id'=>$loan['id']));?>" class="tz_sub">投资</a><?php endif; endif; ?>
        <?php if($loan['deal_status'] == 2): ?><a href="javascript:;" class="tz_sub tz_mb">满标</a><?php endif; ?>        
        <?php if($loan['deal_status'] == 4): ?><a href="javascript:;" class="tz_sub tz_hk">还款中</a><?php endif; ?>
        <?php if($loan['deal_status'] == 5): ?><a href="javascript:;" class="tz_sub tz_dkf">已还清</a><?php endif; ?> 
      <?php } ?>
      </div>
      
      <div class="invest_js">
         <div class="js_menu">
            <ul>
                <li class="js_one js_sel">项目介绍</li>
                <li>企业信息</li>
                <li class="js_three">担保信息</li>
            </ul>
        </div>
        <div class="js_box"> 
             <div>
                <?php if(empty($loan["loan_description"])): else: ?>                 
                <p><?php echo ($loan["loan_description"]); ?></p><?php endif; ?>
                <ul>
                    <?php if(empty($loan["pawn_info"])): else: ?>                 
                    <li>
                       <span class="js_a">抵押物价值</span>
                       <span class="js_b"><?php echo ($loan["pawn_info"]); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?> 
                    <?php if(empty($loan["loan_purpose"])): else: ?>                                        
                    <li>
                       <span class="js_a">借款用途</span>
                       <span class="js_b"><?php echo ($loan["loan_purpose"]); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?> 
                    <?php if(empty($loan["payment_source"])): else: ?>                       
                    <li>
                       <span class="js_a">还款来源</span>
                       <span class="js_b"><?php echo ($loan["payment_source"]); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?>                     
                </ul>
             </div>
             <div class="hide">
                <p>企业信息</p>
                <ul>
                    <?php if(empty($loan["business_scope"])): else: ?>                       
                    <li>
                       <span class="js_a">经营范围</span>
                       <span class="js_b"><?php echo ($loan["business_scope"]); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?> 
                    <?php if(empty($loan["business_state"])): else: ?>                      
                    <li>
                       <span class="js_a">经营状况</span>
                       <span class="js_b"><?php echo (msubstr($loan["business_state"],0,150)); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?>                     
                </ul>
             </div>
             <div class="hide">
                <p>担保信息</p>
                <ul>
                    <?php if(empty($loan["guarantee_institution"])): else: ?>                 
                    <li>
                       <span class="js_a">担保机构</span>
                       <span class="js_b"><?php echo ($loan["guarantee_institution"]); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?>  
                    <?php if(empty($loan["guarantee_content"])): else: ?>                                            
                    <li>
                       <span class="js_a">机构简介</span>
                       <span class="js_b"><?php echo (msubstr($loan["guarantee_content"],0,150)); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?>   
                    <?php if(empty($loan["pawn_info"])): else: ?>                                        
                    <li>
                       <span class="js_a">抵押物信息</span>
                       <span class="js_b"><?php echo ($loan["pawn_info"]); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?>    
                    <?php if(empty($loan["risk_control"])): else: ?>                                         
                    <li>
                       <span class="js_a">风险控制措施</span>
                       <span class="js_b"><?php echo (msubstr($loan["risk_control"],0,150)); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?>   
                    <?php if(empty($loan["guarantee_idea"])): else: ?>                                         
                    <li>
                       <span class="js_a">担保机构意见</span>
                       <span class="js_b"><?php echo ($loan["guarantee_idea"]); ?></span>
                       <div class="spacer"></div>
                    </li><?php endif; ?>                      
                </ul>
             </div>
        </div>
         
         
         <div class="spcaer"></div>
      </div>
      <?php if($lid >= 21 && $loan['re_start_time'] == 0): ?><iframe frameborder="0" width="100%" height="700" src="<?php echo U('Deal/tz_record',array('lid'=>$lid));?>" scrolling="no"></iframe><?php endif; ?>
      <div class="spacer"></div>
   </div>
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
<script type="text/javascript">


    // create instance
    var demoAnim = new countUp("myTargetElement", <?php echo ($loan["load"]); ?>, 2, 2.5);

    window.onload = function() {
        
        // fire animation
        demoAnim.start();
        
    }

    // for demo:
    function createCountUp() {

        var endVal = document.getElementById("num").value;
        endVal = Number(endVal.replace(',','').replace(' ',''));
        var decimals = document.getElementById("decimals").value;
        var duration = document.getElementById("duration").value;

        demoAnim = new countUp("myTargetElement", endVal, decimals, duration);
        demoAnim.start();
    }

</script>