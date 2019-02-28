<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>主页_<?php echo (C("web_title")); ?></title>
<meta name="keywords" content="<?php echo (C("web_keywords")); ?>" />
<meta name="description" content="<?php echo (C("web_description")); ?>" />

<link href="__PUBLIC__/css/style.css?<?php echo rand(0,1000);?>" rel="stylesheet" type="text/css">
<link href="__PUBLIC__/css/mmenu.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.10.2.min.js"></script>

<script type="text/javascript" src="__PUBLIC__/js/jquery.mmenu.js"></script>
<script type="text/javascript">
//stick the footer at the bottom of the page if we're on an iPad/iPhone due to viewport/page bugs in mobile webkit 
if(navigator.platform == 'iPad' || navigator.platform == 'iPhone' || navigator.platform == 'iPod') 
{ 
     $(".ac_float").css("position", "static"); 
};
</script>
</head>
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
     <span>金投资</span>
       <!--登录后显示 start-->
     <?php if($_SESSION['user_id']!= ''): ?><span class="user_icon"><a href="<?php echo U('Member/index');?>" title=""><img src="__PUBLIC__/images/user_info.png" /></a></span>
     <?php else: ?>
     <span class="user_icon"><a href="<?php echo U('user/Login');?>" title=""><img src="__PUBLIC__/images/user_info.png" /></a></span><?php endif; ?>
     <!--end-->
   </div>   
  <div id="wrap">
       <div id="slideshow" class="slideshow swiper-container" style=" max-height: 310px; cursor: -webkit-grab;">
            <ul class="items swiper-wrapper">
                <li class="swiper-slide"><a href="http://m.p2p222.com/index.php/Article/articleDetail.html?id=1793" title=""><img src="__PUBLIC__/images/phone4.jpg" alt=""></a></li>
                <li class="swiper-slide"><a href="<?php echo U('Lottery/feed_back');?>" title=""><img src="__PUBLIC__/images/img2.jpg" alt=""></a></li>
                <li class="swiper-slide"><a href="javascript:;" title=""><img src="__PUBLIC__/images/img3.jpg" alt=""></a></li>
            </ul>
            <div class="number" id="slidePage">
                <span class="swiper-pagination-switch"></span>
                <span class="swiper-pagination-switch"></span>
                <span class="swiper-pagination-switch"></span>
            </div>
       </div>
  </div>
    <script src="__PUBLIC__/js/global-1.1.0.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/touch.swiper.min.js" type="text/javascript"></script> 
  <!--circle-->
  <script type="text/javascript" src="__PUBLIC__/js/raphael-min.js"></script>
  <script type="text/javascript" src="__PUBLIC__/js/circle.js"></script>    
	<script type="text/javascript">
    $(function(){
        showSlide();
		var $pro_li = $(".project-menu ul li");
		$pro_li.click(function(){
		    $(this).addClass("pro-hover").siblings().removeClass("pro-hover");
			var index = $pro_li.index(this);
			$(".product-list > .pro-list").eq(index).show().siblings().hide();
		});
    });
    function showSlide(){
        var swiper = $('.swiper-container');
        var maxH = 310, maxW = 600, minH = 130; 
        var swImg = swiper.find('img');
        if(Util.isMobile.any()){
            var win = $(window).width();
            if(win >= 320 && win < 640){
                var resetH = (maxH/maxW)*(win-20);
                swiper.css({'height': resetH})
                swImg.css({'max-height': resetH});
            } else if(win >= 640){
                swiper.css({'height': maxH})
                swImg.css({'max-height': maxH});
            } else{
                swiper.css({'height': minH})
                swImg.css({'max-height': minH});
            };
        } else {
            swiper.css({'height': maxH})
            swImg.css({'max-height': maxH});
        };
        var newSwiper = new Swiper('.swiper-container', {
            pagination: '#slidePage',
            loop: true,
            grabCursor: true,
            autoplay: 5000,
            paginationClickable: true
        });
        swiper.find('.swiper-wrapper').show();	
    }
    </script>
    
    
    <!--<div class="project-menu">
       <ul>
          <li class="first-m pro-hover">投资项目</li>
          <li class="second-m">理财产品</li>
       </ul>
    </div>-->
    <div class="product-list">
        <div class="pro-list">
            <!--project start-->
            <div class="project">
                <h3>
                   <span class="attr"><img src="__PUBLIC__/images/db.png"></span>
                   <a href="<?php echo U('Deal/DealContent',array('lid'=>$rec_top['id']));?>" title=""><?php echo (msubstr($rec_top["loan_title"],0,11)); ?></a>
                   <?php if($rec_top['deal_status'] == 1): if($rec_top['now_time'] < $rec_top['start_time'] && $rec_top['deal_status'] == 1): ?><span class="state sta_dkf" style="cursor:pointer">待开放</span>
                   <?php else: ?>
                   <span class="state" style="cursor:pointer" onclick="location.href='<?php echo U("Deal/ToInvestView",array('id'=>$rec_top['id']));?>'">投资</span><?php endif; endif; ?>
                   <?php if($rec_top['deal_status'] == 2): ?><span class="state sta_mb">满标</span><?php endif; ?>           
                   <?php if($rec_top['deal_status'] == 4): ?><span class="state state1">还款中</span><?php endif; ?>
                   <?php if($rec_top['deal_status'] == 5): ?><span class="state sta_dkf">已还清</span><?php endif; ?>           
                </h3>
                <div class="proje_left">
                   <ul>
                      <li>借款金额：<font class="amount">￥<?php echo ($rec_top["loan_money"]); ?>元</font></li>
                      <li>
                        <span><font class="jk_num"><?php echo ($rec_top["loan_rate"]); ?></font><font class="jk_nor">%</font><br>年化利率</span>
                        <span><font class="jk_num">                
                        <?php echo ($rec_top["loan_deadline"]); ?></font><font class="jk_nor"><?php if($rec_top['loan_deadline_type'] == 1): ?>天<?php else: ?>个月<?php endif; ?></font><br>项目期限</span>
                      </li>
                      <li>剩余时间：<?php echo (remain_time($rec_top["retime"])); ?></li>
                      <li>担保范围：<font><?php echo ($rec_top["guarantee_purview"]); ?></font></li>
                   </ul>
                </div>
                <div class="proje_right">
                    
                    <div class="percentBox">
                        <div id="per_bg_rec_top"></div> 
                        <!-- 承载进度文字 --> 
                        <div id="per_txt_rec_top" class="pertxt"></div> 
                    </div>
                    
                </div>
                
            </div>
            <script>
               var rec_top_load_rate = <?php echo ($rec_top["load_rate"]); ?>;
                   rec_top_load_rate = rec_top_load_rate/100;
               init('per_bg_rec_top',rec_top_load_rate,'#per_txt_rec_top','#d2a7ff'); 
            </script>
            <!--project end-->
            <?php if(is_array($loan_list)): foreach($loan_list as $key=>$v): ?><div class="project">
                <h3>
                   <span class="attr"><img src="__PUBLIC__/images/db.png"></span>
                   <a href="<?php echo U('Deal/DealContent',array('lid'=>$v['id']));?>" title=""><?php echo (msubstr($v["loan_title"],0,11)); ?></a>
                   <?php if($v['deal_status'] == 1): if($v['now_time'] < $v['start_time'] && $v['deal_status'] == 1): ?><span class="state sta_dkf" style="cursor:pointer">待开放</span>
                   <?php else: ?>
                   <span class="state" style="cursor:pointer" onclick="location.href='<?php echo U("Deal/ToInvestView",array('id'=>$loan['id']));?>'">投资</span><?php endif; endif; ?>
                   <?php if($v['deal_status'] == 2): ?><span class="state sta_mb">满标</span><?php endif; ?>
                   <?php if($v['deal_status'] == 4): ?><span class="state state1">还款中</span><?php endif; ?>
                   <?php if($v['deal_status'] == 5): ?><span class="state sta_dkf">已还清</span><?php endif; ?>           
                </h3>
                <div class="proje_left">
                   <ul>
                      <li>借款金额：<font class="amount"><?php echo ($v["loan_money"]); ?>元</font></li>
                      <li>
                        <span><font class="jk_num"><?php echo ($v["loan_rate"]); ?>%</font><br>年化利率</span>
                        <span><font class="jk_num"><?php echo ($v["loan_deadline"]); ?></font>
                        <font class="jk_nor"><?php if($v['loan_deadline_type'] == 1): ?>天<?php else: ?>个月<?php endif; ?></font><br>项目期限</span>
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
            </script><?php endforeach; endif; ?> 
           <div class="bottom">
<?php $ar = explode('?',$_SERVER['REQUEST_URI']); if(count($ar)>0){ $get_param = $ar[1]; $get_param = "&".$get_param; } ?>
   <p><a href="<?php echo U('Index/Index');?>" title="金投资_首页">首页</a> <a href="<?php echo U('Deal/Index');?>" title="金投资_我要投资">我要投资</a> <a href="<?php echo U('About/Index');?>" title="金投资_公司简介">公司简介</a><a href="?is_change=1<?php echo ($get_param); ?>" class="bot_r">电脑版</a></p>
   <p>客服热线：400-6570-826</p>
   <p>客户交流群：63693972</p>
   <p>服务时间：周一至周五9：00-17：00</p>
</div>
        </div>
        <div class="pro-list hide">
           <ul class="cp-list">
              <li>
                 <h3>月月薪<span>每月稳定收益</span></h3>
                 <div>
                    <p>
                       <span class="cp-rate"><font>9.00</font>%</span>
                       <span>年化收益</span>
                    </p>
                    <p class="cp-second">
                       <span class="cp-month"><font>6</font>个月</span>
                       <span>投资期限</span>
                    </p>
                    <p class="cp-last">
                       <a href="#">马上投资</a>
                    </p>
                 </div>
                 <div class="spacer"></div>
              </li>
              <li>
                 <h3>单季盈<span>灵活</span><span>期限短</span></h3>
                 <div>
                    <p>
                       <span class="cp-rate"><font>9.00</font>%</span>
                       <span>年化收益</span>
                    </p>
                    <p class="cp-second">
                       <span class="cp-month"><font>6</font>个月</span>
                       <span>投资期限</span>
                    </p>
                    <p class="cp-last">
                       <a href="#">马上投资</a>
                    </p>
                 </div>
                 <div class="spacer"></div>
              </li>
              <li>
                 <h3>双季丰<span>收益稳定</span><span>期限较短</span></h3>
                 <div>
                    <p>
                       <span class="cp-rate"><font>9.00</font>%</span>
                       <span>年化收益</span>
                    </p>
                    <p class="cp-second">
                       <span class="cp-month"><font>6</font>个月</span>
                       <span>投资期限</span>
                    </p>
                    <p class="cp-last">
                       <a href="#">马上投资</a>
                    </p>
                 </div>
                 <div class="spacer"></div>
              </li>
              <li>
                 <h3>年年红<span>循环出借</span><span>收益最大化</span></h3>
                 <div>
                    <p>
                       <span class="cp-rate"><font>9.00</font>%</span>
                       <span>年化收益</span>
                    </p>
                    <p class="cp-second">
                       <span class="cp-month"><font>6</font>个月</span>
                       <span>投资期限</span>
                    </p>
                    <p class="cp-last">
                       <a href="#">马上投资</a>
                    </p>
                 </div>
                 <div class="spacer"></div>
              </li>
           </ul>
        </div>
    </div>
   
   
</div>
<div class="ac_float"><a href="<?php echo U('Activity/Index',array('cid'=>48));?>"><img src="__PUBLIC__/images/ac_float.png"></a></div>



</body>
</html>