<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible"content="IE=8"><!--以IE8模式渲染--> 
<title>会员注册-<?php echo (C("web_title")); ?></title>
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
_CWiQ.push(['_trackPdmp', '访问过注册页', 1]); // 访客分类名称是给访客贴的标签，可以自定义，例如： 访问过官网、注册用户等。
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
</head>
<link href="__PUBLIC__/style/base.css" type="text/css" rel="stylesheet" />
<link href="__PUBLIC__/style/register.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="__PUBLIC__/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/prefixfree.min.js"></script>
<!--注册协议-->
<script type="text/javascript" src="__PUBLIC__/js/jquery.jmpopups-0.5.1.js"></script>
<script type="text/javascript">
				$.setupJMPopups({
					screenLockerBackground: "#000000",
					screenLockerOpacity: "0.7"
				});

				function openStaticPopup() {
					$.openPopupLayer({
						name: "myStaticPopup",
						width: 820,
						target: "myHiddenDiv_1"
					});
				}

//ajax验证图形验证码
function check_verify(obj){
  if($(obj).val() != ''){
    /*验证用户名是否存在*/
    $.ajax({
      url: '<?php echo U("user/CheckVerify");?>',
      type: 'POST',
      data: "verify="+$(obj).val(),
      success:function(data){
        if(data.status == '1'){
          $("#send_code").attr('data-status','complate');
          $("#send_code_voice").attr('data-status','complate');
          return false;
        }else{
          $("#send_code").attr('data-status','none');
          $("#send_code_voice").attr('data-status','none');
          return false;                  
        }
      }          
    })          
  }
}            
</script>
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


<div class="reg_cen">
   <div class="reg_st">免费注册金投资账户</div>
   <div class="reg_bot">
   <form action="<?php echo U('user/DoRegister');?>" method="post">     
      <table cellpadding="0" cellspacing="0" border="0" width="85%" class="reg_bd">
         <tr>
            <td width="24%" height="60" align="right">用户名：</td>
            <td colspan="2" class="fl">
            <input name="user_name" type="text" class="login_txt" id="user_name" value="" maxlength="20" />  
            <font color="#FF0000">*</font>
            <span class="prompt" id="user_name_msg"></span>
            </td>
         </tr>
         <tr>
            <td width="24%" height="60" align="right">手机号：</td>
            <td colspan="2" class="fl"><input name="phone" type="text" class="login_phone" id="phone" value="" />
            <font color="#FF0000">*</font>
            <span class="prompt" id="phone_msg"></span>
         </td>
         </tr>
         <tr>
            <td height="60" align="right">密码：</td>
            <td colspan="2" class="fl"><input name="user_pwd" type="password" maxlength="20"  class="login_pwd" id="user_pwd" />
            <font color="#FF0000">*</font>
            <span class="prompt" id="user_pwd_msg"></span>
            </td>
         </tr>
         <tr>
            <td height="60" align="right">确认密码：</td>
            <td colspan="2" class="fl"><input name="re_pwd" type="password" maxlength="20"  class="login_pwd" id="re_pwd" />              
            <font color="#FF0000">*</font>
            <span class="prompt" id="re_pwd_msg"></span>
         </td>
         </tr>
         <tr>
           <td height="60" align="right">推荐人：</td>
           <td colspan="2" class="fl"><input name="pid" value="<?php echo ($pid); ?>" type="text" class="login_txt" />              
           <span class="prompt" id="pid_msg"></span>
           </td>
        </tr>
        <tr>
           <td>&nbsp;;</td>
           <td colspan="" class="fl" style=" font-size:12px; color:#999999;">请输入推荐人的ID号,在会员中心的邀请好友里获取相应ID</td>
        </tr>
         <tr>
            <td height="60" align="right">验证码：</td>
            <td width="60%" class="fl"><input name="verify" onblur="check_verify(this)" type="text" class="login_code" id="verify" style=" float:left;" value="" /> 
            <span class="yzm_code">
            <a href="javascript:void(change_code(this));">
                 <img src="<?php echo U("user/verify",'','');?>" id="code"/>
                 <!-- <img src="/code.php" id="code"/> -->
            </a>
            </span>
            <span class="prompt"></span>
            </td>
            <td width="16%" class="fl">&nbsp; </td>
         </tr>
         <tr>
            <td height="60" align="right">手机验证码：</td>
            <td colspan="2" class="fl">
                <input type="text" value="请输验证码" onclick="if(this.defaultValue==this.value){this.value='';}" onblur="if(this.value==''){this.value=this.defaultValue;}"  class="login_code" name="phone_code" style=" float:left;" />
                <input type="button" value="获取短信验证码" id="send_code" onclick="sendCode(this,1)"  class="hq_yz" />
                <input type="button" value="获取语音验证码" id="send_code_voice" onclick="sendCode(this,2)"  class="hq_yz yz_voice" />
            </td>
         </tr>
         <tr>
            <td height="50">&nbsp;</td>
            <td colspan="2" class="fl">
              <input name="ido" type="checkbox" id="ido" checked="checked" />
              <label for="ido">我已经阅读并同意<a href="javascript:;" onclick="openStaticPopup()" title="金投资服务条款协议" class="reg_xy">《金投资服务条款协议》</a></label>
              <div id="myHiddenDiv_1" style="display:none;">
                  <div class="popup_1">
                     <div class="popup-header_1">
                         <h2>金投资网站服务协议 服务条款</h2>
                     </div>
                     <div class="popup-body_1">
                         <strong>请仔细阅读本协议</strong>
                      <div class="con_1">
                       <p>本协议双方为吉林省金湧博发投资管理有限公司（金投资）与金投资投资人，本协议具有合同效力。本协议的内容包括以下全部条款以及本网站已经发布的及将来可能发布的与出借人用户有关的各项规则，该等规则均为本协议不可分割的一部分，与以下所列条款具有同等法律效力。本网站有权根据需要修改本协议的内容。如以下条款或本网站各项规则有任何变更，本网站将在网站上刊载公告。经修订的相关条款和规则一经公告，即于公告规定的特定生效日期自动生效。</p>
                    　　<p>投资人只要勾选位于注册页面下方的“我同意金投资注册协议”选项并按照本网站的流程成功注册后，本协议即产生法律效力。投资人不得以未签署书面协议为由否认本协议的效力。本协议是由投资人与本网站共同签订的，适用于投资人在本网站的全部活动。在投资人注册成为用户时，投资人已经阅读、理解并接受本协议的全部条款及各类规则，如有违反而导致任何法律后果的发生，投资人将以自己的名义独立承担所有相应的法律责任。</p>
                    　　<p>本协议不涉及投资人与本网站的其他用户之间因网上交易而产生的法律关系及法律纠纷。</p><br />
                    <b>一、投资人须知</b>
                    <p>1、作为本网站投资人用户，投资人必须是中华人民共和国公民，年龄在18周岁以上，且具有完全的民事权利能力及民事行为能力的自然人。如不具备上述资格，投资人应立即停止在本网站的注册程序、停止使用本网站服务，本网站有权随时终止投资人的注册进程及本网站服务，投资人应对投资人的注册给本网站带来的损失承担全额赔偿责任，且投资人的监护人（如投资人为限制民事行为能力的自然人）或投资人的实际控制人（如投资人为实体）应承担连带责任。</p>
                    <p>2、在注册和使用本网站服务的所有期间，投资人应提供投资人自身的真实资料和信息，并保证自投资人注册之时起至投资人使用本网站服务的所有期间，其所提交的所有资料和信息（包括但不限于电子邮件地址、联系电话、联系地址、邮政编码、个人身份信息、征信信息等）真实、准确、完整，且是最新的。</p>
                    <p>3、投资人不得利用本网站或本网站服务从事任何不符合中国法律法规或侵犯他人权益的活动。本网站在发现投资人从事该等活动时，有权不经通知而立即停止投资人对本网站的全部或部分功能的使用。</p>
                    <p>4、在使用本网站提供的任何服务（包括但不限于站内信服务、群组服务、论坛服务或其他电子邮件转发服务）的过程中，投资人不得发送、公布或展示任何垃圾邮件、信息或其他可能违反中国法律法规及本协议的内容。本网站在发现投资人从事该等活动或发布该等内容时，有权不经投资人同意而删除该等内容，并有权不经通知而立即暂停或停止投资人对本网站的全部或部分功能的使用。</p>
                    <p>5、投资人同意本网站在业务运营中收集和储存投资人的用户信息，包括但不限于投资人自行提供的资料和信息，以及本网站自行收集、取得的投资人在本网站的交易记录和使用信息等。本网站收集和储存投资人的用户信息的主要目的在于提高为投资人提供服务的效率和质量。</p>
                    <p>6、本网站将采取一切必要措施以保护投资人的用户信息及个人隐私，包括建立相应的信息保护制度和机制、设备等。</p>
                    <b>二、充值及提现须知</b><br />
                    <b>1、充值须知</b>
                    <p>（1）充值金额需大于100元人民币</p>
                    <p>（2）充值手续费免费</p>
                    <b>2、提现须知</b>
                    <p>（1）提现金额需大于100元人民币</p>
                    <p>（2）提现时绑定的持卡人姓名需与个人资料绑定的姓名一致。</p>
                    <p>（3）提现手续费每笔5元起，工作日16:00前提交提现申请的，当天审核；16:00之后提交申请的，下个工作日审核。</p>
                    <p>（4）审核通过后3个工作日内（不含双休及法定节假），将资金转入您在网站认证的银行卡账号。</p>
                    <b>3、投标须知</b>
                    <p>（1）投资人在期满之前不得私自转让相应债权，期满后债权自动回收，投资人的本金及利息返回投资人充值账户中。</p>
                    <b>三、本网站提供的服务、收费以及保障</b><br /><br />
                    <b>1、本网站通过自身的渠道和方式等向投资人提供以下服务：</b>
                    <p>(1)为投资人寻找合适的借款人并向投资人以投标形式转让债权；</p>
                    <p>(2)负责投资人投资资金的安全流出和投资资金本息的安全流入；</p>
                    <p>(3)信用咨询及贷后管理服务；</p>
                    <b>2、管理费</b>
                    <p>因为金投资引进国有第三方支付平台，所有资金均通过第三方平台支付，将扣取利息的10%作为账户管理费。</p><b>3、贷款的回收。</b><p>投资人同意借款人将应归还的贷款先汇入本网站指定的监管帐户，并由本网站根据借款协议的约定和投资人的指定将款项分别划至涉及该借款的全部投资人的账户。若被投资人所归还的贷款不足以归还全部应还贷款的，投资人同意本网站按照各投资人所贷款款项的比例将还款分别汇入各投资人的账户中。</p><b>4、贷款保障。</b><p>当投资人出借的贷款未能按时偿付时，金投资将启动风险保障金首先垫付VIP投资人投资的本金和利息。</p>
                    <b>5、提现费用。</b>
                    <p>(1)提现金额100元~50万元，手续费5元/笔；</p>
                    <p>(2)提现手续费每笔5元起，工作日16:00前提交提现申请的，当天审核；16:00之后提交申请的，下个工作日审核。</p>
                    <p>(3)审核通过后3个工作日内（不含双休及法定节假），将资金转入您在网站认证的银行卡账号。</p>
                    <b>6、站内债权转让。</b>
                    <p>(1)债权持有天数满30天及以上，免费；</p>
                    <p>(2)债权持有天数不足30天，收取本金的0.5%。</p>
                    <b>三、投资人竞标方式</b>
                    <p>1、借款信息的竞标。投资人在本网站可以对被投资人发布的借款信息进行竞标。借款信息是被投资人在本网站上发布的按照一定利率和期限等要求借入一定金额款项的列表。投资人可以根据借款信息按照投资人的意愿竞标。
                    投资人竞标时，视为向本网站发出不可撤销的授权指令，授权本网站委托相应的第三方支付机构及/或相关商业银行（“监管银行”）以本网站名义开立银行监管账户（“监管账户”），在监管账户中投资人金投资用户名项下虚拟账户（“乙方金投资账户”）中冻结相应的金额。一旦投资人确认竞标，则投资人无权撤销或者收回竞标或者减少竞标数额。</p>
                    <p>2、多个竞标。 一个借款信息可能有多个竞标匹配，投资人可能是多个成功竞标人中的一个投资人，在这种情况下，投资人的贷款将与其他投资人的贷款是相互独立的。</p>
                    <b>四、本网站的权利与义务</b>
                    <b>1、本网站有权核实信息及取消借贷</b>
                    <p>（1）本网站保留核实借款人、投资人提供信息的准确性的权利。本网站有权在任何时候依据自己的判断决定是否终止投资人使用本网站的权利。</p>
                    <p>（2）在本网站向被投资人划转投资人的资金前，本网站有权合理判断借款信息、竞标中是否包含了错误的信息（错误信息包括但不限于非故意提供的错误信息、由本网站的过失引起的错误信息、以及因被投资人的信息变更引起的错误信息），并且有权判断借款信息和竞标的发布是否违反了法律、法规和本网站规则，并且有权与被投资人进行面谈以判断被投资人的真实性，在此基础上，本网站有权拒绝该笔交易的完成，并删除与此有关的任何内容。</p>
                    <p>（3）本网站有权在任何时候根据自己的判断延迟交易的完成，以便核实有关信息或者核实该交易中是否存在违反法律、法规和本网站规则的情形。</p>
                    <p>（4）免责条款无论投资人通过本网站形成的借贷关系是否适用本网站的回款风险保证金计划或者是否存在第三方担保，本网站及本网站的关联方董事、员工、代理人均不以任何明示或默示的方式对投资人通过本网站平台形成的借贷交易及其履行提供任何担保。</p>
                    <p>（5）禁止行为投资人同意不做如下行为：向任何人声称是本网站的代表、代理人、雇员；以同意竞标为由向本网站被投资人收费或试图收费、获取奖励、额外利息、回扣或其他有价值的物品；骚扰其他投资人及其经营实体、网站的客户、职员、合伙人、亲属、代理人等；从事任何需要许可证才能开展的业务；与被投资人串通进行虚假的借款和出借；使用非法资金进行出借或在本网站洗钱；其他违反现行法律、法规的行为。</p>
                    <b>4、本网站要求的其他条款</b>
                    <p>（1）修改本协议的条款的权利. 本网站有权修改本协议的任一条款。</p>
                    <p>（2）不保证. 除非本协议有明确的表述，任何一方均不向对方做出任何保证和陈述，包括但不限于明示的或暗示的。</p>
                    <p>（3）本网站投资人使用限制投资人只能以自己的名义和自己使用本网站进行竞标，而不得代替他人进行操作。但是本网站有权根据自己的判断限制或者阻止投资人进入本网站。</p>
                    <p>（4）责任限制. 除本协议约定外，即使已就损失的可能性告知对方的情况下，任何一方均不对另一方的利润损失承担赔偿责任。</p>
                    <p>（5）服务终止. 本网站有权根据自己的判断，在确定投资人违反本协议的情况下，在事先通知投资人的情况下终止本协议且无须说明理由。一旦投资人的投资人身份被终止，则投资人已作出的竞标均立即终止并删除，但是投资人已向被投资人发放的贷款及完成的交易仍然具有法律效力。服务终止后，本网站同意将服务终止前投资人已经冻结尚未投放的资金一次性返还给投资人，同时，对于已经投放的资金，本网站承诺继续按照项目还款进度向投资人的银行账户返还本金和利息。</p>
                    <p>（6）损失赔偿责任. 投资人同意不损害本网站及其运营的法定代表人、管理人员、雇员、股东和代理机构的利益，并依法赔偿因投资人的违约行为引起的由本网站向第三方承担责任产生的经济损失。</p>
                    <b>五、其他附加条款</b>
                    <p>1、未经本网站事先书面许可，投资人无权将本协议项下的权利转让给任何第三方，否则该转让行为无效。</p>
                    <p> 2、本协议之效力、解释、变更、执行与争议解决均适用中华人民共和国法律，因本协议产生之争议，双方同意将争议提交至长春市具有管辖权的人民法院，通过司法程序予以解决。</p>
                    <b>六、违约责任</b>
                    <p>如果您违反了您在协议中所作的陈述、保证、承诺或任何其他义务，致使本网站或本网站的股东、实际控制人、员工承受任何损失，您应向受损失的一方做出全额赔偿。</p>
                    <b>七、适用法律和管辖</b>
                    <p>本协议适用中华人民共和国法律，与本协议的解释和履行有关的争议应由长春仲裁委员会按照其仲裁规则进行仲裁。</p>
                    <b>八、终止</b>
                    <p>除非本网站终止本协议或者您申请终止本协议及注销相应用户账户且经本网站同意，否则本协议始终有效。在您违反了本协议、相关规则，或在相关法律法规、政府部门的要求下，本网站有权通过电子邮件告知方式终止本协议、关闭您的账户或者限制您使用本网站。</p>
                    <b>九、条款的独立性</b>
                    <p>若本协议的部分条款被认定为无效或者无法实施时，本协议中的其他条款仍然有效。</p>
                    
                    <p>本协议内容最终解释权规金投资网站所有。</p>            
               

                                                    </div>
                                                </div>
                                                <a href="javascript:;" class="close_1" onclick="$.closePopupLayer('myStaticPopup')" title="关闭窗口" >阅读完毕，关闭窗口</a>
                                            </div>
                                        </div>
                                        <div id="fade" class="black_overlay"></div>
            </td>
         </tr>
         <tr>
            <td height="50">&nbsp;</td>
            <td colspan="2" class="fl"><input type="submit" value="立即注册" class="login_sub" id="submit_reg"/></td>
         </tr>
      </table>
    </form>
   </div>
</div>
 <script type="text/javascript">
  verifyURL = '<?php echo U("user/verify","","");?>';
  // verifyURL = '/code.php';
  function change_code(obj){
      $("#code").attr("src",verifyURL+'/'+Math.random());
      return false;
  }  
  $verify = $("input[name=verify]");
  $phone_code = $("input[name=phone_code]");
  $ido = $("input[name=ido]");    
  $user_name_msg = $("#user_name_msg");    
  $phone_msg = $("#phone_msg");
  $user_pwd_msg = $("#user_pwd_msg");
  $re_pwd_msg = $("#re_pwd_msg");
  $mail_msg = $("#mail_msg");
  $pid_msg = $("#pid_msg");
  $mobile = $("#phone");

  $("form :input").blur(function(){
      
      //用户名验证
      if($(this).is("input[name=user_name]")){      
        var ch_reg = /^[u4E00-u9FA5{u-z}]+$/;//筛选包含汉字的字符                      
        $user_name_msg.show();     
        if(this.value == ''){
            $user_name_msg.text('用户名不能为空');$(this).attr('verify-status','err');        
        }else if(!ch_reg.test(this.value)){
            $user_name_msg.text('不可输入汉字');$(this).attr('verify-status','err');
        }else if(this.value.length < 6 || this.value.length > 18){
            $user_name_msg.text('用户名长度不能大于18位，不可小于6位字符');$(this).attr('verify-status','err');
        }else{          
          /*验证用户名是否存在*/
          $.ajax({
            url: '<?php echo U("user/VerifyUserExist");?>',
            type: 'POST',
            data: "type=user_name&v="+this.value,
            success:function(data){
              if(data.status == '0'){                            
                $("#user_name").attr('verify-status','err');$user_name_msg.text('用户名已存在');
              }else{                
                $user_name_msg.hide();$("#user_name").removeAttr("verify-status");
              }
            }          
          })
          return false;
        }                 
       }

       //手机号验证
       var phone_reg = /^(1[3|4|5|7|8]\d{9})$/;//手机号正则过滤           
       if($(this).is("input[name=phone]")){        
        $phone_msg.show();  
      
        //验证手机号是否合法
        if(!phone_reg.test(this.value) || this.value.length != 11){
          $phone_msg.text('请输入正确的手机号码');$(this).attr('verify-status','err');
        }else{
          /*验证手机号是否存在*/
          $.ajax({
            url: '<?php echo U("user/VerifyUserExist");?>',
            type: 'POST',
            data: "type=phone&v="+this.value,
            success:function(data){
              if(data.status == '0'){            
                $phone_msg.text('该手机号码已被使用');
                $("#phone").attr('verify-status','err');             
              }else{
                $phone_msg.hide();$("#phone").removeAttr("verify-status");
              }
            }          
          })
          return false;
        }  
       }

       //密码验证
       if($(this).is("input[name=user_pwd]")){   
          $user_pwd_msg.show();     
          if(this.value.length < 6 || this.value.length > 18 || this.value == ''){
               $user_pwd_msg.text('密码不能为空，且长度控制在6-18位之间');$(this).attr('verify-status','err');
          }else{
            $user_pwd_msg.hide();$(this).removeAttr("verify-status");return false;
          }
       }

       //确认密码验证
       if($(this).is("input[name=re_pwd]")){   
          $re_pwd_msg.show();     
          //验证密码长度
          if(this.value != $.trim($("#user_pwd").val())){
             $re_pwd_msg.text('两次密码输入不一致，请重新输入！');$(this).attr('verify-status','err');//验证确认密码正确性                    
          }else{
            $re_pwd_msg.hide();$(this).removeAttr("verify-status");return false;
          }
       }



      //推荐人验证
      if($(this).is("input[name=pid]")){              
        $pid_msg.show();             
          /*验证用户名是否存在*/
          $.ajax({
            url: '<?php echo U("user/FindParent");?>',
            type: 'POST',
            data: "pid="+this.value,
            success:function(data){
              if(data.status == '0'){                                        
                $pid_msg.text(data.info);
              }else{                
                $pid_msg.hide();
              }
            }          
          })
          return false;                
       }

       // //邮箱验证
       // if($(this).is("input[name=email]")){   
       //    $mail_msg.show();     
       //    var reg = /^(\w)+(\.\w+)*@(\w)+((\.\w+)+)$/; //邮箱正则过滤
       //    //验证邮箱格式
       //    if(!reg.test(this.value) || this.value == ''){
       //       $mail_msg.text('邮箱输入不正确');$(this).attr('verify-status','err');             
       //    }else{
       //      /*验证邮箱是否存在*/
       //      $.ajax({
       //        url: '<?php echo U("user/VerifyUserExist");?>',
       //        type: 'POST',
       //        data: "type=mail&v="+this.value,
       //        success:function(data){                          
       //          if(data.status == '0'){                            
       //            $mail_msg.text('该邮箱已被使用');$("#email").attr('verify-status','err');             
       //          }else{
       //            $mail_msg.hide();$("#email").removeAttr("verify-status");return false;
       //          }
       //        }          
       //      })
       //    return false;
       //  } 
       // }

  }).keyup(function(){
      $(this).triggerHandler("blur");
  })

$("#submit_reg").click(function(){
    //验证手机验证码长度
    if($.trim($phone_code.val()).length != 4 ){
      alert('验证码不正确！');$phone_code.focus();return false;                    
    }    
    //验证长度
    if($.trim($verify.val()).length != 4 ){
      alert('验证码不正确！');$verify.focus();return false;                    
    }  
    //验证是否同意协议
    if(!$ido.is(":checked")){
        alert('请同意注册协议！');           
        return false;               
    }    
    if($("input[verify-status=err]").length){return false;}
  _CWiQ.push(['_trackReg', 1]);
})   


/**
 * 发送短信倒计时
 * @param   obj   元素对象
 * @param   stime 倒计时时间总数
 * @return        定时器
 */
function sendMobileTime(obj, stime, type) {      
    setTimeout(function (){
        var _stime = stime - 1;
        if (_stime) {
            $(obj).val(_stime-1 + '秒后可重新获取');                            
            sendMobileTime(obj, _stime, type);
            $("#send_code").attr("onClick","return false");//倒计时中停止点击事件
        } else {
			if(type == 1){
				txt = '短信';
			}else{
				txt = '语音';
			}
            $(obj).val('免费获取'+txt+'验证码');   //倒计时停止时将元素的文本初始化
            $("#send_code").attr("onClick","sendCode(this)");//倒计时停止加回点击事件sendCode方法
        }
    }, 1000);
}   


/**
 * 单击发送验证码时候的操作     
 */
  function sendCode(obj,type){              
    if($phone_msg.attr("verify-status") == "err" || $.trim($mobile.val()) == ''){
        alert('请输入正确的手机号码');
        $mobile.focus();
        return false;
    }      
    if($(obj).attr('data-status') != 'complate'){
        alert('验证码不正确');$("#verify").focus();return false;
    }           

    $.ajax({
       dataType: "JSON",
       url: "<?php echo U('user/sendCode');?>",
       data: "phone="+$mobile.val()+"&type="+type+"&temp_str=注册验证码",
       success: function(msg){          
          //msg = eval("("+msg.responseText+")");                             
          if(msg.status == 0){                  
              alert(msg.info);return false;
          }else{
              alert('若未及时收到验证信息，请检查是否被手机安全工具拦截');
              sendMobileTime(obj,61,type);
          }
       }
    }); 
  } 

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
</body>
</html>