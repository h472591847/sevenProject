<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo (C("web_title")); ?></title>
<meta name="keywords" content="<?php echo (C("web_keywords")); ?>" />
<meta name="description" content="<?php echo (C("web_description")); ?>" />
<script type='text/javascript'>
window._CWiQ = window._CWiQ || []; 
window.BX_CLIENT_ID = 35795; // 帐号ID 
(function() {
 var c = document.createElement('script') ,
       p = 'https:'==document.location.protocol; 
       c.type = 'text/javascript'; 
       c.async = true; 
       c.src = (p?'https://':'http://')+'whisky.ana.biddingx.com/boot/0'; 
 var h = document.getElementsByTagName('script')[0]; 
       h.parentNode.insertBefore(c, h); 
})(); 
_CWiQ.push(['_trackPdmp', '访问过首页', 1]); // 访客分类名称是给访客贴的标签，可以自定义，例如： 访问过官网、注册用户等。

</script>

<!--[if IE 6]>
	<script type="text/javascript" src="__PUBLIC__/js/DD_belatedPNG.js" ></script>
	<script type="text/javascript">
	DD_belatedPNG.fix('*');
	</script>
<![endif]-->
<!-- Tag Manager -->
<script type="text/javascript">
(function(a, b, c, d, e, f) {
a[d] = a[d] || [];
var g = a[e] = a[e] || {};
a.tagmangerGlobalObject = e, g[f] = {dlName: d};
var h = b.getElementsByTagName(c)[0], i = b.createElement(c);
i.async = !0, i.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://static') + '.tagmanager.cn/boot/' + f + '.js', h.parentNode.insertBefore(i, h)
})(window, document, 'script', 'TGDataLayer', 'tagmanager', 10031);
</script>
<!-- End Tag Manager -->

<link href="__PUBLIC__/style/base.css" type="text/css" rel="stylesheet" />
<link href="__PUBLIC__/style/index.css" type="text/css" rel="stylesheet" />
<link href="__PUBLIC__/style/animate.css" type="text/css" rel="stylesheet" />
<!--大图轮播-->
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/prefixfree.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.flexslider-min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.flexslider').flexslider({
		directionNav: true,
		pauseOnAction: false
	});

	 function a(x,y){
		l = $('.in_center').offset().left;
		w = $('.in_center').width();
		$('.zbs').css('left',(l + w + x) + 'px');
		$('.zbs').css('bottom',y + 'px');
	 }
	 a(70,200);
	
});   
</script>
<!---->
<script type="text/javascript" src="__PUBLIC__/js/news_scroll.js"></script>
<script type="text/javascript">
$(document).ready(function(){ 
<?php if($rec_top['deal_status'] == 1): ?>//倒计时开始
$.ajax({
    url: '<?php echo U("Index/index");?>',
    type: 'GET',
    dataType: 'json',
    async : false,
    data: "get_ajax_time=1",               
    success:function(msg){
        if(msg.status == '1'){
            now_gtime = msg.data * 1000;
        }
        return false;
    }
})
var interval = 1000;
window.setInterval(function(){
  var re_start_time = '<?php echo ($rec_top["re_start_time"]); ?>';
  var retime = <?php echo ($rec_top["retime"]); ?>; 
  now_gtime = now_gtime + interval ;   
  if(retime >= 0 && re_start_time == ''){
  
    var retY = <?php echo (date('Y',$rec_top["end_date"])); ?>;
    var retM = <?php echo (date('m',$rec_top["end_date"])); ?>;
    var retD = <?php echo (date('d',$rec_top["end_date"])); ?>;     
    var retH = <?php echo (date('H',$rec_top["end_date"])); ?>;
    var retI = <?php echo (date('i',$rec_top["end_date"])); ?>;
    var retS = <?php echo (date('s',$rec_top["end_date"])); ?>;   
    ShowCountDown(now_gtime,retY,retM,retD,retH,retI,retS,'divdown1','本期募集结束'); 
  }else{
    
    var retY = <?php echo (date('Y',$rec_top["start_time"])); ?>;
    var retM = <?php echo (date('m',$rec_top["start_time"])); ?>;
    var retD = <?php echo (date('d',$rec_top["start_time"])); ?>;     
    var retH = <?php echo (date('H',$rec_top["start_time"])); ?>;
    var retI = <?php echo (date('i',$rec_top["start_time"])); ?>;
    var retS = <?php echo (date('s',$rec_top["start_time"])); ?>;   
    ShowCountDown(now_gtime,retY,retM,retD,retH,retI,retS,'divdown1','起投时间'); 
  }
}, interval); 
function ShowCountDown(now_time,year,month,day,hour,minute,second,divname,typename){var now_time=new Date(now_time);var endDate=new Date(year,month-1,day,hour,minute,second);var leftTime=endDate.getTime()-now_time.getTime();var leftsecond=parseInt(leftTime/1000);var day1=Math.floor(leftsecond/(60*60*24));var hour=Math.floor((leftsecond-day1*24*60*60)/3600);var minute=Math.floor((leftsecond-day1*24*60*60-hour*3600)/60);var second=Math.floor(leftsecond-day1*24*60*60-hour*3600-minute*60);var cc=document.getElementById(divname);if(day1==0&&hour==0&&minute==0&&second==0){location.reload()}cc.innerHTML="距离"+typename+":"+"<span class='recom_date'>"+day1+"</span>"+"天"+"<span class='recom_date'>"+hour+"</span>"+"小时"+"<span class='recom_date'>"+minute+"</span>"+"分"+"<span class='recom_date'>"+second+"</span>"+"秒"}
//倒计时结束<?php endif; ?>
//进度条
	// var percent = $('.pro_bar').attr('title');
	// $(".pro_bar").animate({width:percent},1000);
	// $(".tip").tipsy({gravity:'s',fade:true});
	//投资项目进度条
	<?php if(is_array($loan_list)): foreach($loan_list as $key=>$v): $i++; ?>
			var p_bar = $(".pro_percent #bard<?php echo ($i); ?>").attr("data-load<?php echo ($i); ?>");
			$(".pro_percent #bard<?php echo ($i); ?>").css("width",p_bar);<?php endforeach; endif; ?>
	
});
//进度条结束
</script>
</head>
<body>
<?php echo (ac($a)); ?>
<div id="browser">您使用的浏览器版本过于陈旧，将会影响体验效果，建议升级！！</div>
<script type="text/javascript">
   if($.browser.msie && $.browser.version <= 7.0){
      $("#browser").show();
   } 
</script>
<div class="out_top">
   <div class="in_top">
      <p class="in_top_phone"><span>客服热线：<?php echo (C("web_tel")); ?></span></p>
      <p class="in_top_left">
          <span href="#" class="inT_wx">
             <font class="wx_img"><img src="__PUBLIC__/images/bot_wx.jpg" width="166" /></font>
          </span>
          <a href="http://weibo.com/jinyongbofa" class="inT_sina" target="_blank"></a>
          <span target="blank" class="inT_qq">
            <font class="qq_x">客户交流群：63693972</font>
          </span>
          <script type="text/javascript">
				$(".qq_x,.wx_img").hide();
				$(".inT_qq").mouseover(function(){
				    $(".qq_x").show();
				}).mouseleave(function(){
				    $(".qq_x").hide();
				});
				$(".inT_wx").mouseover(function(){
				    $(".wx_img").show();
				}).mouseleave(function(){
				    $(".wx_img").hide();
				});
				
		  </script>
      </p>
      <p class="in_top_right"><a href="<?php echo U('Lottery/activity_list');?>" title="最新活动" target="_blank"><font color="#f05802">最新活动</font></a><a href="<?php echo U('SkillInvest/index',array('cid'=>23));?>" title="投资技巧">投资技巧</a><a href="<?php echo U('Single/index',array('cid'=>72));?>" title="联系我们">联系我们</a><a href="<?php echo U('SiteMap/index');?>">网站地图</a>      
      </p>
   </div>
</div>


<div class="out_head">
   <div class="in_head">
      <div class="in_head_left">
         <h1 class="logo"><a href="<?php echo U('Index/index');?>" title="<?php echo (C("web_title")); ?>"><img src="__PUBLIC__/images/logo.png" alt="<?php echo (C("web_title")); ?>" /></a></h1>         
         <div class="logo_img">
         <object id="FlashID" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="192" height="62">
              <param name="movie" value="__PUBLIC__/media/logo.swf" />
              <param name="quality" value="high" />
              <param name="wmode" value="opaque" />
              <param name="swfversion" value="8.0.35.0" />
              <!-- 此 param 标签提示使用 Flash Player 6.0 r65 和更高版本的用户下载最新版本的 Flash Player。如果您不想让用户看到该提示，请将其删除。 -->
              <param name="expressinstall" value="__PUBLIC__/Scripts/expressInstall.swf" />
              <!-- 下一个对象标签用于非 IE 浏览器。所以使用 IECC 将其从 IE 隐藏。 -->
              <!--[if !IE]>-->
              <object type="application/x-shockwave-flash" data="__PUBLIC__/media/logo.swf" width="192" height="62">
                <!--<![endif]-->
                <param name="quality" value="high" />
                <param name="wmode" value="opaque" />
                <param name="swfversion" value="8.0.35.0" />
                <param name="expressinstall" value="__PUBLIC__/Scripts/expressInstall.swf" />
                <!-- 浏览器将以下替代内容显示给使用 Flash Player 6.0 和更低版本的用户。 -->
<!--                 <div>
                  <h4>此页面上的内容需要较新版本的 Adobe Flash Player。</h4>
                  <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="获取 Adobe Flash Player" width="112" height="33" /></a></p>
                </div> -->
                <!--[if !IE]>-->
              </object>
              <!--<![endif]-->
         </object>
        </div>
      </div>
      
      <div class="in_head_right">
         <!--登录后显示-->
         <?php if($_SESSION['user_id']!= ''): ?><a href="<?php echo U('user/ExitUser');?>" class="in_register" title="安全退出">退出</a>
         <a href="<?php echo U('Member/index');?>" class="in_login" style=" width:150px; font-size:12px;" title="会员中心">您好！<?php echo (session('user_name')); ?></a>
         <?php else: ?>
         <!--没登录显示-->
         <a href="<?php echo U('user/register');?>" class="in_register" title="会员注册">注册</a>
         <a href="<?php echo U('user/login');?>" class="in_login" title="会员登录">登录</a><?php endif; ?>         
      </div>
      <div class="in_head_cen"><a href="<?php echo U('user/register');?>"><img src="__PUBLIC__/images/head_l.png" alt="注册即送50元" /></a></div>
   </div>  
</div>
<div class="out_menu">
   <div class="top_menu">
      <ul>
      <?php $uri = $_SERVER['REQUEST_URI']; ?>
         <li class="on"><a href="<?php echo U('Index/index');?>" title="金投资_首页">首页</a></li>
         <li><a href="<?php echo U('Deal/index');?>" title="金投资_我要投资">我要投资</a></li>
         <li class="menu_jyd"><a href="<?php echo U('LoanApply/index');?>" title="金投资_金涌贷">金涌贷</a><span><img src="__PUBLIC__/images/c.gif" /></span></li>
         <li><a href="<?php echo U('Security/index');?>" title="金投资_安全保障">安全保障</a></li>
         <li><a href="<?php echo U('Partner/Join');?>" title="金投资_合作伙伴">合作伙伴</a></li>
         <li><a href="<?php echo U('Guide/index');?>" title="金投资_新手指引">新手指引</a></li>
         <li><a href="<?php echo U('About/index');?>" title="金投资_关于我们">关于我们</a></li>
         <li><a href="<?php echo U('Ask/index');?>" title="金投资_问答专区">常见问题</a></li>
      </ul>
         <script type="text/javascript">
         //----导航高亮效果begin----
         $uri = '<?php echo ($uri); ?>';         
         $(".top_menu ul li a").each(function(i) {                         
              if($(this).attr('href') == $uri){
                $(this).parent().addClass('on').siblings().removeClass('on');
              }
            
         });
        //----导航高亮效果end----
       $(document).ready(function() {          
          $('.menu_account').hover(function() {
               $('.sec_acc', this).slideDown(200);

            }, function() {
               $('.sec_acc', this).slideUp(100);   
          });  
            
       });
       </script>
         <?php if($_SESSION['user_id']!= ''): $com_user_id = session('user_id'); if(!empty($com_user_id)){ $com_msg_where['uid'] = $com_user_id; $com_msg_where['is_read'] = 0; $com_msg_count = M('user_action_record')->where($com_msg_where)->count(); $dlg_where['uid'] = $com_user_id; $dlg_where['is_read'] = 0; $dlg_count = M('user_money_record')->where($dlg_where)->count(); $com_count = $dlg_count + $com_msg_count; } ?>
         <div class="menu_account">
             <a class="my_account" href="<?php echo U('Member/index');?>" title="金投资_我的账户">我的账户<?php if($com_count > 0): ?><span class="me_unread"> (<?php echo ($com_count); ?>)</span><?php endif; ?></a>
             <div class="sec_acc" style="display:none;">
               <a href="<?php echo U('Member/TopUp');?>">充值</a>
               <a href="<?php echo U('Member/Withdrawal');?>">提现</a>
               <a href="<?php echo U('Member/Message');?>">消息 <?php if($com_count > 0): ?><span class="unread_num">( <?php echo ($com_count); ?> )</span><?php endif; ?></a>
             </div>
             <!--以上三个链接是会员里的页-->
         </div><!--如果登录则进入会员首页，没登录进入登录页面--><?php endif; ?>
   </div>
</div>

<div class="flexslider" >
	<ul class="slides">
  <?php if(is_array($top_list)): foreach($top_list as $key=>$v): ?><li style="background:url(<?php if($v['ad_image'] != ''): echo ($v["ad_image"]); else: ?>/Public/Images/image_err.gif<?php endif; ?>) 50% 0 no-repeat;"><a href="<?php echo ($v["ad_link"]); ?>" target="_blank" title="<?php echo ($v["ad_name"]); ?>"></a></li><?php endforeach; endif; ?>
	</ul>
</div>
<div class="in_center InLine">
      <ul class="In_Icon">
         <li style=" margin-left:0;">
             <a href="<?php echo U('Lottery/bond_advantage');?>">
             <span class="InIc InIcon1"><i></i></span>
             <span class="IcTit">债权优势</span>
             <span class="IcCon">东北三省1200余家有抵押企业债权 安全透明看得见</span>
             </a>
         </li>
         <li style=" width:155px;">
             <a href="<?php echo U('Lottery/mode');?>">
               <span class="InIc InIcon2"><i></i></span>
               <span class="IcTit">创新模式</span>
               <span class="IcCon">联盟担保 诉讼保全<br />数十家合作伙伴共同为投资人保驾护航</span>
             </a> 
         </li>
         <li>
             <a href="<?php echo U('Security/index');?>">
               <span class="InIc InIcon3"><i></i></span>
               <span class="IcTit">安全保障</span>
               <span class="IcCon">有抵押有担保 有风险保证金 严谨风控<br />打造安全理财金标准</span>
             </a>
         </li>
         <li>
             <a href="<?php echo U('Guide/index');?>">
               <span class="InIc InIcon4"><i></i></span>
               <span class="IcTit">新手指引</span>
               <span class="IcCon">安全 透明 专业 便捷 享受金投资快乐 从这里开始</span>
             </a>
         </li>
      </ul>
   <p class="In_Video"><a href="<?php echo U('Video/index');?>" title="理财视频"><img src="__PUBLIC__/images/inVideo.png" /></a></p> 
   <div class="spacer"></div>
</div>

<div class="Cen_m">
   <div class="InLeft InLine New_tj">
      <div class="index_tit">
         <span class="New_x">新标推荐</span>
          <?php if($rec_top["deal_status"] != 1): ?><!--<div class="recom"><img src="__PUBLIC__/images/recommend.png" alt="金投资_新标推荐" /></div>-->
          <?php else: ?>         
         <div class="remaining" id="divdown1"></div><?php endif; ?>
         
      </div>
      <p class="NewPro_img"><a href="<?php echo U('Deal/DealContent',array('lid'=>$rec_top['id']));?>" title="<?php echo ($rec_top["loan_title"]); ?>"><img src="<?php if($rec_top['img'] != ''): echo ($rec_top["img"]); else: ?>/Public/Images/image_err.gif<?php endif; ?>" width="248" height="240" /></a></p>
      <ul class="NewPro_Det">
        <li class="NewPro_tit"><span><a href="<?php echo U('Deal/DealContent',array('lid'=>$rec_top['id']));?>" title="<?php echo ($rec_top["loan_title"]); ?>" target="_blank"><?php echo (msubstr($rec_top["loan_title"],0,20)); ?></a></span><span class="NewPro_db"><img src="__PUBLIC__/images/db.png" /></span></li>
        <li class="New_T">
           <div class="recom_bar"><span class="recom_bar_j" data-width="<?php echo (intval($rec_top["load_rate"])); ?>%"><font><i><img src="__PUBLIC__/images/recom_angle.png" /></i><?php echo (intval($rec_top["load_rate"])); ?>%</font></span></div>
        </li>
        <script type="text/javascript">
        $(".recom_bar_j").each(function () {
        
			var recom_width = $(this).attr("data-width");
			$(this).css("width",recom_width);
	
		});
        </script>
        <li class="NewP_am">
           <p class="P_am1">
             <span class="Am_txt">年化利率</span>
             <span class="Am_rate"><?php echo (intval($rec_top["loan_rate"])); ?><i><?php if($rec_top['loan_add_rate'] != 0): ?>+<?php echo ($rec_top["loan_add_rate"]); endif; ?>%</i></span>
           </p>
           <p class="P_am2">
             <span class="Am_txt">借款金额</span>
             <span class="Am_limit"><?php echo ($rec_top["loan_money"]); ?><i>元</i></span>
           </p>
           <p class="am_p_last">
             <span class="Am_txt">借款期限</span>
             <span class="Am_limit">
                <?php echo ($rec_top["loan_deadline"]); ?>
                <i>
                <?php switch($rec_top["loan_deadline_type"]): case "0": ?>个月<?php break;?>
                <?php case "1": ?>天<?php break; endswitch;?>    
                </i>
             </span>
           </p>
        </li>        
        <li class="start_time">
           <p>还款方式：<?php echo ($rec_top["repayment_mode"]); ?></p> 
           <p class="hk_Way">项目开始时间：<span><?php echo (date('Y-m-d H:i:s',$rec_top["start_time"])); ?></span></p>      
        </li>
        <li class="xm_Btn">
        <span class="buy_Btn">        
        <?php switch($rec_top["deal_status"]): case "1": if($rec_top['now_time'] < $rec_top['start_time'] && $rec_top['deal_status'] == 1): ?><a href="javascript:;" title="<?php echo ($rec_top["loan_title"]); ?>" class="huanQ">待开放</a>
              <?php else: ?>
              <a href="<?php echo U('Deal/DealContent',array('lid'=>$rec_top['id']));?>" title="<?php echo ($rec_top["loan_title"]); ?>" class="ljgm" target="_blank">立即投标</a></li><?php endif; break;?>        
            <?php case "2": ?><a href="javascript:;" title="<?php echo ($rec_top["loan_title"]); ?>" class="manB">满标</a></li><?php break;?>
            <?php case "3": ?><a href="javascript:;" class="invest_hk">流标</a><?php break;?>
            <?php case "4": ?><a href="javascript:;" class="huanK">还款中</a><?php break;?>
            <?php case "5": ?><a href="javascript:;" class="huanQ">已还清</a><?php break; endswitch;?> 
        </span>
      </ul>
      
   </div>
   <div class="InRight InLine Media_bd">
      <h3 class="index_tit"><a href="<?php echo U('Media/index',array('cid'=>24));?>" class="index_more">更多></a>媒体新闻</h3>
      <ul class="Media_list">
        <?php if(is_array($mt_list)): foreach($mt_list as $key=>$v): ?><li>
           <span class="MediaImg"><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>"><img src="<?php if($v['img'] != ''): echo ($v["img"]); else: ?>/Public/Images/image_err.gif<?php endif; ?>" width="70" height="50" alt="" /></a></span>
           <span class="MediaTit"><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>"><?php echo (msubstr($v["title"],0,10)); ?></a></span>
           <span class="MediaCon"><?php echo (msubstr($v["description"],0,17)); ?></span>
        </li><?php endforeach; endif; ?>
      </ul>
   </div>
   <div class="spacer"></div>
</div>

<div class="Cen_m">
   <div class="InLeft">
      <div class="financial_Cp InLine">
         <h3 class="index_tit"><a href="<?php echo U('Deal/ProductIntroduce');?>" class="index_more">更多></a>理财产品</h3>
         <ul class="Finan_list">
           <?php $x = 0; ?>
           <?php if(is_array($pro_class_list)): foreach($pro_class_list as $key=>$v): $x++; ?>
            <li <?php if($x == 1): ?>style="border-left:none;"<?php endif; ?>>
                <h3><?php echo ($v["class_name"]); ?></h3>
                <dl>
                   <dd><span class="Finan_s">年化收益</span><span class="Finan_rate">筹备中</span></dd>
                   <dd><span class="Finan_s">封闭期限</span><span class="Finan_mon"><?php echo ($v["class_deadline"]); ?><i>个月</i></span></dd>
                   <dd><span class="Finan_s">发售总额</span><span class="Finan_money"><?php echo ($v["load_money"]); ?>元</span></dd>
                   <dt><?php echo (msubstr($v["class_description"],0,16)); ?></dt>
                </dl>
                <span class="Finan_Btn"><a href="javascript:;" title="" class="Finan_gm">敬请期待</a></span>
            </li><?php endforeach; endif; ?>
         </ul>
         <div class="spacer"></div>
      </div>
      <div class="InvestList InLine">
         <h3 class="index_tit"><a href="<?php echo U('Deal/index');?>" title="投资项目" class="index_more">更多></a>投资项目</h3>
         <ul class="Invest_ul">
          <?php unset($i); ?>
            <?php if(is_array($loan_list)): foreach($loan_list as $key=>$v): $i++; ?>
            <li>
               <h3>
                  <span><a href="<?php echo U('Deal/DealContent',array('lid'=>$v['id']));?>" title="<?php echo ($v["loan_title"]); ?>" target="_blank"><?php echo (msubstr($v["loan_title"],0,15)); ?></a></span>
                  <span class="Invest_icon"><img src="__PUBLIC__/images/Invest_icon.png" /></span>
                  <?php if($v['deal_status'] == '1'): ?><span class="xm_start"><i>项目开始时间：<?php echo (date('Y-m-d H:i:s',$v["start_time"])); ?></i></span><?php endif; ?>              
               </h3>
               <p class="Invest_to">
                  <span class="Invest_txt">总额：</span><span><?php echo ($v["loan_money"]); ?>元</span>
               </p>
               <p class="Invest_li">
                  <span class="Invest_txt">期限：</span><span>
                  <?php echo ($v["loan_deadline"]); ?>
                  <?php switch($v["loan_deadline_type"]): case "0": ?>个月<?php break;?>
                  <?php case "1": ?>天<?php break; endswitch;?>  
                  </span>
               </p>
               <p class="Invest_ra">
                  <span class="Invest_txt">收益率：</span><span class="Sy_rate">
                   <?php echo (intval($v["loan_rate"])); ?>
                   <?php if($v['loan_add_rate'] != 0): ?><i>+<?php echo ($v["loan_add_rate"]); ?></i><?php endif; ?><i>%</i>
                   </span>
               </p>
               <div class="Invest_pe">
                  <div class="pro_percent"><div class="pro_progress" id="bard<?php echo ($i); ?>" data-load<?php echo ($i); ?>="<?php echo ($v["load_rate"]); ?>%"></div></div>
                  <span><?php echo ($v["load_rate"]); ?>%</span>
               </div>
               <p class="Invest_Xx">
               
                  <?php if($v['now_time'] < $v['start_time'] && $v['deal_status'] == 1): ?><a href="javascript:;" class="Invest_hq">待开放</a><?php endif; ?>
                  <?php if($v['now_time'] >= $v['start_time'] && $v['deal_status'] == 1): ?><a href="<?php echo U('Deal/DealContent',array('lid'=>$v['id']));?>" class="Invest_buy" target="_blank">投标</a><?php endif; ?>                  
                  <?php switch($v["deal_status"]): case "2": ?><a href="javascript:;" class="Invest_mb">满标</a><?php break;?>
                      <?php case "3": ?><a href="javascript:;" class="Invest_hk">流标</a><?php break;?>
                      <?php case "4": ?><a href="javascript:;" class="Invest_hk">还款中</a><?php break;?>
                      <?php case "5": ?><a href="javascript:;" class="Invest_hq">已还清</a><?php break; endswitch;?>  
               </p>
            </li><?php endforeach; endif; ?>

         </ul>
      </div>
      <div class="Indus_News InLine">
          <div class="Industry">
             <h3><a href="<?php echo U('Industry/index',array('cid'=>29));?>" class="index_more" title="行业新闻">更多></a>行业新闻</h3>
             <ul>
             <?php if(is_array($hy_list)): foreach($hy_list as $key=>$v): ?><li>
                   <span class="Indus_pic"> <a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>" target="_blank"><img src="<?php if($v['img'] != ''): echo ($v["img"]); else: ?>/Public/Images/image_err.gif<?php endif; ?>" width="52" height="52" alt="" /></a></span>
                   <span class="Indus_tit"> <a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>" target="_blank"><?php echo (msubstr($v["title"],0,17)); ?></a></span>
                   <span class="Indus_con"><?php echo (msubstr($v["description"],0,32)); ?></span>
                </li><?php endforeach; endif; ?>
             </ul>
          </div>
          <div class="ClassRoom">
             <h3><a href="<?php echo U('SkillInvest/index',array('cid'=>23));?>" class="index_more" title="投资技巧">更多></a>投资技巧</h3>
             <ul>
             <?php $x=0; ?>
             <?php if(is_array($jq_list)): foreach($jq_list as $key=>$v): $x++; ?>
                <?php if($x == 1): ?><li class="Class_fir">
                   <span class="Class_pic"><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>" target="_blank"><img src="<?php if($v['img'] != ''): echo ($v["img"]); else: ?>/Public/Images/image_err.gif<?php endif; ?>" width="92" height="92" alt="" /></a></span>
                   <span class="Class_tit"><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>" target="_blank"><?php echo (msubstr($v["title"],0,15)); ?></a></span>
                   <span class="Class_con"><?php echo (msubstr($v["description"],0,20)); ?></span>
                </li>
                <?php else: ?>
                <li><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>" target="_blank"><?php echo (msubstr($v["title"],0,15)); ?></a></li><?php endif; endforeach; endif; ?>
             </ul>
          </div>
          <div class="spacer"></div>
      </div>
      
   </div>
   <div class="InRight scroll_news">
      <div class="NewNews InLine">
          <div class="New_tabmenu">
            <ul>
               <li class="New_sel"><a href="<?php echo U('Announcement/index',array('cid'=>20));?>" target="_blank">网站公告</a></li>
               <li><a href="<?php echo U('Announcement/index',array('cid'=>44));?>" target="_blank">政策法规</a></li>
            </ul>
          </div>
          <div id="playBox">
            <div class="pre"></div>
            <div class="next"></div>
            <div class="smalltitle">
              <ul>
               <?php if(is_array($ad_list)): foreach($ad_list as $key=>$v): ?><li <?php if($v['i'] == 1): ?>class="thistitle"<?php endif; ?>><?php echo ($v["i"]); ?></li><?php endforeach; endif; ?>
              </ul>
            </div>
            <ul class="oUlplay">
               <?php if(is_array($ad_list)): foreach($ad_list as $key=>$v): ?><li><a href="<?php echo ($v["ad_link"]); ?>" target="_blank" title="<?php echo ($v["ad_title"]); ?>"><img src="<?php echo ($v["ad_image"]); ?>" alt="" /></a></li><?php endforeach; endif; ?>
            </ul>
          </div>
          
          <div class="New_tabbox">
             <div class="New_box">
                 <ul>
                   <?php if(empty($gg_list)): ?><li>暂无数据哦!</li>
                   <?php else: ?>
                   <?php $x = 0; ?>
                   <?php if(is_array($gg_list)): foreach($gg_list as $key=>$v): $x++; ?>                 
                    <li <?php if($x == 1): ?>class="news_first"<?php endif; ?>><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>"><span>&bull;</span><?php echo (msubstr($v["title"],0,16)); ?></a></li><?php endforeach; endif; endif; ?>
                 </ul>
             </div>
             <div class="New_box hide">
                 <ul>
                   <?php if(empty($zc_list)): ?><li>暂无数据哦!</li>
                   <?php else: ?>                 
                   <?php $x = 0; ?>
                   <?php if(is_array($zc_list)): foreach($zc_list as $key=>$v): $x++; ?>                 
                    <li <?php if($x == 1): ?>class="news_first"<?php endif; ?>><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" target="_blank" title="<?php echo ($v["title"]); ?>"><span>&bull;</span><?php echo (msubstr($v["title"],0,16)); ?></a></li><?php endforeach; endif; endif; ?>
                 </ul>
             </div>
          </div>
      </div>
      <div class="Calculator InLine">
          <ul>
             <li class="Cal_fir"><a href="<?php echo U('Calculator/EarningsCalc');?>" title="">收益计算器</a></li>
             <li class="Cal_two" style="border-right:none;"><a href="<?php echo U('Calculator/CPICalc');?>" title="">CPI跟踪器</a></li>
             <li class="Cal_thi" style=" border-bottom:none;"><a href="<?php echo U('Calculator/IdentityCalc');?>" title="">身价计算器</a></li>
             <li class="Cal_fou" style=" border-bottom:none; border-right:none;"><a href="javascript:alert('敬请期待');" title="">收益对比器</a></li>
          </ul>
          <div class="spacer"></div>
      </div>
      <div class="LcStory InLine">
         <h3 class="Story_h"><a href="<?php echo U('Announcement/index',array('cid'=>46));?>" title="">理财故事</a></h3>
         <ul>
             <?php if(empty($gs_list)): ?><li>暂无数据哦!</li>
             <?php else: ?>            
             <?php if(is_array($gs_list)): foreach($gs_list as $key=>$v): ?><li>
                <span class="Story_img"><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>" target="_blank"><img src="<?php if($v['img'] != ''): echo ($v["img"]); else: ?>/Public/Images/image_err.gif<?php endif; ?>" width="64" height="64" alt="" /></a></span>
                <span class="Story_tit"><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>" target="_blank"><?php echo (msubstr($v["title"],0,13)); ?></a></span>
                <span class="Story_con"><?php echo (msubstr($v["description"],0,30)); ?></span>
             </li><?php endforeach; endif; endif; ?>
         </ul>
         <div class="spacer"></div>
      </div>
   </div>
   <div class="spacer"></div>
</div>

<div class="media_logo">
      <h3 class="main_tit">合作伙伴</h3>
       <ul class="Patner">
         <?php $i = 0; ?>
         <?php if(is_array($hb_list)): foreach($hb_list as $key=>$v): $i++; ?>
            <li  <?php if($i == 1 || $i % 6 == 1): ?>class="media_none"<?php endif; ?>>
            <a <?php if($v['id'] == 2021): ?>href="#" <?php else: ?>href="<?php echo U('Partner/PartnerDetail',array('id'=>$v['id']));?>"<?php endif; ?> target="_blank" >
            <img src="<?php if($v['img'] != ''): echo ($v["img"]); else: ?>/Public/Images/image_err.gif<?php endif; ?>" alt="<?php echo ($v["title"]); ?>" width="150" height="87" />
            </a>
            </li><?php endforeach; endif; ?>         
       </ul>
       <div class="spacer"></div>
</div>

<div class="media">
   <div class="media_logo">
       <h3 class="main_tit">媒体报道</h3>
       <ul>
          <li class="media_none"><a href="http://news.163.com/14/1203/01/ACGLLURF00014AED.html" target="_blank"><img src="__PUBLIC__/images/media_l_2.jpg" width="100" alt="网易新闻" /></a></li>
          <li><a href="http://nb.ifeng.com/qyzx/detail_2015_01/14/3427220_0.shtml" target="_blank"><img src="__PUBLIC__/images/media_l_3.jpg" width="100" alt="凤凰商业" /></a></li>
          <li><a href="http://finance.21cn.com/newsdoc/zx/a/2015/0114/15/28888794.shtml" target="_blank"><img src="__PUBLIC__/images/media_l_5.jpg" width="100" alt="21CN财经" /></a></li>
          <li><a href="http://finance.ce.cn/sub/2011/qy/lv/201501/14/t20150114_4340484.shtml" target="_blank"><img src="__PUBLIC__/images/media_l_6.jpg" width="100" alt="中国经济网" /></a></li>
          <li><a href="http://mcq.people.com.cn/jrcq/news/2015115/2015115927538172941.htm" target="_blank"><img src="__PUBLIC__/images/media_l_7.jpg" width="100" alt="人民网" /></a></li>
          <li><a href="http://economy.gmw.cn/2015-01/15/content_14527230.htm" target="_blank"><img src="__PUBLIC__/images/media_l_8.jpg" width="100" alt="光明网" /></a></li>
          <li><a href="http://www.fechina.com.cn/" target="_blank"><img src="__PUBLIC__/images/media_l_9.jpg" width="100" alt="财经中国网" /></a></li>
          <li><a href="http://www.hb.xinhuanet.com/2015-01/15/c_1114006941.htm" target="_blank"><img src="__PUBLIC__/images/media_l_29.jpg" width="100" alt="新华网" /></a></li>
          <li class="media_none"><a href="http://www.ci5168.com/caijing/gongsi/3763.html" target="_blank"><img src="__PUBLIC__/images/media_l_12.jpg" width="100" alt="环球金融网" /></a></li>
          <li><a href="http://union.china.com.cn/zfgl/2015-01/14/content_7600516.htm" target="_blank"><img src="__PUBLIC__/images/media_l_14.jpg" width="100" alt="联盟中国" /></a></li>
          <li><a href="http://finance.china.com/fin/lc/201501/15/2164213.html" target="_blank"><img src="__PUBLIC__/images/media_l_25.jpg" width="100" alt="中华网" /></a></li>
          <li><a href="http://www.heb.chinanews.com/shijiazhuang/html/tzlc/201501/142408.html" target="_blank"><img src="__PUBLIC__/images/media_l_28.jpg" width="100" alt="中新网" /></a></li>
          <li><a href="http://www.chinajilin.com.cn/news/content/2014-08/22/content_3380321.htm" target="_blank"><img src="__PUBLIC__/images/media_l_30.jpg" width="100" alt="中国吉林网" /></a></li>
       </ul>
       <div class="spacer"></div>
    </div>
</div>
<div class="zbs"><a href="<?php echo U('Lottery/qualification');?>" target="_blank"><img src="__PUBLIC__/images/zbs1.png" /></a></div>
<div class="bottom1">
   <div class="bot_item">
   
      <div class="bot_item1">
         <h3>关于我们</h3>
         <ul>
            <li><a href="<?php echo U('About/index');?>" title="">公司简介</a></li>
            <li><a href="<?php echo U('Single/index',array('cid'=>74));?>" title="">法律政策</a></li>
            <li><a href="<?php echo U('Bank/index',array('class_name'=>'合作银行'));?>" title="">合作银行</a></li>
            <li><a href="<?php echo U('Media/index',array('cid'=>24));?>" title="">媒体新闻</a></li>
            <li><a href="<?php echo U('Recruitment/index',array('cid'=>34));?>" title="">招贤纳士</a></li>
            
         </ul>
      </div>
      
      <div class="bot_item2">
         <h3>常见问题</h3>
         <ul>         
         <?php $bottom_hot_where['pid'] = array('between',array('31','33')); $bottom_list = M('info')->where($bottom_hot_where)->order('click desc,id desc')->limit(5)->select(); foreach ($bottom_list as $key => $v){ ?>
            <li><a href="<?php echo U('Article/articleDetail',array('id'=>$v['id']));?>" title="<?php echo ($v["title"]); ?>"><?php echo (msubstr($v["title"],0,15)); ?></a></li>
            <?php } ?>
         </ul>
      </div>
      
      <div class="bot_item3">
         <h3>联系我们</h3>
         <ul>
            <li><img src="__PUBLIC__/images/contact_hot.png" alt="客服热线：4006-570-826" /></li>
            <li>地址：<?php echo (C("web_address")); ?></li>
            <!--<li>总部地址：西安大路1016号&nbsp;&nbsp;&nbsp;办公电话：0431-81638266</li>-->
            <li>服务时间：<?php echo (C("web_time")); ?></li>
              <?php $ar = explode('?',$_SERVER['REQUEST_URI']); if(count($ar)>0){ $get_param = $ar[1]; $get_param = "&".$get_param; } ?>            
            <?php if($_SESSION['is_change']== 'www'): ?><li><a href="?is_change=1<?php echo ($get_param); ?>"><font color="#33FFCC">手机版</font></a></li><?php endif; ?>
            <li>客户交流群：63693972</li>
         </ul>
      </div>
      
      <div class="bot_item4">
         <h3>关注我们</h3>
         <ul>
            <li>
               <span><img src="__PUBLIC__/images/bot_wx.jpg" width="90" alt="金投资微信二维码" /></span>
               <span>腾讯微信</span>
            </li>
            <li>
               <span><img src="__PUBLIC__/images/bot_sina.jpg" alt="金投资新浪微博二维码" /></span>
               <span>新浪微博</span>
            </li>
         </ul>
      </div>
      
   </div>   
</div>
<div class="bottom2">
   <div class="bot_bq">
        <p class="Friend_a"><span>
        友情链接：
<?php $links_list = M('ad')->where("class_name='友情链接'")->order('ad_order DESC,id DESC')->select(); $z = 0; foreach($links_list as $k=>$v){ $z++; if($z>1){ echo "|"; } echo "<a href=".$v['ad_link']." target=_blank>".$v['ad_name']."</a>"; } ?>

        </span></p>
       <ul class="bot_rz">
           <li>
              <span><a href="http://webscan.360.cn/index/checkwebsite/url/www.p2p222.com" target="_blank"><img src="__PUBLIC__/images/kx360.png"/></a></span>
              <script src="http://kxlogo.knet.cn/seallogo.dll?sn=e15031022010057890iu2i000000&size=0"></script>
              <span><a href="http://www.p2p222.com/index.php/Article/articleDetail/id/1014.html" target="_blank"><img src="__PUBLIC__/images/gjym.png" /></a></span>
              <span><a href="http://www.itrust.org.cn/yz/pjwx.asp?wm=1081839472" target="_blank"><img src="__PUBLIC__/images/kx_web.png" /></a></span>
           </li>
       </ul>
       <p class="copyright">
          <a href="http://www.beianbeian.com/" target="_blank"><?php echo (C("web_copyright")); ?></a>
       </p>
       <p class="tj_total">
         <script type="text/javascript">
         var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
         document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fe29aec143162fc1ca52145771a9ee305' type='text/javascript'%3E%3C/script%3E"));
         </script>          
         <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_5958842'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/stat.php%3Fid%3D5958842%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>              
       </p>   
       <div class="spacer"></div>   
   </div>
</div>

<div class="side">
	<ul>
		<li><a href="javascript:;" class="cal_jsq"><div class="sidebox"><img src="__PUBLIC__/images/side_icon01.png">理财计算器</div></a></li>
        <li><a href="<?php echo U('user/register');?>"><div class="sidebox"><img src="__PUBLIC__/images/side_icon06.png">用户注册</div></a></li>
		<li><a href="tencent://message/?uin=2057287718&在线咨询&Menu=yes" ><div class="sidebox"><img src="__PUBLIC__/images/side_icon04.png">QQ客服</div></a></li>
		<li><a href="http://weibo.com/jinyongbofa" target="_blank" ><div class="sidebox"><img src="__PUBLIC__/images/side_icon03.png">新浪微博</div></a></li>
		<li style="border:none;"><a href="javascript:goTop();" class="sidetop"><img src="__PUBLIC__/images/side_icon05.png"></a></li>
	</ul>
</div>

<div class="calcul_l">
   <div class="cal_mid">
      <h3>理财计算器</h3>
     <table cellpadding="0" cellspacing="0" border="0" width="75%">
        <tr>
           <td width="28%" height="45">投资金额：</td>
           <td width="72%"><input type="text" class="cul_txt" id="mid_money" /> 元</td>
        </tr>
        <tr>
           <td height="45">投资期限：</td>
           <td>
           <input type="text" class="cul_txt" id="mid_deadline" style="width:50px;" /> 
            <select name="loan_deadline_type" id="loan_deadline_type"  style="width:50px;height:30px;">          
                <option value="1">天</option>
                <option value="0">月</option>
            </select>
           </td>
        </tr>
        <tr>
           <td height="45">年化利率：</td>
           <td><input type="text" class="cul_txt" id="mid_rate" /> %</td>
        </tr>
        <tr>
           <td height="50">&nbsp;</td>
           <td><input type="button" value="计算" class="calculate" id="mid-dispose" /></td>
        </tr>
     </table>
     <h3>计算结果</h3>
     <table cellpadding="0" cellspacing="0" border="0" width="75%">
        <tr>
           <td width="28%">到期收益：</td>
           <td width="72%"><span id="mid_result">0</span>元 </td>
        </tr>
     </table>
     <span class="js_close"><img src="__PUBLIC__/images/wx_close.png" /></span>
   </div>
</div>
<script type="text/javascript" src="__PUBLIC__/js/customer.js"></script>
</body>
</html>