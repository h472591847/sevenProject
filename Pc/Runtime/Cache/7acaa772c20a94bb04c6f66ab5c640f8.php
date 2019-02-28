<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=8,9,10,11">
<title><?php echo ($loan["loan_title"]); ?>-<?php echo (C("web_title")); ?></title>
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
</head>
<link href="__PUBLIC__/style/base.css" rel="stylesheet" type="text/css" />
<link href="__PUBLIC__/style/page.css" rel="stylesheet" type="text/css" />
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
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.8.3.min.js"></script>
<!--百分比进条条 start-->
<script type="text/javascript" src="__PUBLIC__/js/jquery.tips.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.smint.js"></script>

<script type="text/javascript" src="__PUBLIC__/js/prefixfree.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){   
   $('.subMenu').smint({
      'scrollSpeed' : 1000
    });

  //百分比进度条
	var percent = $('.progress_bar').attr('title');
	$(".progress_bar").animate({width:percent},1000);
	$(".tip").tipsy({gravity:'s',fade:true});
  
  $("div.inner .subNavBtn").click(function() {   
    $(this).addClass("subNav_sel").siblings().removeClass("subNav_sel");  
  });
  
});
</script>
<script language="javascript" type="text/javascript"> 
$(document).ready(function(){
  $.ajax({
      url: '<?php echo U("Index/index");?>',
      type: 'GET',
      dataType: 'json',
      async : false,//同步以接收变量
      data: "get_ajax_time=1",               
      success:function(msg){
          if(msg.status == '1'){
              now_gtime = msg.data * 1000;
          }
          return false;
      }
  })
  var retime = '<?php echo ($loan["re_start_time"]); ?>';//剩余时间(秒)   
  var submit_type = "";
  var interval = 1000; 
  window.setInterval(function(){
	now_gtime = now_gtime + interval ;   
    var retY = <?php echo (date('Y',$loan["start_time"])); ?>;//读取年份
    var retM = <?php echo (date('m',$loan["start_time"])); ?>;//读取月份
    var retD = <?php echo (date('d',$loan["start_time"])); ?>;//读取日期       
    var retH = <?php echo (date('H',$loan["start_time"])); ?>;//读取小时
    var retI = <?php echo (date('i',$loan["start_time"])); ?>;//读取分钟
    var retS = <?php echo (date('s',$loan["start_time"])); ?>;//读取秒       
    if(retime >= 0 && retime != ''){
      ShowCountDown(now_gtime,retY,retM,retD,retH,retI,retS,'divdown1');
    }     
  }, interval);  
  //剩余时间计算 
  function ShowCountDown(now_time,year,month,day,hour,minute,second,divname,typename){ 
  var now_time = new Date(now_time);
  var endDate = new Date(year, month-1, day,hour,minute,second); 
  var leftTime= endDate.getTime() - now_time.getTime();
  var leftsecond = parseInt(leftTime/1000); 
  var day1=Math.floor(leftsecond/(60*60*24)); 
  var hour=Math.floor((leftsecond-day1*24*60*60)/3600); 
  var minute=Math.floor((leftsecond-day1*24*60*60-hour*3600)/60); 
  var second=Math.floor(leftsecond-day1*24*60*60-hour*3600-minute*60); 
  var cc = document.getElementById(divname); 
  if(day1 == 0 && hour == 0 && minute == 0 && second == 0){
     location.reload();
  }  
  cc.innerHTML = ""+"<span class='recom_date'>"+day1+"</span>"+"天"+"<span class='recom_date'>"+hour+"</span>"+"小时"+"<span class='recom_date'>"+minute+"</span>"+"分"+"<span class='recom_date'>"+second+"</span>"+"秒"; 
  } 
});


function display_field(obj){  
  $field_content = $(obj).attr('field-content');
  $field_status = $(obj).attr('display-status');
  $field_short_content = $(obj).attr('field-short-content');
  $short_content = $(obj).parent().siblings('.short_content').html();  
  $obj_this = $(obj).parent().siblings('.short_content');    
  if($field_status == '0'){
    $obj_this.html($field_content);
    $(obj).attr('display-status','1');
    $(obj).text('收起');
    return false;
  }else{        
    $(obj).parent().siblings('.short_content').html($field_short_content);
    $(obj).attr('display-status','0');
    $(obj).text('查看详情');    
    return false;
  }
  
  return false;
  // $(obj).parent().html($field_content);return false;
}
</script>
<!---->
<!--相册-->
<link href="__PUBLIC__/style/litebox.css" type="text/css" rel="stylesheet" />
<!---->


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


<div class="project_mid">
    <h3 class="location">首页 > 我要投资 > 项目详情</h3>
    <div class="ProjectTop">
        <h3 class="ProjectTitle"><?php echo ($loan["loan_title"]); ?></h3>
        <div class="ProjectLeft">
           <ul class="deal_amount">
               <li class="d_am_fir">
                  <span>借款金额</span>
                  <span class="deal_big"><?php echo (number_format($loan["loan_money"])); ?><font>元</font></span>
               </li>
               <li>
                  <span>年化利率</span>
                  <span class="deal_big"><?php echo ($loan["loan_rate"]); ?><font>%</font><?php if($loan['loan_add_rate'] != 0): ?><i>+<?php echo ($loan["loan_add_rate"]); ?>%</i><?php endif; ?></span>
               </li>
               <li class="d_am_last">
                  <span>借款期限</span>
                  <span class="deal_big"><?php echo ($loan["loan_deadline"]); ?><font><?php if($loan['loan_deadline_type'] == 0): ?>个月<?php else: ?>天<?php endif; ?></font></span>
               </li>
               <div class="spacer"></div>
           </ul> 
           <ul class="deal_det">
              <li>
                  <span class="det_l">借款用途：</span>
                  <span><?php echo ($loan["loan_purpose"]); ?></span>
              </li>
              <li>
                  <span class="det_l">已投标：</span>
                  <span><?php echo (format_price($loan["sum_amount"])); ?>元</span>
              </li>
              <li>
                  <span class="det_l">还款方式：</span>
                  <span><?php echo ($loan["repayment_mode"]); ?></span>
              </li>
              <li>
                  <span class="det_l">尚需金额：</span>
                  <span><?php echo (format_price($loan["loan_over"])); ?>元</span>
              </li>
              <li id="re_start" style="display:none;">
                  <span class="det_l">起投时间:</span>
                  <span id="divdown1"></span>
              </li> 
              <?php if($loan['re_start_time'] == ''): ?><li>
                  <span class="det_l">募集时间：</span>
                  <span><?php echo ($loan["retime"]); ?></span>
              </li><?php endif; ?>                            
              <li>
                  <span class="det_l">项目开始时间：</span>
                  <span><?php echo (date('Y-m-d H:i:s',$loan["start_time"])); ?></span>
              </li> 
              <li>
                  <span class="det_l">担保范围：</span>
                  <span><?php echo ($loan["guarantee_purview"]); ?></span>
              </li>              
              <div class="spacer"></div>
           </ul>
           
           <ul class="deal_process">
              <li class="d_db"><?php echo ($loan["guarantee_institution"]); ?></li>
              <li class="d_jd">
                  <span>投资进度</span>
                  <div class="progress_container"><div class="progress_bar tip" title="<?php echo ($loan["load"]); ?>%"></div></div>
                  <span><?php echo ($loan["load"]); ?>%</span>
              </li>
           </ul>
        </div>
        <?php if($loan['is_effect'] == 1): ?><div class="ProjectRight">
           <form action="<?php echo U('Deal/DoAddInvested');?>" id="do_add_form" method="post" onsubmit="return checkDoAdd(this)">           
           <input type="hidden" name="lid" id="lid" value="<?php echo ($loan["id"]); ?>">
           <ul>
              <!--<li>可投余额</li>-->
              <li class="DealRemain">可投余额：<span class="remaining"><?php echo ($loan["loan_over_view"]); ?></span>元</li>
              <li class="MyMoney">
                     <?php if($user_info['id'] != ''): ?><p>可用余额：<span class="remain"><?php echo ($user_info["money"]); ?></span>元
                     [<a href="<?php echo U('Member/TopUp');?>">充值</a>]</p>
                     <p>奖励金额：<span class="remain"><?php echo ($user_info["bonus_money"]); ?></span>元</p>
                     <!--没登录显示-->
                     <?php else: ?>
                       <p style=" margin:15px 0;">可用余额：<span>[<a href="<?php echo U('user/Login');?>">请先登录</a>]</span></p><?php endif; ?>
              </li>
              <li>
                   <p class="tz_aaa">
                        <span><input type="text" value="投资额应为<?php echo ($loan["min_loan_money"]); ?>或<?php echo ($loan["min_loan_money"]); ?>的倍数" class="tz_amount" onclick="if(this.defaultValue==this.value){this.value='';}" onblur="if(this.value==''){this.value=this.defaultValue;}" name="money" id="money" /></span>
                        <span class="suspension">
                        <?php if($_SESSION['user_id']!= ''): ?>使用余额￥<font id="use_money"></font>元
                        <br>使用奖励金额 ￥<font id="temp_money"></font>元<?php endif; ?>
                        <br>预期收益 ￥<font id="expect_money"></font>元
                        </span>
                   </p>
              </li>
              
              <?php if($web_user_rate_count > 0): ?><li class="tz_jxq">
                  <input name="rate_id" id="rate_id" type="checkbox" onchange="change_rate(this)" value="<?php echo ($one_user_rate_res["id"]); ?>" data-value="<?php echo ($one_user_rate_res["add_rate"]); ?>" />使用加息1%券（剩余<?php echo ($web_user_rate_count); ?>张）
                </li><?php endif; ?>
              <li>   
               <?php if($loan['re_start_time'] > 0): ?><input type="button" value="待开放" class="investment deal_dkf" disabled="disabled" name="submit_invested" id="submit_invested" /><?php endif; ?>
              <?php if($loan['re_start_time'] == 0 && $loan['deal_status'] == 1): ?><input type="button" value="立即投标" class="investment tz_tjbtn" name="submit_invested" id="submit_invested" /><?php endif; ?>
              <?php if($loan['re_start_time'] == 0 && $loan['deal_status'] == 2): ?><input type="button" value="满标" class="investment tz_tjbtn" disabled="disabled" name="submit_invested"  /><?php endif; ?>
              <?php if($loan['re_start_time'] == 0 && $loan['deal_status'] == 4): ?><input type="button" value="还款中" class="investment deal_dkf" disabled="disabled" name="submit_invested" /><?php endif; ?>
                  
              </li>
           </ul>
           </form>
        </div><?php endif; ?>
        <div class="spacer"></div> 
    </div>
    
</div>

<div class="wrap">
        <div class="subMenu" style=" width:1000px;">
            <div class="inner">
                    <?php if(empty($loan["loan_description"])): else: ?>
                        <a href="javascript:;" id="sTop" class="subNavBtn subNav_sel">项目描述</a><?php endif; ?>
                    <?php if($loan['company_info'] != '' || $loan['business_scope'] != '' || $loan['business_state'] != ''): ?><a href="javascript:;" id="s1" class="subNavBtn">企业信息</a><?php endif; ?>                                
                    <?php if($loan['guarantee_content'] == '' && $loan['guarantee_institution'] == '' && $loan['pawn_info'] == '' && $loan['risk_control'] == '' && $loan['guarantee_idea'] == ''): else: ?>                
                        <a href="javascript:;" id="s2" class="subNavBtn">担保信息</a><?php endif; ?>                                
                    <?php if(empty($loan["company_material"])): else: ?>                
                        <a href="javascript:;" id="s3" class="subNavBtn">企业材料</a><?php endif; ?>                
                    
                    <?php if($loan['pawn_action'] == '' && $loan['field_visit'] == ''): else: ?>                
                        <a href="javascript:;" id="s4" class="subNavBtn">抵押物实景</a><?php endif; ?>                
                    <?php if(empty($loan["contract_agreement"])): else: ?>                
                        <a href="javascript:;" id="s5" class="subNavBtn">合同协议</a><?php endif; ?>
                    <?php if($lid >= 21 && $loan['re_start_time'] == 0): ?><a href="javascript:;" id="s6" class="subNavBtn end">投资记录</a><?php endif; ?>
            </div>
        </div>
        <div class="section sTop" style=" margin-top:50px;">
                <div class="inner">
                    <h1>项目描述</h1>
                    <p>
                       <div class="short_content"><?php echo (msubstr($loan["loan_description"],0,150)); ?></div>
                       <span class="LookSp">                       
                       <?php if (strlen($loan['loan_description']) > strlen(msubstr($loan['loan_description'],0,150))){ ?>
                       <a onclick="display_field(this)" field-content='<?php echo ($loan["loan_description"]); ?>' display-status='0' field-short-content="<?php echo (msubstr($loan["loan_description"],0,150)); ?>" style="cursor:pointer" class="LookDetail">查看详情</a>
                       <?php } ?>
                       </span>
                    </p>
                    <ul class="ProContent">
                    <?php if(empty($loan["pawn_info"])): else: ?>
                       <li>
                          <p class="ProName">抵押物价值</p>
                          <p class="ProIntro"><?php echo ($loan["pawn_info"]); ?></p>
                          <div class="spacer"></div>
                       </li><?php endif; ?>
    <!--                    <li>
                          <p class="ProName">借款用途</p>
                          <p class="ProIntro"><?php echo ($loan["loan_purpose"]); ?><a href="#" class="Look_x">查看详情</a></p>
                          <div class="spacer"></div>
                       </li> -->
                    <?php if(empty($loan["payment_source"])): else: ?>                   
                       <li>
                          <p class="ProName">还款来源</p>
                          <p class="ProIntro"><?php echo ($loan["payment_source"]); ?></p>
                          <div class="spacer"></div>
                       </li><?php endif; ?>
                    </ul>
                </div>
                <div class="spacer"></div>
            </div>
            <?php if($loan['company_info'] != '' || $loan['business_scope'] != '' || $loan['business_state'] != ''): ?><div class="section s1">
                <div class="inner">
                <?php if(empty($loan["company_info"])): else: ?>
                    <h1>企业信息</h1>
                    <p>
                       <div class="short_content"><?php echo (msubstr($loan["company_info"],0,150)); ?></div>
                       <span class="LookSp">
                       <?php if (strlen($loan['company_info']) > strlen(msubstr($loan['company_info'],0,150))){ ?>
                       <a onclick="display_field(this)" field-content='<?php echo ($loan["company_info"]); ?>' display-status='0' field-short-content="<?php echo (msubstr($loan["company_info"],0,150)); ?>" style="cursor:pointer" class="LookDetail">查看详情</a>
                       <?php } ?>
                       </span>                      
                    </p><?php endif; ?>
                    <ul class="ProContent">
                      <?php if(empty($loan["business_scope"])): else: ?>                   
                       <li>
                          <p class="ProName">经营范围</p>
                          <p class="ProIntro"><?php echo ($loan["business_scope"]); ?></p>
                          <div class="spacer"></div>
                       </li><?php endif; ?>
                      <?php if(empty($loan["business_state"])): else: ?>                     
                       <li>
                          <p class="ProName">经营状况</p>
                          <p class="ProIntro">
                          <span class="short_content"><?php echo (msubstr($loan["business_state"],0,150)); ?></span>
                          <span>
                          <?php if (strlen($loan['business_state']) > strlen(msubstr($loan['business_state'],0,150))){ ?>
                          <a onclick="display_field(this)" field-content='<?php echo ($loan["business_state"]); ?>' display-status='0' field-short-content="<?php echo (msubstr($loan["business_state"],0,150)); ?>" style="cursor:pointer" class="Look_x">查看详情</a>
                          </span>
                          <?php } ?>
                          </p>
                          <div class="spacer"></div>
                       </li><?php endif; ?>
                    </ul>
                </div>
            </div><?php endif; ?>
            <?php if($loan['guarantee_content'] != '' || $loan['guarantee_institution'] != '' || $loan['pawn_info'] != '' || $loan['risk_control'] != '' || $loan['guarantee_idea'] != ''): ?><div class="section s2">
                <div class="inner">         
                    <h1>担保信息</h1>                
                    <?php if(empty($loan["guarantee_content"])): else: ?>                   
                    <p>
                       <div class="short_content"><?php echo (msubstr($loan["guarantee_content"],0,150)); ?></div>
                       <span class="LookSp">
                       <?php if (strlen($loan['guarantee_content']) > strlen(msubstr($loan['guarantee_content'],0,150))){ ?>
                       <a onclick="display_field(this)" field-content='<?php echo ($loan["guarantee_content"]); ?>' display-status='0' field-short-content="<?php echo (msubstr($loan["guarantee_content"],0,150)); ?>" style="cursor:pointer" class="LookDetail">查看详情</a>
                       <?php } ?>
                       </span>                   
                    </p><?php endif; ?>
                    <ul class="ProContent">
                      <?php if(empty($loan["guarantee_institution"])): else: ?>                  
                       <li>
                          <p class="ProName">担保机构</p>
                          <p class="ProIntro"><?php echo ($loan["guarantee_institution"]); ?></p>
                          <div class="spacer"></div>
                       </li><?php endif; ?>
    <!--                    <li>
                          <p class="ProName">机构简介</p>
                          <p class="ProIntro"><?php echo ($loan["guarantee_content"]); ?><a href="#" class="Look_x">查看详情</a></p>
                          <div class="spacer"></div>
                       </li> -->
                      <?php if(empty($loan["pawn_info"])): else: ?>    
                         <li>
                            <p class="ProName">抵押物信息</p>
                            <p class="ProIntro"><?php echo ($loan["pawn_info"]); ?></p>
                            <div class="spacer"></div>
                         </li><?php endif; ?>
                      <?php if(empty($loan["risk_control"])): else: ?>                      
                       <li>
                          <p class="ProName">风险控制措施</p>
                          <p class="ProIntro">
                          <span class="short_content"><?php echo (msubstr($loan["risk_control"],0,150)); ?></span>
                          <span>
                          <?php if (strlen($loan['risk_control']) > strlen(msubstr($loan['risk_control'],0,150))){ ?>
                          <a onclick="display_field(this)" field-content='<?php echo ($loan["risk_control"]); ?>' display-status='0' field-short-content="<?php echo (msubstr($loan["risk_control"],0,150)); ?>" style="cursor:pointer" class="Look_x">查看详情</a>
                          <?php } ?>
                          </span>
                          </p>
                          <div class="spacer"></div>
                       </li><?php endif; ?>
                      <?php if(empty($loan["guarantee_idea"])): else: ?>                      
                       <li>
                          <p class="ProName">担保机构意见</p>
                          <p class="ProIntro"><?php echo ($loan["guarantee_idea"]); ?></p>
                          <div class="spacer"></div>
                       </li><?php endif; ?>
                    </ul>
                </div>
            </div><?php endif; ?>
            <?php if(empty($loan["company_material"])): else: ?>                     
            <div class="section s3">
                <div class="inner">
                    <h1>企业材料</h1>
                    <ul class="dul_pic">
                        <?php if(is_array($loan["company_material"])): foreach($loan["company_material"] as $key=>$img): ?><li>
                            <a href="<?php echo ($img); ?>" target="_self" class="litebox" data-litebox-group="group-1" ><img src="<?php echo ($img); ?>" width="190" height="120" /></a>
                        </li><?php endforeach; endif; ?>
                        <div class="spacer"></div>
                   </ul>
                </div>
            </div><?php endif; ?>     
            <?php if(empty($loan["pawn_action"])): else: ?>            
            <div class="section s4">
                <div class="inner">
                    <h1>抵押物实景</h1>
                    <ul class="dul_pic">
                        <?php if(is_array($loan["pawn_action"])): foreach($loan["pawn_action"] as $key=>$img): ?><li>
                            <a href="<?php echo ($img); ?>" target="_self" class="litebox" data-litebox-group="group-2" ><img src="<?php echo ($img); ?>" width="190" height="120" /></a>
                        </li><?php endforeach; endif; ?>
                        <?php if(is_array($loan["field_visit"])): foreach($loan["field_visit"] as $key=>$img): ?><li>
                            <a href="<?php echo ($img); ?>" target="_self" class="litebox" data-litebox-group="group-2" ><img src="<?php echo ($img); ?>" width="190" height="120" /></a>
                        </li><?php endforeach; endif; ?>                    
                        <div class="spacer"></div>
                   </ul>
                </div>
            </div><?php endif; ?>
            <?php if(empty($loan["contract_agreement"])): else: ?>           
            <div class="section s5">
                <div class="inner">           
                    <h1>合同协议</h1>
                    <ul class="dul_pic">
                        <?php if(is_array($loan["contract_agreement"])): foreach($loan["contract_agreement"] as $key=>$img): ?><li>
                            <a href="<?php echo ($img); ?>" target="_self" class="litebox" data-litebox-group="group-3" ><img src="<?php echo ($img); ?>" width="190" height="120" /></a>
                        </li><?php endforeach; endif; ?>
                        <div class="spacer"></div>
                   </ul>
                </div>
            </div><?php endif; ?> 
          <?php if($lid >= 21 && $loan['re_start_time'] == 0): ?><div class="section s6">
             <div class="inner">
                <h1>投资记录</h1>
                <iframe frameborder="0" width="100%" height="700" src="<?php echo U('Deal/tz_record',array('lid'=>$lid));?>" scrolling="no"></iframe>
              </div>
             
          </div><?php endif; ?>
</div>

<!--大图-->
<script src="__PUBLIC__/js/smoothscroll.js" type="text/javascript"></script>
<script src="__PUBLIC__/js/images-loaded.min.js" type="text/javascript"></script>
<script src="__PUBLIC__/js/litebox.js" type="text/javascript"></script>
<script src="__PUBLIC__/js/backbone.js" type="text/javascript"></script>

<script type="text/javascript">
  $('.litebox').liteBox();
  var retime = '<?php echo ($loan["re_start_time"]); ?>';//剩余时间(秒)   
  //投资窗口，筹标剩余时间控制
  if(retime >= 0 && retime != ''){
      $("#re_start").show();
      $("#ing_retime").hide();
      $("#submit_invested").attr('disabled','disabled');
      $("#submit_invested").val('待开放');
  }else{    
      $("#submit_invested").removeAttr('disabled');
      $("#ing_retime").show();
      $("#re_start").hide();
  }    
</script>
<!---->
<div class="de_code" id="invested_loading" style=" display:none;">
  <div class="row_pd" >
    <p class="row_to row_txt">请勿关闭或刷新...请耐心等待...</p>
    <!--<p class="row_to"><img src="__PUBLIC__/images/aabb.gif" /></p>-->
  </div>
</div>

<!--弹出验证码-->
<div class="de_code" id="vrf">
  <div class="row_pd code_input">
    <h3>请输入验证码</h3>
    <span class="code_close"><img src="__PUBLIC__/images/close.png" /></span>
    <p>
      <span><input type="text" value="输入验证码" onclick="if(this.defaultValue==this.value){this.value='';}" onblur="if(this.value==''){this.value=this.defaultValue;}" id="verify" class="inp_code" /></span>
      <span>
        <a href="javascript:void(change_code(this));">
             <img src="<?php echo U("Member/verify",'','');?>" id="code"/>
         </a>
      </span>
      <span><input type="button" id="submit_verify" value="确定" class="code_sure" /></span>
    </p>
  </div>
</div>
<script type="text/javascript">

  
/**
 * checkDoAdd   处理投资表单
 */
function checkDoAdd(){  
    //数字判断，表单判断
    if($.trim($money.val()) == 0 || !number_reg.test($.trim($money.val()))){
      alert('请填写正确的投资金额');$money.val('');$money.focus();return false;
    }  
    $is_login = "<?php echo (session('user_id')); ?>";
    if($is_login == ''){
      alert('请先登录');location.href='<?php echo U("user/Login");?>';return false;
    }
    //判断最低可投金额
    $mod = parseFloat($.trim($money.val())) % parseFloat($min_loan_money);//输入金额是否能整除最小投资额    
    if($mod!=0){
      alert('投资金额需为<?php echo ($loan["min_loan_money"]); ?>或<?php echo ($loan["min_loan_money"]); ?>的倍数');return false;                      
    }          
    //判断最高可投金额     
    if($money.val() > $max_loan_money){
      alert('投资金额不能大于最高投资金额<?php echo ($max_loan_money); ?>');return false;
    }
    //判断剩余借款总额
    if($money.val() > $loan_over){
        alert('输入错误，投资总额'+$money.val()+'不可超过可投金额!'+$loan_over);return false;
    }
    //判断比对当前可用余额
    // if($money.val() > $user_money){
    //   alert('投资金额不能大于您当前可用余额'+$user_money);return false;
    // }
    //通过上述判断，显示验证码层
    $("#vrf").fadeIn();
    return false;      
}

/**
 * show_expect_money   显示预期收益气泡
 */
function show_expect_money(){
    $new_loan_rate = $loan_rate;
    if($("#rate_id").attr('checked')){
      $new_loan_rate = $new_loan_rate + parseFloat($("#rate_id").attr('data-value')/100);
    }     
    <?php if($loan['loan_deadline_type'] == '1'): ?>//预期收益 = 投资金额 * 年利率 / 365 * 借款期限
    var expect_money = parseFloat($money.val()) * $new_loan_rate / 365 * $loan_deadline;
    <?php else: ?>
    //预期收益 = 投资金额 * 年利率 / 12 * 借款期限
    var expect_money = parseFloat($money.val()) * $new_loan_rate / 12 * $loan_deadline;<?php endif; ?>
    expect_money = Math.round(expect_money * 100)/100;//给用户显示收益  
    temp_money = parseFloat($money.val() * <?php echo (C("bonus_rate")); ?>);//可用奖励金额
    //若当前剩余奖励金额足以支付可用奖励金额,那么只使用可用的奖励金额
    if(temp_money < parseFloat(<?php echo ($user_info["bonus_money"]); ?>)){
      temp_money = temp_money;                                                         
    }else{
    //若剩余奖励金额不足可用奖励金额则使用全部奖励金额
      temp_money = parseFloat(<?php echo ($user_info["bonus_money"]); ?>);
    }
    <?php if(empty($user_info["id"])): ?>temp_money = 0;
    <?php else: endif; ?>
    use_money = parseFloat($money.val() - temp_money);
    $("#expect_money").text(expect_money);
    $("#temp_money").text(temp_money);//输出当前可用奖励金额
    $("#use_money").text(use_money);//输出使用余额
    $(".suspension").fadeIn();      
}

//更换验证码
verifyURL = '<?php echo U("Member/verify",'','');?>';
function change_code(obj){
    $("#code").attr("src",verifyURL+'/'+Math.random());
    return false;
}  

$do_add_form = $("#do_add_form");//投资表单
$verify = $("#verify");//验证码
$money = $("#money");//当前投资金额
$min_loan_money = <?php echo ($loan["min_loan_money"]); ?>;//最低投资金额
$max_loan_money = <?php echo ($loan["max_loan_money"]); ?>;//最高投资金额
$loan_over = parseFloat($("#loan_over").text());//可投余额          
// $user_money = parseFloat($("#user_money").text());//用户当前可用余额
$loan_rate = (parseFloat("<?php echo ($loan["loan_rate"]); ?>") + parseFloat("<?php echo ($loan["loan_add_rate"]); ?>")) / 100;//年利率      
$loan_deadline = parseFloat("<?php echo ($loan["loan_deadline"]); ?>");//借款期限（月）          
var number_reg = /^\d+$/;
//验证码提交
$verify.blur(function() {        

   $rate_id = '';//初始化加息券ID
   //勾选加息券ID传值
   if($("#rate_id").attr('checked')){
     $rate_id = $("#rate_id").val();
   }         
   check_verify();   
});
$verify.keyup(function(){
  check_verify();
})
function check_verify(){
    if($.trim($verify.val()) != '' && $.trim($verify.val()) != '输入验证码' && $verify.val().length == 4){    
       //后台比对验证码，若通过则正式提交投资。否则弹出提示
       $.ajax({
         url: "<?php echo U('Deal/DoVerify');?>",
         type: 'POST',
         dataType: 'json',
         data: "verify="+$verify.val(),
         success:function(msg){
            if(msg.status == '1'){            
              $("#submit_verify").css("background","#e43343");
              $("#submit_verify").removeAttr('disabled');              
             // location.href= $submit_url+"?lid="+$("#lid").val()+"&money="+$money.val()+"&rate_id="+$rate_id;          
            }else{
              //alert(msg.info);
              $("#submit_verify").css('background','#CCC');$("#submit_verify").attr('disabled',true);return false;
            }
         }
        })
   }else{
      $("#submit_verify").css('background','#CCC');$("#submit_verify").attr('disabled',true);return false;
   }
}
$("#submit_verify").click(function(event) {    
   //$submit_url+"lid="+$("#lid").val()+"&money="+$money.val()+"&rate_id="+$rate_id + "&verify=" + $verify.val());return false;    
    $.ajax({
      url: "<?php echo U('Deal/DoAddInvested');?>",
      type: 'POST',
      dataType: 'json',
      data:"lid="+$("#lid").val()+"&money="+$money.val()+"&rate_id="+$rate_id + "&verify=" + $verify.val() + "&tpid=<?php echo (session('user_id')); ?>",
      beforeSend:function(){
        $("#invested_loading").show();//显示loading
      },                         
      success:function(msg){        
           $("#invested_loading").hide();         
           if(msg.status == '0'){
             alert(msg.info);
             if(msg.url != null){
                location.href = msg.url;
             }
             return false;
           }else{
             alert(msg.info);location.href = msg.url;
             return false;
           }   
      },
      error:function(e){
          alert('网络繁忙');return false;
      }
    })
});
//关闭验证码层
$(".code_close").click(function(){
    $("#vrf").fadeOut();
});

//同步显示预期收益
$(".tz_amount").keyup(function(){
 if($.trim($money.val()) != 0 && number_reg.test($.trim($money.val()))){       
    show_expect_money();//显示预期收益气泡
 }              
}).blur(function(){
 $(".suspension").fadeOut();             
});  
//投资表单判断
$("#submit_invested").click(function(){          
  checkDoAdd();return false;
})
</script>
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

<!--end-->
</body>
</html>