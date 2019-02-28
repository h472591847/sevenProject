<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>金投资新春钜现_<?php echo (C("web_title")); ?></title>
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
_CWiQ.push(['_trackPdmp', '访问过专题页', 1]); // 访客分类名称是给访客贴的标签，可以自定义，例如： 访问过官网、注册用户等。
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
<link href="__PUBLIC__/style/single.css" type="text/css" rel="stylesheet" />
<link href="__PUBLIC__/style/animate.css" rel="stylesheet" type="text/css">
<style type="text/css"> 
.demo{width:100%; height:800px; position:relative; margin:50px auto}
#disk{width:100%; height:800px; background:url(__PUBLIC__/images/lucky_wheel.png) no-repeat;}
#start{width:163px; height:320px; position:absolute; top:105px; left:182px;}
#start img{cursor:pointer}
</style>
<script type="text/javascript" src="__PUBLIC__/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/spring.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jQueryRotate.2.2.js"></script>
<script type="text/javascript" src="__PUBLIC__/js/jquery.easing.min.js"></script>
<script type="text/javascript"> 
$(function(){
   $("#startbtn").click(function(){
    lottery();
   });
   
   /*--------address-------*/
   $(".add_close").click(function(){
      $(".address_bg").fadeOut();
   });
   
});

/**
 * lottery  大转盘抽奖动作过程
 * @return json
 */
function lottery(){
    <?php if($_SESSION['user_id']== ''): ?>$login_html = "您还尚未登录，请先<a href=<?php echo U('user/Login');?> class=result_jump>前往登录</a>";
      $(".result").find('p').html($login_html);
      $(".result").show();
      return false;<?php endif; ?>
  $.ajax({
    type: 'POST',
    url: '<?php echo U("Lottery/DoLottery");?>',
        data: "type=rotate",
    dataType: 'json',
    cache: false,
    error: function(){
      alert('出错了！');
      return false;
    },
    success:function(json){ 
            //若错误，弹出提示
            if(json.status == '0'){              
                if(json.info){
                  $(".result").find('p').html(json.info);
                  $(".result").show();return false;
                    //alert(json.info);return false;
                }
            }else if(json.status == '1'){
              //$("#startbtn").unbind('click').css("cursor","default");
                var a = json.angle;
                var p = json.prize;
                $("#startbtn").rotate({
                  duration:3000,
                  angle: 0,
                  animateTo:1800+a,
                  easing: $.easing.easeOutSine,
                  callback: function(){
                    if(json.lt_id == '6'){
                      var con = confirm('恭喜你，中得'+p+'\n还要再来一次吗？');
                      if(con){
                        lottery();
                      }else{
                        return false;
                      }
                    }else{
                      alert('恭喜你中得【'+p+'】\n单击确定填写收货地址');
                      $("#record_id").val(json.record_id);
                      $("#address_layer").show();
                      return false;
                    }
                  }
              });
            }
    }
  });
}

function autoScroll(obj){  
	$(obj).find("ul").animate({  
		marginTop : "-39px"  
	},500,function(){  
		$(this).css({marginTop : "0px"}).find("li:first").appendTo(this);  
	})  
}  
$(function(){  
	setInterval('autoScroll(".maquee")',3000);
		  
}) 

/**
 * check_save    提交收货地址表单过滤
 */
function check_save(obj){
  // //手机号验证
  // var phone_reg = /^(1[3|4|5|7|8]\d{9})$/;//手机号正则过滤             
  if($.trim($("#real_name").val()) == ''){    
     alert('请填写收货人姓名');$("#real_name").focus();return false;
  }
  if($.trim($("#address").val()) == ''){
     alert('请填写详细地址');$("#address").focus();return false;  
  }
  if($.trim($("#phone").val()) == ''){
     alert('请填写联系方式');$("#phone").focus();return false;   
  }
}
</script>

<body>
<div class="ny_top">
   <div class="ny_top_1">
      <h1><img src="__PUBLIC__/images/ny_logo.png" alt="<?php echo (C("web_title")); ?>"></h1>
      <ul>
         <li class="on"><a href="<?php echo U('Index/index');?>" title="金投资_首页">首页</a></li>
         <li><a href="<?php echo U('Deal/index');?>" title="金投资_我要投资">我要投资</a></li>
         <!--<li><a href="<?php echo U('LoanApply/index');?>" title="金投资_我要贷款">我要贷款</a></li>-->
         <li><a href="<?php echo U('Security/index');?>" title="金投资_安全保障">安全保障</a></li>
         <li><a href="<?php echo U('Partner/Join');?>" title="金投资_合作伙伴">合作伙伴</a></li>
         <li><a href="<?php echo U('About/index');?>" title="金投资_关于我们">关于我们</a></li>
         <li><a href="<?php echo U('Ask/index');?>" title="金投资_问答专区">常见问题</a></li>
         <li><a href="<?php echo U('Guide/index');?>" title="金投资_新手指引">新手指引</a></li>
         <?php if($_SESSION['user_id']== ''): ?><li class="ny_register"><a href="<?php echo U('user/Login');?>" title="会员登录">登录</a></li>
             <li class="ny_register"><a href="<?php echo U('user/register');?>" title="注册">注册</a></li>
         <?php else: ?>
             <li class="ny_register"><a href="<?php echo U('Member/Index');?>" title="个人中心">个人中心</a></li><?php endif; ?>
         
      </ul>
   </div>
</div>
<div class="newyear_one">
   <div class="light animated fadeIn"></div>
</div>
<div class="newyear_two"></div>
<div class="ny_center">
   <div class="process_1">
      <div class="ny_middle pro_mid_1">
         <p class="hb_one"></p>
      </div>
   </div>
   <div class="process_2">
      <div class="ny_middle">
         <p class="hb_two"></p>
      </div>
   </div>
   <div class="process_3">
      <div class="ny_middle">
         <p class="rockets"></p>
      </div>
   </div>
   <div class="process_4">
   <div class="ny_middle">
     <div class="demo">
          <div id="disk"></div>
          <div id="start"><img src="__PUBLIC__/images/start.png" id="startbtn"></div>
          <div class="result">
               <p>您还没有抽奖机会，请先<a href="#" class="result_jump">投资</a></p>
               <a href="javascript:" id="resultBtn" title="关闭"></a>
          </div>    
          <div class="maquee">
            <ul>
            <?php if(is_array($record_ar)): foreach($record_ar as $key=>$v): ?><li>
                 <span><?php echo ($v["user_name"]); ?></span>
                 <span class="prize"><?php echo ($v["lt_prize_name"]); ?></span>
              </li><?php endforeach; endif; ?>
            </ul>
          </div>    
     </div> 
   </div>
   </div>
   <div class="process_5">
   </div>
   <div class="spacer"></div>
</div>
<form action="<?php echo U('Lottery/DoSaveAddress');?>" name="address_form" id="address_form" onsubmit="return check_save(this)" method="post">
<div class="address_bg" id="address_layer">
   <div class="add_ress">
      <div class="address">
         <h3><!-- <span class="add_close">X</span> -->填写收货地址</h3>
         <ul>
             <li>
             <input type="hidden" name="record_id" id="record_id" value="">
                <span><font>*</font>收货人</span>
                 <?php if($session['record_data']['real_name'] != ''): else: echo ($user_info["real_name"]); endif; ?>
                <?php if(!empty($_SESSION['record_data']['real_name'])){ $real_name = $_SESSION['record_data']['real_name']; }else{ $real_name = $user_info['real_name']; } if(!empty($_SESSION['record_data']['address'])){ $address = $_SESSION['record_data']['address']; } if(!empty($_SESSION['record_data']['phone'])){ $phone = $_SESSION['record_data']['phone']; }else{ $phone = $user_info['phone']; } ?>
                <span><input type="text" class="add_people add_Line" name="real_name" id="real_name" value="<?php echo ($real_name); ?>"/></span>
             </li>
             <li>
                <span><font>*</font>详细地址</span>
                <span><textarea cols="" rows="" class="add_xx add_Line" name="address" id="address"><?php echo ($address); ?></textarea></span>
             </li>
             <li>
                <span><font>*</font>联系方式</span>
                <span><input type="text"  class="add_email add_Line" name="phone" id="phone" size="150" value="<?php echo ($phone); ?>" /></span>
             </li>
             <li class="a_btn"><input  type="submit" class="add_Btn" value="确认提交" /></li>
         </ul>
      </div>
   </div>
</div>
</form>
<div class="ny_bottom">
  <div class="ny_middle">
     <empty name="think.session.user_id"><span class="zc_btn"><a href="<?php echo U('user/register');?>"><img src="__PUBLIC__/images/ny_zc.png" width="170"></a></span>
     <span class="zc_mark">提示：所有奖励金额投资后可提现</span>
  </div>
</div>
<p class="tj_total">
         <script type="text/javascript">
         var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
         document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3Fe29aec143162fc1ca52145771a9ee305' type='text/javascript'%3E%3C/script%3E"));
         </script>          
         <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_5958842'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s19.cnzz.com/stat.php%3Fid%3D5958842%26show%3Dpic' type='text/javascript'%3E%3C/script%3E"));</script>              
</p>
</body>
</html>