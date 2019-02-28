<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
	<title>Error</title>
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
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.8.3.min.js"></script>
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
   <div class="mid_center">
      <div class="prompt_error">
         <p ><?php echo($error); ?></p>
	     <div>页面将在 <span id="wait"><?php echo($waitSecond); ?></span> 秒后<a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 或 <a href="/index.php">返回首页</a>返回</div>
      </div>
      
   </div>        
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
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
    var time = --wait.innerHTML;
    if(time <= 0) {
        location.href = href;
        clearInterval(interval);
    };
}, 1000);
})();
</script>